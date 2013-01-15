<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertrain extends MY_Controller {

	/* static 广告滚动模式 */
	private static $ADROLL_MODE = array (
						'1'	=> '顺序',
						'2'	=> '随机'
				);
	/* static 广告系列使用状态 */
	private static $STATE = array (
						'0' => '暂停中',
						'1' => '投放中',
						'2' => '已结束',
						'3' => '已废弃'
				);
	
	function __construct() {
		parent::__construct();
		
		/* 
		 * TODO: 登录验证
		 */
	}
	
	/* 默认显示 广告系列列表 */
	public function index()
	{
		$this->load->model('admin/model_advertrain');
		$this->load->library('session');
		
		/* 查询参数 */
		$mindate = $this->input->get('mindate');
		$maxdate = $this->input->get('maxdate');
		$state = $this->input->get("state");
		$page = $this->input->get("page");
		
		$conditions = array();
		if ( ! empty($mindate) ) {
			$conditions[] = "`add_time` >= '" . strtotime($mindate) . "'";
		}
		if ( ! empty($maxdate) ) {
			$conditions[] = "`add_time` <= '" . strtotime($maxdate) . "'";
		}
		if ($state !== false && $state != '-') {
			$conditions[] = "`state` = '{$state}'";
		}
		
		/* 广告列表系列总计 */
		$total = $this->model_advertrain->get_advertrains_total_by_conditions($conditions);
		
		/* 分页信息 */
		$persize = 100;
		if ( $page === false || intval($page) <= 0) { $page = 1; }
		$pagesize = ceil($total / $persize);
		$offset = ($page - 1) * $persize;
		$length = $persize;
		
		/* 广告系列列表分页详情 */
		$advertrains = $this->model_advertrain->get_advertrains_detailinfo_by_conditions_limit($conditions, $offset, $length);
		if ($advertrains) {
			foreach ($advertrains as &$adtrain) {
				if (isset(Advertrain::$ADROLL_MODE[$adtrain['roll_type']])) {
					$adtrain['roll_type_str'] = Advertrain::$ADROLL_MODE[$adtrain['roll_type']];
				} else {
					$adtrain['roll_type_str'] = 'unknow';
				}
				
				$adtrain['option_str'] = '';
				if ($adtrain['is_default'] == '1') {
					$adtrain['option_str'] .= '默认, ';
				}
				if ($adtrain['is_share'] == '1') {
					$adtrain['option_str'] .= '共享, ';
				}
				if ($adtrain['is_cutable'] == '1') {
					$adtrain['option_str'] .= '可切换';
				}
				$adtrain['option_str'] = trim($adtrain['option_str'], ', ');
				
				$adtrain['add_time_str'] = date('Y年m月d日', $adtrain['add_time']);
				
				$adtrain['state_str'] = Advertrain::$STATE[$adtrain['state']];
			}
			unset($adtrain);
		}
		
		/* 显示分页html代码 */
		$page_html = page_html($pagesize, $page);
		
		$data = array (
				'advertrains'		=> $advertrains,
				'states'		=> array( '-' => '所有状态') + Advertrain::$STATE,
				'mindate'		=> $mindate,
				'maxdate'		=> $maxdate,
				'state'			=> $state,
				'page_html'		=> $page_html
		);
		
		/* 显示新增或修改等操作完成后 提示消息 */
		if ( ($msg = $this->session->flashdata('msg')) ) {
			$data['msg'] = $this->session->flashdata('msg');
		}

		$this->load->view("admin/advertrain/index", $data);
	}
	
	/* 新建广告系列 */
	public function newone() {
		
		$this->load->library('form_validation');
		$this->load->model("admin/model_advertrain");
		
		$data = array (
				'adrollmodes' 	=> Advertrain::$ADROLL_MODE,
				'validads'	=> $this->model_advertrain->get_adverts_by_valid(),
				'validsites'	=> $this->model_advertrain->get_websites_by_valid()
		);
		
		/* form 提交 */
		if ($this->input->post('__submit') == '1') {
			/* 验证 规则
			 * 广告系列名称：非空
			 * 广告滚动模式：非空
			 */
			$error_str = '';
			
			$this->form_validation->set_rules('trainame', '广告系列名称', 'trim|required');
			$this->form_validation->set_rules('rollmode', '广告滚动模式', 'required');
			
			if ($this->form_validation->run() == FALSE || $error_str ) {
				$data['msg'] = array('error', validation_errors() . $error_str);
				
				$this->load->view("admin/advertrain/newone", $data);
			} else {
				$insert_fields = array(
							'train_name'	=> trim($this->input->post('trainame')),
							'roll_type'	=> $this->input->post('rollmode'),
							'add_time'	=> time()
				);
				if ($this->input->post('asdefault') && in_array('1', $this->input->post('asdefault'))) {
					$insert_fields['is_default'] = '1';
				}
				if ($this->input->post('asshare') && in_array('1', $this->input->post('asshare'))) {
					$insert_fields['is_share'] = '1';
				}
				if ($this->input->post('ascutable') && in_array('1', $this->input->post('ascutable'))) {
					$insert_fields['is_cutable'] = '1';
				}
				
				/* 数据库 insert 操作 */
				if ($trainid = $this->model_advertrain->insert($insert_fields) ) {
					/* 广告系列-广告 */
					$ads = $this->input->post('ads');
					if ($ads != false && ($ads = explode('|', $ads)) ) {
						for ($i = 0, $length = count($ads); $i < $length; $i++) {
							$ad = explode('-', $ads[$i]);
							if ($ad && count($ad) == 3) {
								$ad_fields['ad_train_id'] 	= $trainid;
								$ad_fields['advertise_id'] 	= $ad[0];
								$ad_fields['max_ip'] 		= $ad[1];
								$ad_fields['max_pv'] 		= $ad[2];
								$ad_fields['sort']		= $i + 1;
							}
							
							$this->model_advertrain->insert_advert($ad_fields);
						}
					}
					
					/* 广告系列-网站 */
					$sites = $this->input->post('sites');
					if ($sites != false && ($sites = explode('|', $sites)) ) {
						$this->model_advertrain->update_website_advertrain($trainid, $sites);
					}
					
					/* 广告系列-默认 */
					if (isset($insert_fields['is_default']) && $insert_fields['is_default'] == '1') {
						$this->model_advertrain->update_field_default($trainid);
					}
					
					redirect('admin/advertrain');
				} else {
					/* 插入 失败 */
					show_error("Oop!");
				}
			}
		} else {
			
			$this->load->view("admin/advertrain/newone", $data);
		}
	}
	
	/* 更改广告系列投放状态 */
	public function state($id) {
		$ids = $this->input->post('ids');
		$method = $this->input->post('method');
		
		/* 允许的操作 */
		$methods = array ('pause', 'use', 'over', 'trash');
		
		if ( ! empty($ids) && in_array($method, $methods) ) {
			$ids = explode(',', $ids);
			if (is_array($ids) && ! empty($ids)) {
				$this->load->model('admin/model_advertrain');
				
				/* 字段更新 */
				$fields = array('state' => 0);
				
				if ($method == 'use') {
					$fields['state'] = 1;
				}
				if ($method == 'over') {
					$fields['state'] = 2;
				}
				if ($method == 'trash') {
					$fields['state'] = 3;
				}
				
				foreach ($ids as $id) {
					$this->model_advertrain->update_id($id, $fields);
				}
			}
		}
		
		redirect('admin/advertrain');
	}	
	
	public function get_adver()
	{
		$adver_id = $this->input->get('id');
		$this->load->model('admin/model_advertrain');
		$adver_list = $this->model_advertrain->get_adver_list($adver_id);

		exit(json_encode($adver_list));	
	}
	
	public function update_adver_sort()
	{
		$adver_list = $this->input->post('ads');
		if( !empty($adver_list) )
		{
			$this->load->model('admin/model_advertrain');
			$adver_row =  explode("|",$adver_list);
			for ($i = 0, $length = count($adver_row); $i < $length; $i++) 
			{
				$ads = explode("-",$adver_row[$i]);
				if ($ads && count($ads) == 3)
				{
					$data = array(
						'sort' => $i+1,
						'max_ip' => $ads[1],
						'max_pv' => $ads[2]
					);	
					
					$id = array(
						'id'=> $ads[0]
					);
					$this->model_advertrain->update_adver_sort($data, $id);
				}
			}
			exit("修改成功");
		}
		else
		{
			exit("修改失败");
		}
	}
	
	public function get_web()
	{
		$web_id = $this->input->get('id');
		$this->load->model('admin/model_advertrain');
		$web_list = $this->model_advertrain->get_web_list($web_id);
		
		exit(json_encode($web_list));	
	}
	
	public function modify($id)
	{
		if( is_numeric($id) )
		{
			$this->load->model('admin/model_advertrain');

			$data = $this->model_advertrain->get_advertise_train_by_id($id);
			$data['adrollmodes'] = Advertrain::$ADROLL_MODE;
			$data['id'] = $id;
			$this->load->view("admin/advertrain/modify", $data);
		}
	}
	
	public function update_train($id)
	{
		if( is_numeric($id) )
		{
			$train_name = $this->input->post('trainame');
			$roll_type	= $this->input->post('rollmode');
			$is_default = $this->input->post('asdefault');
			$is_share = $this->input->post('asshare');
			$is_cutable = $this->input->post('ascutable');
			
			if( empty($is_default) )
			{
				$is_default = 0;
			}
			if( empty($is_share) )
			{
				$is_share = 0;
			}
			if( empty($is_cutable) )
			{
				$is_cutable = 0;
			}
			
			if( empty($train_name) or empty($roll_type) )
			{
				redirect(site_url('admin/advertrain'));
			}
			
			$data = array(
				'train_name' => $this->db->escape($train_name),
				'roll_type' => $roll_type,
				'is_default' => $is_default,
				'is_share' => $is_share,
				'is_cutable' => $is_cutable
			);
			
			$id = array(
				'id' => $id
			);
			
			$this->load->model('admin/model_advertrain');
			$this->model_advertrain->update_adver_train($data, $id);
			redirect(site_url('admin/advertrain'));
		}
	}
}

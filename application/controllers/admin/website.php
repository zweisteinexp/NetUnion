<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Website extends MY_Controller {

	/* static 网站组配置 */
	private static $GROUP_TYPE;
	/* static 结算周期 */
	private static $SETTLE_TYPE = array(
						'1' => '日结',
						'2' => '周结',
						'3' => '月结'
				);	
	/* static 合作状态 */
	private static $COOPERATIVE = array(
						'0' => '暂停中',
						'1' => '合作中'
				);
	/* static 验证状态 */
	private static $STATE = array(
						'1' => '已验证',
						'2' => '验证失败'
				);

	/* 分成比例单位，即 每XIP */
	private static $COST_RATE_UNIT;

	function __construct() {
		parent::__construct();
		
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>		1,
			'detail' =>		1,
			'modify' =>		2,
			'movegroup' =>		2,
			'ratemodify' =>		3,
			'cooperative' =>	4
		);
		
		parent::check_permissions();
		
		Website::$GROUP_TYPE = $this->config->item('website_group');
		Website::$COST_RATE_UNIT = $this->config->item('website_cost_rate_unit');
	}
	
	/* 默认显示 站点列表 */
	public function index()
	{
		$this->load->model('admin/model_website');
		
		/* 查询参数 */
		$groupid = $this->input->get("groupid");
		$userid = $this->input->get("userid");
		$cooperative = $this->input->get("cooperative");
		$start_date = $this->input->get("start_date");
		$end_date = $this->input->get("end_date");
		$page = $this->input->get("page");
		
		$conditions = array();
		if ($groupid !== false && $groupid != '-') {
			$conditions[] = "`group_id` = '{$groupid}'";
		}
		if ($userid !== false && ! empty($userid)) {
			$conditions[] = "`user_name` = '{$userid}'";
		}
		if ($cooperative !== false && $cooperative != '-') {
			$conditions[] = "`is_cooperative` = '{$cooperative}'";
		}
		if ($start_date !== false && ! empty($start_date) ) {
			$conditions[] = "`add_time` >= '" . strtotime($start_date . '00:00:00') . "'";
		}
		if ($end_date !== false && ! empty($end_date) ) {
			$conditions[] = "`add_time` <= '" . strtotime($end_date . '23:59:59') . "'";
		}
		
		/* 站点总计 */
		$total = $this->model_website->get_websites_total_by_conditions($conditions);
		
		/* 分页信息 */
		$persize = 100;
		if ( $page === false || intval($page) <= 0) { $page = 1; }
		$pagesize = ceil($total / $persize);
		$offset = ($page - 1) * $persize;
		$length = $persize;
		
		/* 站点列表详情 */
		$websites = $this->model_website->get_websites_detailinfo_by_conditions_limit($conditions, $offset, $length);
		if ($websites) {
			foreach ($websites as &$web) {
				$web['state_str'] = Website::$STATE[$web['state']] . ', ' . Website::$COOPERATIVE[$web['is_cooperative']];
				
				$web['cooperative_str'] = Website::$COOPERATIVE[$web['is_cooperative']];
				
				$web['settle_str'] = Website::$SETTLE_TYPE[$web['settle_type']];
				
				$web['types_str'] = '';
				if ( ! empty($web['types'])) {
					$type_ids = explode(',', $web['types']);
					
					/* 配置文件 取得 网站的类别信息 */
					$type_names = $this->config->item('website_type');
					
					foreach ($type_ids as $index) {
						if ( ! empty($web['types_str'])) {
							$web['types_str'] .= ', ';
						}
						$web['types_str'] .= isset($type_names[$index]) ? $type_names[$index] : '';
					}
				}
								
				/* 网站组别信息 */
				$group_types = $this->config->item('website_group');
				$web['group_str'] = isset($group_types[$web['group_id']]) ? $group_types[$web['group_id']] : '';
	
				$web['add_time_str'] = $web['add_time'] ? date("Y年m月d日", $web['add_time']) : "";
				/* 用于查询扣量，分成记录 */
				$websiteids[] = $web['id'];
			}
			unset($web);
		}
		
		/* 查询扣量，分成记录 */
		$now = date('Y-m-d H:i:s');
		$tomorrow = date('Y-m-d 00:00:00', strtotime('+1 day'));
		if (isset($websiteids)) {
			$now_rates = $this->model_website->get_ratehistorys_by_date($websiteids, $now);
			$tomorrow_rates = $this->model_website->get_ratehistorys_by_date($websiteids, $tomorrow, $tomorrow);
			for ($i = 0, $length = count($websites); $i < $length; $i++) {
			
				/* 在用 比例 记录 */
				for ($j = 0, $length_j = count($now_rates); $j < $length_j; $j++) {
					if ($websites[$i]['id'] == $now_rates[$j]['website_id']) {
						$websites[$i]['n_cost_rate'] = number_format($now_rates[$j]['cost_rate'], 2, '.', '');
						$websites[$i]['n_deduct_rate'] = number_format($now_rates[$j]['deduct_rate']);
						break;
					}
				}
				if ($j >= $length_j) {
				
					/* 没有扣量，分成记录, 默认 */
					$rate_configs = $this->config->item('website_rate');
					$websites[$i]['n_cost_rate'] = number_format($rate_configs['cost_rate'], 2, '.', '');
					$websites[$i]['n_deduct_rate'] = number_format($rate_configs['deduct_rate']);
				}
				
				/* 待更新 比例 记录 */
				for ($k = 0, $length_k = count($tomorrow_rates); $k < $length_k; $k++) {
					if ($websites[$i]['id'] == $tomorrow_rates[$k]['website_id']) {
						$websites[$i]['t_cost_rate'] = number_format($tomorrow_rates[$k]['cost_rate'], 2, '.', '');
						$websites[$i]['t_deduct_rate'] = number_format($tomorrow_rates[$k]['deduct_rate']);
						break;
					}
				}
			}
		}
		
		/* 显示分页html代码 */
		$page_html = page_html($pagesize, $page);
		
		$data = array (
				'group_types'		=> array('-' => '--选择组别--') + Website::$GROUP_TYPE,
				'cooperatives'		=> array('-' => '--选择状态--') + Website::$COOPERATIVE,
				'cost_rate_unit'	=> Website::$COST_RATE_UNIT,
				
				'websites' 		=> $websites,
				'groupid'		=> $groupid,
				'userid'		=> $userid,
				'cooperative'		=> $cooperative,
				'start_date'		=> $start_date,
				'end_date'		=> $end_date,
				'page_html'		=> $page_html
		);
		
		$this->load->view("admin/website_index", $data);
	}
	
	/* 站点详情 */
	public function detail($id = 0) {
		if (intval($id) == 0) { redirect('admin/website'); }
		
		$this->load->model("admin/model_website");
		
		$website = $this->model_website->get_website_by_id($id);
		
		$website['state_str'] = Website::$STATE[$website['state']];
		$website['cooperative_str'] = Website::$COOPERATIVE[$website['is_cooperative']];
		$website['settle_str'] = Website::$SETTLE_TYPE[$website['settle_type']];
		
		$website['options_str'] = sprintf('预付[%s]', $website['is_imprest'] == '1' ? '支持' : '不支持');
		
		/* 没有扣量，分成记录, 默认 */
		$rate_configs = $this->config->item('website_rate');
		$cost_rate_unit = Website::$COST_RATE_UNIT;
		
		(trim($website['deduct_rate'], ' .,0') == '')
			? $website['deduct_rate_str'] = number_format($rate_configs['deduct_rate']) . ' %'
			: $website['deduct_rate_str'] = number_format($website['deduct_rate']) . ' %' ;
		(trim($website['cost_rate'], ' .,0') == '')
			? $website['cost_rate_str'] = number_format($rate_configs['cost_rate'], 2, '.', '') . " /{$cost_rate_unit}IP"
			: $website['cost_rate_str'] = number_format($website['cost_rate'], 2, '.', '') . " /{$cost_rate_unit}IP" ;
		
		$website['types_str'] = '';
		if ( ! empty($website['types'])) {
			$type_ids = explode(',', $website['types']);
			
			/* 配置文件 取得 网站的类别信息 */
			$type_names = $this->config->item('website_type');
			
			foreach ($type_ids as $index) {
				if ( ! empty($website['types_str'])) {
					$website['types_str'] .= ', ';
				}
				$website['types_str'] .= isset($type_names[$index]) ? $type_names[$index] : '';
			}
		}
		
		/* 网站组别信息 */
		$group_types = $this->config->item('website_group');
		$website['group_str'] = isset($group_types[$website['group_id']]) ? $group_types[$website['group_id']] : '';
		
		$website['add_time_str'] = date('Y-m-d H:i:s', $website['add_time']);

		$data['website'] = $website;
		
		$this->load->view("admin/website_detail", $data);
	}
	
	/* 更新网站站点 */
	public function modify($id = 0) {
		if (intval($id) == 0) { redirect('admin/website'); }
		
		$this->load->model("admin/model_website");
		$this->load->library('form_validation');
		
		/* 网站站点 信息 */
		$website = $this->model_website->get_website_by_id($id);
		if ( empty($website)) { redirect('admin/website'); }
		
		$data = array(
				'id'		=> $id,
				'website'	=> &$website,
				'group_types'	=> array('-' => '--选择组别--') + Website::$GROUP_TYPE,
				'settle_types'	=> Website::$SETTLE_TYPE,
				'website_types'	=> explode(',', $website['types']),
				'type_names'	=> $this->config->item('website_type')
		);
		
		/* 确定 二次提交 */
		if ($this->input->post('__submit') == '1') {
			/* 验证 规则
			 * 网站名称：非空
			 * 状态：合作站点需是验证通过的
			 * 网站类别：最多选择两项
			 */
			$this->form_validation->set_rules('website_name', '网站名称', 'trim|required');
			$this->form_validation->set_rules('ascooperative', '合作站点', 'callback_cooperative_fail');
			$this->form_validation->set_rules('website_types', '网站类别', 'callback_types_count');
			
			$this->form_validation->set_message('types_count', '<p>网站类别最多选择两项</p>');
			$this->form_validation->set_message('cooperative_fail', '<p>合作中的站点需是验证通过的</p>');
						
			if ($this->form_validation->run() == FALSE) {				
				$data['msg'] = array('error', validation_errors());
				
				$this->_set_data($data);
				$this->load->view("admin/website_modify", $this->_DATA);
			} else {
				/* 更新前 网站站点 信息 */
				$old_website = $website;
				
				if ($old_website) {
					$update_fields = array(
								'website_name'	=> trim($this->input->post('website_name')),
								'description'	=> trim($this->input->post('description'), TRUE),
								'icp'		=> trim($this->input->post('icp')),
								'is_imprest'	=> '0',
								'is_cooperative'=> '0',
								'state'		=> '2',
								'group_id'	=> '0',
								'settle_type'	=> $this->input->post('settle_type')
					);
					
					if ($this->input->post('asimprest') && current($this->input->post('asimprest')) == '1') {
						$update_fields['is_imprest'] = '1';
					}
					
					if ($this->input->post('ascooperative') && current($this->input->post('ascooperative')) == '1') {
						$update_fields['is_cooperative'] = '1';
					}
					
					if ($this->input->post('state') && current($this->input->post('state')) == '1') {
						$update_fields['state'] = '1';
					}

					if (current($this->input->post('group')) != '-') {
						$update_fields['group_id'] = $this->input->post('group');
					}
										
					/* 数据库 update 操作 */
					if ($this->model_website->update($id, $update_fields)) {
						$type_ids = $this->input->post('website_types');
											
						if ( is_array($type_ids) && ! empty($type_ids)) {
							/* 修改 网址类别 */
							$this->model_website->delete_types($id, $type_ids)
								&& $this->model_website->insert_types($id, $type_ids);
						}
						
						redirect('admin/website');
					} else {
						/* 更新 失败 */
						show_error("Oop!");
					}
				} else {
					/* 无网站站点信息 */
					show_error("Oop!");
				}
			}
		} else {
			/* 首次请求 显示更新页面 */
			$this->load->view("admin/website_modify", $data);
		}
	}
	
	/* 站点 暂停合作, 重新合作 */
	public function cooperative() {
		$ids = $this->input->post('ids');
		$method = $this->input->post('method');
		
		if ( ! empty($ids) && in_array($method, array('start', 'end')) ) {
			$ids = explode(',', $ids);
			if (is_array($ids) && ! empty($ids)) {
				$this->load->model('admin/model_website');
				
				/* 字段更新 */
				$fields = array('is_cooperative' => 0);
				if ($method == 'start') {
					$fields = array('is_cooperative' => 1);
				}
				
				foreach ($ids as $id) {
					$this->model_website->update($id, $fields);
				}
			}
		}
		
		redirect('admin/website');
	}
	
	/* 站点移动网站组 */
	public function movegroup() {
		$ids = $this->input->post('ids');
		$groupid = intval($this->input->post('groupid'));
		
		if ( ! empty($ids) && $groupid >= 1) {
			$ids = explode(',', $ids);
			if (is_array($ids) && ! empty($ids)) {
				$this->load->model('admin/model_website');
				
				/* 字段更新 */
				$fields = array('group_id' => $groupid);
				foreach ($ids as $id) {
					$this->model_website->update($id, $fields);
				}
			}
		}
		
		redirect('admin/website');
	}
	
	/* 扣量, 分成比例修改-ajax */
	public function ratemodify($id = 0) {
		$ratename = $this->input->post('name');
		$ratevalue = floatval($this->input->post('value'));
		
		if ( ! in_array($ratename, array('cost', 'deduct')) || $ratevalue < 0 ) {
			exit("false");
		}
		
		$this->load->model('admin/model_website');
		
		$now = date('Y-m-d H:i:s');
		$tomorrow = date('Y-m-d 00:00:00', strtotime('+1 day'));
		
		/* 今日是否有修改历史 */
		$tomorrow_rate = $this->model_website->get_ratehistorys_by_date($id, $tomorrow, $tomorrow);
		$tomorrow_rate = isset($tomorrow_rate[0]) ? $tomorrow_rate[0] : array();
		
		if ( empty($tomorrow_rate)) {
		
			/* 今日无修改记录，新增 */
			$now_rate = $this->model_website->get_ratehistorys_by_date($id, $now);
			if ( ! empty($now_rate)) {
				$now_rate = $now_rate[0];
				$this->model_website->update_rate_history($now_rate['id'], array (
								'end_time' => date('Y-m-d 23:59:59', strtotime($now))
							)
						);
			} else {
				/* 配置文件默认 */
				$rate_configs = $this->config->item('website_rate');
				$now_rate = array (
							'cost_rate'   => $rate_configs['cost_rate'],
							'deduct_rate' => $rate_configs['deduct_rate']
						);
			}
			
			unset($now_rate['id']);
			
			/* 设置 比例 参数值 */
			$tomorrow_rate = & $now_rate;
			$tomorrow_rate['website_id'] = $id;
			if ($ratename == 'cost') {
				$tomorrow_rate['deduct_rate'] = $ratevalue;
			}
			if ($ratename == 'deduct') {
				$tomorrow_rate['deduct_rate'] = $ratevalue;
			}
			$tomorrow_rate['valid_time'] = $tomorrow;
			
			$this->model_website->insert_rate_history($tomorrow_rate);
		} else {
			if ($ratename == 'cost') {
				$fields['cost_rate'] = $ratevalue;
			}
			if ($ratename == 'deduct') {
				$fields['deduct_rate'] = $ratevalue;
			}
			
			/* 今日有修改，更新记录 */
			$this->model_website->update_rate_history($tomorrow_rate['id'], $fields) ;
		}
		
		exit("true");
	}
	
	/* 验证
	 * 网站类别 最对选择两项
	 */
	public function types_count($types) {
		if (count($types) > 2) {
			return false;
		}
		return true;
	}
	
	/* 验证
	 * 合作站点 验证通过
	 */
	public function cooperative_fail($ascooperative) {
		if (is_array($ascooperative) && current($ascooperative) == '1') {
			if ($this->input->post('state') === false) {
				return false;
			}
		}
		return true;
	}
}

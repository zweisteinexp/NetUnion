<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advert extends MY_Controller {

	/* static 定价模式 */
	private static $COST_MODE;
	/* static 广告展现方式 */
	private static $SHOW_MODE = array (
						'1'	=> '文本',
						'2'	=> '图片',
						'3'	=> 'flash'
				);
	/* static 广告使用状态 */
	private static $STATE = array (
						'0' => '暂停中',
						'1' => '投放中',
						'2' => '已结束',
						'3' => '已废弃'
				);
	/* 默认新添广告的广告主 */
	private static $DEFAULT_USER;
	
	/* 广告链接地址匹配正则 */
	private $adurl_reg = "@(https?\\://)?([\\w-]+\\.)+[\\w-]+(/[\\w- \\./?%&=]*)?@";

	function __construct() {
		parent::__construct();
		
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>		1,
			'detail' =>		1,
			'newone' =>		2,
			'modify' => 		2,
			'state' => 		3
		);
		
		parent::check_permissions();
		
		Advert::$COST_MODE = $this->config->item('advert_cost_mode');
		Advert::$DEFAULT_USER = $this->config->item('advert_default_user');
	}
	
	/* 默认显示 广告列表 */
	public function index()
	{
		$this->load->model('admin/model_advert');
		
		/* 查询参数 */
		$costmode = $this->input->get("costmode");
		$username = $this->input->get("username");
		$mindate = $this->input->get("mindate");
		$maxdate = $this->input->get("maxdate");
		$adddate = $this->input->get("adddate");
		$state = $this->input->get("state");
		$page = $this->input->get("page");
		
		$conditions = array();
		if ($costmode !== false && $costmode != '-') {
			$conditions[] = "`put_type` = '{$costmode}'";
		}
		if ( ! empty($username) ) {
			$conditions[] = "`user_name` = '{$username}'";
		}
		if ( ! empty($mindate) ) {
			$conditions[] = "`min_date` >= '" . strtotime($mindate) . "'";
		}
		if ( ! empty($maxdate) ) {
			$conditions[] = "`max_date` <= '" . strtotime($maxdate) . "'";
		}
		if ( ! empty($adddate) ) {
			$conditions[] = "`add_time` >= '" . strtotime($adddate) . "'";
		}
		if ($state !== false && $state != '-') {
			$conditions[] = "`state` = '{$state}'";
		}
		
		/* 广告列表总计 */
		$total = $this->model_advert->get_adverts_total_by_conditions($conditions);
		
		/* 分页信息 */
		$persize = 100;
		if ( $page === false || intval($page) <= 0) { $page = 1; }
		$pagesize = ceil($total / $persize);
		$offset = ($page - 1) * $persize;
		$length = $persize;
		
		/* 广告列表分页详情 */
		$adverts = $this->model_advert->get_adverts_detailinfo_by_conditions_limit($conditions, $offset, $length);
		if ($adverts) {
			foreach ($adverts as &$ad) {
				if (isset(Advert::$SHOW_MODE[$ad['advertise_type']])) {
					$ad['showmode_str'] = Advert::$SHOW_MODE[$ad['advertise_type']];
				} else {
					$ad['showmode_str'] = 'unknow';
				}
				if (isset(Advert::$COST_MODE[$ad['put_type']])) {
					$ad['costmode_str'] = key(Advert::$COST_MODE[$ad['put_type']]);
				} else {
					$ad['costmode_str'] = 'unkonw';
				}
				
				$ad['minmaxdate_str'] = date('Y年m月d日', $ad['min_date']) . '~' . date('Y年m月d日', $ad['max_date']);
				$ad['adddate_str'] = date('Y年m月d日', $ad['add_time']);
				
				$ad['state_str'] = Advert::$STATE[$ad['state']];
			}
			unset($ad);
		}
		
		/* 显示分页html代码 */
		$page_html = page_html($pagesize, $page);
		
		$data = array (
				'adverts'		=> $adverts,
				'costmodes'		=> array( '-' => array ( '--所有模式--' => 'ALL' ) ) + Advert::$COST_MODE,
				'states'		=> array( '-' => '--所有状态--') + Advert::$STATE,
				'costmode'		=> $costmode,
				'username'		=> $username,
				'mindate'		=> $mindate,
				'maxdate'		=> $maxdate,
				'adddate'		=> $adddate,
				'state'			=> $state,
				'page_html'		=> $page_html
		);

		$this->load->view("admin/advert_index", $data);
	}
	
	/* 广告详情 */
	public function detail($id = 0) {
		/*
		 * TODO:
		 */
		redirect('admin/advert');
	}
	
	/* 新建广告 */
	public function newone() {
		$this->load->library('form_validation');
		$this->load->model("admin/model_advert");
		
		$data = array(
				'defaultuser' 	=> Advert::$DEFAULT_USER,
				'costmodes'	=> Advert::$COST_MODE,
				'showmodes'	=> Advert::$SHOW_MODE
		);
		
		/* form 提交 */
		if ($this->input->post('__submit') == '1') {
			/* 验证 规则
			 * 广告主用户：非空
			 * 广告链接名称：非空
			 * 链接地址：非空，格式正确
			 * 展示模式：非空
			 * 定价模式：非空
			 * 访问限制：非空，非零整数
			 * 投放日期范围：非空，起始日期不小于今日，结束日期大于起始日期
			 */
			$error_str = '';
			
			$this->form_validation->set_rules('username', '广告主用户', 'trim|required');
			$this->form_validation->set_rules('adname', '广告链接名称', 'trim|required');
			$this->form_validation->set_rules('adurl', '链接地址', 'trim|required|regex_match[' . $this->adurl_reg . ']');
			$this->form_validation->set_rules('showmode', '展示模式', 'trim|required');
			$this->form_validation->set_rules('costmode', '定价模式', 'trim|required');			
			$this->form_validation->set_rules('maxip', '独立IP访问量', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('maxpv', '浏览量', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('mindate', '投放起始日期', 'trim|required|callback_mindate_after_today');
			$this->form_validation->set_rules('maxdate', '投放结束日期', 'trim|required|callback_maxdate_gt_mindate');
			
			$this->form_validation->set_message('mindate_after_today', '<p>起始日期不能小于今日</p>');
			$this->form_validation->set_message('maxdate_gt_mindate', '<p>结束日期要大于起始日期</p>');
			
			/* 广告主验证，必须存在且有效 */
			$aduser = $this->model_advert->get_user_by_username($this->input->post('username'));
			if ( empty($aduser) ) {
				$error_str = "<p>广告主用户无效，请确定</p>";
			}
			
			if ($this->form_validation->run() == FALSE || $error_str ) {
				$data['msg'] = array('error', validation_errors() . $error_str);
				
				$this->load->view("admin/advert_newone", $data);
			} else {
				$insert_fields = array(
							'user_id'	=> $aduser['user_id'],
							'put_type'	=> $this->input->post('costmode'),
							'advertise_type'=> $this->input->post('showmode'),
							'advertise_name'=> trim($this->input->post('adname')),
							'advertise_url'	=> trim($this->input->post('adurl')),
							'max_pv'	=> trim($this->input->post('maxpv')),
							'max_ip'	=> trim($this->input->post('maxip')),
							'min_date'	=> strtotime($this->input->post('mindate')),
							'max_date'	=> strtotime($this->input->post('maxdate')),
							'add_time'	=> time()
				);
				
				/* 数据库 insert 操作 */
				if ($this->model_advert->insert($insert_fields) ) {
					
					redirect('admin/advert');
				} else {
					/* 插入 失败 */
					show_error("Oop!");
				}
			}
		} else {
			
			/* 定价模式，展示模式 默认值 */
			$data['defcostmode'] = '1';
			$data['defshowmode'] = '3';
			
			$this->load->view("admin/advert_newone", $data);
		}
	}
	
	/* 修改广告 */
	public function modify($id = 0) {
		$this->load->model("admin/model_advert");
		$this->load->library('form_validation');
		
		/* 广告 信息 */
		$advert = $this->model_advert->get_advert_by_id($id);
		if ( empty($advert)) { redirect('admin/advert'); }
		
		/* 广告 展现模式和定价模式 */
		if (isset(Advert::$SHOW_MODE[$advert['advertise_type']])) {
			$advert['showmode_str'] = Advert::$SHOW_MODE[$advert['advertise_type']];
		} else {
			$advert['showmode_str'] = 'unknow';
		}
		if (isset(Advert::$COST_MODE[$advert['put_type']])) {
			$advert['costmode_str'] = key(Advert::$COST_MODE[$advert['put_type']]);
		} else {
			$advert['costmode_str'] = 'unkonw';
		}
		
		$data = array(
				'id'		=> $id,
				'ad'		=> &$advert,
				'states'	=> Advert::$STATE
		);
		
		/* 确定 二次提交 */
		if ($this->input->post('__submit') == '1') {
			/* 更新前 广告 信息 */
			$this->advert_old = $advert;
			
			/* 验证 规则
			 * 广告主用户：非空
			 * 广告链接名称：非空
			 * 链接地址：非空，格式正确
			 * 访问限制：非空，非零整数，不允许下调
			 * 投放日期范围：非空，结束日期不允许下调
			 * 投放状态：非空
			 */
			$error_str = '';
			
			$this->form_validation->set_rules('username', '广告主用户', 'trim|required');
			$this->form_validation->set_rules('adname', '广告链接名称', 'trim|required');
			$this->form_validation->set_rules('adurl', '链接地址', 'trim|required|regex_match[' . $this->adurl_reg . ']');
			$this->form_validation->set_rules('maxip', '独立IP访问量', 'trim|required|is_natural_no_zero|callback_gt_eq_old[maxip]');
			$this->form_validation->set_rules('maxpv', '浏览量', 'trim|required|is_natural_no_zero|callback_gt_eq_old[maxpv]');
			$this->form_validation->set_rules('maxdate', '投放结束日期', 'trim|required|callback_gt_eq_old[maxdate]');
			$this->form_validation->set_rules('state', '投放状态', 'trim|required');
			
			$this->form_validation->set_message('gt_eq_old', '<p>%s 不允许下调</p>');
			
			/* 广告主验证，必须存在且有效 */
			$aduser = $this->model_advert->get_user_by_username($this->input->post('username'));
			if ( empty($aduser) ) {
				$error_str = "<p>广告主用户无效，请确定</p>";
			}
			
			if ($this->form_validation->run() == FALSE || $error_str ) {
				$data['msg'] = array('error', validation_errors() . $error_str);
				
				$this->load->view("admin/advert_modify", $data);
			} else {
				
				if ($this->advert_old) {
					$update_fields = array(
								'user_id'	=> $aduser['user_id'],
								'advertise_name'=> trim($this->input->post('adname')),
								'advertise_url'	=> trim($this->input->post('adurl')),
								'max_pv'	=> trim($this->input->post('maxpv')),
								'max_ip'	=> trim($this->input->post('maxip')),
								'max_date'	=> strtotime($this->input->post('maxdate')),
								'state'		=> $this->input->post('state')
					);
					
					/* 数据库 update 操作 */
					if ($this->model_advert->update($id, $update_fields)) {
						
						redirect('admin/advert');
					} else {
						/* 更新 失败 */
						show_error("Oop!");
					}
				} else {
					
					show_error("Oop!");
				}
			}
		} else {
			/* 首次请求 显示更新页面 */
			$this->load->view("admin/advert_modify", $data);
		}
	}
	
	/* 更改广告投放状态 */
	public function state($id) {
		$ids = $this->input->post('ids');
		$method = $this->input->post('method');
		
		/* 允许的操作 */
		$methods = array ('pause', 'use', 'over', 'trash');
		
		if ( ! empty($ids) && in_array($method, $methods) ) {
			$ids = explode(',', $ids);
			if (is_array($ids) && ! empty($ids)) {
				$this->load->model('admin/model_advert');
				
				/* 字段更新 */
				$fields = array('state' => 0);
				$wherestr = "`state` = '1'";
				
				if ($method == 'use') {
					$fields['state'] = 1;
					$wherestr = "`state` = '0'";
				}
				if ($method == 'over') {
					$fields['state'] = 2;
					$wherestr = "`state` = '0' OR `state` = '1'";
				}
				if ($method == 'trash') {
					$fields['state'] = 3;
					$wherestr = "";
				}
				
				foreach ($ids as $id) {
					$this->model_advert->update($id, $fields, $wherestr);
				}
			}
		}
		
		redirect('admin/advert');
	}
	
	/* 验证 
	 * 投放起始日期
	 */
	public function mindate_after_today($mindate) {
		return $mindate >= date('Y-m-d');
	}
	
	/* 验证
	 * 投放结束日期
	 */
	public function maxdate_gt_mindate($maxdate) {
		$mindate = $this->input->post('mindate');
		return $mindate ? $mindate < $maxdate : false;
	}
	
	/* 验证
	 * 修改，访问限制，投放结束日期，不允许下调
	 */
	public function gt_eq_old($str, $type) {
		if (in_array($type, array('maxip', 'maxpv', 'maxdate')) && isset($this->advert_old) ) {
			switch($type) {
				case 'maxdate' : return strtotime($str) >= $this->advert_old['max_date'] ;
				case 'maxip'   : return intval($str) >= $this->advert_old['max_ip'] ;
				case 'maxpv'   : return intval($str) >= $this->advert_old['max_pv'] ;
			}
		}
		return false ;
	}
}

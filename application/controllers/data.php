<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MY_Controller {
	
	/* 网站分成单位 */
	private static $_COST_RATE_UNIT;
	
	/* 网站默认 分成 扣量 */
	private static $_WEBSITE_RATE;
	
	/* 网站主信息 */
	private $user_id;
	
	function __construct() {
		parent::__construct();
		
		parent::check_login();
		
		$this->user_id = $this->_UID;
		
		/* 设置网站分成单位 */
		Data::$_COST_RATE_UNIT = $this->config->item('website_cost_rate_unit');
		/* 设置 网站默认 分成 扣量 */
		Data::$_WEBSITE_RATE = $this->config->item('website_rate');
	}
	
	/* 默认 网站列表显示 */
	public function index() {
		$this->load->model("model_data");
		
		$websites = $this->model_data->get_websites_info_by_userid($this->user_id);
		
		$data = array('websites'	=> $websites);
		
		$this->_set_data($data);
		$this->load->view("data_index", $this->_DATA);
	}
	
	/* 显示 站点列表 */
	public function show($id = 0) {
		$id = intval($id) != 0 ? intval($id) : intval($this->input->get('id'));
		if ($id == 0) { redirect('data'); }
		
		$this->load->model("model_data");
		
		$websites = $this->model_data->get_websites_info_by_userid($this->user_id);
		
		$data = array(
				'id'	=> $id,
				'websites'	=> $websites
		);
		
		$this->_set_data($data);
		$this->load->view("data_show", $this->_DATA);
	}
	
	/* 显示 站点统计数据-ajax */
	public function ask($id = 0) {
		$id = intval($id) != 0 ? intval($id) : intval($this->input->get('id'));
		$t1 = $this->input->get('t1');
		$t2 = $this->input->get('t2');
		
		/* 若参数不合法，直接退出，显示页面处理错误 */
		if ($id == 0 || $t1 == '' || $t2 == '' || strtotime($t1) > strtotime($t2) ) {
			exit(false);
		}
		
		$this->load->model('model_data');
		
		$today = date('Y-m-d');
		if ($t1 == $t2 && $t1 == $today ) {
			/* 查询今天数据 */		
			$counts = $this->fetch_today_data($id, $today);
		} else {
			$counts = $this->fetch_history_data($id, $t1, $t2);
		}
		
		/* 填充数据 */
		for ($i = 0, $length = count($counts); $i < $length; $i++) {
			/* x-label-日期数组 */
			$dates[] 	= $counts[$i]['add_date'];
			/* y-label-1-ip数组 */
			$ips[] 		= $counts[$i]['ips'];
			/* y-label-2-click数组 */
			$clicks[] 	= $counts[$i]['clicks'];
		}
		
		/* 顺序列出 */
		if ( ! empty($counts)) {
			array_multisort($dates, $ips, $clicks);
		}
		
		exit( json_encode(
			array(
				'total'		=> array ( 
							isset($ips) ? array_sum($ips) : 0, 
							isset($clicks) ? array_sum($clicks) : 0
						),
						
				'x-date'	=> isset($dates) ? $dates : array(),
				
				'y-data'	=> array (
							isset($ips) ? $ips : array(),
							isset($clicks) ? $clicks : array()
						)
			)
		) );
	}
	
	/* 今日数据 */
	private function fetch_today_data($websiteid, $date) {
		/* ip sources 表中查找今日临时数据 */
		$counts = $this->model_data->get_website_counts_by_websiteid_from_ipsources($this->user_id, $websiteid, $date);
		
		if ( ! empty($counts) && ! empty($counts['add_date']) ) {
			/* 查找此站点的 扣量，分成 比例 */
			$rateinfo = $this->model_data->get_website_rateinfo_by_websiteid($this->user_id, $websiteid);
			
			if ( $rateinfo['deduct_rate'] == 0.0 ) {
				/* 默认 扣量 */
				$rateinfo = Data::$_WEBSITE_RATE;
			}
			
			/* 计算给用户显示的 流量 数据 */
			$counts['ips'] = intval($rateinfo['deduct_rate'] * $counts['real_ips'] / 100);
			$counts['clicks'] = intval($rateinfo['deduct_rate'] * $counts['real_clicks'] / 100);
			
			return array($counts);
		}
		
		return array();
	}
	
	/* 历史数据 */
	private function fetch_history_data($websiteid, $t1, $t2) {
		/* website_daily_stats 表中查找之前历史数据 */
		$counts = $this->model_data->get_website_counts_by_websiteid_from_daily_stats($this->user_id, $websiteid, $t1, $t2);
		
		return $counts;
	}
}

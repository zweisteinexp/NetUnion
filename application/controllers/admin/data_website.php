<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_Website extends MY_Controller {
	/* 网站默认 分成 扣量 */
	private static $_WEBSITE_RATE;
	
	function __construct() {
		parent::__construct();
		
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>		1,
			'ask' =>		1
		);
		
		parent::check_permissions();
		
		/* 设置 网站默认 分成 扣量 */
		Data_Website::$_WEBSITE_RATE = $this->config->item('website_rate');
	}
	
	/* 默认 网站列表显示 */
	public function index($id = 0) {
		
		$this->load->model("admin/model_data");
		
		$websites = $this->model_data->get_websites_info();
		
		$data = array(
				'websites'	=> $websites,
				'id'		=> intval($id) 
		);
		
		$this->load->view("admin/data_website_index", $data);
	}
	
	/* 显示 站点统计数据-ajax */
	public function ask() {
		$id = intval($this->input->get('id'));
		$t1 = $this->input->get('t1');
		$t2 = $this->input->get('t2');
		
		/* 若参数不合法，直接退出，显示页面处理错误 */
		if ($id == 0 || $t1 == '' || $t2 == '' || strtotime($t1) > strtotime($t2) ) {
			exit("false");
		}
		
		$this->load->model('admin/model_data');
		
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
			/* y-label-2-real_ip数组 */
			$real_ips[] 	= $counts[$i]['real_ips'];
			/* y-label-3-click数组 */
			$clicks[] 	= $counts[$i]['clicks'];
			/* y-label-4-real_click数组 */
			$real_clicks[] 	= $counts[$i]['real_clicks'];
		}
		/* 顺序列出 */
		if ( ! empty($counts)) {
			array_multisort($dates, $ips, $real_ips, $clicks, $real_clicks);
		}
		
		exit( json_encode (
			array (
				'total'		=> array ( 
							isset($ips) ? array_sum($ips) : 0, 
							isset($real_ips) ? array_sum($real_ips) : 0, 
							isset($clicks) ? array_sum($clicks) : 0,
							isset($real_clicks) ? array_sum($real_clicks) : 0
						),
						
				'x-date'	=> isset($dates) ? $dates : array(),
				
				'y-data'	=> array (
							isset($ips) ? $ips : array(),
							isset($real_ips) ? $real_ips : array(),
							isset($clicks) ? $clicks : array(),
							isset($real_clicks) ? $real_clicks : array()
						)
			)
		) );
	}
	
	/* 今日数据 */
	private function fetch_today_data($websiteid, $date) {
		/* ip sources 表中查找今日临时数据 */
		$counts = $this->model_data->get_website_counts_by_websiteid_from_ipsources($websiteid, $date);
		
		if ( ! empty($counts) && ! empty($counts['add_date']) ) {
			/* 查找此站点的 扣量，分成 比例 */
			$rateinfo = $this->model_data->get_website_rateinfo_by_websiteid($websiteid);
			
			if ( $rateinfo['deduct_rate'] == 0.0 ) {
				/* 默认 扣量 */
				$rateinfo = Data_Website::$_WEBSITE_RATE;
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
		$counts = $this->model_data->get_website_counts_by_websiteid_from_daily_stats($websiteid, $t1, $t2);
		
		return $counts;
	}
}

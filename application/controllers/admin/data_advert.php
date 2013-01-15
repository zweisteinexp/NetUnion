<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_Advert extends MY_Controller {
	
	function __construct() {
		parent::__construct();
		
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>		1,
			'ask' =>		1
		);
		
		parent::check_permissions();
	}
	
	/* 默认 网站列表显示 */
	public function index($id = 0) {
		
		$this->load->model("admin/model_data");
		
		$adverts = $this->model_data->get_adverts_info();
		
		$data = array (
				'adverts'	=> $adverts,
				'id' 		=> intval($id)
		);
		
		$this->load->view("admin/data_advert_index", $data);
	}
	
	/* 显示 站点统计数据-ajax */
	public function ask() {
		$id = intval($this->input->get('id'));
		$t1 = $this->input->get('t1');
		$t2 = $this->input->get('t2');
		
		/* 若参数不合法，直接退出，显示页面处理错误 */
		if ($id == 0 || $t1 == '' || $t2 == '' || strtotime($t1) > strtotime($t2) ) {
			exit(false);
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
			/* y-label-2-click数组 */
			$clicks[] 	= $counts[$i]['clicks'];
		}
		/* 顺序列出 */
		if ( ! empty($counts)) {
			array_multisort($dates, $ips, $clicks);
		}
		
		exit( json_encode (
			array (
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
	private function fetch_today_data($adid, $date) {
		/* ip sources 表中查找今日临时数据 */
		$counts = $this->model_data->get_advert_counts_by_adid_from_ipsources($adid, $date);
		
		if ( ! empty($counts) && ! empty($counts['add_date']) ) {
			
			$counts['ips'] 		= $counts['real_ips'];
			$counts['clicks'] 	= $counts['real_clicks'];
			
			return array($counts);
		}
		
		return array();
	}
	
	/* 历史数据 */
	private function fetch_history_data($adid, $t1, $t2) {
		/* advertise_daily_stats 表中查找之前历史数据 */
		$counts = $this->model_data->get_advert_counts_by_adid_from_daily_stats($adid, $t1, $t2);
		
		return $counts;
	}
}

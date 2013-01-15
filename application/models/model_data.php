<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Data extends MY_Model
{

	private $_ipsource_table = 	'`union_ip_source`';
	private $_website_table = 	'`union_website`';
	private $_website_daily_table =	'`union_website_daily_stats`';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_websites_info_by_userid($userid) {
		$sql = "SELECT `id`, `website_name`, `domain`, `icp`, `description`
		        FROM {$this->_website_table}
		        WHERE `user_id` = '{$userid}'";
		$websites = $this->get_all($sql);
		
		return $websites;
	}
	
	public function get_website_rateinfo_by_websiteid($userid, $websiteid) {
		$sql = "SELECT `id`, `deduct_rate`, `cost_rate`
		        FROM {$this->_website_table}
		        WHERE `user_id` = '{$userid}' AND `id` = '{$websiteid}'";
		$rateinfo = $this->get_row($sql);
		
		return $rateinfo;
	}
	
	public function get_website_counts_by_websiteid_from_ipsources($userid, $websiteid, $date) {
		$sql = "SELECT COUNT(`ip`) `real_ips`, SUM(`clicks`) `real_clicks`, MAX(`add_date`) `add_date`
		        FROM {$this->_ipsource_table}
		        WHERE `user_id` = '{$userid}' AND `website_id` = '{$websiteid}' AND `add_date` ='{$date}'";
		$counts = $this->get_row($sql);
		
		return $counts;
	}
	
	public function get_website_counts_by_websiteid_from_daily_stats($userid, $websiteid, $start_date, $end_date) {
		$sql = "SELECT `ips`, `clicks`, `stats_date` add_date
		        FROM {$this->_website_daily_table}
		        JOIN {$this->_website_table} ON `website_id` = {$this->_website_table}.`id`
		        WHERE `user_id` = '{$userid}' AND `website_id` = '{$websiteid}' AND `stats_date` BETWEEN '{$start_date}' AND '{$end_date}'";
		$counts = $this->get_all($sql);
		
		return $counts;
	}
}

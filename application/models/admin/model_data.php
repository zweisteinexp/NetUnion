<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Data extends MY_Model
{

	private $_ipsource_table = 	'`union_ip_source`';
	private $_website_table = 	'`union_website`';
	private $_advert_table =  	'`union_advertise`';
	private $_website_daily_table =	'`union_website_daily_stats`';
	private $_advert_daily_table = 	'`union_advertise_daily_stats`';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_websites_info() {
		$sql = "SELECT `id`, `domain`
		        FROM {$this->_website_table}";
		$websites = $this->get_all($sql);
		
		return $websites;
	}
	
	public function get_adverts_info() {
		$sql = "SELECT `id`, `advertise_name`, `advertise_url` FROM {$this->_advert_table}";
		$adverts = $this->get_all($sql);
		
		return $adverts;
	}
	
	public function get_website_rateinfo_by_websiteid($websiteid) {
		$sql = "SELECT `id`, `deduct_rate`, `cost_rate`
		        FROM {$this->_website_table}
		        WHERE `id` = '{$websiteid}'";
		$rateinfo = $this->get_row($sql);
		
		return $rateinfo;
	}
	
	public function get_website_counts_by_websiteid_from_ipsources($websiteid, $date) {
		$sql = "SELECT COUNT(`ip`) `real_ips`, SUM(`clicks`) `real_clicks`, MAX(`add_date`) `add_date`
		        FROM {$this->_ipsource_table}
		        WHERE `website_id` = '{$websiteid}' AND `add_date` ='{$date}'";
		$counts = $this->get_row($sql);
		
		return $counts;
	}
	
	public function get_website_counts_by_websiteid_from_daily_stats($websiteid, $start_date, $end_date) {
		$sql = "SELECT `ips`, `real_ips`, `clicks`, `real_clicks`, `stats_date` add_date
		        FROM {$this->_website_daily_table}
		        WHERE `website_id` = '{$websiteid}' AND `stats_date` BETWEEN '{$start_date}' AND '{$end_date}'";
		$counts = $this->get_all($sql);
		
		return $counts;
	}
	
	public function get_advert_counts_by_adid_from_ipsources($adid, $date) {
		$sql = "SELECT COUNT(`ip`) `real_ips`, SUM(`clicks`) `real_clicks`, MAX(`add_date`) `add_date`
		        FROM {$this->_ipsource_table}
		        WHERE `advertise_id` = '{$adid}' AND `add_date` ='{$date}'";
		$counts = $this->get_row($sql);
		
		return $counts;
	}
	
	public function get_advert_counts_by_adid_from_daily_stats($adid, $start_date, $end_date) {
		$sql = "SELECT `ips`, `real_ips`, `clicks`, `real_clicks`, `stats_date` add_date
		        FROM {$this->_advert_daily_table}
		        WHERE `advertise_id` = '{$adid}' AND `stats_date` BETWEEN '{$start_date}' AND '{$end_date}'";
		$counts = $this->get_all($sql);
		
		return $counts;
	}
}

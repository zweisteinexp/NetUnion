<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Website extends MY_Model
{

	private $_table =		'`union_website`';
	private $_user_table =		'`union_user`';
	private $_attribute_table =	'`union_website_attribute`';
	private $_rate_table = 		'`union_rate_history`';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_website_by_id($website_id) {
		$sql = "SELECT {$this->_table}.*, `user_name`, group_concat(`website_type_id`) `types`
		        FROM {$this->_table} 
		        LEFT JOIN {$this->_user_table} ON {$this->_table}.`user_id` = {$this->_user_table}.`user_id`
		        LEFT JOIN {$this->_attribute_table} ON {$this->_table}.`id` = {$this->_attribute_table}.`website_id`
		        WHERE `id` = '{$website_id}'
		        GROUP BY `id`";
		$website = $this->get_row($sql);
		
		return $website;
	}
	
	public function get_websites_total_by_conditions($conditions) {
		if (empty($conditions)) {
			$where_str = '';
		} else {
			$where_str = 'WHERE ' . implode(' AND ', $conditions);
		}
		
		$sql = "SELECT COUNT(`id`) `total`
		        FROM {$this->_table} 
		        LEFT JOIN {$this->_user_table} ON {$this->_table}.`user_id` = {$this->_user_table}.`user_id`
		        {$where_str}";
		$item = $this->get_row($sql);
		
		return $item && $item['total'] ? $item['total'] : 0;
	}
	
	public function get_websites_detailinfo_by_conditions_limit($conditions, $offset, $length) {
		if (empty($conditions)) {
			$where_str = '';
		} else {
			$where_str = 'WHERE ' . implode(' AND ', $conditions);
		}
		
		$sql = "SELECT `id`, `user_name`, `website_name`, `domain`, `icp`, `description`, 
			       `state`, `is_cooperative`, `settle_type`, `add_time`, `group_id`, group_concat(`website_type_id`) `types`
		        FROM {$this->_table} 
		        LEFT JOIN {$this->_user_table} ON {$this->_table}.`user_id` = {$this->_user_table}.`user_id`
		        LEFT JOIN {$this->_attribute_table} ON {$this->_table}.`id` = {$this->_attribute_table}.`website_id`
		        {$where_str}
		        GROUP BY `id`
		        LIMIT {$offset}, {$length}";
		$websites = $this->get_all($sql);
		
		return $websites;
	}
	
	public function get_ratehistorys_by_date($websiteids, $validtime, $starttime = '0000-00-00 00:00:00') {
		if (is_array($websiteids)) {
			$where_websiteid = "`website_id` IN (" . implode(', ', $websiteids) . ")";
		} else {
			$where_websiteid = "`website_id` = '{$websiteids}'";
		}
		
		$sql = "SELECT * 
		        FROM {$this->_rate_table} 
		        WHERE `valid_time` >= '{$starttime}' 
		          AND `valid_time` <= '{$validtime}' AND `end_time` >= '{$validtime}'
		          AND {$where_websiteid}";
		$rate = $this->get_all($sql);
		
		return $rate;
	}
		
	public function update($websiteid, $fields) {
		$where_str = "`id` = {$websiteid}";
		
		$sql = $this->db->update_string($this->_table, $fields, $where_str);
		return $this->db->query($sql);
	}
	
	public function update_rate_history($id, $fields) {
		$where_str = "`id` = '{$id}'";
		
		$sql = $this->db->update_string($this->_rate_table, $fields, $where_str);
		return $this->db->query($sql);
	}
	
	public function insert_rate_history($fields) {
		$sql = $this->db->insert_string($this->_rate_table, $fields);
		$this->db->query($sql);
		
		return $this->db->insert_id();
	}
	
	public function insert_types($website_id, $type_ids) {
		$fields['website_id'] = $website_id;
		
		foreach ($type_ids as $type_id) {
			$fields['website_type_id'] = $type_id;
			
			$sql = $this->db->insert_string($this->_attribute_table, $fields);
			$this->db->query($sql);
		}
	}
	
	public function delete_types($website_id, $type_ids) {
		$sql = "DELETE FROM {$this->_attribute_table} WHERE `website_id` = {$website_id}";
		return $this->db->query($sql);
	}
}

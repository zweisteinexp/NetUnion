<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Website extends MY_Model
{

	private $_table =		'`union_website`';	
	private $_attribute_table =	'`union_website_attribute`';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_websites_detailinfo_by_userid($userid) {
		$sql = "SELECT `id`, `website_name`, `domain`, `icp`, `description`, 
			       `state`, `is_cooperative`, `settle_type`, `add_time`, group_concat(`website_type_id`) `types`
		        FROM {$this->_table} 
		        LEFT JOIN {$this->_attribute_table} ON {$this->_table}.`id` = {$this->_attribute_table}.`website_id`
		        WHERE `user_id` = '{$userid}'
		        GROUP BY `id`";
		$websites = $this->get_all($sql);
		
		return $websites;
	}
	
	public function get_websites_active_by_userid($userid) {
		$sql = "SELECT `id`, `website_name`, `domain`, `icp`, `description`, `settle_type`
		        FROM {$this->_table} 
		        WHERE `user_id` = '{$userid}' AND `state` = '1' AND `is_cooperative` = '1'";
		$websites = $this->get_all($sql);
		
		return $websites;
	}
	
	public function exist_website_by_domain($domain) {
		$sql = "SELECT `id`, `website_name`, `domain` FROM {$this->_table} WHERE `domain` = '{$domain}'";
		$website = $this->get_row($sql);
		
		return empty($website) ? false : true;
	}
	
	public function get_website_by_id($userid, $website_id) {
		$sql = "SELECT `id`, `website_name`, `domain`, `icp`, `description`, group_concat(`website_type_id`) `types`
		        FROM {$this->_table} 
		        LEFT JOIN {$this->_attribute_table} ON {$this->_table}.`id` = {$this->_attribute_table}.`website_id`
		        WHERE `user_id` = '{$userid}' AND `id` = '{$website_id}'
		        GROUP BY `id`";
		$website = $this->get_row($sql);
		
		return $website;
	}
	
	public function insert($userid, $fields) {
		$fields['user_id'] = $userid;
		
		$sql = $this->db->insert_string($this->_table, $fields);
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
	
	public function update($userid, $websiteid, $fields) {
		$where_str = "`id` = {$websiteid} AND `user_id` = {$userid}";
		
		$sql = $this->db->update_string($this->_table, $fields, $where_str);
		return $this->db->query($sql);
	}
}

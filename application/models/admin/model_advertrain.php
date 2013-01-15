<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Advertrain extends MY_Model
{

	private $_table =		'`union_advertise_train`';
	private $_item_table =		'`union_advertise_train_item`';
	private $_advert_table = 	'`union_advertise`';
	private $_website_table = 	'`union_website`';
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	public function get_adverts_by_valid() {
		$sql = "SELECT `id`, `advertise_name`, `state`
		        FROM {$this->_advert_table}
		        WHERE `state` = '0' OR `state` = '1'";
		$adverts = $this->get_all($sql);
		
		return $adverts;
	}
	
	public function get_websites_by_valid() {
		$sql = "SELECT `id`, `website_name`, `domain`, `state`
		        FROM {$this->_website_table}
		        WHERE `state` = '1'";
		$websites = $this->get_all($sql);
		
		return $websites;
	}
	
	public function get_advertrains_total_by_conditions($conditions) {
		if (empty($conditions)) {
			$where_str = '';
		} else {
			$where_str = 'WHERE ' . implode(' AND ', $conditions);
		}
		
		$sql = "SELECT COUNT(`id`) `total`
		        FROM {$this->_table}
		        {$where_str}";
		$item = $this->get_row($sql);

		return $item && $item['total'] ? $item['total'] : 0;
	}
	
	public function get_advertrains_detailinfo_by_conditions_limit($conditions, $offset, $length) {
		if (empty($conditions)) {
			$where_str = '';
		} else {
			$where_str = 'WHERE ' . implode(' AND ', $conditions);
		}
		
		$sql = "SELECT *
		        FROM {$this->_table}
		        {$where_str}
		        LIMIT {$offset}, {$length}";
		$advertrains = $this->get_all($sql);

		return $advertrains;
	}
	
	public function insert($fields) {
		$sql = $this->db->insert_string($this->_table, $fields);
		$this->db->query($sql);
		
		return $this->db->insert_id();
	}
	
	public function insert_advert($fields) {
		$sql = $this->db->insert_string($this->_item_table, $fields);
		
		return $this->db->query($sql);
	}
	
	public function update_field_default($defaultid) {
		$sql = "UPDATE {$this->_table} SET `is_default` = '0' WHERE `id` != '{$defaultid}'";
		
		return $this->db->query($sql);
	}
	
	public function update_website_advertrain($advertrainid, $websiteids) {
		$sql = "UPDATE {$this->_website_table} SET `ad_train_id` = '{$advertrainid}'
		        WHERE `id` IN (" . implode(', ', $websiteids) . ") ";
		
		return $this->db->query($sql);
	}
	
	public function update_id($advertrainid, $fields) {
		$wherestr = "`id` = '{$advertrainid}'";
			
		$sql = $this->db->update_string($this->_table, $fields, $wherestr);
		
		return $this->db->query($sql);
	}
	
	public function get_adver_list($adver_id)
	{
		$sql	=	"SELECT a.id, a.max_ip,a.max_pv,b.advertise_name  FROM {$this->_item_table} AS a, {$this->_advert_table} 
					AS b  WHERE a.advertise_id = b.id AND ad_train_id = ".$adver_id." Order BY a.sort asc";
		$adver_list	=	$this->get_all($sql);
		return $adver_list;
	}
	
	public function update_adver_sort($data, $id)
	{
		$result = $this->update($this->_item_table, $data, $id);
		return $result;
	}
	
	public function get_web_list($web_id)
	{
		$sql	=	"SELECT id, domain  FROM {$this->_website_table} WHERE ad_train_id = ".$web_id;
		$web_list	=	$this->get_all($sql);
		return $web_list;
	}
	
	public function get_advertise_train_by_id($id)
	{
		$sql	=	"SELECT train_name, roll_type, is_default, is_share, is_cutable
					 FROM {$this->_table} WHERE id = ".$id;
		$adv_train	=	$this->get_row($sql);
		return $adv_train;			 
	}

	public function update_adver_train($data, $id)
	{
		$result = $this->update($this->_table, $data, $id);
		return $result;
	}

}

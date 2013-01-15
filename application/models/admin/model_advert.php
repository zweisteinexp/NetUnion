<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Advert extends MY_Model
{

	private $_table =		'`union_advertise`';
	private $_user_table =		'`union_user`';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_user_by_username($username) {
		$sql = "SELECT * 
		        FROM {$this->_user_table}
		        WHERE `user_name` = '{$username}' AND `user_type` = '1'";
		$item = $this->get_row($sql);
		
		return $item;
	}
	
	public function get_advert_by_id($id) {
		$sql = "SELECT `tad`.*, `tuser`.user_name
		        FROM {$this->_table} `tad`
		        LEFT JOIN {$this->_user_table} `tuser` ON `tad`.`user_id` = `tuser`.`user_id`
		        WHERE `id` = '{$id}'";
		$item = $this->get_row($sql);
		
		return $item;
	}
	
	public function get_adverts_total_by_conditions($conditions) {
		if (empty($conditions)) {
			$where_str = '';
		} else {
			$where_str = 'WHERE ' . implode(' AND ', $conditions);
		}
		
		$sql = "SELECT COUNT(`id`) `total`
		        FROM {$this->_table} `tad`
		        LEFT JOIN {$this->_user_table} `tuser` ON `tad`.`user_id` = `tuser`.`user_id`
		        {$where_str}";
		$item = $this->get_row($sql);
		
		return $item && $item['total'] ? $item['total'] : 0;
	}
	
	public function get_adverts_detailinfo_by_conditions_limit($conditions, $offset, $length) {
		if (empty($conditions)) {
			$where_str = '';
		} else {
			$where_str = 'WHERE ' . implode(' AND ', $conditions);
		}
		
		$sql = "SELECT `tad`.*, `tuser`.user_name
		        FROM {$this->_table} `tad`
		        LEFT JOIN {$this->_user_table} `tuser` ON `tad`.`user_id` = `tuser`.`user_id`
		        {$where_str}
		        LIMIT {$offset}, {$length}";
		$adverts = $this->get_all($sql);
		
		return $adverts;
	}
	
	public function insert($fields) {
		$sql = $this->db->insert_string($this->_table, $fields);
		$this->db->query($sql);
		
		return $this->db->insert_id();
	}
		
	public function update($advertid, $fields, $wherestr = '') {
		if ($wherestr) {
			$wherestr = "`id` = '{$advertid}' AND ({$wherestr})";
		} else {
			$wherestr = "`id` = '{$advertid}'";
		}
			
		$sql = $this->db->update_string($this->_table, $fields, $wherestr);
		return $this->db->query($sql);
	}
}

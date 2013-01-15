<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_User extends MY_Model
{
	private $_table	=	'`union_user`';
	private $info_table	=	'`union_user_data`';
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_user_info($conditions = array(), $select = " * ", $order_by = NULL, $limit = 1)
	{
		$where_sql	=	$conditions ? " WHERE " . implode(' AND ', $conditions) : " ";
		if ( !$order_by )
		{
			$order_by	=	" ORDER BY user_id ASC ";
		}
		$sql	=	"SELECT ".$select." FROM {$this->_table} " . $where_sql . $order_by;
		if ( $limit )
		{
			$sql	.=	" LIMIT ".$limit;
		}
		
		$user	=	$limit == 1 ? $this->get_row($sql) : $this->get_all($sql);
		return $user;
	}
	
	public function get_user_name($user_id)
	{
		$sql	=	"SELECT user_name,is_locked FROM {$this->_table} WHERE user_id = ".$user_id;
		$row	=	$this->get_row($sql);
		return $row;
	}
	
	public function get_user_info_by_id($user_id)
	{
		$sql	=	"SELECT a.user_name, a.email, b.true_name, b.qq, b.mobile_phone, b.identity_card ,b.obverse_identity_thumb
					, b.reverse_identity_thumb, b.bank_code, b.bank_name, b.bank_card FROM {$this->_table} AS a LEFT JOIN {$this->info_table} AS b  
					ON a.user_id = b.user_id WHERE a.user_id = ".$user_id;
		$user	=	$this->get_row($sql);
		return $user;
	}
	
	public function get_user_list($where = null, $limit = null)
	{
		$sql	=	"SELECT a.user_id, a.user_name, a.is_locked, a.user_type, b.true_name FROM {$this->_table} AS a LEFT JOIN {$this->info_table} AS b ON 
					a.user_id = b.user_id WHERE 1=1 ".$where.$limit;
		$result =	$this->get_all($sql);
		return $result;
	}
	
	public function update_user_pwd($user_pwd, $id)
	{
		$result = $this->update($this->_table, $user_pwd, $id);
		return $result;
	}
	
	public function update_user_state($user_state, $id)
	{
		$result = $this->update($this->_table, $user_state, $id);
		return $result;
	}
	
	public function check_username($username)
	{
		$sql = "SELECT COUNT(*) as total FROM {$this->_table} WHERE user_name='".$username."'";
		$result = $this->get_row($sql);
		return $result;
	}
	
	public function insert_user($user){
		$result = $this->insert($this->_table, $user);
		return $result;
	}
	
	public function insert_user_info($user_info)
	{
		$result = $this->insert($this->info_table, $user_info);
		return $result;
	}
}
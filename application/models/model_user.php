<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_User extends MY_Model
{
	private $_table	=	'`union_user`';
	private $info_table =  '`union_user_data`';
	private $email_table = '`union_send_email`';
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_user_info($user_info)
	{
		$username	=	$user_info['username'];
		$pwd	=	$user_info['pwd'];
		
		$sql	=	"SELECT * FROM {$this->_table} WHERE user_name = '".$username."' and password ='".$pwd."'";
		$user	=	$this->get_row($sql);
		
		return $user;
	}
	
	public function get_user_info_by_id($user_id)
	{
		$sql	=	"SELECT * FROM {$this->info_table} WHERE user_id = ".$user_id;
		$user	=	$this->get_row($sql);
		return $user;
	}
	
	public function check_username($username)
	{
		$sql = "SELECT COUNT(*) as total FROM {$this->_table} WHERE user_name='".$username."'";
		$result = $this->get_row($sql);
		return $result;
	}
	
	public function check_userpwd($user_pwd, $user_id)
	{
		$sql = "SELECT COUNT(*) as total FROM {$this->_table} WHERE password='".$user_pwd."' and user_id=".$user_id;
		$result = $this->get_row($sql);
		return $result;
	}
	
	public function check_user_data($user_id)
	{
		$sql = "SELECT COUNT(*) as total FROM {$this->info_table} WHERE user_id=".$user_id;
		$result = $this->get_row($sql);
		return $result;
	}
	
	public function check_useremail($username, $email)
	{
		$sql = "SELECT user_id  FROM {$this->_table} WHERE user_name='".$username."' AND email='".$email."'";
		$result = $this->get_row($sql);
		return $result;
	}
	
	public function insert_user_mail($email)
	{
		$result = $this->insert($this->email_table, $email);
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
	
	public function update_user_info($user_info, $id)
	{
		$result = $this->update($this->info_table, $user_info, $id);
		return $result;
	}
	
	public function update_user_pwd($user_pwd, $id)
	{
		$result = $this->update($this->_table, $user_pwd, $id);
		return $result;
	}
}

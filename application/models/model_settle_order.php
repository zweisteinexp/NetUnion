<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Settle_Order extends MY_Model
{
	private $_table	=	'`union_settle_order`';
	private $web_table	=	'`union_website`';
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_user_website($user_id)
	{
		$sql	=	"SELECT id, website_name FROM {$this->web_table} WHERE user_id = ".$user_id;
		$result	=	$this->get_all($sql);
		return $result;
	}
	
	public function get_user_order($user_id, $where = null, $limit = null)
	{
		$sql	=	"SELECT amount, tax_amount, remit_amount, order_date, apply_time, state FROM {$this->_table} WHERE user_id=".$user_id.$where.$limit;
		$result	=	$this->get_all($sql);
		return $result;
	}
	
	public function get_user_order_count($user_id, $where = null, $limit = null)
	{
		$sql = "SELECT COUNT(*) AS total FROM {$this->_table} WHERE user_id=".$user_id.$where.$limit;
		$result = $this->get_row($sql);
		return $result;
	}
	
	public function check_user_website($user_id, $web_id)
	{
		$sql = "SELECT COUNT(*) AS total FROM {$this->web_table} WHERE user_id=".$user_id." AND id =".$web_id;
		$result = $this->get_row($sql);
		return $result;
	}
}
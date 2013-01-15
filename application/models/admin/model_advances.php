<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Advances extends MY_Model
{
	private $_table	=	'`union_website`';
	private $user_table =  '`union_user`';
	private $advances_table =	'`union_website_advance`';
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_advances_website()
	{
		$sql	=	"SELECT a.id,b.user_name,a.website_name,a.user_id FROM {$this->_table} AS a , {$this->user_table} AS b WHERE is_imprest =1 
					 AND  a.user_id = b.user_id ";
		$result	=	$this->get_all($sql);
		return $result;
	}
	
	public function insert_advances($advances)
	{
		$result = $this->insert($this->advances_table, $advances);
		return $result;
	}
	
	public function get_advances($where = null, $limit = null)
	{
		$sql	=	"SELECT a.user_id, a.website_id, b.website_name, c.user_name, a.advances_amount, a.buyout_value, a.start_time, a.end_time, a.completed_value   
					,a.state FROM {$this->advances_table} AS a INNER JOIN {$this->_table} AS b ON a.user_id = b.user_id AND a.website_id = b.id 
					INNER JOIN {$this->user_table} AS c ON b.user_id=c.user_id WHERE 1=1 ".$where.$limit;
		$result	=	$this->get_all($sql);
		return $result;
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Settle_Order extends MY_Model
{
	private $_table	=	'`union_settle_order`';
	private $web_table	=	'`union_website`';
	private $user_table =	'`union_user`';
	private $info_table =	'`union_user_data`';
	public function __construct()
	{
		parent::__construct();
	}
		
	public function get_user_order($where = null, $limit = null)
	{
		$sql	=	"SELECT a.user_name, b.true_name, b.bank_name, b.bank_card, c.website_name, d.id, d.order_date, d.amount,d.ips ,d.clicks 
					,d.real_ips ,d.real_clicks, d.tax_amount, d.remit_amount, d.apply_time, d.state FROM {$this->user_table} AS a INNER     
					JOIN {$this->info_table} AS b ON a.user_id = b.user_id INNER JOIN {$this->web_table} AS c ON b.user_id = c.user_id INNER JOIN  
					{$this->_table} AS d ON c.user_id = d.user_id AND c.id = d.website_id  WHERE 1=1 ".$where." ORDER BY a.user_id asc
					, d.order_date asc ".$limit."";
		$result	=	$this->get_all($sql);
		return $result;
	}
	
	public function update_row($order_id)
	{
		$now_time = strtotime( date('Y-m-d H:i:s') );
		$sql	=	"UPDATE {$this->_table} SET state = 1, apply_time = ".$now_time."  WHERE id=".$order_id;
		$result	=	$this->db->query($sql);
		return $result;
	}
	
	public function update_all($order_id)
	{
		$now_time = strtotime( date('Y-m-d H:i:s') );
		$sql	=	"UPDATE {$this->_table} SET state = 1, apply_time = ".$now_time."  WHERE id in (".$order_id.")";
		$result	=	$this->db->query($sql);
		return $result;
	}
}
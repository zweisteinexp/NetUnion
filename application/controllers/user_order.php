<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 支付结算

class User_Order extends MY_Controller
{
	private $userid;
	private $user_order_state = array (0,1);
	function __construct()
	{
		parent::__construct();
		parent::check_login();
	}
	
	public function index()
	{
		
		$where = NULL;
		$limit = NULL;
		$check_get = NULL;
		$per_page = $this->input->get('per_page');
		$web_id = $this->input->get('web_id');
		$state = $this->input->get('state');
		$starttime = $this->input->get('starttime');
		$endtime = $this->input->get('endtime');
		

		$this->load->model('model_settle_order', 'user_order');	
		if( !empty($web_id) )
		{
			if( intval($web_id)==0 )
			{
				redirect("user_order");
			}
			else
			{
				$row = $this->user_order->check_user_website($this->_UID, $web_id);
				if( $row['total']==0 )
				{
					redirect("user_order");
				}
			}
			$check_get = 1;
		}
		
		if( $check_get ==1)
		{
			if( !in_array($state, $this->user_order_state) )
			{
				$state = 0;
			}
			
			if( is_numeric($web_id) )
			{
				$where.= " AND website_id =".$web_id;
			}
			if( is_numeric($state) )
			{
				$where.= " AND state =".$state;
			}
			
			if( !empty($starttime) )
			{
				$where.= " AND order_date >= '".$starttime."' ";
			}
			if( !empty($endtime) )
			{
				$where.= " AND order_date <= '".$endtime."' ";
			}
			
			
			
			$this->load->library('pagination');
			$row = $this->user_order->get_user_order_count($this->_UID, $where, $limit);
			$page_config['base_url'] = site_url("user_order/?web_id=".$web_id."&state=".$state."&starttime=".$starttime."&endtime=".$endtime."");
			$page_config['total_rows'] = $row['total'];
			$page_config['per_page'] = '10'; 
			$page_config['num_links'] = 5;
			$page_config['page_query_string'] = TRUE;
			$page_config['prev_link'] = '上一页';
			$page_config['next_link'] = '下一页';
			$page_config['first_link'] = '第一页';
			$page_config['last_link'] = '最后一页';
			$now_page = $per_page;
			if( !is_numeric($now_page) )
			{
				$now_page=0;
			}
			
			$this->pagination->initialize($page_config);
			$limit = " limit ".$now_page.",".$page_config['per_page'];
			
			$data = array(
				'user_order' => $this->user_order->get_user_order($this->_UID, $where, $limit),
				'web_id' => $web_id,
				'state' => $state,
				'starttime' => $starttime,
				'endtime' => $endtime,
				'state_order' => array(
					0 => '未结算',
					1 => '已结算'
				),
				'page_link' => $this->pagination->create_links()
			);
		}
		else
		{
			$data = array(
				'user_order' => '',
				'web_id' => '',
				'state' => '',
				'starttime' => '',
				'endtime' => '',
				'state_order' => array(
					0 => '未结算',
					1 => '已结算'
				),
				'page_link' => ''
			);
		}
		$data['website_list'] = $this->user_order->get_user_website($this->_UID);
		
		$this->_set_data($data);
		$this->load->view('user_order', $this->_DATA);
	}
}
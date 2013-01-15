<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 支付结算后台

class User_Order extends MY_Controller
{
	private $userid;
	function __construct()
	{
		parent::__construct();
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>	1,
			'update_row_state'	=>	2,
			'update_all_state' => 2
		);
		
		parent::check_permissions();
	}
	
	public function index()
	{
		$where = NULL;
		$limit = NULL;
		$user_order = NULL;
		$page_link = NULL ;
		$count_total = 0;
		$order_id_list = NULL;
		$per_page = $this->input->get('per_page');
		$web_owner = $this->input->get('web_owner');
		$state = $this->input->get('state');
		$starttime = $this->input->get('starttime');
		$endtime = $this->input->get('endtime');
		$show_type = $this->input->get('show_type');
		if ( !$starttime )
		{
			$starttime	=	date("Y-m-d", $this->_TIME - 7 * 24 * 3600);
		}
		if ( !$endtime )
		{
			$endtime	=	date("Y-m-d", $this->_TIME - 24 * 3600);
		}
		if( !empty($web_owner) )
		{
			$where.= " AND a.user_name = '".$web_owner."' ";
		}
		
		if( is_numeric($state) )
		{
			$where.= " AND d.state =".$state;
		}
		else
		{
			$where.= " AND d.state =0";
		}
		if( !empty($starttime) )
		{
			$where.= " AND d.order_date >= '".$starttime."' ";
		}
		if( !empty($endtime) )
		{
			$where.= " AND d.order_date <= '".$endtime."' ";
		}

		if( !empty($starttime) && !empty($endtime) )
		{
			$this->load->model('admin/model_settle_order', 'user_order');
			$order_list = $this->user_order->get_user_order($where);
			$this->load->library('pagination');
			$page_config['base_url'] = site_url("admin/user_order/?
			starttime=".$starttime."&amp;endtime=".$endtime."&amp;web_owner=".$web_owner."&amp;state=".$state."&amp;show_type=".$show_type."");
			$page_config['total_rows'] = count($order_list);
			$page_config['per_page'] = '10'; 
			$page_config['num_links'] = 5;
			$page_config['page_query_string'] = TRUE;
			$page_config['prev_link'] = '上一页';
			$page_config['next_link'] = '下一页';
			$page_config['first_link'] = '第一页';
			$page_config['last_link'] = '最后一页';
			$now_page = $per_page;
			if( empty($now_page) )
			{
				$now_page=0;
			}
			$this->pagination->initialize($page_config);
			$page_link = $this->pagination->create_links();
			$limit = " limit ".$now_page.",".$page_config['per_page'];
			
			$user_order = $this->user_order->get_user_order($where, $limit);
			
			if( !empty($web_owner) && !empty($order_list) )
			{
				foreach($order_list as $value)
				{
					$count_total = $count_total+ $value['amount']-$value['tax_amount'];
					$order_id_list.=$value['id'].","; 
				}
			}
		}
		$order_id_list = substr($order_id_list, 0, -1);
		$data = array(
				'user_order' => $user_order,
				'web_owner' => $web_owner,
				'state' => $state,
				'starttime' => $starttime,
				'endtime' => $endtime,
				'page_link' => $page_link,
				'count_total' => $count_total,
				'order_id_list' => $order_id_list,
				'show_type' => $show_type
			);
		
		$this->load->view('admin/user_order', $data);
	}
	
	public function update_row_state($order_id)
	{	
		if( is_numeric($order_id) )
		{
			$per_page = $this->input->get('per_page');
			$web_owner = $this->input->get('web_owner');
			$state = $this->input->get('state');
			$starttime = $this->input->get('starttime');
			$endtime = $this->input->get('endtime');
			$show_type = $this->input->get('show_type');
			
			$this->load->model('admin/model_settle_order', 'user_order');
			$result = $this->user_order->update_row($order_id);
			
			redirect("admin/user_order/index/?starttime=".$starttime."&endtime=".$endtime."&web_owner=".$web_owner."&state=".$state."&show_type=".$show_type."");
		}
		else
		{
			//redirect("admin/user_order/index/");
		}
	}
	public function update_all_state()
	{
		$order_id = NULL;
		$check_list = $this->input->post('check_list'); 
		$web_owner = $this->input->post('w_owner');
		$state = $this->input->post('s_state');
		$starttime = $this->input->post('s_time');
		$endtime = $this->input->post('e_time');
		$show_type = $this->input->post('s_type');
		$order_id_list = $this->input->post('order_id_list');
		if( !empty($check_list) )
		{
			$order_id = implode(",", $check_list);
		}
		if ( !empty($order_id_list) )
		{
			$order_id = $order_id_list;
		}
		
		$this->load->model('admin/model_settle_order', 'user_order');
		$this->user_order->update_all($order_id);

		redirect("admin/user_order/index/?starttime=".$starttime."&endtime=".$endtime."&web_owner=".$web_owner."&state=".$state."&show_type=".$show_type."");
	}
}
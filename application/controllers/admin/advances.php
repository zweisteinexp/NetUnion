<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 预付款站点

class Advances extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>	1,
			'add'	=>	2,
			'insert' => 2
		);
		
		parent::check_permissions();
	}
	
	public function index()
	{
		
		$where = null;
		$limit = null;
		
		$per_page = $this->input->get('per_page');
		
		$web_id = $this->input->get('web_id');
		$web_owner = $this->input->get('web_owner');
		$state = $this->input->get('state');
		
		if(!empty($web_id))
		{
			$where.= " AND a.website_id =".$web_id;
		}
		if(!empty($web_owner))
		{
			$where.= " AND c.user_name = '".$web_owner."'";
		}
		if(!empty($state))
		{
			$where.= " AND a.state =".$state;
		}
		
		$this->load->model('admin/model_advances', 'advances');
		$advances_list = $this->advances->get_advances($where, "");
		$this->load->library('pagination');
		$page_config['base_url'] = site_url("admin/advances/?web_id=".$web_id."&web_owner=".$web_owner."&state=".$state."");
		$page_config['total_rows'] = count($advances_list);
		$page_config['per_page'] = '10'; 
		$page_config['num_links'] = 5;
		$page_config['uri_segment'] = 4;
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
		$limit = " limit ".$now_page.",".$page_config['per_page'];
		$data = array(
			'advances_list' => $this->advances->get_advances($where, $limit),
			'status' => array(
				1 => '进行中',
				2 => '已完成',
				3 => '已超时(补量)'
			),
			'web_id' => $web_id,
			'web_owner' => $web_owner,
			'state' => $state,
			'page_link' => $this->pagination->create_links()
		);
		$this->load->view('admin/advances', $data);
		
	}
	
	public function add()
	{
		$this->load->model('admin/model_advances', 'advances');
		$data = array(
			'website_list'=> $this->advances->get_advances_website(),
			'success' => ''
		);
		$this->load->view('admin/add_advances', $data);
	}
	
	public function insert()
	{
		$web_id = $this->input->post('web_id');
		$web_user = $this->input->post('web_user');
		$pv = $this->input->post('pv');
		$amount= $this->input->post('amount');
		$starttime= $this->input->post('starttime')." 00:00:00";
		$endtime= $this->input->post('endtime')." 23:59:59";
		
		$this->load->model('admin/model_advances', 'advances');
		
		$data = array(
			'website_id' => $this->db->escape($web_id) ,
			'user_id' => $this->db->escape($web_user) ,
			'advances_amount' => $this->db->escape($amount) ,
			'buyout_value' => $this->db->escape($pv) ,
			'start_time' => $this->db->escape($starttime) ,
			'end_time' => $this->db->escape($endtime) 
		);
		$result = $this->advances->insert_advances($data);

		$show_msg = array(
			'website_list'=> $this->advances->get_advances_website(),
			'success' => '添加成功'
		);
		//redirect( site_url("admin/advances/add") );
		$this->load->view('admin/add_advances', $show_msg);
		
	}
}
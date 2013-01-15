<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 网盟平台后台管理中心登录页
 * @author kaiven
 *
 */

class User extends MY_Controller
{
	private $user_info;
	private $userstate = array(
		0 => '正常',
		1 => '锁定',
		2 => '审核中'
	);
	private $user_type = array(
		0 => '普通推广员',
		1 => '网站主',
		2 => '广告主',
		9 => '管理员'
	);
	function __construct()
	{
		parent::__construct();
		parent::check_login_for_manage();
		parent::$permissions	=	array(
			'index'	=>	1,
			'user_info' => 1,
			'user_add'	=>	2,
			'add' => 2,
			'user_pwd' => 3,
			'update_pwd' => 3,
			'state' => 4,
			'update_state' => 4
		);
		
		parent::check_permissions();
	}
	
	public function user_info($uid)
	{
		if(!empty($uid))
		{
			$this->load->model('admin/model_user', 'user');
			$data = array(
				'user_info' => $this->user->get_user_info_by_id($uid),
				'bank_list' => get_config_item('bank_list')
			);
			
			$this->load->view('admin/user_info', $data);
		}
	}
	
	public function index()
	{
		$where = null;
		$limit = null;
		$page_link = null;
		$user_list = null;
		$per_page = $this->input->get('per_page');
		$web_owner = $this->input->get('web_owner');
		$state = $this->input->get('state');
		$type = $this->input->get('type');
		if( !empty($web_owner) )
		{			
			$where.= " AND a.user_name = '".$web_owner."'";
		}
		if( is_numeric($state) )
		{
			$state=intval($state);
			$where.= " AND a.is_locked =".$state;
		}
		if( is_numeric($type) )
		{
			$type=intval($type);
			$where.= " AND a.user_type =".$type;
		}
		
		$this->load->model('admin/model_user', 'user');
		$user_list = $this->user->get_user_list($where);
		$this->load->library('pagination');
		$page_config['base_url'] = site_url("admin/user/?web_owner=".$web_owner."&amp;state=".$state."&amp;type=".$type."");
		$page_config['total_rows'] = count($user_list);
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
		$user_list = $this->user->get_user_list($where, $limit);
		
		$data = array(
			'user_list' => $user_list,
			'user_state' => $this->userstate,
			'user_type' => $this->user_type,
			'web_owner' => $web_owner,
			'state' => $state,
			'type' => $type,
			'page_link' => $page_link,
			'per_page' => $per_page
		);
		
		$this->load->view('admin/user', $data);
	}
	
	public function user_pwd($user_id)
	{
		if( is_numeric($user_id) )
		{
			$this->load->model('admin/model_user', 'user');
			$data = $this->user->get_user_name($user_id);
			$data['user_id'] = $user_id;
			$data['msg'] = '';
			$this->load->view('admin/pwd', $data);
		}
	}
	
	public function update_pwd()
	{
		$user_id = $this->input->post('user_id');
		$pwd = $this->input->post('pwd');
		$username = $this->input->post('username');
		$data = array(
			'password' => $this->db->escape( encrypt($pwd) )
		);
		$id=array(
			'user_id'=> $user_id
		);
		
		if( is_numeric($user_id) && !empty($pwd) )
		{
			$this->load->model('admin/model_user', 'user');
			$result = $this->user->update_user_pwd($data, $id);
			$data['msg'] = '修改成功';
		}
		else
		{
			$data['msg'] = '修改失败';
		}
		$data['user_id'] = $user_id;
		$data['user_name'] = $username;
		$this->load->view('admin/pwd', $data);
	}
	
	public function state()
	{
		$uid = $this->input->get("uid");
		$show = $this->input->get("show");
		$per_page = $this->input->get('per_page');
		$web_owner = $this->input->get('web_owner');
		$state = $this->input->get('state');
		$type = $this->input->get('type');
		if(!empty($uid))
		{
			$this->load->model('admin/model_user', 'user');
			if( $show==1 )
			{
				
				$data = array(
					'user_info' => $this->user->get_user_info_by_id($uid),
					'bank_list' => get_config_item('bank_list')
				);
				$data['user_id'] = $uid;
				$data['per_page'] = $per_page;
				$data['web_owner'] = $web_owner;
				$data['state'] = $state;
				$data['type'] = $type;
				$this->load->view('admin/user_state', $data);
			}
			else
			{
				$data = $this->user->get_user_name($uid);
				$data['user_id'] = $uid;
				$data['per_page'] = $per_page;
				$data['web_owner'] = $web_owner;
				$data['state'] = $state;
				$data['type'] = $type;
				$this->load->view('admin/user_state', $data);
			}
		}

	}
	
	public function update_state()
	{
		$user_state = $this->input->post('user_state');
		$user_id = $this->input->post('user_id');
		$per_page = $this->input->post('per_page');
		$web_owner = $this->input->post('web_owner');
		$state = $this->input->post('state');
		$type = $this->input->post('type');
		$data = array(
			'is_locked' => $user_state
		);
		$id=array(
			'user_id'=> $user_id
		);
		if( is_numeric($user_id) )
		{
			$this->load->model('admin/model_user', 'user');
			$result = $this->user->update_user_state($data, $id);
			redirect("admin/user/?web_owner=".$web_owner."&state=".$state."&type=".$type."&per_page=".$per_page."");
		}
	}
	
	public function user_add()
	{
		$data = array (
			'user_type' => $this->user_type,
			'user_state' => $this->userstate,
			'success' => ''
		);
		$this->load->view('admin/add_user', $data);
	}
	
	public function add()
	{
		$username = $this->input->post('username');
		$pwd = $this->input->post('pwd');
		$truename = $this->input->post('truename');
		$type = $this->input->post('type');
		$state = $this->input->post('state');
		
		if( empty($username) or empty($pwd) or empty($truename) )
		{
			exit("添加失败");
		}
		
		$this->load->model('admin/model_user','user');
		$row = $this->user->check_username($username);
		if($row['total']==0)
		{
			$data=array(
				'user_name'=>$this->db->escape($username),
				'password'=>$this->db->escape(encrypt($pwd)),
				'is_locked' => $state,
				'user_type' => $type
			);
			$this->user->insert_user($data);
			$info_data = array(
				'user_id' => $this->db->insert_id(),
				'true_name' => $this->db->escape($truename)
			);
			$this->user->insert_user_info($info_data);
			exit("1");	
		}
		else
		{
			exit("账号已经存在!");
		}
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 后台管理中心

class index extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->library('session');
		
		$admin_user_name	=	$this->session->userdata('admin_user_name');
		$admin_user_pass	=	$this->session->userdata('admin_user_pass');
		
		if ( !$admin_user_name )
		{
			redirect('admin/user/login');
		}
		else
		{
			// 判断验证是否有效
			$this->load->model('admin/user');
			
//			if ( $this->user->_check_user(array('user_name' => $admin_user_name, 'user_pass' => $admin_user_pass)) )
//			{
//				redirect('admin/home');
//			}
		}
	}
	
}
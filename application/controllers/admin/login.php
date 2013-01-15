<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 网盟平台后台管理中心登录页
 * @author kaiven
 *
 */

class Login extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->view('admin/login');
	}
	
	public function check_login()
	{
		$user_name	=	$this->input->post('userid');
		$user_pass	=	$this->input->post('pwd');
		if ( !$user_name )
		{
			exit('101');
		}
		if ( !$user_pass )
		{
			exit('102');
		}
		
		$this->user_info	=	array(
			'user_name'	=>	$user_name,
			'user_pass'	=>	$user_pass,
		);
		
		$this->load->model('admin/model_user');
		
		$manager_user_type	=	get_config_item('manager_user_type');
		
		$user_info	=	$this->model_user->get_user_info($conditions = array("user_name = '".$user_name."'", "user_type = ".$manager_user_type));

		if ( !$user_info )
		{
			redirect('admin/login');
		}
		
		if ( $user_info['password'] != encrypt($user_pass) || $user_info['is_locked'] != 0 || $user_info['user_type'] != $manager_user_type )
		{
			redirect('admin/login');
		}
		
		$this->load->model('admin/model_user_privilege');
		$user_privilege	=	$this->model_user_privilege->get_privilege($user_info['user_id']);
		
		$this->session->set_userdata('user_privilege', $user_privilege['privilege']);
		$this->session->set_userdata('admin_user_name', $user_info['user_name']);
		$this->session->set_userdata('admin_user_id', $user_info['user_id']);
		
		redirect('admin/home');
	}
	
	public function logout()
	{
		$this->session->unset_userdata( array('admin_user_name' => '', 'admin_user_pass' => '', 'admin_user_id' => '') );
		redirect('admin/login');
	}
}
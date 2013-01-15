<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登录页
 * @author lyu
 *
 */

class Login extends MY_Controller
{
	private $userinfo;	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->view('login');
	}
	
	public function check_login()
	{
		$username = $this->input->post('username');
		$pwd = $this->input->post('pwd');
		
		$this->userinfo	=	array(
			'username'	=>	$username,
			'pwd'	=>	encrypt($pwd),
		);
		
		$this->load->model('model_user','user');
				
		$row = $this->user->get_user_info($this->userinfo);
		if( !empty($row) )
		{
			if( $row['is_locked'] == 1 || $row['user_type'] != 1 )
			{
				exit("您的账号已经被锁定或无权限访问!");
			}
			
			$this->load->library('session');
			$this->session->set_userdata('user_name', $row['user_name']);
			$this->session->set_userdata('user_id', $row['user_id']);
			$this->session->set_userdata('email', $row['email']);
			exit("0");
		}
		else
		{
			exit("账号或密码错误!");
		}
		
	}
}
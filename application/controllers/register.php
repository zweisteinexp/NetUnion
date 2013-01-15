<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 注册页
 * @author lyu
 *
 */

class Register extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', '登录账号', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('pwd', '登录密码', 'trim|required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('ck_pwd', '确认密码', 'trim|required|min_length[6]|max_length[50]|matches[pwd]');
		$this->form_validation->set_rules('email', '安全邮箱', 'trim|required|valid_email|max_length[60]');
		
		if ( $this->form_validation->run() == FALSE )
		{
			$data['msg'] = validation_errors();
			$this->load->view("register", $data);
		}
		else
		{
			$username = trim( $this->input->post('username') );
			$pwd = trim( $this->input->post('pwd') );
			$email = trim( $this->input->post('email') );
			$this->load->model('model_user','user');
			$data=array(
				'user_name'=>$this->db->escape($username),
				'password'=>$this->db->escape(encrypt($pwd)),
				'email'=>$this->db->escape($email),
				'user_type' => 1
			);
			$row = $this->user->check_username($username);
			if($row['total']==0)
			{
				$result = $this->user->insert_user($data);
				$data['msg'] = '账号注册成功！ <a href ="'.site_url("login").'" >返回</a>';
				$this->load->view("register", $data);
			}
			else
			{
				$data['msg'] = '账号已经存在！';
				$this->load->view("register", $data);
			}
		}
		
	}
	
	public function agreement()
	{
		$this->load->view('agreement');
	}
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户取回密码
 * @author lyu
 *
 */

class Pwd extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', '登录账号', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('email', '安全邮箱', 'trim|required|valid_email|max_length[60]');
		
		if ( $this->form_validation->run() == FALSE )
		{
			$data['msg'] = validation_errors();
			$this->load->view("pwd", $data);
		}
		else
		{
			$username = trim( $this->input->post('username') );
			$email = trim( $this->input->post('email') );
			$this->load->model('model_user','user');	
			$row = $this->user->check_useremail($username, $email);
			if( !empty($row) )
			{
				$pwd = random_key();
				$user_pwd=array(
					'password'=> $this->db->escape( encrypt($pwd) )
				);
				$id=array(
					'user_id'=> $row['user_id']
				);
				$this->user->update_user_pwd($user_pwd, $id);
				$title = "取回密码说明";
				$content = "亲爱的".$username.": <br/>您的新密码为".$pwd."<br/>点击登录 <a href='".site_url("login")."'>".site_url("login")."</a>";
				
				$data = array(
					'type' => 1,
					'email' => $this->db->escape($email),
					'title' => $this->db->escape($title),
					'content' => $this->db->escape($content),
					'add_time' => strtotime( date('Y-m-d H:i:s') )
				);
				
				$this->user->insert_user_mail($data);
				$data['msg'] = '取回密码的方法已经发送到您的信箱！<a href ="'.site_url("login").'" >返回</a>';
				$this->load->view("pwd", $data);
			}
			else
			{
				$data['msg'] = '登录账号或邮箱错误！';
				$this->load->view("pwd", $data);
			}
		}
	}
}
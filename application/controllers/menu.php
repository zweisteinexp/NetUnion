<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 用户后台管理

class Menu extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		parent::check_login();
	}
	
	public function index()
	{
		$this->load->library('session');
		
		$data['user_name']	=	$this->session->userdata('user_name');
		$this->load->view('menu', $data);
	}
}
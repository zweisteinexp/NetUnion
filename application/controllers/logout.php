<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 用户后管理中心退出系统

class Logout extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		parent::check_login();
	}
	
	public function index()
	{
		$this->load->library('session');
		$this->session->unset_userdata( array('user_name' => '', 'user_id' => '', 'email' => '') );
		$this->session->sess_destroy();
		redirect( site_url("login") );
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 后台管理

class home extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		parent::check_login_for_manage();
	}
	
	public function index()
	{
		$this->load->view('admin/main');
	}
}
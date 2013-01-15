<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户管理后台
 * @author lyu
 *
 */

class Home extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		parent::check_login();
	}
	
	public function index()
	{
		$this->load->library('session');
		
		$this->_set_data();
		$this->load->view('main', $this->_DATA);
	}
}
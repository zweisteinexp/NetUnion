<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 起始页

class welcome extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->view('admin/welcome');
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// È¨ÏÞ²âÊÔÓÃÀý
class main extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		parent::$permissions	=	array(
			'index'	=>	1,
			'show'	=>	2,
		);
		
		parent::check_permissions();
	}
	
	function index()
	{
		echo '123213';
	}
	
	function show()
	{
		echo 'test';
	}
	
	function test()
	{
		echo 'main';
	}
}
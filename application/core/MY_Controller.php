<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 判断登录状态与权限
class MY_Controller extends CI_Controller
{
	var $db;
	protected $_UID;
	protected $_UNAME;
	protected $_M_UID; // manage user id
	protected $_DATA;
	protected $_TIME;
	protected static $permissions;
	function __construct()
	{
		parent::__construct();
		$this->__init();
		
	}
	
	private function __init()
	{
		$this->load->database();
		
		/* 前台用户全局变量 */
		
		$this->load->library('session');
		
		$this->_UID	=	$this->session->userdata('user_id');
		$this->_UNAME	=	$this->session->userdata('user_name');
		
		$this->_TIME	=	 time();
	}
	
	public function _set_data($data = array())
	{
		// 追加部分全局变量
		$data['current_class']	=	$this->router->class;
		$data['user_id']		=	$this->_UID;
		$data['user_name']		=	$this->_UNAME;
		$this->_DATA	=	$data;
	}
	
	// 管理平台权限
	public function check_permissions()
	{
		$this->load->model('admin/model_menu');
		$menu	=	$this->model_menu->get_menu($conditions = array("menu_code = '".$this->router->class."'"), $limit = 1);
		
		if ( ! $menu )
		{
			exit('对不起,你没有权限访问!');
//			redirect(site_url("admin/login"));
		}
		
		if ( ! self::$permissions )
		{
			exit('对不起,你没有权限访问!');
//			redirect(site_url("admin/login"));
		}
		
		$method	=	$this->router->method;
		if ( ! self::$permissions[$method] )
		{
			exit('对不起,你没有权限访问!');
//			redirect(site_url("admin/login"));
		}
		
		/*
		// 关闭重复加载权限,从session中读取,更改权限后需重新登录才生效
		$this->load->model('model_user_privilege');
		*/
		
		$user_privilege	=	$this->session->userdata('user_privilege');
		
		if ( $user_privilege != 'ALL' && strpos(','.$user_privilege.',', ','.$menu['id'] .'-'. self::$permissions[$method].',') === FALSE )
		{
			exit('对不起,你没有权限访问!');
//			redirect(site_url("admin/login"));
		}
	}
	
	// 网站主判断登录状态
	public function check_login()
	{	
		if( empty($this->_UID) )
		{
			redirect(site_url("login"));
		}
	}
	
	public function check_login_for_manage()
	{
		
		$this->_M_UID	=	$this->session->userdata('admin_user_id');
		
		if( empty($this->_M_UID) )
		{
			redirect(site_url("admin/login"));
		}
	}
}
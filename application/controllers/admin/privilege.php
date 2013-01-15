<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 后台管理

class Privilege extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::check_login_for_manage();
		$this->load->model('admin/model_menu');
	}
	
	public function index()
	{
		$data['menu']	=	$this->model_menu->get_menu();
		$data['main_menu']	=	array();
		
		foreach ($data['menu'] as $value)
		{
			if ( $value['parent_menu_id'] == 0 )
			{
				$data['main_menu'][$value['id']]	=	$value['menu_name'];
			}
		}
		
		$this->load->view('admin/privilege', $data);
	}
	
	public function insert()
	{
		$data['menu']	=	$this->model_menu->get_menu();
		$data['main_menu']	=	array();
		
		$menu_id	=	intval($this->input->get('id'));
		$data['row']	=	array(
			'id'		=>	'',
			'menu_name'	=>	'',
			'menu_code'	=>	'',
			'parent_menu_id'=>	'',
			'children_menu'	=>	'',
			'display'		=>	0,
		);
		if ( $menu_id )
		{
			$data['row']	=	$this->model_menu->get_menu($conditions = array('id = '.$menu_id), $limit = 1);
		}
		
		foreach ($data['menu'] as $value)
		{
			if ( $value['parent_menu_id'] == 0 )
			{
				$data['main_menu'][$value['id']]	=	$value['menu_name'];
			}
		}
		
		$this->load->view('admin/edit_privilege', $data);
	}
	
	public function execute()
	{
		$menu_id		=	intval($this->input->post('id'));
		$menu_name		=	$this->input->post('menu_name');
		$menu_code		=	$this->input->post('menu_code');
		$parent_menu_id	=	intval($this->input->post('parent_menu_id'));
		$display		=	intval($this->input->post('display'));
		$children_menu	=	$this->input->post('children_menu');
		
		$display != 1 && $display = 0;
		
		if ( !$menu_name )
		{
			exit('110');
		}
		if ( !$menu_code )
		{
			exit('111');
		}
		
		$menu	=	$this->model_menu->get_menu($conditions = array("menu_code = '".$menu_code."'"), $limit = 1);
		if ( $menu )
		{
			if ( ! $menu_id )
			{
				exit('112');
			}
			if ( $menu['id'] != $menu_id )
			{
				exit('112');
			}
			
			$sql	=	"UPDATE `union_menu` SET menu_name = '".$menu_name."'".
						", menu_code = '".$menu_code."', parent_menu_id = ".$parent_menu_id.
						", display = ".$display.", children_menu = '".$children_menu."'".
						"WHERE id = ".$menu_id;
		}
		else
		{
			$sql	=	"INSERT INTO `union_menu`(menu_name, menu_code, parent_menu_id, display, children_menu)".
						"VALUES('".$menu_name."', '".$menu_code."', ".$parent_menu_id.", ".$display.", '".$children_menu."')";
		}
		
		if ( !$this->model_menu->execute_sql($sql) )
		{
			exit('114');
		}
		redirect('admin/privilege');
	}
}
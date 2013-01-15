<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 后台管理左侧栏

class Feature extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		parent::$permissions	=	array(
			'index'	=>	1,
			'edit_privilege'	=>	2,
		);
		
		parent::check_permissions();
	}
	
	public function index()
	{
		$m_user_id	=	$this->input->get('uid');
		
		$manager_user_type	=	get_config_item('manager_user_type');
		$this->load->model('admin/model_user');
		$user_list	=	$this->model_user->get_user_info($conditions = array("user_type = ".$manager_user_type), $select = " * ", $order_by = NULL, $limit = NULL);

		$this->load->model('admin/model_menu');
		$menu	=	$this->model_menu->get_menu();
		
		$parent_menu	=	array();
		$sub_menu		=	array();
		foreach ( $menu as $value )
		{
			if ( $value['parent_menu_id'] == 0 )
			{
				$parent_menu[$value['id']]['name']	=	$value['menu_name'];
			}
			else
			{
				$sub_menu[$value['id']]['id']		=	$value['id'];
				$sub_menu[$value['id']]['name']		=	$value['menu_name'];
				$sub_menu[$value['id']]['children']	=	$value['children_menu'];
				$sub_menu[$value['id']]['parent_id']=	$value['parent_menu_id'];
				$sub_menu[$value['id']]['display']	=	$value['display'];
			}
		}
		
		if ( $m_user_id )
		{
			$this->load->model('admin/model_user_privilege', 'user_privilege');
			$user_privilege	=	$this->user_privilege->get_privilege($m_user_id);
			$data['user_privilege']	=	','.$user_privilege['privilege'].',';
			$data['m_user_id']	=	$m_user_id;
		}
		
		$data['user_list']	=	$user_list;
		$data['parent_menu']=	$parent_menu;
		$data['sub_menu']	=	$sub_menu;

		$this->_set_data($data);
		
		$this->load->view('admin/feature', $this->_DATA);
	}
	
	public function edit_privilege()
	{
		$privilege	=	$this->input->post('privilege');
		$m_user_id	=	$this->input->post('user_id');
		
		if ( intval($m_user_id) != $m_user_id )
		{
			exit('系统错误');
		}
		
		$manager_user_type	=	get_config_item('manager_user_type');
		
		$this->load->model('admin/model_user');
		$manager	=	$this->model_user->get_user_info($conditions = array("user_id = ".$m_user_id, "user_type = ".$manager_user_type));
		
		if ( !$manager )
		{
			exit('系统错误');
		}
		
		$this->load->model('admin/model_menu');
		$menu_list	=	$this->model_menu->get_menu();
		$sub_menu	=	array();
		foreach ( $menu_list as $value )
		{
			if ( $value['parent_menu_id'] != 0 )
			{
				$sub_menu[$value['id']]	=	$value['parent_menu_id'];
			}
		}
		
		$save_privilege	=	'';
		$parent_model	=	array();
		$sub_model		=	array();
		
		if ( $privilege )
		{
			foreach ( $privilege as $value )
			{
				list($model_id, $privilege_id)	=	split('-', $value);
				if ( !is_numeric($model_id) || !is_numeric($privilege_id) )
				{
					exit('系统错误');
				}
				
				if ( !in_array($model_id, $sub_model) )
				{
					$sub_model[]	=	$model_id;
				}
				
				if ( !in_array($sub_menu[$model_id], $parent_model) )
				{
					$parent_model[]	=	$sub_menu[$model_id];
				}
			}
			
			if ( $sub_model )
			{
				$save_privilege	=	implode(',', $parent_model) .','. implode(',', $sub_model) . ',' . implode(',', $privilege);
			}
		}
		
		
		$this->load->model('admin/model_user_privilege', 'user_privilege');
		
		if ( !$this->user_privilege->save_privilege($m_user_id, $save_privilege) )
		{
			exit('系统错误');
		}
		
		redirect('admin/feature/index?uid='.$m_user_id);
	}
}
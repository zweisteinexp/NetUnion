<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 后台管理左侧栏

class Menu extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data['user_name']		=	$this->session->userdata('admin_user_name');
		$data['admin_user_id']	=	$this->session->userdata('admin_user_id');
		
		if ( !$data['admin_user_id'] )
		{
			redirect(site_url("admin/login"));
		}
		
		$this->load->model('admin/model_user_privilege');
		$user_privilege	=	$this->model_user_privilege->get_privilege( $data['admin_user_id'] );
		
		if ( !$user_privilege )
		{
			redirect(site_url("admin/login"));
		}
		$main_ids	=	array();
		
		if ( $user_privilege['privilege'] == 'ALL' )
		{
			// 所有权限
			$main_ids	=	$user_privilege['privilege'];
		}
		else
		{
			$user_privilege	=	explode(',' , $user_privilege['privilege']);
			
			foreach ($user_privilege as $value)
			{
				if ( strpos($value, '-') === FALSE )
				{
					if ( !@$main_ids[$value] )
					{
						$main_ids[$value]	=	$value;
					}
				}
				else
				{
					list($main_id, $sub_id)	=	explode('-', $value);
					if ( !@$main_ids[$main_id] )
					{
						$main_ids[$main_id]	=	$main_id;
					}
				}
			}
		}
		
		
		$this->load->model('admin/model_menu');
		$menu_list	=	$this->model_menu->get_menu_by_id($main_ids);
		
		foreach ((array)$menu_list as $value)
		{
			if ( $value['parent_menu_id'] != 0 )
			{
				$data['menu_list'][$value['parent_menu_id']]['sub_menu'][$value['id']]	=	$value;
			}
			else
			{
				$data['menu_list'][$value['id']]	=	$value;
			}
		}
		
		$this->load->view('admin/menu', $data);
	}
}

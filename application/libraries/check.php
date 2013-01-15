<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class check
{
	
	function __construct()
	{
		
	}
	
	public function _set_permissions()
	{
//		print_r($_COOKIE);
		
//		$admin_user_id	=	$this->get_param('admin_user_id', 'cookie');
//		var_dump($admin_user_id);
		return TRUE;
	}
}
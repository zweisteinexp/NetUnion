<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ajax 请求

class Ajax extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		parent::check_login();
	}
	
	// 网站主获取广告代码
	public function get_code()
	{
		$website_id	=	$this->input->post('website_id');
		$return		=	array();
		
		if ( ! $website_id )
		{
			$this->_json_callback();
		}
		
		$this->load->model('model_website');
		$website	=	$this->model_website->get_website_by_id($this->_UID, $website_id);
		if ( ! $website )
		{
			$this->_json_callback();
		}
		
		$return	=	array(
			'secret'	=>	md5(base64_encode(md5($website['domain']))),
			'ad_type'	=>	1,
			'wid'		=>	$website_id,
		);
		
		$click_url	=	get_config_item('click_url');
		$content	=	$click_url . 'push.php?a='.base64_encode(serialize($return));
		$callback	=	array('code' => 119, 'content' => $content);
		$this->_json_callback($callback);
	}
	
	// 统一返回json
	private function _json_callback($json_result = NULL)
	{
		if ( !is_array($json_result) )
		{
			exit(json_encode(array('code' => 110)));
		}
		
		exit(json_encode($json_result));
	}
}
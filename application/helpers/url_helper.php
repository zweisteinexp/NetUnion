<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_slash_item($item)
{
	$CI =& get_instance();
	
	return $CI->config->slash_item($item);
}

function base_url($output = TRUE)
{
	$url	=	get_slash_item('base_url');
	if ( $output )
	{
		echo $url;
	}
	else
	{
		return $url;
	}
	
}

function base_res_script()
{
	echo base_url(FALSE) . get_slash_item('base_res_script');	
}
function base_res_style()
{
	echo base_url(FALSE) . get_slash_item('base_res_style');
}

function base_res_image()
{
	echo base_url(FALSE) . get_slash_item('base_res_image');
}
function base_res_plugins()
{
	echo base_url(FALSE) . get_slash_item('base_res_plugins');	
}
function base_att_verify()
{
	echo base_url(FALSE) . get_slash_item('base_att_verify');	
}

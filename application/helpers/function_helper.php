<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_config_item'))
{
	function get_config_item($item)
	{
		$CI =& get_instance();
		
		return $CI->config->item($item);
	}
}

if ( ! function_exists('site_url'))
{
	function site_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->site_url($uri);
	}
}

if ( ! function_exists('uri_string'))
{
	function uri_string()
	{
		$CI =& get_instance();
		return $CI->uri->uri_string();
	}
}

if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}
		
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

if ( ! function_exists('encrypt'))
{
	function encrypt($string)
	{
		if ( $string )
		{
			$hash	=	get_config_item('encrypt_key');
			$key	=	strtoupper(md5($hash));
			$string	=	strtoupper(md5($string));
			$num	=	strlen($key);
			$code	=	'';
			for ( $i = 0; $i < $num; $i++ )
			{
				$code	.=	$key[$i] ^ $string[$i];	
			}
			return md5($code);
		}
		return '';
	}
}

/**
  * 可逆加密函数
  */
if ( ! function_exists('str_encrypt') )
{
	function str_encrypt($string, $action='ENCODE')
	{
		$hash	=	get_config_item('encrypt_key');
		$action != 'ENCODE' && $string = base64_decode($string);
		$code = '';
		$key  = substr(md5($_SERVER['HTTP_USER_AGENT'] . $hash), 8, 18);
		$keylen	=	strlen($key);
		$strlen	=	strlen($string);
		for ($i = 0; $i < $strlen; $i++) 
		{
			$k		=	$i % $keylen;
			$code	.=	$string[$i] ^ $key[$k];
		}
		return ($action!='DECODE' ? base64_encode($code) : $code);
	}
}

if ( ! function_exists('random_key'))
{
	function random_key($len = 10, $t = FALSE)
	{
		$pattern	=	"1234567890";
		if ( $t )
		{
			$pattern	.=	'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTWXYZ1234567890';
		}
		else
		{
			$pattern	.=	'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTWXYZ';
		}
		$key	=	'';
		$length	=	strlen($pattern) - 1;
		for ($i = 0; $i < $len; $i++)
		{
			$key.=	$pattern{rand(0, $length)};
		}
		return $key;
	}
}

if ( ! function_exists('page_html')) 
{
	function page_html($pagesize, $page) 
	{
		if ($pagesize <= 1) 
		{
			$page_html = "只有一页";
		} else 
		{
			$page_html = "";
			if ($page <= 1) {
				$page_html .= '<a href="javascript:;"><input type="button" value="&lt;" disabled="disabled"/></a>';
			}
			if ($page > 1) {
				$page_html .= '<a href="javascript:;"><input type="button" value="&lt;" onclick="javascript:_p(' . ($page - 1) . ');"/></a>';
			}
			$page_html .= " {$page}/{$pagesize} ";
			if ($page >= $pagesize) {
				$page_html .= '<a href="javascript:;"><input type="button" value="&gt;" disabled="disabled"/></a>';
			}
			if ($page < $pagesize) {
				$page_html .= '<a href="javascript:;"><input type="button" value="&gt;" onclick="javascript:_p(' . ($page + 1) . ');"/></a>';
			}
		}
		return $page_html;
	}
}

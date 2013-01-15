<?php
/* 函数库 */
function file_write($file_name, $content, $method = 'ab')
{
	if (!$file_name || !$content)
	{
		return FALSE;
	}
	if (!file_exists($file_name))
	{
		touch($file_name);
	}
	
	$fp		=	fopen($file_name, $method);
	flock($fp, LOCK_EX);
	fwrite($fp, $content);
	flock($fp, LOCK_UN);
	fclose($fp);
}

function get_ip()
{
	static $onlineip;
	if ( $onlineip )
	{
		return $onlineip;
	}
	if ( getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown') )
	{
		$onlineip	=	getenv('HTTP_CLIENT_IP');
	}
	elseif ( getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown') )
	{
		$onlineip	=	getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif ( getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown') )
	{
		$onlineip	=	getenv('REMOTE_ADDR');
	}
	elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown') )
	{
		$onlineip	=	$_SERVER['REMOTE_ADDR'];
	}
	
	preg_match("/[\d\.]{7,15}/", $onlineip, $m);
	
	return $m[0] ? $m[0] : 'unknown';
}

function show_msg($msg)
{
	exit($msg);
}

function str_encrypt($string, $action='ENCODE')
{
	$hash	=	$GLOBALS['config']['encrypt_key'];
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


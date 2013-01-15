<?php

require 'init.php';


// 核实脚本的有效性


$value	=	$_GET['a'];
if ( !$value )
{
	echo 'retun false;';
	exit;
}

// 将请求写入memcache
/* 连接memcache */
include_once(CLS . 'class_memcache.php');
$class_memcache	=	new Class_Memcache();
$class_memcache->add_server($config['memcache']);


$last_request_key =       $class_memcache->increment('last_request_key', 1);
if ( !$last_request_key )
{
		$last_request_key->set_value('last_request_key', 0);
		$last_request_key =       0;
}
$class_memcache->set_value('request_'.$last_request_key, serialize(array('request_time' => time())));

$class_memcache->_close();


$param	=	unserialize(base64_decode($value));

if ( ! is_array($param) )
{
	exit;
}

foreach ($param as $key=>$value)
{
	echo 'var '.$key.' = \''.$value.'\';'."\n";
	echo "var key = ".$this_request_key.";\n";
}


print <<<EOF


//document.write("<script src='http://click.lezi.com/script/popup.js'></script>");
document.write("<script src='http://click.lezi.com/script/dt.js'></script>");

EOF;

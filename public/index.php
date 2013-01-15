<?php

require 'init.php';

if ( class_exists('memcache') )
{
	$memcache	=	new Memcache;
	$memcache->connect($config['memcache']['host'], $config['memcache']['port']) || die('connect failed');
	
	$last_end_key	=	$memcache->get('last_end_key');
	$now_end_key	=	$memcache->get('now_end_key');
	
	!$last_end_key && $last_end_key = 0;
	!$now_end_key && $now_end_key = 1;
	echo '<pre>';
	echo $last_end_key."\n";
	echo $now_end_key."\n";
	
	
	for ($key_num = $last_end_key; $key_num < $now_end_key; $key_num++)
	{
//		echo $key_num."<br />";
		$memcache->set('last_end_key', $key_num);
		print_r($memcache->get($key_num));
	}
	
	$memcache->close();
	echo $key_num;
	
}
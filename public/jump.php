<?php
/**
 * @filename : jump.php
 * @purpose : 广告跳转
 * @example : jump.php?s=
*/

/*

$array	=	array('web');

$param	=	base64_encode(serialize($array));

*/



if ( isset($_GET['s']) && $_GET['s'] == 'g' )
{
	include_once('init.php');
	$userAgent	=	$_POST['userAgent'];
	$wid		=	$_POST['ss'];
	$refer_url	=	$_POST['r'];
	$screen		=	$_POST['screen'];
	$param		=	$_POST['param'];
	$request_key=	intval($_POST['key']);
	
	if ( !$param || !$wid )
	{
		echo 110;
		exit();
	}
	
	// 定义全局
	$have_website		=	FALSE;
	$have_advertise		=	FALSE;
	$return_advertise	=	array();
	
	/* 连接memcache */
	include_once(CLS . 'class_memcache.php');
	$class_memcache	=	new Class_Memcache();
	$class_memcache->add_server($config['memcache']);
	
	// 请求成功返回入memcache
	if ( $request_key )
	{
		$request_info	=	$class_memcache->get_value('request_'.$request_key);
		if ( $request_info )
		{
			$request_info	=	unserialize($request_info);
			$request_info['succ_time']	=	time();
			$class_memcache->set_value('request_'.$request_key, serialize($request_info));
		}
		else
		{
			/*
			$invalid_times	=	$class_memcache->get_value('invalid_times');
			!$invalid_times && $invalid_times = 0;
			$invalid_times++;
			$class_memcache->set_value('invalid_times', $invalid_times);
			*/
			$invalid_times =       $class_memcache->increment('invalid_times', 1);
			if ( !$invalid_times )
			{
				$class_memcache->set_value('invalid_times', 0);
			}
		}
	}
	
	// 从memcache中获取站点信息
	$website	=	$class_memcache->get_value('website');
	
	// 审核站点有效性{站点存在,但host加密串错误}
	$domain		=	$website[$wid]['domain'];
	if ( substr($domain, -1, 1) != '/' )
	{
		$domain	.=	'/';
	}
	
	if ( $website[$wid] && host_encrypt($domain) != $param )
	{
		echo 114;
		exit();
	}
	
	// 系统默认广告系列
	$default_series	=	$class_memcache->get_value('default_series');
	
	// 是否有指定广告
	if ( $website[$wid]['train_item'] && $website[$wid]['train_info'] )
	{
		$train_info	=	$website[$wid]['train_info'];
		$train_item	=	$website[$wid]['train_item'];
		$roll_type	=	$train_info['roll_type'];
		
		// 1=asc order,2=random
		if ( $roll_type == 1 )
		{
			foreach ( $train_item as $value )
			{
				if ( $value['max_ip'] > 0 && $value['pass_ip'] >= $value['max_ip'] )
				{
					continue;
				}
				$return_advertise	=	$value;
				$have_advertise	=	TRUE;
				break;
			}
		}
		elseif ( $roll_type == 2 )
		{
			while (TRUE)
			{
				$rand_key			=	array_rand($train_item, 1);
				$return_advertise	=	$train_item[$rand_key];
				if ( $return_advertise['max_ip'] > 0 && $return_advertise['pass_ip'] >= $return_advertise['max_ip'] )
				{
					continue;
				}
				$have_advertise	=	TRUE;
				break;
			}
		}
	}
	// 没有找到对应的广告(如果站点不存在或未传值 则直接弹出系统推荐广告)
	if ( !$have_advertise )
	{
		if ( !$default_series )
		{
			exit;
		}
		
		$default_series_key	=	array_rand($default_series);
		
		$series_advertise	=	$default_series[$default_series_key];
		
		// 解析广告
		########################################################
		# 1=asc order,2=random,1 as default
		########################################################
		
		$roll_type	=	$series_advertise['roll_type'];
		
		if ( $roll_type == 1 )
		{
			$return_advertise	=	array();
			
			foreach ( $series_advertise['item_list'] as $value )
			{
				if ( $value['max_ip'] > 0 && $value['pass_ip'] >= $value['max_ip'] )
				{
					continue;
				}
				$return_advertise	=	$value;
				break;
			}
		}
		elseif ( $roll_type == 2 )
		{
			while (TRUE)
			{
				$rand_key			=	array_rand($series_advertise['item_list'], 1);
				$return_advertise	=	$series_advertise['item_list'][$rand_key];
				if ( $return_advertise['max_ip'] > 0 && $return_advertise['pass_ip'] >= $return_advertise['max_ip'] )
				{
					continue;
				}
				break;
			}
		}
	}
	
//	$return_advertise['jump_url']	=	'http://sh.lezi.com/entire/promote/session_1/reg_gwjk_110620.html';
	
	if ( !$return_advertise )
	{
		echo 119;
		exit;
	}
	
	// 测试跳转链接
	$return_advertise['jump_url']	=	$return_advertise['advertise_url'] ? $return_advertise['advertise_url'] : 'http://www.lezi.com/';
//	$return_advertise['jump_url']	=	'http://mrcs.lezi.com/entire/promote/session_1/reg_265g_110513.html';
	
	// 客户端信息写入memcached
	
	$client_info	=	array(
		'push_time'		=>	time(),
		'ip'			=>	get_ip(),
		'website_id'	=>	$wid,
		'advertise_id'	=>	$return_advertise['advertise_id'],
		'refer_url'		=>	$refer_url,
		'user_agent'	=>	array(
			'info'		=>	$_SERVER['HTTP_USER_AGENT'],
			'os'		=>	'',
			'alex_bar'	=>	'',
			'baidu_bar'	=>	'',
			'screen'	=>	$screen,
		),
	);
	
	$end_record_key =       $class_memcache->increment('end_record_key', 1);
	if ( !$end_record_key )
	{
		$class_memcache->set_value('end_record_key', 0);
		$end_record_key =       0;
	}
	$class_memcache->add_value($end_record_key, $client_info);
	
	/*
	$end_record_key	=	$class_memcache->get_value('end_record_key');
	!$end_record_key && $end_record_key = 0;
	
	$class_memcache->add_value($end_record_key, $client_info);
	$class_memcache->set_value('end_record_key', $end_record_key + 1);
	*/
	
	// 输出json结果
	echo json_encode($return_advertise);
	
	exit;
}

if ( isset($_GET['s']) && $_GET['s'] && strcasecmp($_GET['s'], intval($_GET['s'])) == 0 )
{
	$refer_url	=	$_SERVER['HTTP_REFERER'];
	
	$website_id	=	intval($_GET['s']);
	$request_key=	$_GET['key'];
	$host		=	$_GET['host'];
	if ( ! $host )
	{
		exit;
	}
	
	$param	=	host_encrypt($host);
	
	print <<<EOF
		<script src="script/jquery-1.5.1.min.js" lanuage="javascript"></script>
		<script>
		$(document).ready(function () {
			var ss = '{$website_id}';
			var r = '{$refer_url}';
			var param = '{$param}';
			var key	=	'{$request_key}';
			var userAgent = navigator.userAgent.toLowerCase();
			var screen	=	window.screen.width + 'x' + window.screen.height;
			var url = 'jump.php?s=g';
			var data = 'userAgent='+userAgent+'&ss='+ss+'&r='+r+'&screen='+screen+'&param='+param+'&key='+key;
			$.post(url, data, function (result) {
				if (result) {
						switch (result) {
							case '110':
							case '114':
							case '119':
								return false;
							break;
							default:
								var dataObj	=	eval("("+result+")"); // json对象
								var jump_url	=	dataObj.jump_url;
								window.location.href = jump_url;
							break;
						}
				}
				else {
					return false;
				}
			});
		});
		</script>
EOF;
	exit;
}

function host_encrypt($host = NULL)
{
	if ( $host )
	{
		return strtoupper(md5(base64_encode(md5(serialize(array('host' => $host))))));
	}
	return FALSE;
}
?>
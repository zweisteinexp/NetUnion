<?php
/**
 * @purpose : 服务端处理文件 将memcached的数据移入到MySQL中
 * @author : kaiven
 */


// 隔15分钟请求一次

include_once('init.php');

if ( $argv[1] == 'go' )
{
	
	/* 连接memcache */
	include_once(CLS . 'class_memcache.php');
	$class_memcache	=	new Class_Memcache();
	$class_memcache->add_server($config['memcache']);
	
	/* 连接MySQL */
	include_once(CLS . 'class_mysqli.php');
	$db	=	new Class_MySQLi();
	
	
	$last_deal_key	=	$class_memcache->get_value('last_deal_key');
	$end_record_key	=	$class_memcache->get_value('end_record_key');
	$website		=	$class_memcache->get_value('website');
	!$last_deal_key && $last_deal_key = 0;
	
	for ( $key_num = $last_deal_key; $key_num < $end_record_key; $key_num++ )
	{
		$key_value	=	$class_memcache->get_value($key_num);
		print_r($key_value)."\n";
		
		$push_time	=	$key_value['push_time'];
		$ip			=	$key_value['ip'];
		$website_id	=	$key_value['website_id'];
		$advertise_id	=	$key_value['advertise_id'];
		$user_agent	=	serialize($key_value['user_agent']);
		$add_date	=	date("Y-m-d", $push_time);
		
		if ( !$push_time || !$ip || !$website_id || !$advertise_id || !$user_agent )
		{
			continue;
		}
		
		$sql	=	"SELECT user_id FROM `union_website` WHERE `id` = ".$website_id;
		$user	=	$db->get_row($sql);
		$user_id	=	$user['user_id'];
		
		$sql	=	"SELECT * FROM `union_ip_source`".
					" WHERE ip = '".$ip."' AND add_date = '".$add_date."'".
					" AND website_id = ".$website_id.
					" AND advertise_id = ".$advertise_id;
		
		$row	=	$db->get_row($sql);
		
		$is_update_ip	=	FALSE;
		
		if ( $row )
		{
			$sql	=	"UPDATE `union_ip_source` SET last_click_time = ".$push_time.
						", clicks = clicks + 1 WHERE id = ".$row['id'];
			$is_update_ip	=	FALSE;
		}
		else
		{
			$sql	=	"INSERT INTO `union_ip_source`(`ip`, `clicks`, `first_click_time`, `last_click_time`, `user_agent`, `user_id`, `website_id`, `advertise_id`, `add_date`)".
						"VALUES('".$ip."', 1, ".$push_time.", ".$push_time.", '".$user_agent."', ".$user_id.", ".$website_id.", ".$advertise_id.", '".$add_date."')";
			$is_update_ip	=	TRUE;
		}
		
		if ( !$db->execute($sql) )
		{
			exit('error');
		}
		
		// 有指定广告,更新已跑量
		if ( $website[$website_id]['train_item'] )
		{
			$train_item	=	$website[$website_id]['train_item'];
			foreach ( $train_item as $key=>$value )
			{
				if ( $value['advertise_id'] != $advertise_id )
				{
					continue;
				}
				
				if ( $is_update_ip )
				{
					$website[$website_id]['train_item'][$key]['pass_ip']++;
					$website[$website_id]['train_item'][$key]['pass_pv']++;
				}
				else
				{
					$website[$website_id]['train_item'][$key]['pass_pv']++;
				}
			}
		}
		
		$class_memcache->set_value('website', $website);
		$class_memcache->del_value($key_num);
		$last_deal_key	=	$class_memcache->set_value('last_deal_key', $key_num);
	}
	
	$last_deal_request_key	=	$class_memcache->get_value('last_deal_request_key');
	if ( !$last_deal_request_key )
	{
		$last_deal_request_key	=	0;
	}
	
	$last_request_key	=	$class_memcache->get_value('last_request_key');
	if ( !$last_request_key )
	{
		$last_request_key	=	0;
	}
	
	$request_times	=	0;
	$succ_times		=	0;
	$invalid_times	=	0;
	
	if ( $last_request_key > $last_deal_request_key )
	{
		for ($deal_key = $last_deal_request_key; $deal_key <= $last_request_key; $deal_key++)
		{
			$request_info	=	$class_memcache->get_value('request_'.$deal_key);
			if ( $request_info )
			{
				$request_times++;
				$request_info	=	unserialize($request_info);
				
				// 同时存在request_time和succ_time才算完成一次请求
				if ( $request_info['request_time'] && $request_info['succ_time'] )
				{
					$succ_times++;
				}
				
				
				$class_memcache->del_value('request_'.$deal_key);
			}
		}
	}
	
	$invalid_times	=	$class_memcache->get_value('invalid_times');
	
	if ( $request_times > 0 || $succ_times > 0 || $invalid_times > 0 )
	{
		$request_date	=	date("Y-m-d", TIME);
		
		$request_times	=	(int)$request_times;
		$succ_times		=	(int)$succ_times;
		$invalid_times	=	(int)$invalid_times;
		
		$sql	=	"SELECT * FROM `union_request_stats` WHERE request_date = '".$request_date."'";
		$stats	=	$db->get_row($sql);
		
		if ( $stats )
		{
			$sql	=	"UPDATE `union_request_stats` SET request_times = request_times + ".$request_times.
						", succ_times = succ_times + ".$succ_times.", invalid_times = invalid_times + ".$invalid_times.
						" WHERE id = ".$stats['id'];
		}
		else
		{
			$sql	=	"INSERT INTO `union_request_stats`(request_times, succ_times, invalid_times, request_date)".
						"VALUES(".$request_times.", ".$succ_times.", ".$invalid_times.", '".$request_date."')";
		}
		$db->execute($sql);
	}
	
	echo "ok\n";
}

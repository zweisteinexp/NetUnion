<?php
/**
 * @purpose : 统计数据,生成结算单
 * @author : kaiven
 * @example : $argv = array(0 => file, 1 => 'go', 2 => settle_type, 3 => 指定日期);
 */

error_reporting(0);

include_once('init.php');

if ( $argv[1] != 'go' )
{
	exit('Forbidden');
}

define('IN_ANALYZER', TRUE);

//$settle_type	=	$argv[2];
/* 连接MySQL */
include_once(CLS . 'class_mysqli.php');
$db	=	new Class_MySQLi();

// 定义全局(取消自定义时间点)
//$record_date	=	!$argv[3] ? date("Y-m-d", time() - 24 * 3600) : $argv[3];
$record_date	=	date("Y-m-d", time() - 24 * 3600);

/*
include(ROOT . 'analyzer_'.$settle_type.'.php');
*/


$is_weekend		=	date('w', strtotime($record_date)) == 0 ? TRUE : FALSE;
$is_monthend	=	(date('t', strtotime($record_date)) == date("d", strtotime($record_date))) ? TRUE : FALSE;

// 读取网站列表
$sql	=	"SELECT id, user_id, website_name, deduct_rate, cost_rate, settle_type FROM `union_website`";
$query	=	$db->get_all($sql);
$website	=	array();
$advertise_stats	=	array();
foreach ($query as $value)
{
	$website_id	=	$value['id'];
	$website[$website_id]['website_id']	=	$website_id;
	$website[$website_id]['user_id']	=	$value['user_id'];
	$website[$website_id]['website_name']	=	$value['website_name'];
	$website[$website_id]['deduct_rate']	=	$value['deduct_rate'] > 0.00 ? $value['deduct_rate'] : 100;
	$website[$website_id]['cost_rate']		=	$value['cost_rate'] > 0.00 ? $value['cost_rate'] : $config['cost_rate'];
	$website[$website_id]['settle_type']	=	$value['settle_type'];
	$website[$website_id]['ad_train_id']	=	$value['ad_train_id'];
}


// 生成每日的网站日统计记录
$sql	=	"SELECT COUNT(*) AS num FROM `union_ip_source` WHERE add_date = '".$record_date."'";
$row	=	$db->get_row($sql);
if ( $row && $row['num'] > 0 )
{
	$per_page	=	2000;
	$pages		=	ceil( $row['num'] / $per_page );
	
	for ( $page_num = 0; $page_num < $pages;$page_num++ )
	{
		$sql	=	"SELECT * FROM `union_ip_source` WHERE add_date = '".$record_date."' ORDER BY id ASC LIMIT ".($page_num * $per_page).", ".$per_page;
		$list	=	$db->get_all($sql);
		if ( $list )
		{
			foreach ( $list as $value )
			{
				$website_id	=	$value['website_id'];
				$website[$website_id]['real_ips']++;
				$website[$website_id]['real_clicks']	+=	$value['clicks'];
				
				$advertise_id	=	$value['advertise_id'];
				
				$advertise_stats[$advertise_id][$website_id]['real_ips']++;
				$advertise_stats[$advertise_id][$website_id]['real_clicks']	+=	$value['clicks'];
			}
		}
	}
}


// 广告日统计记录
if ( $advertise_stats )
{
	foreach ( $advertise_stats as $advertise_id=>$value )
	{
		$ips	=	0;
		$clicks	=	0;
		$real_ips	=	0;
		$real_clicks=	0;
		foreach ( $value as $website_id=>$val )
		{
			// 获取比例值
			$deduct_rate	=	intval($website[$website_id]['deduct_rate']);
			$temp_ips		=	ceil($val['real_ips'] * $deduct_rate / 100);
			$temp_clicks	=	ceil($val['real_clicks'] * $deduct_rate / 100);
			
			$ips += $temp_ips;
			$clicks += $temp_clicks;
			$real_ips	+=	$val['real_ips'];
			$real_clicks+=	$val['real_clicks'];
		}
		
		$sql	=	"SELECT * FROM `union_advertise_daily_stats` WHERE stats_date = '".$record_date."' AND advertise_id = ".$advertise_id;
		$row	=	$db->get_row($sql);
		if ( $row )
		{
			$sql	=	"UPDATE `union_advertise_daily_stats` SET ips = ".$ips.
						", real_ips = ".$real_ips.", clicks = ".$clicks.", real_clicks = ".$real_clicks.
						" WHERE id = ".$row['id'];
			$db->execute($sql);
		}
		else
		{
			// 排除数值都为0的数据
			if ( $ips > 0 && $clicks > 0 )
			{
				$sql	=	"INSERT INTO `union_advertise_daily_stats`(advertise_id, stats_date, ips, real_ips, clicks, real_clicks)".
							"VALUES(".$advertise_id.", '".$record_date."', ".$ips.", ".$real_ips.", ".$clicks.", ".$real_clicks.")";
				$db->execute($sql);
			}
		}
	}
}



foreach ( $website as $value )
{
	$website_id	=	$value['website_id'];
	$real_ips	=	intval($value['real_ips']);
	$real_clicks=	intval($value['real_clicks']);
	$deduct_rate=	intval($value['deduct_rate']);
	
	$ips		=	ceil($real_ips * $deduct_rate / 100);
	$clicks		=	ceil($real_clicks * $deduct_rate / 100);
	$website[$website_id]['ips']	=	$ips;
	$website[$website_id]['clicks']	=	$clicks;
	
	$sql	=	"SELECT * FROM `union_website_daily_stats` WHERE stats_date = '".$record_date."' AND website_id = ".$website_id;
	$row	=	$db->get_row($sql);
	if ( $row )
	{
		$sql	=	"UPDATE `union_website_daily_stats` SET ips = ".$ips.
					", real_ips = ".$real_ips.", clicks = ".$clicks.", real_clicks = ".$real_clicks.
					" WHERE id = ".$row['id'];
		$db->execute($sql);
	}
	else
	{
		// 排除数值都为0的数据
		if ( $ips > 0 && $clicks > 0 )
		{
			$sql	=	"INSERT INTO `union_website_daily_stats`(website_id, stats_date, ips, real_ips, clicks, real_clicks)".
						"VALUES(".$website_id.", '".$record_date."', ".$ips.", ".$real_ips.", ".$clicks.", ".$real_clicks.")";
			$db->execute($sql);
		}
	}
}


foreach ( $website as $value )
{
	$website_id	=	$value['website_id'];
	$settle_type=	$value['settle_type'];
	$cost_rate	=	$value['cost_rate'];
	$ips		=	intval($value['ips']);
	$real_ips	=	intval($value['real_ips']);
	$clicks		=	intval($value['clicks']);
	$real_ips	=	intval($value['real_ips']);
	$real_clicks=	intval($value['real_clicks']);
	$user_id	=	$value['user_id'];
	
	// 日结算
	if ( $settle_type == 1 )
	{
		$amount	=	$cost_rate * ($ips / 1000);
		$sql	=	"SELECT * FROM `union_settle_order` WHERE website_id = ".$website_id.
					" AND order_date = '".$record_date."' AND settle_type = 1";
		$row	=	$db->get_row($sql);
		if ( $row )
		{
			// 未更改结算单状态之前可以更新数据
			if ( $row['state'] == 0 )
			{
				$sql	=	"UPDATE `union_settle_order` SET ips = ".$ips.", clicks = ".$clicks.
							", real_ips = ".$real_ips.", real_clicks = ".$real_clicks.
							", amount = '".$amount."' WHERE id = ".$row['id'];
				$db->execute($sql);
			}
//			$sql	=	"UPDATE `union_settle_order` SET ";
		}
		else
		{
			$sql	=	"INSERT INTO `union_settle_order`(user_id, website_id, ips, clicks, real_ips, real_clicks, amount, order_date, state, settle_type)".
						"VALUES(".$user_id.", ".$website_id.", ".$ips.", ".$clicks.", ".$real_ips.", ".$real_clicks.", '".$amount."', '".$record_date."', 0, 1)";
			$db->execute($sql);
		}
	}
	
	// 周结算 与 月结算
	if ( ($settle_type == 2 && $is_weekend) || ($settle_type == 3 && $is_monthend) )
	{
		if ( $is_weekend )
		{
			$start_date		=	date("Y-m-d", strtotime($record_date) - 6 * 24 * 3600);
			$rate_history_limit	=	10;
		}
		else
		{
			$start_date		=	date("Y-m", strtotime($record_date)).'-01';
			$rate_history_limit	=	30;
		}
		$end_date		=	$record_date;
		
		$start_time		=	strtotime($start_date);
		$end_time		=	strtotime($end_date);
		
		// 查询比例记录
		$sql		=	"SELECT * FROM `union_rate_history` ORDER BY valid_time DESC LIMIT ".$rate_history_limit;
		$rate_list	=	$db->get_all($sql);
		$have_rate	=	TRUE;
		if ( $rate_list )
		{
			$key	=	0;
			foreach ( $rate_list as $rate_value )
			{
				$end_time	=	$rate_value['end_time'];
				$valid_time	=	$rate_value['valid_time'];
				$value['rate_list'][$key]['start_time']	=	$valid_time;
				$value['rate_list'][$key]['end_time']	=	$end_time;
				$value['rate_list'][$key]['deduct_rate']=	$rate_value['deduct_rate'];
				$value['rate_list'][$key]['cost_rate']	=	$rate_value['cost_rate'];
				
				$key++;
			}
		}
		else
		{
			$have_rate	=	FALSE;
		}
		
		$sql	=	"SELECT * FROM `union_website_daily_stats` WHERE website_id = ".$website_id.
					" AND stats_date >= '".$start_date."' AND stats_date <= '".$end_date."'";
		$stats	=	$db->get_all($sql);
		
		$week_ips	=	0;
		$week_clicks=	0;
		$week_amount=	0;
		$week_real_ips	=	0;
		$week_real_clicks=	0;
		
		foreach ( $stats as $stats_value )
		{
			$stats_date	=	$stats_value['stats_date'];
			
			if ( !$have_rate )
			{
				$cost_rate	=	$config['cost_rate'];
				$deduct_rate=	100;
			}
			else
			{
				foreach ( $value['rate_list'] as $rate_value )
				{
					if ( $stats_date >= $rate_value['start_time'] && $stats_date < $rate_value['end_time'] )
					{
						$cost_rate	=	$rate_value['cost_rate'];
						$deduct_rate=	$rate_value['deduct_rate'];
						break;
					}
				}
			}
			
			$week_real_ips	+=	$stats_value['ips'];
			$week_real_clicks+=	$stats_value['clicks'];
			
			$ips		=	$stats_value['ips'] * $deduct_rate / 100;
			$clicks		=	$stats_value['clicks'] * $deduct_rate / 100;
			
			$amount		=	$ips / 1000 * $cost_rate;
			
			$week_ips	+=	$ips;
			$week_clicks+=	$clicks;
			$week_amount+=	$amount;
		}
		
		$sql	=	"SELECT * FROM `union_settle_order` WHERE website_id = ".$website_id.
					" AND order_date = '".$record_date."' AND settle_type = 2";
		$row	=	$db->get_row($sql);
		if ( $row )
		{
			// 未更改结算单状态之前可以更新数据
			if ( $row['state'] == 0 )
			{
				$sql	=	"UPDATE `union_settle_order` SET ips = ".$week_ips.", clicks = ".$week_clicks.
							", real_ips = ".$week_real_ips.", real_clicks = ".$week_real_clicks.
							", amount = '".$week_amount."' WHERE id = ".$row['id'];
				$db->execute($sql);
			}
		}
		else
		{
			$sql	=	"INSERT INTO `union_settle_order`(user_id, website_id, ips, clicks, real_ips, real_clicks, amount, order_date, state, settle_type)".
						"VALUES(".$user_id.", ".$website_id.", ".$week_ips.", ".$week_clicks.", ".$week_real_ips.", ".$week_real_clicks.", '".$week_amount."', '".$record_date."', 0, 2)";
			$db->execute($sql);
		}
	}
}


// 调整次日的比例有效值
$sql	=	"SELECT * FROM `union_rate_history` WHERE end_time = '9999-12-31 23:59:59'";
$list	=	$db->get_all($sql);

if ( $list )
{
	foreach ( $list as $value )
	{
		$cost_rate	=	$value['cost_rate'];
		$deduct_rate=	$value['deduct_rate'];
		$website_id	=	$value['website_id'];
		
		$sql	=	"UPDATE `union_website` SET deduct_rate = '".$deduct_rate."', cost_rate = '".$cost_rate."' WHERE id = ".$website_id;
		$db->execute($sql);
	}
}

echo 'it\'s over';
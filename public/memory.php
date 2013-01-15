<?php
/* 测试文件 */

/* 加载全局 */
require_once 'init.php';

/* 加载memcache类 */
include_once(CLS . 'class_memcache.php');
$class_memcache	=	new Class_Memcache();
$class_memcache->add_server($config['memcache']);

/* 连接MySQL */
include_once(CLS . 'class_mysqli.php');
$db	=	new Class_MySQLi();
$per_page	=	2000;

/* 获取CGI参数 */
$action	=	$argv[1];

switch ( $action )
{
	// 将数据广告植入内存
	case 'insert_advertise_into_memory_from_mysql':
		/*
		$sql	=	"SELECT * FROM `union_advertise` WHERE state = 1";
		$list	=	$db->get_all($sql);
		
		if ( $class_memcache->set_value('advertise', $list) )
		{
			echo "succ";
		}
		*/
		$default_series	=	array();
		$default_series_ids	=	array();
		$all_series		=	array();
		$all_series_id	=	array();
		$sql	=	"SELECT * FROM `union_advertise_train` WHERE state = 1";
		$list	=	$db->get_all($sql);
		$all_series	=	$list;
		if ( $list )
		{
			foreach ( $list as $value )
			{
				if ( $value['is_default'] == 1 )
				{
					$default_series[$value['id']]	=	$value;
					$default_series_ids[]	=	$value['id'];
				}
				$all_series_id[]	=	$value['id'];
			}
		}
		unset($list);
		
		if ( $all_series_id )
		{
			$sql	=	"SELECT COUNT(*) AS num FROM `union_advertise_train_item` WHERE ad_train_id IN (".implode(',', $all_series_id).")";
			$row	=	$db->get_row($sql);
			$total	=	$row && $row['num'] > 0 ? $row['num'] : 0;
			
			if ( $total > 0 )
			{
				$pages	=	ceil($total / $per_page);
				for ($page_num = 0; $page_num < $pages; $page_num++)
				{
					$sql	=	"SELECT * FROM `union_advertise_train_item` WHERE ad_train_id IN (".implode(',', $all_series_id).")".
								" ORDER BY id ASC LIMIT ".($page_num * $per_page).", ".$per_page;
					$list	=	$db->get_all($sql);
					
					foreach ( $list as $value )
					{
						$ad_train_id	=	$value['ad_train_id'];
						$all_series[$ad_train_id][$value['id']]	=	$value;
						
						if ( in_array($ad_train_id, $default_series_ids) )
						{
							$default_series[$ad_train_id]['item_list'][$value['id']]	=	$value;
						}
					}
				}
			}
		}
		// 所有广告(物理路径)
		$sql	=	"SELECT COUNT(*) AS num FROM `union_advertise`";
		$row	=	$db->get_row($sql);
		$total	=	$row && $row['num'] > 0 ? $row['num'] : 0;
		if ( $total )
		{
			$pages	=	ceil($total / $per_page);
			for ( $page_num = 0; $page_num < $pages; $page_num++ )
			{
				$sql	=	"SELECT * FROM `union_advertise` ORDER BY id ASC LIMIT ".($per_page * $page_num).", ".$per_page;
				$list	=	$db->get_all($sql);
				foreach ( $list as $value )
				{
					$save_value	=	'<?php exit();?>'.serialize($value);
					file_write(AD_DIR . $value['id'].'.php', $save_value, $method = 'w');
				}
			}
		}
		
		if ( $class_memcache->set_value('default_series', $default_series) && $class_memcache->set_value('all_series', $all_series) )
		{
			echo 'succ';
		}
		else
		{
			echo 'failed';
		}
		break;
	case 'insert_website_into_memory_form_mysql':
		$sql	=	"SELECT * FROM `union_website` WHERE state = 1";
		$list	=	$db->get_all($sql);
		$result	=	array();
		
		$advertise_train	=	array();
		foreach ((array)$list as $value)
		{
			$website_id			=	$value['id'];
			$result[$website_id]	=	$value;
			$ad_train_id	=	$value['ad_train_id'];
			if ( $ad_train_id )
			{
				// 推广链接
				$sql	=	"SELECT * FROM `union_advertise_train` WHERE id = ".$ad_train_id;
				$train	=	$db->get_row($sql);
				if ( $train )
				{
					$result[$website_id]['train_info']	=	$train;
				}
				
				// 推广链接包含的广告
//				$sql	=	"SELECT * FROM `union_advertise_train_item` WHERE ad_train_id = ".$ad_train_id." AND state = 0 ORDER BY sort ASC";
				$sql	=	"SELECT a.*, b.advertise_url FROM `union_advertise_train_item` AS a JOIN `union_advertise` AS b ON (a.advertise_id = b.id) WHERE a.ad_train_id = ".$ad_train_id." AND a.state = 0 ORDER BY a.sort ASC";
				$train_item	=	$db->get_all($sql);
				if ( $train_item )
				{
					$result[$website_id]['train_item']	=	$train_item;
				}
			}
		}
		
		unset($list);
		
		if ( $class_memcache->set_value('website', $result) )
		{
			echo "succ";
		}
		else
		{
			echo 'failed';
		}
		break;
	default :
		
		break;
}
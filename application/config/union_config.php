<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| copy from config.php
|
*/
$config['language']	= 'chinese';

$config['base_title']	=	'网盟';


$config['base_res_script']	=	'resources/script/';
$config['base_res_style']	=	'resources/style/';
$config['base_res_image']	=	'resources/image/';
$config['base_res_plugins']	=	'resources/plugins/';
$config['base_att_verify']	=	'attachment/verify/';

$config['click_url']		=	'http://click.lezi.com/';


$config['encrypt_key']		=	'!&@6AJ&D6(@%*&DF^95#)(';
$config['secret_key_filename']	=	'lzunion.txt';

$config['bank_list']=array(
	'BOC'=>'中国银行',
	'ABC'=>'中国农业银行',
	'ICBC'=>'中国工商银行',
	'CCB'=>'中国建设银行'
);

$config['website_type']		=	array (
	'1'	=> '门户',
	'2'	=> '搜索',
	'3'	=> '商城',
	'4'	=> '行业',
	'5'	=> '汽车',
	'6'	=> '住房',
	'7'	=> '游戏',
	'8'	=> '动漫',
	'9'	=> '软件',
	'10'	=> '影视',
	'11'	=> '音乐',
	'12'	=> '手机',
	'13'	=> '娱乐',
	'14'	=> '图片',
	'15'	=> '书籍',
	'16'	=> '教育',
	'17'	=> '医疗',
	'18'	=> '旅游',
	'19'	=> '体育',
	'20'	=> '论坛',
	'21'	=> '博客',
	'22'	=> '热线',
	'23'	=> '导航'
);
$config['website_group']	=	array (
	'1'	=> '跑量组',
	'2'	=> 'CPS组',
	'3'	=> 'CPA组',
	'4'	=> '让人气足组'
);
$config['website_rate'] 	=	array (
	'deduct_rate'	=> '95.00',
	'cost_rate'	=> '4.2'
);
$config['website_cost_rate_unit']	=	1000;

$config['advert_cost_mode']	=	array (
	'1'	=> array (
				'CPM'	=> '千次印象计费'
		),
	'2'	=> array (	
				'CPC'	=> '单次点击计费'
		),
	'3'	=> array (
				'CPS'	=> '营销效果计费'
		)
);
$config['advert_default_user']		=	'ttt';

$config['member_user_type']			=	0;
$config['website_master_user_type']	=	1;
$config['advertiser_user_type']		=	2;
$config['manager_user_type']		=	9;
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" type="text/css" href="<?php base_res_style();?>style.css" />
<style type="text/css">
.cols-3 li {
	padding-top:10px;
}
</style>
</head>
<body>
<?php
	if( !empty($user_info) )
	{
?>
<div class="container">	
<a href="<?php echo site_url("admin/advances");?>">返回</a>
<fieldset>
	<legend><?php echo $user_info['user_name'];?>的个人信息</legend>
	<ul  class="cols-3">
		<li>
			<strong>基本信息</strong>
		</li>
		<li>
			通行证账号：<?php echo $user_info['user_name'];?>
		</li>
		<li>
			真实姓名：<?php echo $user_info['true_name'];?>
		</li>
		<li>
			联系邮箱：<?php echo $user_info['email'];?>
		</li>
		<li>
			QQ号码：<?php echo $user_info['qq'];?>
		</li>
		<li>
			手机号码：<?php echo $user_info['mobile_phone'];?>
		</li>
	</ul>
	
	<ul class="cols-3">
		<li>
			<strong>银行账户信息</strong>
		</li>
		<li>
			付款银行：<?php if( !empty($user_info['bank_code']) ) { echo $bank_list[ $user_info['bank_code'] ]; }?>
		</li>
		<li>
			银行卡号：<?php echo $user_info['bank_card'];?>
		</li>
	</ul>
	
	<ul class="cols-3">
		<li>
			<strong>身份证：</strong>
		</li>
		<li>
			<img src="<?php echo base_att_verify()."/".$user_info['obverse_identity_thumb'];?>" border="0"/> 
		</li>
		<li>
			<img src="<?php echo base_att_verify()."/".$user_info['reverse_identity_thumb'];?>" border="0" />
		</li>
	</ul>
</fieldset>
	
<?php	
	}
?>
</div>
</body>
</html>
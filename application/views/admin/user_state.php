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
<div class="container">
<p><a href="<?php echo site_url("admin/user/?web_owner=".$web_owner."&amp;state=".$state."&amp;type=".$type."&amp;per_page=".$per_page."")?>">返回</a></p>
<form action="<?php echo site_url("admin/user/update_state/")?>" method="post">
<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
<input type="hidden" name="per_page" value="<?php echo $per_page;?>" />
<input type="hidden" name="web_owner" value="<?php echo $web_owner;?>" />
<input type="hidden" name="state" value="<?php echo $state;?>" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<?php
	if( !empty($user_info) )
	{
?>
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
	<ul class="cols-3">
		<li class="s-search-btn colspan-2" style="width:100%;margin:0;">
			<input type="hidden" value="0" name="user_state" />
			<input type="submit" class="btn btn-default" value="审核通过"/>
		</li>
	</ul>
</fieldset>
<?php	
	}
	else
	{
?>
	<fieldset>
		<legend>修改状态</legend>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="table">
			<tbody align="right">
				<tr>
					<td>用户名：</td>
					<td class="pad-left">
						<input type="text" value="<?php echo $user_name;?>" id="username" name="username" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td>状态：</td>
					<td class="pad-left">
						<input type="radio" name="user_state" value="0" <?php if($is_locked==0) { echo "checked='checked'";}; ?> /> 正常  
						<input type="radio" name="user_state" value="1" <?php if($is_locked==1) { echo "checked='checked'";}; ?> /> 锁定 
					</td>
				</tr>
			</tbody>	
		</table>
		<div class="button-bar">
			<input type="submit" id="sub_btn" value="确定" />
		</div>
	</fieldset>
<?php
	}
?>
</form>
</div>
</body>
</html>
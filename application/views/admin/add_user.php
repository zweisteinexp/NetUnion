<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" type="text/css" href="<?php base_res_style();?>style.css" />
	<link rel="stylesheet" href="<?php base_res_style();?>jquery-ui-1.8.13.custom.css" type="text/css" />
    <script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
	<script src="<?php base_res_script();?>jquery-ui-1.8.13.custom.min.js" type="text/javascript" ></script>
<style type="text/css">
body{font:12px arial;background:#fff;}
</style>
</head>
<body>
<div class="container">
	<p align="left"><a href="<?php echo site_url("admin/user/")?>">返回</a></p>
	<fieldset>
		<legend>新增用户</legend>
		<form action="">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="table">
			<tbody align="right">
				<tr>
					<td>用户名：</td>
					<td class="pad-left">
						<input type="text" class="text" name="username" id="username"  />
					</td>
				</tr>
				<tr>
					<td>登录密码：</td>
					<td class="pad-left">
						<input type="password" class="text" name="pwd" id="pwd"  />
					</td>
				</tr>
				<tr>
					<td>确认密码：</td>
					<td class="pad-left"><input type="password" class="text" name="ck_pwd" id="ck_pwd" /></td>
				</tr>
				<tr>
					<td>姓名：</td>
					<td class="pad-left"><input type="text" id="truename" name="truename" /></td>
				</tr>
				<tr>
					<td>用户类型</td>
					<td class="pad-left">
					  <select name ="type" id="type">
						<?php
							foreach($user_type as $key=>$value)
							{
						?>
							<option value="<?php echo $key;?>">
								<?php echo $value;?>
							</option>
						<?php
							}
						?>
					  </select>
					</td>
				</tr>
				<tr>
					<td>用户状态：</td>
					<td class="pad-left">
					  <select name ="state" id="state">
						<?php
							foreach($user_state as $key=>$value)
							{
						?>
							<option value="<?php echo $key;?>">
								<?php echo $value;?>
							</option>
						<?php
							}
						?>
					  </select>
					</td>
				</tr>
				
			</tbody>
		</table>
		<div class="button-bar">
			<input type="button" id="add_user" value="提交" />
		</div>
		</form>
	</fieldset>
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	
	$("#add_user").click(function(){
		var username=$("#username").val();
		var pwd=$("#pwd").val();
		var ck_pwd=$("#ck_pwd").val();
		var truename=$("#truename").val();
		var type = $("#type").val();
		var state = $("#state").val();
		if ( username == null || username == '') 
		{
			alert('登录账号不能为空!');
			return false;
		}
		if ( pwd == null || pwd == '' ) {
			alert('登录密码不能为空!');
			return false;
		}
		if ( ck_pwd == null || ck_pwd == '' ) 
		{
			alert('请重复输入密码!');
			return false;
		}
		if(pwd!=ck_pwd)
		{
			alert("两次输入的密码不相同!");
			return false;
		}
		if ( truename == null || truename == '') 
		{
			alert('姓名不能为空!');
			return false;
		}
		
		
		$.post("<?php echo site_url('admin/user/add');?>",{username:username,pwd:pwd,truename:truename,type:type,state:state},function(data){
			if(isNaN(data))
			{
				alert(data);
				return false;
			}
			else
			{
				alert("添加成功");
				location.href="<?php echo site_url('admin/user/');?>";
			}
		});
	});
});
/* ]]> */
</script>
</body>
</html>
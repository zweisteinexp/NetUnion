<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" type="text/css" href="<?php base_res_style();?>style.css" />
	<script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
</head>
<body>
<div class="container">
	<p align="left"><a href="<?php echo site_url("admin/user/")?>">返回</a></p>	
	<fieldset>
		<legend>修改密码</legend>
		<?php 
			echo $msg;
		?>
		<form action="<?php echo site_url("admin/user/update_pwd/")?>" method="post">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="table">
			<tbody align="right">
				<tr>
					<td>用户名：</td>
					<td class="pad-left">
						<input type="text" value="<?php echo $user_name;?>" id="username" name="username" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td>新密码：</td>
					<td class="pad-left"><input type="password"  id="pwd" name="pwd" /></td>
				</tr>
				<tr>
					<td>确认密码：</td>
					<td class="pad-left"><input type="password"  id="ck_pwd" name="ck_pwd" /></td>
				</tr>
			</tbody>
		</table>
		
		<div class="button-bar">
			<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
			<input type="submit" id="sub_btn" value="确定" />
		</div>
		</form>
	</fieldset>
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#sub_btn").click(function(){
		var pwd=$("#pwd").val();
		var ck_pwd=$("#ck_pwd").val();
		
		if ( pwd == null || pwd == '' ) {
			alert('新密码不能为空!');
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

	});
});
/* ]]> */
</script>
</body>
</html>
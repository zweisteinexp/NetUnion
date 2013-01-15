<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>乐子联盟</title>
<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
<script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
</head>

<body>
<div class="container">
	<div class="main">
		<h3>注册账号</h3>
			<br class="clear" />
			<?php 
			if(!empty( $msg ))
			{
			?>
			<div class='msg error'>
			<?php 
				echo $msg;
			?>
			</div>
			<?php 
				}
			?>
			<form action="<?php echo site_url('register');?>" method="post" id="reg_sub">
				<table width="100%" border="0" cellspacing="0" cellpadding="3">
				  <tr>
					<td width="126" align="right">登录账号：</td>
					<td><input type="text" class="text" name="username" id="username" value="<?php echo set_value('username') ;?>" style=" width:155px;" /></td>
				  </tr>
				  <tr>
					<td width="126" align="right">登录密码：</td>
					<td><input type="password" class="text" name="pwd" id="pwd" value="<?php echo set_value('pwd') ;?>" style=" width:155px;" /> </td>
				  </tr>
				  <tr>
					<td width="126" align="right">确认密码：</td>
					<td><input type="password" class="text" name="ck_pwd" id="ck_pwd" value="<?php echo set_value('ck_pwd') ;?>" style=" width:155px;" /> </td>
				  </tr>
				  <tr>
					<td width="126" align="right">安全邮箱：</td>
					<td><input type="text" class="text" name="email" id="email" value="<?php echo set_value('email') ;?>" style=" width:155px;" /> </td>
				  </tr>
				  <tr>
					<td width="126" align="right"></td>
					<td height="30"><input type="checkbox" name="agree"  checked="checked" /> 我已经看过并同意<a target="_blank" href="<?php echo site_url("register/agreement");?>">《乐子联盟协议》</a></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td style="padding-top:10px;"><a href="javascript:void(0);" id="reg_btn" class="golden_btn">提交</a></td>
				  </tr>
				</table>
			</form>
	</div>
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#reg_btn").click(function(){
		var username=$("#username").val();
		var pwd=$("#pwd").val();
		var ck_pwd=$("#ck_pwd").val();
		var email=$("#email").val();
		var check_box = $("input[name='agree']").is(":checked");
		if ( username == null || username == '') 
		{
			alert('登录账号不能为空!');
			return false;
		}
		var patrn=/^[\w]+$/;
		if (!patrn.exec(username))
		{
			alert("登录账号仅限字母、数字、下划线！");
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


		patrn = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		if (!patrn.exec(email))
		{
			alert("请输入正确的邮箱地址！");
			return false;
		}
		
		if(check_box!=true)
		{
			alert("您需要同意用户协议后才能注册！");
			return false;
		}
		
		$("#reg_sub").submit();
		
	});
});
/* ]]> */
</script>
</body>
</html>

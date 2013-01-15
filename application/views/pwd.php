<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>乐子联盟</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
	<script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
</head>
<body>
<div class="container">
	<div class="main">
		<h3>找回密码</h3>
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
			<form action="<?php echo site_url("pwd");?>" method="post" id="pwd_sub">
				<table width="100%" border="0" cellspacing="0" cellpadding="3">
				  <tr>
					<td width="126" align="right">登录账号：</td>
					<td><input type="text" class="text" name="username" id="username" value="<?php echo set_value('username') ;?>" style=" width:155px;" /></td>
				  </tr>
				  <tr>
					<td width="126" align="right">安全邮箱：</td>
					<td><input type="text" class="text" name="email" id="email" value="<?php echo set_value('email') ;?>"  style=" width:155px;" /> </td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td style="padding-top:10px;"><a href="javascript:void(0);" id="sub_btn" class="golden_btn">确定</a></td>
				  </tr>
				</table>
			</form>		
	</div>
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#sub_btn").click(function(){
		var username = $("#username").val();
		var email = $("#email").val();
		if ( username == null || username == '') {
			alert('登录账号不能为空!');
			return false;
		}
		
		var patrn = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		if (!patrn.exec(email))
		{
			alert("请输入正确的邮箱地址！");
			return false;
		}
		
		$("#pwd_sub").submit();
	});
});
/* ]]> */
</script>
</body>
</html>
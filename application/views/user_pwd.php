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
		<?php require('top.php');?>
		<div class="main">
			<h3>修改密码</h3>
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
			<form id="update_pwd" action="<?php echo site_url("user_info/user_pwd")?>" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="3">
				  <tr>
					<td width="126" align="right">当前密码：</td>
					<td><input type="password" class="text" name="pwd" id="pwd" value="<?php echo set_value('pwd') ;?>" style=" width:155px;" /></td>
				  </tr>
				  <tr>
					<td width="126" align="right">新密码：</td>
					<td><input type="password" class="text" name="new_pwd" id="new_pwd" value="<?php echo set_value('new_pwd') ;?>" style=" width:155px;" /> </td>
				  </tr>
				  <tr>
					<td width="126" align="right">确认密码：</td>
					<td><input type="password" class="text" name="ck_pwd" id="ck_pwd" value="<?php echo set_value('ck_pwd') ;?>" style=" width:155px;"  /> </td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td style="padding-top:10px;"><a href="javascript:void(0);" id="ck_btn" class="golden_btn">保存修改</a></td>
				  </tr>
				</table>
			</form>
		</div>	
	</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#ck_btn").click(function(){
		var pwd=$("#pwd").val();
		var new_pwd=$("#new_pwd").val();
		var ck_pwd=$("#ck_pwd").val();
		if ( pwd == null || pwd == '') 
		{
			alert('当前密码不能为空!');
			return false;
		}
		
		if ( new_pwd == null || new_pwd == '') 
		{
			alert('新密码不能为空!');
			return false;
		}
		
		if ( ck_pwd == null || ck_pwd == '') 
		{
			alert('确认密码不能为空!');
			return false;
		}
		if(new_pwd!=ck_pwd)
		{
			alert("两次输入的密码不相同!");
			return false;
		}
		
		$('#update_pwd').submit();
	});
});
/* ]]> */
</script>
</body>
</html>
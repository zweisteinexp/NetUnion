<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧栏</title>
<style type="text/css">
<!--
body{
	background: url(<?php base_res_image()?>left.gif) repeat-y;
}
body, h1, h2, h3, h4, h5, h6, hr, p, blockquote,dl, dt, dd, ul, ol, li,pre,fieldset, lengend, button,input, textarea,th, td,form{
    margin:0;
    padding:0;
}
body,button, input, select, textarea {
	font: 12px/1 "\5b8b\4f53";
}
a {text-decoration: none; color:#000; }
a:hover{text-decoration: underline; }
h1,h2,h3,h4, h5, h6 {font-size: 100%; }
ul, ol {list-style: none;}
.navlink{
	padding:8px 0 7px 30px;
	background:url(<?php base_res_image()?>nav02.gif) 14px 0 no-repeat;
	overflow:hidden;
	zoom:1;
}
.navlink span{
	cursor:pointer;
	width:150px;
	display:inline-block;
	margin-left:3px;
}
.navlink li{
	overflow:hidden;
	zoom:1;
}
.linklist{
	padding:15px 0 0 20px;
}
.linklist li{
	padding:3px 0 2px 0px;
	line-height:20px;
}
.linklist li img{
	position:relative;
	margin-right:5px;
}
.welcome{
	margin:0 0 0 14px;
	border-top:solid 1px #b8c9d6;
}
.welcome ul{
	height:55px;
	padding-left:60px;
}
.welcome ul li{
	line-height:25px;
}
-->
</style>
</head>
<body>
<div class="welcome" style="background:url(<?php base_res_image()?>nav01.gif) repeat-y;">
	<ul style="background:url(<?php base_res_image()?>ico02.gif) 5px 5px no-repeat;">
		<li>您好，<span class="left-font02"><?php echo $user_name?></span></li>
		<li>[&nbsp;<a href="<?php echo site_url('logout');?>" target="_top" class="left-font01">退出</a>&nbsp;]</li>
	</ul>
</div>
<ul class="navlink">
	<li><img name="img1" id="img1" src="<?php base_res_image();?>ico04.gif" width="8" height="11" /><a href="<?php echo site_url("user_info");?>" target="mainFrame" class="left-font03" >个人信息</a></li>
	<li id="subtree1" >
		<ul class="linklist">
			<li><img id="xiaotu1" src="<?php base_res_image();?>ico06.gif" width="8" height="12" /><a href="<?php echo site_url("user_info/user_pwd");?>" target="mainFrame" class="left-font03" onClick="focusMenu('1');">修改密码</a></li>
		</ul>
	</li>
	<li><img name="img1" id="img1" src="<?php base_res_image();?>ico04.gif" width="8" height="11" /><a href="<?php echo site_url("user_order");?>" target="mainFrame" class="left-font03" >支付结算</a></li>
</ul>
</body>
</html>

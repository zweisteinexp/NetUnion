<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
<title>乐子联盟</title>
<style type="text/css">
*, body, div,p,form,input, a{margin:0;padding:0;}
body{
	background:#ddedf4 url(<?php base_res_image()?>x_bg.png) repeat-x 0 0;
	font-size:14px;
	margin-bottom:8px;
	width:960px; 
	margin:0 auto;
	font-family:Arial, Helvetica, sans-serif;
}
.header, .box_tab, .box_bt, .btn span input, .google .search h2{background:url(<?php base_res_image()?>box.jpg) no-repeat 0 0;}
.header{
	width:960px;
	height:182px;
}
.header h1{ 
	overflow:hidden;
	text-indent:-9999em;
	display:block;
}
.box{
	width:592px;
	background:url(<?php base_res_image()?>x_bg1.png) repeat-y 0 0;
	margin:0 auto;
	
}
.box_tab{
	background-position:0 -274px;
	min-height:300px;
	_height:300px;
	padding:50px 30px 15px;
}
.box_bt{
	width:592px;
	height:90px;
	background-position:0px -183px;
}
.login{
	width:350px;
	margin:0 auto;
}
.login .field{
	height:27px;
	padding-bottom:10px;
	overflow:hidden;
	zoom:1;
	
	
}
.login .field label{
	float:left;
	width:60px;
	text-align:right;
	line-height:24px;
}
.login .field .text_input, .google .text{
	height:26px;
	border-right:1px solid #cecece;
	border-bottom:1px solid #cecece;
	margin-right:5px;
	float:left;
}
.login .field .text_input input, .google .text input{
	border-color:#cecece #999 #999 #cecece;
	border-width:1px;
	border-style:solid;
	padding:3px 5px 2px;
	font-size:14px;
	color:#000;
	height:19px;
	width:180px;
}
.login .field .text_input1 input{ width:70px;}
.field a, #idVdImg{
	display:inlin-block;
	text-decoration:none;
	font-size:12px;
	color:#2692C6;
	line-height:26px;
	vertical-align:middle;
}
.field a, #idVdImg img{vertical-align:middle;}
.login .submit{ 
	height:28px;
	padding-left:63px;
	font-size:12px;
	color:#999;
	overflow:hidden;
	zoom:1;
	
}
.btn{
	border-right:1px solid #cecece;
	border-bottom:1px solid #cecece;
	float: left;
	margin-right:5px;
}
.btn span{
	border-color:#cecece #999 #999 #cecece;
	border-width:1px;
	border-style:solid;
	height:26px;
	display:inline-block;
}
.btn span input{
	background-position:-810px -223px;
	background-repeat:repeat-x;
	font-size:14px;
	color:#000;
	height:26px;
	cursor:pointer;
	border:none;
	display:block;
	padding:0 8px;
}
#clear_cookie, .saveCookie{
	overflow:hidden;
	line-height:27px;
	vertical-align:bottom;
	

}
.submit #clear_cookie{
	cursor:pointer;
	margin-left:10px;
	width:120px;
}

.google{
	width:462px;
	margin:20px auto 5px;
	background:url(<?php base_res_image()?>line.png) no-repeat center top;
	padding:20px 0 0 50px;
}
.google .search{ overflow:hidden; zoom:1; padding-bottom:5px;}
.google .search h2{
	width:79px;
	height:30px;
	float:left;
	text-indent:-999em;
	overflow:hidden;
	background-position:right -183px;
	margin-right:6px;
}
.google .text { height:28px;}
.google .text input{ width:240px; height:21px;}
.google_select{ 
	padding-left:86px;
	line-height:26px;
	font-size:12px;
	color:#999;
}
.list{
	overflow:hidden;
	zoom:1;
	font-size:14px;
	color:#;
	width:512px;
	margin:10px auto 0;
	line-height:24px;
}
.list dt{
	width:50px;
	float:left;
	font-weight:bold;
	display:block;
}
.list dd{
	float:left;
	width:460px;
}
.list dd a{
	color:#007aab;
	margin-right:10px;
	text-decoration:none;
	white-space:nowrap;
	overflow:hidden;
}
.list dd a:hover{
	text-decoration:underline;
}
.hidden{display:none;}
#errorBox {
	width:260px;
	line-height:22px;
	text-align:center;
	border:1px solid #f00;
	background:#fff8e7;
	color:#F00;
	padding:5px;
	margin:0 0 5px 30px;
}
</style>
</head>
<body>
<div class="header">

	<h1></h1>

</div>

<div class="box">

	<div class="box_tab" style="min-height:100px;">

   		<div class="login"><!--login-->

        	<form action="">

			<input name="forward" type="hidden" value="" />

			<div class="error-box hidden" id="errorBox"></div><!-- 错误提示框 -->

            <div class="field">

                <label>用户名：</label>

                <span class="text_input"><input name="username" id="username" size="20" tabindex="1" type="text"/></span>

            </div>

            <div class="field">

                <label>密&nbsp;&nbsp;&nbsp;&nbsp;码：</label>

                <span class="text_input"><input name="pwd" id="pwd" size="20" tabindex="2" type="password"/></span>

            </div>

			<div class="field" id="show_check_code"  style="display: none;">

                <label>验证码：</label>  

                <span class="text_input1 text_input"><input name="vdcode" class="code_input" size="10" tabindex="3" type="text"/></span>

                <a class="vdimg" href="#">看不清？</a>

                <span id="idVdImg" class="hidden"><img src="" id="idVdimgck" class="vdimg" alt="验证码"/></span>             

            </div>

            <div class="submit">

                <span class="btn">

					<span><input  id="login_btn" class="wp-submit" value="登 录" tabindex="100" type="button"/></span>

                </span>
				 <span class="btn">

					<span><input  id="reg_btn" class="wp-submit" value="注 册" tabindex="100" type="button"/></span>

                </span>
                <span class="saveCookie"><a href="<?php echo site_url("pwd");?>">忘记密码？</a></span>

            	
            </div>

            </form>

        </div><!--end login-->
    </div>

	<div class="box_bt"></div>

</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#login_btn").click(function(){
		var username	=	$('#username').val();
		var pwd		=	$('#pwd').val();
		if ( username == null || username == '') {
			alert('用户名不能为空!');
			return false;
		}
		if ( pwd == null || pwd == '' ) {
			alert('密码不能为空!');
			return false;
		}

		$.post("<?php echo site_url('login/check_login');?>",{username:username,pwd:pwd},function(data){
			if(isNaN(data))
			{
				alert(data);
				return false;
			}
			else
			{
				
				location.href="<?php echo site_url('data');?>";
			}
		});
	});
	
	$("#reg_btn").click(function(){
		location.href="<?php echo site_url('register');?>";
	})
});
/* ]]> */
</script>
</body>

</html>
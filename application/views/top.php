<script src="<?php base_res_script();?>jquery-1.5.1.min.js"></script>
<script>
$(document).ready(function (){
	$('.menu ul li a').mouseover(function (){
		$(this).parent('li').addClass('active');
		$(this).parent('li').siblings('li').removeClass('active');
		$(this).parent('li').siblings('li').children('ul').hide();
		$(this).siblings('ul').show();
	});
	$('#<?php echo $current_class?>').addClass('active').children('ul').show();
	
});
</script>
<div class="Header">
	<div class="welcome">
		欢迎你，<?php echo $user_name;?> <a href="<?php echo site_url("logout")?>" title="退出" class="red_text">退出</a>
	</div>
	<div class="logo">
		<a title="乐子联盟" href="#">乐子联盟</a>
	</div>
	<div class="services">
		<a title="联系客服" href="#" class="blue_link">联系客服 
			<div class="layer">
				<em></em>
				<p>电话：0571-88888888<br />ＱＱ：<img src="<?php base_res_image();?>QQ.gif" height="22" width="74" title="QQ在线" alt="QQ在线" align="absmiddle"/> <br />邮箱：adsf@asl.com</p>
			</div>
		</a>
	</div>           
</div>
<div class="menu">
	<ul>
		<li id="home">
			<a href="<?php echo site_url('data');?>">我的首页</a>
		</li>
		<li id="website">
			<a href="<?php echo site_url('website');?>">网站管理</a>
			<ul style="display: none;">
				<li><a href="<?php echo site_url('website/index'); ?>">网站管理</a></li>
				<li><a href="<?php echo site_url('website/newone'); ?>">新增网站</a></li>
				<li><a href="<?php echo site_url('website/sitecode'); ?>">获取代码</a></li>
			</ul>
		</li>
		<li id="data"><a href="<?php echo site_url('data');?>">数据统计</a></li>
		<li id="user_order"><a href="<?php echo site_url('user_order');?>">支付结算</a></li>
		<li id="user_info">
			<a href="<?php echo site_url('user_info');?>">会员信息</a>
			<ul style="display: none;">
				<li><a href="<?php echo site_url('user_info');?>">修改信息</a></li>
				<li><a href="<?php echo site_url('user_info/user_pwd');?>">修改密码</a></li>
			</ul>
		</li>

	</ul>
</div>

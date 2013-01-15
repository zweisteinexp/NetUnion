<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>站点详情</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="<?php base_res_style(); ?>style.css" type="text/css"/>
</head>

<body>
<div class="container">
	<br/>
	<p><a href="javascript:history.go(-1);">返回</a></p>
	<br/>
	
	<fieldset>
		<legend>站点详情</legend>
		
		<table class="table" border="0" cellspacing="0" cellpadding="0"><tbody align="right">
			<tr>
				<td width="200px;">网站ID：</td>
				<td class="pad-left"><span><?php echo $website['id'] ;?></span></td>
			</tr>
			<tr>
				<td>网站主用户：</td>
				<td class="pad-left"><span><?php echo $website['user_name'] ;?></span></td>
			</tr>
			<tr>
				<td>网站名称：</td>
				<td class="pad-left"><span><?php echo $website['website_name'] ;?></span></td>
			</tr>
			<tr>
				<td>网站域名：</td>
				<td class="pad-left"><span><?php echo $website['domain'] ;?></span></td>
			</tr>
			<tr>
				<td>网站ICP备案号：</td>
				<td class="pad-left"><span><?php echo $website['icp'] ;?></span></td>
			</tr>
			<tr>
				<td>网站描述：</td>
				<td class="pad-left"><span><?php echo $website['description'] ;?></span></td>
			</tr>
			<tr>
				<td>选项：</td>
				<td class="pad-left"><span><?php echo $website['options_str'] ;?></span></td>
			</tr>
			<tr>
				<td>状态：</td>
				<td class="pad-left"><span><?php echo $website['state_str'] ;?></span>
					<span><?php echo $website['cooperative_str'] ;?><span></td>
			</tr>
			<tr>
				<td>结算周期：</td>
				<td class="pad-left"><span><?php echo $website['settle_str'] ;?></span></td>
			</tr>
			<tr>
				<td>扣量比例：</td>
				<td class="pad-left"><span><?php echo $website['deduct_rate_str'] ;?></span></td>
			</tr>
			<tr>
				<td>分成比例：</td>
				<td class="pad-left"><span><?php echo $website['cost_rate_str'] ;?></span></td>
			</tr>
			<tr>
				<td>网站组别：</td>
				<td class="pad-left"><span><?php echo $website['group_str'] ;?></span></td>
			</tr>
			<tr>
				<td>网站类别：</td>
				<td class="pad-left"><span><?php echo $website['types_str'] ;?></span></td>
			</tr>
			<tr>
				<td>验证码：</td>
				<td class="pad-left"><span><?php echo $website['secret_key'] ;?></span></td>
			</tr>
			<tr>
				<td>加盟时间：</td>
				<td class="pad-left"><span><?php echo $website['add_time_str'] ;?></span></td>
			</tr>
		</tbody></table>
	</fieldset>
</div>

</body>
</html>

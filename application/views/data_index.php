<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>乐子联盟</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="container">
	
	<?php require('top.php'); ?>
        
        <div class="main">
        	<h3>数据统计</h3>

		<div>
	<?php if ( empty($websites)) { ?>
		<p style="text-align: center;margin: 20px;">暂时没有站点，你可以 <a href="<?php echo site_url('website/newone') ;?>">添加站点</a></p>
	<?php } else { ?> 
		<table id="block-container" align="center" cellpadding="0" cellspacing="0" border="0">
	<?php 	for ($i = 0, $length = count($websites); $i < $length; $i++) { 
			if ($i % 3 == 0) { echo "<tr>"; } ?> 
			<td><div class="block">
				<span><a href="<?php echo site_url('data/show/' . $websites[$i]['id']); ?>"><?php echo $websites[$i]['domain']; ?></a></span>
				<div><?php echo $websites[$i]['description']; ?></div>
			</div></td>
	<?php 		if ($i % 3 == 2) { echo "</tr>"; } 
		}
		if ($i % 3 != 0) { echo "</tr>"; } ?>
		</table>
	<?php } ?> 
		</div>

		<div class="tip">
			<h4>友情提示</h4>
		</div> 
	</div>	
	</div>
</body>
</html>

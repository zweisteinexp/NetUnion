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
        	<h3>网站列表</h3>
        	<table class="gray_table" align="center" style="width: 100%; margin-top: 20px;margin-bottom:30px;" border="0" cellspacing="0" cellpadding="3">
			<thead>
				<tr>
					<!-- <td align="center">网站ID</td> -->
					<td align="center">网站名称</td>
					<td align="center">网址</td>
					<td align="center">ICP</td>
					<td align="center">状态</td>
					<td align="center">结算周期</td>
					<td align="center">类别</td>
					<!-- <td align="center">加盟日期</td> -->
					<td align="center">操作</td>
				</tr>
			</thead>
			<tbody>
			<?php if ($websites) { 
				foreach ($websites as $item) { ?> 
				<tr>
					<!-- <td align="center"><?php echo $item['id'] ;?>&nbsp;</td> -->
					<td style="text-align: left;" title="<?php echo $item['description'] ;?>"><?php echo $item['website_name'] ;?>&nbsp;</td>
					<td style="text-align: left;"><?php echo $item['domain'];?>&nbsp;</td>
					<td align="center"><?php echo $item['icp'] ;?>&nbsp;</td>
					<td align="center"><?php echo $item['state_str'] ;?>&nbsp;</td>
					<td align="center"><?php echo $item['settle_type_str'] ;?>&nbsp;</td>
					<td align="center"><?php echo $item['types_str'] ;?>&nbsp;</td>
					<!-- <td align="center"><?php echo $item['add_time_str'] ;?>&nbsp;</td> -->
					<td align="center"><a href="<?php echo site_url('website/modify/' . $item['id']); ?>">修改</a> | 
						<a href="<?php echo site_url('website/sitecode/' . $item['id']); ?>">获取代码</a></td>
				</tr>
			<?php   }
			      } else { ?> 
				<tr><td colspan="9" style="text-align:center;">暂无数据</td></tr>
			<?php } ?> 
			</tboby>
		</table>

		<div class="tip">
			<h4>友情提示</h4>
		</div> 
	</div>	
	</div>
</body>
</html>

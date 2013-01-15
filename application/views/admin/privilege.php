<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限控制</title>
<script src="<?php base_res_script()?>jquery-1.5.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php base_res_style()?>style.css" />
</head>

<body>
<div class="top-bar">
	<ul>
		<li><a href="<?php echo site_url('admin/privilege/insert');?>">新增版块</a></li>
	</ul>
</div>
<br />
<div class="container">
	<fieldset>
		<legend>版块管理</legend>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="table">
			<thead>
				<tr>
					<th><input type="checkbox" /></th>
					<th>编号</th>
					<th>名称</th>
					<th>父级名称</th>
					<th align="center">开放状态</th>
					<th align="center">排序</th>
				</tr>
			</thead>
			<?php
			foreach ($menu as $value)
			{
			?>
			<tr>
				<td align="center"><input type="checkbox" /></td>
				<td><?php echo $value['menu_code']?></td>
				<td><a href="<?php echo site_url('admin/privilege/insert?id='.$value['id']);?>"><?php echo $value['menu_name']?></a></td>
				<td><?php echo $value['parent_menu_id'] > 0 ? $main_menu[$value['parent_menu_id']] : '主父类';?></td>
				<td align="center"><?php echo $value['display'] == 1 ? '显示' : '隐藏';?></td>
				<td align="center">0</td>
			</tr>
			<?php
			}
			?>
		</table>
	</fieldset>
</div>

</body>
</html>

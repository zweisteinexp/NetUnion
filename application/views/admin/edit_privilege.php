<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>增加新权限模版</title>
<script src="<?php base_res_script()?>jquery-1.5.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php base_res_style()?>style.css" />
</head>

<body>
<div class="top-bar">
	<ul>
		<li><a href="<?php echo site_url('admin/privilege/index');?>">版块列表</a></li>
	</ul>
</div>
<br />

<div class="container">
	<fieldset>
		<legend><?php echo $row ? '编辑' : '新增';?>权限版块</legend>
		<form action="<?php echo site_url('admin/privilege/execute');?>" method="post">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="table">
			<tr>
				<td>编号</td>
				<td><input type="text" value="<?php echo $row['menu_code']?>" name="menu_code" <?php if ($row['menu_code']) {echo 'readonly';}?> /></td>
			</tr>
			<tr>
				<td>父级类别</td>
				<td>
				<select name="parent_menu_id">
					<option value="0">作为父类</option>
					<?php
					foreach ($main_menu as $key=>$value)
					{
						$selected	=	'';
						if ( $row['parent_menu_id'] && $row['parent_menu_id'] == $key )
						{
							$selected = ' selected ';
						}
						echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
					}
					?>
				</select>
				</td>
			</tr>
			<tr>
				<td>版块名称</td>
				<td><input type="text" name="menu_name" value="<?php echo $row['menu_name']?>" /></td>
			</tr>
			<tr>
				<td>子权限</td>
				<td><textarea cols="70" rows="5" name="children_menu"><?php echo $row['children_menu']?></textarea></td>
			</tr>
			<tr>
				<td>状态</td>
				<td><input type="radio" name="display" value="1" <?php echo $row['display'] == 1 ? 'checked' : '';?> /> 开放 <input type="radio" name="display" value="0" <?php echo $row['display'] != 1 ? 'checked' : '';?> /> 隐藏 </td>
			</tr>
		</table>
		
		<div class="button-bar">
			<input type="submit" name="Submit" value="<?php echo $row ? ' 编 辑 ' : ' 新 增 ';?>" />
			<?php if ($row['id']) :?>
			<input type="hidden" name="id" value="<?php echo $row['id'];?>" />
			<?php endif;?>
			<input type="button" name="Submit2" value=" 返 回 " onclick="javascript:history.back()" />
		</div>
		</form>
	</fieldset>
</div>
</body>
</html>

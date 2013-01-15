<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>权限管理</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<style type="text/css">
		span.cost_editable, span.deduct_editable { cursor: pointer; border: 1px solid gray; }
	</style>
	<link rel="stylesheet" href="<?php base_res_style(); ?>style.css" type="text/css"/>
	<link rel="stylesheet" href="<?php base_res_style(); ?>jquery-ui-1.8.13.custom.css" type="text/css"/>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-1.5.1.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-ui-1.8.13.custom.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>ui.datepicker-zh-CN.js" > </script>
</head>

<body>
<div class="container">
	<fieldset>
		<legend>选择用户</legend>
		<select onchange="javascript:location.href='<?php echo site_url('admin/feature/index?uid=');?>'+this.value;">
			<option value="0">请选择管理员</option>
			<?php
			foreach ( $user_list as $value )
			{
				$selected	=	$m_user_id && $value['user_id'] == $m_user_id ? 'selected' : '';
			?>
			<option value="<?php echo $value['user_id'];?>" <?php echo $selected;?>><?php echo $value['user_name'];?></option>
			<?php
			}
			?>
		</select>
	</fieldset>
	<?php
	if ( @$m_user_id )
	{
	?>
	<fieldset>
		<legend>权限分配</legend>
		<form action="<?php echo site_url('admin/feature/edit_privilege');?>" method="POST">
		<table class="table" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th align="center" width="20%">功能模块名称</th>
					<th align="center">子权限</th>
					<th align="center">开放状态</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( $sub_menu )
				{
					foreach ( $sub_menu as $value )
					{
				?>
				<tr>
					<td align="left"><?php echo $parent_menu[$value['parent_id']]['name'] . ' - ' . $value['name'];?></td>
					<td align="left">
					<?php
					$children	=	explode(',', $value['children']);
					if ( $children )
					{
//						echo '<input type="checkbox" style="display:none;" name="privilege[]" value="'.$value['parent_id'].'" />';
						foreach ( $children  as $children_value)
						{
							if ( $children_value )
							{
								list($children_key, $children_value)	=	explode('=', $children_value);
								$selected	=	'';
								
								if ( $user_privilege == ',ALL,' || strpos($user_privilege, ','.$value['id'].'-'.$children_key.',') !== FALSE )
								{
									$selected	=	' checked ';
								}
								echo '<input type="checkbox" name="privilege[]" value="'.$value['id'].'-'.$children_key.'" '.$selected.' /> '.$children_value.'&nbsp;';
							}
						}
					}
					?>
					</td>
					<td align="center"><?php echo $value['display'] == 1 ? '显示' : '关闭';?></td>
				</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
		<input type="hidden" value="<?php echo $m_user_id?>" name="user_id" />
		<input type="submit" value="提交修改" class="btn btn-default" />
		</form>
	</fieldset>
	<?php
	}
	?>
</div>
</body>
</html>
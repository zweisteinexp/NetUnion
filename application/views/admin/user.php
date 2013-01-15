<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" type="text/css" href="<?php base_res_style();?>style.css" />
<style type="text/css">
.cols-3 li {
	padding-top:10px;
}
</style>	
</head>
<body>
<div class="container">
	<p align="left"><a href="<?php echo site_url("admin/user/user_add")?>">新增用户</a></p>
	<fieldset>
		<legend>用户查询</legend>
		<form action="" method="get">
			<ul class="cols-3">
				<li class="no-qos">
					<strong>用户名：</strong>
					<input type="text"  id="web_owner" name="web_owner" value="<?php echo $web_owner;?>"  /> 
				</li>
				<li>
					<strong>状态：</strong>
					<select name ="state">
					<option value="">全部</option>
					<?php
						foreach($user_state as $key=>$value)
						{
					?>
						<option value="<?php echo $key;?>" <?php if($state===$key){ echo " selected='selected'";}?>>
							<?php echo $value;?>
						</option>
					<?php
						}
					?>
				  </select>
				</li>
				<li>
					<strong>类型：</strong>
					<select name ="type">
					<option value="">全部</option>
					<?php
						foreach($user_type as $key=>$value)
						{
					?>
						<option value="<?php echo $key;?>" <?php if($type===$key){ echo " selected='selected'";}?>>
							<?php echo $value;?>
						</option>
					<?php
						}
					?>
				  </select>	  
				</li>
			</ul>
			<ul class="cols-3">
				<li class="s-search-btn colspan-2" style="width:100%;margin:0;">
					<input type="submit" class="btn btn-default" id="search_btn" value="点击查询" />
				</li>
			</ul>
		</form>
	</fieldset>
	<fieldset>
		<legend>用户列表</legend>
		<div id="idLists">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" id="myTable" class="table">
				<thead align="center">
					<tr>
						<th>编号</th>
						<th>用户名</th>
						<th>姓名</th>
						<th>状态</th>
						<th>类型</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody align="center">
					<?php
					if( !empty($user_list) )
					{
						foreach($user_list as $value)
						{
					?>
					<tr bgcolor="#FFFFFF">
						<td><?php echo $value['user_id'];?></td>
						<td><?php echo $value['user_name'];?></td>
						<td><?php echo $value['true_name'];?></td>
						<td class="<?php if( $value['is_locked']==0 ) { echo 'player';} elseif( $value['is_locked']==2 ) {echo 'wait'; } ?> "><?php echo $user_state[ $value['is_locked'] ];?></td>
						<td><?php echo $user_type[ $value['user_type'] ];?></td>
						<td align="center"><?php if($value['is_locked']==2) {?><a href="<?php echo site_url("admin/user/state/?uid=".$value['user_id']."&amp;show=1&amp;web_owner=".$web_owner."&amp;state=".$state."&amp;type=".$type."&amp;per_page=".$per_page."");?>">审核信息</a>&nbsp;<?php } ?>
						<a href="<?php echo site_url("admin/user/user_pwd/".$value['user_id']."/")?>">修改密码</a>&nbsp;
						<a href="<?php echo site_url("admin/user/state/?uid=".$value['user_id']."&amp;show=2&amp;web_owner=".$web_owner."&amp;state=".$state."&amp;type=".$type."&amp;per_page=".$per_page."");?>">修改状态</a></td>
					</tr>
					<?php
						}
					}
					else
					{
					?>
					<tr bgcolor="#FFFFFF"><td align="center" class="warning" colspan="6">暂无用户信息</td></tr>	
					<?php
					}
					if( !empty($page_link) )
					{
					?>
					<tr bgcolor="#FFFFFF"><td align="left" colspan="6"><?php echo $page_link;?></td></tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</fieldset>
</div>
</body>
</html>
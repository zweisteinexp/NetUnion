<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" type="text/css" href="<?php base_res_style();?>style.css" />
    <script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
</head>
<body>
<div class="container">
	<p align="left"><a href="<?php echo site_url("admin/advances/add")?>">新增预付款站点</a></p>
	<br/>
	<fieldset class="s_container">
		<legend>预付款站点搜索</legend>
		<form action="" method="get">
			<ul class="cols-3">
				<li class="no-qos">
					<strong>网站ID：</strong>
					<input type="text" name="web_id" id="web_id" value="<?php echo $web_id;?>" />  
				</li>
				<li>
					<strong>站长：</strong>
					<input type="text" name="web_owner" value="<?php echo $web_owner;?>" /> 
				</li>
				<li>
					<strong>完成状态：</strong>
					<select name="state">
						<option value="0">请选择完成状态</option>
						<option value="1" <?php if($state==1){ echo " selected='selected'";}?>>进行中</option>
						<option value="2" <?php if($state==2){ echo " selected='selected'";}?>>已完成</option>
						<option value="3" <?php if($state==3){ echo " selected='selected'";}?>>已超时(补量)</option>
					</select>
				</li>
			</ul>
			<ul class="cols-3">
				<li class="s-search-btn colspan-2" style="width:100%;margin:0;">
					<input type="submit" class="btn btn-default" value="点击查询" id="search_btn" />
				</li>
			</ul>
		</form>
	</fieldset>
	
	<fieldset>
		<legend>预付款站点列表</legend>
		<div id="idLists">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" id="myTable" class="table">
				<thead align="center">
					<tr>
						<th>网站ID</th>
						<th>网站</th>
						<th>站长</th>
						<th>预支付金额</th>
						<th>买断量</th>
						<th>开始时间</th>
						<th>结束时间</th>
						<th>已完成量（扣量后）</th>
						<th>完成状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody align="center">
					<?php
					if( !empty($advances_list) )
					{
						foreach($advances_list as $value)
						{
					?>
					
						<tr bgcolor="#FFFFFF">
							<td><?php echo $value['website_id'];?></td>
							<td><?php echo $value['website_name'];?></td>
							<td><?php echo $value['user_name'];?></td>
							<td><?php echo $value['advances_amount'];?></td>
							<td><?php echo $value['buyout_value'];?></td>
							<td><?php echo $value['start_time'];?></td>
							<td><?php echo $value['end_time'];?></td>
							<td><?php echo $value['completed_value'];?></td>
							<td class="<?php if( $value['state']==2 ) { echo " player";} elseif( $value['state']==3 ){ echo "wait";}?>"><?php echo $status[ $value['state'] ];?></td>
							<td><a href="<?php echo site_url("admin/data_website/index/".$value['website_id']);?>">数据详情</a> <a href="<?php echo site_url("admin/user/user_info/".$value['user_id']."/");?>">个人信息</a> <a href="<?php echo site_url("admin/website/detail/".$value['website_id']);?>">网站信息</a> </td>
						</tr>
					<?php
						}
					}
					else
					{
					?>
						<tr bgcolor="#FFFFFF"><td class="warning" colspan="10">暂无相关信息</td></tr>
					<?php
					}
						if( !empty($page_link) )
						{
					?>
					<tr bgcolor="#FFFFFF"><td align="left" colspan="10"><?php echo $page_link;?></td></tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</fieldset>
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#search_btn").click(function(){
		var web_id=$("#web_id").val();
		if( isNaN(web_id) )
		{
			alert("网站ID必须为数字!");
			return false;
		}
	});
});
/* ]]> */
</script>
</body>
</html>
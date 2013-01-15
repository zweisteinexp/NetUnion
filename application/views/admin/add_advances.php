<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" type="text/css" href="<?php base_res_style();?>style.css" />
	<link rel="stylesheet" href="<?php base_res_style();?>jquery-ui-1.8.13.custom.css" type="text/css" />
    <script src="<?php base_res_script();?>jquery-1.5.1.min.js" type="text/javascript" ></script>
	<script src="<?php base_res_script();?>jquery-ui-1.8.13.custom.min.js" type="text/javascript" ></script>
<style type="text/css">
body{font:12px arial;background:#fff;}
</style>
</head>
<body>
<div class="container">
	<p align="left"><a href="<?php echo site_url("admin/advances/")?>">返回</a></p>
	<fieldset>
		<legend>新增预付款站点</legend>
		<?php 
				echo $success;
		?>
		<form id="add_form" action="<?php echo site_url("admin/advances/insert")?>" method="post">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="table">
			<tbody align="right">
				<tr>
					<td>网站名称：</td>
					<td class="pad-left">
						<select id="web_key">
						<?php
							echo "<option value=''>请先选择网站</option>";
							foreach($website_list as $value)
							{
								echo "<option value='".$value['id']."|".$value['user_name']."|".$value['user_id']."'>".$value['website_name']."</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>网站ID：</td>
					<td class="pad-left">
						<input type="text" id="web_id" name="web_id" readonly="readonly" />
					</td>
				</tr>
				<tr>
					<td>站长名：</td>
					<td class="pad-left"><input type="text" id="web_owner" name="web_owner" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>买断量：</td>
					<td class="pad-left"><input type="text" id="pv" name="pv" />次</td>
				</tr>
				<tr>
					<td>买断价格：</td>
					<td class="pad-left"><input type="text" id="amount" name="amount"  />元</td>
				</tr>
				<tr>
					<td>买断时间：</td>
					<td class="pad-left"><input type="text" id="starttime" name="starttime" readonly="readonly" value="<?php echo date("Y-m-d");?>" /> 至 <input type="text" id="endtime" name="endtime" readonly="readonly"  /></td>
				</tr>
				
			</tbody>
		</table>
		<div class="button-bar">
			<input type="hidden" id="web_user" name="web_user" />
			<input type="button" id="add_web" value="提交" />
		</div>
		</form>
	</fieldset>
</div>
</body>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	 
	$("#web_key").change(function(){
		var web_key = $(this).val();
		var web_key = web_key.split("|");
		$("#web_id").val(web_key[0]);
		$("#web_owner").val(web_key[1]);
		$("#web_user").val(web_key[2]);
	});
	
	$("#starttime").datepicker({
		showWeek: true,
		firstDay: 1,
		dateFormat:"yy-mm-dd",
	});
	
	$("#endtime").datepicker({
		showWeek: true,
		firstDay: 1,
		dateFormat:"yy-mm-dd"
	});
	
	$("#add_web").click(function(){
		var web_key = $("#web_key").val();
		var web_id = $("#web_id").val();
		var web_owner = $("#web_owner").val();
		var pv = $("#pv").val();
		var amount = $("#amount").val();
		var starttime = $("#starttime").val();
		var endtime = $("#endtime").val();
		if ( web_key == null || web_key == '') 
		{
			alert('请先选择网站!');
			return false;
		}
		if ( pv == null || pv == '') 
		{
			alert('买断量不能为空!');
			return false;
		}
		if ( isNaN(pv) )
		{
			alert('买断量必须为数字!')
			return false;
		}
		if ( amount == null || amount == '') 
		{
			alert('买断价格不能为空!');
			return false;
		}
		if ( isNaN(amount) )
		{
			alert('买断价格必须为数字!')
			return false;
		}
		if ( starttime == null || starttime == '') 
		{
			alert('起始时间不能为空!');
			return false;
		}
		if ( endtime == null || endtime == '') 
		{
			alert('结束时间不能为空!');
			return false;
		}
		if( starttime > endtime )
		{
			alert('起始时间不能大于结束时间!');
			return false;
		}
		
		$("#add_form").submit();	
	});
});
/* ]]> */
</script>
</html>
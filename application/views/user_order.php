<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>乐子联盟</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php base_res_style();?>jquery-ui-1.8.13.custom.css" type="text/css" />
</head>
<body>
	<div class="container">
		<?php require('top.php');?>
		<script src="<?php base_res_script();?>jquery-ui-1.8.13.custom.min.js" type="text/javascript" ></script>
		<div class="main">
			<h3>支付信息</h3>
			<br class="clear" />
			<?php 
				if( !empty($website_list) )
				{
			?>
			<form id="search_form" action="" method="get">
					<p style="margin-left:90px;">	
						<select name="web_id" id="web_id">
							<option value="">请选择网站</option>
							<?php 
								foreach($website_list as $value)
								{
							?>
								<option value="<?php echo $value['id'];?>"<?php if($value['id']==$web_id) {echo " selected='selected'";}?>><?php echo $value['website_name'];?></option>
							<?php
								}
							?>
						</select> 	 	
						<input type="radio" value="1" name="state" <?php if($state==1){echo " checked='checked'";}?>  /> 已结算 <input type="radio" name="state" value="0" <?php if($state==0){echo " checked='checked'";}?>  /> 未结算
						结算时间：<input type="text" class="text" value="<?php echo $starttime;?>" id="starttime" name="starttime" readonly="readonly" /> 
						至 <input type="text" id="endtime" class="text" name="endtime" value="<?php echo $endtime;?>"  readonly="readonly" />
						&nbsp;&nbsp;<a href="javascript:void(0);" id="search_btn" class="golden_btn">查询</a>
						
					</p>
					<br>
				<?php
					if(!empty($web_id))
					{
				?>	
						 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gray_table" >
						  <thead>
							<tr>
								<td>结算时间</td>
								<td>结算金额/元</td>
								<td>扣税/元</td>
								<td>实际支付/元</td>
								<td>结算状态</td>
							</tr>
						  </thead>
						  <tbody>
							 <?php
							if( !empty($user_order) )
							{
								foreach($user_order as $value)
								{
							?>
								<tr bgcolor="#FFFFFF">
									<td><?php echo $value['order_date'];?></td>
									<td><?php echo $value['amount'];?></td>
									<td><?php echo $value['tax_amount'];?></td>
									<td><?php echo $value['amount']-$value['tax_amount'];?></td>
									<td><?php echo $state_order[ $value['state'] ]?></td>
								</tr>
							<?php
								}
							}
							else
							{
							?>
								<tr bgcolor="#FFFFFF"><td align="center" colspan="6"><?php echo "暂无支付信息";?></td></tr>
								
							<?php
							}
								if( !empty($page_link) )
								{
							?>
							<tr bgcolor="#FFFFFF"><td colspan="6"><?php echo $page_link;?></td></tr>
							<?php
								}
							?>	
							</tbody>
						</table>
			
				<?php
					}
				?>	
				</form>
			<?php 
				}
				else
				{
					echo "您还没有添加网站，请先<a href=".site_url('website/newone').">添加网站</a>。";
				}
			?>
		</div>	
	</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#starttime").datepicker({
		showWeek: true,
		firstDay: 1,
		dateFormat:"yy-mm-dd"
	});
	
	$("#endtime").datepicker({
		showWeek: true,
		firstDay: 1,
		dateFormat:"yy-mm-dd"
	});
	
	$("#search_btn").click(function(){
		var web_id=$("#web_id").val();
		if ( web_id == null || web_id == '' ) 
		{
			alert('请选择网站!');
			return false;
		}
		$("#search_form").submit();
	});
});
/* ]]> */
</script>
</body>
</html>
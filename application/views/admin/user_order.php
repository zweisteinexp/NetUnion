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
	<fieldset class="s_container">
		<legend>结算查询</legend>
		<form action="" method="get">
			<p><strong>起始时间：</strong><input type="text"  id="starttime" name="starttime" readonly="readonly" value="<?php echo $starttime;?>" />
				<strong>结束时间：</strong> <input type="text"  id="endtime" name="endtime" readonly="readonly" value="<?php echo $endtime;?>" /> 
				<strong>站长：</strong><input type="text"  id="web_owner" name="web_owner" value="<?php echo $web_owner;?>"  /> 
			</p>
			<p style="padding-top:10px;">
				<strong>结算状态：</strong><select name ="state">
							<option value="0" <?php if($state==0){ echo " selected='selected'";}?>>结算中</option>
							<option value="1" <?php if($state==1){ echo " selected='selected'";}?>>已结算</option>
						  </select>	
				<strong>显示：</strong>
						  <select name ="show_type">
							<option value="0" <?php if($show_type==0){ echo " selected='selected'";}?>>简单信息(仅包括结算信息)</option>
							<option value="1" <?php if($show_type==1){ echo " selected='selected'";}?>>详细信息(包括真实IP，点击等)</option>
						  </select>					
			</p>
			<br>
			<p>
				<input type="submit" class="btn btn-default" id="search_btn" value="点击查询" />
			</p>
		</form>
	</fieldset>

<br/>
<fieldset>
<?php 
	if( !empty($starttime) && !empty($endtime) )
	{
?>
<legend>结算单列表</legend>
	<div id="idLists">
		<form id="up_all" action="<?php echo site_url("admin/user_order/update_all_state/")?>" method="post">
		<input type="hidden" name="s_time" value="<?php echo $starttime;?>" />
		<input type="hidden" name="e_time" value="<?php echo $endtime;?>" />
		<input type="hidden" name="w_owner" value="<?php echo $web_owner;?>" />
		<input type="hidden" name="s_state" value="<?php echo $state;?>" />
		<input type="hidden" name="order_id_list" value="<?php echo $order_id_list;?>" />
		<input type="hidden" name="s_type" value="<?php echo $show_type;?>" />
		<?php
		if( !empty($web_owner) && !empty($user_order) )
		{
		?>
			<p><?php echo "站长：".$web_owner." 总实际支付为：".$count_total."元";?>&nbsp;<?php if($state==0) { ?><input type="submit" value="全部打款" id="all_btn" />(无需选择对象)<?php } ?></p>
		<?php
			}
		?>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" id="myTable" class="table">
			<thead align="center">
				<tr bgcolor="#FFFFFF">
					<th>选择</th>
					<th>站长</th>
					<th>真实姓名</th>
					<th>网站名称</th>
					<th>结算时间</th>
					<th>结算金额/元</th>
					<th>扣税/元</th>
					<th>实际支付/元</th>
					<th>打款银行及卡号</th>
					<th>支付时间</th>
					<?php 
						if( $show_type==1 )
						{
					?>
					<th>扣量后IP数</th>
					<th>扣量后点击数</th>
					<th>真实IP数</th>
					<th>真实点击数</th>
					<?php 
						}
					?>
					<th>操作</th>
				</tr>
			</thead>
			<tbody align="center">
			<?php
			if( !empty($user_order) )
			{
				foreach($user_order as $value)
				{
			?>
				<tr bgcolor="#FFFFFF">
					<td><input type="checkbox" name="check_list[]" value="<?php echo $value['id'];?>" /></td>
					<td><?php echo $value['user_name'];?></td>
					<td><?php echo $value['true_name'];?></td>
					<td><?php echo $value['website_name'];?></td>
					<td><?php echo $value['order_date'];?></td>
					<td><?php echo $value['amount'];?></td>
					<td><?php echo $value['tax_amount'];?></td>
					<td><?php echo $value['amount']-$value['tax_amount'];?></td>
					<td><?php echo $value['bank_name']."<br/>".$value['bank_card'];?></td>
					<td><?php if($value['state']==0){echo "结算中"; }else{ echo gmdate('Y-m-d H:i:s',$value['apply_time']+8*3600);}?></td>
					<?php 
						if( $show_type==1 )
						{
					?>
					<td><?php echo $value['ips'];?></td>
					<td><?php echo $value['clicks'];?></td>
					<td><?php echo $value['real_ips'];?></td>
					<td><?php echo $value['real_clicks'];?></td>
					<?php
						}
					?>
					<td><?php if($value['state']==0) {?><a href="<?php echo site_url("admin/user_order/update_row_state/".$value['id']."/?starttime=".$starttime."&amp;endtime=".$endtime."&amp;web_owner=".$web_owner."&amp;state=".$state."&amp;show_type=".$show_type."")?>">打款</a><?php } else { echo "已打款";}?></td>
				</tr>
			<?php
				}
			}
			else
			{
			?>
				<tr bgcolor="#FFFFFF"><td align="center" colspan="15"><?php echo "暂无结算信息";?></td></tr>
			<?php
			}
			
			?>
			<tr bgcolor="#FFFFFF"><td height="35" align="left" colspan="15"><span style="margin-right:30px;">全选 <input type="checkbox" id="check_all" />&nbsp;&nbsp;<?php if($state==0) {?><input type="submit" class="submit" value="分批打款" id="batch_btn" /> <?php } ?></span><?php if( !empty($page_link) ){ echo $page_link; }?></td></tr>
			</tbody>
		</table>
		</form>
		<?php
			}
			else
			{
				echo "请先选择起始时间和结束时间再查询！";
			}	
		?>
	</div>
</fieldset>
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
		var starttime = $("#starttime").val();
		var endtime = $("#endtime").val();
		
		if ( starttime == null || starttime == '' ) 
		{
			alert('请选择起始时间!');
			return false;
		}
		
		if ( endtime == null || endtime == '' ) 
		{
			alert('请选择结束时间!');
			return false;
		}
		
		if( starttime > endtime )
		{
			alert('起始时间不能大于结束时间!');
			return false;
		}
	});
	
	$("#check_all").click(function(){
		$("input[name='check_list[]']").each(function(){
			$(this).attr("checked",!this.checked); 
		});	
     });
	
	$("#batch_btn").click(function(){
		var check_box = $("input[name='check_list[]']").is(":checked");
		if(check_box==true)
		{
			if( confirm('确定分批打款？') )
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
		else
		{
			alert("请选择打款的对象！");
			return false;
		}
	});
	
	$("#all_btn").click(function(){
		if( confirm('确定全部打款？') )
		{
			return true;
		}
		else
		{
			return false;
		}
	});
});
/* ]]> */
</script>
</body>
</html>	
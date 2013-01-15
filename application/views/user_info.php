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
	<?php require('top.php');?>
	<div class="main">
		<h3>基本信息</h3>
		<br class="clear" />
		<?php 
		if(!empty( $msg ))
		{
		?>
		<div class='msg error'>
		<?php 
			echo $msg;
		?>
		</div>
		<?php 
		}
		?>
		<form id="update_user_info" method="post" action="<?php echo site_url('user_info');?>" enctype="multipart/form-data" >
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td width="126" align="right">您的真实姓名：</td>
			<td>
				<?php 
					if( empty($true_name) )
					{
				?>
				<input type="text" class="text" id="truename" name="truename" value="<?php  echo set_value('truename')? set_value('truename'):$true_name;?>" style=" width:155px;" /> <span class="red_text">请与提供的银行卡用户名一致，否则无法汇款</span>
				<?php 
					}
					else
					{
						echo $true_name;
					}
				?>
			</td>
		  </tr>
		  <tr>
			<td width="126" align="right">您的身份证号：</td>
			<td>
				<?php 
					if( empty($identity_card) )
					{
				?>	
				<input type="text" class="text" id="identitycard" name="identitycard" value="<?php echo set_value('identitycard')? set_value('identitycard'):$identity_card;?>" style=" width:155px;" />
				<?php 
					}
					else
					{
						echo $identity_card;
					}
				?>
			</td>
		  </tr>
		  <tr>
			<td width="126" align="right">请上传您的身份证：</td>
			<td>
			<?php
				if(!empty($obverse_identity_thumb))
				{
			?>
			<p><a href="<?php echo base_att_verify().$obverse_identity_thumb;?>" target="_blank"><img src="<?php echo base_att_verify().$obverse_identity_thumb;?>" border="0" width="300" height="300"/></a>&nbsp;&nbsp; 
			<a href="<?php echo base_att_verify().$reverse_identity_thumb;?>" target="_blank"><img src="<?php echo base_att_verify().$reverse_identity_thumb;?>" border="0" width="300" height="300" /></a></p>
			<?php
				}
				else
				{
			?>
				<p><input type="file"  id="obverse_photo" name="obverse_photo" size="20" /> 正面<br/><input type="file" id="reverse_photo" name="reverse_photo" size="20" /> 反面<br/>请上传您的身份证件，以进行您的实名认证

二代身份证需上传正反两面，支持JPG、JPEG、GIF、BMP和PNG文件</p>	
			<?php
				}
			?>
			</td>
		  </tr>
		  <tr>
			<td width="126" align="right">您的邮箱：</td>
			<td><?php echo $email;?></td>
		  </tr>
		  <tr>
			<td width="126" align="right">您的QQ号码：</td>
			<td><input type="text" class="text" id="qq" name="qq" value="<?php echo set_value('qq')? set_value('qq'):$qq;?>" style=" width:155px;" /> <?php if( empty($qq) ){ ?><span class="red_text">请填写您的QQ号码，以便我们与您及时沟通</span><?php } ?></td>
		  </tr>
		  <tr>
			<td width="126" align="right">您的手机号码：</td>
			<td><input type="text" class="text" id="mobilephone" name="mobilephone" value="<?php echo set_value('mobilephone')? set_value('mobilephone'):$mobile_phone;?>" style=" width:155px;" /> <?php if( empty($mobile_phone) ) {?> <span class="red_text">请填写您的手机号码，以便我们能第一时间与您联系</span> <?php } ?></td>
		  </tr>
		</table>
		<h3>银行账号信息</h3>
		<br class="clear" />
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td width="126" align="right">付款银行：</td>
			<td>
			<?php 
				if( empty($bank_code) )
				{
			?>
				<select id="bankcode" name="bankcode" style=" width:155px;">
				<option value="0">请选择付款银行</option>
				<?php
					foreach($bank_list as $key=>$value)
					{
				?>
					<option value="<?php echo $key;?>"<?php echo set_select('bankcode', $key); ?>><?php echo $value;?></option>
				<?php
					}
				?>
				</select>	
			<?php
				}
				else
				{
					echo $bank_list[$bank_code];
				}
			?>
		</td>
		  </tr>
		  <tr>
			<td width="126" align="right">开户银行：</td>
			<td>
				<?php 
					if( empty($bank_name) )
					{
				?>
				<input type="text" class="text" name="bankname" id="bankname" value="<?php echo set_value('bankname')? set_value('bankname'):$bank_name;?>" style=" width:155px;" /> <span class="red_text">请填写开户银行至支行一级，如：中国建设银行杭州分行文三路支行</span>
				<?php
					}
					else
					{
						echo $bank_name;
					}
				?>
			</td>
		  </tr>
		  <tr>
			<td width="126" align="right">银行卡号：</td>
			<td>
				<?php 
					if( empty($bank_card) )
					{
				?>
				<input type="text" class="text" name="bankcard" id="bankcard" value="<?php echo set_value('bankcard')? set_value('bankcard'):$bank_card;?>" style=" width:155px;" /> 
				<?php 
					}
					else
					{
						echo $bank_card;
					}
				?>
			</td>
		  </tr>
		  <tr>
			<td></td>
			<td style="padding-top:10px;"><a href="javascript:void(0);" id="sub_btn" class="golden_btn">提交</a></td>
			
		  </tr>
		</table>
		</form>      
	</div>	
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){
	$("#sub_btn").click(function(){
		var truename=$("#truename").val();
		var identitycard=$("#identitycard").val();
		var obverse_photo = $("#obverse_photo").val();
		var reverse_photo = $("#reverse_photo").val();
		if(identitycard != "" && identitycard != null )
		{
			isIDCard_15=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;
			isIDCard=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[x|X])$/;
			if((isIDCard.exec(identitycard) && (identitycard.substr(6,2)=='19' || identitycard.substr(6,2)=='20'))|| isIDCard_15.exec(identitycard))
			{
				//return true;
			}
			else
			{
				alert("身份证号码校验错误");
				return false;
			} 
		}
		if( obverse_photo!="" && reverse_photo=="")
		{
			alert("请上传反面照片！");
			return false;
		}
		
		if( obverse_photo=="" && reverse_photo!="" )
		{
			alert("请上传正面照片！");
			return false;
		}
		
		$('#update_user_info').submit();

	});
});	
/* ]]> */
</script>
</body>
</html>
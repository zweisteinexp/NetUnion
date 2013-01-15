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
        	<h3>修改网站</h3>
        	
        	<?php if (isset($msg)) { echo "<div class='msg {$msg[0]}'>{$msg[1]}</div>"; } ?>
		
		<form name="form1" method="post" action="<?php echo site_url('website/modify/' . $id) ;?>">
			<input type="hidden" name="__submit" value="1" />
		
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td width="200" align="right">您的网站域名：</td>
					<td><span class="text"><?php echo $website['domain'] ;?></span></td>
				
				</tr>
				<tr>
					<td align="right"><span style="color: red;">*</span>网站名称：</td>
					<td><input type="text" class="text" name="website_name" 
						value="<?php echo set_value('website_name') ? set_value('website_name') : $website['website_name'] ;?>" size="30"/></td>
				</tr>
				<tr>
					<td align="right">网站ICP备案号：</td>
					<td><?php if (empty($website['icp'])) { ?> 
						<input type="text" class="text" name="icp" value="<?php 
							echo set_value('icp', $this->input->post('icp')) ;?>" size="20" />
						<span class="red_text" >填写后将不可更改</span>
					    <?php } else { ?> 
					    	<span class="text"><?php echo $website['icp'] ;?></span>
					    <?php } ?> </td>
				</tr>
				<tr>
					<td align="right">网站描述：</td>
					<td><textarea class="text" style="float: left;width: 60%;" name="description" rows="5"><?php 
						echo set_value('description', $this->input->post('description'))
							? set_value('description', $this->input->post('description')) : $website['description'] ;?></textarea>
						<p class="red_text" 
							style="margin-left: 5px;float: left;width: 35%;line-height: 1.3em;">
							请简单描述下您的网站基本信息。<br/>
							例如：我的网站日独立IP××，PV××，PR：4，Alexa排名前1万。并且每日新增用户约××人。目前已有xxxxxx位会员等。</p>
					</td>
				</tr>
				<tr>
					<td align="right">网站类别：</td>
					<td><div style="float: left;width: 60%;font-size: 14px;line-height: 1.5em;">
					<?php 
						$values_index = array_keys($type_names);
						$i = 0;
						foreach ($values_index as $key => $value) { 
							echo '<input id="type_', $value, '" type="checkbox" name="website_types[]" value="', $value, '" ';
							echo in_array($value, $website_types) ? 'checked="checked" ' : '';
							echo '/> <label for="type_', $value, '">', $type_names[$value], '</label> &nbsp; ', "\n";
							if (($i++) % 8 == 7) { echo '<br/>'; }
						}
					?></div>
						<p class="red_text" style="margin-left: 5px;float: left;width: 35%;">最多只允许选择两项类别</p></td>
				</tr>
				<tr>
					<td></td>
					<td style="padding-top: 10px;"><a class="golden_btn" href="javascript:;"
						onclick="javascript:$(document.form1).submit(); return false; "> 提 &nbsp; 交 </a></td>
				</tr>
			</table>
		</form>

		<div class="tip">
			<h4>友情提示</h4>
		</div>
		
	</div>	
	</div>
</body>
<script type="text/javascript">
	$(document.form1).submit( function() {
		if ($.trim($(document.form1.website_name).val()) == '') {
			alert("请填写站点名称！"); return false;
		}
		if ($(":checkbox[checked=true]", $(document.form1)).length > 2) {
			alert("最多只允许选择两项类别！"); return false;
		}
		if (document.form1.icp) {
			if ($.trim($(document.form1.icp).val()) != '') {
				return confirm("请认真确定 网站ICP备案号 ，提交后无法修改！");
			}
		}			
	} );
</script>
</html>

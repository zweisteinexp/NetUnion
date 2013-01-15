<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>修改网站</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="<?php base_res_style(); ?>style.css" type="text/css"/>
	<link rel="stylesheet" href="<?php base_res_style(); ?>jquery-ui-1.8.13.custom.css" type="text/css"/>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-1.5.1.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-ui-1.8.13.custom.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>ui.datepicker-zh-CN.js" > </script>
</head>

<body>
<div class="container">
	<br/>
	<p><a href="<?php echo site_url('admin/website'); ?>">返回</a></p>
	<br/>
	
	<fieldset>
		<legend>修改网站</legend>
		<?php if (isset($msg)) { echo "<div class='msg {$msg[0]}'>{$msg[1]}</div>"; } ?>
		
		<form name="form1" method="post" action="<?php echo site_url('admin/website/modify/' . $id) ;?>">
			<input type="hidden" name="__submit" value="1" />
		
			<table class="table" border="0" cellspacing="0" cellpadding="0"><tbody align="right">
				<tr>
					<td>网站主用户：</td>
					<td class="pad-left"><span class="text"><?php echo $website['user_name'] ;?></span></td>
				
				</tr>
				<tr>
					<td>网站域名：</td>
					<td class="pad-left"><span class="text"><?php echo $website['domain'] ;?></span></td>
				
				</tr>
				<tr>
					<td><span style="color: red;">*</span>网站名称：</td>
					<td class="pad-left"><input type="text" class="text" name="website_name" 
						value="<?php echo set_value('website_name') ? set_value('website_name') : $website['website_name'] ;?>" size="30"/></td>
				</tr>
				<tr>
					<td>网站ICP备案号：</td>
					<td class="pad-left"><input type="text" class="text" name="icp" 
						value="<?php echo set_value('icp', $this->input->post('icp')) ? 
									set_value('icp', $this->input->post('icp')) : $website['icp'] ;?>" size="20" /></td>
				</tr>
				<tr>
					<td>网站描述：</td>
					<td class="pad-left"><textarea class="text" style="float: left;width: 60%;" name="description" rows="5"><?php 
						echo set_value('description', $this->input->post('description'))
							? set_value('description', $this->input->post('description')) : $website['description'] ;?></textarea>
						<p class="red_text" 
							style="margin-left: 5px;float: left;width: 35%;line-height: 1.3em;">
							请简单描述下您的网站基本信息。<br/>
							例如：我的网站日独立IP××，PV××，PR：4，Alexa排名前1万。并且每日新增用户约××人。目前已有xxxxxx位会员等。</p>
					</td>
				</tr>
				<tr>
					<td>选项：</td>
					<td class="pad-left"><input id="asimprest_1" type="checkbox" name="asimprest[]" value="1"
						<?php   if ($this->input->post('asimprest')) {
								if (in_array('1', $this->input->post('asimprest'))) { echo 'checked="checked"'; }
							} else {
								if ($website['is_imprest'] == '1') { echo 'checked="checked"'; }
							} ?>/> <label for="asimprest_1">支持预付</label>
					</td>
				</tr>
				<tr>
					<td>状态：</td>
					<td class="pad-left"><input id="state_pass" type="checkbox" name="state[]" value="1"
						<?php   if ($this->input->post('state')) {
								if (in_array('1', $this->input->post('state'))) { echo 'checked="checked"'; }
							} else {
								if ($website['state'] == '1') { echo 'checked="checked"'; }
							} ?>/> <label for="state_pass">已验证</label>
						<input id="ascooperative_1" type="checkbox" name="ascooperative[]" value="1"
						<?php   if ($this->input->post('ascooperative')) {
								if (in_array('1', $this->input->post('ascooperative'))) { echo 'checked="checked"'; }
							} else {
								if ($website['is_cooperative'] == '1') { echo 'checked="checked"'; }
							} ?>/> <label for="ascooperative_1">合作中</label>
					</td>
				</tr>
				<tr>
					<td>结算周期：</td>
					<td class="pad-left">
					<?php foreach ($settle_types as $key => $val) { ?> 
						<input id="settle_type_<?php echo $key;?>" type="radio" name="settle_type" value="<?php echo $key;?>" 
						<?php   if ($this->input->post('settle_type') == $key) { 
								echo 'checked="checked"'; 
							} else if ($website['settle_type'] == $key) {
								echo 'checked="checked"';
							} ?>/> <label for="settle_type_<?php echo $key;?>"><?php echo $val;?> </label>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td>网站组别：</td>
					<td class="pad-left"><select name="group">
						<?php 	foreach ($group_types as $k => $v) {  
								echo '<option value="', $k, '" ';
								if ($this->input->post('group') == $k) {
									echo 'selected="selected"';
								} else if ($website['group_id'] == $k) {
									echo 'selected="selected"';
								}
								echo '>', $v, '</option>', "\n" ; 
							} ?> </select>
					</td>
				</tr>
				<tr>
					<td>网站类别：</td>
					<td class="pad-left"><div id="show_types" style="float: left;width: 60%;font-size: 14px;line-height: 1.5em;">
					<?php 
						$values_index = array_keys($type_names);
						$i = 0;
						foreach ($values_index as $key => $value) { 
							echo '<input id="type_', $value, '" type="checkbox" name="website_types[]" value="', $value, '" ';
							if ($this->input->post('website_types') && in_array($value, $this->input->post('website_types'))) {
								echo 'checked="checked"';
							} else if (in_array($value, $website_types)) {
								echo 'checked="checked"';
							}
							echo '/> <label for="type_', $value, '">', $type_names[$value], '</label> &nbsp; ', "\n";
							if (($i++) % 8 == 7) { echo '<br/>'; }
						}
					?></div>
						<p class="red_text" style="margin-left: 5px;float: left;width: 35%;">最多只允许选择两项类别</p></td>
				</tr>
			</tbody></table>
			<div class="button-bar">
				<input type="submit" value=" 提 &nbsp; 交 "/>
			</div>
		</form>
	</fieldset>
</div>

</body>
<script type="text/javascript">
	$(document.form1).submit( function() {
		if ($.trim($(document.form1.website_name).val()) == '') {
			alert("请填写站点名称！"); return false;
		}
		if (document.form1['ascooperative[]'].checked && ! document.form1['state[]'].checked) {
			alert("合作中的站点，需是验证通过的！"); return false;
		}
		if ($(":checkbox[checked=true]", $('#show_types')).length > 2) {
			alert("最多只允许选择两项类别！"); return false;
		}
	} );
</script>
</html>

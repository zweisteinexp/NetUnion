<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>广告管理</title>
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
	<p><a href="<?php echo site_url('admin/advert'); ?>">返回</a></p>
	<br/>
	
	<fieldset>
		<legend>修改广告</legend>
		<?php if (isset($msg)) { echo "<div class='msg {$msg[0]}'>{$msg[1]}</div>"; } ?>
		
		<form name="form1" method="post" action="<?php echo site_url('admin/advert/modify') . '/' . $id ;?>">
			<input type="hidden" name="__submit" value="1" />
		
			<table class="table" border="0" cellspacing="0" cellpadding="0"><tbody align="right">
				<tr>
					<td><span style="color: red;">*</span>广告主用户：</td>
					<td class="pad-left"><input type="text" name="username"
						value="<?php echo set_value('username') ? set_value('username') : $ad['user_name'] ;?>"/></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>广告链接名称：</td>
					<td class="pad-left"><input type="text" name="adname" size="30"
						value="<?php echo set_value('adname') ? set_value('adname') : $ad['advertise_name'] ;?>"/></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>链接地址：</td>
					<td class="pad-left"><input type="text" name="adurl" size="80"
						value="<?php echo set_value('adurl') ? set_value('adurl') : $ad['advertise_url'] ;?>"/></td>
				</tr>
				<tr>
					<td>展示模式：</td>
					<td class="pad-left"><span><?php echo $ad['showmode_str']; ?></span></td>
				</tr>
				<tr>
					<td>定价模式：</td>
					<td class="pad-left"><span><?php echo $ad['costmode_str']; ?></span></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>访问限制：</td>
					<td class="pad-left">
						<span>独立IP访问 &lt;= <input type="text" name="maxip" size="8" style="text-align: right;"
							value="<?php echo set_value('maxip') ? set_value('maxip') : $ad['max_ip'] ;?>" />万</span>
						且 
						<span>浏览量PV &lt;= <input type="text" name="maxpv" size="8" style="text-align: right;"
							value="<?php echo set_value('maxpv') ? set_value('maxpv') : $ad['max_pv'] ;?>"/>万</span>
						<span class="red_text">此值将不允许下调</span></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>投放日期范围：</td>
					<td class="pad-left"><span><?php echo date('Y-m-d', $ad['min_date']); ?></span> ~
						<input type="text" name="maxdate" id="maxdate" class="datepicker" size="10" 
							value="<?php echo set_value('maxdate') ? set_value('maxdate') : date('Y-m-d', $ad['max_date']) ;?>"/>
						<span class="red_text">不允许结束日期下调</span></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>投放状态：</td>
					<td class="pad-left"><?php foreach ($states as $k => $v) { ?> 
						<input id="state_<?php echo $k;?>" type="radio" name="state" value="<?php echo $k;?>"
							<?php if ($this->input->post('state') !== false) echo set_radio('state', strval($k)) ;
							      else echo $k == $ad['state'] ? 'checked="checked"' : ''; ?>/>
						<label for="state_<?php echo $k;?>"> <?php echo $v; ?> </label>
					<?php } ?> </td>
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
	$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});	
	$("form:first").submit( function() {
		if ($.trim(this.username.value) == '') {
			alert('填写广告主用户！'); return false;
		}
		
		if ($.trim(this.adname.value) == '') {
			alert('填写广告链接名称！'); return false;
		}
		
		if ($.trim(this.adurl.value) == '') {
			alert('填写链接地址！'); return false;
		}
		
		if ( ! /([http|https]:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/.test($.trim(this.adurl.value)) ) {
			alert('填写格式正确的 url 地址'); return false;
		}
		
		var maxip = parseInt($.trim(this.maxip.value));
		var maxpv = parseInt($.trim(this.maxpv.value));
		if (isNaN(maxip) || maxip <= 0 || isNaN(maxpv) || maxpv <= 0) {
			alert('访问限制参数不合法'); return false;
		}

		var maxdate = new Date(this.maxdate.value).getTime();
		if (isNaN(maxdate)) {
			alert('请选择投放结束日期'); return false;
		}
	} );
</script>
</html>

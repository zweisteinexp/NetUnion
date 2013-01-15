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
		<legend>添加新广告</legend>
		<?php if (isset($msg)) { echo "<div class='msg {$msg[0]}'>{$msg[1]}</div>"; } ?>
		
		<form name="form1" method="post" action="<?php echo site_url('admin/advert/newone') ;?>">
			<input type="hidden" name="__submit" value="1" />
		
			<table class="table" border="0" cellspacing="0" cellpadding="0"><tbody align="right">
				<tr>
					<td><span style="color: red;">*</span>广告主用户：</td>
					<td class="pad-left"><input type="text" name="username" value="<?php 
						echo set_value('username') ? set_value('username') : $defaultuser ;?>" readonly="readonly"/></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>广告链接名称：</td>
					<td class="pad-left"><input type="text" name="adname" value="<?php echo set_value('adname') ;?>" size="30" /></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>链接地址：</td>
					<td class="pad-left"><input type="text" name="adurl" value="<?php 
						echo set_value('adurl') ? set_value('adurl') : 'http://' ;?>" size="80"/></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>展示模式：</td>
					<td class="pad-left"><?php foreach ($showmodes as $k => $v) { ?> 
						<input id="showmode_<?php echo $k;?>" type="radio" name="showmode" value="<?php echo $k;?>"
							<?php echo isset($defshowmode)
								? ($k == $defshowmode ? 'checked="checked"' : '') : set_radio('showmode', $k) ;?>/>
						<label for="showmode_<?php echo $k;?>"> <?php echo $v; ?> </label>
					<?php } ?> 
						<span class="red_text">填写后将不可更改(目前系统仅支持 flash)</span></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>定价模式：</td>
					<td class="pad-left"><?php foreach ($costmodes as $k => $v) { ?> 
						<input id="costmode_<?php echo $k;?>" type="radio" name="costmode" value="<?php echo $k;?>"
							<?php echo isset($defcostmode)
								? ($k == $defcostmode ? 'checked="checked"' : '') : set_radio('costmode', $k) ;?>/>
						<label for="costmode_<?php echo $k;?>"> <?php echo key($v); ?> </label>
					<?php } ?> 
						<span class="red_text">填写后将不可更改(目前系统仅支持 CPM)</span></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>访问限制：</td>
					<td class="pad-left">
						<span>独立IP访问 &lt;= <input type="text" name="maxip" size="8" style="text-align: right;"
							value="<?php echo set_value('maxip') ;?>"/>万</span>
						且 
						<span>浏览量PV &lt;= <input type="text" name="maxpv" size="8" style="text-align: right;"
							value="<?php echo set_value('maxpv') ;?>"/>万</span></td>
				</tr>
				<tr>
					<td><span style="color: red;">*</span>投放日期范围：</td>
					<td class="pad-left"><input type="text" name="mindate" id="mindate" class="datepicker" size="10" 
							value="<?php echo set_value('mindate') ;?>" />  ~
						<input type="text" name="maxdate" id="maxdate" class="datepicker" size="10" 
							value="<?php echo set_value('maxdate') ;?>" />
						<span class="red_text">起始日期不能小于今日</span></td>
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
		
		if ($("input[name=showmode]:checked", $(this)).val() == undefined) {
			alert('选择展示模式'); return false;
		}
		
		if ($("input[name=costmode]:checked", $(this)).val() == undefined) {
			alert('选择定价模式'); return false;
		}
		
		var maxip = parseInt($.trim(this.maxip.value));
		var maxpv = parseInt($.trim(this.maxpv.value));
		if (isNaN(maxip) || maxip <= 0 || isNaN(maxpv) || maxpv <= 0) {
			alert('访问限制参数不合法'); return false;
		}

		var mindate = new Date(this.mindate.value).getTime();
		var maxdate = new Date(this.maxdate.value).getTime();
		if (isNaN(mindate) || isNaN(maxdate)) {
			alert('请选择投放日期范围'); return false;
		}
		if (mindate + 24 * 3600 * 1000 < new Date().getTime()) {
			alert('起始日期不能小于今日'); return false;
		}
		if (maxdate <= mindate) {
			alert('结束日期要大于起始日期'); return false;
		}
	} );
</script>
</html>

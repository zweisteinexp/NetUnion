<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>广告系列管理</title>
	<style type="text/css">
		body, div, span, p, img, a, ol, ul, li, dl, dd, dt, form { padding: 0; margin: 0; border: 0; }
		:focus { outline: 0; }
		a, a:link, a:visited, a:hover, a:active { text-decoration: none; color: blue; }
		ol, ul { list-style: none; }

		body { font-size: 12px; text-align: center; }
		div.default { width: 90%; margin: 0 auto; margin-bottom: 10px; text-align: left; }
		table.default { border-left: 1px solid gray; border-top: 1px solid gray; margin: 10px 0 5px; width: 100%; }
		table.default td, table.default th { border-bottom: 1px solid gray; border-right: 1px solid gray; padding: 4px; font-size: 12px; }
		table.default th { background-color: #E6EEEE; }
		div.error { margin: 5px 0; padding: 5px; width: 30%; color: white; background-color: red; }
		div.ok { margin: 5px 0; padding: 5px; width: 30%; color: white; background-color: green; }
		
		ul.item_contain li { margin: 4px 2px; margin-right: 10px; padding: 2px; padding-left: 2em; height: 18px;}
		ul.item_contain li span.ui-icon { float: left; }
		ul.item_contain li span {  cursor: move; color: black; !important }
		div.item_order_contain { float: left; margin: 4px 10px; margin-left: 50px; width: 50%; padding: 1%; }
		div.item_order_contain h4 { line-height: 25px; padding: 1px 5px; margin: 0 }
		div.item_order_contain ul { min-height: 2em; }
		
		
		#ads { width: 30%; height: 200px; overflow-y: auto; overflow-x: hidden; float: left; }
		#sites { width: 30%; height: 200px; overflow-y: auto; overflow-x: hidden; float: left; }
	</style>
	<link rel="stylesheet" href="<?php base_res_style(); ?>jquery-ui-1.8.13.custom.css" type="text/css"/>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-1.5.1.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-ui-1.8.13.custom.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>ui.datepicker-zh-CN.js" > </script>
</head>

<body>

<h1>Welcome to Union.Lezi!</h1>
<div class="default">
	<a href="<?php echo site_url('admin/advertrain'); ?>">返回</a>
	<form name="form1" method="post" action="<?php echo site_url('admin/advertrain/update_train/'.$id.'/') ;?>">
		
		<table class="default" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="200" align="right" valign="top"><span style="color: red;">*</span>广告系列名称：</th>
				<td><input type="text" name="trainame" size="30" value="<?php echo $train_name;?>"/></td>
			</tr>
			<tr>
				<th width="200" align="right" valign="top"><span style="color: red;">*</span>广告系列滚动模式：</th>
				<td><?php foreach ($adrollmodes as $k => $v) { ?> 
					<input id="rollmode_<?php echo $k;?>" type="radio" name="rollmode" value="<?php echo $k;?>" 
					<?php if($roll_type==$k) {echo 'checked="checked"';} ?> />
					<label for="rollmode_<?php echo $k;?>"> <?php echo $v; ?> </label>
				<?php } ?> 
					<span style="margin-left: 10px;">若选定 随机 模式，则广告添加中的顺序将不可用</span></td>
			</tr>
			<tr>
				<th width="200" align="right" valign="top">选项：</th>
				<td><input id="asdefault_1" type="checkbox" name="asdefault" value="1"
					<?php if ($is_default==1) { echo 'checked="checked"'; } ?>/> <label for="asdefault_1">默认推广系列</label>
					, 
					<input id="asshare_1" type="checkbox" name="asshare" value="1"
					<?php if ($is_share==1) { echo 'checked="checked"'; } ?>/> <label for="asshare_1">网站可共享此推广系列</label>
					,
					<input id="ascutable_1" type="checkbox" name="ascutable" value="1"
					<?php if ($is_cutable==1) { echo 'checked="checked"'; } ?>/> <label for="ascutable_1">可切换此推广系列</label></td>
			</tr>
		</table>
		<input type="submit" name="submit1" value=" 提 &nbsp; 交 "/>
	</form>
</div>

</body>
<script type="text/javascript">
	$("form:first").submit( function() {
		if ($.trim(this.trainame.value) == '') {
			alert('请填写 广告系列名称'); return false ;
		}
		if ($("input[name=rollmode]:checked").length == 0) {
			alert('请选择 广告系列滚动模式'); return false ;
		}
		
	} );
</script>
</html>

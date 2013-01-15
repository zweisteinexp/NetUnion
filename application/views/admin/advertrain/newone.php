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
	
	<?php if (isset($msg)) { echo "<div class='{$msg[0]}'>{$msg[1]}</div>"; } ?>
		
	<form name="form1" method="post" action="<?php echo site_url('admin/advertrain/newone') ;?>">
		<input type="hidden" name="__submit" value="1" />
		<input type="hidden" name="ads" value="" />
		<input type="hidden" name="sites" value="" />
		
		<table class="default" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="200" align="right" valign="top"><span style="color: red;">*</span>广告系列名称：</th>
				<td><input type="text" name="trainame" size="30" value="<?php echo set_value('trainame') ;?>"/></td>
			</tr>
			<tr>
				<th width="200" align="right" valign="top"><span style="color: red;">*</span>广告系列滚动模式：</th>
				<td><?php foreach ($adrollmodes as $k => $v) { ?> 
					<input id="rollmode_<?php echo $k;?>" type="radio" name="rollmode" 
						value="<?php echo $k;?>" <?php echo set_radio('rollmode', $k) ;?>/>
					<label for="rollmode_<?php echo $k;?>"> <?php echo $v; ?> </label>
				<?php } ?> 
					<span style="margin-left: 10px;">若选定 随机 模式，则广告添加中的顺序将不可用</span></td>
			</tr>
			<tr>
				<th width="200" align="right" valign="top">选项：</th>
				<td><input id="asdefault_1" type="checkbox" name="asdefault[]" value="1"
					<?php if ($this->input->post('asdefault') && in_array('1', $this->input->post('asdefault'))) { 
							echo 'checked="checked"'; } ?>/> <label for="asdefault_1">默认推广系列</label>
					, 
					<input id="asshare_1" type="checkbox" name="asshare[]" value="1"
					<?php if ($this->input->post('asshare') && in_array('1', $this->input->post('asshare'))) { 
							echo 'checked="checked"'; } ?>/> <label for="asshare_1">网站可共享此推广系列</label>
					,
					<input id="ascutable_1" type="checkbox" name="ascutable[]" value="1"
					<?php if ($this->input->post('ascutable') && in_array('1', $this->input->post('ascutable'))) { 
							echo 'checked="checked"'; } ?>/> <label for="ascutable_1">可切换此推广系列</label></td>
			</tr>
			<tr>
				<th width="200" align="right" valign="top">广告：</th>
				<td><ul id="ads" class="item_contain ads_sortable"><?php foreach ($validads as $v) { ?> 
					<li class="ui-state-default" adid="<?php echo $v['id']; ?>"><span class="ui-icon ui-icon-arrow-4"></span>
						<span><?php echo $v['advertise_name']; ?></span></li>
				<?php } ?></ul>				
					<div id="ads_order_contain" class="ui-widget-content ui-state-default item_order_contain">
						<h4 class="ui-widget-header">添加到</h4>
						<ul id="ads_order" class="item_contain ads_sortable">
						</ul></div>
				</td>
			</tr>
			<tr>
				<th width="200" align="right" valign="top">网站：</th>
				<td><ul id="sites" class="item_contain"><?php foreach ($validsites as $v) { ?> 
					<li class="ui-state-default" siteid="<?php echo $v['id']; ?>"><span class="ui-icon ui-icon-arrow-4"></span>
						<span><?php echo $v['domain']; ?></option></li>
				<?php } ?></ul>
					<div id="sites_order_contain" class="ui-widget-content ui-state-default item_order_contain">
						<h4 class="ui-widget-header">添加到</h4>
						<ul id="sites_order" class="item_contain">
						</ul></div>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit1" value=" 提 &nbsp; 交 "/>
	</form>
</div>

</body>
<script type="text/javascript">
	$("#ads, #ads_order").sortable({
		connectWith: ".ads_sortable",
		placeholder: "ui-state-highlight",
		stop: function(e, ui) {
			if (ui.item.parent().attr('id') == 'ads_order') {
				if ($("span.ui-icon-arrowthick-2-n-s", ui.item).length == 0) {
					$("<span> </span>")
						.addClass('ui-icon')
						.addClass('ui-icon-arrowthick-2-n-s')
						.prependTo(ui.item);
					$('<div style="float: right;"> </div>')
						.append('<span>MAXIP:<input type="text" value="0" style="width: 60px; text-align: right;"></span>')
						.append('<span>MAXPV:<input type="text" value="0" style="width: 60px; text-align: right;"></span>')
						.appendTo(ui.item);
				}
			}
			if (ui.item.parent().attr('id') == 'ads') {
				$("span.ui-icon-arrowthick-2-n-s", ui.item).remove();
				$("div:has(input)", ui.item).remove();
			}
		}
	});
	
	$("#sites li").draggable({
		helper: "clone",
		evert: "invalid"
	});
	$("#sites_order").droppable({
		accept: '#sites li',
		activeClass: "ui-state-highlight",
		drop: function(e, ui) { $(this).append(ui.draggable); }
	});
	$("#sites_order li").draggable({
		helper: "clone",
		evert: "invalid"
	});
	$("#sites").droppable({
		accept: '#sites_order li',
		activeClass: "ui-state-highlight",
		drop: function(e, ui) { $(this).append(ui.draggable); }
	});
	
	$("form:first").submit( function() {
		if ($.trim(this.trainame.value) == '') {
			alert('请填写 广告系列名称'); return false ;
		}
		if ($("input[name=rollmode]:checked").length == 0) {
			alert('请选择 广告系列滚动模式'); return false ;
		}
		var ads = [];
		var sites = [];
		$("#ads_order li").each(function() {
			var ipobj = $("input:first", $(this));
			ipobj.val(isNaN(parseInt(ipobj.val())) ? '0' : Math.abs(parseInt(ipobj.val())));
			var pvobj = $("input:last", $(this));
			pvobj.val(isNaN(parseInt(pvobj.val())) ? '0' : Math.abs(parseInt(pvobj.val())));
			ads[ads.length] = [$(this).attr('adid'), ipobj.val(), pvobj.val()].join('-');
		} );
		$("#sites_order li").each(function() {
			sites[sites.length] = $(this).attr('siteid');
		} );
		$("input[name=ads]").val(ads.join('|'));
		$("input[name=sites]").val(sites.join('|'));
		
		if (ads.length == 0 || sites.length == 0) {
			return confirm('广告或网站没有选择，确定提交么？');
		}
	} );
</script>
</html>

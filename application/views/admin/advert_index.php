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
	<p><a href="<?php echo site_url('admin/advert/newone'); ?>">添加新广告</a></p>
	<br/>
	
	<fieldset class="s_container">
		<legend>广告搜索</legend>	
		<form id="form1" name="form1" method="get" action="<?php echo site_url('admin/advert') ;?>">
			<ul class="cols-3">
				<li><strong>定价模式：</strong>
					<select name="costmode"><?php foreach ($costmodes as $k => $v) { ?> 
						<option value="<?php echo $k; ?>"<?php if ($costmode !== false && $costmode == strval($k)) { 
								echo ' selected="selected"'; } ?>><?php echo key($v); ?></option>
					<?php } ?></select></li>
				<li><strong>用户ID：</strong>
					<input type="text" name="username" value="<?php echo isset($username) ? $username : '';?>"/></li>
				<li><strong>投放日期：</strong>
					<input type="text" name="mindate" id="mindate" class="datepicker" size="10" 
						value="<?php echo isset($mindate) ? $mindate : '';?>" /> 
					<a onclick="$('#mindate').val('');" href="javascript:;">清除</a> ~
					<input type="text" name="maxdate" id="maxdate" class="datepicker" size="10" 
						value="<?php echo isset($maxdate) ? $maxdate : '';?>" />
					<a onclick="$('#maxdate').val('');" href="javascript:;">清除</a></li>
			</ul>
			<ul class="cols-3">
				<li><strong>发布日期：</strong>
					<input type="text" name="adddate" id="add_date" class="datepicker" size="10"
						value="<?php echo isset($adddate) ? $adddate : '';?>" /> 
					<a onclick="$('#add_date').val('');" href="javascript:;">清除</a></li>
				<li><strong>投放状态：</strong>
					<select name="state"><?php foreach ($states as $k => $v) { ?> 
						<option value="<?php echo $k; ?>"<?php if ($state !== false && $state == strval($k)) { 
							echo ' selected="selected"'; } ?>><?php echo $v; ?></option>
					<?php } ?></select></li>
				<li class="s-search-btn">
					<input type="submit" class="btn btn-default" value="点击查询"/>
				</li>
			</ul>
		</form>
	</fieldset>
	
	<fieldset>
		<legend>广告列表</legend>
		
		<table class="table" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th align="center">选择</th>
					<th align="center">广告主</th>
					<th align="center">广告链接名称</th>
					<th align="center" width="400px">链接地址</th>
					<th align="center">展示模式</th>
					<th align="center">定价模式</th>
					<th align="center">最大IP</th>
					<th align="center">最大PV</th>
					<th align="center">投放状态</th>
					<th align="center">投放日期范围</th>
					<th align="center">发布日期</th>
					<th align="center">操作</th>
				</tr>
			</thead>
			<tbody>
		<?php if ( ! empty($adverts)) { ?> 
			<?php foreach($adverts as $ad) { ?> 
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" value="<?php echo $ad['id']; ?>"/></td>
					<td align="center"><?php echo $ad['user_name']; ?>&nbsp;</td>
					<td><?php echo $ad['advertise_name']; ?></td>
					<td><a href="<?php echo $ad['advertise_url']; ?>" target="_black"><?php echo $ad['advertise_url']; ?></a></td>
					<td align="center"><?php echo $ad['showmode_str']; ?></td>
					<td align="center"><?php echo $ad['costmode_str']; ?></td>
					<td align="right"><?php echo $ad['max_ip']; ?>万</td>
					<td align="right"><?php echo $ad['max_pv']; ?>万</td>
					<td align="center"><?php echo $ad['state_str']; ?></td>
					<td align="center"><?php echo $ad['minmaxdate_str']; ?></td>
					<td align="center"><?php echo $ad['adddate_str']; ?></td>
					<td align="center"><a href="<?php echo site_url('admin/advert/modify') . '/' . $ad['id']; ?>">编辑</a>
						| <a class="do_one_trash" href="javascript:;">废弃</a>
						<!--| <a href="<?php echo site_url('admin/advert/detail') . '/' . $ad['id']; ?>">详情</a>--></td>
				</tr>
			<?php } ?> 
			</tboby>
			<tfoot>
				<tr><th align="center"><input type="checkbox" id="checkall"/></th>
					<th align="left" colspan="11">
						<span>选择项：</span>
						<span><input id="do_pause" type="button" value="暂停投放" /></span>
						<span style="margin-left: 10px;"><input id="do_use" type="button" value="重新投放" /></span>
						<span style="margin-left: 10px;"><input id="do_over" type="button" value="投放结束" /></span>
						<span style="margin-left: 10px;"><input id="do_trash" type="button" value="废弃" /></span>
					</th></tr>
			</tfoot>
		<?php } else { ?> 
				<tr> <td align="center" colspan="12"> 暂无广告 </td> </tr>
		<?php } ?> 
		</table>
		<p style="width: 100%;text-align: center;"> <?php echo isset($page_html) ? $page_html : ''; ?> </p>
	</fieldset>
</div>

<script type="text/javascript">
	var state_url = '<?php echo site_url("admin/advert/state"); ?>';
	
	$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
	$("#checkall").click( function() { $("input[name='ids[]']").attr('checked', $(this).attr('checked')); } );
	var _p = function(num) {
		if (isNaN(num)) return ;
		$('<input type="hidden" name="page">').appendTo("#form1");
		$("#form1").get(0).page.value = num;
		$("#form1").submit();
	};
	var statuechange = function(method) {
		var ids = [];
		$("input[name='ids[]']:checked").each( function() {
			ids[ids.length] = this.value;
		} );
		ids = ids.join(',');
		if (ids == '') { alert('选择要操作的广告');  return ; }
		if (method == 'trash' && ! confirm('确定要废弃这些广告么，废弃后仅能通过后台管理员重新恢复')) { return ; }
		if (method == 'over' && ! confirm('确定要结束这些广告的投放么，结束后仅能通过后台管理员重新启用投放')) { return ; }
		if (method.match(/pause|use/) && ! confirm('若所选中包含 已结束，或已废弃 的广告，将忽略掉')) { return ; }
			
		var submitform = $("<form> </form>").appendTo("body")
			.css('display', 'none')
			.attr('method', 'post')
			.attr('action', state_url);
		$("<input type='hidden'>").appendTo(submitform)
			.attr('name', 'ids')
			.attr('value', ids);
		$("<input type='hidden'>").appendTo(submitform)
			.attr('name', 'method')
			.attr('value', method);
		submitform.submit();
	};
	$("#do_pause").click( function() { 
		statuechange('pause'); 
	} );
	$("#do_use").click( function() { 
		statuechange('use'); 
	} );
	$("#do_over").click( function() { 
		statuechange('over'); 
	} );
	$("#do_trash").click( function() { 
		statuechange('trash'); 
	} );
	$("a.do_one_trash").each( function() { 
		$(this).click( function() {
			$(this).parents('tbody').find('input[type=checkbox]').attr('checked', false);
			$(this).parents('tr').eq(0).find('input[type=checkbox]').attr('checked', true);
			statuechange('trash');
		} );
	} );
</script>
</html>

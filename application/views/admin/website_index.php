<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>站点管理</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<style type="text/css">
		span.cost_editable, span.deduct_editable { cursor: pointer; border: 1px solid gray; }
	</style>
	<link rel="stylesheet" href="<?php base_res_style(); ?>style.css" type="text/css"/>
	<link rel="stylesheet" href="<?php base_res_style(); ?>jquery-ui-1.8.13.custom.css" type="text/css"/>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-1.5.1.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-ui-1.8.13.custom.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>ui.datepicker-zh-CN.js" > </script>
</head>

<body>
<div class="container">
	<br/>
	<fieldset class="s_container">
		<legend>站点搜索</legend>
		<form id="form1" name="form1" method="get" action="<?php echo site_url('admin/website') ;?>">
			<ul class="cols-3">
				<li><strong>网站组别：</strong>
					<select name="groupid"><?php foreach ($group_types as $k => $v) { ?> 
						<option value="<?php echo $k; ?>"<?php if ($groupid !== false && $groupid == strval($k)) { 
							echo ' selected="selected"'; } ?>><?php echo $v; ?></option>
					<?php } ?></select></li>
				<li><strong>用户ID：</strong>
					<input type="text" name="userid" value="<?php echo isset($userid) ? $userid : '';?>"/></li>
				<li><strong>加盟日期：</strong>
					<input type="text" name="start_date" id="start_date" class="datepicker" size="10" 
						value="<?php echo isset($start_date) ? $start_date : '';?>" /> 
					<a onclick="$('#start_date').val('');" href="javascript:;">清除</a> ~
					<input type="text" name="end_date" id="end_date" class="datepicker" size="10" 
						value="<?php echo isset($end_date) ? $end_date : '';?>" />
					<a onclick="$('#end_date').val('');" href="javascript:;">清除</a></li>
			</ul>
			<ul class="cols-3">
				<li><strong>合作状态：</strong>
					<select name="cooperative"><?php foreach ($cooperatives as $k => $v) { ?> 
						<option value="<?php echo $k; ?>"<?php if ($cooperative !== false && $cooperative == strval($k)) { 
								echo ' selected="selected"'; } ?>><?php echo $v; ?></option>
					<?php } ?></select></li>
				<li class="s-search-btn colspan-2">
					<input type="submit" class="btn btn-default" value="点击查询"/>
				</li>
			</ul>
		</form>
	</fieldset>
	
	<fieldset>
		<legend>站点列表</legend>
		<table class="table" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th align="center">选择</th>
					<th align="center">网站主</th>
					<th align="center">网站名称</th>
					<th align="center">网址</th>
					<th align="center">IPC</th>
					<th align="center">结算周期</th>
					<th align="center">分成比例(E)</th>
					<th align="center">扣量比例(E)</th>
					<th align="center">状态</th>
					<th align="center">分组</th>
					<th align="center">类别</th>
					<th align="center">加盟日期</th>
					<th align="center">操作</th>
				</tr>
			</thead>
			<tbody>
		<?php if ( ! empty($websites)) { ?> 
			<?php foreach($websites as $web) { ?> 
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" value="<?php echo $web['id']; ?>" /></td>
					<td align="center"><?php echo $web['user_name']; ?>&nbsp;</td>
					<td title="<?php echo $web['description']; ?>"><?php echo $web['website_name']; ?></td>
					<td><?php echo $web['domain']; ?>&nbsp;</td>
					<td align="center"><?php echo $web['icp']; ?>&nbsp;</td>
					<td align="center"><?php echo $web['settle_str']; ?>&nbsp;</td>
					<td align="center"><span class="cost_editable"><?php 
						echo $web['n_cost_rate'], isset($web['t_cost_rate']) ? "-{$web['t_cost_rate']}" : '' ;?></span>
						/<?php echo $cost_rate_unit; ?>IP&nbsp;</td>
					<td align="center"><span class="deduct_editable"><?php 
						echo $web['n_deduct_rate'], isset($web['t_deduct_rate']) ? "-{$web['t_deduct_rate']}" : '' ;?></span>
						%&nbsp;</td>
					<td align="center"><?php echo $web['state_str']; ?>&nbsp;</td>
					<td align="center"><?php echo $web['group_str']; ?>&nbsp;</td>
					<td align="center"><?php echo $web['types_str']; ?>&nbsp;</td>
					<td align="center"><?php echo $web['add_time_str']; ?></td>
					<td align="center"><a href="<?php echo site_url('admin/website/modify') . '/' . $web['id']; ?>">编辑</a>
						| <a href="<?php echo site_url('admin/website/detail') . '/' . $web['id']; ?>">详情</a></td>
				</tr>
			<?php } ?> 
			</tboby>
			<tfoot>
				<tr>
					<th align="center"><input type="checkbox" id="checkall"/></th>
					<th align="left" colspan="12">
						<span>选择项：</span>
						<span><input id="do_cooperative_end" type="button" value="暂停合作" /></span>
						<span style="margin-left: 10px;"><input id="do_cooperative_start" type="button" value="重新合作" /></span>
						<span style="margin-left: 10px;">移动至：<select id="do_group_move"><?php foreach ($group_types as $k => $v) { ?> 
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php } ?></select></span>
					</th>
				</tr>
			</tfoot>
		<?php } else { ?> 
				<tr> <td align="center" colspan="13"> 暂无网站 </td> </tr>
		<?php } ?> 
		</table>
		<p style="width:100%;text-align: center;"> <?php echo isset($page_html) ? $page_html : ''; ?> </p>
	</fieldset>
</div>

</body>
<script type="text/javascript">
	var indicator = '<img src="<?php base_res_image(); ?>indicator.gif">';
	var ratemodify_url = '<?php echo site_url("admin/website/ratemodify"); ?>';
	var cooperative_url = '<?php echo site_url("admin/website/cooperative"); ?>';
	var movegroup_url = '<?php echo site_url("admin/website/movegroup"); ?>';
	
	$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
	$("#checkall").click( function() { $("input[name='ids[]']").attr('checked', $(this).attr('checked')); } );
	var _p = function(num) {
		if (isNaN(num)) return ;
		$('<input type="hidden" name="page">').appendTo("#form1");
		$("#form1").get(0).page.value = num;
		$("#form1").submit();
	};
	
	var checkboxtostr = function(checkboxname) {
		var checkboxvals = [];
		$("input[name='" + checkboxname + "']:checked").each( function() {
			checkboxvals[checkboxvals.length] = this.value;
		} );
		return checkboxvals.join(',');
	};
	
	var cooperativeEdit = function(method, msg) {
		var checkboxvals = checkboxtostr("ids[]");
		if (checkboxvals == '') {
			alert('请选择网站');
			return ;
		}
		var submitform = $("<form> </form>").appendTo("body")
			.css('display', 'none')
			.attr('method', 'post')
			.attr('action', cooperative_url);
		$("<input type='hidden'>").appendTo(submitform)
			.attr('name', 'ids')
			.attr('value', checkboxvals);
		$("<input type='hidden'>").appendTo(submitform)
			.attr('name', 'method')
			.attr('value', method);
		if (confirm('确定所选站点 ' + msg + ' 么？')) {
			submitform.submit();
		} else {
			submitform.remove();
		}
	};
	$("#do_cooperative_end").click( function() {
		cooperativeEdit('end', '暂停合作');
	} );
	$("#do_cooperative_start").click( function() {
		cooperativeEdit('start', '重新合作');
	} );
	
	$("#do_group_move").change( function() {
		if (this.value == '-') { return; }
		var checkboxvals = checkboxtostr("ids[]");
		if (checkboxvals == '') {
			alert('请选择网站');
			return ;
		}
		var submitform = $("<form> </form>").appendTo("body")
			.css('display', 'none')
			.attr('method', 'post')
			.attr('action', movegroup_url);
		$("<input type='hidden'>").appendTo(submitform)
			.attr('name', 'ids')
			.attr('value', checkboxvals);
		$("<input type='hidden'>").appendTo(submitform)
			.attr('name', 'groupid')
			.attr('value', $(this).val());
		if (confirm('确定所选站点移动至 ' + $('option:selected', this).text() + ' 么？')) {
			submitform.submit();
		} else {
			submitform.remove();
		}
	} );
	
	var rateEdit = function(obj, ratename) {
		var rateShow = function(str1, str2) {
			return str2 == undefined ? str1 : str1 + "-" + str2;
		};
		var fixRate = function(jobj) {
			jobj.text(rateShow(jobj.attr('now_rate'), jobj.attr('next_rate')));
			jobj.removeAttr('state');
		};
		
		var jthis = $(obj);
		if (jthis.attr('state') != undefined) { return ; }
		
		jthis.attr('state', 'editing');
		if (jthis.attr('now_rate') == undefined) {
			var text = jthis.text();
			var reg = /\d+(\.\d+)?/g;
			var strs = text.match(reg);
			if (strs[0] != undefined) { jthis.attr('now_rate', strs[0]); }
			if (strs[1] != undefined) { jthis.attr('next_rate', strs[1]); }
		}
		
		jthis.empty();
		$('<input type="text">')
			.appendTo(jthis)
			.css('width', '30px')
			.val(jthis.attr('next_rate') ? jthis.attr('next_rate') : jthis.attr('now_rate'))
			.focus()
			.blur( function(e) { 
				setTimeout( function() { 
					if (jthis.attr('state') == 'editing') {
						fixRate(jthis);
					}
				}, 200); 
			} );
		$('<input id="submit" type="button">')
			.appendTo(jthis)
			.css('width', '27px')
			.val('OK')
			.click( function(e) { 
				e.stopPropagation();
				jthis.attr('state', 'submitint');
				var id = jthis.parents("tr").first().find("td:first input").val();
				var value = parseFloat(jthis.find("input:first").val());
				if (isNaN(value) || value < 0) { 
					fixRate(jthis);
					return ;
				}
				if (jthis.attr('next_rate') == undefined && value == jthis.attr('now_rate') || value == jthis.attr('next_rate')) {
					fixRate(jthis);
					return ;
				}
				jthis.html(indicator);
				$.post(ratemodify_url + '/' + id, { 'name' : ratename, 'value' : value }, function(data) {
					if (data == "true") {
						jthis.attr('next_rate', value);
					} else {
						alert('未知原因，更新失败');
					}
					fixRate(jthis);
				} );
			} );
	};
	$("span.cost_editable").each( function() {
		$(this).click( function(e) {
			rateEdit(this, 'cost');
		} );
	} );	
	$("span.deduct_editable").each( function() {
		$(this).click( function(e) {
			rateEdit(this, 'deduct');
		} );
	} );
</script>
</html>

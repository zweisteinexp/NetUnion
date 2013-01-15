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
		
		
		ul.item_contain li { margin: 4px 2px; margin-right: 10px; padding: 2px; padding-left: 2em; height: 18px;}
		ul.item_contain li span.ui-icon { float: left; }
		ul.item_contain li span {  cursor: move; color: black; !important }
		div.item_order_contain { float: left; margin: 4px 10px; margin-left: 50px; width: 50%; padding: 1%; }
		div.item_order_contain h4 { line-height: 25px; padding: 1px 5px; margin: 0 }
		div.item_order_contain ul { min-height: 2em; }
		
		div.error { margin: 5px 0; padding: 5px; width: 30%; color: white; background-color: red; }
		div.ok { margin: 5px 0; padding: 5px; width: 30%; color: white; background-color: green; }
		
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
	<h3>后台-广告系列</h3>
	
	<?php if (isset($msg)) { echo "<div class='{$msg[0]}'>{$msg[1]}</div>"; } ?>
	
	<form id="form1" name="form1" method="get" action="<?php echo site_url('admin/advertrain') ;?>">
		<span style="margin-right: 10px;">投放状态：<select name="state"><?php foreach ($states as $k => $v) { ?> 
			<option value="<?php echo $k; ?>"<?php if ($state !== false && $state == strval($k)) { 
					echo ' selected="selected"'; } ?>><?php echo $v; ?></option>
			<?php } ?></select></span>
		<span style="margin-right: 10px;">发布日期：<input type="text" name="mindate" id="mindate" class="datepicker" size="8" 
				value="<?php echo isset($mindate) ? $mindate : '';?>" /> 
			<a onclick="$('#mindate').val('');" href="javascript:;">清除</a> ~
			<input type="text" name="maxdate" id="maxdate" class="datepicker" size="8" 
				value="<?php echo isset($maxdate) ? $maxdate : '';?>" />
			<a onclick="$('#maxdate').val('');" href="javascript:;">清除</a></span>
		<input type="submit" value="List!" />
	</form>
	
	<table class="default" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th align="center">选择</th>
				<th align="center">系列ID</th>
				<th align="center">系列名称</th>
				<th align="center">系列详情</th>
				<th align="center">滚动模式</th>
				<th align="center">选项</th>
				<th align="center">投放状态</th>
				<th align="center">发布日期</th>
			</tr>
		</thead>
		<tbody>
	<?php if ( ! empty($advertrains)) { ?> 
		<?php foreach($advertrains as $adtrain) { ?> 
			<tr>
				<td align="center"><input type="checkbox" name="ids[]" value="<?php echo $adtrain['id']; ?>"/>
					| <a href="<?php echo site_url('admin/advertrain/modify') . '/' . $adtrain['id']; ?>">编辑</a>
					| <a class="do_trash" href="javascript:;">废弃</a></td>
				<td align="center"><?php echo $adtrain['id']; ?>&nbsp;</td>
				<td><?php echo $adtrain['train_name']; ?></td>
				<td align="center">广告 <a href="javascript:void(0);" id="adver_<?php echo $adtrain['id'];?>" class="change_adver" >+</a> | 站点 <a href="javascript:void(0);" id="web_<?php echo $adtrain['id'];?>" class="change_web" >+</a></td>
				<td align="center"><?php echo $adtrain['roll_type_str']; ?></td>
				<td align="center"><?php echo $adtrain['option_str']; ?></td>
				<td align="center"><?php echo $adtrain['state_str']; ?></td>
				<td align="center"><?php echo $adtrain['add_time_str']; ?></td>
			</tr>
			<tr id="showad_<?php echo $adtrain['id'];?>" style="display:none;"></tr>
			<tr id="showweb_<?php echo $adtrain['id'];?>" style="display:none;"></tr>
		<?php } ?> 
			
	<?php } else { ?> 
			<tr> <td align="center" colspan="8"> 暂无广告 </td> </tr>
	<?php } ?> 		
		<tfoot>
			<tr><th align="center"><input type="checkbox" id="checkall"/></th>
				<th colspan="7"><span><select id="do_state"><option value="-">With checked:</option>
						<option value="pause">暂停投放</option>
						<option value="use">重新投放</option>
						<option value="over">投放结束</option>
						<option value="trash">废弃</option></select></span>
					<span>You can also:</span>
					<span><a href="<?php echo site_url('admin/advertrain/newone'); ?>">
						<input type="button" value="新广告系列" /></a></span>
				</th></tr>
		</tfoot>
	</table>
	<p style="width: 90%;text-align: center;"> <?php echo isset($page_html) ? $page_html : ''; ?> </p>
</div>

</body>
<script type="text/javascript">
	var state_url = '<?php echo site_url("admin/advertrain/state"); ?>';
	
	$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
	$("#checkall").click( function() { $("input[name='ids[]']").attr('checked', $(this).attr('checked')); } );
	
	var _p = function(num) {
		if (isNaN(num)) return ;
		$('<input type="hidden" name="page">').appendTo("#form1");
		$("#form1").get(0).page.value = num;
		$("#form1").submit();
	};
	
	$("#do_state").change( function() { 
		var method = $(this).val();
		var ids = [];
		$("input[name='ids[]']:checked").each( function() {
			ids[ids.length] = this.value;
		} );
		ids = ids.join(',');
		if (ids == '') { alert('选择要操作的广告');  $(this).val('-'); return ; }
		if (method == 'trash' && ! confirm('确定要废弃这些广告系列么？')) { $(this).val('-'); return ; }
			
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
	} );
	$("a.do_trash").click( function() {
		var method = 'trash';
		var ids = $(this).parent().find('input').val();
		if (method == 'trash' && ! confirm('确定要废弃这个广告系列么？')) { return ; }
			
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
	} );
	$(".change_adver").click(function(){
		var adver_id = $(this).attr("id").replace("adver_","");
		var show_adver =$("#showad_"+adver_id);
		if(show_adver.is(":visible"))
		{
			$(this).html("+");
			show_adver.hide();
		}
		else
		{
			var adver_list = "";
			
			var mid_table_left = '<td colspan="8" height="200px" ><div id="ads_order_contain" class="ui-widget-content ui-state-default item_order_contain"><h4 class="ui-widget-header">广告系列</h4><ul id="ads_order_'+adver_id+'" class="item_contain ads_sortable">';
			var mid_table_right = '</ul><input type="button" id="update_ad_'+adver_id+'"  value="确定修改" > <input type="button" id="close_ad_'+adver_id+'" value="关闭" /></div></td>';
			$(this).html("-");
			show_adver.show();
			$.getJSON("<?php echo site_url('admin/advertrain/get_adver');?>",{id:adver_id},function(data){
				if(data!="")
				{
					$.each(data, function(i,item){
						 adver_list += "<li id="+item['id']+" class='ui-state-default'><span class='ui-icon ui-icon-arrowthick-2-n-s'> </span><span class='ui-icon ui-icon-arrow-4'></span><span>"+item['advertise_name']+"</span><div style='float: right;'><span>MAXIP: <input type='text' style='width: 60px; text-align: right;' value="+item['max_ip']+" /></span> MAXPV: <span><input type='text' style='width: 60px; text-align: right;' value="+item['max_pv']+" /></span> </div></li>";
					});
					adver_list = mid_table_left+adver_list+mid_table_right;
					show_adver.html(adver_list);
					$( "#ads_order_"+adver_id ).sortable();
					
					$("#update_ad_"+adver_id).click(function(){
						if( confirm('确定修改该广告系列？') )
						{
							var ads = [];
							$("#ads_order_"+adver_id+" li").each(function() {
								var ipobj = $("input:first", $(this));
								ipobj.val(isNaN(parseInt(ipobj.val())) ? '0' : Math.abs(parseInt(ipobj.val())));
								var pvobj = $("input:last", $(this));
								pvobj.val(isNaN(parseInt(pvobj.val())) ? '0' : Math.abs(parseInt(pvobj.val())));
								ads[ads.length] = [$(this).attr('id'), ipobj.val(), pvobj.val()].join('-');
							} );
							ads = ads.join("|");
							$.post("<?php echo site_url('admin/advertrain/update_adver_sort');?>",{ads:ads},function(data){
								alert(data);
							});
						}
						else
						{
							return false;
						}
					});
					$("#close_ad_"+adver_id).click(function(){
						$("#adver_"+adver_id).html("+");
						show_adver.hide();
					});
				}
				else
				{
					show_adver.html('<td colspan="8" align="left" >暂无广告系列</td>');
				}
			});

			
			
		}
		
	});
	$(".change_web").click(function(){
		var web_id = $(this).attr("id").replace("web_","");
		var show_web =$("#showweb_"+web_id);
		if(show_web.is(":visible"))
		{
			$(this).html("+");
			show_web.hide();
		}
		else
		{
			
			var web_list = "";
			var mid_table_left = '<td colspan="8" height="200px" ><div id="sites_order_contain" class="ui-widget-content ui-state-default item_order_contain"><h4 class="ui-widget-header">站点列表</h4><ul id="sites_order_'+web_id+'" class="item_contain ui-droppable">';
			var mid_table_right = '</ul><input type="button" id="close_web_'+web_id+'" value="关闭" /></div></td>';
			$(this).html("-");
			show_web.show();
			$.getJSON("<?php echo site_url('admin/advertrain/get_web');?>",{id:web_id},function(data){
				if(data!="")
				{
					$.each(data, function(i,item){
						 web_list += "<li id="+item['id']+" class='ui-state-default'><span class='ui-icon ui-icon-arrow-4'></span><span>"+item['domain']+"</span></li>";
					});
					web_list = mid_table_left+web_list+mid_table_right;
					show_web.html(web_list);
					$("#close_web_"+web_id).click(function(){
						$("#web_"+web_id).html("+");
						show_web.hide();
					});
				}
				else
				{

					show_web.html('<td colspan="8" align="left">暂无站点列表</td>');
				}
			});
			
		}
	});
	
	
</script>
</html>

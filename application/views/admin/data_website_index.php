<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>站点数据统计</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="<?php base_res_style(); ?>style.css" type="text/css"/>
	<link rel="stylesheet" href="<?php base_res_style(); ?>jquery-ui-1.8.13.custom.css" type="text/css"/>
	<!--[if IE]>
		<script type="text/javascript" src="<?php base_res_script() ;?>excanvas.js"></script>
	<![endif]-->
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-1.5.1.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-ui-1.8.13.custom.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>ui.datepicker-zh-CN.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>strftime-min.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>rgbcolor.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>dygraph-canvas.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>dygraph.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>data-graph.js"></script>
</head>

<body>
<div class="container">
	<br/>
	<fieldset class="s_container">
		<legend>站点选择</legend>
		<ul class="cols-3">
			<li style="line-height: 35px;"><strong>站点选择：</strong>
				<select id="websiteid"><?php foreach ($websites as $web) { ?> 
					<option value="<?php echo $web['id']; ?>"><?php echo $web['domain']; ?></option>
				<?php } ?></select></li>
			
			<li style="width: 370px;line-height: 35px;"><strong>日期：</strong>
				<input type="text" id="start_date" class="datepicker" size="10" value="" /> 
				<a onclick="$('#start_date').val('');" href="javascript:;">清除</a> ~
				<input type="text" id="end_date" class="datepicker" size="10" value="" /> 
				<a onclick="$('#end_date').val('');" href="javascript:;">清除</a>
				<select id="date_between"><option value="-">--选择--</option>
					<option value="today">--今天--</option>
					<option value="yesterday">--昨天--</option>
					<option value="week">--本周--</option>
					<option value="lastweek">--上周--</option>
					<option value="month">--本月--</option>
					<option value="lastmonth">--上月--</option></select></li>
			<li>
				<input type="button" id="do_search" class="btn btn-default" value="点击查询"/>
			</li>
		</ul>
	</fieldset>
	
	<fieldset>
		<legend>数据统计</legend>
		<div style="margin: 10px auto; position: relative;"><div id="graphdiv" style="margin: 0 auto;width: 100%; "> 
			<p>请选择站点和时间段查询...</p> </div></div>
	</fieldset>
</div>

</body>
<script type="text/javascript">
	var ask_url = "<?php echo site_url('admin/data_website/ask') ;?>";
	var _id = "<?php echo $id; ?>";	
	$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
	
	DATA_GRAPH_DRAW.setGraphDiv('#graphdiv');
	DATA_GRAPH_DRAW.setLabels(['IP', 'Real-IP', 'PV', 'Real-PV']);
	DATA_GRAPH_DRAW.init();
	
	$("#date_between").change( function() { 
		var start_date, end_date;
		
		if (Date[$(this).val()]) {
			var tmp = Date[$(this).val()]();
			start_date = tmp[0];
			end_date = tmp[1];
		} else {
			var now = new Date();
			start_date = now._format();
			end_date = now._format();
		}
		
		$("#start_date").val(start_date);
		$("#end_date").val(end_date);
	} );
	
	$("#do_search").click( function() {
		var websiteid 	= $("#websiteid option:selected").val();
		var start_date 	= $("#start_date").val();
		var end_date 	= $("#end_date").val();	
		
		var now = new Date();
		if (start_date == '') {
			start_date = now._format();
			$("#start_date").val(start_date);
		} else {
			var ms = 183 * 24 * 3600 * 1000;
			if (now._msdiff(new Date(start_date)) > ms) {
				start_date = new Date(now.getTime() - ms)._format();
				$("#start_date").val(start_date);
			}
		}
		if (end_date == '') {
			end_date = now._format();
			$("#end_date").val(end_date);
		}
		if (new Date(start_date).getTime() > new Date(end_date).getTime()) {
			alert("查询开始时间不能大于结束时间！");
			return ;
		}
		
		$('#graphdiv').empty();
		window.DATA_GRAPH_DRAW.loadingData();
		$.getJSON(ask_url, { 'id' : websiteid, 't1' : start_date, 't2' : end_date}, function(jsondata) {
			window.DATA_GRAPH_DRAW.showData(jsondata, [start_date, end_date]);
		} );
	} );
	
	if (parseInt(_id)) {
		$("#websiteid").val(_id);
		$("#date_between").val('today');
		$("#date_between").change();
		$("#do_search").click();
	}
</script>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>乐子联盟</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php base_res_style(); ?>jquery-ui-1.8.13.custom.css" type="text/css"/>
	<!--[if IE]>
		<script type="text/javascript" src="<?php base_res_script() ;?>excanvas.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">

	<?php require('top.php'); ?>
	<script type="text/javascript" src="<?php base_res_script() ;?>jquery-ui-1.8.13.custom.min.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>ui.datepicker-zh-CN.js" > </script>
	<script type="text/javascript" src="<?php base_res_script() ;?>strftime-min.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>rgbcolor.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>dygraph-canvas.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>dygraph.js"></script>
	<script type="text/javascript" src="<?php base_res_script() ;?>data-graph.js"></script>
	<div class="main">
		<h3>数据统计</h3>

		<p style="margin: 10px 20px;">
			<span>选择推广网站：</span>
			<select id="website_id">
			<?php foreach ($websites as $web) { ?> 
				<option value="<?php echo $web['id'], '"'; 
					if($id == $web['id']) echo ' selected="selected"'; ?>><?php echo $web['domain'] ;?></option>
			<?php } ?></select>
		
			<span style="padding-left: 20px;">日期：</span>
			<input type="text" id="start_date" class="datepicker" size="8" value="" /> 
				<a onclick="$('#start_date').val('');" href="javascript:;">清除</a> ~
			<input type="text" id="end_date" class="datepicker" size="8" value="" />
				<a onclick="$('#end_date').val('');" href="javascript:;">清除</a>
			&nbsp; 
			<a id="do_search" class="golden_btn" href="javascript:;">Show!!!</a>
		</p>
	
		<p id="date-line" style="margin: 5px 20px;">
			<a class="do_action do_active" href="javascript:;" ds="today" >今天</a>
			<a class="do_action" href="javascript:;" ds="yesterday" >昨天</a>
			<a class="do_action" href="javascript:;" ds="week" >本周</a>
			<a class="do_action" href="javascript:;" ds="lastweek" >上周</a>
			<a class="do_action" href="javascript:;" ds="month" >本月</a>
			<a class="do_action" href="javascript:;" ds="lastmonth" >上月</a>
		</p>
		<div style="clear: left;"></div>		
	
		<table cellspacing="0" cellpadding="0" border="0" style="margin: 5px 20px; width: 80%;" class="gray_table">
			<thead>
				<tr>
					<td style="width: 180px;">日期</td>
					<td style="width: 250px;">站点</td>
					<td style="width: 20%;">独立IP量</td>
					<td style="width: 20%;">访问量</td>
					<!-- <td>可获得佣金</td> -->
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span id="dates_show"></span>&nbsp;</td>
					<td><span id="website_show"></span>&nbsp;</td>
					<td><span id="ips_show"></span>&nbsp;</td>
					<td><span id="clicks_show"></span>&nbsp;</td>
					<!-- <td><span id="amounts_show"></span>&nbsp;</td> -->
				</tr>
			</tbody>
		</table>
	
		<div style="margin: 10px auto; position: relative;"><div id="graphdiv" style="margin: 0 auto;width: 100%; min-height: 300px;"> </div></div>
		
		<div class="tip">
			<h4>友情提示</h4>
		</div> 
	</div>	
	</div>
</body>
<script type="text/javascript">
	var ask_url = "<?php echo site_url('data/ask'); ?>";
	$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});

	DATA_GRAPH_DRAW.setGraphDiv('#graphdiv');
	DATA_GRAPH_DRAW.setLabels(['IP', 'PV']);
	DATA_GRAPH_DRAW.init();
	
	var loadAndShowData = function(websiteid, start_date, end_date) {
		window.DATA_GRAPH_DRAW.loadingData();
		$.getJSON(ask_url + "/" + websiteid, {'t1' : start_date, 't2' : end_date}, function(jsondata) {
			window.DATA_GRAPH_DRAW.showData(jsondata, [start_date, end_date]);
			if (jsondata) {
				$("#dates_show").text(start_date + ' 到 ' + end_date);
				$("#website_show").text($("#website_id option:selected").text());
				$("#ips_show").text(jsondata['total'][0]);
				$("#clicks_show").text(jsondata['total'][1]);
			}
		} );
	};
	
	$("#do_search").click( function() {
		var websiteid 	= $("#website_id option:selected").val();
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
		$("a.do_action").removeClass('do_active');
		
		loadAndShowData(websiteid, start_date, end_date);
	} );
	
	$("a.do_action").each( function() {
		$(this).click( function() {
			$("a.do_action").removeClass("do_active");
			$(this).addClass("do_active");
			var websiteid 	= $("#website_id option:selected").val();
			var start_date, end_date;
			
			if (Date[$(this).attr("ds")]) {
				var tmp = Date[$(this).attr("ds")]();
				start_date = tmp[0];
				end_date = tmp[1];
			} else {
				var now = new Date();
				start_date = now._format();
				end_date = now._format();
			}
			$("#start_date").val(start_date);
			$("#end_date").val(end_date);
			
			loadAndShowData(websiteid, start_date, end_date);
		} );
	} );
	
	$("a.do_active").click();
</script>
</html>

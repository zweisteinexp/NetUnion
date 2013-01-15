<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>乐子联盟</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="container">
	
	<?php require('top.php'); ?>
        
        <div class="main">
        	<h3>新增网站</h3>
	
		<?php if (isset($msg)) { echo "<div class='msg {$msg[0]}'>{$msg[1]}</div>"; } ?>
		
		<form name="form1" method="post" action="<?php echo site_url('website/newone') ;?>">
			<input type="hidden" name="__submit" value="1" />
			<input type="hidden" name="__validate" value="0" />
		
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td width="200" align="right"><span style="color: red;">*</span>您的网站域名：</td>
					<td><div style="float: left; ">
						<input type="text" class="text" name="domain" 
							value="<?php echo set_value('domain') ? set_value('domain') : 'http://' ;?>" size="30"/></div>
						<p class="red_text" 
							style="margin-left: 5px;float: left;line-height: 1.3em;">
							<input type="button" id="get_validatestr" value="验证网站" /><br/>
							如果网站的默认端口不为80，则在域名加上端口号，如lezi.com:8080</p>
						<div id="validator_content" style="clear: left; display: none;line-height: 1.5em;margin: 5px 20px;">
						1、请在您的网站根目录下建立 <span id="validator_filename"></span> 文件，内容为 <span id="validator_str"></span>, 
							或直接使用下载文件 <br/>
						2、请您在30分钟内将验证文件放置于您所配置的域名的根目录下<br/>
						3、点击“完成验证”<br/>
						<input type="button" id="submit_validatestr" value="完成验证" />
						<input type="button" id="cancel_validatestr" value="取消" /><span></span></div> </td>
				
				</tr>
				<tr>
					<td align="right"><span style="color: red;">*</span>网站名称：</td>
					<td><input type="text" class="text" name="website_name" value="<?php echo set_value('website_name') ;?>" size="30"/>
						<span></span></td>
				</tr>
				<tr>
					<td align="right">网站ICP备案号：</td>
					<td><input type="text" class="text" name="icp" value="<?php echo set_value('icp', $this->input->post('icp')) ;?>" size="20"/>
						<span class="red_text" >填写后将不可更改</span></td>
				</tr>
				<tr>
					<td align="right">网站描述：</td>
					<td><textarea class="text" style="float: left;width: 60%;" name="description" 
							rows="5"><?php echo set_value('description', $this->input->post('description')) ;?></textarea>
						<p class="red_text" 
							style="margin-left: 5px;float: left;width: 35%;line-height: 1.3em;">
							请简单描述下您的网站基本信息。<br/>
							例如：我的网站日独立IP××，PV××，PR：4，Alexa排名前1万。并且每日新增用户约××人。目前已有xxxxxx位会员等。</p>
					</td>
				</tr>
				<tr>
					<td align="right">网站类别：</td>
					<td><div style="float: left;width: 60%;font-size: 14px;line-height: 1.5em;">
					<?php 
						$values_index = array_keys($type_names);
						$i = 0;
						foreach ($values_index as $key => $value) { 
							echo '<input id="type_', $value, '" type="checkbox" name="website_types[]" value="', $value, '" ';
							echo set_checkbox('website_types', $value);
							echo '/> <label for="type_', $value, '">', $type_names[$value], '</label> &nbsp; ', "\n";
							if (($i++) % 8 == 7) { echo '<br/>'; }
						}
					?></div>
						<p class="red_text" style="margin-left: 5px;float: left;width: 35%;">最多只允许选择两项类别</p></td>
				</tr>
				<tr>
					<td></td>
					<td style="padding-top: 10px;"><a class="golden_btn" href="javascript:;"
						onclick="javascript:$(document.form1).submit(); return false; "> 提 &nbsp; 交 </a></td>
				</tr>
			</table>
		</form>

		<div class="tip">
			<h4>友情提示</h4>
		</div>    
	</div>	
	</div>
</body>
<script type="text/javascript">
	var _validate_url = "<?php echo site_url('website/validate'); ?>";
	var _validator_down_url = "<?php echo site_url('website/validator_down'); ?>";
	var _parse_title_url = "<?php echo site_url('website/parse_title'); ?>";
	$("#cancel_validatestr").click( function() { $("#validator_content").hide(); } );
	$("#get_validatestr").click( function() {
		var url = $.trim($(document.form1.domain).val());
		if (url == '' || url == 'http://' || url == 'https://') {
			alert('填写合适的网址！'); return ;
		}
		$.post(_validate_url, {'weburl' : url}, function(data) {
			var data = eval('(' + data + ')');
			if ( data.rcode ) {
				if ($("#validator_down").length == 0) {
					$('body')
						.append('<iframe height="0" width="0">')
							.find('iframe:last')
							.attr('id', 'validator_down')
							.css('display', 'none')
							.append('<form method="post">')
								.find('form')
								.attr('action', _validator_down_url)
								.append('<input type="text" name="weburl">')
									.find('input')
									.val('');
				}
				
				$("#validator_down input").val(url);
				$("#validator_down form").submit();

				$("#validator_filename").text(data.rdata[0]);
				$("#validator_str").html(data.rdata[1]);
				$("#validator_content").show();
			} else {
				alert(data.rdata[0]);
			}
		} );
	} );
	$("#submit_validatestr").click( function() {
		$(this).attr('disabled', true);
		var url = $.trim($(document.form1.domain).val());
		$(this).nextAll('span').first().css('padding-left', 10).empty().text('验证中...');
		$.post(_validate_url, {'weburl' : url, '__validate' : '1'}, function(data) {
			var data = eval('(' + data + ')');
			if ( data.rcode ) {
				$("#validator_content").hide();
				$(document.form1.__validate).val('1');
			} else {
				$("#validator_content").show();
				$(document.form1.__validate).val('0');
			}
			alert(data.rdata[0]);
			$("#submit_validatestr").attr('disabled', false);
			$("#submit_validatestr").nextAll('span').first().empty();
			if ( data.rcode ) {
				$(document.form1.website_name).next().empty().text('获取网站名称...');
				$.post(_parse_title_url, {'weburl' : url}, function(data) {
					var data = eval('(' + data + ')');
					if ( data.rcode ) {
						if ($.trim($(document.form1.website_name).val()) == '') {
							$(document.form1.website_name).val(data.rdata[0]);
						}
						$(document.form1.website_name).next().empty();
					}
				} );
			}
		} );
	} );
	$(document.form1).submit( function() {
		var url = $.trim($(document.form1.domain).val());
		if (url == '' || url == 'http://' || url == 'https://') {
			alert('填写合适的网址！'); return false;
		}
		if ($.trim($(document.form1.website_name).val()) == '') {
			alert("请填写站点名称！"); return false;
		}
		if ($(":checkbox[checked=true]", $(document.form1)).length > 2) {
			alert("最多只允许选择两项类别！"); return false;
		}
		if (document.form1.__validate.value == '0') {
			alert("请先验证站点！"); return false;
		}
		if ($.trim($(document.form1.icp).val()) != '') {
			return confirm("请认真确定 网站ICP备案号 ，提交后无法修改！");
		}
	} );
</script>
</html>

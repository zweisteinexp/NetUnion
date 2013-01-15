<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>乐子联盟</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link href="<?php base_res_style();?>css.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php base_res_script();?>zeroclipboard.js" > </script>
</head>
<body>
	<div class="container">
	
	<?php require('top.php'); ?>
        
        <div class="main">
        	<h3>获取网页代码</h3>
        	
        	<?php if (isset($msg)) { echo "<div class='{$msg[0]}'>{$msg[1]}</div>"; } ?>
	
		<form name="form1" method="get" action="<?php echo site_url('website/sitecode') ;?>">
	
			<p style="margin: 10px auto; text-align: center;">
				<span>推广网站：</span>
				<select name="id">
			<?php if (isset($websites)) { ?>
				<?php foreach ($websites as $web) { ?> 
					<option <?php echo "value='{$web['id']}' settle='{$web['settle_type']}'";
						if($id == $web['id']) echo ' selected="selected"'; ?>><?php echo $web['domain'] ;?></option>
				<?php } ?>
			<?php } ?> 
				</select>
				
				<a class="golden_btn" href="javascript:;"
					onclick="javascript:$(document.form1).submit(); return false; ">Go!!!</a>
			</p>
		</form>
		
	<?php if ( count($websites) <= 1 ) { ?>
		<div style="text-align: center; margin: 30px 0;">
			没有可用站点，你可以<a href="<?php echo site_url('website/newone'); ?>">新增站点</a>，或者联系我们！</div>
	<?php } else if ( $id != 0 && ! isset($website)) { ?>
		<div style="text-align: center; margin: 30px 0;"> 所选站点目前不可用，请选择其余站点！</div>
	<?php } else if ( ! isset($website)) { ?>
		<div style="text-align: center; margin: 30px 0;"> 请选择站点！</div>
	<?php } else { ?>
		<div style="margin: 0 auto; width: 80%">
			<textarea id="codeshow" style="width: 100%; height: 100px;"></textarea>
		</div>
		<div style="text-align: center;margin: 20px;"><a id="do_copy_code" class="golden_btn" href="javascript:;">复制代码</a></div>
		<script type="text/javascript">
			var _id = '<?php echo $id; ?>';
			var _codeget_url = '<?php echo site_url("ajax/get_code"); ?>';
			var _zeroflash = '<?php base_res_plugins();?>zeroclipboard.swf';
			
			$.post(_codeget_url, {'website_id' : _id}, function(data) {
				data = eval('(' + data + ')');
				if (data.code == 119) {
					var html = '<script type="text/javascript" src="' + data.content + '"> <\/script>';
					$("#codeshow").text(html);
				}
			} );
			
			ZeroClipboard.setMoviePath(_zeroflash);
			var clip = new ZeroClipboard.Client();
			clip.setHandCursor(true);
			clip.glue('do_copy_code');
			
			clip.addEventListener('mouseup', function() { clip.setText($('#codeshow').val()); } );
			clip.addEventListener('complete', function() { alert('已复制到剪切板～'); } );
		</script>
	<?php } ?>
	
		<div class="tip">
			<h4>友情提示</h4>
		</div>
	</div>	
	</div>
</body>
</html>

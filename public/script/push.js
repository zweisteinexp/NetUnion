$(document).ready(function () {
	/* 客户端请求广告 返回json */
	var userAgent = navigator.userAgent.toLowerCase();
	
	ad_type	=	parseInt(ad_type);
	wid		=	parseInt(wid);
	
	if (ad_type == null || ad_type == 'undefined' || wid == null || wid == 'undefined') {
		return false;
	}
	
	switch (ad_type) {
		case 1: // 弹出新标签
			/*
			var element = jQuery('form');
			element.id = 'union_show_sth';
			element.target = '_blank';
			element.action = ad_url;
			*/
			/* FORM防拦截 */
			var url = 'http://'+location.hostname+'/';
//			$('body').append('<form action="http://union.lezi.com/public/jump.php" id="union_show_sth" target="_blank" method="GET"><input type="text" id="click_web" name="s" value="'+wid+'" /></form>');
			$('body').append('<form action="http://union.lezi.com/public/jump.php" id="union_show_sth" target="_blank" method="GET" style="display:none;"><input type="hidden" name="s" value="'+wid+'" /><input type="hidden" value="'+url+'" name="host" /></form>');
			$('#union_show_sth').submit();
			break;
		case 2: // 当前页面
			/*
			$('body').append('<div id="union_show_sth"></div>');
			$('#union_show_sth').css({position : 'absolute', top : '100px', left : '1px'});
			*/
			break;
		case 3: // 右下角
			
			break;
		default:
			
		break;
	}
});
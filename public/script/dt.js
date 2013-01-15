var ad = new Array(0); 
//ad_type	=	parseInt(ad_type);
	wid		=	parseInt(wid);
var url = 'http://'+location.hostname+'/';
ad[0] = 'http://union.lezi.com/public/jump.php?s='+wid+'&host='+url+'&key='+key;
ad[1] = 'http://www.lezi.com';
 
//var cookie_time = 12*60*60; //cookie过期时间
var cookie_time = 1; //cookie过期时间
var showad=false;
index=0;




	var then = new Date();
	then.setTime(then.getTime() + 30*60*1000);  //
    var gotourl=ad[index];
    var popup_cookie_name = "tb02_"+index;

    document.write("<SCR"+"IPT LANGUAGE=JavaScript1.1 SRC='http://click.lezi.com/script/popup.js'><"+"/SCRIPT>");  // popu



function chk_cookie(Name) {
	var search = Name + "="
	var returnvalue = "";
	if (document.cookie.length > 0) {
		offset = document.cookie.indexOf(search)
		if (offset != -1) {
			offset += search.length;
			end = document.cookie.indexOf(";", offset);
			if (end == -1)
				end = document.cookie.length;
			returnvalue=unescape(document.cookie.substring(offset, end));
		}
	} 
	return returnvalue;
}
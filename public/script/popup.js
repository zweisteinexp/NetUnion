//popup.js 

var ptype=1;

function setcookie(cName,cExpires)
{
        
}


function upcookie(cname,ctime){
	setcookie(cname,ctime);
}

var state=0;
;(function(){
	var d=navigator.userAgent;
	var a={};
	a.ver={
		ie:/MSIE/.test(d),
		ie6:!/MSIE 7\.0/.test(d)&&/MSIE 6\.0/.test(d)&&!/MSIE 8\.0/.test(d),
		tt:/TencentTraveler/.test(d),
		i360:/360SE/.test(d),
		sogo:/; SE/.test(d),
		gg:window.google&&window.chrome,
		_v1:'<object id="p01" width="0" height="0" classid="CLSID:6BF5'+'2A52-394'+'A-1'+'1D3-B15'+'3-00'+'C04F'+'79FAA6"></object>',
		_v2:'<object id="p02" style="position:absolute;left:1px;top:1px;width:1px;height:1px;" classid="clsid:2D'+'360201-FF'+'F5-11'+'d1-8D0'+'3-00A'+'0C95'+'9BC0A"></object>'
	};
	if(a.ver.ie||a.ver.tt){
		document.write(a.ver._v1);document.write(a.ver._v2);
		}
	a.fs=null;a.fdc=null;a.timeid=0;a.first=1;a.url='';a.w=0;a.h=0;
	a.init=function(){
		try{
			if(typeof document.body.onclick=="function"){
				a.fs=document.body.onclick;document.body.onclick=null
				}
			if(typeof document.onclick=="function"){
				if(document.onclick.toString().indexOf('clickpp')<0){
					a.fdc=document.onclick;document.onclick=function(){
						a.clickpp(a.url,a.w,a.h)
						}
					}
				}
		}catch(q){}
	};
	a.donepp=function(c,g){
		if (g==1 && (!a.ver.i360 && a.ver.ie6))	return;
		if (state)	return;
		try{
			document.getElementById("p01").launchURL(c);state=1;upcookie(popup_cookie_name,cookie_time)
		}catch(q){}
	};
	a.clickpp=function(c,e,f){
		a.open(c,e,f);clearInterval(a.timeid);document.onclick=null;
		if(typeof a.fdc=="function") try{document.onclick=a.fdc}catch(q){}
		if(typeof a.fs=="function") try{document.body.onclick=a.fs}catch(q){}
	}
	a.open=function(c,e,f){
		if (state)	return;
		a.url=c;a.w=e;a.h=f;
		if (a.timeid==0) a.timeid=setInterval(a.init,100);
		var b='height='+f+',width='+e+',left=0,top=0,toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes';
		var j='window.open("'+c+'", "_blank", "'+b+'")';
		var m=null;
		try{m=eval(j)}catch(q){}
		if(m && !(a.first && a.ver.gg)){
			if (ptype!=-1){m.focus();}else{m.blur();window.focus();}
			state=1;upcookie(popup_cookie_name,cookie_time);
			if(typeof a.fs=="function")	try{document.body.onclick=a.fs}catch(q){}
			clearInterval(a.timeid);
		}else{
			var i=this,	j=false;
			if(a.ver.ie||a.ver.tt){
				document.getElementById("p01");document.getElementById("p02");
				setTimeout(function(){
						var obj=document.getElementById("p02");
						if (state || !obj)	return;	
						try{
							var wPop=obj.DOM.Script.open(c,"_blank",b);
							if (wPop){
								if (ptype!=-1){wPop.focus();}else{wPop.blur();window.focus();}
								state=1;upcookie(popup_cookie_name,cookie_time);
							}else if (a.ver.sogo){state=1;upcookie(popup_cookie_name,cookie_time);}
						}catch(q){}},200);
			}
			if (a.first){
				a.first=0;
				try{if(typeof document.onclick=="function") a.fdc=document.onclick}catch(p){}
				document.onclick=function(){i.clickpp(c,e,f)};
				if (a.ver.ie){
					if (window.attachEvent)	window.attachEvent("onload", function(){i.donepp(c,1);});
					else if (window.addEventListener) window.addEventListener("load", function(){i.donepp(c,1);},true);
					else window.onload=function(){i.donepp(c,1);};
				}
			}
		}
	};	
	window.popup=a;
})();
popup.open(gotourl, window.screen.width, window.screen.height);
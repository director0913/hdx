// JavaScript Document

var is_low = navigator.userAgent.toLowerCase().indexOf('android') != -1;
var _ww = (window.screen.availWidth * (window.devicePixelRatio || 1.5) / 1);
if (is_low && _ww < 720) {
	document.writeln('<meta name="viewport" content="width=750px,target-densitydpi=device-dpi,user-scalable=yes,initial-scale=0.5" />');
} else {
	document.writeln('<meta name="viewport" content="width=750px,target-densitydpi=device-dpi,user-scalable=no" />');
}

//跳转到指定链接
function tiaourl(url)
{
	if(url)
	{
		window.location.href=url;
	}
}
function redirect(url){
	var tt = $(url).attr('data-href');
	window.location.href=tt;
}
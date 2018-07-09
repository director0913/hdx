// JavaScript Document

//分享相关。
wx.ready(function () {
	sharedata = {
		title: sharetitle,
		desc: sharedesc,
		link: shareurl,
		imgUrl: shareimg,
		success: function(){
		},
		cancel: function(){
		}
	};
	wx.onMenuShareAppMessage(sharedata);
	wx.onMenuShareTimeline(sharedata);
});
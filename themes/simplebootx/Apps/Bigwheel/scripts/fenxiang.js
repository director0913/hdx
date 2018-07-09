// JavaScript Document

//分享相关
wx.ready(function () {
	sharedata = {
		title: sharetitle,
		desc: sharedesc,
		link: shareurl,
		imgUrl: shareimg,
		success: function(){
			/*var open_random = "{$activity['open_random']}";
			if(open_random!=0){*/
				//异步修改会员的分享状态；
				var url=gaifenxiangurl;
				$.post(url,null,function(res){
					res =JSON.parse(res);
					if(res.status<=0)
					{
						tanmsg(res.msg);
					}
					else
					{
						is_formal=1;
					}
				});
			//}
		},
		cancel: function(){
		}
	};
	wx.onMenuShareAppMessage(sharedata);
	wx.onMenuShareTimeline(sharedata);
});
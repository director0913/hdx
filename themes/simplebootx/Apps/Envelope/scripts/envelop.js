// JavaScript Document

var music;
$(function(){
	music=document.getElementById("bgMusic");
	//播放音乐
	$("#yinyue").click(function()
	{
		if(music.paused)
		{
			music.play();
			$("#yinyue").removeClass("ting");
		}
		else
		{
			music.pause();
			$("#yinyue").addClass("ting");
		}
	});
	$(window).scroll(function(){
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		if(scrollTop + windowHeight+10 >= scrollHeight){
			getsort();
		}
	});
	getsort();
})
//异步获取排名
function getsort()
{
	if(page>zongpage) return;
	var url = getpaihangurl;
	$.post(url,{'page':page},function(res){
		res =JSON.parse(res);
		var datastr="";
		for(var i=0;i<res.length;i++)
		{
			datastr+='<tr>'+
						'<td>No.'+index+'</td>'+
						'<td><img src="'+res[i].avatar+'"/></td>'+
						'<td>'+res[i].name+'</td>'+
						'<td>'+res[i].top_score+'分</td>'+
					'</tr>';
			index++;
		}
		$("#sort").append(datastr);
		page++;
	});
}
//开始游戏
function begin_game(){
	//如果活动还没有开始或已经结束就提示。
	var astatus="{$astatus}";
	if(parseInt(astatus)==-1)
	{
		$("#tixing").html("{$axinxi}");
		showBg("tishidiv",true);
	}
	else
	{
		window.location.href=url;
	}
}
//显示灰色 jQuery 遮罩层 
function showBg(tanid,diclose) { 
	$("#fullbg").css({
		display:"block" 
	});
	if(diclose)
	{
		$("#fullbg").click(function(){
			closeBg(tanid)
		})
	}
	else
	{
		$('#fullbg').unbind("click");
	}
	$("#"+tanid).show(); 
} 
//关闭灰色 jQuery 遮罩 
function closeBg(tanid) {
	$("#fullbg,#"+tanid).hide(); 
}
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
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
						'<td>'+res[i].nickname+'</td>'+
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
	//获取选择的题目类型。
	var titype=$("#titype").val();
	//如果活动还没有开始或已经结束就提示。
	if(parseInt(astatus)==-1)
	{
		$("#tixing").html(hdxx);
		showBg("tishidiv",true);
	}
	else if(parseInt(titype)==-1)
	{
		$("#tixing").html("请选择题目类型");
		showBg("tishidiv",true);
	}
	else
	{
		window.location.href=gameurl+"&titype="+titype;
	}
}
//点击选择题目类型的效果。
function xuantilei()
{
	$(".selecttidiv ul").toggleClass("d_n");
}
//选择题目类型的效果。
function xuanli(obj,id)
{
	$(".titype .zuop").html($(obj).html());
	$(".selecttidiv ul li").removeClass("xuan");
	$(obj).addClass("xuan");
	$(".selecttidiv ul").addClass("d_n");
	$("#titype").val(id);
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
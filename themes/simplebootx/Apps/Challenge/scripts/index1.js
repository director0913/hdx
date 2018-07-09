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
	$("#paihang .tanzhongdiv").scroll(function(){
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(this).height();
		var windowHeight = $(this).find("table").height();
		if(scrollTop + scrollHeight+5 >= windowHeight){
			getsort();
		}
	});
	getsort();
})
//异步获取排名
function getsort()
{
	if(page>1&&page>pagecount) return;
	if(clickstatus) return;
	clickstatus=true;
	$.post(getpaihangurl,{page:page},function(res){
		clickstatus=false;
		var datastr="";
		if(res.status>0)
		{
			for(var i=0;i<res.list.length;i++)
			{
				datastr+='<tr>'+
							'<td>No.'+index+'</td>'+
							'<td><img src="'+res.list[i].avatar+'"/></td>'+
							'<td>'+res.list[i]['nickname'].substring(0,8)+'</td>'+
							'<td>'+res.list[i]['per_num']+'分</td>'+
						'</tr>';
				index++;
			}
			if(page==1&&res.list.length>0)
			{
				//隐藏无信息提示。
				$("#paihang .wujilu").hide();
				//显示排行区域。
				$("#paihang .tanzhongdiv").show();
			}
			if(page==1)
				pagecount=res.pagecount;
			page++;
			$("#sort").append(datastr);
		}
		else alert(res.msg);
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
		window.location.href="/index.php/apps/challenge/run/aid/"+aid+"/titype/"+titype+".shtml";
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
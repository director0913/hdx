// JavaScript Document

$(function(){
	$(".myhuodong").click(function(){
		window.location.href=indexurl;
	})
	//如果需要获取二维码，就获取一个。
	//如果有分享id或没有分享id但自己已经注册了，就获取二维码。
	if(shareid)
	{
		showBg('fullbg','load',false);
		$.post(erweimaurl,{},function(res){
			closeBg('fullbg','load');
			res = $.parseJSON(res);
			if(res.status==1)
			{
				$(".ewm img").attr("src",res.getimg);
			}
		});
	}
})
//注册
var kaiguan=false;
function regist()
{
	if(kaiguan)
	{
		return;
	}
	
	kaiguan=true;
	var name=$("#name").val();
	var tel=$("#tel").val();
	if(!name)
	{
		kaiguan=false;alert("姓名不能为空！");return;
	}
	if(!tel)
	{
		kaiguan=false;alert("手机号不能为空！"); return;
	}
	if(tel.length!=11)
	{
		kaiguan=false;alert("手机号不正确，请重新填写！"); return;
	}
	showBg('fullbg','load',false);
	$.post(registurl,{name:name,tel:tel},function(res){
		res = $.parseJSON(res);
		if(res.status==1)
		{
			//修改分享链接。
			sharedata.link+="&shareid="+res.uid;
			$.post(erweimaurl,{uid:res.uid,avatar:res.avatar},function(res1){
				closeBg('fullbg','load');
				kaiguan=false;
				res1 = $.parseJSON(res1);
				if(res1.status==1)
				{
					$(".ewm img").attr("src",res1.getimg);
					$(".login,.weizhuce").hide();
					$(".ewm,.yizhuce").show();
				}
			});
		}
		else
		{
			closeBg('fullbg','load');
			kaiguan=false;
			alert(res.msg);
		}
	});
}
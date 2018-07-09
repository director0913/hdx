// JavaScript Document

$(function(){
	$(".btnok").click(function(){
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
		$.post(registurl,{name:name,tel:tel},function(res){
			kaiguan=false;
			closeBg('fullbg','baoming');
			res = $.parseJSON(res);
			if(res.status==1)
			{
				alert("报名成功！");
				$(".baomingdiv .title span").html(parseInt($(".baomingdiv .title span").html())+1);
				var xinxistr='<div>'+
								 '<img src="'+res.avatar+'" />'+
								 '<p>'+res.name+'</p>'+
							 '</div>';
				$(".baomingls").append(xinxistr);
				$(".baoming").hide();
				$(".fenxiang").css("width","100%");
				$(".fenxiang").html("已报名");
				$(".fenxiang").unbind("click");
			}
			else alert(res.msg);
		});
	})
})
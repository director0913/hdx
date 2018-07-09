// JavaScript Document

$(function(){
	$(".jichu").bind({
		'mousemove':function(){
			$(this).css("border-color","#03C");
			$(this).children(".czspan").css("display","block");
		},
		'mouseout':function(){
			$(this).css("border-color","transparent");
			$(this).children(".czspan").css("display","none");
		}
	})
});
//富文本加载后更改自己的内容。
function editorlod(objname)
{
	window.parent.UE.getEditor(objname).ready(function() {
		genggai(objname,'fwb');
	});
}
//为自己页面的元素赋值，参数为父页面标签。
function genggai(can,type)
{
	if(type=="fwb")
	{
		//获取富文本框中的值。
		var val=$("textarea[name='"+can+"']",parent.document).val();
		$("."+can).html(val);
	}
	else if(type=="input"||!type)
	{
		//获取父窗口输入框的值。
		var val=$("input[name='"+can+"']",parent.document).val();
		//为指定元素赋值。
		$("."+can).html(val);
		$("."+can+".zhuan").html(val.replace("，","<br/>"));
	}
	else if(type=="img")
	{
		//获取父窗口输入框的值。
		var val=$("input[name='"+can+"']",parent.document).val();
		//为指定元素赋值。
		$("."+can).attr("src",val!=""?val:"");
	}
	else if(type=="radio")
	{
		var val=$("input[name='"+can+"'][checked='checked']",parent.document).val();
		if(val==0)
		{
			$("."+can).hide();
		}
		else $("."+can).show();
	}
}
//点击编辑按钮执行的函数，参数为：对应输入框的name值，对应输入框所在的tab标记，对应的输入框是否为规则等富文本框。
function dianji(can,tabname,guize)
{
	//去掉所有文本框和富文本框的阴影。
	if(window.parent.yininput!=null)
		window.parent.yininput.css("box-shadow","");
	if(tabname!=null)
	{
		if((tabname instanceof Array))
		{
			for(var i=0;i<tabname.length;i++)
			{
				//根据名称获取对象。
				var obj=$(".tab[data-id='#"+tabname[i]+"']",parent.document);
				window.parent.qietab(obj);
			}
		}
		else
		{
			//根据名称获取对象。
			var obj=$(".tab[data-id='#"+tabname+"']",parent.document);
			window.parent.qietab(obj);
		}
	}
	if(guize=="guize")
	{
		window.parent.yininput=$("#"+can,parent.document);
		$("#"+can,parent.document).css("box-shadow","0 0 10px rgba(255, 0, 0, 1)");
	}
	else if(guize=="img")
	{
		window.parent.yininput=$("."+can,parent.document);
		$("."+can,parent.document).css("box-shadow","0 0 10px rgba(255, 0, 0, 1)");
	}
	else
	{
		window.parent.yininput=$("input[name='"+can+"']",parent.document);
		$("input[name='"+can+"']",parent.document).css("box-shadow","0 0 10px rgba(255, 0, 0, 1)");
	}
	var jianzhi=tabname==null?160:200;
	//获取标签距离顶部的距离。
	var height=window.parent.yininput.offset().top-jianzhi;
	height=height<=0?0:height;
	$(".neidiv",parent.document).animate({scrollTop:height},100);
	//alert(height);
	//控制滚动区域滚动指定距离。
}
// JavaScript Document

//页面加载完毕执行的操作。
$(function(){
	//输入框添加鼠标移入上去时去掉阴影效果。
	$("input,textarea").bind("mousemove",function(){
		$(this).css("box-shadow","");
	})
	iframe=document.getElementById("yulaniframe");
})
//更改输入框数据时左侧框架内文本随之改变，参数为输入框对象，输入框类型。
function genggai(obj,type,kongzhiyuansu)
{
	if(type=="fwb")
	{
		var val=UE.getEditor(obj).getContent();
		$("#yulaniframe").contents().find("."+obj).html(val);
	}
	else if(type=="input"||!type)
	{
		var val=$(obj).val();
		$("#yulaniframe").contents().find("."+$(obj).attr('name')).html(val);
		$("#yulaniframe").contents().find("."+$(obj).attr('name')+".zhuanhuan").html(val.replace("，","<br/>"));
	}
	else if(type=="img")
	{
		var val=$(obj).val()!=""?$(obj).val():"";
		$("#yulaniframe").contents().find("."+$(obj).attr('name')).attr("src",val);
	}
	else if(type=="radio")
	{
		//获取当前对象的值。
		var kuangobj=$("#yulaniframe").contents().find("."+$(obj).attr('name'));
		if($(obj).val()==1)
			kuangobj.show();
		else kuangobj.hide();
	}
	else if(type=="background-color")
	{
		var val=$(obj).css("background-color");
		$("#yulaniframe").contents().find(kongzhiyuansu).css("background-color",val);
	}
}
//富文本框加载完毕后执行的内容。
function editorlod(objname)
{
	UE.getEditor(objname).ready(function() {
		//鼠标移动到上面时去掉阴影效果。
		$("#"+objname).bind("mousemove",function(){
			$(this).css("box-shadow","");
		});
		//输入内容时更改对应框架中区域的值。
		UE.getEditor(objname).addListener("keyup",function(type,event){
			if(!event.ctrlKey && 13!==event.keyCode){
				genggai(objname,"fwb");
			}
		});
		//失去焦点时更改对应框架中区域的值。
		UE.getEditor(objname).addListener("blur",function(type,event){
			genggai(objname,"fwb");
		});
	});
}
//获取富文本框中的内容，需要框架中调用。
function getEditorContent(id)
{
	UE.getEditor(id).ready(function() {
		return UE.getEditor(id).getContent();
	});
}
//更改图片时执行的内容。
function changeimg(obj)
{
	genggai(obj,"img");
}
//更改图片时执行的内容。
function changecolor(obj)
{
	genggai(obj,"background-color","body");
}
//切换框架。
function qieiframe(zhi)
{
	var dqzhi=dqpage+zhi;
	if(dqzhi>=0&&dqzhi<pagelist.length)
	{
		dqpage=dqzhi;
		iframe.src=pagelist[dqzhi];
	}
}
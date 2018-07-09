// JavaScript Document
//递增变量，初始为0。
var i=0;
var chuan=1;
//获取图片包含图片列表的div对象，在本页面只有一个且常被用到，所以定义为全剧变量。
var listdivobj="";
//用来存放上传成功后的图片名称的数组。
var imgurlarr=Array();

//根据id获取对象的函数。
function getObjById(id)
{
	return document.getElementById(id);
}

//选择图片后执行的函数，包括选择图片的预览、包含图片的div样式修改、新图片列表div及Input标签的创建。以及单个图片上传时进行的处理。
function xuantu(fileobj)
{
	//判断该文件框中的内容是否被改变了。
	if(fileobj.value=="") return;
	chuan=2;
	//临时变量，用来接收i的值，弥补i作为全局变量造成的值的更改。
	var a=i;
	//获取当前输入框对象的父级div.
	var fudiv=fileobj.parentNode;
	//清空添加图片div的背景图片。
	fudiv.style.backgroundImage="none";
	//为图片div添加边框。
	fudiv.style.border="1px solid #888888";
	//创建img标签。
	var imgobj=document.createElement("img");
	//预览图片，为img标签设置图片路径。
	imgobj.src=getFileUrl(fileobj);
	//在div中插入图片节点，并放在input节点前。
	fudiv.insertBefore(imgobj,fileobj);
	//fileobj.insertAdjacentHTML('beforebegin', '<img src="'+imgsrc+'" />');    //在当前节点前面添加html代码。这种插入代码的方式更灵活。
	lodingimg=document.createElement("img");
	lodingimg.className="lodingimg";
	lodingimg.src="../addons/yyvote/images/load.gif";
	fudiv.insertBefore(lodingimg,imgobj);
	var objlsimg=new Image();
	objlsimg.src=imgobj.src;
	/**选择一张就上传一张时在这里插入要操作的代码。**/
	//定义url参数，这个url文件中包含了上传文件的操作，由于这里是多次上传文件，每次上传一张，所以要将当前要上传的控件编号传递过去，这里用i做参数名。
	//定义form表单的提交地址为指定的url。
	up_url+="&file_i="+i;
	$("#myform").attr("action",up_url);
	//执行异步上传操作。
	$("#myform").ajaxForm(
	{
		//提交之前要做的处理。
		beforeSubmit:function(){},
		//提交完成后要做的处理。
		success:function(res)
		{
			//不管上传的结果怎么样，都要在当前图片上删除掉上传中图标。
			fudiv.removeChild(lodingimg);
			res =JSON.parse(res);
			if(res.status==1)   //如果上传成功，删除上传中图片，添加新div和上传图片的控件等。
			{
				//增加删除图片。
				deleimgobj=document.createElement("img");
				deleimgobj.className="deleimg";
				deleimgobj.src="../addons/yyvote/images/delete.png";
				deleimgobj.onclick=function(){deleimg(a)};   //由于i是全局变量，执行关闭时直接会将当时i的值传递过去，造成错误，所以这里用临时变量a代替。
				//deleimg.setAttribute("onClick",function(){deleimg('')});   //此方法不可用。
				fudiv.insertBefore(deleimgobj,imgobj);
				//设置当前对象为隐藏状态。不能再次被点击了。
				fileobj.style.display="none";
				//将返回的名称放进数组中。
				imgurlarr[i]=new Array(res.filename+"?width="+objlsimg.width+"&height="+objlsimg.height,res.minfilename);
				chuan=3;
				//上传成功，递增编号增加。
				i++;
				//创建下一个初始div并修改工具栏图片图标的点击事件。
				createImgDiv();
			}
			else
			{
				if(res.status==-2||res.status==-3)
				{
					//将图片div的背景图片更改为本地图片这里设置为本地图片会出现访问不了的情况。
					fudiv.style.backgroundImage="url("+imgobj.src+")";
				}
				//删除本地预览图片。
				fudiv.removeChild(imgobj);
				alert(res.msg);
			}
		},
		//错误时做的处理。
		error:function(res)
		{
			alert("上传失败，请重新上传");
		}
	}).submit();
}
//点击删除图片，删除当前图片所在的节点。
function deleimg(a)
{
	/*var url = "upfile.php?do=delefile";
	$.post(url,{'fileurl0' : imgurlarr[a][0],'fileurl1' : imgurlarr[a][1]},function(res)
	{
		res =JSON.parse(res);
		if(res.status!=1)
			alert(res.msg);
		else
		{*/
			var divobj=getObjById("file"+a).parentNode;
			//删除该上传控件的父节点
			divobj.parentNode.removeChild(divobj);
			//清空对应元素中的值;
			imgurlarr[a]=new Array();
		/*}
	});*/
}
//初始化图片列表div，在刚加载完页面后执行。
function initialImgList()
{
	listdivobj=getObjById("imglist");
	createImgDiv();
}

//图片列表的初始状态创建，一个div，内部包含了一个input，且更改工具栏图片的点击事件。参数为：放置图片列表的div对象；工具栏图片图标对象；递增编号。
function createImgDiv()
{
	//创建下一个div
	var divobj=document.createElement("div");
	//定义div的样式。
	divobj.className="imgdiv";
	//拼接一个input标签并将标签放入到创建好的div中e，由于在php中是通过name的值来接收控件数据的，所以这里定义一个name值。
	divobj.innerHTML='<input type="file" class="fileinput" id="file'+i+'" name="file'+i+'" onchange="xuantu(this)"/>';
	//将创建好的包含了上传input的div放入图片列表div的末尾。
	listdivobj.appendChild(divobj);
}


//获取上传图片的本地路径，预览使用。
function getFileUrl(uploadobj)
{
	var url;
	if (window.navigator.userAgent.indexOf("Chrome") >= 1 || window.navigator.userAgent.indexOf("Safari") >= 1) {
		url = window.webkitURL.createObjectURL(uploadobj.files[0]);
	}
	else {
		url = window.URL.createObjectURL(uploadobj.files[0]);
	}
	return url;
}
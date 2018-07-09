// JavaScript Document
//递增变量，初始为0。
var i=0;
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
	lodingimg.src="images/load.gif";
	fudiv.insertBefore(lodingimg,imgobj);
	/**选择一张就上传一张时在这里插入要操作的代码。**/
	
	
	//模拟上传的结果。
	$result=1;
	//不管上传的结果怎么样，都要在当前图片上删除掉上传中图标。
	fudiv.removeChild(lodingimg);
	//如果上传成功增加删除按钮，添加下一个图片上传控件以及div等元素，当前上传控件将不可用，工具栏中上传图标的点击事件将是下一个控件的点击事件。
	if($result==1)
	{
		//增加删除图片。
		deleimgobj=document.createElement("img");
		deleimgobj.className="deleimg";
		deleimgobj.src="images/delete.png";
		deleimgobj.onclick=function(){deleimg(a)};   //由于i是全局变量，执行关闭时直接会将当时i的值传递过去，造成错误，所以这里用临时变量a代替。
		//deleimg.setAttribute("onClick",function(){deleimg('')});   //此方法不可用。
		fudiv.insertBefore(deleimgobj,imgobj);
		//设置当前对象为隐藏状态。不能再次被点击了。
		fileobj.style.display="none";
		//上传成功，递增编号增加。
		i++;
		//创建下一个初始div并修改工具栏图片图标的点击事件。
		createImgDiv();
	}
	//如果上传失败了，当前div的背景图片将换成本地选择的图片，供用户查看是哪张图片上传失败了。删除预览的那个图片。工具图标的点击事件还是当前控件的点击事件。
	else
	{
		//将图片div的背景图片更改为本地图片这里设置为本地图片会出现访问不了的情况。
		fudiv.style.backgroundImage="url("+imgobj.src+")";
		//删除本地预览图片。
		fudiv.removeChild(imgobj);
	}
}
//点击删除图片，删除当前图片所在的节点。
function deleimg(a)
{
	var divobj=getObjById("file"+a).parentNode;
	//删除该上传控件的父节点
	divobj.parentNode.removeChild(divobj);
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
	if (navigator.userAgent.indexOf("MSIE")>=1)    // IE 
		url = window.URL.createObjectURL(uploadobj.files[0]);    //只有ie10可用
	else if(navigator.userAgent.indexOf("Firefox")>0||navigator.userAgent.indexOf("Chrome")>0)   // Firefox 或 Chrome 
		url = window.URL.createObjectURL(uploadobj.files.item(0));
	return url;
}

//动态为工具栏图标创建点击事件。
function tooimgClick()
{
	getObjById("file"+i).click();
}
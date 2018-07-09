// JavaScript Document
var speed=10;        //滚动的时间间隔
var Dom;            //包含了两个小DIV的大DIV，小DIV1中包含了要滚动的内容。小DIV2中不包含任何内容。
var Dom1;           //要滚动的内容的小DIV1。
var Dom2;           //要滚动的内容的小DIV2。
var Doma;            //包含了两个小DIV的大DIV，小DIV1中包含了要滚动的内容。小DIV2中不包含任何内容。
var Doma1;           //要滚动的内容的小DIV1。
var Doma2;           //要滚动的内容的小DIV2
var MyMar;          //定时器变量。
var yi=0;
var yi1=0;


function GunDongZY()
{
    Dom = document.getElementById("scroll_div");             //获取标签元素。 
	Dom.innerHTML=getimgstr(baojl,jinefw,6);
    Doma = document.getElementById("scroll_div1");             //获取标签元素。
	Doma.innerHTML=getimgstr(baojl,jinefw,6);
}
//这个函数中包含了滚动效果的函数。
function Marquee(){
	yi+=4;
	Dom.scrollLeft+=4;       //显示的位置向右移动一个像素。
	if(yi>=201)         //如果移动的宽度等于第一个包含内容的DIV的宽度，说明开始显示第二个DIV中的内容了。
	{
		yi-=201
		$(Dom).append(getimgstr(baojl,jinefw,1));
	}
	yi1+=5;
	Doma.scrollLeft+=5;       //显示的位置向右移动一个像素。
	if(yi1>=201)         //如果移动的宽度等于第一个包含内容的DIV的宽度，说明开始显示第二个DIV中的内容了。
	{
		yi1-=201
		$(Doma).append(getimgstr(baojl,jinefw,1));
	}
}
//根据红包的几率生成红包和炸弹的图片以及红包中的金额值。
function getimgstr(baojl,jinefw,ci)
{
	var imgstr="";
	for(var i=0;i<ci;i++)
	{
		//产生随机数。
		var sj=Math.floor(Math.random()*100);
		var tuming="zhadan";
		var baosui=1;
		var bie="zd";
		if(sj<=baojl)
		{
			bie="hb";
			tuming="hongbao";
			if(jinefw.length==1)
				baosui=jinefw[0];
			else
			{
				//产生指定范围内的随机数;
				baosui=Math.floor(jinefw[0]+Math.random()*(jinefw[1]-jinefw[0]));
			}
		}
		imgstr+='<img src="../addons/envelop/images/'+tuming+'.png" class="'+bie+'" data-t="'+baosui+'"/>';
	}
	return imgstr;
}

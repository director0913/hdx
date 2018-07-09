// JavaScript Document
var daoshistime=null;
//shitime=shitime*60;
var miaoobj=null;
var haomiaoobj=null;
var music;
//页面加载完毕后执行的操作。
$(function(){
	//FastClick.attach(document.body);
	miaoobj=$("#miao");
	haomiaoobj=$("#haomiao");
	music=document.getElementById("bgMusic");
	//播放音乐
	$("#yinyue").click(function()
	{
		if(music.paused)
		{
			music.play();
			$(this).removeClass("ting");
		}
		else
		{
			music.pause();
			$(this).addClass("ting");
		}
	});
	//将题目插入到答题区域。
	getti();
	$("#paihang .tanzhongdiv").scroll(function(){
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(this).height();
		var windowHeight = $(this).find("table").height();
		if(scrollTop + scrollHeight+5 >= windowHeight){
			getsort();
		}
	});
	getsort();
	jitime=setInterval("daojishi()",20);
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
//获取题目
function getti()
{
	$("#daan").val("-1");
	$("#right").val(question[num-1].right);
	$("#tiankong_type").val(question[num-1].type);
	if(question[num-1].type==0){
		//选择题
		var datastr='<li class="timu" style="height:auto">'+question[num-1].title+'</li>';
		if(question[num-1].item1)
			datastr+='<li onclick="xuanze(this,1)">A、'+question[num-1].item1+'</li>';
		if(question[num-1].item2)
			datastr+='<li onclick="xuanze(this,2)">B、'+question[num-1].item2+'</li>';
		if(question[num-1].item3)
			datastr+='<li onclick="xuanze(this,3)">C、'+question[num-1].item3+'</li>';
		if(question[num-1].item4)
			datastr+='<li onclick="xuanze(this,4)">D、'+question[num-1].item4+'</li>';
	}else{
		//填空题
		var datastr='<li class="timu" style="height:auto">'+question[num-1].title+'</li>';
		datastr+='<li style="padding-bottom:1px;"><input type="text" class="tiankong"></li>';
		$("#right").val(question[num-1].daan_str);
	}
	$(".datiqu").html(datastr);
}
function xuanze(obj,daanid)
{
	$(".datiqu li img").remove();
	$(obj).append('<img src="/themes/simplebootx/Apps/Challenge/images/gouxuan.png" />');
	$("#daan").val(daanid);
}
//下一题
function nextti()
{
	if($("#tiankong_type").val()==0){
		//如果没有选择就提示需要选择一个答案。
		if(parseInt($("#daan").val())==-1)
		{
			alert("请选择您的答案！"); return;
		}
		//获取选择的答案，跟正确的答案比较。
		var is_right=0;
		if($("#daan").val()==$("#right").val()){
			$("#fenshu").html(parseInt($("#fenshu").html())+parseInt(tifen));
			is_right=1;
		}
		//保存这一次这一道题的答案，正确与否
		var flag=parseInt(t);
		if(flag==1){
			var url=dati_put_url;
			//is_right表示是否正确
			var daan=$("#daan").val();//这一道题的答案
			$.post(url,{round:round,type:type,answer:daan,is_right:is_right},function(res){
				alert(res.status+'>>'+res.info);
			});
			return;
		}

		num++;
		if(question[num-1])
			getti();
		else
		{
			clearInterval(jitime);    //销毁定时器。
			$(".myfen").html($("#fenshu").html());
			$("#fullbg").show();
			panjieguo();
		}
	}else{
		//填空
		var daan_str=$(".tiankong").val();
		if(daan_str==''){
			alert("请输入您的答案！"); return;
		}
		var is_right=0;
		if(daan_str==$("#right").val()){
			$("#fenshu").html(parseInt($("#fenshu").html())+parseInt(tifen));
			is_right=1;
		}

		//保存这一次这一道题的答案，正确与否
		var flag=parseInt(t);
		if(flag==1){
			var url=dati_put_url;
			//is_right表示是否正确
			var daan=daan_str;//这一道题的答案
			$.post(url,{round:round,type:type,answer:daan,is_right:is_right},function(res){
				alert(res.status+'>>'+res.info);
			});
			return;
		}



		num++;
		if(question[num-1])
			getti();
		else
		{
			clearInterval(jitime);    //销毁定时器。
			$(".myfen").html($("#fenshu").html());
			$("#fullbg").show();
			panjieguo();
		}
	}
}
//进行倒计时。
function daojishi()
{
	haomiao-=2;
	if(haomiao<=0)
	{
		shitime-=1;
		haomiao=99;
	}
	if(shitime>=0&&haomiao>0)
	{
		var miao=shitime;
		miao=miao>9?miao:"0"+miao;
		haomiao=haomiao>9?haomiao:"0"+haomiao;
		miaoobj.html(miao);
		haomiaoobj.html(haomiao);
	}
	else
	{
		clearInterval(jitime);    //销毁定时器。
		$(".myfen").html($("#fenshu").html());
		showBg("fullbg","daoshi");
		daoshistime=setTimeout("panjieguo()",1000);
	}
}
//判断答题结果，是否挑战成功。
function panjieguo()
{
	$("#daoshi").hide();
	//更改答题记录。
	//更新个人最高分数
	$.post(editdataurl,{defen:$("#fenshu").html()},function(res){
		if(res.status<=0)
		{
			alert("更改个人纪录失败！");
		}
		else
		{
			if(parseInt($("#fenshu").html())>parseInt($("#zuigao").html()))
			{
				//更新个人最高分数
				$.post(pjgurl,{'maxfen':$("#fenshu").html()},function(res){
					if(res.status>0)
					{
						$("#jibai").html(res.jibai);
						$("#zuigao").html($("#fenshu").html());
						if(parseInt($("#fenshu").html())>=chenggongfen)
						{
							$("#chenggong").show();
						}
						else
						{
							$("#shibai").show();
						}
						page=1;
						var paichushi='<tr class="toutable">'+
											'<td width="25%">排行</td>'+
											'<td width="25%">头像</td>'+
											'<td width="25%">昵称</td>'+
											'<td width="25%">成绩</td>'+
										'</tr>';
						$("#sort").html(paichushi);
						getsort();
					}
					else alert('更新个人最高分失败！');
				});
			}
			else
			{
				if(parseInt($("#fenshu").html())>=chenggongfen)
				{
					//挑战成功需要计算打败百分比。
					if(parseInt($("#fenshu").html())>0)
					{
						//更新个人最高分数
						$.post(calculatedurl,{'fen':$("#fenshu").html()},function(res){
							if(res.status>0)
							{
								$("#jibai").html(res.jibai);
								$("#chenggong").show();
							}
							else
							{
								alert("计算打败比例失败！");
							}
						});
					}
					else
					{
						$("#jibai").html("0");
						$("#chenggong").show();
					}
				}
				else
				{
					$("#shibai").show();
				}
			}
		}
	});
}
//放入宝箱效果。
function fangbaoxiang()
{
	$("#huojiang").hide();
	//弹出输入信息页面。
	$("#tianxx").show();
}
//点击注册按钮提交信息的效果。
function submitxinxi()
{
	var name=$("#name").val();
	//获取手机号
	var phone=$("#phone").val();
	if(name=="")
	{
		alert("姓名不能为空！");return;
	}
	if(phone=="")
	{
		alert("手机号不能为空！");return;
	}
	if(phone.length!=11)
	{
		alert("请填写正确的手机号！");return;
	}
	//添加填写的信息。
	$.post(getPrizeurl,{"name":name,"mobile":phone},function(res){
		if(res.status>0)
		{
			$("#tianxx").hide();
			//如果配置有提示信息就弹出提示信息。
			if(tishiyu)
			{
				$("#tishixx").show();
				alert("领奖成功！");
			}
			//否则跳转到首页。
			else
			{
				alert("领奖成功！");
				window.location.href=indexurl;
			}
		}
		else alert(res.msg);
	});
}
var yaoci=0;
//打开摇一摇界面效果。
function yaojiang()
{
	$('#chenggong').hide();
	$('#yaojiang').show();
	if (window.DeviceMotionEvent){
		var speed = 20;
		var audio = document.getElementById("shakemusic");
		var openAudio = document.getElementById("openmusic");
		var x = t = z = lastX = lastY = lastZ = 0;
		window.addEventListener('devicemotion',
			function () {
				var acceleration = event.accelerationIncludingGravity;
				x = acceleration.x;
				y = acceleration.y;
				if (Math.abs(x - lastX) > speed || Math.abs(y - lastY) > speed) {
					yaoci++;
					audio.play();
					if(yaoci==1)
					{
						$('.red-ss').addClass('wobble')
						setTimeout(function(){
							audio.pause();
							openAudio.play();
							/*对中奖的判断和处理在这里进行*/
							$.post(getjiangurl,null,function(res){
								$('#yaojiang').hide();
								if(res.status>0)
								{
									$(".gift img").attr("src",res.jiang.thumb);
									$("#jiangtu").attr("src",res.jiang.thumb);
									$("#jiangbie").html(res.jiang.type);
									$("#jiangming").html(res.jiang.name);
									$(".tishi span").html(res.jiang.name);
									$('#huojiang').show();
								}
								else
								{
									$('#meizhong').show();
								}
							});
							/*处理结束*/
						}, 1500);
					}
				};
				lastX = x;
				lastY = y;
			},false);
	};
}
//跳转到指定url。
function tiaourl(url)
{
	window.location.href=url;
}
//显示灰色 jQuery 遮罩层 
function showBg(zheid,tanid,zheclose) {
	$("#"+zheid).css({
		display:"block" 
	});
	if(zheclose)
	{
		$("#"+zheid).click(function(){
			closeBg(zheid,tanid);
		})
	}
	else
	{
		$('#'+zheid).unbind("click");
	}
	$("#"+tanid).show(); 
} 
//关闭灰色 jQuery 遮罩 
function closeBg(zheid,tanid) {
	$("#"+zheid+",#"+tanid).hide(); 
}
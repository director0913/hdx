// JavaScript Document


var jinefw=getfanwei(jinefanwei);

zhatime=zhatime<=0?1:zhatime;

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
	init();
	//初始化红包滚动区域效果。
	//GunDongZY();   //生成红包图片放入指定位置。
	//touch.live(".zongdiv img", 'touchstart', function(ev){
	/*$(".zongdiv img").live("touchstart",function(){
	//$(".zongdiv img").click(function(){
		//$(this).live("touchstart",function(){return false;});
		//获取是红包还是炸弹
		if($(this).hasClass("hb"))
		{
			$(this).removeClass("hb");
			//document.getElementById("jinbimusic").play();
			//music.play();
			var baosui=parseInt($(this).attr("data-t"));
			$(this).attr("src","../addons/envelop/images/kongbai.png");
			//获取当前节点的位置
			//获取点击的位置
			var e=window.event;
			var x=e.touches[0].clientX;
			var y=e.touches[0].clientY;
			var fenshu=$('<span style="position:absolute; top:'+y+'px; left:'+x+'px; color:#FFF; font-size:50px; z-index:9;">+'+baosui+'</span>');
			$("body").append(fenshu);
			var zhongtop=parseInt(fenshu.css('top'))-120;
			fenshu.stop().animate({top:zhongtop+"px"},200);
			fenshu.fadeOut(100);
			$("#fenshu").html(parseInt($("#fenshu").html())+baosui);
		}
		else if($(this).hasClass("zd"))
		{
			$(this).removeClass("zd");
			//document.getElementById("dianzhadanmusic").play();
			//music.play();
			$(this).attr("src","../addons/envelop/images/baozha.png");
			//创建一个节点，然后移动和淡入。
			var jianshi=$('<span style="position:absolute; top:74px; right:190px; color:#FFF; font-size:60px;">-'+zhatime+'</span>');
			$("#alldiv").append(jianshi);
			jianshi.stop().animate({top:"20px"},200);
			jianshi.fadeOut(100);
			//时间-1秒
			//shitime-=zhatime;
			if(parseInt($("#fenshu").html())>=zhatime)
			{
				$("#fenshu").html(parseInt($("#fenshu").html())-zhatime);
			}
			else
			{
				$("#fenshu").html(0);
			}
		}
	});*/
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
						'<td>'+res[i].name+'</td>'+
						'<td>'+res[i].top_score+'分</td>'+
					'</tr>';
			index++;
		}
		$("#sort").append(datastr);
		page++;
	});
}
//关闭教程。
function closejiaocheng()
{
	closeBg("fullbg",'jiaocheng');
	gameRestart();
	//MyMar=setInterval(Marquee,speed);
	jitime=setInterval("daojishi()",20);
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
		clearInterval(MyMar);
		$(".myfen").html($("#fenshu").html());
		showBg("fullbg","daoshi");
		daoshistime=setTimeout("panjieguo()",1000);
	}
}
//判断抢红包结果，是否挑战成功。
function panjieguo()
{
	clearTimeout(daoshistime)    //销毁定时器。
	$("#daoshi").hide();
	if(parseInt($("#fenshu").html())>parseInt($("#zuigao").html()))
	{
		//更新个人最高分数
		var url=pjgurl;
		$.post(url,{'maxfen':$("#fenshu").html()},function(res){
			res =JSON.parse(res);
			if(res.status>0)
			{
				$("#jibai").html(res.jibai);
				if(parseInt($("#fenshu").html())>=chenggongfen)
				{
					$("#chenggong").show();
				}
				else
				{
					$("#shibai").show();
				}
			}
			else alert('更新个人最高分失败！');
		});
	}
	else
	{
		if(parseInt($("#fenshu").html())>=chenggongfen)
		{
			$("#chenggong").show();
		}
		else
		{
			$("#shibai").show();
		}
	}
}
//放入宝箱效果。
function fangbaoxiang()
{
	if(fangbx==0)
	{
		fangbx=1;
		var url=getzcurl;
		$.post(url,null,function(res){
			$('#yaojiang').hide();
			res =JSON.parse(res);
			if(res.status>0)
			{
				//放入宝箱，
				var url=fbxurl;
				$.post(url,null,function(res){
					var a=res;
					res =JSON.parse(res);
					if(res.status>0)
					{
						var baoxiangstr="";
						for(var i=0; i<res.baoxiang.length;i++)
						{
							baoxiangstr+='<div class="fendiv">'+
												'<div class="tudiv"><img src="'+res.baoxiang[i].prize_thumb+'"/></div>'+
												'<div class="youdiv">'+
													'<p class="jiangji">'+res.baoxiang[i].prize_type+'</p>'+
													'<p class="jiangname">'+res.baoxiang[i].prize_name+'</p>'+
												'</div>'+
												'<div class="c_b"></div>'+
											'</div>';
										
						}
						baoxiangstr='<p class="title">我的宝箱<img src="../addons/envelop/images/close.png" onclick="closeBg(\'fullbg2\',\'baoxiang\')"/></p><p class="defen">我的最高游戏得分<span>'+$("#zuigao").html()+'</span>分</p><div class="tanzhongdiv">'+baoxiangstr+'</div>';
						$("#baoxiang").html(baoxiangstr);
						showBg("fullbg2","fenxiang",true);
					}
					else alert(res.msg);
				});
				//显示宝箱。
			}
			else
			{
				//弹出注册层；
				showBg('fullbg2','tianxx')
			}
		});
	}
	else
	{
		showBg("fullbg2","baoxiang");
	}
}
//点击注册按钮提交信息的效果。
function zhuce()
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
	//异步注册
	var url=zhuceurl;
	$.post(url,{"name":name,"tel":phone},function(res){
		res =JSON.parse(res);
		if(res.status>0)
		{
			closeBg("tianxx");
			var baoxiangstr="";
			for(var i=0; i<res.baoxiang.length;i++)
			{
				baoxiangstr+='<div class="fendiv">'+
									'<div class="tudiv"><img src="'+res.baoxiang[i].prize_thumb+'"/></div>'+
									'<div class="youdiv">'+
										'<p class="jiangji">'+res.baoxiang[i].prize_type+'</p>'+
										'<p class="jiangname">'+res.baoxiang[i].prize_name+'</p>'+
									'</div>'+
									'<div class="c_b"></div>'+
								'</div>';
							
			}
			baoxiangstr='<p class="title">我的宝箱<img src="../addons/envelop/images/close.png" onclick="closeBg(\'fullbg2\',\'baoxiang\')"/></p><p class="defen">我的最高游戏得分<span>'+$("#zuigao").html()+'</span>分</p><div class="tanzhongdiv">'+baoxiangstr+'</div>';
			$("#baoxiang").html(baoxiangstr);
			showBg("fullbg2","fenxiang",true);
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
							var url=huojiangurl;
							$.post(url,null,function(res){
								$('#yaojiang').hide();
								res =JSON.parse(res);
								if(res.status>=0)
								{
									$("#jiangtu").attr("src",res.jiang.thume);
									$("#jiangbie").html(res.jiang.type);
									$("#jiangming").html(res.jiang.name);
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
//根据给出的范围字符串获取转换成整形后的值。返回一个数组。
function getfanwei(yfanwei)
{
	var xinfanwei=Array();
	var fenfanwei=yfanwei.split("、");
	for(var i=0;i<fenfanwei.length;i++)
	{
		xinfanwei[i]=parseInt(fenfanwei[i]);
	}
	if(xinfanwei.length<=0||(xinfanwei.length==1&&xinfanwei[0]==0))
	{
		xinfanwei=2;
	}
	if(xinfanwei.length>=2)
	{
		if(xinfanwei[0]>xinfanwei[1])
		{
			var ls=xinfanwei[0];
			xinfanwei[0]=xinfanwei[1];
			xinfanwei[1]=ls;
		}
	}
	return xinfanwei;
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


var blockSize=161, height=158, GameLayer = [], GameLayerBG, touchArea = [];
	var transform, transitionDuration;

	function init (argument) {
		GameLayerBG = document.getElementById( 'kuangdiv' );
		GameLayerBG.innerHTML=createGameLayer();
		transform = typeof(GameLayerBG.style.webkitTransform) != 'undefined' ? 'webkitTransform' : (typeof(GameLayerBG.style.msTransform) != 'undefined'?'msTransform':'transform');
		transitionDuration = transform.replace(/ransform/g, 'ransitionDuration');
		GameLayerBG.ontouchstart = gameTapEvent;
		if( GameLayerBG.ontouchstart === null ){
		}else{
			GameLayerBG.onmousedown = gameTapEvent;
		}
		//初始化必须的。
		GameLayer.push( document.getElementById('GameLayer1') );
		GameLayer[0].children = GameLayer[0].querySelectorAll('div');
		GameLayer.push( document.getElementById( 'GameLayer2' ) );
		GameLayer[1].children = GameLayer[1].querySelectorAll('div');
		//开始游戏
		//gameRestart();
	}
	function _refreshSize(){
		for( var i=0; i<GameLayer.length; i++ ){
			var box = GameLayer[i];
			for( var j=0; j<box.children.length; j++){
				var r = box.children[j],
					rstyle = r.style;
				rstyle.left = (j%4)*blockSize+'px';
				rstyle.bottom = Math.floor(j/4)*height+'px';
				rstyle.width = blockSize+'px';
				rstyle.height = height+'px';
			}
		}
		var f, a;
		if( GameLayer[0].y > GameLayer[1].y ){
			f = GameLayer[0];
			a = GameLayer[1];
		}else{
			f = GameLayer[1];
			a = GameLayer[0];
		}
		var y = ((_gameBBListIndex)%10)*height;
		f.y = y;
		f.style[transform] = 'translate3D(0,'+f.y+'px,0)';

		a.y = -height*Math.floor(f.children.length/4)+y;
		a.style[transform] = 'translate3D(0,'+a.y+'px,0)';

	}
	var _gameBBList = [], _gameBBListIndex = 0, _gameOver = false, _gameStart = false, _gameTime;
	function gameRestart(){
		console.log('gameRestart');
		_gameBBList = [];
		_gameBBListIndex = 0;
		_gameOver = false;
		_gameStart = false;
		refreshGameLayer(GameLayer[0]);
		refreshGameLayer(GameLayer[1], 1);
	}
	function gameStart(){
		_gameStart = true;
	}
	//游戏结束时执行的内容
	function gameOver(){
		_gameOver = true;
	}
	function refreshGameLayer( box, loop, offset ){
		var i = Math.floor(Math.random()*1000)%4+(loop?0:4);
		for( var j=0; j<box.children.length; j++){
			var r = box.children[j],
				rstyle = r.style;
			rstyle.left = (j%4)*blockSize+'px';
			rstyle.bottom = Math.floor(j/4)*height+'px';
			rstyle.width = blockSize+'px';
			rstyle.height = height+'px';
			if( i == j ){
				_gameBBList.push( {cell:i%4, id:r.id} );
				r.className = 'hb';
				if(jinefw.length==1)
					baosui=jinefw[0];
				else
				{
					//产生指定范围内的随机数；
					var suijijine=Math.floor(jinefw[0]+Math.random()*(jinefw[1]-jinefw[0]));
					baosui=suijijine;
				}
				r.setAttribute('data-t',baosui);
				r.notEmpty = true;
				i = ( Math.floor(j/4)+1)*4+Math.floor(Math.random()*1000 )%4;
			}else{
				r.className = 'zd';
				r.notEmpty = false;
			}
		}
		if( loop ){
			box.style.webkitTransitionDuration = '0ms';
			box.style.display          = 'none';
			box.y                      = -height*(Math.floor(box.children.length/4)+(offset||0))*loop;
			setTimeout(function(){
				box.style[transform] = 'translate3D(0,'+box.y+'px,0)';
				setTimeout( function(){
					box.style.display     = 'block';
				}, 100 );
			}, 200 );
		} else {
			box.y = 0;
			box.style[transform] = 'translate3D(0,'+box.y+'px,0)';
		}
		box.style[transitionDuration] = '150ms';
	}
	//图片的向下移动函数。
	function gameLayerMoveNextRow(){
		for(var i=0; i<GameLayer.length; i++){
			var g = GameLayer[i];
			g.y += height;
			if( g.y > height*(Math.floor(g.children.length/4)) ){
				refreshGameLayer(g, 1, -1);
			}else{
				g.style[transform] = 'translate3D(0,'+g.y+'px,0)';
			}
		}
	}
	//盒子的触摸事件
	function gameTapEvent(e){
		if (_gameOver) {
			return false;
		}
		var tar = e.target;
		//存放红包的数组。
		p = _gameBBList[_gameBBListIndex];
		//处理点到红包的操作。
		if( (p.id==tar.id&&tar.notEmpty)){
			if( !_gameStart ){
				gameStart();
			}
			tar = document.getElementById(p.id);
			
			var baosui=parseInt($(tar).attr("data-t"));
			//获取当前节点的位置
			//获取点击的位置
			var tach=e.targetTouches[0];
			var x=tach.clientX;
			var y=tach.clientY;
			var fenshu=$('<span style="position:absolute; top:'+y+'px; left:'+x+'px; color:#FFF; font-size:50px; z-index:9;">+'+baosui+'</span>');
			$("body").append(fenshu);
			var zhongtop=parseInt(fenshu.css('top'))-120;
			fenshu.stop().animate({top:zhongtop+"px"},200);
			fenshu.fadeOut(100);
			$("#fenshu").html(parseInt($("#fenshu").html())+baosui);
			
			tar.className ="";
			_gameBBListIndex++;
			gameLayerMoveNextRow();
		//处理点到其他的操作。
		}else if( _gameStart && !tar.notEmpty ){
			tar.className ="";
		}
		return false;
	}
	//创建红包和空白图片，必须。
	function createGameLayer(){
		var html = '';
		for(var i=1; i<=2; i++){
			var id = 'GameLayer'+i;
			html += '<div id="'+id+'" class="qiangquyu">';
			for(var j=0; j<10; j++ ){
				for(var k=0; k<4; k++){
					html += '<div id="'+id+'-'+(k+j*4)+'" num="'+(k+j*4)+'"></div>';
				}
			}
			html += '</div>';
		}
		return html;
	}
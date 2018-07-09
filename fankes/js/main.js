function isWeiXin() {
	var ua = window.navigator.userAgent.toLowerCase();
	console.log(ua);//mozilla/5.0 (iphone; cpu iphone os 9_1 like mac os x) applewebkit/601.1.46 (khtml, like gecko)version/9.0 mobile/13b143 safari/601.1
	if (ua.match(/MicroMessenger/i) == 'micromessenger') {
		return true;
	} else {
		return false;
	}
}

function dj(){
	if(isWeiXin()){
		console.log(" 是来自微信内置浏览器");
		alert('是来自微信内置浏览器');
//		$('#fenx').delegate('img','click',function(){
			$('.fenbg').show();
//		});
	
	
	}else{
		console.log("不是来自微信内置浏览器");
//		$('#fenx').delegate('img','click',function(){
			var nativeShare = new NativeShare();
		
			nativeShare.setShareData(shareData);
			nativeShare.call('wechatTimeline')
//		});
	}
}



var all;
$.ajax({
	type:"POST",
	url:"http://wdgame.itlanbao.com/index.php/Api/Problem/problems",//score
	dataType:'json',
	success:function(data){
		console.log(data);
		all = data.result;
	}
});

var time = 30;//答题总时间
time = time+1;
var begin = 0;//已答题时间
var wiper = 20;//进度条总个数
var ago;
var defen = 30;//坚持的时间
var current = 0;//当前题的下标
var right = 0;//答对题的数量
var ddgs = 0;//点点的个数
var txt = '';
var shareData ={};
var thisUrl = window.location.href;

//时间、百分比
function ui(){
	begin++;
	var per = (time-begin)/time;	
	var html='';
	html += '<div class="progress_tip">剩余<span id="sec" class="sec">'+ (time-begin) +'</span>秒</div>'
	for(var i=0;i<wiper;i++){
		if(i<=Math.round(per*wiper)-1){
			html += '<span class="progress_time on"></span>';
		}else{
			html += '<span class="progress_time"></span>'
		}
	}
	$('#progress_wrap').html(html);
	$('#won').html(right);
	if(time-begin<=0){
		jieshu();
	}
}




//等待页面
var load;
function loading(){
	load = setInterval(function(){
		if(ddgs==4){
			ddgs = 0;
		}
		ddgs++;
		var html = '上传中';
		for(var i=0;i<ddgs;i++){
			html+='.';
		}
		$('.bg_5').html(html);
	},300);
}




//答题
function reUI(){
//	console.log(all[current]);
	var title = all[current].title;
	$('#question').html(title);
	$('#choice').html(' ');
	var xuanzhe='';
	if(all[current].right=='A'){
		var aa = document.createElement('div');
		var bb = document.createElement('div');
		aa.className = 'list';
		aa.innerHTML = '<img id=idtrue src="http://oym3kqbov.bkt.clouddn.com/isCheck.jpg"/>'+all[current].answer1;
		bb.className = 'list';
		bb.innerHTML = '<img id=idfalse src="http://oym3kqbov.bkt.clouddn.com/isCheck.jpg"/>'+all[current].answer2;
		aa.addEventListener('touchstart',function(){
			tf(true)
		});
		bb.addEventListener('touchstart',function(){
			tf(false)
		});
		console.log(aa)
		console.log(bb)
		$('#choice').append(aa).append(bb);
		
//		xuanzhe+='<div class="list" ontouch="tf(true)"><img id=idtrue src="img/isCheck.jpg"/>'+all[current].answer1+'</div>';
//		xuanzhe+='<div class="list" ontouch="tf(false)"><img id=idfalse src="img/isCheck.jpg"/>'+all[current].answer2+'</div>';
	}else{
//		xuanzhe+='<div class="list" ontouch="tf(false)"><img id=idfalse src="img/isCheck.jpg"/>'+all[current].answer1+'</div>';
//		xuanzhe+='<div class="list" ontouch="tf(true)"><img id=idtrue src="img/isCheck.jpg"/>'+all[current].answer2+'</div>';
		var aa = document.createElement('div');
		var bb = document.createElement('div');
		aa.className = 'list';
		aa.innerHTML = '<img id=idfalse src="http://oym3kqbov.bkt.clouddn.com/isCheck.jpg"/>'+all[current].answer1;
		bb.className = 'list';
		bb.innerHTML = '<img id=idtrue src="http://oym3kqbov.bkt.clouddn.com/isCheck.jpg"/>'+all[current].answer2;
		aa.addEventListener('touchstart',function(){
			tf(false);
		});
		bb.addEventListener('touchstart',function(){
			tf(true);
		});
		console.log(aa)
		console.log(bb)
		$('#choice').append(aa).append(bb);
	}
//	$('#choice').html(xuanzhe);
}

//$('.list').bind('touchstart',function(){
//	console.log($(this).index())
//})


function tf(bol){
//	console.log(bol)
//	console.log($('#idtrue').html())
	if(current == all.length){
		jieshu();
	}
	if(bol){
		$("#idtrue").attr('src','http://oym3kqbov.bkt.clouddn.com/trueCheck.jpg');
		if(all[current].added == '0'){
			time+=2;//一般题+2
			defen+=2;
			jia2();
			trueMuisc();
		}else{
			time+=3;//暴击+3
			defen+=3;
			jia3();
			baojiMuisc();
		}
		
		current++;
		right++;
		var truetime=setInterval(function(){
			reUI();
			clearInterval(truetime);
		},200);
		
	}else{
		falseMuisc();
		$("#idfalse").attr('src','http://oym3kqbov.bkt.clouddn.com/falseCheck.jpg');
		time-=2;//答错减2秒
		jian2();
		current++;
		var truetime=setInterval(function(){
			reUI();
			clearInterval(truetime);
		},200);
	}
}

//关闭分享遮罩
function fenbg(){
	console.log('ddddd')
	document.getElementsByClassName('fenbg')[0].style.display = 'none';
}

		
//正确音乐
function trueMuisc(){
	document.getElementById('trueMusic').currentTime = 0;
	document.getElementById('trueMusic').play();
}

//正确音乐
function baojiMuisc(){
	document.getElementById('baojiMusic').currentTime = 0;
	document.getElementById('baojiMusic').play();
}

//错误音乐
function falseMuisc(){
	document.getElementById('falseMusic').currentTime = 0;
	document.getElementById('falseMusic').play();
}

//结束音乐
function endMuisc(){
	document.getElementById('endMusic').currentTime = 0;
	document.getElementById('endMusic').play();
}


//加减时间
function jia2(){
	$('.jia2').css({'transition':'.5s','opacity':'1','top':'7%'});
	var jia2 = setInterval(function(){
		$('.jia2').css({'transition':'none','opacity':'0','top':'10%'});
		clearInterval(jia2);
	},200);
}
function jian2(){
	$('.jian2').css({'transition':'.5s','opacity':'1','top':'7%'});
	var jian2 = setInterval(function(){
		$('.jian2').css({'transition':'none','opacity':'0','top':'10%'});
		clearInterval(jian2);
	},200);
	
}
function jia3(){
	$('.jia3').css({'transition':'.5s','opacity':'1','top':'7%'});
	var jia3 = setInterval(function(){
		$('.jia3').css({'transition':'none','opacity':'0','top':'10%'});
		clearInterval(jia3);
	},200);
}


function jieshu(){
	clearInterval(ago);//游戏结束
		$('.bg_3').hide();
		$('.bg_5').show();
		document.getElementById('bgMusic').pause();
		loading();
		endMuisc();
		$.ajax({
			type:"POST",
			url:"http://wdgame.itlanbao.com/index.php/Api/Problem/score",//score
			dataType:'json',
			data:{times:defen,rights:right},
			success:function(data){
				console.log(data);
				clearInterval(load);
				$('#bg_4').attr('src',data.imgs);
				var newimg = setInterval(function(){
					$('.bg_5').hide();
					$('.bg_4').show();
					clearInterval(newimg);
				},1000);
				txt = '新时代挑战，我坚持了'+ data.time +'秒，全国排名'+ data.pm +'，能考进'+ data.dx +'党校，获得成就'+ data.cj +',你呢？';
				shareData = {
				    title: txt,//分享的标题
				    link: thisUrl,
				    //desc: 'NativeShare是一个整合了各大移动端浏览器调用原生分享的插件',
				    // 如果是微信该link的域名必须要在微信后台配置的安全域名之内的。
				    //link: 'https://www.baidu.com',//分享的地址
				    
				    //分享的那个图片,绝对定位
				    icon: 'http://oym3kqbov.bkt.clouddn.com/icon.png',
				    
				    // 不要过于依赖以下两个回调，很多浏览器是不支持的
				    success: function() {
						//alert('success')
				    },
				    fail: function() {
						//alert('fail')
				    }
				}
			}
		});
}

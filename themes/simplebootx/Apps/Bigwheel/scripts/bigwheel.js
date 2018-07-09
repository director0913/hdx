// JavaScript Document

function check_status(){
	var url=checkstatusurl;
	$.post(url,null,function(res){
		res =JSON.parse(res);
		if(res.status==1)
		{
			isajax++;
			choujiang();
		}
		else
		{
			return tanmsg(res.msg);
			setTimeout("shua()",2000);
		}
	});
}

function choujiang()
{
	if(isajax!=2)
	{
		return false;
	}
	else
	{
		if(open_random!=0){
			var rand=prompt("请输入抽奖码","");
			if(""==rand){
				tanmsg("请输入抽奖码");
			}else{
				//校验输入的激活码是否正确
				var url1=checkmaurl1;
				$.post(url1,{'rand':rand},function(res){
					res =JSON.parse(res);
					if(res.status==1)
					{
						isajax++;
						//调用大转盘执行函数
						start_game();
					}
					else
					{
						tanmsg("抽奖码输入错误");
					}
				});
			}
		}else{
			//调用大转盘执行函数
			start_game();
		}
	}
}

	var lottery = {
		index: 0, //当前转动到哪个位置，起点位置
		count: 0, //总共有多少个位置
		timer: 0, //setTimeout的ID，用clearTimeout清除
		speed: 20, //初始转动速度
		times: 0, //转动次数
		cycle: 50, //转动基本次数：即至少需要转动多少次再进入抽奖环节
		prize: 0, //中奖位置
		init: function(id) {
			$lottery = $("#" + id);
			//$units = $lottery.find(".lottery-unit");
			this.obj = $lottery;
			this.count = 8;
			$lottery.find(".tu" + this.index).addClass("selectjiang");
		},
		roll: function() {
			var index = this.index;
			var count = this.count;
			var lottery = this.obj;
			$(lottery).find(".tu" + index).removeClass("selectjiang");
			index += 1;
			if (index > count - 1) {
				index = 0;
			}
			$(lottery).find(".tu" + index).addClass("selectjiang");
			this.index = index;
			return false;
		},
		stop: function(index) {
			this.prize = index;
			return false;
		}
	};

	function roll() {
		lottery.times += 1;
		lottery.roll();
		var prize_site = $("#chjtdiv").attr("prize_site");
		if (lottery.times > lottery.cycle + 10 && lottery.index == prize_site) {
			var prize_id = $("#chjtdiv").attr("prize_id");
			var prize_name = $("#chjtdiv").attr("prize_name");
			//alert("前端中奖位置："+prize_site+"\n"+"中奖名称："+prize_name+"\n中奖id："+prize_id)
			clearTimeout(lottery.timer);
			
			lottery.prize = -1;
			lottery.times = 0;
			click = false;
			if(prize_site!=2&&prize_site!=5)
			{
				setTimeout("showBg('fullbg','tanjiang',false)",1000);
			}
			else
			{
				if(parseInt($("#keyong1").html())>0)
				{
					setTimeout("showBg('fullbg','wujiang',false)",1500);
				}
				else
				{
					tanmsg("亲！很遗憾没有中奖，您当天的抽奖次数已用完，明天再来吧。");
				}
			}

		} else {
			if (lottery.times < lottery.cycle) {
				lottery.speed -= 10;
			} else if (lottery.times == lottery.cycle) {
				var index = Math.random() * (lottery.count) | 0;
				lottery.prize = index;
			} else {
				if (lottery.times > lottery.cycle + 10 && ((lottery.prize == 0 && lottery.index == 7) || lottery.prize == lottery.index + 1)) {
					lottery.speed += 110;
				} else {
					lottery.speed += 20;
				}
			}
			if (lottery.speed < 40) {
				lottery.speed = 40;
			}
			lottery.timer = setTimeout(roll, lottery.speed);
		}
		return false;
	}

	var click = false;
function start_game()
{
	lottery.speed = 100;
	var url=getjiangurl;
	$.post(url,null, function(data) { //获取奖品，也可以在这里判断是否登陆状态
	//alert("随机数是："+data.suiji+";原奖是："+data.yuanzhi+";新奖是："+data.jiangzhi+";停止位置是："+data.status);
		$("#chjtdiv").attr("prize_site", data.status);
		$("#keyong1").html(parseInt($("#keyong1").html())-1);
		roll();
		click = true;
		isajax=1;
		return false;
	}, "json")
}
	$(function() {
		lottery.init('chjtdiv');
		$("#choudiv").click(function() {
			 if (click) {
				return false;
			} else {
				check_status();
			}
		});
	})
var selejiang=null;
var seleindex=0;
var ci=0;
var zhi=1;
var jiange=0;
var jitime=null;
var is_formal = 1;
//点击领取奖品
function lingqu()
{
	//异步判断用户是否已经注册
	var url=checkzhuceurl;
	$.post(url,null,function(res){
		res =JSON.parse(res);
		if(res.status<=0)
		{
			//隐藏中奖页面。
			$("#tanjiang").hide();
			//显示信息页面。
			$("#tianxx").show();
		}
		else
		{
			var url=insertjiangurl;
			$.post(url,null,function(res){
				res =JSON.parse(res);
				if(res.status>0)
				{
					closeBg('fullbg',"tanjiang");
					window.location.href=lingqutiaourl;
				}
				else alert(res.msg);
			});
		}
	});
}

function tanmsg(msg)
{
	$("#msgp").html(msg);
	showBg('fullbg',"msg",false);
}
function regist()
{
	var name=$("#name").val();
	var tel=$("#tel").val();
	if(name=="")
	{
		alert("姓名不能为空");return;
	}
	if(tel=="")
	{
		alert("手机号不能为空");return;
	}
	var url=zhuceurl;
	$.post(url,{"name":name,"tel":tel},function(res){
		res =JSON.parse(res);
		if(res.status>0)
		{
			closeBg('fullbg',"tianxx");
			window.location.href=lingqutiaourl;
			//window.location.href=res.url;
		}
		else tanmsg(res.msg);
	});
}
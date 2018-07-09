// JavaScript Document

var is_vote = false;
var curr_vote_id = 0;
//增加投票
function addvote(vote_id){
	//处理投票
	curr_vote_id = vote_id;
	showBg('fullbg','yanzheng');
}
//处理投票
function deal_vote(){
	var yzm = $("#yzm").val();

	if(!yzm){
		alert("请输入验证码");return false;
	}
	closeBg('fullbg','yanzheng');
	var url = toupiaourl;
	if(!is_vote){
		is_vote = true;
		$.post(url,{vote_id:curr_vote_id,yzm:yzm},function(res){
			//alert(res);return;
			res = $.parseJSON(res);
			//return;
			if(res.status>0){
				myhelp=1;
				is_vote = false;
				$("#piao").html(parseInt($("#piao").html())+1);
				if(titext=="1")
				{
					update();
				}
				else
				{
					$("#jiangtishi").html("投票成功");
					showBg('fullbg','wujiang',false);
				}
			}else if(res.status==-1){
				window.location.href=res.msg;
			}else{
				is_vote = false;
				$("#jiangtishi").html(res.msg);
				showBg('fullbg','wujiang',false);
			}
		});
	}
}
//预览图片效果
var img_list = $('.tupian img');
	var img_array = new Array();
	for(var i=0;i<img_list.length;i++){
		var tt = img_list[i].src;
		img_array.push(tt);
	}
	$('.tupian img').click(function(e){
		test_preview($(this).attr('src'),img_array);
	});
function test_preview(dqimg,imgarray){
	wx.previewImage({
		current: dqimg,
		urls :  imgarray
	 });
}
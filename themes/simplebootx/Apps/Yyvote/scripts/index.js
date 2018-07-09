// JavaScript Document

function search(){
	var keyword = $('#keyword').val();
	if(!keyword){
		alert("请输入姓名或编号！");
		return false;
	}
}
var is_vote = false;
//增加投票
function addvote(vote_id){
	//处理投票
	var url = toupiaourl;
	if(!is_vote){
		is_vote = true;
		$.post(url,{vote_id:vote_id},function(res){
			res = $.parseJSON(res);
			if(res.status>0){
				myhelp=1;
				is_vote = false;
				$(".huanum"+vote_id).html(parseInt($(".huanum"+vote_id).html())+1);
				if(titext=="1")
				{
					update();
				}
				else
				{
					$("#jiangtishi").html("投票成功");
					showBg('fullbg','wujiang');
				}
				//alert(titext+'成功！');
			}else if(res.status==-1)
			{
				window.location.href=res.msg;
			}else
			{
				is_vote = false;
				$("#jiangtishi").html(res.msg);
				showBg('fullbg','wujiang');
			}
		});
	}
}
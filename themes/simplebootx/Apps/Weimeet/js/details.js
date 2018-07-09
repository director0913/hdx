// JavaScript Document

function baoming()
{
	showBg("fullbg","baoming",false);
}
//展示报名者信息接口
function showJoin(that){
	if(is_self==0) {
		return false;
	}else{
		var tel = $(that).attr('data-tel');
		var avatar = $(that).attr('data-avatar');
		var name = $(that).attr('data-name');
		$(".xinxi").text(name);
		$(".haoma").text(tel);
		$(".touxiang").attr('src',avatar);
		$(".tel_href").attr('href',"tel:"+tel);
		showBg('fullbg','xinxi',false);
	}
	
}
//测试报名端口
function join(){
	var name=$('#name').val();
	var tel=$('#tel').val();
	var join_total = $('#join_total').val();
	var school = $("#school").val();
	var area = $("#area").val();
	var age=$("#age").val();
	if(!name){alert("请输入姓名");return false;}
	if(!tel){alert("请输入手机号");return false;}
	if(tel.length!=11){alert("请输入11位手机号");return false;}
	if(submitType==0)
	{
		if(!school){alert("请输入学校");return false;}
		if(!area){alert("请输入地区");return false;}
		if(join_total<=0){alert("报名人数至少为1个");return false;}
	}
	else
	{
		if(!age){alert("请输入年龄");return false;}
	}
	var flag = 1;
	if(flag){
		flag =0;
		$.post(invite_url,{school:school,area:area,name:name,tel:tel,join_total:join_total},function(res){
			res =JSON.parse(res);
			if(res.status==1)
			{
				//将评论信息添加到评论列表之中
				var str =
					'<div onclick="return showJoin(this)" data-name="'+name+'" data-tel="'+tel+'" data-avatar="'+res.avatar+'">'+
						'<img src="'+res.avatar+'" />'+
						'<p >'+name+'</p>'+
					'</div>';
				$('.baomingls').prepend(str);

				var join_total = parseInt($('.join_total').text());
				$('.join_total').text(join_total+1)
				closeBg('fullbg','baoming');
				alert("报名成功！")
				//将报名者信息添加到列表之中
				return false;
			}else{
				alert(res.msg);return false;
			}
		});
	}
}
//测试评论端口
function comment(){
	var comment = $('#comment').val();
	if(!comment){alert("请输入您想说的话");return false;}
	var flag = 1;
	if(flag){
		flag =0;
		$.post(comment_url,{comment:comment},function(res){
			res =JSON.parse(res);
			if(res.status==1)
			{
				//将评论信息添加到评论列表之中
				var str ='<li>'+
						'<img src="'+res.avatar+'"  />'+
						'<div class="o_h">'+
							'<p class="xinxi">'+res.name+'<span>刚刚</span></p>'+
							'<p class="neirong">'+comment+'</p>'+
						'</div>'+
						'</li>';
				$('.comment').prepend(str);
				closeBg('fullbg','pinglunqu');return false;
			}else{
				alert(res.msg);return false;
			}
		});
	}
}
function test_preview(dqimg,imgarray){
	wx.previewImage({
		current: dqimg,
		urls :  imgarray
	 });
}
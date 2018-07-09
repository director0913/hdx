// JavaScript Document

//报名数据提交
function submit_join(){
	var name = $('#name').val();
	var tel = $('#tel').val();
	var des = $('#des').val();
	if(!name){alert('请填写姓名！');return;}
	if(!tel){alert('请输入手机号！');return;}
	//if(!des){alert('请输入简单的描述！');return;}
	if(chuan==1){alert("请选择要上传的照片");return;}
	if(chuan==2){alert("您的照片正在上传中，请耐心等待！");return;}
	if(imgurlarr.length<1) {alert('请选择照片！');return;}
	var imgls=new Array(),minimgls=new Array();
	for(var i=0;i<imgurlarr.length;i++)
	{
		imgls[i]=imgurlarr[i][0];
		minimgls[i]=imgurlarr[i][1];
	}
	var url = addJoinurl;
	$.post(url,{name:name,tel:tel,des:des,thumb:imgls.toString(),minthumb:minimgls.toString()},function(res){
		res = $.parseJSON(res);
		if(res.status){
			window.location.href=res.redirect;
		}else{
			alert(res.msg);
		}
	});
}
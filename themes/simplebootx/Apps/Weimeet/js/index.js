// JavaScript Document
//邀约端口函数
var images = {
    localId: [],
    serverId: []
};
function invite(){
	var name=$('#name').val();
	var tel=$('#phone').val();
	var wx_number=$('#wx_number').val();
	var resource_id = ""+$('#resource_id').val()+"";
	if(!name){alert("请输入姓名");return false;}
	if(!tel){alert("请输入11位手机号");return false;}
	if(tel.length!=11){alert("请输入11位手机号");return false;}
	//alert(resource_id);
	if(!wx_number){alert("请输入微信号");return false;}
	$.post(invite_url,{sid:sid,name:name,tel:tel,wx_number:wx_number,resource_id:resource_id},function(res){
		res =JSON.parse(res);
		//alert(res.status);
		if(res.status==1)
		{
			var redirect = shareurl+"&uid="+res.uid;
			window.location.href=redirect;
		}else{
			alert(res.msg);return false;
		}
	});
}
function test_preview(){
	wx.previewImage({
		current: toptu,
		urls :  [toptu]
	 });
}
//上传二维码
function upload_qrcode(){
  wx.chooseImage({
		count: 2, // 默认9
		sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
		sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
		success: function (res) {
			var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			images.localId = res.localIds;
			upload_img(localIds);
		}
   });
}
function upload_img(localId){
  wx.uploadImage({
    localId: images.localId[0], // 需要上传的图片的本地ID，由chooseImage接口获得
    //isShowProgressTips: 1, // 默认为1，显示进度提示
    success: function (res) {
		$('#resource_id').val(res.serverId);  
		$('#qrcode').show();
    },
	fail: function (res) {
	  alert(JSON.stringify(res));
	}
  });
}
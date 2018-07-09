// JavaScript Document

//显示灰色 jQuery 遮罩层 
function showBg(zheid,tanid,zheclose) {
	$("#"+zheid).css({
		display:"block" 
	});
	if(zheclose)
	{
		$("#"+zheid).click(function(){
			closeBg(zheid,tanid)
		})
	}
	else
	{
		$("#"+zheid).unbind("click");
	}
	$("#"+tanid).show(); 
} 
//关闭灰色 jQuery 遮罩 
function closeBg(zheid,tanid) {
	$("#"+zheid+",#"+tanid).hide(); 
}
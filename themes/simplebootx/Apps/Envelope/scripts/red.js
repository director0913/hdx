
/////////////////////////////////////////////////////

//					web·唐明明 			           //

/////////////////////////////////////////////////////

var yaoci=0;
$(document).ready(function() {
	if (window.DeviceMotionEvent){
		var speed = 25;
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
					if(yaoci==5)
					{
						$('.red-ss').addClass('wobble')
						setTimeout(function(){
							audio.pause();
							openAudio.play();
							/*对中奖的判断和处理在这里进行*/
							var url='{php echo $this->createMobileUrl("getjiang",array("id"=>$id))}';
	$.post(url,null,function(res){
		alert(res);
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
});






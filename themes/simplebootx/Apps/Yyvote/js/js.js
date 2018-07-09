//获取画布对象
canvas = document.getElementById("canvas");
//获取画布返回的绘图环境。
context = canvas.getContext("2d");
//定义宽度为屏幕的宽度
width = canvas.width = window.innerWidth;
//定义高度为屏幕的高度。
height = canvas.height = window.innerHeight;


//定义数组存放色块对象。
particle = [];
//定义变量色块对象的元素个数初始为0。
particleCount = 0,
//色块的颜色范围选项。
colors = [
	'#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5',
	'#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4CAF50',
	'#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107', '#FF9800',
	'#FF5722', '#795548'
];
img= loadImage("../addons/yyvote/images/hua.png");
chgimg=loadImage("../addons/yyvote/images/songcheng.png");





function drawScreen() {
    //画布的透明度为1。
	context.globalAlpha = 1;
	//开始进行色块的处理。
    for (var i = 0; i < particle.length; i++) {
		//执行每个色块的处理函数。
        particle[i].draw();
    }
}

function loadImage(url) {
    var img = document.createElement("img");
    img.src = url;
    return img;
}
function update()
{
	particle = [];
	//循环300次创造300个色块对象放入色块数组中。
	for (var i = 0; i < 50; i++) {

		particle.push({
			x: width / 2,
			y: height / 2,
			boxW: randomRange(5, 20),
			boxH: randomRange(5, 20),
			size: randomRange(2, 8),
	
			spikeran: randomRange(3, 5),
	
			velX: randomRange(-8, 8),
			velY: randomRange(-50, -10),
	
			angle: convertToRadians(randomRange(0, 360)),
			color: colors[Math.floor(Math.random() * colors.length)],
			anglespin: randomRange(-0.2, 0.2),
	
			draw: function() {
	
	
				//保存画布上的当前效果。
				context.save();
				//重新定位画布的0，0位置为指定的坐标。
				context.translate(this.x, this.y);
				//将画布旋转指定的度数。
				context.rotate(this.angle);
				//定义或设置矩形中要填充的颜色。
				context.fillStyle = this.color;
				//开始一条新的路径或重置当前路径。
				context.beginPath();
				context.drawImage(img, this.boxW / 2 * -1, this.boxH / 2 * -1);
				//画一个矩形并指定大小和位置。
				//context.fillRect(this.boxW / 2 * -1, this.boxH / 2 * -1, this.boxW, this.boxH);
				//填充指定的矩形。
				//context.fill();
				//关闭路径。
				context.closePath();
				//把最后一个状态弹出去。
				context.restore();
				this.angle += this.anglespin;
				this.velY *= 0.999;
				this.velY += 0.3;
	
				this.x += this.velX;
				this.y += this.velY;
				if (this.y < 0) {
					this.velY *= -0.2;
					this.velX *= 0.9;
				};
				if (this.y > height) {
					this.anglespin = 0;
					this.y = height;
					this.velY *= -0.2;
					this.velX *= 0.9;
				};
				if (this.x > width || this.x < 0) {
	
					this.velX *= -0.5;
				};
			},
		});
	}
    //清除指定的矩形范围，并用透明的颜色填充。
	context.clearRect(0, 0, width, height);
	context.fillStyle="rgba(0,0,0,0.8)";
	canvas.style.display="block";
	kaishi();
}
//开始执行
function kaishi() {
	context.clearRect(0, 0, width, height);
	context.fillRect(0,0,width,height);
	context.drawImage(chgimg, width/2-298, height/2-108);
    drawScreen();
	var a=0;
	for(var i=0;i<particle.length;i++)
	{
		if(particle[i].y<height)
		{
			a=1;break;
		}
	}
	if(a==1)
	{
    	requestAnimationFrame(kaishi);
	}
	else
	{
		canvas.style.display="none";
	}
}
//开始执行。
//update();


function randomRange(min, max) {
    return min + Math.random() * (max - min);
}

function randomInt(min, max) {
    return Math.floor(min + Math.random() * (max - min + 1));
}

function convertToRadians(degree) {
    return degree * (Math.PI / 180);
}

function drawStar(cx, cy, spikes, outerRadius, innerRadius, color) {
    var rot = Math.PI / 2 * 3;
    var x = cx;
    var y = cy;
    var step = Math.PI / spikes;

    context.strokeSyle = "#000";
    context.beginPath();
    context.moveTo(cx, cy - outerRadius)
    for (i = 0; i < spikes; i++) {
        x = cx + Math.cos(rot) * outerRadius;
        y = cy + Math.sin(rot) * outerRadius;
        context.lineTo(x, y)
        rot += step

        x = cx + Math.cos(rot) * innerRadius;
        y = cy + Math.sin(rot) * innerRadius;
        context.lineTo(x, y)
        rot += step
    }
    context.lineTo(cx, cy - outerRadius)
    context.closePath();
    context.fillStyle = color;
    context.fill();

}
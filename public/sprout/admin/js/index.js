window.onload =function(){

//获取上传按钮
    var input1 = document.getElementById("upload");

    if(typeof FileReader==='undefined'){
        //result.innerHTML = "抱歉，你的浏览器不支持 FileReader";
        input1.setAttribute('disabled','disabled');
    }else{
        input1.addEventListener('change',readFile,false);
        input1.addEventListener('change',function (e){
         console.log(this.files);//上传的文件列表
         },false);
    }
    function readFile(){
        var file = this.files[0];//获取上传文件列表中第一个文件
        if(!/image\/\w+/.test(file.type)){
            //图片文件的type值为image/png或image/jpg
            alert("文件必须为图片！");
            return false;
        }
        // console.log(file);
        var reader = new FileReader();//实例一个文件对象
        reader.readAsDataURL(file);//把上传的文件转换成url
        //当文件读取成功便可以调取上传的接口
        reader.onload = function(e){
            // console.log(this.result);//文件路径
            // console.log(e.target.result);//文件路径
            var data = e.target.result.split(',');
             var tp = (file.type == 'image/png')? 'png': 'jpg';
             var imgUrl = data[1];//图片的url，去掉(data:image/png;base64,)
             //需要上传到服务器的在这里可以进行ajax请求
             console.log(imgUrl);
             // 创建一个 Image 对象
             var image = new Image();
             // 创建一个 Image 对象
             image.src = imgUrl;
             image.src = e.target.result;
             image.onload = function(){
             document.body.appendChild(image);
             }

            var image = new Image();
            // 设置src属性
            image.src = e.target.result;
            var max=200;
            // 绑定load事件处理器，加载完成后执行，避免同步问题
            image.onload = function(){
                // 获取 canvas DOM 对象
                var canvas = document.getElementById("cvs");
                // 如果高度超标 宽度等比例缩放 *=
                if(image.height > max) {
                 image.width *= max / image.height;
                 image.height = max;
                 }
                // 获取 canvas的 2d 环境对象,
                var ctx = canvas.getContext("2d");
                // canvas清屏
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                // 重置canvas宽高
                /*canvas.width = image.width;
                 canvas.height = image.height;
                 if (canvas.width>max) {canvas.width = max;}*/
                // 将图像绘制到canvas上
                // ctx.drawImage(image, 0, 0, image.width, image.height);
                ctx.drawImage(image, 0, 0, 200, 200);
                // 注意，此时image没有加入到dom之中
            };
        }
    };

    $(function(){
        $("#yincag").click(function(){
            $("#qiygw").hide()
        });
        $("#yincag1").click(function(){
            $("#qiygw").show()
        });
        $("#yincag2").click(function(){
            $("#qiygw").show()
            $(".qiygw2").hide()
            $(".qiygw3").show()
        });


        $("#fold-button").click(function(){
            $(".fold-box").slideToggle("slow");
            $("#Wu-Bot1").slideToggle("slow");
            $("#open").toggle();
            $("#close").toggle();
        });
        $("#dakais").click(function(){
            $(".fold-box").slideToggle("slow");
            $("#Wu-Bot").slideToggle("slow");
            $("#dakai").toggle();
            $("#dakai1").toggle();
        });
        $("#fold-button1").click(function(){
            $(".fold-box").slideToggle("slow");
            $("#Wu-Bot1").slideToggle("slow");
            $("#opoen").toggle();
            $("#cloose").toggle();
        });


        $("#Wu-ji").click(function(){
            $(".Wu-set1").show().siblings(".Wu-set2").hide()
            $(".Wu-set1").show().siblings(".Wu-set3").hide()
            $(".Wu-set1").show().siblings(".Wu-set4").hide()
            $("#Wu-ji").css({"background":"#3896dc","color":"#fff"}).siblings().css({"background":"#fff","color":"#585858"})
        })
        $("#Wu-bao").click(function(){
            $(".Wu-set2").show().siblings(".Wu-set1").hide()
            $(".Wu-set2").show().siblings(".Wu-set3").hide()
            $(".Wu-set2").show().siblings(".Wu-set4").hide()
            $("#Wu-bao").css({"background":"#3896dc","color":"#fff"}).siblings().css({"background":"#fff","color":"#585858"})
        })
        $("#Wu-tou").click(function(){
            $(".Wu-set3").show().siblings(".Wu-set1").hide()
            $(".Wu-set3").show().siblings(".Wu-set2").hide()
            $(".Wu-set3").show().siblings(".Wu-set4").hide()
            $("#Wu-tou").css({"background":"#3896dc","color":"#fff"}).siblings().css({"background":"#fff","color":"#585858"})
        })
        $("#Wu-gao").click(function(){
            $(".Wu-set4").show().siblings(".Wu-set1").hide()
            $(".Wu-set4").show().siblings(".Wu-set2").hide()
            $(".Wu-set4").show().siblings(".Wu-set3").hide()
            $("#Wu-gao").css({"background":"#3896dc","color":"#fff"}).siblings().css({"background":"#fff","color":"#585858"})
        })
        $(".Wu-in1").click(function(){
            $("Wu-twoBot").show()
        })
        $(".Wu-in2").click(function(){
            $(".Wu-twoBot").hide()
        });




    });
}
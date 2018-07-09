window.onload = function(){
    var label1 = document.getElementById("label1");
    var label2 = document.getElementById("label2");
    var label3 = document.getElementById("label3");
    var label4 = document.getElementById("label4");
    var label5 = document.getElementById("label5");
    var kx_first = document.getElementById("kx_first");
    var kx_top = document.getElementById("kx_top");
    var kx_intruduce = document.getElementById("kx_intruduce");
    var kx_enroll = document.getElementById("kx_enroll");
    var kx_detail = document.getElementById("kx_detail");
    var kx_pall = document.getElementById("kx_pall");
    var kx_nav_bottom = document.getElementById("kx_nav_bottom");
    var l1 = document.getElementsByClassName("label1")[0];
    var kx_label1 = document.getElementsByClassName("kx_label1")[0];
    var li = kx_label1.getElementsByTagName("li");
    var p = kx_nav_bottom.getElementsByTagName("p");
    var sy = document.getElementById("sy");
    var ph = document.getElementById("ph");
    var sm = document.getElementById("sm");
    var gz = document.getElementById("gz");
    var kx_l1 = document.getElementsByClassName("kx_l1")[0];
    var kx_l2 = document.getElementsByClassName("kx_l2")[0];
    var kx_l3 = document.getElementsByClassName("kx_l3")[0];
    var kx_l4 = document.getElementsByClassName("kx_l4")[0];
    console.log(kx_intruduce)

    
    label1.onclick = function(){
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label1.className = "kx_under_line";
        kx_first.style.display = "block";
        kx_top.style.display = "none";
        kx_intruduce.style.display = "none";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "block";
        for(var i=0;i<p.length;i++){
            p[i].className = " ";
            p[0].className = "kx_blue"
        }
        kx_l1.className = "kx_l1"
        kx_l2.className = "kx_l2"
        kx_l3.className = "kx_l3"
        kx_l4.className = "kx_l4"
    }
    label2.onclick = function(){
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label2.className = "kx_under_line";
        kx_first.style.display = "none";
        kx_top.style.display = "none";
        kx_intruduce.style.display = "none";
        kx_enroll.style.display = "block";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "none";
    }
    label3.onclick = function(){
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label3.className = "kx_under_line";
        kx_first.style.display = "none";
        kx_top.style.display = "none";
        kx_intruduce.style.display = "none";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "block";
        kx_pall.style.display = "block";
        kx_nav_bottom.style.display = "none";
    }
    label4.onclick = function(){
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label4.className = "kx_under_line";
        kx_first.style.display = "none";
        kx_top.style.display = "block";
        kx_intruduce.style.display = "none";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "block";
        for(var i=0;i<p.length;i++){
            p[i].className = " ";
            p[1].className = "kx_blue"
        }
        kx_l1.className = "kx_l5"
        kx_l2.className = "kx_l6"
        kx_l3.className = "kx_l3"
        kx_l4.className = "kx_l4"
    }
    label5.onclick = function(){
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label5.className = "kx_under_line";
        kx_first.style.display = "none";
        kx_top.style.display = "none";
        kx_intruduce.style.display = "block";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "block";
        for(var i=0;i<p.length;i++){
            p[i].className = " ";
            p[2].className = "kx_blue"
        }
        kx_l1.className = "kx_l5"
        kx_l2.className = "kx_l2"
        kx_l3.className = "kx_l7"
        kx_l4.className = "kx_l4"
    }
    sy.onclick = function(){
        for(var i=0;i<p.length;i++){
            p[i].className = " ";
            p[0].className = "kx_blue"
        }
        kx_l1.className = "kx_l1"
        kx_l2.className = "kx_l2"
        kx_l3.className = "kx_l3"
        kx_l4.className = "kx_l4"
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label1.className = "kx_under_line";
        kx_first.style.display = "block";
        kx_top.style.display = "none";
        kx_intruduce.style.display = "none";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "block";
    }
    ph.onclick = function(){
        for(var i=0;i<p.length;i++){
            p[i].className = " ";
            p[1].className = "kx_blue"
        }
        kx_l1.className = "kx_l5"
        kx_l2.className = "kx_l6"
        kx_l3.className = "kx_l3"
        kx_l4.className = "kx_l4"
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label4.className = "kx_under_line";
        kx_first.style.display = "none";
        kx_top.style.display = "block";
        kx_intruduce.style.display = "none";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "block";
    }
    sm.onclick = function(){
        for(var i=0;i<p.length;i++){
            p[i].className = " ";
            p[2].className = "kx_blue"
        }
        kx_l1.className = "kx_l5"
        kx_l2.className = "kx_l2"
        kx_l3.className = "kx_l7"
        kx_l4.className = "kx_l4"
        for(var i=0;i<li.length;i++){
            li[i].className = " "
        };
        label5.className = "kx_under_line";
        kx_first.style.display = "none";
        kx_top.style.display = "none";
        kx_intruduce.style.display = "block";
        kx_enroll.style.display = "none";
        kx_detail.style.display = "none";
        kx_pall.style.display = "none";
        kx_nav_bottom.style.display = "block";
    }



    var endDate = new Date("2018/7/6 00:00:00");
    var day = document.getElementsByClassName("day")[0];
    var hour = document.getElementsByClassName("hour")[0];
    var minute = document.getElementsByClassName("minute")[0];
    var second = document.getElementsByClassName("second")[0];
    var day1 = document.getElementsByClassName("day")[1];
    var hour1 = document.getElementsByClassName("hour")[1];
    var minute1 = document.getElementsByClassName("minute")[1];
    var second1 = document.getElementsByClassName("second")[1];
    var day2 = document.getElementsByClassName("day")[2];
    var hour2 = document.getElementsByClassName("hour")[2];
    var minute2 = document.getElementsByClassName("minute")[2];
    var second2 = document.getElementsByClassName("second")[2];
    // console.log(hour)
    setInterval(autoplay,1000);

    function autoplay(){
        var date = new Date();
        var ms = endDate.getTime() - date.getTime();
        // console.log(ms)
        var time = parseInt(ms / 1000);
        var d = parseInt(time / 3600 / 24); 
        var h = parseInt(time / 3600 % 24);
        var m = parseInt(time / 60 % 60);
        var s = parseInt(time % 60)
         //console.log(h)
         //console.log(m)
         //console.log(s)
        day.innerHTML= d; 
        hour.innerHTML = h;
        minute.innerHTML = m;
        day1.innerHTML= d; 
        hour1.innerHTML = h;
        minute1.innerHTML = m;
        day2.innerHTML= d; 
        hour2.innerHTML = h;
        minute2.innerHTML = m;
    }





    var input1 = document.getElementById("upload");

    if(typeof FileReader==='undefined'){
        //result.innerHTML = "��Ǹ������������֧�� FileReader";
        input1.setAttribute('disabled','disabled');
    }else{
        input1.addEventListener('change',readFile,false);
        input1.addEventListener('change',function (e){
         console.log(this.files);//�ϴ����ļ��б�
         },false);
    }
    function readFile(){
        var file = this.files[0];//��ȡ�ϴ��ļ��б��е�һ���ļ�
        if(!/image\/\w+/.test(file.type)){
            //ͼƬ�ļ���typeֵΪimage/png��image/jpg
            alert("�ļ�����ΪͼƬ��");
            return false;
        }
        // console.log(file);
        var reader = new FileReader();//ʵ��һ���ļ�����
        reader.readAsDataURL(file);//���ϴ����ļ�ת����url
        //���ļ���ȡ�ɹ�����Ե�ȡ�ϴ��Ľӿ�
        reader.onload = function(e){
            // console.log(this.result);//�ļ�·��
            // console.log(e.target.result);//�ļ�·��
            var data = e.target.result.split(',');
             var tp = (file.type == 'image/png')? 'png': 'jpg';
             var imgUrl = data[1];//ͼƬ��url��ȥ��(data:image/png;base64,)
             //��Ҫ�ϴ�������������������Խ���ajax����
             console.log(imgUrl);
             // ����һ�� Image ����
             var image = new Image();
             // ����һ�� Image ����
             image.src = imgUrl;
             image.src = e.target.result;
             image.onload = function(){
             document.body.appendChild(image);
             }

            var image = new Image();
            // ����src����
            image.src = e.target.result;
            var max=200;
            // ��load�¼���������������ɺ�ִ�У�����ͬ������
            image.onload = function(){
                // ��ȡ canvas DOM ����
                var canvas = document.getElementById("cvs");
                // ����߶ȳ��� ��ȵȱ������� *=
                if(image.height > max) {
                 image.width *= max / image.height;
                 image.height = max;
                 }
                // ��ȡ canvas�� 2d ��������,
                var ctx = canvas.getContext("2d");
                // canvas����
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                // ����canvas���
                /*canvas.width = image.width;
                 canvas.height = image.height;
                 if (canvas.width>max) {canvas.width = max;}*/
                // ��ͼ����Ƶ�canvas��
                // ctx.drawImage(image, 0, 0, image.width, image.height);
                ctx.drawImage(image, 0, 0, 200, 200);
                // ע�⣬��ʱimageû�м��뵽dom֮��
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























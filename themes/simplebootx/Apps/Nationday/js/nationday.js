
 var gift1_name = "";
 var gift2_name = "";
 var gift3_name = "";
 
 var music;
 var registclick=false;
    $(function(){
        music=document.getElementById("bgMusic");
        music.play();
        $("#yinyue").click(function()
            {

            if(music.paused)
            {
                music.play();
                $("#yinyue").removeClass("ting");
            }
            else
            {
                music.pause();
                $("#yinyue").addClass("ting");
            }
        });
        $(".tab li").click(function(){
            var class1=$(this).attr("data-class");
            if(!class1) return;
            var id=$(this).attr("data-id");
            $(this).parent().find("li.active").removeClass("active");
            $(this).addClass("active");
            $("."+class1).addClass("d_n");
            $("."+id).removeClass("d_n");
        });
        $('.lihe>li').click(function(){
            //如果已经被选中，就取消选中。
            var lihe_list='';
            $('lihe_num').html(lihe_list.length);
            if($(this).hasClass("xuanzhong"))
            {
                // var quxiao0="{$mylihe[0]}";
                // var quxiao1="{$mylihe[1]}";
                // var quxiao2="{$mylihe[2]}";
                var id=$(this).attr('data-id');
                if(id!=quxiao0&&id!=quxiao1&&id!=quxiao2){
                    $(this).removeClass("xuanzhong");
                }
            }
            else
            {
                if($(".lihe li.xuanzhong").length<3)
                    $(this).addClass("xuanzhong");
            }
            $(".lihe li.xuanzhong").each(function(){
                var id=$(this).attr('data-id');
                lihe_list+=id;
            });
            var lihe_num=$(".lihe li.xuanzhong").length;
            $(".lihe_num").text(lihe_num);
            //alert(id_list);
            $('.lihe_list').val(lihe_list);
        });
        $(".binglist li").click(function(){
            //获取自己的值，
            var classid=$(this).attr("data-id");
            var class1=$(this).attr("data-class");
            if(!class1)
            {
                $(".lingqu").hide();
                select(); return;
            }
            var kuangzhi=1;
            switch(classid)
            {
                case "dier": kuangzhi=2;break;$(fudiv)
                case "disan": kuangzhi=3;break;
            }
            $(this).parent().parent().css("backgroundImage","url(__TMPL__Apps/nationday/images/kuang"+kuangzhi+".png)");
        });
        $(".zhucebtn").click(function(){
            if(registclick) return;
            var lihe_num=$(".lihe li.xuanzhong").length;
            if($(".lihe li.xuanzhong").length==0)
            {
                alert("需要选择礼包！");return;
            }
            var lihe_select="";
            $(".lihe li.xuanzhong").each(function(){
                lihe_select+=$(this).attr('data-id');
            });
            if(!is_register){
                var name=$("input[name='name']").val();
                var mobile=$("input[name='mobile']").val();
                if(name=="")
                {
                    alert("名称不能为空！");return;
                }
                if(mobile=="")
                {
                    alert("手机号不能为空！");return;
                }
                if(mobile.length!=11)
                {
                    alert("手机号不正确！");return;
                }
                var remark=$('[name="remark"]').val(); 
                var age=$('[name="age"]').val();
                var cless=$('[name="cless"]').val();
                var school=$('[name="school"]').val();
            }else{
                name= tmp_name;
                mobile= tmp_mobile;
                remark= tmp_remark; 
                age= tmp_age;
                cless= tmp_cless;
                school= tmp_school;
            }
            registclick=true;
            $.post(registurl,{name:name,mobile:mobile,age:age,cless:cless,school:school,remark:remark,lihe_num:lihe_num,lihe_select:lihe_select},function(res){
                //alert(res.shunxu+">>>");
                registclick=false;
                if(res.status==1)
                {
                    is_register = true;
                    //alert("注册成功！");
                    $(".login").hide();
                    $(".topdiv,.lingqu,.xinxidiv").show();
                    window.location.href=url_zhucebtn;
                }
                else
                {
                    alert(res.msg);
                }
            });
        });
        //给ta帮帮忙
        var kaiguan_zhuli=false;
        $('.zhuli').click(function(){
            if(kaiguan_zhuli) return;
            kaiguan_zhuli=true;
            var url=url_zhuli;
            $.post(url,{},function(res){
                //alert("his"+res.his+":::"+res.msg+">>>"+res.status);
                if(res.test==1)
                    alert(res.msg);
                kaiguan_zhuli=false;
                if(res.status==1)
                {
                    alert("帮忙成功！");
                    window.location.href=url_zhuli_redict;
                }
                else
                {
                    alert(res.msg);
                }
            });
        });
        //我的活动
        $('.myhuodong').click(function(){
            window.location.href=url_myhuodong;
        });
    });
    function select()
    {
        $(".topdiv,.canyu,.xinxidiv").hide();
        $(".login").show();
    }
    var kaiguan_submit=false;
    function submit()
    {
        if(kaiguan_submit) return;
            kaiguan_submit=true;
        if($(".lihe li.xuanzhong").length==0)
        {
            alert("需要选择礼包！");return;
        }
        if($('input[name=name]').val().length==0)
        {
            alert("姓名不能为空");
        }
        if($('input[name=mobile]').val().length!=11)
        {
            alert("请输入11位手机号");
        }
        $(".login").hide();
        $(".topdiv,.lingqu,.xinxidiv").show();
        $('#registform').submit();
    }
    var kaiguan_libao=false;
    function libao(thatx,that)
    {   if(kaiguan_libao) return;
        kaiguan_libao=true;
        var url = url_libao;
        $.post(url,{gift_key:that},function(res){
            kaiguan_libao=false;
            //alert(res.msg+"has"+res.has_gift+"name"+res.gift_name);
            if(res.has_gift){
                $(".konglihe").hide();
                $(".lihediv").show();
                $(".xiaoren2").hide();
                $(".xiaoren").show();
                $(".gift_have").text(res.gift_name);
                $(".gift_has").show();
                $(".gift_none").hide();
                $(".gift_have").show();
                $(".gift_tishi").show();
                $(thatx).parent().find("p").css("color","#cb2c29");
                $(thatx).parent().find("p").html(res.gift_name);
                $(thatx).parent().append("<div style='height:50px;'></div>");
                $(thatx).hide();
            }else{
                $(".lihediv").hide();
                $(".konglihe").show();
                $(".xiaoren").hide();
                $(".xiaoren2").show();
                $(".gift_has").hide();
                $(".gift_none").show();
                $(".gift_have").hide();
                $(".gift_tishi").hide();
                $(thatx).parent().find("p").css("color","black");
                $(thatx).parent().find("p").html("该奖品已经被领完了");
                $(thatx).parent().find(".btn").hide();
                $(thatx).parent().append("<div style='height:50px;'></div>");
            }
            showBg('fullbg2','libao',true);
        });
        
    }
    function baoxiang()
    {
        showBg('fullbg','baoxiang',true);
    }
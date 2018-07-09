$(function () {
    console.log("fdsf");
    $("#tongzi").keydown(function () {
        var text1=document.getElementById("tongzi").value;
        console.log(text1);
        var len;//记录剩余字符串的长度
        if(text1.length>=25) //限制字数
        {
            document.getElementById("tongzi").value=text1.substr(0,25);
            len=0;
        }
        else
        {
            len=25-text1.length;  //限制字数
        }
        var show=len;
        document.getElementById("pinglun").innerText=show;
    });



    $("#vote-content-nav-text").on('click',function () {
        $(".vote-content-top-hide-one ").css("display","block")
        $(".vote-content-top-hide-two ").css("display","none")
        $(".vote-content-top-hide-three ").css("display","none")
    });
    $("#vote-content-nav-img").on('click',function () {
        $(".vote-content-top-hide-one ").css("display","none")
        $(".vote-content-top-hide-three ").css("display","none")
        $(".vote-content-top-hide-two ").css("display","block")
    });
    $("#vote-content-nav-video").on('click',function () {
        $(".vote-content-top-hide-one ").css("display","none")
        $(".vote-content-top-hide-two ").css("display","none")
        $(".vote-content-top-hide-three ").css("display","block")
    });

    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
    var tpeImg = $('<img class="vote-content-top-hide-two-img" />');
    $('#thumbnail').change(function() {
        tpeImg.attr('src', getObjectURL($(this)[0].files[0]));
        $(this).after(tpeImg);});

})
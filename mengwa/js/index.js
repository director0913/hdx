$(function () {
    $("#list1").click(function(){
        window.location.href="index.html"
    });
    $("#list2").click(function(){
        window.location.href="ranking.html"
    });
    $("#list3").click(function(){
        window.location.href="win.html"
    })
    $("#list4").click(function(){
        $(".shang").css("display","block")
    })
    $(".shang").click(function(){
        $(".shang").css("display","none")
    })
    $(".shnagc").click(function(){
        $(".shang").css("display","block")
        if(window.event){//IEÏÂ×èÖ¹Ã°ÅÝ
            event.cancelBubble  = true;
        }else{
            event.stopPropagation();
        }
    })
    $(".mingbaoi").click(function () {
        window.location.href="reports.html"
    })
    $(".defai").click(function () {
        window.location.href="defail.html"
    })

});
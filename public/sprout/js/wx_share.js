function wechat(appid,timestamp,noncestr,signature,title,link,imgUrl,desc){
    wx.config({
        debug: false,
        appId: appid,
        timestamp: timestamp,
        nonceStr: noncestr,
        signature: signature,
        jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'chooseWXPay'
          // 所有要调用的 API 都要加到这个列表中
        ]
      });
    wx.ready(function () {
        // 在这里调用 API
        wx.onMenuShareTimeline({  //例如分享到朋友圈的API 
            title: title, // 分享标题
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
             success: function () {
                                alert('分享成功');
            },
            cancel: function () {
                                alert('分享失败');
            // 用户取消分享后执行的回调函数
            }
        })
        wx.onMenuShareAppMessage({  
             title: title, // 分享标题
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            desc:desc,
            type: '', // 分享类型,music、video或link，不填默认为link  
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空  
            success: function () {   
                 alert('分享成功');
                // 用户确认分享后执行的回调函数  
            },  
            cancel: function () {   
                alert('分享失败');
                // 用户取消分享后执行的回调函数  
            }  
        });  
                   
        });
    wx.error(function(res){
        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
        alert(res);
    }); 
}
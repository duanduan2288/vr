if(typeof(window.shareData) == 'undefined'){
  window.shareData = {
       "imgUrl": "", 
     "Link": location.href,
    "Title": document.title,
    "Content": document.title,
    "openid":"openid"
  }
}

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {  
  // 发送给好友
  WeixinJSBridge.on('menu:share:appmessage', function (argv) {
    WeixinJSBridge.invoke('sendAppMessage', { 
      "img_url": window.shareData.imgUrl,
      "img_width": "640",
      "img_height": "640",
      "link": window.shareData.Link,
      "desc": window.shareData.Content,
      "title": window.shareData.Title
    }, function (res) {
      report(window.shareData.openid,window.shareData.Link,'appmessage');

    });
  });

  // 分享到朋友圈
  WeixinJSBridge.on('menu:share:timeline', function (argv) {
    WeixinJSBridge.invoke('shareTimeline', {
      "img_url": window.shareData.imgUrl,
      "img_width": "640",
      "img_height": "640",
      "link": window.shareData.Link,
      "desc": window.shareData.Content,
      "title": window.shareData.Title
    }, function (res) {
         report(window.shareData.openid,window.shareData.Link,'timeline');
    });
  });

  // 分享到微博
  WeixinJSBridge.on('menu:share:weibo', function (argv) {
    WeixinJSBridge.invoke('shareWeibo', {
      "content": window.shareData.Content,
      "url": window.shareData.Link
    }, function (res) {
      report(window.shareData.openid,window.shareData.Link,'weibo');
    });
  });
}, false);


var shareurl = window.location.href;
var imgUrl = gConfig.share_img; 
var title = gConfig.share_title;
var content = gConfig.share_content;
wx.ready(function () {
    wx.onMenuShareAppMessage({
        title: title,
        desc:  content,
        link:  shareurl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {
            
        }
    });
    wx.onMenuShareTimeline({
        title: title,
        link: shareurl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {}
    });
    wx.onMenuShareQQ({
        title: title,
        desc:  content,
        link:  shareurl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {}
    });
    wx.onMenuShareWeibo({
        title: title,
        desc:  content,
        link:  shareurl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {}
    });
});
/**
 * 分享回调方法
 */
function shareCallback(){
    
}
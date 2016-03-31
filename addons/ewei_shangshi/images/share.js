var Share = Share || {};
// WeChat Share
;! function() {
    var WeChat = {
            defaultData: {
                title: "",
                desc: "",
                link : "",
                imgUrl : "",
                type: "",
                dataUrl: ""
            },
            dImg: new Image(),
            customData: {},
            callback: [],
            setData: function( key, value ) {
                var customData = this.customData,
                    newData = {};
                if ( typeof key === "object" ) {
                    newData = key
                } else {
                    newData[key] = value;
                }
                for ( i in newData ) {
                    customData[i] = newData[i];
                    switch ( i ) {
                        case "title" :
                            ( document.getElementsByTagName( "title" )[0] || {} ).innerHTML = newData[i];
                            break;
                        case "imgUrl" :
                            var dBody = document.getElementsByTagName( "body" )[0],
                                dImg  = this.dImg;
                            dImg.src = newData[i] || "";
                            dImg.width  = 320;
                            dImg.height = 320;
                            dImg.style.position = "fixed";
                            dImg.style.top = "-500px";
                            dBody && ( dBody.firstChild ? dBody.insertBefore( dImg, dBody.firstChild ) : dBody.appendChild( dImg ) );
                            break;
                        default:;
                    }
                }
                this.initShareByWeixinJSSDK();
            },
            getData: function() {
                var defaultData = this.defaultData,
                    customData  = this.customData,
                    data = {};
                for ( i in defaultData ) {
                    data[i] = defaultData[i];
                }
                for ( i in customData ) {
                    data[i] = customData[i];
                }
                data.title || ( data.title = ( document.getElementsByTagName( "title" )[0].innerHTML || "" ).replace( /&nbsp;/g, " " ) );
                data.link  || ( data.link = location.href.split( "#" )[0] || "" );
                return data;
            },
            addCallback: function( fn ) {
                ( typeof fn === "function" ) && this.callback.push( fn );
            },
            fireCallback: function( shareTo ) {
                var callback = this.callback;
                for ( var i = 0, l = callback.length; i < l; ++ i ) {
                    callback[i].call( this, shareTo );
                }
            },
            // old api
            initShareByWeixinJSBridge: function() {
                var _this = this;
                // to friends
                WeixinJSBridge.on('menu:share:appmessage', function( argv ){
                    var data = _this.getData();
                    WeixinJSBridge.invoke( "sendAppMessage", {
                        "appid": data.appid || "",
                        "img_url": data.imgUrl || "",
                        "img_width": data.imgWidth || "",
                        "img_height": data.imgHeight || "",
                        "link": data.link || "",
                        "desc": data.desc || "",
                        "title": data.title || ""
                    }, function( res ) {
                        // _report( "send_msg", res.err_msg );
                    } );
                    _this.fireCallback( "Chat" );
                });
                // to moments
                WeixinJSBridge.on('menu:share:timeline', function( argv ){
                    var data = _this.getData();
                    WeixinJSBridge.invoke( "shareTimeline", {
                        "img_url": data.imgUrl || "",
                        "img_width": data.imgWidth || "",
                        "img_height": data.imgHeight || "",
                        "link": data.link || "",
                        "desc": data.desc || "",
                        "title": data.title || ""
                    }, function( res ) {
                        // _report('timeline', res.err_msg);
                    } );
                    _this.fireCallback( "Moments" );
                });
                // to weibo
                WeixinJSBridge.on('menu:share:weibo', function( argv ){
                    var data = _this.getData();
                    WeixinJSBridge.invoke( "shareWeibo", {
                        "content": data.desc || "",
                        "url": data.url || "",
                    }, function( res ) {
                        // _report('weibo', res.err_msg);
                    } );
                    _this.fireCallback( "WeiBo" );
                });
            },
            initShareByWeixinJSSDK: function() {
                var _this = this,
                    data  = this.getData();

                wx.ready( function() {
                    wx.onMenuShareTimeline({
                        title: data.title, // 分享标题
                        link: data.link, // 分享链接
                        imgUrl: data.imgUrl, // 分享图标
                        success: function () { 
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function () { 
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    wx.onMenuShareAppMessage({
                        title: data.title, // 分享标题
                        desc: data.desc, // 分享描述
                        link: data.link, // 分享链接
                        imgUrl: data.imgUrl, // 分享图标
                        type: data.type, // 分享类型,music、video或link，不填默认为link
                        dataUrl: data.dataUrl, // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () { 
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function () { 
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    wx.onMenuShareQQ({
                        title: data.title, // 分享标题
                        desc: data.desc, // 分享描述
                        link: data.link, // 分享链接
                        imgUrl: data.imgUrl, // 分享图标
                        success: function () { 
                           // 用户确认分享后执行的回调函数
                        },
                        cancel: function () { 
                           // 用户取消分享后执行的回调函数
                        }
                    });
                    wx.onMenuShareWeibo({
                        title: data.title, // 分享标题
                        desc: data.desc, // 分享描述
                        link: data.link, // 分享链接
                        imgUrl: data.imgUrl, // 分享图标
                        success: function () { 
                           // 用户确认分享后执行的回调函数
                        },
                        cancel: function () { 
                            // 用户取消分享后执行的回调函数
                        }
                    });
                } );
            }
        };

    window.Share.WeChat = {
        setData: function( key, value ) {
            WeChat.setData( key, value );
        },
        getData: function() {
            return WeChat.getData();
        },
        addCallback: function( fn ) {
            WeChat.addCallback( fn );
        }
    };

    // old api
    navigator.userAgent.match( /micromessenger/gi ) && document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        WeChat.initShareByWeixinJSBridge();
    }, false);
} ();

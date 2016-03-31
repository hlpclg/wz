define(function (require, exports, module) {
    "require:nomunge,exports:nomunge,module:nomunge";
    require("../../lib/dialog_skin");
    var media = require("../../lib/media");    
    //var weixin = require("lib/myweixin.js");

    //随机因子
    var _rnd = {};
    _rnd.today = new Date();
    _rnd.seed = _rnd.today.getTime();

    //定时器引用
    var _timer = {};

    //宽度定义，对应css也要调整
    //圣诞老人宽度
    var _SantasCar_Width = 396;
    //礼物划落区宽度
    var _Send_Gift_Screen_Width = 300;
    //头像区宽度
    var _Interat_Headers_Layer_Width = 560;

    module.exports = {
        init: function (config) {
            // 初始化微信分享
            this.initShare(config);
            
            //显示loading……
            $.loading.show();
            //预加载图片后开始加载场景
            this._imagePreload(__Images, this._load);

            //加载背景音乐，只有音频是并行下载，其他是需要在_load中完成
            media.media_init();
        },
		        // 兼容6.0.2以下版本的微信分享
        _fixShare : function(share){
            require('lib/WeixinApi');                                    
            // 初始化WeixinApi，等待分享
            WeixinApi.ready(function(Api) {
                // 微信分享的数据
                var wxData = {
                    "imgUrl" :share.imgUrl,
                    "link" : share.link,
                    "desc" : share.desc,
                    "title" : share.title
                };
                    // 分享的回调
                var wxCallbacks = {
                    // 收藏操作不执行回调，默认是开启(true)的
                    favorite : false,

                    // 分享操作开始之前
                    ready : function() {
                        // 你可以在这里对分享的数据进行重组
                        //alert("准备分享");
                    },
                    // 分享被用户自动取消
                    cancel : function(resp) {
                        // 你可以在你的页面上给用户一个小Tip，为什么要取消呢？
                        //alert("分享被取消，msg=" + resp.err_msg);
                    },
                    // 分享失败了
                    fail : function(resp) {
                        // 分享失败了，是不是可以告诉用户：不要紧，可能是网络问题，一会儿再试试？
                        alert("分享失败，msg=" + resp.err_msg);
                    },
                    // 分享成功
                    confirm : function(resp) {
                        // 分享成功了，我们是不是可以做一些分享统计呢？
                        //alert("分享成功，msg=" + resp.err_msg);
                    },
                    // 整个分享过程结束
                    all : function(resp,shareTo) {
                        // 如果你做的是一个鼓励用户进行分享的产品，在这里是不是可以给用户一些反馈了？
                        // alert("分享" + (shareTo ? "到" + shareTo : "") + "结束，msg=" + resp.err_msg);
                    }
                };

                // 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
                Api.shareToFriend(wxData, wxCallbacks);

                // 点击分享到朋友圈，会执行下面这个代码
                Api.shareToTimeline(wxData, wxCallbacks);

                // 点击分享到腾讯微博，会执行下面这个代码
                Api.shareToWeibo(wxData, wxCallbacks);

                // iOS上，可以直接调用这个API进行分享，一句话搞定
                Api.generalShare(wxData,wxCallbacks);
            });
        },
        
        // 监听微信分享JSSDK
        _wxShare:function(wx, share){
            // 监听“分享给朋友”
            wx.onMenuShareAppMessage({
                title: share.title,
                desc: share.desc,
                link: share.link,
                imgUrl: share.imgUrl
            });

             //监听“分享到朋友圈”
            wx.onMenuShareTimeline({
                title: share.title,
                link: share.link,
                imgUrl: share.imgUrl
            });

            //监听“分享到QQ”
            wx.onMenuShareQQ({
                title: share.title,
                desc: share.desc,
                link: share.link,
                imgUrl: share.imgUrl
            });

            //监听“分享到微博”
            wx.onMenuShareWeibo({
                title: share.title,
                desc: share.desc,
                link: share.link,
                imgUrl: share.imgUrl
            });
        },
        
        _weixin : function () {
            var wx  = {
                isWeixin: false,
                version: -1
            },
            reg = /MicroMessenger\/([\d.]+)/i,
            match = navigator.userAgent.match(reg);

            if (match) {
                wx.isWeixin = true;
                wx.version = match[1];
            }

            return wx;
        },
        
        // 初始化分享
        initShare : function(config){
            var me = this;
            
            //分享数据
            var share = {};
            share.imgUrl = config.imgUrl;
            share.link = config.link;
            share.desc = config.desc;
            share.title= config.title;
            
            var wx = this._weixin();
            if(wx.isWeixin){
                var strVer = wx.version.replace(/\./g, '');
                if(strVer.length < 2){
                    strVer += '00';
                }
                if(strVer.length < 3){
                    strVer += '0';
                }
                if(strVer.length > 3){
                    strVer = strVer.substr(0,3);
                }
                var ver = parseInt(strVer, 10);
                if(ver < 602){
                    me._fixShare(share);
                    return;
                }
            }
            
            var wx = require('http://res.wx.qq.com/open/js/jweixin-1.0.0.js');
            
            if(!wx){
                me._fixShare(share);
                return;
            }
            wx.config({
                debug: config.debug,
                appId: config.appId,
                timestamp: config.timestamp,
                nonceStr: config.nonceStr,
                signature: config.signature,
                jsApiList: [
                    'checkJsApi',
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareWeibo',
                    'hideMenuItems',
                    'showMenuItems',
                    'hideAllNonBaseMenuItem',
                    'showAllNonBaseMenuItem'
                ]
            });
            
            var isError = false;
            //config信息验证失败会执行error函数
            wx.error(function(res){
				//alert(res.errMsg);
                isError = true;
                me._fixShare(share);
            });
            
            wx.ready(function () {
                if(isError){return;}
                wx.checkJsApi({
                    jsApiList: [
                      'onMenuShareTimeline'
                    ],
                    success: function (res) {    
                        //如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}                        
                        if(res.errMsg == "checkJsApi:ok"){                            
                            var chkResult = res.checkResult;
                            var api_name='onMenuShareTimeline';
                            if(chkResult[api_name] === true){
                                me._wxShare(wx, share);
                            }
                            else{
                                me._fixShare(share);
                            }
                        }
                        else{
                            me._fixShare(share);
                        }
                    }
               });              
            });        
        },
        //载入拆红包主场景
        //s:this, e:图片
        _load: function (s, e) {
            //隐藏loading……
            $.loading.hide();

            var fg = e[0].src;
            /*      s._loadFg(fg);*/

            s._loadAnimate(e);

            s._loadInterat();
        },
        //加载拆红包的朋友
        _loadInterat: function () {
            var left = ($(window).width() - _Interat_Headers_Layer_Width) / 2;
            //设置礼物划落left
            /*            $('#interat_headers_layer').css({left:left+'px'});    */

            this._bindEvents();
        },
        //绑定相关事件，放在加载用户头像之后绑定
        _bindEvents: function () {
            var me = this;
            //打开活动详情弹出层
            me._openActivityDetails();
            //关闭活动详情弹出层
            me._closeActivityDetails();
            //打开分享弹出层
            me._openShare();
            //关闭分享弹出层
            me._closeShare();
            //打开拆红包弹出层
            me._openRedPackets();
            //关闭拆红包弹出层
            me._closeRedPackets();
            //打开圣诞帮他拆红包弹出层
            me._openHelpHimRedPackets();
            //关闭圣诞帮他拆红包弹出层
            me._closeHelpHimRedPackets();
        },
        //打开活动详情弹出层
        _openActivityDetails: function () {
            var me = this;
            $('#info_arrow').click(function () {
                $("#activeDetailLayer,#dialog_mask").show();
                me._mask();
				//弹出层居中
				me._maskContent("#activeDetailLayer");
            });
        },
        //关闭活动详情弹出层
        _closeActivityDetails: function () {
            $("#underStandBtn").on("click", function () {
                $("#activeDetailLayer,#dialog_mask").hide();
            });
        },
        //打开分享弹出层
        _openShare: function () {
            var me = this;
            $("#scene1, #scene2").on("click", ".js_help", function () {
                $("#activeShareLayer,#dialog_mask").show();
                me._mask();
            });
        },
        //关闭分享弹出层
        _closeShare: function () {
			$("body").on("click",function(e){
				var target = $(e.target);
				if(target.closest(".js_help").length == 0 && $("#activeShareLayer").css("display")=="block")
				{
					$("#activeShareLayer,#dialog_mask").hide();		
				}
			});
        },
        //打开拆红包弹出层
        _openRedPackets: function () {
            var me = this;
            $("#scene1").on("click", ".js_folder", function () {
                htmlobj = $.ajax({url: $(this).attr('data-url'), async: false});
                var result = parseInt(htmlobj.responseText, 10);
                if(isNaN(result)){
                    $.message.show({content:htmlobj.responseText});
                }
                else{
                    if (result > 0 && result < 7) {
                        $("#openGiftLayer").find('.prize_mes').text('恭喜你中奖了！奖品：'+__Prizes[result-1]);
                        $("#openGiftLayer,#dialog_mask").show();
                        me._mask();
                    }
                    else{
                        $.message.show({content:"100%都不中，还不快快去买彩票？再次点击拆开礼包，马上中奖萌萌哒~"});
                    }
                }
                //弹出层居中
				me._maskContent("#openGiftLayer");
            });
        },
        //关闭拆红包弹出层
        _closeRedPackets: function () {
            var me = this;
            $("#get_prize_btn").on("click", function () {
                //获将逻辑判断						  
                me._acceptStatusPrize();
                $("#openGiftLayer,#dialog_mask").hide();
            });
			$("#close_open_gift_layer").on("click", function () {
				$("#openGiftLayer,#dialog_mask").hide();
            });
        },
        //打开圣诞帮他拆红包弹出层
        _openHelpHimRedPackets: function () {
            var me = this;
            $("#scene2").one("click", ".js_helpfolder", function () {
                htmlobj = $.ajax({url: $(this).attr('data-url'), async: false});
                if (htmlobj.responseText == 'ok') {
                    $("#helpHimGiftLayer,#dialog_mask").show();
                    me._mask();
                }
                else {
                    $.message.show({content:"验证失败"});
                }
            });
        },
        //关闭圣诞帮他拆红包弹出层
        _closeHelpHimRedPackets: function () {
            $("#gift_join_btn").on("click", function () {
                $("#helpHimGiftLayer,#dialog_mask").hide();
            });
        },
        //蒙板层高度设置
        _mask: function () {
            var mask_height = $("#fg_screen").height();
            $("#dialog_mask").css("height", mask_height);
            $("html,body,#fg_screen").scrollTop(0);

        },
		//蒙板弹出层中内容居中
		_maskContent: function(obj){
			    var popCon_h = $(obj).height();
                var win_h = $(window).height();
                var popContTop = (win_h - popCon_h) / 2;
                if (popCon_h < win_h) {
                    $(obj).css({top: popContTop});
                }
				else{
					$(obj).css({top: 0});
				}
		
		},
        //兑奖页面逻辑
        _acceptStatusPrize: function () {
            //判断兑奖是否需先注册经纪人            
            var isreceive = __Status.isReceive;
            // http://www.wifixc.com/app/index.php?i=2&j=4&c=entry&rid=27&do=detail&m=qiyue_luckymoney&wxref=mp.weixin.qq.com&wxref=mp.weixin.qq.com
            var params = this.getUrlParams();
            var exchangeUrl = sysinfo.siteurl;
            switch (__Status.activityObject)
            {
                case 'Broker':
                    var isBrokerLogin = __Status.isBrokerLogin;
                    if (isBrokerLogin === "1") {
                        //显示 确认信息
                        if (isreceive==='1') {
                            $.message.show({content:"恭喜，您已经领过奖了！"});
                        }
                        else {
                            window.location.href = exchangeUrl;
                        }
                    }
                    else {
                        $.message.show({content:"请注册经纪人后再回来兑奖！"});
                        setTimeout(function () {
                            window.location.href = __Status.returnUrl;
                        }, 3000);
                    }
                    break;
                case 'Sale':
                    var isfans = __Status.isfans;
                    if (isfans === "1") {
                        //显示 确认信息
                        if (isreceive==='1') {
                            $.message.show({content:"恭喜，您已经领过奖了！"});
                        }
                        else {
                            window.location.href = exchangeUrl;
                        }
                    }
                    else {
                        $.message.show({content:"请关注"+__Status.wxname+"后再来兑奖"});
			            setTimeout(function () {
                            window.location.href = __Status.attentionUrl;
                        }, 2500);
                    }
                    break;
                case 'Customer':
                    if (isreceive==='1') {
                        $.message.show({content:"恭喜，您已经领过奖了！"});
                    }
                    else {
                        window.location.href = sysinfo.public_url+'&do=register';
                    }
                /*
                    var isfans = __Status.isfans;
                    if (isfans === "1") {
                        //显示 确认信息
                        if (isreceive==='1') {
                            $.message.show({content:"恭喜，您已经领过奖了！"});
                        }
                        else {
                            window.location.href = exchangeUrl;
                        }
                    }
                    else {
                        $.message.show({content:"请关注"+__Status.wxname+"后再来兑奖"});
                        setTimeout(function () {
                            window.location.href = __Status.attentionUrl;
                        }, 2500);
                    }
                */
                    break;
                default:
                    if (isreceive==='1') {
                        $.message.show({title:"登陆信息", content:"恭喜，您已经领过奖了！", time:"3600"});
                    }
                    else {
                        window.location.href = exchangeUrl;
                    }
                    break;
            }
        },
        //加载前景
        /*        _loadFg:function(fg){
         $('#fg_screen').css({'background-image': 'url("'+fg+'")'});
         },*/

        //图片预加载，全部加载完毕时，回调callback方法
        //images:[{src:'', loaded:true/false}]
        _imagePreload: function (images, callback) {
            var oThis = this;
            $(images).each(function () {
                var me = this;
                var img = new Image();
                img.onload = function () {
                    me.loaded = true;

                    if (!!callback) {
                        var flag = true;
                        $(images).each(function () {
                            if (!this.loaded) {
                                flag = false;
                            }
                        });
                        if (flag) { //所有图片加载完成了，才callback
                            callback(oThis, images);
                        }
                    }
                };
                img.src = me.src;
            });
        },
        //加载动画
        _loadAnimate: function (images) {
            this._driveSantasCar(images);
        },
        //圣诞车
        _driveSantasCar: function (images) {
            var santasCarImg = images[1].src;

            var width = $(window).width();
            var img = $('<img />').attr('src', santasCarImg).css({
                'position': 'absolute',
                'left': width + 'px',
                'top': '5px',
                'width': '62%',
                'marginLeft': '20%'
            });
            img.appendTo('#animate_screen');
            var left = ($(window).width() - _SantasCar_Width) / 2;
            var me = this;
            img.animate({left: 0}, 1200, 'ease-in-out', function () {

                $("#info_arrow img").css("z-index", 3);
                me._sendChristmasGift(images);
            });
        },
        //圣诞礼物
        _sendChristmasGift: function (images) {
            var left = ($(window).width() - _Send_Gift_Screen_Width) / 2;
            //设置礼物划落left
            $('#send_gift_screen').css({left: left + 'px'});

            var me = this;

            setInterval(function () {
                me._randomGifts(images);
                _timer = setTimeout(function () {
                    me._randomGifts(images);
                }, 50);
                _timer = setTimeout(function () {
                    me._randomGifts(images);
                }, 100);
            }, 800);
        },
        //随机生成划落的礼物
        _randomGifts: function (images) {
            var rnd = Math.ceil(Math.random() * 3);
            if (rnd === 0) {
                rnd = 1;
            }
            var giftImg = images[1 + rnd].src;

            var rnd1 = this._random(3);

            var img = $('<img />').attr('src', giftImg).css({
                'position': 'absolute',
                'left': (rnd1 * 60) + 'px',
                'top': '0',
                'width': (rnd * 6) + 'px',
                'height': (rnd * 5) + 'px'
            });
            img.appendTo('#send_gift_screen');

            img.animate({top: '350px'}, 1500 + rnd * 100, 'ease-in-out', function () {
                img.remove();
            });
        },
        _rand: function () {
            _rnd.seed = (_rnd.seed * 9301 + 49297) % 233280;
            return _rnd.seed / (233280.0);
        },
        _random: function (number) {
            return Math.ceil(this._rand() * number);
        },
        
        getUrlParams : function() {
            var re = /(?:\\?|#|&)([^&#?=]*)=([^&#?=]*)(?:$|&|#)/ig;
            var url = window.location.href;
            var temp;
            var result = {};
            while ((temp = re.exec(url)) != null) {
                result[temp[1]] = temp[2];
            }
            return result;
        }
    };
});
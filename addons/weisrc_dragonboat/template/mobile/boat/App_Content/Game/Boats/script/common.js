/*
 公用的一些
 jslover@20150504
 */
define(function (require, exports, module) {
    var $ = require('zepto');
    var Music = require('script/music.js');
    var isTouch = 'ontouchstart' in document;
    var E = {
        touchstart: isTouch ? 'touchstart' : 'mousedown'
        , touchmove: isTouch ? 'touchmove' : 'mousemove'
        , touchend: isTouch ? 'touchend' : 'mouseup'
    };
    var $mask = $('.mask');

    var bodyOffsetLeft = $('body').offset().left;


    var ImgLoader = function () {
        var imgArray = [];
        return {
            createImage: function (src) {
                return typeof imgArray[src] != 'undefined' ? imgArray[src] : (imgArray[src] = new Image(), imgArray[src].src = src, imgArray[src]);
            },
            loadImage: function (arr, callback) {
                for (var i = 0, l = arr.length; i < l; i++) {
                    var img = arr[i];
                    imgArray[img] = new Image();
                    imgArray[img].onload = imgArray[img].onerror = function () {
                        this.onload = this.onerror = null;
                        if (i == l - 1 && typeof callback == 'function') {
                            callback();
                        }
                    };
                    imgArray[img].src = img;
                }
            }
        };
    };

    var config = {
        time: 15
        , level: 3
        ,count:-1
    };
    if (window.gameConfig) {
        config.time = gameConfig.time || 15;
        config.level = gameConfig.level || 3;
        config.count = gameConfig.count || 0;
    }

    var Common = {
        w: 320
        , h: window.stageH
        , level: config.level
        , randomAmi: 0.025
        ,randomGood: 0.4
        //帧率
        , fps: 45    //60-2,30-4,45-3
        //速度
        , bgSpeed: 3
        , bgWidth: 320
        , bgHeight: 758
        , baseUrl: window.baseUrl || '/'
        , time: 0
        , distance:0
        , gameTime: config.time //秒
        , timeLeft: config.time
        , timmer: null
        , bgloop: 0
        , score: 0
        , im: new ImgLoader()
        , amiList: []
        , bgDistance: 0
        , E: E
        ,gamePause:false
        , frame: 0
        , $mask: $mask
        , isTouch: isTouch
        , getScore: function () {
            var time = Math.floor(Common.time / this.fps);
            var score = Common.score;
            var per = 0;
            if (this.distance < 10) {
                per = 0;
            }else if (this.distance < 20) {
                per = 1;
            } else if (this.distance < 50) {
                per = 5;
            } else if (this.distance < 60) {
                per = 10;
            } else if (this.distance < 70) {
                per = 15;
            } else if (this.distance < 90) {
                per = 20;
            } else if (this.distance < 100) {
                per = 35;
            } else if (this.distance < 120) {
                per = 50;
            } else if (this.distance < 140) {
                per = 60;
            } else if (this.distance < 160) {
                per = 70;
            } else if (this.distance < 180) {
                per = 80;
            } else if (this.distance < 200) {
                per = 90;
            } else if (this.distance < 260) {
                per = 95;
            } else {
                per = 99;
            }

            if (Common.timeLeft <= 0) {
                $('#box-submit h1').html('时间耗尽..');
            } else {
                $('#box-submit h1').html('失败..');
            }
            if (per > 30) {
                $('#box-submit h1').html('抢粽初级生');
                Music.sound.play('win');
            } else {
                Music.sound.play('lost');
            }
            if (per > 50) {
                $('#box-submit h1').html('抢粽大侠');
            }

            $('.pop-box').hide().removeClass('show');
            Common.$mask.show();
            setTimeout(function () {

                $('.light-box').addClass('show');
            },250);
            $('#box-submit').show().addClass('show');

            $('#result-distance').html(this.distance);
            $('#result-count').html(this.score);
            $('#beyound-count').html(per + '%');




            try {
                //玩完自动提交分数
                var point = 10 * this.distance;
                //提交结果
                //alert('玩完自动提交分数' + this.distance);

                //$.ajax
                //({
                //    url: "/Boats/Sigunp",
                //    type: "post",
                //    data: { MinigameId: $("#MinigameId").val(), point: point },
                //    error: function () {
                //
                //    },
                //    success: function (result) {
                //        //console.log(result);
                //    }
                //});

                var ruleid = $("#ruleid").val();
                var weid = $("#weid").val();

                var url = 'index.php?i='+weid+'&c=entry&id='+ruleid+'&do=AutoSaveCredit&m=weisrc_dragonboat#';

                $.ajax
                ({
                    url: url,
                    type:'POST',
                    data: {point:this.distance},
                    dataType:'json',
                    error: function () {
                        //alert('网络通讯异常，请稍后再试！');
                    },
                    success: function (result) {
                        if (result.success == 1) {

                        } else if (result.success == 0) {

                        } else {
                            alert('未知状态');
                        }
                    }
                });
            }catch(eee){}
        }
        , stop: function () {

            Music.bg.stop();
            $('#stage').off(Common.E.touchstart + ' ' + Common.E.touchmove + ' ' + Common.E.touchend);
            setTimeout(function () {
                clearTimeout(Common.timmer);
            }, 0);
        }
        , bodyOffsetLeft: bodyOffsetLeft
    };
    //难度级别
    if (config.level == 1) {
        Common.bgSpeed = 2;
        Common.randomAmi = 0.02;
        Common.randomGood = 0.7;
    }
    if (config.level == 2) {
        Common.bgSpeed = 3;
        Common.randomAmi = 0.025;
        Common.randomGood = 0.6;
    }
    if (config.level == 3) {
        Common.bgSpeed = 3;
        Common.randomAmi = 0.03
        Common.randomGood = 0.45;
    }
    if (config.level == 4) {
        Common.bgSpeed = 5;
        Common.randomAmi = 0.03
        Common.randomGood = 0.45;
    }
    if (config.level == 5) {
        Common.randomAmi = 0.04
        Common.randomGood = 0.5;
        Common.bgSpeed = 8;
    }
    //IPHONE特殊处理
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf('iphone') > 0 || ua.indexOf('ios') > 0) {
        Common.fps = 60;
        Common.bgSpeed = Common.bgSpeed * 45 / 60;
    }

    module.exports = Common;
});
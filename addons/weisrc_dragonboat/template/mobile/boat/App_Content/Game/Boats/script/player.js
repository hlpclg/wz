/*
 玩家对象
 jslover@20150504
 */
define(function (require, exports, module) {
    var $ = require('zepto');
    var Common = require('script/common.js');
    var Music = require('script/music.js');

    var Player = function (ctx) {
        Common.im.loadImage([Common.baseUrl + 'style/images/player.png']);
        this.width = 59;
        this.height = 93;
        this.left = Common.w / 2 - this.width / 2;
        this.top = Common.h - 2 * this.height;
        this.playerImg = Common.im.createImage(Common.baseUrl + 'style/images/player.png');


        this.paint = function () {
            Common.frame++;
            if (Common.frame % 10 == 0) {
                if (Common.frame % 20 == 0) {
                    this.playerImg = Common.im.createImage(Common.baseUrl + 'style/images/player.png');
                } else {
                    this.playerImg = Common.im.createImage(Common.baseUrl + 'style/images/player-1.png');
                }
            }
            ctx.drawImage(this.playerImg, this.left, this.top, this.width, this.height);
        };

        this.setPosition = function (event) {
            if (Common.isTouch) {
                var tarL = event.changedTouches[0].clientX;
                var tarT = event.changedTouches[0].clientY;
            }
            else {
                var tarL = event.offsetX || event.clientX;
                var tarT = event.offsetY || event.clientY;
            }
            //修正偏移量
            tarT = tarT / window.stageScale;
            tarL = tarL - Common.bodyOffsetLeft;
            tarL = tarL / window.stageScale;

            var _left = tarL - this.width / 2 ;
            var _top = tarT - this.height /1.3;

            var myLeft = this.left;
            var myTop = this.top;


            this.left = myLeft + (_left - myLeft) / 4;
            this.top = myTop + (_top - myTop) / 4;
            if (this.left < 0) {
                this.left = 0;
            }
            if (this.left > 320 - this.width) {
                this.left = 320 - this.width;
            }
            if (this.top < 100) {
                this.top = 100;
            }
            if (this.top > Common.h - this.height) {
                this.top = Common.h - this.height;
            }
        };

        this.controll = function () {
            var _this = this;
            var stage = $('#stage');
            var currentX = this.left,
                currentY = this.top,
                move = false;
            stage.on(Common.E.touchstart, function (event) {
                _this.setPosition(event);
                move = true;
            }).on(Common.E.touchend, function () {
                move = false;
            }).on(Common.E.touchmove, function (event) {
                event.preventDefault();
                if (move) {
                    _this.setPosition(event);
                }
            });
        };

        this.hit = function (amiList) {
            for (var i = amiList.length - 1; i >= 0; i--) {
                var f = amiList[i];

                if (f) {
                    var l1 = this.top + this.height / 2 - (f.top + f.height / 2);
                    var l2 = this.left + this.width / 2 - (f.left + f.width / 2);
                    var l3 = Math.sqrt(l1 * l1 + l2 * l2) + 5;
                    if (l3 <= this.width / 2 + f.width / 2) {
                        if (f.type == 0) {
                            Common.stop();
                            setTimeout(function () {
                                Common.getScore();
                            },200);
                        } else {
                            var postion = {
                                left: (amiList[f.id].left + 20) * window.stageScale
                                , top: (amiList[f.id].top - 20) * window.stageScale
                            };
                            amiList[f.id] = null;
                            $('#score').html(++Common.score+'个')
                                .removeClass('aminate-fire').addClass('aminate-fire');
                            //时间加2S
                            var addTime = Math.floor(Math.random() * 30) / 10;
                            Common.timeLeft = addTime + Common.timeLeft;
                            $('#timer-add').css(postion).show().html('+' + addTime + '秒').removeClass('aminate-fire').addClass('aminate-fire');
                            setTimeout(function () {
                                $('#score').removeClass('aminate-fire');
                                $('#timer-add').removeClass('aminate-fire').hide();
                            }, 250);
                            //$('#audio-sound')[0].play();
                            Music.sound.play('hit');
                        }
                        break;
                    }
                }
            }
        };
    };
    module.exports = Player;
});
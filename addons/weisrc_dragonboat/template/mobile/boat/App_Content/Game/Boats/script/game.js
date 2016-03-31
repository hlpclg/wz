/*
 游戏主逻辑
 jslover@20150504
 */
define(function (require, exports, module) {
    var $ = require('zepto');
    var Common = require('script/common.js');
    var Player = require('script/player.js');
    var Ami = require('script/ami.js');
    var Music = require('script/music.js');

    var Game = {

        init: function () {
            var _this = this;
            var canvas = document.getElementById('stage');
            var ctx = canvas.getContext('2d');

            //绘制背景
            var bg = new Image();
            _this.bg = bg;
            bg.onload = function () {
                ctx.drawImage(bg, 0, 0, Common.bgWidth, Common.bgHeight);
            };
            bg.src = Common.baseUrl +  'style/images/bg.jpg';
            _this.initListener(ctx);
        }
        , replay: function () {
            var _this = this;
            var canvas = document.getElementById('stage');
            var ctx = canvas.getContext('2d');
            _this.player = new Player(ctx);
            _this.player.controll();
            _this.reset();
            _this.run(ctx);
            Music.bg.play();
        }
        , initListener: function (ctx) {
            var _this = this;
            /*
             $(document).on(Common.E.touchmove, function (event) {

             event.preventDefault();
             });
             */
            $('#box-guide').bind(Common.E.touchstart, function () {
                $(this).hide();
                _this.player = new Player(ctx);
                _this.player.paint();
                _this.player.controll();
                Game.run(ctx);
                Music.bg.play();
                Common.$mask.hide();
            });
        }
        , rollBg: function (ctx) {
            if (Common.bgDistance >= Common.bgHeight) {
                Common.bgloop = 0;
            }
            Common.bgDistance = ++Common.bgloop * Common.bgSpeed;
            ctx.drawImage(this.bg, 0, Common.bgDistance - Common.bgHeight, Common.bgWidth, Common.bgHeight);
            ctx.drawImage(this.bg, 0, Common.bgDistance, Common.bgWidth, Common.bgHeight);

        }
        , run: function (ctx) {
            if (!Common.gamePause) {
                var _this = Game;
                ctx.clearRect(0, 0, Common.bgWidth, Common.bgHeight);
                _this.rollBg(ctx);

                //绘制玩家
                _this.player.paint();
                _this.player.hit(Common.amiList);


                //生成移动对象
                _this.createAmi();

                //绘制粽子
                for (i = Common.amiList.length - 1; i >= 0; i--) {
                    var f = Common.amiList[i];
                    if (f) {
                        f.paint(ctx);
                        f.move(ctx);
                    }
                }
                Common.time++;
                if (Common.time % Math.round(Common.fps / 10) == 0) {
                    Common.timeLeft = parseFloat(Common.timeLeft);
                    Common.timeLeft -= 0.1;
                    if (Common.timeLeft <= 0) {
                        Common.timeLeft = 0;
                        Common.stop();
                        setTimeout(function () {
                            Common.getScore();
                        }, 200);
                    }
                    //时间
                    var txtTimer = Common.timeLeft.toFixed(1) + '秒';
                    //最后3秒 时间提醒
                    if (Common.timeLeft < 3 && Math.floor(Common.timeLeft * 10) % 2 == 1) {
                        txtTimer = '<b>' + Common.timeLeft.toFixed(1) + '</b>' + '秒';
                    }
                    $('#timer').html(txtTimer);
                    var dis = Common.time * Common.level  / 3 / 13 ;


                    Common.distance = dis.toFixed(1);
                    $('#distance').html(Common.distance + '米');
                    var _left = Math.floor(dis - 170);
                    if (_left > 0) {
                        _left = 0;
                    }
                    if (_left % 3 == 0) {
                        $('#distance-bar').css('margin-left', _left);
                    }
                }
            }
            clearTimeout(Common.timmer);
            Common.timmer = setTimeout(function () {
                Game.run(ctx);
            }, Math.round(1000 / Common.fps));
        }
        , createAmi: function () {

            var random = Math.random();
            //产生目标的概率
            if (random < Common.randomAmi) {
                var left = Math.random() * (Common.w - 50);
                var randomType = Math.random();
                //产生粽子的概率
                var type = randomType > Common.randomGood ? 0 : 1;
                var id = Common.amiList.length;
                var f = new Ami(type, left, id);
                Common.amiList.push(f);
            }
        }
        , reset: function () {
            Common.amiList = [];
            Common.bgloop = 0;
            Common.score = 0;
            Common.timmer = null;
            Common.time = 0;
            Common.timeLeft = Common.gameTime;
            $('#score').text(Common.score);


        }
        , pause: function () {
            Common.gamePause = true;
            Music.bg.stop();
            $('#btn-game-pause').show();
        }
        , go: function () {
            Common.gamePause = false;
            Music.bg.play();
            $('#btn-game-pause').hide();
        }

    }
    module.exports = Game;
});
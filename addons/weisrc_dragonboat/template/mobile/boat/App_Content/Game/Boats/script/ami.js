/*
 移动目标对象
 jslover@20150504
 */
define(function (require, exports, module) {
    var $ = require('zepto');
    var Common = require('script/common.js');
    var Ami = function (type, left, id) {
        this.speedUpTime = 150;
        this.id = id;
        this.type = type;
        this.width = 45;
        this.height = 45;
        this.left = left;
        this.top = -50;
        this.speed = (Common.bgSpeed / 50) * Math.pow(1.2, Math.floor(Common.time / this.speedUpTime));
        this.loop = 0;

        var p = this.type == 0 ? 'style/images/ami-1.png' : 'style/images/ami-2.png';
        p = Common.baseUrl + p;
        this.pic = Common.im.createImage(p);
    };
    Ami.prototype.paint = function (ctx) {
        ctx.drawImage(this.pic, this.left, this.top, this.width, this.height);
    };
    Ami.prototype.move = function (ctx) {
        if (Common.time % this.speedUpTime == 0) {
            this.speed *= 1.2;
        }
        this.top += ++this.loop * this.speed;
        if (this.top > Common.h) {
            Common.amiList[this.id] = null;
        }
    };
    module.exports = Ami;
});
!function (a) {
    "use strict";
    function b(a) {
        return Math.round(100 * a) / 100
    }

    function c(a, b) {
        return Math.floor(Math.random() * (a - b) + b)
    }

    function d(a, b) {
        return Math.random() * a + b
    }

    function e(a, b) {
        return Math.random() * (b - a) + a
    }

    function f(a) {
        return a[Math.floor(Math.random() * a.length)]
    }

    function g() {
        return "#" + Math.floor(16777215 * Math.random()).toString(16)
    }

    function h(a, b) {
        return a > b ? a : b
    }

    function i(a, b) {
        return b > a ? a : b
    }

    function j(a, b, c) {
        return a > c ? a : c > b ? b : c
    }

    function k() {
        return 2 * Math.random() - 1 + (2 * Math.random() - 1) + (2 * Math.random() - 1)
    }

    function l(a, b) {
        return k() * b + a
    }

    function m(a, b, c, d) {
        var e = a - c, f = b - d;
        return Math.sqrt(Math.pow(e, 2) + Math.pow(f, 2))
    }

    function n(a) {
        var b = {}, c = a.substring(1).split("&");
        if (c.length) {
            for (var d = 0; d < c.length; d++) {
                var e = c[d].split("=");
                b[decodeURI(e[0])] = decodeURI(e[1])
            }
            return b
        }
    }

    function o(a) {
        a = a.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var b = new RegExp("[\\?&]" + a + "=([^&#]*)"), c = b.exec(location.search);
        return null === c ? "" : decodeURIComponent(c[1].replace(/\+/g, " "))
    }

    function p() {
        var a = $(".qr-code-overlay");
        $(".sharing-icon.wechat").on("click", function (b) {
            b.preventDefault(), a.hasClass("active") || a.addClass("active")
        }), $(document).on("click", ".bd_weixin_popup_close", function () {
            a.hasClass("active") && a.removeClass("active")
        })
    }

    var q = {
        mobile: a.Modernizr.touch,
        iOS: /(iPad|iPhone|iPod)/g.test(navigator.userAgent),
        wechat: /MicroMessenger/g.test(navigator.userAgent),
        clickEvent: a.Modernizr.touch ? "touchstart" : "click",
        mousedownEvent: a.Modernizr.touch ? "touchstart" : "mousedown",
        mousemoveEvent: a.Modernizr.touch ? "touchmove" : "mousemove",
        mouseupEvent: a.Modernizr.touch ? "touchend" : "mouseup"
    };
    window.requestAnimFrame = function () {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (a) {
                window.setTimeout(a, 1e3 / 60)
            }
    }();
    var r = function (a, b) {
        if ("number" == typeof a && "number" == typeof b)this.x = a, this.y = b; else {
            if (!(a instanceof r && 1 === arguments.length))throw new Error("Invalid arguments" + a + "," + b);
            this.x = a.x, this.y = a.y
        }
    };
    r.prototype.sub = function (a) {
        return new r(this.x - a.x, this.y - a.y)
    }, r.prototype.add = function (a) {
        return new r(this.x + a.x, this.y + a.y)
    }, r.prototype.distance = function (a) {
        var b = this.x - a.x, c = this.y - a.y;
        return Math.sqrt(b * b + c * c)
    }, r.prototype.multNew = function (a) {
        return new r(this.x * a, this.y * a)
    }, r.prototype.magnitude = function () {
        return Math.sqrt(this.x * this.x + this.y * this.y)
    }, r.prototype.round = function () {
        this.x = Math.round(10 * this.x) / 10, this.y = Math.round(10 * this.y) / 10
    }, r.prototype.toString = function () {
        return "x:" + this.x + ",y:" + this.y + "]"
    };
    var s = {
        roundTwo: b,
        randomCalc: c,
        randomCalc2: d,
        random: e,
        rnd: l,
        randomColor: g,
        randomFromList: f,
        minVal: h,
        maxVal: i,
        betweenVal: j,
        calculateDistance: m,
        extractParameters: n,
        getUrlParameterByName: o,
        showQRcodeOverlay: p,
        Vector: r,
        noop: function () {
        }
    };
    a.client = q, a.budCNY = {
        util: s,
        client: q,
        parametersUrl: n(window.location.search),
        parametersFromHash: n(window.location.hash),
        config: {
            fpsMeter: !1,
			
            gatewayEndPoint: "http://bud-cny-238862581.ap-northeast-1.elb.amazonaws.com",
            fontServerUrl: "http://bud-font-947736416.ap-northeast-1.elb.amazonaws.com/api/font/",
            wechat: {
                appId: "",
                imgUrl: $(".sharing-poster").attr("src") ? window.location.origin + "/" + $(".sharing-poster").attr("src") : null,
                friendDesc: "想要一生一次登上纽约时代广场大屏幕? 现在就许个最闪新年梦，让全世界为你的梦想举杯！",
                friendTitle: "我刚许的新年梦，将高调登上纽约时代广场！快来观望",
                momentsDesc: "",
                momentsTitle: "我刚许的新年梦，将高调登上纽约时代广场！快来观望",
                genericTitle: "我刚许的新年梦，将高调登上纽约时代广场！快来观望"
            }
        }
    }
}(window), function () {
    "use strict";
    function a() {
        this.selector = !1, this.count = 0, a.prototype.init = function (a, b) {
            this.selector = a, this.style = document.createElement("style"), this.style.setAttribute("type", "text/css"), document.getElementsByTagName("head")[0].appendChild(this.style), this.serverUrl = b
        }, a.prototype.createDynamicFont = function (a, b, c, d, e, f, g) {
            var h = c.join() + "BESbswy";
            this.style.innerHTML += '@font-face {font-family:"' + a + '";src: url("' + this.serverUrl + b + ".ttf?text=" + window.encodeURIComponent(h) + '") format("truetype");};\n', window.WebFont.load({
                custom: {
                    families: [a],
                    testStrings: {fontFamily: h}
                }, active: d, inactive: e, fontactive: f, fontinactive: g, timeout: 1e3
            })
        }
    }

    window.budCNY.FontManager = a
}(window), function () {
    "use strict";
    function a(a) {
        return a.bdUrl = window.budCNY.config.sharingUrl, a
    }

    window._bd_share_config = {
        share: [{
            tag: "share_1",
            bdText: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdDesc: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdPic: window.budCNY.config.wechat.imgUrl,
            bdSize: 32
        }, {
            tag: "share_2",
            bdText: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdDesc: "想要一生一次登上纽约时代广场大屏幕？现在就许个最闪新年梦，让全世界为你的梦想举杯！ #百威为梦想举杯#",
            bdPic: window.budCNY.config.wechat.imgUrl,
            onBeforeClick: function (b, c) {
                return a(c), c
            },
            bdSize: 32
        }]
    };
    var b = window._bd_share_config.share.reduce(function (a, b) {
        return a[b.tag] = b, a
    }, {});
    $.ajax({url: "http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion", dataType: "script", cache: !1});
    var c = $(".sharing-icon.qzone");
    c.attr("title", "分享到QQ空间"), c.on("click", function (a) {
        a.preventDefault();
        var c = this, d = b[c.parentElement.dataset.tag] || {};
        d.onBeforeClick && (d = d.onBeforeClick("qzone", d)), d.bdSite = d.bdUrl || window.location.href, d.bdText = "百威为梦想举杯";
        var e = {url: d.bdSite, title: d.bdText, summary: d.bdDesc, site: d.bdSite, pics: d.bdPic}, f = [];
        for (var g in e)f.push(g + "=" + window.encodeURIComponent(e[g] || ""));
        var h = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?";
        window.budCNY.client.mobile && (h = "http://openmobile.qq.com/oauth2.0/m_jump?page=qzshare.html&loginpage=loginindex.html&logintype=qzone&"), window.open(h + f.join("&"))
    })
}(window), function (a) {
    "use strict";
    function b(a, b) {
        for (var c, d = k.length, e = 0; d > e; e++)c = k[e], c[a] && c[a](b)
    }

    function c(a) {
        switch (a.err_msg) {
            case"send_app_msg:cancel":
                b("wechat.menu.share.appmessage", ["cancel"]);
                break;
            case"send_app_msg:ok":
            case"send_app_msg:confirm":
                b("wechat.menu.share.appmessage", ["ok"])
        }
    }

    function d(a) {
        switch (a.err_msg) {
            case"share_timeline:cancel":
                b("wechat.menu.share.timeline", ["cancel"]);
                break;
            case"share_timeline:ok":
            case"share_timeline:confirm":
                b("wechat.menu.share.timeline", ["ok"])
        }
    }

    function e(a) {
        switch (a.err_msg) {
            case"share_weibo:cancel":
                b("wechat.menu.share.weibo", ["cancel"]);
                break;
            case"share_weibo:ok":
            case"share_weibo:confirm":
                b("wechat.menu.share.weibo", ["ok"])
        }
    }

    function f() {
        window.WeixinJSBridge.on("menu:share:appmessage", function () {
            var a = j.link || window.location.href.replace(/(&|\?)fromSharing=[^&]*/, "");
            window.WeixinJSBridge.invoke("sendAppMessage", {
                appid: j.appId,
                img_url: j.imgUrl,
                img_width: "120",
                img_height: "120",
                link: a,
                desc: j.friendDesc,
                title: j.friendTitle
            }, c), b("wechat.menu.share.appmessage", ["click", "url=" + a])
        }), window.WeixinJSBridge.on("menu:share:timeline", function () {
            var a = j.link || window.location.href.replace(/(&|\?)fromSharing=[^&]*/, "");
            window.WeixinJSBridge.invoke("shareTimeline", {
                img_url: j.imgUrl,
                img_width: "120",
                img_height: "120",
                link: a,
                desc: j.momentsDesc,
                title: j.momentsTitle
            }, d), b("wechat.menu.share.timeline", ["click", "url=" + a])
        }), window.WeixinJSBridge.on("menu:share:weibo", function () {
            var a = j.link || window.location.href.replace(/(&|\?)fromSharing=[^&]*/, "");
            window.WeixinJSBridge.invoke("shareWeibo", {
                content: j.friendTitle,
                url: a
            }, e), b("wechat.menu.share.weibo", ["click", "url=" + a])
        })
    }

    function g() {
        window.budCNY.client.wechat = !0, b("bridgeReady"), f()
    }

    function h(a) {
        k.push(a)
    }

    function i(a) {
        var b = k.indexOf(a);
        -1 !== b && k.splice(b, 1)
    }

    var j = a.budCNY.config.wechat, k = [];
    "undefined" == typeof WeixinJSBridge ? document.addEventListener ? document.addEventListener("WeixinJSBridgeReady", g, !1) : document.attachEvent && (document.attachEvent("WeixinJSBridgeReady", g), document.attachEvent("onWeixinJSBridgeReady", g)) : g(), window.budCNY.wechat = {
        registerListener: h,
        unregisterListener: i
    }
}(window), function (a) {
    "use strict";
    function b() {
        a._gaq = a._gaq || [], a._gaq.push(["_setAccount", "UA-52397263-1"]), a._gaq.push(["_trackPageview"])
    }

    function c() {
        a._CiQ10406 = window._CiQ10406 || []
    }

    function d() {
        b(), c(), e()
    }

    function e() {
        $("body").on(window.budCNY.client.clickEvent || "click", "[data-analytics]", function () {
            var a = $(this).attr("data-analytics");
            void 0 !== a && h(a, ["clicked"])
        })
    }

    function f(b, c) {
        var d = ["_trackEvent", b].concat(c);
        a._gaq.push(d)
    }

    function g(b, c) {
        Array.isArray(c) || (c = [c]);
        var d = b + "." + c.join(".");
        a._CiQ10406.push(["_trackEvent", {
            type: 1,
            labels: [{"按钮名称": d}],
            values: [{"数量": 1}]
        }]), a.CClickiV3 && a.CClickiV3[10406] && a.CClickiV3[10406]._flushObserver && a.CClickiV3[10406]._flushObserver(a.budCNY.util.noop)
    }

    function h(a, b) {
        f(a, b), g(a, b)
    }

    function i() {
        d()
    }

    i.prototype = {sendEvent: h}, a.budCNY.Analytics = i
}(window), function (a, b) {
    "use strict";
    function c(a, b, c, d) {
        this.running = !0, this.name = b, this.context = a, this.timeBased = !1 || c, this.compositeFrames = [], this.compressedFrames = [], this.maxFrameNumber = 0, this.onFinish = [], this.startTime = null, this.motionBlurLength = d || 0, this.lastFrame = 0
    }

    function d() {
        this.elementPositions = []
    }

    function e() {
        this.width = 0, this.height = 0, this.imageData = null
    }

    function f(a, b, c) {
        this.x = b, this.y = c, this.frameElement = a
    }

    function g() {
        this.size = 1, this.gravity = new p(0, .004)
    }

    function h() {
        this.size = 1, this.gravity = new p(0, .003)
    }

    function i(a) {
        this.duration = a, this.size = 1, this.colours = ["255,255,255", "255,255,255", "255,255,255", "255,255,255", "255,255,255", "215,0,0"]
    }

    function j() {
        this.size = 1, this.colors = ["white", "white", "red", "red"], this.gravity = new p(0, 0)
    }

    function k(a, b, c, d, e, f) {
        this.startingPosition = new p(a), this.dtl = d || 255, this.opacity = 1, this.size = c || 1, this.behaviour = e, this.appearance = f, this.originalVelocity = b
    }

    function l(a, b, c, d, e) {
        this.name = "DefaultExplosion", this.numParticles = Math.floor(a * b * .009), this.particles = [], this.pad = document.createElement("canvas"), this.pad.width = a, this.pad.height = b, this.duration = c, this.context = this.pad.getContext("2d"), this.behaviour = d, this.appearance = e || new i(c), this.init()
    }

    function m(a) {
        r && (a.save(), a.beginPath(), a.strokeStyle = "white", a.lineJoin = "bevel", a.lineWidth = 1, a.moveTo(0, 0), a.lineTo(a.canvas.width, 0), a.lineTo(a.canvas.width, a.canvas.height), a.lineTo(0, a.canvas.height), a.lineTo(0, 0), a.stroke(), a.restore())
    }

    function n() {
    }

    function o() {
        var c = this;
        this.animator = new n, this.explosions = [], this.canvas = b.getElementById("fireworksCanvas"), this.context = this.canvas.getContext("2d"), this.listeners = [];
        var d = window.budCNY.config.fpsMeter;
        d && d.showFps().show(), this.cw = a.innerWidth, this.ch = a.innerHeight, this.canvas.width = this.cw, this.canvas.height = this.ch, this.width = this.canvas.width, this.height = this.canvas.height, this.count = 0, $(window).on("resize", function () {
            c.cw = a.innerWidth, c.ch = a.innerHeight, c.canvas.width = c.cw, c.canvas.height = c.ch, c.width = c.canvas.width, c.height = c.canvas.height
        })
    }

    var p = a.budCNY.util.Vector, q = 5e3;
    c.prototype._getStartTime = function () {
        return this.startTime || (this.startTime = new Date), this.startTime
    }, c.prototype.listenOnFinish = function (a) {
        this.onFinish.push(a)
    }, c.prototype._getFrameFromTime = function (a) {
        var b = a || 0, c = this._getStartTime().getTime(), d = (new Date).getTime() + 0 * b, e = Math.floor((d - c) / 45);
        return e
    }, c.prototype.playLoop = function () {
        this.drawFrame(0, !0, !1)
    }, c.prototype.playOnce = function () {
        this.drawFrame(0, !1, !1)
    }, c.prototype.compress = function (a) {
        var b = this, c = function () {
            b.compositeFrames = b.compressedFrames, a()
        };
        this.listenOnFinish(c), this.drawFrame(0, !1, !0)
    }, c.prototype.addFrame = function (a) {
        this.compositeFrames.push(a), this.maxFrameNumber++
    }, c.prototype.addFrameAt = function (a, b) {
        this.compositeFrames[a] = b, a > this.maxFrameNumber && (this.maxFrameNumber = a)
    }, c.prototype.notifyListeners = function () {
        for (var a, b = this, c = function (a) {
            return function () {
                a()
            }
        }, d = 0; d < this.onFinish.length; d++) {
            a = b.onFinish[d];
            var e = c(a);
            setTimeout(e, 0)
        }
        this.onFinish = []
    }, c.prototype.addAnimationFromNow = function (a, b, c, d) {
        var e = 0;
        this.timeBased ? this.startTime && (e = this._getFrameFromTime()) : e = this.lastFrame, this.addAnimationAtXYT(a, b, c, e + d + 1)
    }, c.prototype.addAnimationAtXYT = function (a, b, c, e) {
        b = Math.floor(b - a.context.canvas.width / 2), c = Math.floor(c - a.context.canvas.height / 2);
        for (var f = null, g = null, h = e; h < a.compositeFrames.length + e; h++) {
            if (f = a.compositeFrames[h - e], !f instanceof d)throw new Error("should be a CompositeFrame ");
            g = this.compositeFrames[h], g || (g = new d, this.compositeFrames[h] = g), g.merge(f, b, c), h > this.maxFrameNumber && (this.maxFrameNumber = h)
        }
    }, c.prototype.drawFrame = function (b, c, d, e) {
        if (this.running)try {
            e = e || a.requestAnimFrame;
            var f = b;
            this.timeBased && (f = this._getFrameFromTime(b)), this.lastFrame = f, this.context.clearRect(0, 0, this.context.canvas.width, this.context.canvas.height);
            var g = window.budCNY.config.fpsMeter;
            g && g.tickStart();
            var h, i = this;
            if (f < this.maxFrameNumber) {
                for (var j = f; j >= f - this.motionBlurLength && j >= 0; j--)h = this.compositeFrames[j], h && h.draw(this.context);
                d && this.snapshotFrame(f), e(function () {
                    i.drawFrame(f + 1, c, d, e)
                })
            } else this.startTime = null, c ? e(function () {
                i.drawFrame(0, c, d, e)
            }) : (this.running = !1, this.onFinish && this.notifyListeners());
            g && g.tick()
        } catch (k) {
            console.log(k)
        }
    }, c.prototype.snapshotFrame = function (a) {
        var b = new d, c = new e;
        c.fromContext(this.context, 0, 0, this.context.canvas.width, this.context.canvas.height), b.addFrameElement(c, 0, 0), this.compressedFrames[a] = b
    }, d.prototype.draw = function (a) {
        for (var b, c = 0; c < this.elementPositions.length; c++)b = this.elementPositions[c], b.frameElement.drawAt(a, b.x, b.y)
    }, d.prototype.merge = function (a, b, c) {
        if (!a instanceof d)throw new Error("should be a CompositeFrame");
        if (a && a.elementPositions && this.elementPositions)for (var e = null, f = 0; f < a.elementPositions.length; f++)e = a.elementPositions[f], this.elementPositions.push(e.translate(b, c))
    }, d.prototype.addFrameElement = function (a, b, c) {
        if (!c instanceof e)throw new Error("Invalid Frame Element");
        var d = new f(a, b, c);
        this.elementPositions.push(d)
    }, e.prototype.fromContext = function (a, b, c, d, e) {
        var f = this;
        f.imageLoaded = !1, this.imageData = a.canvas.toDataURL(), this.width = d, this.height = e;
        var g = new Image;
        g.src = this.imageData, this.img = g
    }, e.prototype.drawAt = function (a, b, c) {
        a.drawImage(this.img, b, c, this.width, this.height)
    }, f.prototype.translate = function (a, b) {
        return new f(this.frameElement, this.x + a, this.y + b)
    }, g.prototype.calcPosition = function (a, b) {
        var c = this.gravity.multNew(a * a);
        return b.startingPosition.add(b.originalVelocity.multNew(a)).add(c)
    }, h.prototype.calcPosition = function (a, b) {
        var c, d = this.gravity.multNew(3 * a * 3 * a), e = a, f = 15;
        c = f - f / ((e + 1) * (e + 1) * (e + 1) * (e + 1));
        var g = c * b.originalVelocity.x * a, h = c * b.originalVelocity.y * a;
        return b.startingPosition.add(new p(g, h)).add(d)
    }, h.prototype.expOut = function (a) {
        return 1 - 1 / ((a + 1) * (a + 1) * (a + 1) * (a + 1))
    }, i.prototype.draw = function (b, c, d, e, f) {
        var g, h = .7 * d.dtl, i = .85 * this.duration, j = e.distance(d.startingPosition), k = f, l = a.budCNY.util.randomFromList(this.colours);
        h > j ? k = 1 : (k = a.budCNY.util.roundTwo(1 - (j - h) / (d.dtl - h)), 0 > k && (k = 0)), c > i && (k *= a.budCNY.util.roundTwo(1 - (c - i) / (this.duration - i)), 0 > k && (k = 0)), g = "rgba(" + l + "," + k + ")", b.fillStyle = g, b.fillRect(e.x - this.size / 2, e.y - this.size / 2, this.size, this.size)
    }, j.prototype.calcPosition = function (a, b) {
        var c = this.gravity.multNew(a * a);
        return b.startingPosition.add(b.originalVelocity.multNew(a)).add(c)
    }, k.prototype.drawAtTime = function (a, b) {
        var c = this.behaviour.calcPosition(b, this), d = this.behaviour.calcPosition(b - 1, this);
        a.save(), this.appearance.draw(a, b, this, c, d), a.restore()
    }, l.prototype.init = function () {
        for (var b = null, c = 0, d = .4, e = .1, f = 0; f < this.numParticles; f++)b = new k(new p(this.pad.width / 2, this.pad.height / 2), new p(a.budCNY.util.rnd(c, d) - c, a.budCNY.util.rnd(c, d) - c - e), 1, this.pad.width / 2, this.behaviour, this.appearance), this.particles.push(b);
        this.boundary(this.context)
    };
    var r = !1;
    l.prototype.boundary = m, n.prototype.animate = function (b, f) {
        for (var g = new c(b.context, "created by animator"), h = 0, i = function (a) {
            var c, f, h = new d, i = new e;
            for (b.context.clearRect(0, 0, b.pad.width, b.pad.height), b.boundary(b.context), c = 0; c < b.particles.length; c++)f = b.particles[c], f && f.drawAtTime(b.context, a);
            i.fromContext(b.context, 0, 0, b.pad.width, b.pad.height), h.addFrameElement(i, 0, 0), g.addFrameAt(a, h)
        }, j = function () {
            h++, h === b.duration && f(g)
        }, k = function (a, b) {
            return function () {
                i(a), b()
            }
        }, l = b.duration; l > 0; l--)a.requestAnimFrame(k(l, j));
        return g
    }, o.prototype.init = function (a, b) {
        var c = 2, d = 0;
        this.containerElementClass = a;
        var e = Math.floor($(document).width() / 4);
        e = e > 140 ? 140 : e;
        var f, g = this, i = function () {
            d++, d >= c || !k ? b() : (f = new l(e, 1.2 * e, 100, new h), g.animator.animate(f, m))
        }, j = new Date, k = !0, m = function (a) {
            g.explosions.push(a);
            var b = (new Date).getTime();
            q > b - j ? a.motionBlurLength = b - j > q / 2 ? 2 : 4 : (a.motionBlurLength = 0, k = !1), setTimeout(function () {
                a.compress(i)
            }, 0)
        };
        f = new l(e, 1.2 * e, 100, new h), this.animator.animate(f, m)
    }, o.prototype.playFirework = function (a) {
        var b = new c(this.context, "page anim " + this.count++, !0, 0);
        b.listenOnFinish(a);
        for (var d, e, f, g, h, i, j, k = $("." + this.containerElementClass).get(0), l = k.children, m = 0; m < l.length; m++)d = l[m], e = d.getBoundingClientRect().left, f = d.getBoundingClientRect().top, g = d.getBoundingClientRect().width, h = d.getBoundingClientRect().height, i = 2 * m, i > 1 && (i -= 2), j = m % this.explosions.length, b.addAnimationFromNow(this.explosions[j], e + g / 2, f + h / 2.2, i), b.playOnce()
    }, a.budCNY.MessageExplosionManager = new o
}(window, document), function (a) {
    "use strict";
    function b() {
        this.dreamsArray = [], this.currentDream = 0, this.messageInfos = $(".message-infos"), this.messageWrapper = $(".message-wrapper"), this.dreamIdWrapper = $(".dream-id"), this.dreamFromToWrapper = $(".dream-from-to"), this.moon = $(".landing-moon"), this.cloud1 = $(".cloud1"), this.cloud2 = $(".cloud2"), this.city = $(".city"), this.bg = $(".landing-bg"), this.landingContainer = $(".landing-container"), this.topArea = $(".top"), this.centerArea = $(".centered-area"), this.landscape = !1, b.prototype.intro = function () {
            e.to(this.moon, 2, {
                yPercent: -75,
                force3D: !0,
                ease: "Sine.easeInOut",
                delay: .5
            }), e.to(this.cloud1, 2, {
                yPercent: -90,
                force3D: !0,
                ease: "Sine.easeInOut",
                delay: .5
            }), e.to(this.cloud2, 2, {
                yPercent: -90,
                force3D: !0,
                ease: "Sine.easeInOut",
                delay: .5
            }), e.to(this.city, 2.5, {
                yPercent: -100,
                force3D: !0,
                ease: "Sine.easeInOut"
            }), e.to(this.bg, 2, {
                yPercent: -10,
                force3D: !0,
                ease: "Sine.easeInOut",
                delay: .5
            }), e.to(this.centerArea, 2, {alpha: 1, ease: "easeIn", delay: 2}), e.to(this.topArea, 2, {
                alpha: 1,
                ease: "easeIn",
                delay: .5
            }), e.to(this.topArea, 2, {
                yPercent: -100,
                force3D: !0,
                ease: "Sine.easeInOut",
                delay: .5,
                onComplete: function () {
                    a.budCNY.client.mobile ? d() : c()
                }
            })
        }, b.prototype.showShareIcon = function () {
            $(".social-icons .sharing-box").removeClass("hide"), e.to(".icon-1", .6, {
                alpha: 1,
                ease: "easeIn",
                delay: .2
            }), e.to(".icon-2", .6, {alpha: 1, ease: "easeIn", delay: .1}), e.to(".icon-3", .6, {
                alpha: 1,
                ease: "easeIn"
            })
        }, b.prototype.hideShareIcon = function () {
            e.to(".icon-3", .4, {
                alpha: 0, ease: "easeIn", delay: .2, onComplete: function () {
                    $(".social-icons .sharing-box").addClass("hide")
                }
            }), e.to(".icon-2", .4, {alpha: 0, ease: "easeIn", delay: .1}), e.to(".icon-1", .4, {
                alpha: 0,
                ease: "easeIn"
            })
        }, b.prototype.processDreams = function (b) {
            var c = this, d = new a.budCNY.FontManager;
            d.init($(".message-wrapper").get(0), a.budCNY.config.fontServerUrl);
            var f = (b.length > 10 ? b.slice(0, 9) : b).map(function (a) {
                return a.message
            }), g = !1, h = function () {
                g || (g = !0, e.to(".loader-landing", .4, {
                    alpha: 0, ease: "easeIn", onComplete: function () {
                        $(".loader-landing").addClass("hide"), c.nextDream()
                    }
                }))
            };
            d.createDynamicFont("budFont", "HYLingXinJ", f, h, h, h, h), c.formatDreams(b)
        }, b.prototype.getDreams = function () {
            var b = this;
			         
            //console.log( a.budCNY.config.gatewayEndPoint+ "/api/dream/gethighlight");
                 
            $.ajax({
                
				url: address,
                type: "GET",
                dataType: "jsonp",
                beforeSend: function () {
                    b.spriteAnim(".loader-sprite", 67, 45)
                },
                success: function (c) {
					console.log(c);
                    a.budCNY.MessageExplosionManager.init("message-wrapper", function () {
                        b.processDreams(c)
                    })
                }
            })
        }, b.prototype.formatDreams = function (a) {
            for (var b = a.length > 10 ? a.slice(0, 9) : a, c = 0; c < b.length; c++) {
                //var d = "新的一年";
                b[c].fromUser.split(" ").join("").length > 0 && b[c].toUser.split(" ").join("").length > 0 && (d = b[c].fromUser + "祝" + b[c].toUser ), b[c].fromUser.split(" ").join("").length > 0 && b[c].toUser.split(" ").join("").length < 1 && (d = b[c].fromUser ), b[c].fromUser.split(" ").join("").length < 1 && b[c].toUser.split(" ").join("").length > 0 && (d = "祝" + b[c].toUser);
                for (var e, f = b[c].message.trim().split(""), g = [], h = 0; h < f.length; h++)e = document.createElement("span"), e.setAttribute("class", "dreamChar"), e.innerHTML = f[h], g.push(e);
                var i = {message: g, fromToWish: d, idDream: b[c].uuid};
                this.dreamsArray.push(i)
            }
        }, b.prototype.nextDream = function () {
            var b = this;
            this.currentDream++, this.currentDream >= this.dreamsArray.length && (this.currentDream = 0), e.to(this.messageInfos, .4, {
                alpha: 0,
                ease: "easeIn"
            }), e.to(this.messageWrapper, .4, {
                alpha: 0, ease: "easeIn", onComplete: function () {
                    for (var c, d = 0, f = b.messageWrapper.get(0); f.firstChild;)f.removeChild(f.firstChild);
                    d = b.currentDream, b.dreamIdWrapper.text("梦想" + b.dreamsArray[d].idDream + "号"), b.dreamFromToWrapper.text(b.dreamsArray[d].fromToWish);
                    var g;
                    g = Math.floor(b.messageWrapper.width() / 13), g = g > 50 ? 50 : g;
                    for (var h = 0; h < b.dreamsArray[d].message.length; h++)c = b.dreamsArray[d].message[h], c.style.fontSize = "" + g + "px", f.appendChild(c);
                    e.set(f, {perspective: 1e3}), e.staggerFromTo(".dreamChar", .3, {
                        scale: "0",
                        force3D: "true"
                    }, {
                        scale: "1",
                        force3D: "true",
                        ease: "Expo.easeOut"
                    }, .1), a.budCNY.MessageExplosionManager.playFirework(function () {
                        b.nextDream()
                    })
                }
            }), e.to(this.messageInfos, .6, {
                alpha: 1,
                ease: "easeIn",
                delay: .6
            }), e.to(this.messageWrapper, .6, {alpha: 1, ease: "easeIn", delay: .6})
        }, b.prototype.spriteAnim = function (a, b, c) {
            var d = 0, f = 0;
            this.spriteInterval = setInterval(function () {
                d = -b * f, f++, e.set(a, {y: d, force3D: !0}), f === c && (f = 1)
            }, 30)
        }, b.prototype.rotateDevice = function () {
            90 === a.orientation || -90 === a.orientation ? (this.landscape = !0, $(a).height() < 550 && (this.landingContainer.hasClass("landscape") || this.landingContainer.addClass("landscape"))) : (this.landscape = !1, this.landingContainer.hasClass("landscape") && this.landingContainer.removeClass("landscape"))
        }
    }

    function c() {
        l.y = a.budCNY.util.roundTwo(.06 * (k / 1e3 - l.y + l.startY) + l.y), l.x = a.budCNY.util.roundTwo(.06 * (j / 1e3 - l.x) + l.x), m.y = a.budCNY.util.roundTwo(.06 * (k / 100 - m.y + m.startY) + m.y), m.x = a.budCNY.util.roundTwo(.08 * (j / 100 - m.x + m.startX) + m.x), n.y = a.budCNY.util.roundTwo(.06 * (k / 200 - n.y + m.startY) + n.y), n.x = a.budCNY.util.roundTwo(.06 * (j / 200 - n.x + m.startX) + n.x), o.y = a.budCNY.util.roundTwo(.06 * (k / 3250 - o.y) + o.y), o.x = a.budCNY.util.roundTwo(.06 * (j / 1250 - o.x) + o.x), o.rotateY = a.budCNY.util.roundTwo(.06 * (k / 250 - o.rotateY) + o.rotateY), o.rotateX = a.budCNY.util.roundTwo(.06 * (j / 300 - o.rotateX) + o.rotateX), e.set(o.selector, {
            yPercent: o.y,
            xPercent: o.x,
            rotationX: o.rotateY,
            rotationY: o.rotateX,
            force3D: !0
        }), e.set(l.selector, {yPercent: l.y, xPercent: l.x, force3D: !0}), e.set(m.selector, {
            yPercent: m.y,
            xPercent: m.x,
            force3D: !0
        }), e.set(n.selector, {yPercent: n.y, xPercent: n.x, force3D: !0}), a.requestAnimFrame(c)
    }

    function d() {
        l.y = a.budCNY.util.roundTwo(.06 * (k / 500 - l.y + l.startY) + l.y), l.x = a.budCNY.util.roundTwo(.06 * (j / 500 - l.x) + l.x), m.y = a.budCNY.util.roundTwo(.06 * (k / 100 - m.y + m.startY) + m.y), m.x = a.budCNY.util.roundTwo(.06 * (j / 100 - m.x + m.startX) + m.x), n.y = a.budCNY.util.roundTwo(.06 * (k / 200 - n.y + m.startY) + n.y), n.x = a.budCNY.util.roundTwo(.06 * (j / 200 - n.x + m.startX) + n.x), o.x = a.budCNY.util.roundTwo(.06 * (j / 125 - o.x) + o.x), o.rotateX = a.budCNY.util.roundTwo(.06 * (j / 35 - o.rotateX) + o.rotateX), e.set(o.selector, {
            xPercent: o.x,
            rotationY: o.rotateX,
            force3D: !0
        }), e.set(l.selector, {yPercent: l.y, xPercent: l.x, force3D: !0}), e.set(m.selector, {
            yPercent: m.y,
            xPercent: m.x,
            force3D: !0
        }), e.set(n.selector, {yPercent: n.y, xPercent: n.x, force3D: !0}), a.requestAnimFrame(d)
    }

    var e = a.TweenMax, f = new b;
    f.intro(), f.getDreams();
    var g = $(a).height() / 2, h = $(a).width() / 2;
    $(a).on("resize", function () {
        g = $(a).height() / 2, h = $(a).width() / 2
    });
    var i = a.budCNY.client.iOS ? 1 : -1, j = 0, k = 0;
    a.budCNY.client.mobile ? a.DeviceMotionEvent && (a.ondevicemotion = function (a) {
        a.preventDefault(), j = f.landscape ? (a.accelerationIncludingGravity.y || a.acceleration.y) * i * 150 : (a.accelerationIncludingGravity.x || a.acceleration.x) * i * 150
    }) : $(a).on("mousemove", function (a) {
        j = a.pageX - h, k = a.pageY - g
    }), a.budCNY.util.showQRcodeOverlay(), $("#button-create").on("click", function () {
        var b, c = a.budCNY.util.getUrlParameterByName("channel");
        b = a.budCNY.util.getUrlParameterByName("channel").length > 0 ? "dreams.html?channel=" + c : "dreams.html", a.location.href = b
    }), $("#share-btn").on("click", function () {
        $(".social-icons .sharing-box").hasClass("hide") ? (f.showShareIcon(), p.sendEvent("landing.share", "clicked")) : f.hideShareIcon()
    }), a.addEventListener("orientationchange", function () {
        f.rotateDevice()
    }, !1), f.rotateDevice();
    var l = {selector: $(".city"), y: -100, startY: -100, x: 0}, m = {
        selector: $(".cloud1"),
        y: -90,
        startY: -90,
        x: 0,
        startX: 0
    }, n = {selector: $(".cloud2"), y: -90, startY: -90, x: 0, startX: 10}, o = {
        selector: $(".rotate-area"),
        rotateY: 0,
        rotateX: 0,
        y: 0,
        x: 0
    }, p = {};
    p.sendEvent = a.budCNY.util.noop, a.budCNY.Analytics && (p = new a.budCNY.Analytics);
    var q = a.budCNY.wechat;
    q.registerListener({
        "wechat.menu.share.appmessage": function (a) {
            p.sendEvent("wechat.menu.share.appmessage", a)
        }, "wechat.menu.share.timeline": function (a) {
            p.sendEvent("wechat.menu.share.timeline", a)
        }, "wechat.menu.share.weibo": function (a) {
            p.sendEvent("wechat.menu.share.weibo", a)
        }
    })
}(window);
/*! running-money-frontend - v1.0.0 - 2015-05-05 */
!function (a, b) {
    function c(a) {
        return function (b) {
            return {}.toString.call(b) == "[object " + a + "]"
        }
    }

    function d() {
        return A++
    }

    function e(a) {
        return a.match(D)[0]
    }

    function f(a) {
        for (a = a.replace(E, "/"); a.match(F);)a = a.replace(F, "/");
        return a = a.replace(G, "$1/")
    }

    function g(a) {
        var b = a.length - 1, c = a.charAt(b);
        return "#" === c ? a.substring(0, b) : ".js" === a.substring(b - 2) || a.indexOf("?") > 0 || ".css" === a.substring(b - 3) || "/" === c ? a : a + ".js"
    }

    function h(a) {
        var b = v.alias;
        return b && x(b[a]) ? b[a] : a
    }

    function i(a) {
        var b, c = v.paths;
        return c && (b = a.match(H)) && x(c[b[1]]) && (a = c[b[1]] + b[2]), a
    }

    function j(a) {
        var b = v.vars;
        return b && a.indexOf("{") > -1 && (a = a.replace(I, function (a, c) {
            return x(b[c]) ? b[c] : a
        })), a
    }

    function k(a) {
        var b = v.map, c = a;
        if (b)for (var d = 0, e = b.length; e > d; d++) {
            var f = b[d];
            if (c = z(f) ? f(a) || a : a.replace(f[0], f[1]), c !== a)break
        }
        return c
    }

    function l(a, b) {
        var c, d = a.charAt(0);
        if (J.test(a))c = a; else if ("." === d)c = f((b ? e(b) : v.cwd) + a); else if ("/" === d) {
            var g = v.cwd.match(K);
            c = g ? g[0] + a.substring(1) : a
        } else c = v.base + a;
        return 0 === c.indexOf("//") && (c = location.protocol + c), c
    }

    function m(a, b) {
        if (!a)return "";
        a = h(a), a = i(a), a = j(a), a = g(a);
        var c = l(a, b);
        return c = k(c)
    }

    function n(a) {
        return a.hasAttribute ? a.src : a.getAttribute("src", 4)
    }

    function o(a, b, c) {
        var d = U.test(a), e = L.createElement(d ? "link" : "script");
        if (c) {
            var f = z(c) ? c(a) : c;
            f && (e.charset = f)
        }
        p(e, b, d, a), d ? (e.rel = "stylesheet", e.href = a) : (e.async = !0, e.src = a), Q = e, T ? S.insertBefore(e, T) : S.appendChild(e), Q = null
    }

    function p(a, c, d, e) {
        function f() {
            a.onload = a.onerror = a.onreadystatechange = null, d || v.debug || S.removeChild(a), a = null, c()
        }

        var g = "onload"in a;
        return !d || !V && g ? (g ? (a.onload = f, a.onerror = function () {
            C("error", {uri: e, node: a}), f()
        }) : a.onreadystatechange = function () {
            /loaded|complete/.test(a.readyState) && f()
        }, b) : (setTimeout(function () {
            q(a, c)
        }, 1), b)
    }

    function q(a, b) {
        var c, d = a.sheet;
        if (V)d && (c = !0); else if (d)try {
            d.cssRules && (c = !0)
        } catch (e) {
            "NS_ERROR_DOM_SECURITY_ERR" === e.name && (c = !0)
        }
        setTimeout(function () {
            c ? b() : q(a, b)
        }, 20)
    }

    function r() {
        if (Q)return Q;
        if (R && "interactive" === R.readyState)return R;
        for (var a = S.getElementsByTagName("script"), b = a.length - 1; b >= 0; b--) {
            var c = a[b];
            if ("interactive" === c.readyState)return R = c
        }
    }

    function s(a) {
        var b = [];
        return a.replace(Y, "").replace(X, function (a, c, d) {
            d && b.push(d)
        }), b
    }

    function t(a, b) {
        this.uri = a, this.dependencies = b || [], this.exports = null, this.status = 0, this._waitings = {}, this._remain = 0
    }

    if (!a.seajs) {
        var u = a.seajs = {version: "2.2.0"}, v = u.data = {}, w = c("Object"), x = c("String"), y = Array.isArray || c("Array"), z = c("Function"), A = 0, B = v.events = {};
        u.on = function (a, b) {
            var c = B[a] || (B[a] = []);
            return c.push(b), u
        }, u.off = function (a, b) {
            if (!a && !b)return B = v.events = {}, u;
            var c = B[a];
            if (c)if (b)for (var d = c.length - 1; d >= 0; d--)c[d] === b && c.splice(d, 1); else delete B[a];
            return u
        };
        var C = u.emit = function (a, b) {
            var c, d = B[a];
            if (d)for (d = d.slice(); c = d.shift();)c(b);
            return u
        }, D = /[^?#]*\//, E = /\/\.\//g, F = /\/[^/]+\/\.\.\//, G = /([^:/])\/\//g, H = /^([^/:]+)(\/.+)$/, I = /{([^{]+)}/g, J = /^\/\/.|:\//, K = /^.*?\/\/.*?\//, L = document, M = e(L.URL), N = L.scripts, O = L.getElementById("seajsnode") || N[N.length - 1], P = e(n(O) || M);
        u.resolve = m;
        var Q, R, S = L.getElementsByTagName("head")[0] || L.documentElement, T = S.getElementsByTagName("base")[0], U = /\.css(?:\?|$)/i, V = +navigator.userAgent.replace(/.*AppleWebKit\/(\d+)\..*/, "$1") < 536;
        u.request = o;
        var W, X = /"(?:\\"|[^"])*"|'(?:\\'|[^'])*'|\/\*[\S\s]*?\*\/|\/(?:\\\/|[^\/\r\n])+\/(?=[^\/])|\/\/.*|\.\s*require|(?:^|[^$])\brequire\s*\(\s*(["'])(.+?)\1\s*\)/g, Y = /\\\\/g, Z = u.cache = {}, $ = {}, _ = {}, aa = {}, ba = t.STATUS = {
            FETCHING: 1,
            SAVED: 2,
            LOADING: 3,
            LOADED: 4,
            EXECUTING: 5,
            EXECUTED: 6
        };
        t.prototype.resolve = function () {
            for (var a = this, b = a.dependencies, c = [], d = 0, e = b.length; e > d; d++)c[d] = t.resolve(b[d], a.uri);
            return c
        }, t.prototype.load = function () {
            var a = this;
            if (!(a.status >= ba.LOADING)) {
                a.status = ba.LOADING;
                var c = a.resolve();
                C("load", c);
                for (var d, e = a._remain = c.length, f = 0; e > f; f++)d = t.get(c[f]), d.status < ba.LOADED ? d._waitings[a.uri] = (d._waitings[a.uri] || 0) + 1 : a._remain--;
                if (0 === a._remain)return a.onload(), b;
                var g = {};
                for (f = 0; e > f; f++)d = Z[c[f]], d.status < ba.FETCHING ? d.fetch(g) : d.status === ba.SAVED && d.load();
                for (var h in g)g.hasOwnProperty(h) && g[h]()
            }
        }, t.prototype.onload = function () {
            var a = this;
            a.status = ba.LOADED, a.callback && a.callback();
            var b, c, d = a._waitings;
            for (b in d)d.hasOwnProperty(b) && (c = Z[b], c._remain -= d[b], 0 === c._remain && c.onload());
            delete a._waitings, delete a._remain
        }, t.prototype.fetch = function (a) {
            function c() {
                u.request(g.requestUri, g.onRequest, g.charset)
            }

            function d() {
                delete $[h], _[h] = !0, W && (t.save(f, W), W = null);
                var a, b = aa[h];
                for (delete aa[h]; a = b.shift();)a.load()
            }

            var e = this, f = e.uri;
            e.status = ba.FETCHING;
            var g = {uri: f};
            C("fetch", g);
            var h = g.requestUri || f;
            return !h || _[h] ? (e.load(), b) : $[h] ? (aa[h].push(e), b) : ($[h] = !0, aa[h] = [e], C("request", g = {
                uri: f,
                requestUri: h,
                onRequest: d,
                charset: v.charset
            }), g.requested || (a ? a[g.requestUri] = c : c()), b)
        }, t.prototype.exec = function () {
            function a(b) {
                return t.get(a.resolve(b)).exec()
            }

            var c = this;
            if (c.status >= ba.EXECUTING)return c.exports;
            c.status = ba.EXECUTING;
            var e = c.uri;
            a.resolve = function (a) {
                return t.resolve(a, e)
            }, a.async = function (b, c) {
                return t.use(b, c, e + "_async_" + d()), a
            };
            var f = c.factory, g = z(f) ? f(a, c.exports = {}, c) : f;
            return g === b && (g = c.exports), delete c.factory, c.exports = g, c.status = ba.EXECUTED, C("exec", c), g
        }, t.resolve = function (a, b) {
            var c = {id: a, refUri: b};
            return C("resolve", c), c.uri || u.resolve(c.id, b)
        }, t.define = function (a, c, d) {
            var e = arguments.length;
            1 === e ? (d = a, a = b) : 2 === e && (d = c, y(a) ? (c = a, a = b) : c = b), !y(c) && z(d) && (c = s("" + d));
            var f = {id: a, uri: t.resolve(a), deps: c, factory: d};
            if (!f.uri && L.attachEvent) {
                var g = r();
                g && (f.uri = g.src)
            }
            C("define", f), f.uri ? t.save(f.uri, f) : W = f
        }, t.save = function (a, b) {
            var c = t.get(a);
            c.status < ba.SAVED && (c.id = b.id || a, c.dependencies = b.deps || [], c.factory = b.factory, c.status = ba.SAVED)
        }, t.get = function (a, b) {
            return Z[a] || (Z[a] = new t(a, b))
        }, t.use = function (b, c, d) {
            var e = t.get(d, y(b) ? b : [b]);
            e.callback = function () {
                for (var b = [], d = e.resolve(), f = 0, g = d.length; g > f; f++)b[f] = Z[d[f]].exec();
                c && c.apply(a, b), delete e.callback
            }, e.load()
        }, t.preload = function (a) {
            var b = v.preload, c = b.length;
            c ? t.use(b, function () {
                b.splice(0, c), t.preload(a)
            }, v.cwd + "_preload_" + d()) : a()
        }, u.use = function (a, b) {
            return t.preload(function () {
                t.use(a, b, v.cwd + "_use_" + d())
            }), u
        }, t.define.cmd = {}, a.define = t.define, u.Module = t, v.fetchedList = _, v.cid = d, u.require = function (a) {
            var b = t.get(t.resolve(a));
            return b.status < ba.EXECUTING && b.exec(), b.exports
        };
        var ca = /^(.+?\/)(\?\?)?(seajs\/)+/;
        v.base = (P.match(ca) || ["", P])[1], v.dir = P, v.cwd = M, v.charset = "utf-8", v.preload = function () {
            var a = [], b = location.search.replace(/(seajs-\w+)(&|$)/g, "$1=1$2");
            return b += " " + L.cookie, b.replace(/(seajs-\w+)=1/g, function (b, c) {
                a.push(c)
            }), a
        }(), u.config = function (a) {
            for (var b in a) {
                var c = a[b], d = v[b];
                if (d && w(d))for (var e in c)d[e] = c[e]; else y(d) ? c = d.concat(c) : "base" === b && ("/" !== c.slice(-1) && (c += "/"), c = l(c)), v[b] = c
            }
            return C("config", a), u
        }
    }
}(this), !function (a, b) {
    "function" == typeof define && (define.amd || define.cmd) ? define("cmp/jweixin-1.0.0", [], function () {
        return b(a)
    }) : b(a, !0)
}(this, function (a, b) {
    function c(b, c, d) {
        a.WeixinJSBridge ? WeixinJSBridge.invoke(b, e(c), function (a) {
            g(b, a, d)
        }) : j(b, d)
    }

    function d(b, c, d) {
        a.WeixinJSBridge ? WeixinJSBridge.on(b, function (a) {
            d && d.trigger && d.trigger(a), g(b, a, c)
        }) : d ? j(b, d) : j(b, c)
    }

    function e(a) {
        return a = a || {}, a.appId = z.appId, a.verifyAppId = z.appId, a.verifySignType = "sha1", a.verifyTimestamp = z.timestamp + "", a.verifyNonceStr = z.nonceStr, a.verifySignature = z.signature, a
    }

    function f(a) {
        return {
            timeStamp: a.timestamp + "",
            nonceStr: a.nonceStr,
            "package": a["package"],
            paySign: a.paySign,
            signType: a.signType || "SHA1"
        }
    }

    function g(a, b, c) {
        var d, e, f;
        switch (delete b.err_code, delete b.err_desc, delete b.err_detail, d = b.errMsg, d || (d = b.err_msg, delete b.err_msg, d = h(a, d, c), b.errMsg = d), c = c || {}, c._complete && (c._complete(b), delete c._complete), d = b.errMsg || "", z.debug && !c.isInnerInvoke && alert(JSON.stringify(b)), e = d.indexOf(":"), f = d.substring(e + 1)) {
            case"ok":
                c.success && c.success(b);
                break;
            case"cancel":
                c.cancel && c.cancel(b);
                break;
            default:
                c.fail && c.fail(b)
        }
        c.complete && c.complete(b)
    }

    function h(a, b) {
        var c, d, e, f;
        if (b) {
            switch (c = b.indexOf(":"), a) {
                case o.config:
                    d = "config";
                    break;
                case o.openProductSpecificView:
                    d = "openProductSpecificView";
                    break;
                default:
                    d = b.substring(0, c), d = d.replace(/_/g, " "), d = d.replace(/\b\w+\b/g, function (a) {
                        return a.substring(0, 1).toUpperCase() + a.substring(1)
                    }), d = d.substring(0, 1).toLowerCase() + d.substring(1), d = d.replace(/ /g, ""), -1 != d.indexOf("Wcpay") && (d = d.replace("Wcpay", "WCPay")), e = p[d], e && (d = e)
            }
            f = b.substring(c + 1), "confirm" == f && (f = "ok"), "failed" == f && (f = "fail"), -1 != f.indexOf("failed_") && (f = f.substring(7)), -1 != f.indexOf("fail_") && (f = f.substring(5)), f = f.replace(/_/g, " "), f = f.toLowerCase(), ("access denied" == f || "no permission to execute" == f) && (f = "permission denied"), "config" == d && "function not exist" == f && (f = "ok"), b = d + ":" + f
        }
        return b
    }

    function i(a) {
        var b, c, d, e;
        if (a) {
            for (b = 0, c = a.length; c > b; ++b)d = a[b], e = o[d], e && (a[b] = e);
            return a
        }
    }

    function j(a, b) {
        if (z.debug && !b.isInnerInvoke) {
            var c = p[a];
            c && (a = c), b && b._complete && delete b._complete, console.log('"' + a + '",', b || "")
        }
    }

    function k() {
        if (!("6.0.2" > w || y.systemType < 0)) {
            var a = new Image;
            y.appId = z.appId, y.initTime = x.initEndTime - x.initStartTime, y.preVerifyTime = x.preVerifyEndTime - x.preVerifyStartTime, C.getNetworkType({
                isInnerInvoke: !0,
                success: function (b) {
                    y.networkType = b.networkType;
                    var c = "https://open.weixin.qq.com/sdk/report?v=" + y.version + "&o=" + y.isPreVerifyOk + "&s=" + y.systemType + "&c=" + y.clientVersion + "&a=" + y.appId + "&n=" + y.networkType + "&i=" + y.initTime + "&p=" + y.preVerifyTime + "&u=" + y.url;
                    a.src = c
                }
            })
        }
    }

    function l() {
        return (new Date).getTime()
    }

    function m(b) {
        t && (a.WeixinJSBridge ? b() : q.addEventListener && q.addEventListener("WeixinJSBridgeReady", b, !1))
    }

    function n() {
        C.invoke || (C.invoke = function (b, c, d) {
            a.WeixinJSBridge && WeixinJSBridge.invoke(b, e(c), d)
        }, C.on = function (b, c) {
            a.WeixinJSBridge && WeixinJSBridge.on(b, c)
        })
    }

    var o, p, q, r, s, t, u, v, w, x, y, z, A, B, C;
    return a.jWeixin ? void 0 : (o = {
        config: "preVerifyJSAPI",
        onMenuShareTimeline: "menu:share:timeline",
        onMenuShareAppMessage: "menu:share:appmessage",
        onMenuShareQQ: "menu:share:qq",
        onMenuShareWeibo: "menu:share:weiboApp",
        previewImage: "imagePreview",
        getLocation: "geoLocation",
        openProductSpecificView: "openProductViewWithPid",
        addCard: "batchAddCard",
        openCard: "batchViewCard",
        chooseWXPay: "getBrandWCPayRequest"
    }, p = function () {
        var a, b = {};
        for (a in o)b[o[a]] = a;
        return b
    }(), q = a.document, r = q.title, s = navigator.userAgent.toLowerCase(), t = -1 != s.indexOf("micromessenger"), u = -1 != s.indexOf("android"), v = -1 != s.indexOf("iphone") || -1 != s.indexOf("ipad"), w = function () {
        var a = s.match(/micromessenger\/(\d+\.\d+\.\d+)/) || s.match(/micromessenger\/(\d+\.\d+)/);
        return a ? a[1] : ""
    }(), x = {initStartTime: l(), initEndTime: 0, preVerifyStartTime: 0, preVerifyEndTime: 0}, y = {
        version: 1,
        appId: "",
        initTime: 0,
        preVerifyTime: 0,
        networkType: "",
        isPreVerifyOk: 1,
        systemType: v ? 1 : u ? 2 : -1,
        clientVersion: w,
        url: encodeURIComponent(location.href)
    }, z = {}, A = {_completes: []}, B = {state: 0, res: {}}, m(function () {
        x.initEndTime = l()
    }), C = {
        config: function (a) {
            z = a, j("config", a);
            var b = z.check === !1 ? !1 : !0;
            m(function () {
                var a, d, e;
                if (b)c(o.config, {verifyJsApiList: i(z.jsApiList)}, function () {
                    A._complete = function (a) {
                        x.preVerifyEndTime = l(), B.state = 1, B.res = a
                    }, A.success = function () {
                        y.isPreVerifyOk = 0
                    }, A.fail = function (a) {
                        A._fail ? A._fail(a) : B.state = -1
                    };
                    var a = A._completes;
                    return a.push(function () {
                        z.debug || k()
                    }), A.complete = function () {
                        for (var b = 0, c = a.length; c > b; ++b)a[b]();
                        A._completes = []
                    }, A
                }()), x.preVerifyStartTime = l(); else {
                    for (B.state = 1, a = A._completes, d = 0, e = a.length; e > d; ++d)a[d]();
                    A._completes = []
                }
            }), z.beta && n()
        }, ready: function (a) {
            0 != B.state ? a() : (A._completes.push(a), !t && z.debug && a())
        }, error: function (a) {
            "6.0.2" > w || (-1 == B.state ? a(B.res) : A._fail = a)
        }, checkJsApi: function (a) {
            var b = function (a) {
                var b, c, d = a.checkResult;
                for (b in d)c = p[b], c && (d[c] = d[b], delete d[b]);
                return a
            };
            c("checkJsApi", {jsApiList: i(a.jsApiList)}, function () {
                return a._complete = function (a) {
                    if (u) {
                        var c = a.checkResult;
                        c && (a.checkResult = JSON.parse(c))
                    }
                    a = b(a)
                }, a
            }())
        }, onMenuShareTimeline: function (a) {
            d(o.onMenuShareTimeline, {
                complete: function () {
                    c("shareTimeline", {
                        title: a.title || r,
                        desc: a.title || r,
                        img_url: a.imgUrl,
                        link: a.link || location.href
                    }, a)
                }
            }, a)
        }, onMenuShareAppMessage: function (a) {
            d(o.onMenuShareAppMessage, {
                complete: function () {
                    c("sendAppMessage", {
                        title: a.title || r,
                        desc: a.desc || "",
                        link: a.link || location.href,
                        img_url: a.imgUrl,
                        type: a.type || "link",
                        data_url: a.dataUrl || ""
                    }, a)
                }
            }, a)
        }, onMenuShareQQ: function (a) {
            d(o.onMenuShareQQ, {
                complete: function () {
                    c("shareQQ", {
                        title: a.title || r,
                        desc: a.desc || "",
                        img_url: a.imgUrl,
                        link: a.link || location.href
                    }, a)
                }
            }, a)
        }, onMenuShareWeibo: function (a) {
            d(o.onMenuShareWeibo, {
                complete: function () {
                    c("shareWeiboApp", {
                        title: a.title || r,
                        desc: a.desc || "",
                        img_url: a.imgUrl,
                        link: a.link || location.href
                    }, a)
                }
            }, a)
        }, startRecord: function (a) {
            c("startRecord", {}, a)
        }, stopRecord: function (a) {
            c("stopRecord", {}, a)
        }, onVoiceRecordEnd: function (a) {
            d("onVoiceRecordEnd", a)
        }, playVoice: function (a) {
            c("playVoice", {localId: a.localId}, a)
        }, pauseVoice: function (a) {
            c("pauseVoice", {localId: a.localId}, a)
        }, stopVoice: function (a) {
            c("stopVoice", {localId: a.localId}, a)
        }, onVoicePlayEnd: function (a) {
            d("onVoicePlayEnd", a)
        }, uploadVoice: function (a) {
            c("uploadVoice", {localId: a.localId, isShowProgressTips: 0 == a.isShowProgressTips ? 0 : 1}, a)
        }, downloadVoice: function (a) {
            c("downloadVoice", {serverId: a.serverId, isShowProgressTips: 0 == a.isShowProgressTips ? 0 : 1}, a)
        }, translateVoice: function (a) {
            c("translateVoice", {localId: a.localId, isShowProgressTips: 0 == a.isShowProgressTips ? 0 : 1}, a)
        }, chooseImage: function (a) {
            c("chooseImage", {
                scene: "1|2",
                count: a.count || 9,
                sizeType: a.sizeType || ["original", "compressed"]
            }, function () {
                return a._complete = function (a) {
                    if (u) {
                        var b = a.localIds;
                        b && (a.localIds = JSON.parse(b))
                    }
                }, a
            }())
        }, previewImage: function (a) {
            c(o.previewImage, {current: a.current, urls: a.urls}, a)
        }, uploadImage: function (a) {
            c("uploadImage", {localId: a.localId, isShowProgressTips: 0 == a.isShowProgressTips ? 0 : 1}, a)
        }, downloadImage: function (a) {
            c("downloadImage", {serverId: a.serverId, isShowProgressTips: 0 == a.isShowProgressTips ? 0 : 1}, a)
        }, getNetworkType: function (a) {
            var b = function (a) {
                var b, c, d, e = a.errMsg;
                if (a.errMsg = "getNetworkType:ok", b = a.subtype, delete a.subtype, b)a.networkType = b; else switch (c = e.indexOf(":"), d = e.substring(c + 1)) {
                    case"wifi":
                    case"edge":
                    case"wwan":
                        a.networkType = d;
                        break;
                    default:
                        a.errMsg = "getNetworkType:fail"
                }
                return a
            };
            c("getNetworkType", {}, function () {
                return a._complete = function (a) {
                    a = b(a)
                }, a
            }())
        }, openLocation: function (a) {
            c("openLocation", {
                latitude: a.latitude,
                longitude: a.longitude,
                name: a.name || "",
                address: a.address || "",
                scale: a.scale || 28,
                infoUrl: a.infoUrl || ""
            }, a)
        }, getLocation: function (a) {
            a = a || {}, c(o.getLocation, {type: a.type || "wgs84"}, function () {
                return a._complete = function (a) {
                    delete a.type
                }, a
            }())
        }, hideOptionMenu: function (a) {
            c("hideOptionMenu", {}, a)
        }, showOptionMenu: function (a) {
            c("showOptionMenu", {}, a)
        }, closeWindow: function (a) {
            a = a || {}, c("closeWindow", {immediate_close: a.immediateClose || 0}, a)
        }, hideMenuItems: function (a) {
            c("hideMenuItems", {menuList: a.menuList}, a)
        }, showMenuItems: function (a) {
            c("showMenuItems", {menuList: a.menuList}, a)
        }, hideAllNonBaseMenuItem: function (a) {
            c("hideAllNonBaseMenuItem", {}, a)
        }, showAllNonBaseMenuItem: function (a) {
            c("showAllNonBaseMenuItem", {}, a)
        }, scanQRCode: function (a) {
            a = a || {}, c("scanQRCode", {
                needResult: a.needResult || 0,
                scanType: a.scanType || ["qrCode", "barCode"]
            }, function () {
                return a._complete = function (a) {
                    var b, c;
                    v && (b = a.resultStr, b && (c = JSON.parse(b), a.resultStr = c && c.scan_code && c.scan_code.scan_result))
                }, a
            }())
        }, openProductSpecificView: function (a) {
            c(o.openProductSpecificView, {pid: a.productId, view_type: a.viewType || 0}, a)
        }, addCard: function (a) {
            var b, d, e, f, g = a.cardList, h = [];
            for (b = 0, d = g.length; d > b; ++b)e = g[b], f = {card_id: e.cardId, card_ext: e.cardExt}, h.push(f);
            c(o.addCard, {card_list: h}, function () {
                return a._complete = function (a) {
                    var b, c, d, e = a.card_list;
                    if (e) {
                        for (e = JSON.parse(e), b = 0, c = e.length; c > b; ++b)d = e[b], d.cardId = d.card_id, d.cardExt = d.card_ext, d.isSuccess = d.is_succ ? !0 : !1, delete d.card_id, delete d.card_ext, delete d.is_succ;
                        a.cardList = e, delete a.card_list
                    }
                }, a
            }())
        }, chooseCard: function (a) {
            c("chooseCard", {
                app_id: z.appId,
                location_id: a.shopId || "",
                sign_type: a.signType || "SHA1",
                card_id: a.cardId || "",
                card_type: a.cardType || "",
                card_sign: a.cardSign,
                time_stamp: a.timestamp + "",
                nonce_str: a.nonceStr
            }, function () {
                return a._complete = function (a) {
                    a.cardList = a.choose_card_info, delete a.choose_card_info
                }, a
            }())
        }, openCard: function (a) {
            var b, d, e, f, g = a.cardList, h = [];
            for (b = 0, d = g.length; d > b; ++b)e = g[b], f = {card_id: e.cardId, code: e.code}, h.push(f);
            c(o.openCard, {card_list: h}, a)
        }, chooseWXPay: function (a) {
            c(o.chooseWXPay, f(a), a)
        }
    }, b && (a.wx = a.jWeixin = C), C)
}), define("cmp/net", [], function (a, b) {
    "use strict";
    function c(a) {
        var b;
        return null == a ? b = String(a) : (b = Object.prototype.toString.call(a).toLowerCase(), b = b.substring(8, b.length - 1)), b
    }

    function d(a, b, d) {
        var e, f, g;
        if ("object" == typeof a)if (g = c(a), d = d || a, "array" === g || "arguments" === g || "nodelist" === g) {
            for (e = 0, f = a.length; f > e; e++)if (b.call(d, a[e], e, a) === !1)return
        } else for (e in a)if (a.hasOwnProperty(e) && b.call(d, a[e], e, a) === !1)return
    }

    function e() {
        var a = {};
        return d(arguments, function (b) {
            d(b, function (b, c) {
                a[c] = b
            })
        }), a
    }

    function f(a, b) {
        b = b || location.search;
        var c, d = b.indexOf("#");
        return d > 0 && (b = b.substr(0, d)), c = b.match(new RegExp("[?|&]" + encodeURIComponent(a) + "=([^&]*)(&|$)")), c ? decodeURIComponent(c[1]) : ""
    }

    function g(a, b) {
        return b && (a += (a.indexOf("?") < 0 ? "?" : "&") + b.replace(/^[?|&]+/, "")), a
    }

    function h(a, b) {
        var e, f = [];
        return d(a, function (a, b) {
            f.push(encodeURIComponent(b) + "=" + encodeURIComponent(a))
        }), e = f.join("&").replace(/%20/g, "+"), "string" === c(b) ? g(b, e) : e
    }

    function i(a) {
        a = a || {};
        var b, f = a.type || "GET", g = k(a.url || ""), i = e(l, a.data), j = a.success, m = a.error, n = new XMLHttpRequest;
        g = h({_t: +new Date}, g), n.onreadystatechange = function () {
            4 === n.readyState && (200 === n.status ? j && j(JSON.parse(n.responseText)) : m && m(n))
        }, f = "POST" === f.toUpperCase() ? "POST" : "GET";
        try {
            "POST" === f ? (b = new FormData, d(i, function (a, d) {
                d && b.append(d, "array" === c(a) ? a.join() : a)
            }), n.open(f, g, !0), n.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), n.send(b)) : (b = h(i), n.open(f, g + "&" + b, !0), n.send())
        } catch (o) {
            console.error("ajax error", o)
        }
    }

    function j(a) {
        return function (b, c, d, e) {
            i({type: a, url: b, data: c, success: d, error: e})
        }
    }

    var k, l = {};
    k = function () {
        var a, b, c, d, e, i = 0, j = {};
        if ("wp" === f("fr"))for (c = f("uc_param_str"), e = c.length - c.length % 2; e > i;)d = c.substr(i, 2), j[d] = f(d), i += 2;
        return b = f("entry"), b && (j.entry = b), a = h(j), function (b) {
            return !f("uc_param_str"), b && (b = g(b, "uc_param_str=dnfrpfbivesscpgimibtbmntnisieijblauputog")), g(b, a)
        }
    }(), b.ping = function (a, b) {
        var c = new Image;
        b && (a = h(b, a)), c.src = k(a) + "&__t=" + +new Date
    }, b.baseParam = function (a, b) {
        if ("string" === c(a)) {
            if (1 === arguments.length)return l[a];
            l[a] = b
        } else"object" === c(a) && (l = e(l, a))
    }, b.query = f, b.parseQuery = h, b.ucParam = k, b.ajax = i, b.get = j("GET"), b.post = j("POST")
}), define("cmp/observer", [], function (a, b, c) {
    "use strict";
    function d(a) {
        this._ctx = a || this
    }

    var e = [].slice, f = d.prototype;
    f.on = function (a, b) {
        return this._cbs = this._cbs || {}, (this._cbs[a] = this._cbs[a] || []).push(b), this
    }, f.once = function (a, b) {
        function c() {
            d.off(a, c), b.apply(this, arguments)
        }

        var d = this;
        return this._cbs = this._cbs || {}, c.fn = b, this.on(a, c), this
    }, f.off = function (a, b) {
        if (this._cbs = this._cbs || {}, !arguments.length)return this._cbs = {}, this;
        var c = this._cbs[a];
        if (!c)return this;
        if (1 === arguments.length)return delete this._cbs[a], this;
        for (var d, e = 0; e < c.length; e++)if (d = c[e], d === b || d.fn === b) {
            c.splice(e, 1);
            break
        }
        return this
    }, f.emit = function (a, b, c, d) {
        this._cbs = this._cbs || {};
        var e = this._cbs[a];
        if (e) {
            e = e.slice(0);
            for (var f = 0, g = e.length; g > f; f++)e[f].call(this._ctx, b, c, d)
        }
        return this
    }, f.applyEmit = function (a) {
        this._cbs = this._cbs || {};
        var b, c = this._cbs[a];
        if (c) {
            c = c.slice(0), b = e.call(arguments, 1);
            for (var d = 0, f = c.length; f > d; d++)c[d].apply(this._ctx, b)
        }
        return this
    }, c.exports = d
}), define("cmp/share", [], function (a, b, c) {
    "use strict";
    function d(a, b, c, d, f, g) {
        var h = "";
        if (e())d && (d = d.replace("SinaWeibo", "kSinaWeibo"), d = d.replace("WechatFriends", "kWeixin"), d = d.replace("WechatTimeline", "kWeixinFriend")), ucbrowser.web_share(a, b, c, d || "", "", "", f || ""); else try {
            g && 0 !== g.length && (h = g.toString()), ucweb.startRequest("shell.page_share", [a, b, c, d || "", h || "", "", j(f) || ""])
        } catch (i) {
            console && console.error(i.message)
        }
    }

    function e() {
        return -1 !== k.navigator.userAgent.toLowerCase().indexOf("iphone") ? !0 : !1
    }

    function f(a) {
        var b = a.offsetTop;
        return b += null != a.offsetParent ? b += f(a.offsetParent) : 0
    }

    function g(a) {
        var b = a.offsetLeft;
        return b += null != a.offsetParent ? g(a.offsetParent) : 0
    }

    function h(a) {
        var b, c = getComputedStyle(a, null).webkitTransform;
        return b = "none" === c ? 0 : parseInt(c.split(",")[5].replace(")", "")), b += "BODY" !== a.parentNode.tagName ? h(a.parentNode) : 0
    }

    function i(a) {
        var b, c = getComputedStyle(a, null).webkitTransform;
        return b = "none" === c ? 0 : parseInt(c.split(",")[4]), b += "BODY" !== a.parentNode.tagName ? i(a.parentNode) : 0
    }

    function j(a) {
        var b = document.getElementById(a);
        if (b) {
            var c = [g(b) + i(b), f(b) + h(b), b.offsetWidth, b.offsetHeight];
            return c
        }
        return ""
    }

    var k = window;
    c.exports = d
}), define("cmp/silly-encrypt", [], function (a, b, c) {
    "use strict";
    var d = function () {
    };
    d.config = {dig: 32, el: 1, kl: 2, ke: 2, wd: 14, kd: 13}, d.relation = {
        9: ["p", "m"],
        8: ["o", "l"],
        7: ["i", "k"],
        6: ["u", "j"],
        5: ["y", "h"],
        4: ["t", "g"],
        3: ["r", "f"],
        2: ["e", "d"],
        1: ["w", "s", "x"],
        0: ["q", "a", "z"],
        ".": ["n", "b"],
        "-": ["v", "c"]
    }, d.cw = function (a, b) {
        return "number" != typeof a || isNaN(a) || "number" != typeof b || isNaN(b) ? !1 : (a + "").length > d.config.wd || (b + "").length > d.config.kd ? !1 : !0
    }, d.insert = function (a, b, c) {
        return a.substr(0, b) + c + a.substr(b)
    }, d.convert = function (a, b) {
        a += "", b += "";
        var c = a.length, e = d.ran(c), f = b.length;
        return f = (10 > f ? "0" : "") + f, e = (10 > e ? "0" : "") + e, e + f + d.insert(a, e, b)
    }, d.ran = function (a) {
        return Math.floor(Math.random() * a)
    }, d.hash = function (a) {
        for (var b = d.config.dig - a.length; b--;)a = d.insert(a, d.ran(a.length), d.ran(10));
        return a
    }, d.ct = function (a) {
        return a[d.ran(a.length)]
    }, c.exports.encode = d.encode = function (a, b) {
        if (d.cw(a, b)) {
            a = d.convert(a, b);
            for (var c, e = a.length, f = d.relation, g = d.ran(10), h = d.ct(f[g]); e--;)c = "." !== a[e] && "-" !== a[e] ? f[(Math.floor(a[e]) + g) % 10] : f[a[e]], h += d.ct(c);
            return d.hash(h)
        }
    }, d.cc = function (a, b) {
        return "string" != typeof a || "number" != typeof b || isNaN(b) ? !1 : a.length > d.config.dig || (b + "").length > d.config.kd ? !1 : !0
    }, d.initIndex = function () {
        if (!d.index) {
            var a, b, c = d.index = {}, e = d.relation;
            for (a in e)if (e.hasOwnProperty(a))for (b = e[a].length; b--;)c[e[a][b]] = a
        }
    }, d.clear = function (a) {
        for (var b = "", c = 0; c < a.length;)isNaN(parseInt(a[c])) && (b += a[c]), ++c;
        return b
    }, c.exports.decode = d.decode = function (a, b) {
        if (d.cc(a, b)) {
            d.initIndex(), a = d.clear(a);
            for (var c, e, f, g = d.index, h = g[a[0]], i = "", j = a.length; j-- > 1;)c = g[a[j]], "." !== c && "-" !== c && (c -= h, c = 0 > c ? c + 10 : c), i += c;
            e = Math.floor(i.substr(0, d.config.ke)), f = Math.floor(i.substr(d.config.ke, d.config.kl));
            var k = d.config.ke + d.config.kl, l = e + k;
            if (Math.floor(i.substr(l, f)) === b)return parseFloat(i.substr(k, e) + i.substr(l + f))
        }
    }
}), define("cmp/storage", [], function (a, b) {
    "use strict";
    var c = window, d = document;
    b.setLocal = function (a, b) {
        return c.localStorage ? (c.localStorage.setItem(a, JSON.stringify(b)), !0) : !1
    }, b.getLocal = function (a) {
        if (c.localStorage) {
            var b = c.localStorage.getItem(a);
            return b ? JSON.parse(b) : null
        }
        return null
    }, b.setSession = function (a, b) {
        return c.sessionStorage ? (c.sessionStorage.setItem(a, JSON.stringify(b)), !0) : !1
    }, b.getSession = function (a) {
        if (c.sessionStorage) {
            var b = c.sessionStorage.getItem(a);
            return b ? JSON.parse(b) : null
        }
        return null
    }, b.setCookie = function (a, b, e) {
        var f = new Date;
        f.setDate(f.getDate() + e), d.cookie = a + "=" + c.escape(b) + (null == e ? "" : ";expires=" + f.toGMTString())
    }, b.getCookie = function (a) {
        if (d.cookie.length > 0) {
            var b = d.cookie.indexOf(a + "=");
            if (-1 !== b) {
                b = b + a.length + 1;
                var e = d.cookie.indexOf(";", b);
                return -1 === e && (e = d.cookie.length), c.unescape(d.cookie.substring(b, e))
            }
        }
        return ""
    }, b.clearSession = function () {
        return c.sessionStorage ? (c.sessionStorage.clear(), !0) : !1
    }, b.clearLocal = function () {
        return c.localStorage ? (c.localStorage.clear(), !0) : !1
    }, b.deleteCookie = function (a) {
        d.cookie = a + "=;expires=" + new Date(0).toGMTString()
    }
}), define("cmp/taobao", [], function (a, b) {
    "use strict";
    function c(a) {
        var b = o();
        w.ucbrowser.getAccountInfo(b, function (c) {
            var d = JSON.parse(c);
            d.verificationCode = b, a(d)
        })
    }

    function d(a) {
        return u = a, w.ucweb ? i(function (b) {
            if (b) {
                var c = o();
                v = "getCallback(" + c + ' ,"##RESULT##", "##SIGN##", "##KPS##", "##NICKNAME##");', w.ucweb.startRequest("shell.taobao.getLoginInfo", [c, v])
            } else a()
        }) : a(), t
    }

    function e() {
        w.ucbrowser.requestTBUserToLogin()
    }

    function f() {
        if (w.ucweb) {
            var a = o();
            v = "loginCallback();", w.ucweb.startRequest("shell.taobao.getLoginInfo", [a, v])
        }
    }

    function g(a) {
        s() ? n(h, a) : i(a)
    }

    function h(a) {
        c(function (b) {
            a(p(b.result) ? !0 : !1)
        })
    }

    function i(a) {
        w.ucweb && "true" === w.ucweb.startRequest("shell.taobao.isLogined", [""]) ? a(!0) : (t = void 0, a(!1))
    }

    function j() {
        w.ucbrowser.openUserCenter()
    }

    function k() {
        w.ucweb.startRequest("shell.account.invoke", [""])
    }

    function l(a) {
        w.ucbrowser.getTBUserAvatar(function (b) {
            a(b)
        })
    }

    function m(a) {
        a(w.ucweb.startRequest("shell.taobao.getAvatar", [""]))
    }

    function n(a, b) {
        w.ucbrowser ? a(b) : x.addEventListener("UCBrowserReady", function () {
            a(b)
        }, !1)
    }

    function o() {
        return new Date % 1e9 + 1e8
    }

    function p(a) {
        return -1 !== a.indexOf("success") ? !0 : !1
    }

    function q(a, b, c, d) {
        return {result: "success", kps: a, sign: b, verificationCode: c, nickname: d}
    }

    function r(a, b) {
        function c() {
            var c = document.createEvent("Event");
            return c.initEvent(a, !0, !0), c.detail = b, c
        }

        if (w.CustomEvent)try {
            return new CustomEvent(a, {detail: b})
        } catch (d) {
            c()
        } else c()
    }

    function s() {
        return -1 !== w.navigator.userAgent.toLowerCase().indexOf("iphone") ? !0 : !1
    }

    var t, u, v, w = window, x = document;
    b.getInfo = function (a) {
        s() ? n(c, a) : d(a)
    }, b.login = function () {
        s() ? n(e) : f()
    }, b.checkLogin = g, b.userCenter = function () {
        s() ? n(j) : k()
    }, b.getAvatar = function (a) {
        s() ? n(l, a) : m(a)
    }, w.loginCallback = function () {
        x.dispatchEvent(r("cmp-after-login"))
    }, x.addEventListener("on_tb_user_login", function () {
        x.dispatchEvent(r("cmp-after-login"))
    }), w.getCallback = function (a, b, c, d, e) {
        p(b) ? u(q(d, c, a, e)) : u()
    }
}), define("cmp/vibration", [], function (a, b) {
    "use strict";
    function c(a) {
        return !a || 100 > a ? 100 : a
    }

    function d(a) {
        g.navigator.vibrate(c(a))
    }

    function e() {
        f && clearInterval(f), g.navigator.vibrate(0)
    }

    var f, g = window;
    b.isSupport = function () {
        return g.navigator.vibrate ? !0 : !1
    }, b.start = d, b.startPeristent = function () {
        f && clearInterval(f), d(5e3), f = setInterval(function () {
            d(5e3)
        }, 1e3)
    }, b.startInterval = function (a, b) {
        f && clearInterval(f), g.navigator.vibrate(a), f = setInterval(function () {
            g.navigator.vibrate(a)
        }, a + b)
    }, b.stop = e
}), define("index/game/animate/drawPerson", ["utils/utils"], function (a, b, c) {
    "use strict";
    var d = a("utils/utils");
    c.exports.render = function (a) {
        var b = window.innerWidth, c = window.innerHeight, e = null != a.bfb ? a.bfb : .15;
        d.preImage(a.src, function () {
            a.ctx.drawImage(this, 2 * a.x, 0, 2 * a.w, 2 * a.h, (b - a.w) / 2, c - a.h - c * e - 10, a.w, a.h)
        })
    }
}), define("index/game/animate/ground", [], function (a, b, c) {
    "use strict";
    function d(a) {
        this.startFrame = -1, this.frames = a, this.render = function (a, b) {
            -1 === this.startFrame && (this.startFrame = b), this.drawBg(a, b)
        }, this.drawBg = function (a, b) {
            var c = this;
            c.frame = b, c.el = {}, c.el.$root = a;
            var d = c.el.$root.getContext("2d"), e = window.innerWidth, f = window.innerHeight, g = {};
            switch (c.img = {idx: this.getFrame(b), w: 320, h: 330}, c.img.idx) {
                case 0:
                    g = {gy: 4, wx: 2};
                    break;
                case 1:
                    g = {gy: 0, wx: 0};
                    break;
                case 2:
                    g = {gy: -2, wx: 3};
                    break;
                case 3:
                    g = {gy: -4, wx: 6};
                    break;
                case 4:
                    g = {gy: -3, wx: 11};
                    break;
                case 5:
                    g = {gy: -5, wx: 13};
                    break;
                case 6:
                    g = {gy: 0, wx: 15};
                    break;
                case 7:
                    g = {gy: 1, wx: 19};
                    break;
                case 8:
                    g = {gy: -2, wx: 17};
                    break;
                case 9:
                    g = {gy: -3, wx: 15};
                    break;
                case 10:
                    g = {gy: -6, wx: 10};
                    break;
                case 11:
                    g = {gy: -7, wx: 8};
                    break;
                case 12:
                    g = {gy: -2, wx: 3};
                    break;
                default:
                    g = {gy: 0, wx: 0}
            }
            var h = 0, i = .4 * f + g.gy;
            d.fillStyle = "#90c983", d.beginPath(), d.moveTo(h, i), d.quadraticCurveTo(.5 * e, i - .1 * f, e, i), d.lineTo(e, f), d.lineTo(0, f), d.closePath(), d.fill();
            var j = h + .43 * e + g.wx, k = i - .052 * f;
            d.fillStyle = "#474540", d.beginPath(), d.moveTo(j, k), d.quadraticCurveTo(.5 * e, k - 5, j + .13 * e, k), d.quadraticCurveTo(j + .3 * e, k + .1 * f, e, k + .4 * f), d.lineTo(e, f), d.lineTo(0, f), d.lineTo(0, k + .4 * f), d.quadraticCurveTo(j - .17 * e, k + .1 * f, j, k), d.closePath(), d.fill()
        }, this.reset = function () {
            this.startFrame = -1
        }, this.getFrame = function (a) {
            return this.init(a), this.frames[(a - this.startFrame) % this.frames.length]
        }, this.init = function (a) {
            -1 === this.startFrame && (this.startFrame = a)
        }
    }

    c.exports = d
}), define("index/game/animate/ground1", ["index/game/animate/ground", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/ground"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g1), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/ground2", ["index/game/animate/ground", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/ground"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g2), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/ground3", ["index/game/animate/ground", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/ground"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g3), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/ground4", ["index/game/animate/ground", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/ground"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g4), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/ground5", ["index/game/animate/ground", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/ground"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g5), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/person", ["utils/utils", "appData", "widget/observer"], function (a, b, c) {
    "use strict";
    function d(b) {
        this.startFrame = -1, this.frames = b, this.render = function (a, b) {
            this.draw(a, b)
        }, this.draw = function (b, c) {
            var d = this;
            d.frame = c, d.el = {}, d.el.$root = b, d.wWin = window.innerWidth, d.hWin = window.innerHeight, d.img = {
                src: f.RESOURCE[1],
                num: d.frames.length,
                idx: d.getFrame(c),
                w: 125,
                h: 275
            };
            var g = d.el.$root, h = g.getContext("2d");
            e.preImage(d.img.src, function () {
                var a = d.img, b = d.img.idx;
                switch (d.img.idx) {
                    case 8:
                        b = 6;
                        break;
                    case 9:
                        b = 5;
                        break;
                    case 10:
                        b = 4;
                        break;
                    case 11:
                        b = 3;
                        break;
                    case 12:
                        b = 2;
                        break;
                    case 13:
                        b = 1
                }
                h.drawImage(this, d.img.w * b, 0, a.w, a.h, (d.wWin - a.w) / 2, d.hWin - a.h - .15 * d.hWin - 10, a.w, a.h)
            }), d.img.idx === d.frames[d.img.num - 1] && a("widget/observer").emit("round")
        }, this.reset = function () {
            this.startFrame = -1
        }, this.getFrame = function (a) {
            return this.init(a), this.frames[(a - this.startFrame) % this.frames.length]
        }, this.init = function (a) {
            -1 === this.startFrame && (this.startFrame = a)
        }
    }

    var e = a("utils/utils"), f = a("appData").CONF;
    c.exports = d
}), define("index/game/animate/person1", ["index/game/animate/person", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/person"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g1), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/person2", ["index/game/animate/person", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/person"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g2), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/person3", ["index/game/animate/person", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/person"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g3), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/person4", ["index/game/animate/person", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/person"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g4), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/person5", ["index/game/animate/person", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/person"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g5), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/player", ["utils/utils", "appData"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = a("utils/utils"), f = a("appData").CONF, g = window;
    d.canvas = e.$("#can-game"), d.FPS = f.FPS[0], d.frame = 0, d.timer = g.requestAnimationFrame || g.webkitRequestAnimationFrame || g.setTimeout, d.playing = !1, d.buffer = null, d.animateList = c.exports.animateList = [], c.exports.initCanvas = d.initCanvas = function () {
        function a() {
            b.width = c.width = g.innerWidth, b.height = c.height = g.innerHeight, e.$("#g-main").style.height = g.innerHeight + "px"
        }

        var b = d.canvas, c = d.buffer = document.createElement("canvas");

        a(), g.onresize = function () {
            a()
        }
    }, d.clearBuff = function () {
        var a = d.buffer, b = a.getContext("2d");
        b.clearRect(0, 0, a.width, a.height)
    }, d.renderBuff = function () {
        for (var a = 0, b = d; a < b.animateList.length;)b.animateList[a].render(b.buffer, b.frame), ++a
    }, d.render = function () {
        var a = d.canvas, b = a.getContext("2d");
        b.drawImage(d.buffer, 0, 0)
    }, c.exports.play = d.play = function () {
        function a(f) {
            b = f || +new Date, e || (e = b), c.playing && (b - e > 1e3 / d.FPS && (c.clearBuff(), c.renderBuff(), c.render(), ++c.frame, e = b), c.timer.call(g, a))
        }

        var b, c = d, e = 0;
        c.playing = !0, c.timer.call(g, a)
    }, c.exports.stop = d.stop = function () {
        d.playing = !1
    }, c.exports.overclock = d.overclock = function (a) {
        d.FPS = a
    }
}), define("index/game/animate/sky", [], function (a, b, c) {
    "use strict";
    function d() {
    }

    c.exports.render = d.render = function (a, b) {
        d.init(a);
        var c = d.ctx = d.el.$root.getContext("2d"), e = d.w = window.innerWidth, f = d.h = window.innerHeight, g = 0;
        switch (b % 7) {
            case 1:
                g = 2;
                break;
            case 2:
                g = 4;
                break;
            case 3:
                g = 6;
                break;
            case 4:
                g = 4;
                break;
            case 5:
                g = 2;
                break;
            default:
                g = 0
        }
        c.fillStyle = "#c4f2ff", c.fillRect(0, 0, e, f), d.drawSun({
            color: "rgba(255,253,52,0.32)",
            r: .34 * e * .5
        }), d.drawSun({color: "rgba(255,255,255,1)", r: .3 * e * .5}), d.drawSun({
            color: "rgba(255,252,178,1)",
            r: .28 * e * .5
        }), d.drawSun({
            color: "rgba(255,253,52,1)",
            r: .26 * e * .5
        }), c.fillStyle = c.strokeStyle = "white", c.beginPath();
        var h = .05 * e, i = .3 * f + g;
        c.moveTo(h, i), c.quadraticCurveTo(h + 50, i - 10, h + 111, i - 14), c.bezierCurveTo(h + 125, i - 20, h + 120, i - 40, h + 98, i - 38), c.bezierCurveTo(h + 90, i - 52, h + 65, i - 50, h + 65, i - 27), c.quadraticCurveTo(h + 58, i - 30, h + 56, i - 24), c.quadraticCurveTo(h + 40, i - 30, h + 37, i - 14), c.quadraticCurveTo(h + 33, i - 14, h + 31, i - 8), c.quadraticCurveTo(h + 11, i - 4, h, i - 2), c.closePath(), c.fill(), c.stroke(), c.beginPath(), h = .49 * e, i = .25 * f + g, c.moveTo(h, i), c.quadraticCurveTo(h + 24, i + 1, h + 63, i), c.arc(h + 63, i - 13, 13, .45 * Math.PI, 1 * Math.PI, !0), c.quadraticCurveTo(h + 40, i - 12, h + 40, i - 6), c.quadraticCurveTo(h + 36, i, h + 36, i - 2), c.quadraticCurveTo(h, i, h, i - 3), c.fill(), c.stroke(), c.closePath(), c.beginPath(), h = .625 * e, i = .28 * f + g, c.moveTo(h, i), c.quadraticCurveTo(h + 48, i - 2, h + 104, i + 11), c.lineTo(h + 104, i + 9), c.quadraticCurveTo(h + 85, i + 9, h + 89, i - 18), c.bezierCurveTo(h + 88, i - 41, h + 56, i - 41, h + 53, i - 22), c.quadraticCurveTo(h + 38, i - 26, h + 39, i - 11), c.quadraticCurveTo(h + 32, i - 11, h + 32, i - 5), c.quadraticCurveTo(h + 32, i - 2, h + 29, i - 2), c.quadraticCurveTo(h + 15, i - 1, h, i - 2), c.fill(), c.stroke(), c.closePath()
    }, d.init = function (a) {
        d.el = a, d.el.$root = a
    }, d.drawSun = function (a) {
        var b = d;
        b.ctx.fillStyle = a.color, b.ctx.beginPath(), b.ctx.arc(.51 * b.w, .4 * b.h, a.r, 0, 2 * Math.PI), b.ctx.closePath(), b.ctx.fill()
    }
}), define("index/game/animate/tree", ["appData", "utils/utils"], function (a, b, c) {
    "use strict";
    function d(a) {
        this.startFrame = -1, this.framesImg = f.RESOURCE[0], this.frameSize = {
            w: 288,
            h: 315
        }, this.frames = a, this.render = function (a, b) {
            var c = a.getContext("2d"), d = this.getFrame(b), f = this.frameSize;
            g.preImage(this.framesImg, function () {
                c.drawImage(this, d * f.w, 0, f.w, f.h, 0, .24 * e.innerHeight, e.innerWidth, .76 * e.innerHeight)
            })
        }, this.reset = function () {
            this.startFrame = -1
        }, this.getFrame = function (a) {
            return this.init(a), this.frames[(a - this.startFrame) % this.frames.length]
        }, this.init = function (a) {
            -1 === this.startFrame && (this.startFrame = a)
        }
    }

    var e = window, f = a("appData").CONF, g = a("utils/utils");
    c.exports = d
}), define("index/game/animate/tree1", ["index/game/animate/tree", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/tree"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g1), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/tree2", ["index/game/animate/tree", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/tree"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g2), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/tree3", ["index/game/animate/tree", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/tree"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g3), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/tree4", ["index/game/animate/tree", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/tree"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g4), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/animate/tree5", ["index/game/animate/tree", "appData"], function (a, b, c) {
    "use strict";
    var d, e = a("index/game/animate/tree"), f = a("appData").CONF;
    d = new e(f.FRAMES_MAP.g5), c.exports.render = function (a, b) {
        d.render(a, b)
    }, c.exports.reset = function () {
        d.reset()
    }
}), define("index/game/engine", ["utils/utils", "utils/timer", "utils/image", "index/game/animate/player", "appData", "index/game/scene", "index/game/motion", "index/game/voiceover", "index/game/music", "index/game/animate/sky", "index/game/animate/person1", "index/game/animate/person2", "index/game/animate/person3", "index/game/animate/person4", "index/game/animate/person5", "index/game/animate/ground1", "index/game/animate/ground2", "index/game/animate/ground3", "index/game/animate/ground4", "index/game/animate/ground5", "index/game/animate/tree1", "index/game/animate/tree2", "index/game/animate/tree3", "index/game/animate/tree4", "index/game/animate/tree5", "widget/observer", "jquery.min"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = a("utils/utils"), f = a("utils/timer"), jq = a("jquery.min"), g = a("utils/image"), h = a("index/game/animate/player"), i = a("appData").CONF, j = a("index/game/scene"), k = a("index/game/motion"), l = a("index/game/voiceover"), m = a("index/game/music"), n = d.animates = {
        sky: a("index/game/animate/sky"),
        person1: a("index/game/animate/person1"),
        person2: a("index/game/animate/person2"),
        person3: a("index/game/animate/person3"),
        person4: a("index/game/animate/person4"),
        person5: a("index/game/animate/person5"),
        ground1: a("index/game/animate/ground1"),
        ground2: a("index/game/animate/ground2"),
        ground3: a("index/game/animate/ground3"),
        ground4: a("index/game/animate/ground4"),
        ground5: a("index/game/animate/ground5"),
        tree1: a("index/game/animate/tree1"),
        tree2: a("index/game/animate/tree2"),
        tree3: a("index/game/animate/tree3"),
        tree4: a("index/game/animate/tree4"),
        tree5: a("index/game/animate/tree5")
    };
    d.curGrade = 1, d.observer = a("widget/observer"), d.needCalcul = !1, d.gameTime = i.GAME_TIME, d.interval = 100, d.dis = 0, d.started = !1, d.showUpGrade = !1, c.exports.start = d.start = function () {
        e.once("game/setup", function () {
            d.setupObserver()
        }), h.initCanvas(), d.loadResource(function () {
            function a() {
                e.$("#page-game").removeEventListener("touchstart", a), j.started(), e.once("game/listen", function () {
                    d.ListenDom()
                }), d.setupTimer(), h.animateList.push(n.sky), h.animateList.push(n.ground1), h.animateList.push(n.tree1), h.animateList.push(n.person1), h.play(), d.observer.emit("log", {page: "touchRun"}), l.show(1, 1)
            }

            g.init([{
                q: "#g-ready",
                bg: "url(./../addons/wmb_run/template/mobile/images/game.png) center -200px no-repeat",
                bgSize: "225px 245px"
            }, {
                q: ".g-finger dt",
                bg: "url(./../addons/wmb_run/template/mobile/images/game.png) no-repeat center top",
                bgSize: "225px 245px"
            }, {
                q: ".g-finger dd",
                bg: "url(./../addons/wmb_run/template/mobile/images/game.png) no-repeat center -100px",
                bgSize: "225px 245px"
            }]), j.renderStart(), e.$("#g-loading").hide(), e.$("#g-main").show(), e.$("#page-game").addEventListener("touchstart", a)
        })
    }, d.loadResource = function (a) {
        function b() {
            ++f === c.length && (g ? a() : setTimeout(function () {
                a()
            }, i.LOAD_GAME_MORE))
        }

        var c = i.RESOURCE, d = c.length, f = 0, g = !1;
        for (m.playBg(); d--;)e.preImage(c[d], b);
        setTimeout(function () {
            g = !0
        }, i.LOAD_GAME_MAX)
    }, d.setupObserver = function () {
        var a = this;
        this.observer.on("round", function () {
            a.needCalcul && (d.roundHandle(), a.needCalcul = !1)
        }), this.observer.on("musicon", function () {
            m.on(), d.observer.emit("log", {page: "music_turnon"})
        }), this.observer.on("musicoff", function () {
            m.off(), d.observer.emit("log", {page: "music_shutdown"})
        })
    }, d.setupTimer = function () {
        function a() {
            var a = (c.gameTime - g) / 1e3;
            e.$("#g-sec").innerText = a % 1 !== 0 ? a + "" : a + ".0", g += c.interval
        }

        function b() {
            i ? (c.needCalcul = !0, i = !1) : i = !0
        }

        var c = this, g = 0, h = 0, i = !1;
        f.countdown(this.gameTime, this.interval, function () {
            a(), 5 === h && (b(), c.updateDis(), h = 0), ++h
        }, d.gameOver)
    }, d.ListenDom = function () {
        function a() {
            k.run(0), e.addClass(d, "sel"), e.removeClass(f, "sel")
        }

        function b() {
            k.run(1), e.addClass(f, "sel"), e.removeClass(d, "sel")
        }

        function c(a) {
            e.cancelBubble(a), a.preventDefault()
        }

        var d = e.$("#pace-left"), f = e.$("#pace-right");
        d.addEventListener("touchstart", function () {
            a()
        }), f.addEventListener("touchstart", function () {
            b()
        }), e.$("#page-game").addEventListener("touchstart", function (a) {
            c(a)
        }), e.$("#page-game").addEventListener("touchend", function (a) {
            c(a)
        }), e.$("#page-game").addEventListener("touchmove", function (a) {
            c(a)
        }), document.onkeydown = function (c) {
            37 === c.keyCode && a(), 39 === c.keyCode && b()
        }
    }, d.roundHandle = function () {
        var a = k.getGrade(this.curGrade);
        h.overclock(i.FPS[a - 1]), a !== this.curGrade && (this.updateAnimateList(a), l.show(this.curGrade, a), this.curGrade = a, console.log("new grade:", this.curGrade)), this.showUpGrade || 5 !== a || (this.showUpGrade = !0, j.upGrade())
    }, d.updateAnimateList = function (a) {
        h.animateList.pop().reset(), h.animateList.pop().reset(), h.animateList.pop().reset(), h.animateList.push(n["ground" + a]), h.animateList.push(n["tree" + a]), h.animateList.push(n["person" + a])
    }, d.updateDis = function () {
        function a() {
            return Math.random() > .5 ? i.DIS_RANDOM : -1 * i.DIS_RANDOM
        }

        this.dis += i.DIS[d.curGrade - 1] + a(), e.$("#g-dis").innerText = this.dis
    }, d.gameOver = function () {
        function a() {
            m.playCheer(), e.$("#page-game").removeEventListener("touchstart", a), console.log(e.$("#g-dis").innerText)
        }
		
        h.stop(), m.pauseBg(), l.hide(), j.renderEnd(), d.observer.emit("gameover", d.dis, function () {
             $.ajax({
                type: 'post',
                url: '' + e.$("#Url").value + '',
                data: {dis: d.dis},
                async: false,
                success: function (data) {				
                   // var data = eval("(" + data + ")");
                    $.each(data, function (i, value) {
                        e.$("#myOld").show();
                        alert("dd");
                        e.$("#i-best").innerText =data;
                        e.$("#myScroe").innerText =data + "m";
						e.$("#i-start").hide();
                        e.$("#i-invite").show();

						e.$("#i-totalxx").innerText =data;
                    })
                },
                error: function (e) {
                    alert('xxxxx');
                }
            }), e.$("#disInp").value = d.dis, e.$("#oldNum").innerText = d.dis, setTimeout(function () {
                e.$("#g-loading").show(), e.$("#g-main").hide(), d.reset(), e.$("#page-game").removeEventListener("touchstart", a), d.observer.emit("gamequit")
            }, i.GAMEOVER_WAIT_TIME)
        }), e.$("#page-game").addEventListener("touchstart", a)
    }, d.reset = function () {
        this.curGrade = 1, this.needCalcul = !1, this.dis = 0, this.started = !1, this.showUpGrade = !1, k.reset(), m.reset(), j.reset()
    }
}), define("index/game/motion", ["appData"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = window, f = a("appData").CONF;
    d.gradeMap = f.GRADE_MAP, d.curType = 1, d.combo = 0, c.exports.run = d.run = function (a) {
        var b = d;
        a !== b.curType && (1 === a && (++b.combo, e.navigator.vibrate && e.navigator.vibrate(20)), b.curType = a)
    }, c.exports.getGrade = d.getGrade = function (a) {
        for (var b, c = d, e = c.combo, f = c.gradeMap, g = f.length; g--;)if (e >= f[g]) {
            b = g + 1;
            break
        }
        return c.combo = 0, b > a ? b - a >= 2 ? a + 2 : a + 1 : a > b ? a - 1 : a
    }, c.exports.reset = d.reset = function () {
        d.curType = 1, d.combo = 0
    }
}), define("index/game/music", ["appData"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = document, f = a("appData").CONF;
    d.audioBg = {}, d.audioCheer = {}, d.able = !0, c.exports.playBg = d.playBg = function () {
        d.able && (d.audioBg = e.createElement("audio"), d.audioBg.src = f.MUSIC_BG, d.audioBg.volume = f.MUSIC_VOLUME, d.audioBg.loop = !0, d.audioBg.play())
    }, c.exports.pauseBg = d.pauseBg = function () {
        d.audioBg.pause && d.audioBg.pause(), d.audioBg.src = ""
    }, c.exports.playCheer = d.playCheer = function () {
        d.able && (d.audioCheer = e.createElement("audio"), d.audioCheer.src = f.MUSIC_CHEER, d.audioCheer.volume = f.MUSIC_VOLUME, d.audioCheer.loop = !1, d.audioCheer.play())
    }, c.exports.reset = d.pauseCheer = function () {
        d.audioCheer.pause && d.audioCheer.pause(), d.audioCheer.src = ""
    }, c.exports.on = d.on = function () {
        d.able = !0, d.playBg()
    }, c.exports.off = d.off = function () {
        d.able = !1, d.pauseBg()
    }
}), define("index/game/scene", ["appData", "appData", "index/game/animate/sky", "index/game/animate/ground", "index/game/animate/tree", "widget/observer", "index/store", "index/game/animate/drawPerson", "utils/utils"], function (a, b) {
    "use strict";
    var c = a("appData").CONF, d = a("appData").FILTER, e = a("index/game/animate/sky"), f = a("index/game/animate/ground"), g = a("index/game/animate/tree"), h = a("widget/observer"), i = a("index/store"), j = a("index/game/animate/drawPerson"), k = a("utils/utils"), l = k.$("#can-game"), m = k.$("#pace-left"), n = k.$("#pace-right"), o = k.$("#finger-left"), p = k.$("#finger-right"), q = k.$("#g-ready"), r = k.$("#g-countdown"), s = k.$("#g-points"), t = k.$("#g-points-s");
    b.renderStart = function () {
        var a = 0, b = c.FRAMES_MAP.g1;
        e.render(l, a), new f(b).render(l, a), new g(b).render(l, a), j.render({
            ctx: l.getContext("2d"),
            src: c.RESOURCE[2],
            x: 0,
            w: 166,
            h: 260,
            bfb: .03
        }), window.startFinger = window.setInterval(function () {
            var a = k.hasClass(m, "sel");
            a ? (k.removeClass(m, "sel"), k.addClass(n, "sel"), k.addClass(o, "sel"), k.removeClass(p, "sel")) : (k.addClass(m, "sel"), k.removeClass(n, "sel"), k.removeClass(o, "sel"), k.addClass(p, "sel"))
        }, 200)
    }, b.started = function () {
        window.clearInterval(window.startFinger), k.removeClass(m, "sel"), k.removeClass(n, "sel"), o.parentNode.style.display = "none", q.style.display = "none", r.style.opacity = "1", k.$("#g-music").hide()
    }, b.renderEnd = function () {
        k.addClass(r, "sel");
        var a = 0, b = c.FRAMES_MAP.g1;
        e.render(l, a), new f(b).render(l, a), new g(b).render(l, a), j.render({
            ctx: l.getContext("2d"),
            src: c.RESOURCE[2],
            x: 166,
            w: 200,
            h: 250
        }), k.addClass(s, "sel-ani"), setTimeout(function () {
            k.addClass(s, "sel"), t.innerHTML = "厉害!  跑了"
        }, 1e3)
    }, b.reset = function () {
        t.innerHTML = "", k.$("#g-dis").innerHTML = "0", k.removeClass(s, "sel"), k.removeClass(s, "sel-ani"), k.$("#g-sec").innerHTML = "10.0", k.removeClass(r, "sel"), r.style.opacity = "0", o.parentNode.style.display = "block", q.style.display = "block", k.$("#g-music").show()
    }, b.upGrade = function () {
        k.$("#g-up").className = "g-up-animate", setTimeout(function () {
            k.$("#g-up").className = ""
        }, 3e3)
    }, i.userInfo && k.$("#g-nick").txt(d.nick(i.userInfo.nick)), k.$("#g-music").className || (k.$("#g-music").className = "g-musicon"), k.$("#g-music").addEventListener("touchstart", function (a) {
        "g-musicon" === k.$("#g-music").className ? (k.$("#g-music").className = "g-musicoff", h.emit("musicoff")) : (k.$("#g-music").className = "g-musicon", h.emit("musicon")), a.cancelBubble = !0
    })
}), define("index/game/voiceover", ["utils/utils", "appData"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = a("utils/utils"), f = a("appData").CONF;
    d.ele = e.$("#g-voiceover"), d.timer = 0, d.randomTime = f.VOICES_RANDOM_TIME, c.exports.show = d.show = function (a, b) {
        function c() {
            d.ele.innerText = e[parseInt(e.length * Math.random(), 10)]
        }

        var e = f.VOICES[b - 1];
        d.timer && clearInterval(d.timer), d.timer = setInterval(function () {
            c()
        }, d.randomTime), c()
    }, c.exports.hide = d.hide = function () {
        d.timer && clearInterval(d.timer), d.ele.innerText = ""
    }
}), define("index/index", ["utils/utils", "utils/log", "appData", "index/store", "utils/pf", "index/game/engine", "index/share", "widget/download", "index/render", "widget/observer"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = window, f = e.location, g = a("utils/utils"), h = a("utils/log"), i = a("appData").CONF, j = a("index/store"), k = a("utils/pf"), l = a("index/game/engine"), m = a("index/share"), n = a("widget/download"), o = a("index/render");
    d.observer = a("widget/observer"), d.start = function () {
        o.showGame(), l.start(), h({page: "startRun"})
    }, d.startAgain = function () {
        o.showGame(), l.start(), h({page: "startRunAgain"})
    }, d.createRunning = function () {
        h({page: "click_new"}, function () {
            f.href = "../a/index?uc_param_str=" + i.UC_PARAMS
        })
    }, d.invite = function (a) {
        j.ownerInfo.isShared ? m.share() : j.invite(function () {
            o.render(), m.share()
        }), h(a ? {page: "click_reshare", step: 1} : {page: "click_share", step: 1})
    }, d.getReward = function () {
        h({page: "click_award"}, function () {
            var a = "reward";
            "wechat" === k() && (a = "cheer"), f.href = "../" + a + "?uc_param_str=" + i.UC_PARAMS
        })
    }, d.download = function () {
        n()
    }, d.rule = function () {
        h({page: "rule"}, function () {
            f.href = "../rule?uc_param_str=" + i.UC_PARAMS
        })
    }, d.closeDownload = function () {
        g.$("#i-notice-download").hide(), h({page: "close_download"})
    }, c.exports.init = d.init = function () {
        function a() {
            j.init();
            var a = j.records.length;
            for (j.isOwner() && (j.bestDis = j.ownerInfo.dist); a--;)j.records[a].runnerUserId !== j.userInfo.userId || j.isOwner() || (j.curDis = j.records[a].dist)
        }

        d.callUcbrowser(), d.listenDom(), d.setupObserver(), m.init(), a(), o.renderStatic(), o.render(), d.preLoad()
    }, d.callUcbrowser = function () {
        "others" === k() && g.callUcbrowser(f.href)
    }, d.listenDom = function () {

        g.$("#i-start").addEventListener("click", function () {
            d.start()
        }), g.$("#i-relay").addEventListener("click", function () {
            d.start()
        }), g.$("#i-again").addEventListener("click", function () {
            d.startAgain()
        }), g.$("#i-try").addEventListener("click", function () {
            d.start()
        }), g.$("#i-self").addEventListener("click", function () {
            d.createRunning()
        }), g.$("#i-back").addEventListener("click", function () {
            d.createRunning()
        }), g.$("#i-invite").addEventListener("click", function () {
            d.invite(!1)
        }), g.$("#i-invite-continue").addEventListener("click", function () {
            d.invite(!0)
        }), g.$("#i-notice-download").addEventListener("click", function () {
            d.download()
        }), g.$("#i-download").addEventListener("click", function () {
            d.download()
        }), g.$("#i-rule").addEventListener("click", function () {
            d.rule()
        }), g.$("#i-notice-download-close").addEventListener("click", function () {
            d.closeDownload()
        }), g.$("#i-reward").addEventListener("click", function () {/*d.getReward()*/
        })

    }, d.setupObserver = function () {

        this.observer.on("gameover", function (a, b) {
            j.run(a, function () {
                b(), m.updateRemain()
            })
        }), this.observer.on("gamequit", function () {
            o.showIndex()
        }), this.observer.on("log", function (a) {
            "click_share" === a.page && j.ownerInfo.isShared && (a.page = "click_reshare"), "download" === a.page && (a.page = "wechat" === k() ? "ad_download" : "others_down_btn"), h(a)
        })

    }, d.preLoad = function () {
        for (var a = i.PRE_RESOURCE.concat(i.RESOURCE), b = a.length; b--;)g.preImage(a[b])
    }

}), define("index/render", ["appData", "appData", "index/tpl", "utils/pf", "index/store", "utils/utils"], function (a, b, c) {
    "use strict";
    function d() {
    }

    var e = document, f = a("appData").CONF, g = a("appData").FILTER, h = a("index/tpl"), i = a("utils/pf"), j = a("index/store"), k = a("utils/utils");
    d.renderStatic = function () {
        "uc" === i() ? (k.$("#i-notice-uc").show(), j.isOwner() && j.ownerInfo.wechatBind && k.$("#i-notice-bind").show()) : "wechat" === i() ? (k.$("#i-notice-download").show(), k.$("#i-notice-download-close").show()) : (k.$("#i-adverise").hide(), k.$("#i-abstract").hide()), k.$(".i-copy").show()
    }, d.render = function () {
        function a() {

            k.$("#i-owner").hide(), k.$(".i-stat").hide(), k.$(".i-button").hide(),k.$("#i-start").show(), k.$("#i-invite").addEventListener("click", function () {
                k.$("#timelineGuid").show()
            })

        }

        var b;


        return a(), j.error ? (void k.$("#i-stat-error").hide(), k.$("#i-invite").show(), k.$("#i-again").show()) : "others" === i() ? (0 === j.curDis ? (k.$("#i-stat-others").hide(), k.$("#i-try").hide()) : (k.$(".i-cur").txt(j.curDis), k.$("#i-stat-others-runed").show(), k.$("#i-again").show()), k.$("#i-download").hide(), k.$("i-start").show(),k.$("#i-start").hide(), k.$("#i-invite").show(), void k.$("#i-nodes").show()) : (j.records.length ? (k.$("#i-nodes").show(), this.renderMap()) : k.$("#i-nodes").hide(), j.isOwner() ? (b = j.ownerInfo.isShared ? j.getRemain() : f.TOTAL_DIS - j.bestDis, j.ownerInfo.isShared ? b > 0 ? (k.$("#i-stat-remain-owner").show(), k.$("#i-invite-continue").show()) : (k.$("#i-stat-reward-owner").show(), k.$("#i-reward").show(), k.$("#i-invite-continue").show()) : 0 === j.bestDis && 0 === j.curDis ? (k.$("#i-stat-remain-owner").show(), k.$("#i-start").show()) : 0 !== j.bestDis && 0 !== j.curDis ? (k.$("#i-stat-remain-runed-owner").show(), k.$("#i-again").show(), k.$("#i-invite").show()) : 0 !== j.bestDis && 0 === j.curDis && (k.$("#i-stat-remain-runed-again-owner").show(), k.$("#i-again").show(), k.$("#i-invite").show())) : (b = j.getRemain(), b > 0 ? 0 !== j.curDis ? k.$("#i-stat-remain-runed").show() : (k.$("#i-stat-remain").show(), k.$("#i-relay").show()) : 0 !== j.curDis ? k.$("#i-stat-reward-runed").show() : k.$("#i-stat-reward").show(), j.userInfo.created ? k.$("#i-back").show() : k.$("#i-self").show()), k.$("#i-owner").show(), k.$("#i-owner strong").txt(g.nick(j.ownerInfo.nick)), k.$(".i-total").txt(j.getTotalDis()), k.$(".i-cur").txt(j.curDis), k.$(".i-best").txt(j.bestDis), k.$(".i-remain").txt(b), void(j.ownerInfo.isShared ? (k.$("#i-ranking").show(), this.renderRanking()) : k.$("#i-ranking").hide()))
    }, d.renderRanking = function () {
        for (var a = k.$("#i-records"), b = j.records.sort(function (a, b) {
            return a.dist < b.dist ? -1 : 1
        }), c = e.createDocumentFragment(), d = b.length; a.firstChild;)a.removeChild(a.firstChild);
        for (; d--;)c.appendChild(h.record(b[d]));
        a.appendChild(c)
    }, d.renderMap = function () {
        function a(a) {
            for (; a.firstChild;)a.removeChild(a.firstChild)
        }

        function b() {
            for (var a = j.records.sort(function (a, b) {
                return a.createTime < b.createTime ? -1 : 1
            }), b = 0, c = []; b < a.length;)c.push({type: 0, dist: a[b].dist, avatar: a[b].avatar}), ++b;
            return j.getRemain() > 0 && c.push({type: 1, avatar: f.NODE_AVATAR.next}), c.push({
                type: 2,
                avatar: f.NODE_AVATAR.reward
            }), c
        }

        function c(a, b) {
            a.style.height = 62 * Math.ceil((b.length + 1) / 4) + "px"
        }

        function d(a, b) {
            for (var c = 0, d = b.length, f = e.createDocumentFragment(); d > c;)f.appendChild(h.node(b[c], c, j.getRemain())), ++c;
            a.appendChild(f)
        }

        var g, i = k.$("#i-nodes1");
        a(i), g = b(), c(i, g), d(i, g)
    }, d.showIndex = function () {
        k.$("#page-index").show(), k.$("#page-game").hide(), this.render()
    }, d.showGame = function () {
        k.$("#page-index").hide(), k.$("#page-game").show()
    }, c.exports = d
}), define("index/share", ["utils/utils", "widget/mask", "index/store", "cmp/share", "utils/pf", "appData", "appData", "cmp/jweixin-1.0.0", "widget/observer"], function (a, b) {
    "use strict";
    function c(a) {
        var b = {};
        return "friend" === a ? (b.title = k.SHAREFRIENDCONF.title, b.desc = k.SHAREFRIENDCONF.desc.replace("xxx", g.getRemain())) : b.title = k.SHARELINECONF.title.replace("xxx", g.getRemain()), b.imgUrl = j.userInfo.avatar, b.link = n, b.success = function () {
            m.emit("log", {page: "click_share", step: 2, share_type: a, share_status: "ok"})
        }, b.cancel = function () {
            m.emit("log", {page: "click_share", step: 2, share_type: a, share_status: "cancel"})
        }, b
    }

    function d(a) {
        if ("uc" === i()) {
            var b = k.UC_SHARE.FRIENDS;
            "WechatFriends" === a ? b.content = b.content.replace("xxx", g.getRemain()) : "WechatTimeline" === a && (b = k.UC_SHARE.TIMELINE, b.title = b.title.replace("xxx", g.getRemain())), b.uclink = n, e.$("#i-sharemask-uc").hide(), setTimeout(function () {
                h(b.title, b.content, b.uclink, a, b.domid, "")
            }, 200)
        }
    }

    var e = a("utils/utils"), f = a("widget/mask"), g = a("index/store"), h = a("cmp/share"), i = a("utils/pf"), j = a("appData").RENDER, k = a("appData").CONF, l = a("cmp/jweixin-1.0.0"), m = a("widget/observer"), n = window.location.protocol + "//" + window.location.host + "/running/" + g.ownerInfo.magic + "/index?uc_param_str=" + k.UC_PARAMS + "&entry=indexshare" + k.SHARE_PARAM;
    b.share = function () {
        "wechat" === i() ? f.showShare() : "uc" === i() && d("WechatFriends")
    }, b.updateRemain = function () {
        l.onMenuShareTimeline(c("timeline")), l.onMenuShareAppMessage(c("friend"))
    }, b.init = function () {
        e.$("#i-sharemask-uc").addEventListener("click", function () {
            e.$("#i-sharemask-uc").hide(), m.emit("log", {page: "click_share", step: 2, share_status: "cancel"})
        }), e.$("#i-shareuc-friend").addEventListener("click", function (a) {
            d("WechatFriends"), m.emit("log", {
                page: "click_share",
                step: 2,
                share_type: "friend",
                share_status: "ok"
            }), e.cancelBubble(a)
        }), e.$("#i-shareuc-line").addEventListener("click", function (a) {
            d("WechatTimeline"), m.emit("log", {
                page: "click_share",
                step: 2,
                share_type: "timeline",
                share_status: "ok"
            }), e.cancelBubble(a)
        }), window.addEventListener("load", function () {
            "wechat" === i() && (g.sdkConfig.debug = k.DEBUG, g.sdkConfig.jsApiList = ["onMenuShareTimeline", "onMenuShareAppMessage"], l.config(g.sdkConfig), l.ready(function () {
                l.onMenuShareTimeline(c("timeline")), l.onMenuShareAppMessage(c("friend"))
            }))
        })
    }
}), define("index/store", ["appData", "appData", "cmp/net", "cmp/storage", "utils/pf", "utils/utils", "cmp/silly-encrypt"], function (a, b, c) {
    "use strict";
    function d() {
        this.curDis = 0, this.bestDis = 0, this.run = function (a, b) {
            function c(a) {
                return a.substr(7, 6)
            }

            function d() {
                return a > f.getRemain() ? f.records.length + 1 : 0
            }

            function e() {
                f.bestDis = f.curDis
            }

            var f = this, h = +new Date, l = parseInt(c(f.time + "") + c(h + "")), m = k.encode(a, l);
            this.curDis = a, this.curDis > this.bestDis && "others" !== i() ? (g.get("run", {
                token: m,
                time: h,
                finish: d()
            }, function (c) {
                c.success ? (f.records = c.data, f.isOwner() && (f.ownerInfo.dist = a), f.save(), b()) : (j.$("#i-error-code").txt(c.code), f.error = !0, b())
            }, function () {
                f.error = !0, b()
            }), e()) : b()
        }, this.invite = function (a) {
            var b = this;
            g.get("invite", {}, function (c) {
                c.success && (b.ownerInfo.isShared = !0, b.save(), a())
            })
        }, this.isOwner = function () {
            return this.ownerInfo.userId && this.userInfo.userId ? this.ownerInfo.userId === this.userInfo.userId : !1
        }, this.init = function () {
            return this.userInfo.nick = this.userInfo.nick || f.DEFAULT.nick, this.restore(), this
        }, this.save = function () {
            h.clearLocal(), h.setLocal(this.seq, {records: this.records, ownerInfo: this.ownerInfo})
        }, this.restore = function () {
            var a = h.getLocal(this.seq);
            a && (this.records = a.records, this.ownerInfo = a.ownerInfo)
        }, this.getTotalDis = function () {
            for (var a = this.records.length, b = 0; a--;)b += this.records[a].dist;
            return b
        }, this.getRemain = function () {
            var a = f.TOTAL_DIS - this.getTotalDis();
            return 0 > a ? 0 : a
        }
    }

    var e = a("appData").RENDER, f = a("appData").CONF, g = a("cmp/net"), h = a("cmp/storage"), i = a("utils/pf"), j = a("utils/utils"), k = a("cmp/silly-encrypt");
    d.prototype = e, c.exports = new d
}), define("index/tpl", ["index/store", "appData", "appData"], function (a, b) {
    "use strict";
    var c = document, d = a("index/store"), e = a("appData").MSG, f = a("appData").FILTER;
    b.record = function (a) {
        var b, g = c.createElement("li"), h = c.createElement("span"), i = c.createElement("img"), j = c.createElement("h3"), k = c.createElement("p"), l = c.createElement("time"), m = c.createElement("em");
        return i.src = a.avatar, j.innerText = f.nick(a.nick), l.innerText = f.time(a.createTime), b = d.ownerInfo.userId === d.userInfo.userId ? e.i : e.he, m.innerText = a.runnerUserId === d.ownerInfo.userId ? f.dist1(a) : f.dist2(b, a), g.appendChild(h), g.appendChild(j), g.appendChild(k), h.appendChild(i), k.appendChild(l), k.appendChild(m), g
    }, b.node = function (a, b, d) {
        function e(a) {
            function b() {
                g = 0 === g ? 1 : 0
            }

            function c() {
                for (; a >= e;) {
                    if (a === e)return !0;
                    e += f[d][g], b(g)
                }
                return !1
            }

            var d, e, f = [[7, 1], [5, 3], [3, 5], [1, 7]], g = 0;
            return e = d = 0, c() ? 0 : (e = d = 1, g = 0, c() ? 1 : (e = d = 2, g = 0, c() ? 2 : 3))
        }

        var f = c.createElement("li"), g = c.createElement("section"), h = c.createElement("span"), i = c.createElement("img"), j = c.createElement("p"), k = b % 8 + 1, l = parseInt((b + 1) / 4), m = e(b + 1);
        return f.appendChild(g), f.appendChild(h), f.appendChild(j), h.appendChild(i), f.style.top = 7 + 62 * l + "px", f.style.left = 29 + 74 * m + "px", 0 === a.type ? (i.src = a.avatar, j.innerText = a.dist + "m", g.className = "i-node-" + k) : 1 === a.type ? (h.className = "i-node-null", g.className = "i-node-" + k + "-g") : (h.className = "i-node-final", g.className = "i-node-" + k, d > 0 && (g.className += "-g")), f
    }
}), define("utils/image", [], function (a, b) {
    "use strict";
    b.init = function (a) {
        for (var b, c, d, e = a.length; e--;)for (b = a[e], c = document.querySelectorAll(b.q), d = c.length; d--;)c[d].style.background = b.bg, c[d].style.backgroundSize = b.bgSize
    }
}), define("utils/lock", [], function (a, b, c) {
    "use strict";
    var d = {};
    c.exports = function (a, b) {
        d[a] || (d[a] = !0, b(function () {
            d[a] = !1
        }))
    }
}), define("utils/log", ["cmp/net"], function (a, b, c) {
    "use strict";
    var d = a("cmp/net");
    c.exports = function (a, b) {
        function c() {
            b && b()
        }

        d.get("../log", a, function () {
            c()
        }, function () {
            c()
        })
    }
}), define("utils/pf", [], function (a, b, c) {
    "use strict";
    var d, e = window.navigator.userAgent.toLowerCase();
    c.exports = function () {
        return d || (d = e.indexOf("ucbrowser") >= 0 ? "uc" : e.indexOf("micromessenger") >= 0 ? "wechat" : "others"), d
    }
}), define("utils/timer", [], function (a, b) {
    "use strict";
    b.countdown = function (a, b, c, d) {
        var e = 0, f = setInterval(function () {
            c(), e += b, e > a && -1 !== a && (clearInterval(f), d && d())
        }, b)
    }
}), define("utils/utils", [], function (a, b, c) {
    "use strict";
    function d(a) {

        function b(a) {
            return a && (a.hide = function () {
                this.style.display = "none"
            }, a.show = function () {
                this.style.display = "block"
            }, a.txt = function (a) {
                this.innerText = a
            }), a
        }

        function c(a) {
	
            for (var c = a.length; c--;)a[c] = b(a[c]);
            return a.txt = function (b) {
                for (c = a.length; c--;)a[c].txt(b)
            }, a.show = function (b) {

                for (c = a.length; c--;)a[c].show(b)
				
            }, a.hide = function (b) {
                for (c = a.length; c--;)a[c].hide(b)
            }, a
				
        }

        var d, e;
        return 0 === a.indexOf("#") ? (d = u.querySelector(a), b(d)) : (e = u.querySelectorAll(a), c(e), e)
    }

    function e(a, b) {
        function c() {
            v[a] = e, d()
        }

        function d() {
            b && b.call(e)
        }

        var e = v[a];
        e ? d() : (e = new Image, e.src = a, e.complete && c(), e.onload = c)
    }

    function f(a) {
        return a.replace(/(^\s*)|(\s*$)/g, "")
    }

    function g(a, b) {
        try {
            return a.className.match(new RegExp("(\\s|^)" + b + "(\\s|$)"))
        } catch (c) {
            return !1
        }
    }

    function h(a, b) {
        if (a.hasOwnProperty("length"))for (var c = 0; c < a.length; c++)h(a[c], b); else if (!g(a, b)) {
            var d = f(a.className) + " " + b;
            a.className = d
        }
    }

    function i(a, b) {
        if (a.hasOwnProperty("length"))for (var c = 0; c < a.length; c++)i(a[c], b); else {
            if ("undefined" == typeof b)return void(a.className = "");
            if (g(a, b)) {
                var d = new RegExp("(\\s|^)" + b + "(\\s|$)");
                a.className = a.className.replace(d, " ")
            }
        }
    }

    function j() {
        document.addEventListener("touchmove", function (a) {
            a.preventDefault()
        })
    }

    function k(a) {
        return a.replace(/[ ]/g, "").length <= 0 ? !0 : !1
    }

    function l(a) {
        var b = /^(1700)\d{7}$/;
        return b.test(a) ? !0 : !1
    }

    function m(a) {
        var b = /^((13[0-9])|(14[5,7,9])|(15[^4,\D])|(17[0,6-8])|(18[0-9]))\d{8}$/;
        return b.test(a) ? !0 : !1
    }

    function n(a) {
        var b = /^((133|149|153|177|180|181|189)\d{8}|(170[1-9])\d{7})$/;
        return b.test(a) ? !0 : !1
    }

    function o() {
        return w.indexOf("iphone") > -1 || w.indexOf("ipad") > -1 ? !0 : !1
    }

    function p(a) {
        var b = u.createElement("iframe");
        b.style.display = "none", b.src = o() ? "ucbrowser://" + a : "ucweb://|" + a, b.src += b.src.indexOf("?") < 0 ? "?fromCallUc=true" : "&fromCallUc=true", u.body.appendChild(b)
    }

    function q(a) {
        a.stopPropagation ? a.stopPropagation() : a.cancelBubble = !0
    }

    function r(a) {
        return a.replace(/[ ]/g, "").length <= 0
    }

    function s(a, b) {
        y[a] || (y[a] = !0, b())
    }

    var t = window, u = document, v = {}, w = t.navigator.userAgent.toLowerCase(), x = function (a, b) {
        var c = new Date(a), d = function (a) {
            return (10 > a ? "0" : "") + a
        };
        return b.replace(/yyyy|MM|dd|HH|mm|ss/g, function (a) {
            switch (a) {
                case"yyyy":
                    return d(c.getFullYear());
                case"MM":
                    return d(c.getMonth() + 1);
                case"mm":
                    return d(c.getMinutes());
                case"dd":
                    return d(c.getDate());
                case"HH":
                    return d(c.getHours());
                case"ss":
                    return d(c.getSeconds())
            }
        })
    }, y = [];
    c.exports = {
        $: d,
        preImage: e,
        trim: f,
        hasClass: g,
        addClass: h,
        removeClass: i,
        tMovePreDef: j,
        txtNull: k,
        checkPhone1700: l,
        checkPhone: m,
        checkTelPhone: n,
        isIOS: o,
        callUcbrowser: p,
        format: x,
        cancelBubble: q,
        isInputNull: r,
        once: s
    }
}), define("widget/download", ["utils/utils", "utils/pf", "appData", "widget/observer"], function (a, b, c) {
    "use strict";
    var d = window.location, e = a("utils/utils"), f = a("utils/pf"), g = a("appData").CONF, h = a("widget/observer");
    c.exports = function () {
        e.isIOS() ? "wechat" === f() ? (h.emit("log", {
            page: "download",
            os: "ios"
        }), d.href = g.DOWNLOAD_URL.iosQQ) : (h.emit("log", {
            page: "download",
            os: "ios"
        }), d.href = g.DOWNLOAD_URL.iosStore) : "wechat" === f() ? (h.emit("log", {
            page: "download",
            os: "android"
        }), d.href = g.DOWNLOAD_URL.androidQQ) : (h.emit("log", {
            page: "download",
            os: "android"
        }), d.href = g.DOWNLOAD_URL.android)
    }
}), define("widget/mask", ["appData", "utils/utils"], function (a, b, c) {
    "use strict";
    function d() {
        this.init = function () {
            var a = this.m = e.createElement("section");
            a.style.display = "none", a.style.position = "fixed", a.style.top = 0, a.style.left = 0, a.style.width = "100%", a.style.height = "100%", a.style.background = "rgba(0, 0, 0, .6)", a.style.zIndex = 1e3, a.addEventListener("click", function () {
                this.style.display = "none", this.imgOpen && (this.imgOpen.style.display = "none"), this.imgShare && (this.imgShare.style.display = "none")
            }), e.body.appendChild(a)
        }, this.showDownload = function () {
            this.show("imgDownload", f.MASK_SRC.download)
        }, this.showReward = function () {
            g.isIOS() ? this.show("imgReward", f.MASK_SRC.rewardIos) : this.show("imgReward", f.MASK_SRC.rewardAndroid)
        }, this.showShare = function () {
            this.show("imgShare", f.MASK_SRC.share)
        }, this.show = function (a, b) {
            var c = this;
            this[a] ? (this.m.style.display = "block", this[a].style.display = "block") : (this[a] = e.createElement("img"), this[a].onload = function () {
                var a = this.style;
                a.position = "absolute", a.top = "10px", a.right = "20px", a.width = "250px", c.m.appendChild(this), c.m.style.display = "block"
            }, this[a].src = b)
        }, this.init()
    }

    var e = document, f = a("appData").CONF, g = a("utils/utils");
    c.exports = new d
}), define("widget/observer", ["cmp/observer"], function (a, b, c) {
    "use strict";
    c.exports = new (a("cmp/observer"))
});
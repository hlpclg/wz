$(function () {
    var b = {};
    if (getCookie("headinaguid") == null) {
        b.uid = guidGenerator();
        setCookie("headinaguid", b.uid)
    } else {
        b.uid = getCookie("headinaguid")
    }
    if (document) {
        b.domain = document.domain;
        b.title = document.title;
        b.curl = document.URL;
        b.rurl = document.referrer
    } else {
        b.domain = "";
        b.title = "";
        b.curl = "";
        b.rurl = ""
    }
    if (window && window.screen) {
        b.screen = window.screen.width + "*" + window.screen.height
    }
    var a = new Date();
    $.ajax({url: "http://analysis.headin.cn/tongji", type: "get", dataType: "jsonp", data: {"tt": encodeURIComponent("hd" + a.toLocaleDateString()), "uid": b.uid, "domain": b.domain, "curl": encodeURIComponent(b.curl), "rurl": encodeURIComponent(b.rurl), "ip": "192.168.1.1", "title": encodeURIComponent(b.title), "ctype": encodeURIComponent(navigator.userAgent.toLowerCase()), "screen": b.screen, "vtime": a.toISOString(), "etime": "2014-07-1 00:00:00"}})
});
function guidGenerator() {
    var a = function () {
        return(((1 + Math.random()) * 65536) | 0).toString(16).substring(1)
    };
    return(a() + a() + "-" + a() + "-" + a() + "-" + a() + "-" + a() + a() + a())
}
function setCookie(a, c) {
    var b = 3000;
    var d = new Date();
    d.setTime(d.getTime() + b * 24 * 60 * 60 * 1000);
    document.cookie = a + "=" + escape(c) + ";expires=" + d.toGMTString()
}
function getCookie(b) {
    var a, c = new RegExp("(^| )" + b + "=([^;]*)(;|$)");
    if (a = document.cookie.match(c)) {
        return unescape(a[2])
    } else {
        return null
    }
};
define(function (require, exports, module) {
    'use strict';

    var win = window,
        doc = document,
        imgMap = {},
        ua = win.navigator.userAgent.toLowerCase();

    function $(q) {

        var e, l;
        if (q.indexOf('#') === 0) {
            e = doc.querySelector(q);
            return augment(e);
        } else {
            l = doc.querySelectorAll(q);
            augmentList(l);
            return l;
        }

        function augment(e) {

            if (e) {
                e.hide = function () {

                    this.style.display = 'none';
                };
                e.show = function () {

                    this.style.display = 'block';
                };
                e.txt = function (t) {

                    this.innerText = t;
                };
            }
            return e;
        }

        function augmentList(l) {

            var i = l.length;
            while (i--) {
                l[i] = augment(l[i]);
            }
            l.txt = function (t) {

                i = l.length;
                while (i--) {
                    l[i].txt(t);
                }
            };
            l.show = function (t) {

                i = l.length;
                while (i--) {
                    l[i].show(t);
                }
            };
            l.hide = function (t) {

                i = l.length;
                while (i--) {
                    l[i].hide(t);
                }
            };
            return l;
        }
    }

    function preImage(url, callback) {

        var img = imgMap[url];

        if (img) {
            cb();
        } else {
            img = new Image();
            img.src = url;

            if (img.complete) {
                imgReady();
            }

            img.onload = imgReady;
        }


        function imgReady() {

            imgMap[url] = img;
            cb();
        }

        function cb() {

            if (callback) {
                callback.call(img);
            }
        }
    }

    function trim(str) {
        // 用正则表达式将前后空格
        // 用空字符串替代。
        return str.replace(/(^\s*)|(\s*$)/g, '');
    }

    function hasClass(ele, cls) {
        try {
            return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
        } catch (e) {
            return false;
        }

    }

    function addClass(ele, cls) {
        //如果ele是nodeList,则递归执行
        if (ele.hasOwnProperty('length')) {
            for (var i = 0; i < ele.length; i++) {
                addClass(ele[i], cls);
            }
        } else {
            if (!hasClass(ele, cls)) {
                var classNames = trim(ele.className) + ' ' + cls;
                ele.className = classNames;
            }
        }
    }

    function removeClass(ele, cls) {
        //如果ele是nodeList,则递归执行
        if (ele.hasOwnProperty('length')) {
            for (var i = 0; i < ele.length; i++) {
                removeClass(ele[i], cls);
            }
        } else {
            if (typeof cls === 'undefined') {
                ele.className = '';
                return;
            }
            if (hasClass(ele, cls)) {
                var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
                ele.className = ele.className.replace(reg, ' ');
            }
        }
    }

    function tMovePreDef() {
        document.addEventListener('touchmove', function (e) {
            e.preventDefault();
        });
    }

    function txtNull(val) {
        if (val.replace(/[ ]/g, '').length <= 0) {
            return true;
        } else {
            return false;
        }
    }

    function checkPhone1700(val) {
        var re = /^(1700)\d{7}$/;
        if (re.test(val)) {
            return true;
        } else {
            return false;
        }
    }

    function checkPhone(val) {
        var re = /^((13[0-9])|(14[5,7,9])|(15[^4,\D])|(17[0,6-8])|(18[0-9]))\d{8}$/;
        if (re.test(val)) {
            return true;
        } else {
            return false;
        }
    }

    function checkTelPhone(val) {
        var re = /^((133|149|153|177|180|181|189)\d{8}|(170[1-9])\d{7})$/;
        if (re.test(val)) {
            return true;
        } else {
            return false;
        }
    }

    function isIOS() {

        if (ua.indexOf('iphone') > -1 || ua.indexOf('ipad') > -1) {
            return true;
        }
        return false;
    }

    function callUcbrowser(url) {

        var iframe = doc.createElement('iframe');

        iframe.style.display = 'none';

        if (isIOS()) {
            iframe.src = 'ucbrowser://' + url;
        } else {
            iframe.src = 'ucweb://|' + url;
        }

        if (iframe.src.indexOf('?') < 0) {
            iframe.src += '?fromCallUc=true';
        } else {
            iframe.src += '&fromCallUc=true';
        }

        doc.body.appendChild(iframe);
    }

    var format = function (time, format) { //format(1396178344662, 'yyyy-MM-dd HH:mm:ss')
        var t = new Date(time);
        var tf = function (i) {
            return (i < 10 ? '0' : '') + i;
        };
        return format.replace(/yyyy|MM|dd|HH|mm|ss/g, function (a) {
            switch (a) {
            case 'yyyy':
                return tf(t.getFullYear());
            case 'MM':
                return tf(t.getMonth() + 1);
            case 'mm':
                return tf(t.getMinutes());
            case 'dd':
                return tf(t.getDate());
            case 'HH':
                return tf(t.getHours());
            case 'ss':
                return tf(t.getSeconds());
            }
        });
    };

    function cancelBubble(e) {

        if (e.stopPropagation) {
            e.stopPropagation();
        } else {
            e.cancelBubble = true;
        }
    }

    function isInputNull(val) {
        return val.replace(/[ ]/g, '').length <= 0;
    }

    var onceSet = [];

    function once(key, fun) {

        if (!onceSet[key]) {
            onceSet[key] = true;
            fun();
        }
    }

    module.exports = {
        $: $,
        preImage: preImage,
        trim: trim,
        hasClass: hasClass,
        addClass: addClass,
        removeClass: removeClass,
        tMovePreDef: tMovePreDef,
        txtNull: txtNull,
        checkPhone1700: checkPhone1700,
        checkPhone: checkPhone,
        checkTelPhone: checkTelPhone,
        isIOS: isIOS,
        callUcbrowser: callUcbrowser,
        format: format,
        cancelBubble: cancelBubble,
        isInputNull: isInputNull,
        once: once
    };

    // window.addEventListener('error', function (e) {

    //     alert('message:' + e.message + '`filename:' + e.filename + '`lineno:' + e.lineno);
    // });
});

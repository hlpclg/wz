define(function (require, exports) {
    'use strict';

    var baseRequestParams = {}, // 每个请求都会带的参数，可通过 baseParam 设置
        ucParam; // 为 url 添加 uc 公参和 entry 参数的方法

    /**
     * 获取对象的类型
     * @param {any} obj - 被检查的对象
     */
    function getType(obj) {
        var type;
        if (obj == null) {
            type = String(obj);
        } else {
            type = Object.prototype.toString.call(obj).toLowerCase();
            type = type.substring(8, type.length - 1);
        }
        return type;
    }

    /**
     * 遍历数组或对象，当迭代函数返回 false 时终止
     * @param {object|array} obj - 需要遍历的对象或数组
     * @param {function} iterator - 迭代器，遍历时调用的迭代函数
     * @param {object} context - 迭代器的 this 上下文对象
     */
    function each(obj, iterator, context) {
        /*jshint curly: false */
        var i,
            l,
            type;
        if (typeof obj !== 'object') {
            return;
        }
        type = getType(obj);
        context = context || obj;
        if (type === 'array' || type === 'arguments' || type === 'nodelist') {
            for (i = 0, l = obj.length; i < l; i++) {
                if (iterator.call(context, obj[i], i, obj) === false) {
                    return;
                }
            }
        } else {
            for (i in obj) {
                if (obj.hasOwnProperty(i)) {
                    if (iterator.call(context, obj[i], i, obj) === false) {
                        return;
                    }
                }
            }
        }
    }

    /**
     * 浅复制
     * @params {object...} args - 多个被复制的对象
     */
    function extend() {
        var obj = {};
        each(arguments, function (arg) {
            each(arg, function (val, key) {
                obj[key] = val;
            });
        });
        return obj;
    }

    /**
     * 获取 url 上的 search 参数值
     * @param {string} key - 参数名
     * @param {string} url - 被查找的 url，默认为当前的 location
     */
    function getQueryParam(key, url) {
        url = url || location.search;
        var hashIndex = url.indexOf('#'),
            keyMatches;
        if (hashIndex > 0) {
            url = url.substr(0, hashIndex);
        }
        keyMatches = url.match(new RegExp('[?|&]' + encodeURIComponent(key) + '=([^&]*)(&|$)'));
        return keyMatches ? decodeURIComponent(keyMatches[1]) : '';
    }

    /**
     * 将 queryString 拼接在 url 后面
     * @param {string} url - 拼接的 url
     * @param {string} query - queryString
     */
    function appendQuery2Url(url, query) {
        if (query) {
            url += (url.indexOf('?') < 0 ? '?' : '&') + query.replace(/^[?|&]+/, '');
        }
        return url;
    }

    /**
     * 序列化对象 {a: 1, b: 2, c: 'd e'} 为 'a=1&b=2&c=d+e' 形式的 querystring
     * @param {object} data - 被转化的对象
     * @param {string} appendTo - ，追加 querystring 的 url
     * @return {string} - 若指定 appendTo，则返回追加 querystring 后的 url，否则直接返回 querystring
     */
    function parseObject2QueryString(data, appendTo) {
        var stack = [],
            query;

        each(data, function (value, key) {
            stack.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
        });
        query = stack.join('&').replace(/%20/g, '+');

        if (getType(appendTo) === 'string') {
            return appendQuery2Url(appendTo, query);
        } else {
            return query;
        }
    }

    /**
     * 为 url 添加 uc 公参和 entry 参数
     * @params {string} url - 需要被添加公参的 url 地址
     */
    ucParam = (function () {
        var query,
            entry,
            ucParamStr,
            i = 0,
            pName,
            data = {},
            validLen;

        // 兼容 WP 在 ajax 的时候不带公参
        if (getQueryParam('fr') === 'wp') {
            ucParamStr = getQueryParam('uc_param_str');
            validLen = ucParamStr.length - ucParamStr.length % 2;
            while (i < validLen) {
                pName = ucParamStr.substr(i, 2);
                data[pName] = getQueryParam(pName);
                i += 2;
            }
        }
        entry = getQueryParam('entry');
        if (entry) {
            data.entry = entry;
        }
        query = parseObject2QueryString(data);
        return function (url) {
            if (!getQueryParam('uc_param_str'), url) {
                url = appendQuery2Url(url, 'uc_param_str=dnfrpfbivesscpgimibtbmntnisieijblauputog');
            }
            return appendQuery2Url(url, query);
        };
    })();

    /**
     * ajax 方法(只支持 GET 和 POST)
     * @params {object} options - ajax 的配置
     */
    function ajax(options) {
        options = options || {};
        var type = options.type || 'GET',
            url = ucParam(options.url || ''),
            params = extend(baseRequestParams, options.data),
            success = options.success,
            error = options.error,
            xhr = new XMLHttpRequest(),
            querystring;
        url = parseObject2QueryString({
            _t: +new Date()
        }, url);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    if (success) {
                        success(JSON.parse(xhr.responseText));
                    }
                } else if (error) {
                    error(xhr);
                }
            }
        };
        type = type.toUpperCase() === 'POST' ? 'POST' : 'GET';
        try {
            if (type === 'POST') {
                querystring = new FormData();
                each(params, function (val, key) {
                    if (key) {
                        querystring.append(key, getType(val) === 'array' ? val.join() : val);
                    }
                });
                xhr.open(type, url, true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send(querystring);
            } else {
                querystring = parseObject2QueryString(params);
                xhr.open(type, url + '&' + querystring, true);
                xhr.send();
            }
        } catch (e) {
            console.error('ajax error', e);
            // do nothing
        }
    }

    /**
     * 创建一个 GET 或 POST 的 ajax 的 快捷方法
     * @params {string} type - ajax 的 method，'GET' 或 'POST'
     */
    function _typeAjax(type) {
        return function (url, params, success, error) {
            ajax({
                type: type,
                url: url,
                data: params,
                success: success,
                error: error
            });
        };
    }

    /**
     * ping 一个 url 地址
     * @param {string} url - 访问的地址
     * @param {object} data - get 参数
     */
    exports.ping = function (url, data) {
        var img = new Image();
        if (data) {
            url = parseObject2QueryString(data, url);
        }
        img.src = ucParam(url) + '&__t=' + (+new Date());
    };

    /**
     * 设置、获取 baseParam
     * @param {string|object} name - 若为 string，则为被设置、获取的参数名；若为 object，则为设置的对象，并忽略后面的参数
     * @param {string} val - 若为不传参，则返回前面的 name 参数值；否则设置 name 的值为 val
     */
    exports.baseParam = function (name, val) {
        if (getType(name) === 'string') {
            if (arguments.length === 1) {
                return baseRequestParams[name];
            } else {
                baseRequestParams[name] = val;
            }
        } else if (getType(name) === 'object') {
            baseRequestParams = extend(baseRequestParams, name);
        }
    };

    exports.query = getQueryParam;
    exports.parseQuery = parseObject2QueryString;
    exports.ucParam = ucParam;
    exports.ajax = ajax;
    exports.get = _typeAjax('GET');
    exports.post = _typeAjax('POST');
});

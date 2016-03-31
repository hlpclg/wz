// define('lib/global_v2', function(require, exports, module) {
(function($, doc) {             
    // var $ = Zepto, doc = document;

    // 兼容处理，兼容以前的代码
    // $.sessionCache = $.sessionS;
        
    //解决触摸屏click事件延迟问题
    FastClick.attach(doc.body);
    
    //解决textarea placeHolder不换行bug
    var PlaceHolder = {
        init: function() {
            var inputs = document.getElementsByTagName("textarea");
            PlaceHolder.create(inputs);
        },
        create: function(inputs) {
            var input;
            if (!inputs.length) {
                inputs = [inputs];
            }
            for (var i = 0, length = inputs.length; i < length; i++) {
                input = inputs[i];
                if (input.attributes && input.attributes.placeholder2) {
                    PlaceHolder._setValue(input);
                    input.addEventListener("focus", function(e) {
                        if (this.value === this.attributes.placeholder2.nodeValue) {
                            this.value = "";
                            this.style.color = "";
                        }
                    }, false);
                    input.addEventListener("blur", function(e) {
                        if (this.value === "") {
                            PlaceHolder._setValue(this);
                        }
                    }, false);
                }
            }
        },
        _setValue: function(input) {
            if (input.value != "" && input.value != input.attributes.placeholder2.nodeValue) return;
            input.value = input.attributes.placeholder2.nodeValue;
            input.style.color = "#999";
        }
    };

    $.extend($, {
        /*  
         *  说明：过滤XSS
         *  @param  {String}    str 需要过滤的内容
         *  @return {String}    显示的内容
         */
        xss: function(str) {
            var div = doc.createElement("div"),
                text = doc.createTextNode(str), val = '';

            div.appendChild(text);
            val = div.innerHTML;
            text = null; div = null;

            return val;
        },

        // 简单的模板替换方法
        format: function(str, data) {
            var that = this;
            return str.replace(/\{([^{}]+)\}/g, function(match, key) {
                var value = data[key];
                return (value !== undefined) ? that.xss('' + value) : match;
            });
        },

        /**
         * 获取url或者自定义字符串中的参数
         * 
         * @param {String} name 不传name则直接返回整个参数对象
         * @param {String} queryStr 自定义字符串
         * @param {Boolean} [unfilter:false] 不进行参数XSS安全过滤
         * @param {Boolean} [undecode:false]] 不进行自动解码
         * @return {String|Object} 获取到的参数值或者由所有参数组成完整对象
        */
        getQuery: function(name, queryStr, unxss, undecode) {
            var str = queryStr || location.search.replace("?",""), tempArr,
                obj = {}, temp, arr = str.split("&"), len = arr.length;

            if(len > 0) {
                for(var i = 0; i < len; i++) {
                    try{
                        if((tempArr = arr[i].split('=')).length === 2) {
                            temp = undecode ? tempArr[1] : decodeURIComponent(tempArr[1]);
                            obj[tempArr[0]] = unxss ? temp : this.xss(temp);
                        }                        
                    }catch(e){}
                }
            }

            return name ? obj[name] : obj;
        },

        // 取得url单个参数
        getQueryStr: function(name, str) {
            var reg = new RegExp("(^|&|\\?)" + name + "=([^&]*)(&|$)", "i");
            var result = (str || location.search.substr(1)).match(reg);
            if (result != null) return decodeURIComponent(result[2]);
            return null;
        },

        /**
         * 统一给url添加参数，如果url中存在，则会自动添加token参数
         * @param  {String} str 要跳转到的url链接
         * @param  {JSON}   paramMap 一些附加参数
         * @param  {String} 拼接后的url链接
         */
        urlAddParam: function(str, paramMap) {
            var hash = '', index, indx, 
                urlQuery, strQuery, param = '';

            indx = str.indexOf('#');

            if (indx !== -1) {
                hash = str.substring(indx);
                str = str.substring(0, indx);
            }

            index = str.indexOf('?');
            urlQuery = this.getQuery();

            if(index !== -1) {
                param = str.substring(index + 1);
                str = str.substring(0, index);
            }

            strQuery = $.extend(true, {token: urlQuery.token || ''}, this.unparam(param), paramMap);

            str = str + '?' + $.param(strQuery); 

            return str + hash;
        },

        /**
         * 将参数形式字符串转为json格式
         * @param  {String} str 类似于:a=12&b=23&c=45
         * @param  {String} sep 分隔符
         * @return {JSON} JSON对象数据
         */
        unparam: function(str, sep) {
            if (typeof str !== 'string') return str; 
            if ((str = $.trim(str)).length === 0) return {};
            
            var ret = {},
                pairs = str.split(sep || '&'),
                pair, key, val, m,
                i = 0, len = pairs.length;

            for (; i < len; i++) {
                pair = pairs[i].split('=');
                key = decodeURIComponent(pair[0]);
            

                // pair[1] 可能包含gbk编码中文, 而decodeURIComponent 仅能处理utf-8 编码中文
                try {
                    val = decodeURIComponent(pair[1]);
                } catch(e) {
                    val = pair[1] || '';
                }

                if ((m = key.match(/^(\w+)\[\]$/)) && m[1]) {
                    ret[m[1]] = ret[m[1]] || [];
                    ret[m[1]].push(val);
                } else {
                    ret[key] = val;
                }
            }
            return ret;
        },

        //简单去除数组相同项
        uniq: function (arr) {
            if (!$.isArray(arr)) 
                return arr;

            var a = [],
                o = {},
                i,
                v,
                len = arr.length;

            if (len < 2) {
                return arr;
            }

            for (i = 0; i < len; i++) {
                v = arr[i];
                if (o[v] !== 1) {
                    a.push(v);
                    o[v] = 1;
                }
            }

            return a;
        },

        // 辨别内容中的电话，可以直接拨打
        identifyPhoneNum: function($node) {
            var self = this;
            var html = $node.html() || "";

            var reg = /(<a[^>]+>[^><]*)?(\d{3,4}-\d{5,8}|\d{4}-\{7,8}|((\(\d{3}\))|(\d{3}\-))?13[0123456789]\d{8}|15[012356789]\d{8}|18[0236789]\d{8}|\d{8,10}|110|120|119|10000|969368|95598|96956|96833|96968|10050|12530)(\s*转\s*[0-9]+)*([^><]*<\/a>)?/g;
            
            var tel, tels = html.match(reg), 
                htm = '', regex, sub, idx, index = 0, 
                content = '', str = '', exec = null;
        
            if (tels != null) {
                for (var i = 0, len = tels.length; i < len; i++) {
                    tel = tels[i];
                    content = htm || html;
                    
                    if (/^([0-9]|\-|转|\s|\+|\(|\))+$/.test(tel)) {
                        regex = new RegExp('(>?[^><]*[^a-zA-Z])(' + tel + ')([^><]*</)');
                        exec = regex.exec(content);

                        if (exec != null) {
                            index = exec.index + exec[1].length + exec[2].length;
                            htm = content.substring(index);
                            sub = content.substring(0, index);

                            if (/[a-zA-Z]/.test(content.charAt(index)) || 
                                content.charAt(exec.index + exec[0].length).toLowerCase() == 'a') {
                                str += sub;
                            } else { 
                                idx = sub.lastIndexOf(tel)
                                str += sub.substring(0, idx) + 
                                    sub.substring(idx).replace(tel, "<a href='tel:" + self.oneKeyToCall(tel) + "'>" + tel + "</a>");
                            }
                        }

                    }

                    if (i == len - 1) {
                        index = (htm || html).length;
                        str += (htm || html).substring(0, index);
                    }
                }
            } else {
                str = html;
            }

            if (str.length < html.length) str = html; // 确保正常显示
            str = str.replace(/(href=.tel:)([0-9]+(\s*转\s*[0-9]+)+)/g, function(match, $1, $2) {return $1 + self.oneKeyToCall($2);});

            $node.html(str);
        },

        // 一键拨号，号码中的'转'字转换为','
        oneKeyToCall: function(phoneNum) {
            return phoneNum.replace(/-/g, '').replace(/\s*转\s*/g, ',');
        },

        /**
         * 因压缩问题，该方法不要再用了
         * https://github.com/sindresorhus/multiline
         * 在JS中自然地写html，经测试(2000000次)：
         *     chrome为用'+'的10-15分之一，约2.7秒
         *     IE11为用'+'的5-6分之一，约11秒
         * @param  {Function} fn 
         * @return {String}
         */
        multiline: function (fn) {
            if (typeof fn !== 'function') {
                throw new Error('multiline need a function');
            }
            // 用正则匹配比用字符串快，此处有做ios的兼容
            return fn.toString().replace(/.*?\/\*!/, '').replace(/(!\*\/\})|(!\*\/;\})/, '');//.replace('!*/}', '').replace('!*/;}', '');
        },

        // 函数节流
        throttle: function(method, context, time) {
            clearTimeout(method.tId);
            method.tId = setTimeout(function() {
                method.call(context);
            }, time || 100);
        },

        // 取得网络类型，适用于微信
        getNetworkType: function() {
            var reg = /NetType\/([a-zA-Z0-9]+)/i,
                match = navigator.userAgent.match(reg);

            if(match) {
                return match[1].toLowerCase();
            }

            return 'fail';
        },

        // 发送测速统计
        sendSpeed: function() {
            if (typeof SPEED_TIME == 'undefined'
                || !$.isArray(SPEED_TIME)
                || typeof Speed == 'undefined') return;

            SPEED_TIME.push('footEnd:' + (+new Date()));
            var type = this.getNetworkType();
            var spd = new Speed();

            spd.setNetwork(type);
            spd.addPoints(SPEED_TIME);
            spd.send();
        },
        
        //预览图片
        previewPhoto:function(imgWrapId){
            var imgurls = [];
            var $imgs = $("#"+imgWrapId+" img");
            $imgs.on("click",imgview);
            $imgs.each(function(i,e){
                    var $e = $(e);
                    var imgsrc= $e.prop('src');
                    imgurls.push(imgsrc);
            });

            function imgview() {
                var me= $(this);
                var _src=me.prop('src');
                typeof window.WeixinJSBridge !== "undefined" && WeixinJSBridge.invoke("imagePreview", {
                    current:_src,
                    urls: imgurls
                });
            }
        },
        PlaceHolder:PlaceHolder
    });

})(Zepto, document);

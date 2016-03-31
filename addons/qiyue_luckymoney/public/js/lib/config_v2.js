(function(seajs, $, global, _doc) {
    $.extend($, {
        /**
         * 将字符串转换为JSON格式，如果参数为对象则直接返回         
         * 
         * @param {String|Object} data 需要进行格式转换的数据
         * @return {Object} 转换后的JSON数据
        */
        parseJSON: function(data) { 
            if(!data || typeof(data) != "string" ){
                return data;
            }
            data = $.trim(data);
            
            try{
                data = JSON.parse(data);
            }catch(e){
                data = (new Function("return " + data))();
            }

            return data;
        },

        // 将js嵌入到页面中执行
        htmlJs: function(data, parent) {
            (parent ? parent : this.getHeadElem()).
                append($("<script type='text/javascript'>" + data + "</script>"));
        },

        // 将css嵌入到页面中执行
        htmlCss: function(data) {
            this.getHeadElem().append($("<style type='text/css'>" + data + "</style>"));
        },

        // 取得head标签元素
        getHeadElem: function() {
            return $(_doc.getElementsByTagName("head")[0] || _doc.documentElement);
        },

        // 是否空对象或数组
        isEmptyObj: function(obj) {
            for (var i in obj) {
                return false;
            }

            return true;
        }
    });

    // =======================================存储支持==========================================
    var checkStorage = function(s) {  
        var key = "CHECK_STOARGE_TEST",
            value;                
        
        try {
            s.setItem(key, 1);                
            value = s.getItem(key);
            s.removeItem(key); 
            
            return value == 1;
        } catch(e) {
            return false;
        }        
    };

    // 存储支持情况
    try { 
        $.isSessionAble = checkStorage(sessionStorage);
        $.isLocalAble = checkStorage(localStorage);
    } catch(e) {
        $.isSessionAble = false;
        $.isLocalAble = false;
    }

    // window.name缓存，和localStorage及sessionStorage行为保持一致
    var nameStore = {
        // 刷新数据
        _flush: function(data) {
            data && (window.name = JSON.stringify(data));
        },
        getAll: function() {
            try{
                return this.data = $.parseJSON(window.name || '{}');
            }catch(e) {
                return this.data = {};
            }
        },
        setItem: function(key, value) {
            var data = this.data || this.getAll();
            if (!$.isPlainObject(data)) {
                data = {};
            }
            data[key] = value;
            this._flush(data);
        },
        getItem: function(key) {
            var data = this.data || this.getAll();
            if ($.isPlainObject(data)) {
                return data[key];
            }
        },
        removeItem: function(key) {
            var data = this.data || this.getAll();
            if ($.isPlainObject(data)) {
                delete data[key];
                this._flush(data);
            }
        }
    };

    /**
     * 存储支持
     * @param  {String} type      可选，存储类型：local、session、name、storage，默认session
     * @param  {String} nameSpace 可选，命名空间，默认使用'STORAGE_NAMESPACE'命名空间
     */
    var _Storage = function(type, nameSpace) {
        type = type || 'session'; 
        nameSpace = nameSpace || 'STORAGE_NAMESPACE';

      var 
        MAX = 40, // 最大尝试次数
        COUNT = 0, // 计数
        TIME = 1000 * 60 * 60 * 24, // 一天时间
        storageTpye = {
            local: function(key) {
                return $.isLocalAble ? // localStorage存储，如果不支持该存储方式，设置无效果，所以需要先判断是否支持local存储
                    [$.parseJSON(localStorage.getItem(key) || "{}"), localStorage] : [{}, {setItem: function(){}}];
            },
            session: function(key) { // session级缓存，sessionStorage -> window.name 逐步兼容
                return $.isSessionAble ? 
                    [$.parseJSON(sessionStorage.getItem(key) || "{}"), sessionStorage] : this.name(key);
            },
            name: function(key) { // 也是session级缓存，但是只用window.name存储
                return [nameStore.getItem(key), nameStore];
            },
            storage: function(key) { // localStorage -> sessionStorage -> window.name 逐步兼容
                return $.isLocalAble ?
                    [$.parseJSON(localStorage.getItem(key) || "{}"), localStorage] : this.session(key);
            }
        };

        var temp, storage, storageData;
        temp = storageTpye[type](nameSpace);
        storageData = temp[0]; // 存储数据
        storage = temp[1]; // 存储方式

        /**
         * 设置存储数据
         * @param {[type]} key   键名
         * @param {[type]} value 键值
         */
        var setItem = function(key, value) {
            COUNT = MAX;  //重置
            storageData[key] = {"v" : value, "t": +new Date()};
            _flush();
        };

        // 获取存储数据
        var getItem = function(key) {
            var value = storageData[key],
                vv = value &&　value.v;

            return $.isPlainObject(vv) ? $.extend(true,{},vv) : 
                $.isArray(vv) ? $.extend(true, [], vv) : vv;
        };

        // 移除存储数据
        var removeItem = function(key) {
            COUNT = MAX;  //重置
            delete storageData[key];
            _flush();
        };

        /**
         * 取得整段数据
         * @param  {Boolean} extend ，最好传入该参数为true，防止对返回的数据更改
         */
        var getAll = function(extend) {
            return extend ? $.extend(true, {}, storageData) : storageData;
        };

        // 刷入缓存数据
        var _flush = function() {
            var dataStr;

            try {
                dataStr = JSON.stringify(storageData);
            } catch(e) {
                throw new Error('JSON.stringify转化出错');
            }

            try {
                storage.setItem(nameSpace, dataStr);
            } catch(e) {
                COUNT--;
                if(COUNT >= 0) {
                    _deleteByTime();
                    _flush();
                } else {
                    throw new Error("写入存储报错");
                }
            }
        };

        // 按时间删除
        var _deleteByTime = function() {
            var old, key, now = +new Date();
            
            $.each(storageData, function(k,v){
                if(old){
                    if (now - old.t >= TIME) return false;
                    else if (old.t > v.t) { old = v; key = k;}
                }else{
                    old = v;
                    key = k;
                }
            });
            
            old && delete storageData[key];
        };

        return {
            getAll: getAll,
            setItem: setItem,
            getItem: getItem,
            removeItem: removeItem
        };
    };

    $.storage = _Storage;
    $.localS = _Storage('local');
    $.sessionS = _Storage('session');
    //暂不开放，防止内存占用及可能的数据串扰
    // $.nameS = storage('name');
    // $.storageS = storage('storage');

    // =================================以下皆为版本控制相关==================================
    var _version = +new Date(), _encode = encodeURIComponent;

    // 初始化设置
    var _Res_init = function() {
        // 是否自动更新时间戳
        var _setAutoStamp = function(autoStamp) {
            var debug = location.search
                .search('sea-debug') > 0 ? true : false;

            if(autoStamp || debug) {
                return true;
            }

            return false; 
        };

        // 是否启用本地存储
        var _setStorage = function(useStorage) {
            if (useStorage === true || useStorage == null) {
                return true && $.isLocalAble;
            }

            return false;
        };

        // 设置当有多少个文件变动时，才更新整个合并后的文件
        // 比如通过比较本地和页面的版本信息，默认发现有大于2个文件变动，则请求合并后的文件，否则分别请求
        var _setMaxNum = function(maxNum) {
            return maxNum === 0 ? 0 : (maxNum || 2);
        };

        /**
         * 设置文件版本相关信息，约定res参数格式：
         * 构建前：
         * // **G_VERSIONINFO_START**
         * var G_VERSIONINFO = [
         *     'logic/vote/app',
         *     'logic/vote/detail'
         * ]
         * // **G_VERSIONINFO_END**
         * 
         * 构建后：
         * // **G_VERSIONINFO_START**
         * var G_VERSIONINFO = [
         *     {id: 'logic/vote/app', ver: '合并后的版本号', deps: {'xx': '版本号', 'xx.css': '版本号'}},
         *     {id: 'logic/vote/detail',  ver: '合并后的版本号', deps: {'xx': '版本号', 'xx.css': '版本号'}}
         * ]
         * // **G_VERSIONINFO_END**
         * ps：deps中的组件表示合并文件的所有依赖
         */ 
        var _setResource = function() {
            var res = [], deps = {}, resource = {}, 
                gVer = typeof G_VERSIONINFO == 'undefined' ? [] : G_VERSIONINFO;

            // 已经构建
            if ($.isPlainObject(gVer[0])) {
                $.each(gVer, function(i, v) {
                    res.push({
                        id: v.id, 
                        ver: v.ver,
                        url: v.id + '-build.js'
                    });
                    $.extend(deps, v.deps);
                });

                resource = {
                    build: true, //是否构建
                    deps: deps, // 依赖
                    res: res, //seajs.use方法加载的文件
                };
            }  

            // 未构建
            if ($.type(gVer[0]) === 'string') {
                resource = {
                    build: false,
                    deps: deps,
                    res: gVer
                };
            }

            if (!$.isArray(gVer)) {
                throw new Error('custom: G_VERSONINFO参数错误');
            }

            return resource;
        };

        // 设置环境配置
        var init = function(useStorage, autoStamp, maxNum) {
            return {
                resource: _setResource(), 
                maxNum: _setMaxNum(maxNum),
                useStorage:  _setStorage(useStorage), 
                autoStamp: _setAutoStamp(autoStamp)
            };
        };        

        return {
            init: init
        };
    }();

    // =======================================资源更新========================================
    var _Res_updateRes = function() {
        var _count = 0, //计数
            _requested = {}; // 已经请求且返回的模块

        // 资源更新方案
        var _updateRes = function(callback, useStorage, autoStamp, maxNum) {
            var cof = _Res_init.init(useStorage, autoStamp, maxNum), 
                r = cof.resource, res = r.res,  build = r.build, arr = [];

            if ($.isEmptyObj(r)) {
                callback();
                return;
            }

            build ? $.each(res, function(i, v) {
                arr.push(v.id);
            }) : (arr = res);

            // 开发调试：按时间戳更新js和link标签引用的css文件
            if (cof.autoStamp) {
                $('link').each(function(i, v) {
                    arr.push(v.href.replace(/\?.*/, ''));
                }).remove();

                seajs.on('request', function(data) {
                    data.requestUri = data.requestUri + '?v=' + _version;
                });

                _callbackFn(arr, callback); return;
            }

            // 正式环境
            var deps = r.deps,  diff = {};

            build && (seajs.data.base = _seajsBase = 
                _seajsBase.replace('/js/', '/build/js/'));

            _Res_cache.setDeps(deps);
            _Res_updateRes.res = arr;
            _Res_updateRes.callback = callback;

            if(cof.useStorage) {
                diff = _Res_cache.getDiff(deps);

                build && (diff.length > cof.maxNum) ?
                    _updateWhole(res, true) :
                    _updateMods(deps, diff, build);  
            } else {
                build ? 
                    _updateWhole(res, false) : 
                    _callbackFn(arr, callback);
            }
        };

        // 最后的回调
        var _callbackFn = function(res, callback) {
            // 注册事件，适用于第三方不是seajs模块的组件
            seajs.on('request', function(data) {
                var uri = data.requestUri,
                    id = uri.replace(/.*?\/(lib[^\.]+)\.js/, '$1');

                if(_requested[id]) {
                    data.requested = true;
                    data.onRequest();
                }
                
            });

            seajs.use(res, function() {
                callback.apply(global, arguments);
            });
        };

        // 请求合并文件，全量更新
        var _updateWhole = function(res, useLocal) {
            _count = res.length;
            _count > 0 && $.each(res, function(i, v) {
                new _Loader(v).request('whole', useLocal);
            });
        };

        // 更新队列
        var _updateQueue = function() {
            if (--_count <= 0) {
                setTimeout(function(){
                    var res = _Res_updateRes.res;
                    var callback = _Res_updateRes.callback;

                    delete _Res_updateRes.res;
                    delete _Res_updateRes.callback;

                    _callbackFn(res, callback);
                },5);
            }
        };

        // 按模块更新，支持本地存储，但没有构建或差异文件数量小于maxNum
        var _updateMods = function(deps, diff, build) {
            _count = diff.length;
            var map = diff.map;

            $.each(deps, function(i, v) {
                if(!map[i]) {
                    _requested[i] = true;
                    $.htmlJs(_Res_cache.getContent(i));
                }
            });

            _count > 0 && build ?
                $.each(map, function(i, v) {
                    new _Loader({id: i, ver: v, url: i + '.js'}).request('mod', true);
                }) : _updateQueue();

        };

        var _updateRequested = function(requested) {
            $.extend(_requested, requested);
        }

        return {
            updateRes: _updateRes,
            updateQueue: _updateQueue,
            updateRequested: _updateRequested
        };
    }();

    // =======================================资源文件加载=======================================
    var _Loader = function(item){
        this.req = item;
    };
    
    _Loader.prototype = {
        /**
         * 发起资源更新的ajax请求
         * 
         * @method request
         * @private         
        */
        request: function(type, useLocal){
            var that = this;
            this.type = type;
            this.useLocal = useLocal;

            setTimeout(function(){
                $.ajax(that._getOption());
            },0);
        },
        /**
         * 获取ajax请求的参数对象
        */
        _getOption: function(){
            return {
                type    : "get", 
                dataType: "text",
                async   : true,
            
                url     : _seajsBase + this.req.url,
                data    : "v=" + this.req.ver,
                success : $.proxy(this.success, this),
                error   : $.proxy(this.error, this)
            };
        },       
        // 成功
        success: function(data) { 
            $.htmlJs(data);
            data = this.splitAndUpdateRequested(data);

            this.useLocal && (this.type == 'mod' ? 
                _Res_cache.setContent(this.req.id, data) 
                : _Res_cache.setWholeContent(data));

            _Res_updateRes.updateQueue();
        },
        // 分割数据并更新已经请求并返回的模块列表
        splitAndUpdateRequested: function(data) {
            var reg = /\/\*\s!define:\s([^!]+?)!\s\*\//;
            var arr, map = {}, dataArr = [], requested = {};

            while((arr = reg.exec(data)) !== null) {
                dataArr = data.split(arr[0]);
                map[arr[1]] = dataArr[0];
                requested[arr[1]] = true;
                data = dataArr[1];
            }

            if($.isEmptyObj(requested)) {
                map = data;
                requested[this.req.id] = true;
            }

            _Res_updateRes.updateRequested(requested);

            return map;
        },
        // 失败
        error: function() {
            _Res_updateRes.updateQueue();                
        }
    };

    // =======================================存储控制=======================================
    var _Res_cache = {   
        // 向本地存储写入内容
        setContent: function(id, code) {           
            if(id && code){
                this._getStorage().setItem(id, {
                    id: id,
                    code: code, 
                    ver: this.deps[id],
                    length: _encode(code).length
                });
            }            
        },
        
        // 删掉存储中已经废弃的数据
        delContent: function(id) {
            this._getStorage().removeItem(id);
        },

        // 获取存储中的数据
        getContent: function(id) {
            var o = this._getAllData()[id] || {};
            return (o && o.v && o.v.code) || "";
        },
        
        // 获取存储中的版本号
        getVersion: function(id) {
            var o = this._getAllData()[id] || {};
            return (o && o.v && o.v.ver) || 0;
        },

        // 取得存储对象
        _getStorage: function() {
            return this.storage || (this.storage = _Storage('local', 'SEAJSMODSTORAGE'));
        },
        
        // 获取存储中的所有数据
        _getAllData: function() {
            return this.data || (this.data = this._getStorage().getAll());
        },

        // 取得存储中的所有版本信息
        _getWholeVersion: function() {
            var data = this._getAllData(), 
                mod, map = {};

            for(var i in data) {
                mod = data[i].v;

                if (mod && mod.length === _encode(mod.code).length) {
                    map[i] = mod.ver;
                } else {
                    map[i] = 0;
                }
            }

            return map;
        },

        // 设置依赖信息
        setDeps: function(deps) {
            this.deps = deps;
        },

        // 取得存储中和当前版本的不同项
        getDiff: function(deps) {
            var local = this._getWholeVersion(),
                len = 0, map = {};

            $.each(deps, function(i, v) {
                if(deps[i] != local[i]) {
                    len++; 
                    map[i] = deps[i];
                }
            });

            return {
                map: map,
                length: len
            };
        },

        // 设置整块的内容
        setWholeContent: function(map) {
            var that = this;

            $.each(map, 
                function(i, v) {
                    that.setContent(i, v);
                });
        }

        /**
         * 分割数据
         * 默认格式: /* !define: id! *\/
         */
        /*_splitContent: function(data) {
            var  reg = /\/\*\s!define:\s([^!]+?)!\s\*\//;
            var arr, map = {}, dataArr = [];

            while((arr = reg.exec(data)) !== null) {
                dataArr = data.split(arr[0]);
                map[arr[1]] = dataArr[0];
                data = dataArr[1];
            }

            return map;
        }*/
    }; 

    // =========================================分割线===============================================
    var _seajsBase = sysinfo.MODULE_URL+'/public/js/',
        _seajsAlias = {
            'json': 'lib/json.min.js',
            'es5-safe': 'lib/iscroll/es5-safe.min.js'
        };
     
    seajs.config({
        base: _seajsBase,
        alias: _seajsAlias,

        preload: [
            this.JSON ? '' : 'json',
            Function.prototype.bind ? '' : 'es5-safe'
        ]
    });

    seajs.updateRes = _Res_updateRes.updateRes;
})(seajs, Zepto, this, document);

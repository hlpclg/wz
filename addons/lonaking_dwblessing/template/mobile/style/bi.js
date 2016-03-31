(function () {
	//通用微信接口
	var weixin = (function () {
		//微信接口类
		function WeiXin () {
			//私有变量
			this.__private__ = {
				readyArr : [],
				isInited: false
			};

			//初始化
			WeiXin._init.apply(this);
		}

		//初始化
		WeiXin._init = function () {
			var that = this;
			//事件回调函数
			var readyHandler = function () {
				// 重写weixin.invoke函数
				window.oldInvoke = WeixinJSBridge.invoke;

				WeixinJSBridge.invoke = function(type, options, cbFn) {
					try{
						var typeFilter = ['shareTimeline', 'sendAppMessage', 'shareWeibo'].join();

						// 如果不是需要监控的三种方法，则直接调用原有接口
						if(typeFilter.indexOf(type) === -1 || !that.__private__.share){
							window.oldInvoke.apply(this, arguments);
						}else{
							// 执行绑定函数,执行all和个性化type
							var tempList = (that.__private__.share[type] || []).concat(that.__private__.share['all'] || []);

							// 给options添加参数等操作
							var opts = bi && bi.__private__.shareOptions;
							for(var o in opts) {
								options[o] = opts[o];
							}

							// 重新封装回调函数
							var saveCbFn = cbFn;
							arguments[2] = function() {
								for(var it = 0; it < tempList.length; it++) {
									if(tempList[it]) tempList[it].apply(this, arguments);
								}

								saveCbFn.apply(this, arguments);
							};

							// 调用原有接口
							window.oldInvoke.apply(this, arguments);
						}

					} catch(e) {
						console.log(e);
					}
				};

				that.ready();
			};

			//注册事件
			if (document.addEventListener) {
				document.addEventListener('WeixinJSBridgeReady', readyHandler, false);
			} else if (document.attachEvent) {
				document.attachEvent('WeixinJSBridgeReady', readyHandler);
				document.attachEvent('onWeixinJSBridgeReady', readyHandler);
			}
		};

		//微信Ready事件
		WeiXin.prototype.ready = function(fn) {
			var api = this;
			if(fn){
				if(window.WeixinJSBridge){
					fn(api);
				}else{
					this.__private__.readyArr.push(fn);
				}
			}else if(window.WeixinJSBridge){
				for(var i = 0; i < this.__private__.readyArr.length; i++){
					this.__private__.readyArr[i](api);
				}
			}
		};

		//分享
		WeiXin.prototype.share = window.WEIXIN_API && window.WEIXIN_API.Share || {
			//分享到朋友圈
			timeline : function (options) {
				alert('timeline: ' + options.link);
				WeixinJSBridge.on('menu:share:timeline', function (argv) {
					WeixinJSBridge.invoke('shareTimeline', options, function(res){
						switch (res.err_msg) {
							// share_timeline:cancel 用户取消
							case 'share_timeline:cancel':
								options.cancel && options.cancel(res);
								break;
							// share_timeline:confirm 发送成功
							case 'share_timeline:confirm':
							case 'share_timeline:ok':
								options.success && options.success(res);
								break;
							// share_timeline:fail 发送失败
							case 'share_timeline:fail':
							default:
								options.fail && options.fail(res);
								break;
						}
					});
				});
			},
			//发送给好友
			message : function (options) {
				alert('message: ' + options.link);
				WeixinJSBridge.on("menu:share:appmessage", function(argv){
					WeixinJSBridge.invoke('sendAppMessage', options, function(res){
						switch (res.err_msg) {
							// send_app_msg:cancel 用户取消
							case 'send_app_msg:cancel':
								options.cancel && options.cancel(res);
								break;
							// send_app_msg:confirm 发送成功
							case 'send_app_msg:confirm':
							case 'send_app_msg:ok':
								options.success && options.success(res);
								break;
							// send_app_msg:fail 发送失败
							case 'send_app_msg:fail':
							default:
								options.fail && options.fail(res);
								break;
						}
					});
				});
			},
			//分享到微博
			weibo : function (options) {
				WeixinJSBridge.on("menu:share:weibo", function(argv){
					WeixinJSBridge.invoke('shareWeibo', {
						url : options.link,
						content : options.desc
					}, function(res){
						switch (res.err_msg) {
							// share_weibo:cancel 用户取消
							case 'share_weibo:cancel':
								options.cancel && options.cancel(res);
								break;
							// share_weibo:confirm 发送成功
							case 'share_weibo:confirm':
							case 'share_weibo:ok':
								options.success && options.success(res);
								break;
							// share_weibo:fail 发送失败
							case 'share_weibo:fail':
							default:
								options.fail && options.fail(res);
								break;
						}
					});
				});
			},
		};
		//分享所有
		WeiXin.prototype.share.all = function (options) {
			//alert(JSON.stringify(options));
			bi.__private__.shareOptions = options;
		};


		//工具条
		WeiXin.prototype.toolbar = {
			//显示工具条
			show : function() {
				WeixinJSBridge.call('showToolbar');
			},
			//隐藏工具条
			hide : function() {
				WeixinJSBridge.call('hideToolbar');
			}
		};

		//菜单
		WeiXin.prototype.menu = {
			//显示菜单
			show : function() {
				WeixinJSBridge.call('showOptionMenu');
			},
			//隐藏菜单
			hide : function() {
				WeixinJSBridge.call('hideOptionMenu');
			}
		};

		//支付
		WeiXin.prototype.pay = function() {
			// body...
		};

		//图片预览
		WeiXin.prototype.viewImage = function (curSrc, srcList) {
			if(!curSrc || !srcList || srcList.length == 0) {
				return;
			}
			WeixinJSBridge.invoke('imagePreview', {
				'current' : curSrc,
				'urls' : srcList
			});
		};

		//扫描二维码
		WeiXin.prototype.scanQRCode =function (callbacks) {
			callbacks = callbacks || {};
			WeixinJSBridge.invoke("scanQRCode", {}, function(res){
				switch (res.err_msg) {
					// 打开扫描器成功
					case 'scan_qrcode:ok':
						callbacks.success && callbacks.success(res);
						break;
					// 打开扫描器失败
					default :
						callbacks.fail && callbacks.fail(res);
						break;
				}
			});
		};

		//关闭当前页面
		WeiXin.prototype.closeWindow =function (callbacks) {
			callbacks = callbacks || {};
			WeixinJSBridge.invoke("closeWindow" ,{} ,function(res){
				switch (res.err_msg) {
					// 关闭成功
					case 'close_window:ok':
						callbacks.success && callbacks.success(res);
						break;
					// 关闭失败
					default :
						callbacks.fail && callbacks.fail(res);
						break;
				}
			});

		};

		//是否在微信
		WeiXin.prototype.isInWeiXin = (function (e) {
			return /MicroMessenger/i.test(navigator.userAgent);
		})();

		/**
		 * 分享数据
		 * @param {String} [type] 分享类型,即微信调用类型，ex:shareTimeline, sendAppMessage
		 * @param {Function} handlerFn 处理函数
		 * @returns {*}
		 * @private
		 */
		WeiXin.prototype.onShare = function(handlerFn, type) {
			if(Object.prototype.toString.call(handlerFn) != '[object Function]') return console.warn('BI._share-->arguments is not a function!');
			var share = this.__private__.share = this.__private__.share || [];
			// 如果type未传入，则绑定所有的
			type = type || 'all';
			share[type] = share[type] || [];
			share[type].push(handlerFn);
		};

		//实例化微信类
		return new WeiXin();
	})();

	//BI工具
	var util = (function (weixin) {
		//BI工具类
		function BIUtil (){
			this.__private__ = {
				//数据缓存
				dataCache : {
					ip : '',
					province : '未知地区',
					city : '未知地区',
					startTime : Date.now(),
					loadTime : 0
				},
				readyArr : [],
				isReady : false,
				isGetLocation: false
			};
			//初始化
			BIUtil._init.apply(this);
		}

		//通过jsonp方式访问脚本
		BIUtil._init = function () {
			var that = this;

			//获取加载时间
			this.bindEvent(window, 'load', function (e) {
				//加载时间 = (当前时间 - 开始时间（php输入）)
				var page_start_time = window.page_start_time;
				that.__private__.dataCache.loadTime = page_start_time ? (Date.now() - page_start_time) : (Date.now() - that.__private__.dataCache.startTime) + 600;
				//执行ready
				that.__private__.isReady = true;
				that.ready();
			});
		};

		BIUtil.prototype.channelId = [
	    	'百度',
	    	'直接[官网]',
	    	'微信',
	    	'其他',
	    	'微博'
	    ];

	    //渠道获取的基本信息
	    BIUtil.prototype.Browser = {
	    	isWX : navigator.userAgent.indexOf('MicroMessenger') >= 0 ? true : false,
	    	refer : document.referrer ? document.referrer : '',
	    	link : window.location.href,
	    	pathname_last : document.referrer.match(/^https?:\/\/.*?\/([^\?]+)/) ? '/' + document.referrer.match(/^https?:\/\/.*?\/([^\?]+)/)[1] : document.referrer.indexOf('liveapp.cn') > -1 ? '/' : ''

	    };
	    
		//绑定事件
		BIUtil.prototype.bindEvent = function (element, eventName, handler) {
			if(element.attachEvent){
				element.attachEvent('on' + eventName, handler);
			}else{
				element.addEventListener(eventName, handler, false);
			}
		}

		//移除事件
		BIUtil.prototype.unbindEvent = function (element, eventName, handler) {
			if(element.detachEvent){
				element.detachEvent('on' + eventName, handler);
			}else{
				element.removeEventListener(eventName, handler, false);
			}
		};

		//ready事件
		BIUtil.prototype.ready = function (fn) {
			var pv = this.__private__;
			// 轮询触发函数
			var fire = function() {
				for(var i = 0; i < pv.readyArr.length; i++){
					pv.readyArr[i](this);
				}
			};

			if(fn){
				if(pv.isReady){
					// 如果没有取到地区，则发起请求去取
					!pv.isGetLocation ? this.getIpLocation(function() { fn(this);}) : fn(this);
				} else {
					pv.readyArr.push(fn);
				}
			}else if(pv.isReady){
				!pv.isGetLocation ? this.getIpLocation(fire) : fire.call(this);
			}
		};

		// 调用sohu ip转地区部分代码
		BIUtil.prototype.getIpLocation = function(cbFn) {
			var pv = this.__private__;
			var dataCache = pv.dataCache;
			var that = this;

			//获取IP地址、物理地址
			this.getScript('http://pv.sohu.com/cityjson', function (e) {
				//保存ip地址
				dataCache.ip = returnCitySN.cip;
				//保存地区
				if(returnCitySN.cname.indexOf('未能识别') >= 0){
					dataCache.province = dataCache.city = '未知地区';
				}else if(returnCitySN.cname.indexOf('省') >= 0){
					var regResult = 0;//(/(^.*[省])(.*$)/ig).exec(returnCitySN.cname);
					dataCache.province = regResult[2] ? regResult[1] : regResult[0];
					dataCache.city = regResult[2] ? regResult[2] : regResult[1];
				}else{
					dataCache.province = dataCache.city = returnCitySN.cname;
				}

				pv.isGetLocation = true;
				if(cbFn) cbFn.call(that);
			});
		};

		//通过jsonp方式访问脚本
		BIUtil.prototype.getScript = function (url, callback, isJsonp) {
			//创建script标签
			var script = document.createElement('script');
			script.type = 'text/javascript';
			//回调事件
			if(callback){
				if(isJsonp){
					window.jsonp_callbacks = window.jsonp_callbacks || {};
					var callbackKey = ['fn', Math.random().toString(16).replace('0.','')].join('_');
					window.jsonp_callbacks[callbackKey] = callback;
					url = this.addQueryString(url, {callback : 'jsonp_callbacks.' + callbackKey});
				}else{
					script.onload = callback;
				}
			}
			//设置src
			script.src = url;
			//添加到head
			document.head.appendChild(script);
		};

		//格式化Url参数
		BIUtil.prototype.formatUrlParams = function(data){
			var arr = [];
			for (var name in data) {
				arr.push(encodeURIComponent(name) + '=' + encodeURIComponent(data[name]));
			}
			return arr.join('&');
		};

		//添加queryString
		BIUtil.prototype.addQueryString = function(url, queryString){
			if(typeof(queryString) == 'object'){
				queryString = this.formatUrlParams(queryString);
			}else{
				queryString = queryString.replace(/^\s+|\s+$/ig, '');
			}
			if(queryString){
				url = [url, (url.indexOf('?') >= 0 ? '&' : '?'), queryString].join('');
			}
			return url;
		};

		//Ajax
		BIUtil.prototype.ajax = function (options) {

		};

		//设置Cookie
		BIUtil.prototype.getCookie = function (key) {
			var reg = new RegExp('(^|\\s+)' + key +'=([^;]*)(;|$)');
			var regResult = document.cookie.match(reg);
			if(regResult){
				return unescape(regResult[2]);
			}else{
				return '';
			}
		};

		//获取Cookie
		BIUtil.prototype.setCookie = function (key, value, expires) {
			var cookieItem = key + '=' + escape(value);
			if(expires){
				if(typeof(expires) == 'number'){
					expires = new Date(expires);
				}
				cookieItem += ';expires=' + expires.toGMTString();
			}
			document.cookie = cookieItem;
		};

		//创建一个GUID
		BIUtil.prototype.createGuid = function () {
                //定义guid
                var guid = '';
                //创建guid
                do{
                    guid += Math.random().toString(16).replace('0.','');
                }while(guid.length < 32)
                guid = [guid.substr(0, 8), guid.substr(8, 4), guid.substr(12, 4), guid.substr(16, 4), guid.substr(20, 12)].join('-');
                //返回guid
                return guid.toUpperCase();
		};

		//获取网络类型
		BIUtil.prototype.getNetworkType = function () {
			var networkType = 'UNKNOWN';
			var result = (/NetType\/([^\s]*)/ig).exec(navigator.userAgent);
			if(result){
				networkType = result[1];
			}else if(navigator.connection){
				var connection = navigator.connection;
				var type = connection['type'];
				for(var key in connection){
					if( key != 'type' && connection[key] == type){
						networkType = key;
					}
				}
			}
			return networkType;
		};

		//获取今天日期的结束时间
		BIUtil.prototype.getToDayEndTime = function () {
			var endTime = new Date();
			endTime.setHours(23);
			endTime.setMinutes(59);
			endTime.setSeconds(59);
			endTime.setMilliseconds(999);
			return endTime.getTime();
		};

        //获取app的id值
        BIUtil.prototype.getAppID = function () {
            return window.liveApp.caseData.id;
        }

		//获取CUID
		BIUtil.prototype.getCUID = function () {
			//从Cookie获取uid
			var cuid = this.getCookie('BI_CUID');
			//如没有则创建uid
			if(!cuid){
				cuid = this.createGuid();
				var expires = new Date();
				expires.setFullYear(expires.getFullYear() + 60);
				this.setCookie('BI_CUID', cuid, expires);
			}
			//返回uid
			return cuid;
		};

		//获取UUID（UV统计）
		BIUtil.prototype.getUUID = function () {
			//从Cookie获取uid
			var uuid = this.getCookie('BI_UUID');
			//如没有则创建uid
			if(!uuid){
				uuid = this.createGuid();
				var expires = this.getToDayEndTime();
				this.setCookie('BI_UUID', uuid, expires);
			}
			//返回uid
			return uuid;
		};

		//获取PVID（PV统计）
		BIUtil.prototype.getPVID = function () {
			//如没有则创建pvid
			if(!this.__private__.pvid){
				this.__private__.pvid = this.createGuid();
			}
			//返回pvid
			return this.__private__.pvid;
		};

		//获取操作系统名称
		BIUtil.prototype.getOS = function () {
			//定义结果变量
			var name = 'Other';
			var version = '';
			//获取userAgent
			var ua = navigator.userAgent;
			//移动平台iOS探测
			var reg = /like Mac OS X|Android|Windows Phone|Symbian/ig;
			var regResult = reg.exec(ua);
			if(!regResult){
				reg = /Mac OS X|Windows NT|Linux/ig;
				regResult = reg.exec(ua);
			}
			if(!regResult){
				//返回Other
				return name;
			}else{
				//操作系统检测
				switch(regResult[0]){
					case 'like Mac OS X':
						name = 'iOS';
						reg = /(iPhone|iPod|iPad).*?OS\s*(\d*[\_|\.\d]*)/ig;
						break;
					case 'Android':
						name = 'Android';
						reg = /(Android)\s*(\d*[\.\d]*)/ig;
						break;
					case 'Windows Phone':
						name = 'Windows Phone';
						reg = /(Windows Phone)\s*[OS]*\s*(\d*[\.\d]*)/ig;
						break;
					case 'Symbian':
						name = 'Symbian';
						reg = /(Symbian)\s*[OS]*\/*\s*(\d[\.\d]*)/ig;
						break;
					case 'Mac OS X':
						name = 'OS X';
						reg = /(Mac OS X)\s*(\d*[\_|\.\d]*)/ig;
						break;
					case 'Windows NT':
						name = 'Windows NT';
						reg = /(Windows NT)\s*(\d*[\_|\.\d]*)/ig;
						break;
					case 'Linux':
						name = 'Linux';
						reg = /(Linux)\s*(i*\d*)/ig;
						break;
				}
				//获取版本号
				regResult = reg.exec(ua);
				if(regResult && regResult.length >= 3){
					version = regResult[2].replace(/\_+/ig, '.');
					reg = /^\d+\.*\d*/ig;
					regResult = reg.exec(version);
					if(regResult){
						version = regResult[0];
					}
				}
			}

			//返回操作系统名称+版本号
			return [name, version].join(' ');
		};

		BIUtil.prototype.getPhoneBrand = function () {
			var ua = navigator.userAgent;
			var regs = [];
			var result = 'other';

			regs[regs.length] = ['Sony([^ ]+)','Sony'];//
			regs[regs.length] = ['Softbank\\/[0-9]+\.0\\/([A-Za-z0-9\\-]+)','Softbank'];
			regs[regs.length] = ['ZTE[\\-|\\s|\\_]([A-Za-z0-9\\-]+)','ZTE'];
			regs[regs.length] = ['SAMSUNG[\\-|\\;]\\s*([A-Za-z0-9\\-]+)','Samsung'];
			regs[regs.length] = ['LG[\\/|\\-|E\\sVX|\\-AX]\\s*([A-Za-z0-9]+)','LG'];
			regs[regs.length] = ['Lenovo[\\-|\\_]([A-Za-z0-9]+)','Lenovo'];
			regs[regs.length] = ['Huawei\\-?([A-Za-z0-9]+)','Huawei'];
			regs[regs.length] = ['vodafone([A-Za-z0-9]+)','Huawei Vodafone'];
			regs[regs.length] = ['Dell\\s?([A-Za-z0-9]+)','Dell'];
			regs[regs.length] = ['BIRD[\\-|\\.|\\s]([A-Za-z0-9]+)','Bird'];
			regs[regs.length] = ['acer\\_([A-Za-z0-9]+)','Acer'];
			regs[regs.length] = ['(iPad)','iPad'];
			regs[regs.length] = ['(iPhone)','iPhone'];
			regs[regs.length] = ['M(3)[0-9]{2}\\sBuild','MX'];
			regs[regs.length] = ['(M040)\\sBuild','MX'];
			regs[regs.length] = ['MI\\s([0-9]+)\\sBuild','MX'];


			for (var i = 0; i < regs.length; i++) {
				var reg = new RegExp(regs[i][0],'ig');
				var regResult = reg.exec(ua);
				if(regResult){
					//魅族2
					if(regResult[1] == 'M040'){
						regResult[1] = 2;
					}

					result = regs[i][1]+' '+regResult[1];
					break;
				}
			};

			return result;
		}

		//获取浏览器名称和版本号
		BIUtil.prototype.getBrowser = function () {
			//定义结果变量
			var name = 'Other';
			var version = '';
			//获取userAgent
			var ua = navigator.userAgent;
			//移动平台iOS探测
			var reg = /MSIE|Chrome|Firefox|Opera|UCBrowser|UCWEB|Safari/ig;
			var regResult = reg.exec(ua);
			if(!regResult){
				//返回UNKNOWN
				return name;
			}else{
				//浏览器检测
				switch(regResult[0]){
					case 'MSIE':
						name = 'IE';
						reg = /MS(IE)[\/|\s]+(\d*[\.\d]*)/ig;
						break;
					case 'Chrome':
						name = 'Chrome';
						reg = /(Chrome)[\/|\s]+(\d*[\.\d]*)/ig;
						break;
					case 'Firefox':
						name = 'Firefox';
						reg = /(Firefox)[\/|\s]+(\d*[\.\d]*)/ig;
						break;
					case 'Safari':
						name = 'Safari';
						reg = /(Safari)[\/|\s]*(\d*[\.\d]*)/ig;
						break;
					case 'Opera':
						name = 'Opera';
						reg = /(Opera)[\/|\s]+(\d*[\.\d]*)/ig;
						break;
					case 'UCBrowser':
						name = 'UC';
						reg = /(UCBrowser)[\/|\s]+(\d*[\.\d]*)/ig;
						break;
					case 'UCWEB':
						name = 'UC';
						reg = /(UCWEB)[\/|\s]*(\d*[\.\d]*)/ig;
						break;
				}
				//获取版本号
				regResult = reg.exec(ua);
				if(regResult && regResult.length >= 3){
					version = regResult[2].replace(/\_+/ig, '.');
					reg = /^\d+\.*\d*/ig;
					regResult = reg.exec(version);
					if(regResult){
						version = regResult[0];
					}
				}
			}

			//返回操作系统名称+版本号
			return [name, version].join(' ');
		};

		//获取IP
		BIUtil.prototype.getIP = function () {
			return this.__private__.dataCache.ip;
		};

		//获取省
		BIUtil.prototype.getProvince = function () {
			return this.__private__.dataCache.province;
		};

		//获取城市
		BIUtil.prototype.getCity = function () {
			return this.__private__.dataCache.city;
		};

		//获取屏幕分辨率
		BIUtil.prototype.getDpi = function (e) {
			return [window.screen.width, window.screen.height].join('*');
		};

		//获取距离此刻的访问时长
		BIUtil.prototype.getRemainTime = function () {
			return Date.now() - this.__private__.dataCache.startTime;
		};

		//获取统计的时间点
		BIUtil.prototype.getSTime = function () {
			return this.__private__.dataCache.startTime;
		};

		//获取页面加载时间（第一屏显示时间）
		BIUtil.prototype.getLoadTime = function () {
			return this.__private__.dataCache.loadTime;
		};

		//浏览器语言
		BIUtil.prototype.getLang = function () {
			return (navigator.language || navigator.browserLanguage).toLowerCase();
		}

		//访问方式（微信/浏览器）
		BIUtil.prototype.getEnter = function () {
			var ua = navigator.userAgent;
			//移动平台iOS探测
			var reg = /MicroMessenger/ig;
			var regResult = reg.exec(ua);
			if(regResult){
				return 'weixin';
			}else{
				return 'browser';
			}
		}

        //渠道获取
		BIUtil.prototype.getChannelId = function(){
			var channelId = '';
			var refer = util.Browser.refer.toLocaleLowerCase();
			var hostname = window.location.hostname.toLocaleLowerCase();

			if ( /baidu.com|baidu.cn/.test(refer) ) {

				channelId = '百度';

			} else if ( refer == '' && util.Browser.isWX ) {
				channelId = '微信';
			} else if ( /liveapp.cn|liveapp.com.cn|liveapp.dev$/.test(hostname) ) {
				channelId = '直接[官网]';
			} else if ( /weibo.com/.test(refer) ) {
				channelId = '微博';
			} else {
				channelId = '其他';
			}

			channelId = util.channelId.indexOf( channelId ) + 1;

			return channelId;
		}

		/**
		 * 获取渲染时间
		 */
		BIUtil.prototype.getRenderTime = function getRenderTime() {
			try {
				var tObj = window.performance.timing;
				return 	tObj.domInteractive - tObj.domLoading;
			} catch(e) {
				console.error('get render time!');
				return -1;
			}
		};

		/**
		 * 获取网络时间
		 */
		BIUtil.prototype.getNetWorkTime = function getNetWorkTime() {
			try {
				var tObj = window.performance.timing;
				return 	tObj.responseEnd - tObj.navigationStart;
			} catch(e) {
				console.error('get net_work time!');
				return -1;
			}
		};

		//实例化工具类
		return new BIUtil();
	})(weixin);


	//BI接口
	var bi = (function (util, weixin) {
		//BI接口类
		function BI() {
			//私有变量
			this.__private__ = {
				postData : {},
				isReady : false,
				readyArr : [],
				isTest : true
			};
			//初始化
			BI._init.apply(this);
		}

		//util
		BI.prototype.util = util;

		//weixin
		BI.prototype.weixin = weixin;

		//ready事件
		BI.prototype.ready = function (fn) {
			if(fn){
				this.__private__.readyArr.push(fn);
				if(this.__private__.isReady){
					fn(this);
				}
			}else if(this.__private__.isReady){
				for(var i = 0; i < this.__private__.readyArr.length; i++){
					this.__private__.readyArr[i](this);
				}
			}
		};

		//获取基础数据
		BI._init = function() {
			var that = this;

			//设置是否为开发环境
			var domain = (/[^\.\s]+\.?(com|cn|net|org|gov|me|tv|biz|us|hkasia|co|info|name|tm|in|mobi|io|pro|la|ws|bz|vc|travel|mn|ag|tel|cm|wang|pw|cc|中国|香港|ac|bj|sh|hk|tj|cq|he|sx|nm|ln|jl|hl|js|zj|ah|fj|jx|sd|ha|hb|hn|gd|gx|hi|sc|gz|yn|xz|sn|gs|qh|nx|xj|tw|mo)(\.[^\.\s]+)*(?=$|\n|\?|\/|\#)/ig).exec(domain);
			domain = domain ? domain[0] : domain;
			//域名检测
			var ignoreDomains = '|www.lightapp.cn|www.lightapp.me|www.lightapp.mobi|www.lightapp.net|www.lightapp.org|www.lightapp.so|www.lightapp.tv|www.liveapp.cn|www.liveapp.com|www.uliveapp.cn|www.uliveapp.com|www.uliveapp.org|www.uliveapp.net|www.linklive.com|www.livelink.com|';
			that.__private__.isTest = (domain == undefined ||  ignoreDomains.indexOf(domain + '|') < 0);

			//post基础数据
			var onloadCallback = function (e) {
				util.ready(function (e) {
					//获取基础数据和用户行为数据
					BI._getBasicData.apply(that);
					//BI._getBehaviorData.apply(that);
					//获取业务数据
					that.__private__.isReady = true;
					that.ready();
					//console.log(that.__private__.postData);
					//提交数据
					BI._post.apply(that);
				});
			};
			window.attachEvent && window.attachEvent('onload', onloadCallback);
			window.addEventListener && window.addEventListener('load', onloadCallback);

			//post行为数据
			/*var onunloadCallback = function (e) {
			 BI._getBehaviorData.apply(that);
			 BI._postBehaviorData.apply(that);
			 };
			 window.attachEvent && window.attachEvent('onunload', onunloadCallback);
			 window.addEventListener && window.addEventListener('unload', onunloadCallback);*/
		};

		//BI数据push接口
		BI.prototype.push = function (serviceType, key, value) {
			var serviceKey = 'service_' + serviceType;
			var biItem = this.__private__.postData[serviceKey];
			if(!biItem){
				biItem = {
					opt : {
						service_type : serviceType
					},
					data : {}
				};
				this.__private__.postData[serviceKey] = biItem;
			}
			biItem.data[key] = value;
		};

		//BI数据push接口
		BI.prototype.get = function (serviceType, key) {
			switch(arguments.length){
				case 1:
					return this.__private__.postData['service_' + serviceType];
					break;
				case 2:
					var biData = this.__private__.postData['service_' + serviceType];
					if(biData){
						return biData[key];
					}
					return undefined;
					break;
				default:
					throw '参数输入不正确！';
					break;
			}
		};

		/**
		 * 将Post接口暴露出来，以备单独调用
		 */
		BI.prototype.post = function() {
			BI._post.apply(this, arguments);
		};

		//获取基础数据
		BI._getBasicData = function () {

			//自动获取基础数据
			//this.push(1, 'device_type', util.getDeviceType());	    //设备类型（三星/苹果/小米...）
            this.push(4001, 'app_id', util.getAppID());                 //场景app的id值
            this.push(4001, 'phone_brand', util.getPhoneBrand());		//手机品牌   
			this.push(4001, 'ua', navigator.userAgent);				    //userAgent
			this.push(4001, 'browser', util.getBrowser());				//浏览器名称
			this.push(4001, 'enter', util.getEnter());				    //浏览器名称
			this.push(4001, 'os', util.getOS());						//操作系统
			this.push(4001, 'lang', navigator.language);				//语言类型
			this.push(4001, 'ip', util.getIP());						//IP地址
			this.push(4001, 'stime', util.getSTime());					//统计时间
			//this.push(1, 'device', util.getDeviceType());			    //设备名
			//this.push(1, 'activity_id', util.getActivityID());	    //LiveApp ID
			this.push(4001, 'link', document.location.href);			//url
			this.push(4001, 'refer', document.referrer);				//refer
			this.push(4001, 'dpi', util.getDpi());						//分辨率
			this.push(4001, 'uuid', util.getUUID());					//UV统计ID
			this.push(4001, 'pvid', util.getPVID());					//PV统计ID
			this.push(4001, 'cuid', util.getCUID());					//用户唯一标识ID
			this.push(4001, 'nettype', window.wxinfo && window.wxinfo.networkType ? window.wxinfo.networkType : util.getNetworkType());			                              //网络类型
			//this.push(1, 'site_id', util.getSiteID());			    //LiveApp 所属用户 ID
			this.push(4001, 'province', util.getProvince());			//省份
			this.push(4001, 'city', util.getCity());					//城市
			this.push(4001, 'lt', util.getLoadTime());					//加载时间
			this.push(4001, 'render_time', util.getRenderTime());		// 渲染时间
			this.push(4001, 'nt_time', util.getNetWorkTime());			// 网络时间
            //this.push(4001, 'lot', window.wxinfo.location ? (window.wxinfo.location.lot ? window.wxinfo.location.lot : '') : '');                             //经度
            //this.push(4001, 'lat', window.wxinfo.location ? (window.wxinfo.location.lat ? window.wxinfo.location.lot : '') : '');                             //纬度
            this.push(4001, 'channel', util.getChannelId());            //渠道
            //alert(JSON.stringify(window.wxinfo.networkType));
			//返回基础数据
			return this.get(4001);
		};

		//提交BI数据
		BI._post = function(serviceType, callback) {
			if(typeof(serviceType) == 'undefined' || arguments.length == 0){
				for(var key in this.__private__.postData){
					if(!this.__private__.postData[key]) return console.warn('this.__private__.postData[key] is undefined' + key);
					BI._post.apply(this, [this.__private__.postData[key].opt.service_type]);
				}
			}else{
				//获取bi数据
				var biData = this.get(serviceType);
				//bi数据中的url转义
				var temp;
				for(var key in biData.data){
					temp = biData.data[key];
					if(typeof(temp) == 'string' && temp.indexOf('http') == 0){
						biData.data[key] = escape(temp);
					}
				}
				//数据提交url
				var url = ['http://121.40.184.62?p=', JSON.stringify(biData)].join('');
				//判断本地环境还是生产环境
				this.__private__.isTest = false;
				if(this.__private__.isTest){
					console.debug('BI Test:', url, biData);
				}else{
					util.getScript(url, callback);
				}
				// 提交数据后将postData删除掉 modified by zak
				delete this.__private__.postData['service_'+serviceType];
			}
		};

		//提交基础数据
		BI._postBasicData = function(callback) {
			BI._post.apply(this, [1, callback]);
		};

		//提交行为数据
		BI._postBehaviorData = function(callback) {
			BI._post.apply(this, [2, callback]);
		};

		//返回BI类的实例
		return new BI();
	})(util, weixin);


	//将保存到window命名空间下
	window.bi = bi;
})();
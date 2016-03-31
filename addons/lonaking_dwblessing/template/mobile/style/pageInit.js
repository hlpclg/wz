seajs.use(['/tpl/ylcom', '/tpl/pages/flipPage', '/tpl/base-lib/utils/comUtils'], function(o, FlipPage, comUtils) {
    // 案例类
    var Case = function() {
        this.pageInsList = [];

        //翻页接口对象
        this.flipPage = new FlipPage({
            boxSelector: '.pages',
            pageSelector: '.page'
        });
    };

    Case.fn = Case.prototype;

    /**
     * 设置案例页实例列表
     * @param {Array} list 页数组
     */
    Case.fn.setPageInsList = function setPageInsList(list) {
        this.pageInsList = list;
    };

    /**
     * 重置页动画
     *
     * @param {Object} pageIns 页实例
     */
    Case.fn.resetPage = function resetPage(pageIns) {
        if(!pageIns) return;

        _.each(pageIns.comJsList, function(it) {
            it.fireAllDecorators();
        });
    };

    /**
     * 批量重置页动画
     *
     * @param {Object} pageInsList 页实例列表
     */
    Case.fn.resetPages = function resetPages(pageInsList) {
        //数据验证
        pageInsList = pageInsList || this.pageInsList;

        //循环调用caseIns.resetPage
        var caseIns = this;
        _.each(pageInsList, function (pageIns) {
            caseIns.resetPage(pageIns);
        });
    };

    /**
     * 触发当前页的动画
     *
     * @param {Page} pageIns page实例
     */
    Case.fn.fireAnime = function fireAnime(pageIns) {
        if(!pageIns) return console.info('pageInit-->fireAnime-->pageIns is empty!');

        //caseIns
        var caseIns = this;

        if(pageIns.isComplete) {
            _.each(pageIns.comJsList, function(it) {
                it.fireAnimeDecorators();
            });

            return true;
        } else {
            setTimeout(function() {
                caseIns.fireAnime(pageIns, caseIns);
            }, 100);
        }
    };

    /**
     * 重置当前组件位置
     * @param {Page} pageIns page实例
     */
    Case.fn.fireAttrs = function fireAttrs(pageIns) {
        if(!pageIns) return console.info('pageInit-->fireAttrs-->pageIns is empty!');

        if(pageIns.isComplete) {
            _.each(pageIns.comJsList, function(it) {
                it.fireAttrsDecorators();
            });
        }
    };

    /**
     * 获取案例原始数据
     *
     * @return {Object} caseData
     */
    Case.fn.getCaseData = function () {
        var curCSave = window.liveApp.pageData || window.liveApp.caseData;
        if(!curCSave) return console.warn('container-->init-->data is empty!!');

        // 数据格式处理
        _.each(curCSave.comList, function(it) {
            // 处理bool和number
            _.each(it, function(el, key) {
                if(/^is/ig.test(key)) it[key] = el !== false && el !== 'false';
                if(!isNaN(parseInt(el, 10))) it[key] = +el;
            });
        });

        return JSON.parse(JSON.stringify(curCSave));
    };

    /**
     * 初始化全局音频组件
     */
    Case.fn.initGlobalAudio = function initGlobalAudio () {
        if(!window.caseData && !window.liveApp.caseData) return;
        var caseData = window.caseData || window.liveApp.caseData;
        // 当不存在音频时退出
        if(!caseData || !caseData.music || caseData.music.length < 3) return;

        var pos = ['right: 20px; top: 20px;', 'right: 20px; bottom: 20px;', 'left: 20px; top: 20px;', 'left: 20px; bottom: 20px;'];
        var posWarp = ['right: 0; top: 0;', 'right: 0; bottom: 0;', 'left: 0; top: 0;', 'left: 0; bottom: 0'];
        /**
         * 简单模板引擎
         */
        var simpleTplEng = function simpleTplEng(obj) {
            var domTpl = '<div id="globalAudioWarp" style="{{posWarp}}"></div><div id="globalAudio" class="ga-active" style="{{pos}}">' +
                '<audio id="globalAudioPlayer" src="{{music}}" loop autoplay="true"></audio>' +
                '</div>';

            return domTpl.replace(/({{(\w*)}})/gi, function($0, $1, $2) {
                return obj[$2];
            });
        };
        // 延迟渲染全局音乐
        window.addEventListener('load', function() {
            // style 现在只有一种之后再补充
            var div = document.createElement('div');
            div.innerHTML = simpleTplEng({
                posWarp: posWarp[caseData.musicPosition],
                pos: pos[caseData.musicPosition],
                music: caseData.music
            });

            document.querySelector('.liveApp').appendChild(div);
            setTimeout(function() {
                // 绑定事件
                $(document.body).delegate('#globalAudioWarp', comUtils.isMobile() ? 'touchend' : 'click', function (e) {
                    var $gaPlayer = $('#globalAudioPlayer')[0];
                    var $globalAudio = $('#globalAudio');
                    if ($globalAudio.hasClass('ga-active')) {
                        $globalAudio.removeClass('ga-active');
                        $gaPlayer.pause();
                    } else {
                        $globalAudio.addClass('ga-active');
                        $gaPlayer.play();
                    }

                    e.preventDefault();
                });

                $(document.body).one('touchstart', function() {
                    $('#globalAudioPlayer')[0].play();
                });
            }, 0);
        });
    };

    /**
     * 给页绑定事件
     * @param {Case} caseIns 实例
     */
    Case.fn.bindEvents = function bindEvents(caseIns) {
        //显示当前页时，显示当前页所有组件的动画
        $(document.body).delegate('.page', 'showComsAnime', function (e) {
            //重置
            $(e.target).find('.z-hasAnimationIn').css('display', 'none');
            //触发动画
            var pageIns = caseIns.pageInsList[caseIns.flipPage.currentPageIndex];
            caseIns.fireAnime(pageIns);
        });

        //激活当前页时，添加操作指引
        $(document.body).delegate('.page', 'active', function (e) {
            if (e.target.hasGuideWrap) return;
            // 如果总页面数量为一页则不添加引导箭头
            if ($('.page').length <= 1) return;
            var divEle = document.createElement('div');
            divEle.innerHTML = '<div class="u-guideWrap"><a href="javascript:void(0);" class="u-guideTop"></a></div>';
            e.target.appendChild(divEle.firstChild);
            e.target.hasGuideWrap = true;
        });
    };

    /**
     * 免费案例添加品牌感知广告
     * @param pagelist
     */
    Case.fn.appendAd = function () {
        // 获取页面数据案例
        var $lastPage = $('.page').last();
        // 加载广告类
        seajs.use('pages/append/ad/ad', function(o) {
            o.init($lastPage);
        });
    };

    /*
     * 免费场景添加运营页
     */
    Case.fn.YunYingPage = function () {
        // 非免费案例不添加运营页面
        if(!liveApp.is_ad) return;
        // 加载运营页面接口
        seajs.use('pages/append/yunying/yunying', function (o) {
            o.init()
        });
    };

    //创建案例实例
    var caseIns = new Case();
    // 绑定在window域上，以便之后进行扩展
    window.liveApp = window.liveApp || {};
    window.liveApp.caseIns = caseIns;
    // 配置seajs
    seajs.config(o.getComsLibSeajsConf('//' + location.host + '/tpl/'));
    // 初始化页
    seajs.use(['pages/common'], function(common) {
        //获取容器
        var pagesElement = document.getElementById('pages');

        try {
            // 初始化音频
            caseIns.initGlobalAudio();

            //获取caseData
            var data = caseIns.getCaseData();
            if(!data) return console.warn('data is no define !');

            //设置pageList
            var pageList = data.pageList ? data.pageList : [data];

            // 给页增加index
            _.each(pageList, function(it, index) {
                it.pageIndex = index;
            });

            // 给页绑定事件
            caseIns.bindEvents(caseIns);

            /**
             * 延迟加载,此处存在递归调用
             *
             * @param {Array} pageList 需要加载的列表
             * @param {Number} pageNo 需要加载的页数,传入0代表剩下的所有
             * @param {boolean} isNeedAppend 是否需要后续加载
             */
            var lazyLoad = function lazyLoad(pageList, pageNo, isNeedAppend) {
                // 创建Page实例添加到Page实例列表
                _.each(pageList.splice(0, pageNo === 0 ? pageList.length : pageNo), function (pageData, index) {
                    caseIns.pageInsList.push(common.init(pageData, pagesElement, caseIns.flipPage));
                });

                //刷新翻页组件
                caseIns.flipPage.refresh();

                // 如果是第一次加载
                if(isNeedAppend) {
                    //显示当前页时，显示当前页所有组件的动画
                    $('.page').first().one('out', function(e) {
                        lazyLoad(pageList, 0, false);
                        // 当页面全部初始化完成后添加 广告banner 与运营页面
                        // 初始化运营页面
                        caseIns.YunYingPage();
                        // 如果没有运营页，需要给所有的加上banner,
                        // 应客户要求，对18022id进行特殊处理
                        if(!liveApp.is_ad && liveApp.caseData.id !== '18022') caseIns.appendAd();
                        setTimeout(function () {
                            caseIns.flipPage.refresh();
                        }, 200);
                        // 监听运营页面初始化完成的自定义事件 yunyingOver 追加广告页面到运营页面的最后一页
                        $(document).one('yunyingOver', function () {
                            setTimeout(function () {
                                caseIns.appendAd();
                            },300);
                        });
                    });
                }
            };
            // 先加载2页
            lazyLoad(pageList, 4, true);
        } catch(e) {
            console.error(e);
        }

    });
});
define('lib/dialog', function(require, exports, module) {
    "require:nomunge,exports:nomunge,module:nomunge,";
    var $win = $(window),
        _winW = $win.width(),
        _winH = $win.height(),
        _isLock = false,
        _timeout = null,
        _prefix = '#dialog_js',
        _eventType = $(document).tap ? 'tap' : 'click';

    /**
     * 创建弹层 - 废弃
     */
   /* var _createBox = function(zIndex) {
        var id = _prefix + '_box',
            cls = _prefixCls + '-box',
            box = $(id), index = zIndex + 1,
            exist = box.length > 0 ? true : false;

        var dialog = exist ? box.css('z-index', index) :
            $('<div class="hide ' + cls + '" id="' + id + '">').css('z-index', index).appendTo('body');

        return dialog;
    };*/

    // 创建按钮
    var _createBtns = function(data) {
        var btns = $(_prefix + '_btns'), 
            btn, params, len = data.length,
            evt = _eventType, 
            btnCls = len >= 2 ? 'dialog-confirm-btn dialog-confirm-btn-{i}' : 'dialog-confirm-btn',
            tpl = '<a href="javascript:;" class="' + btnCls + '">{text}</a>';

        if (btns.length === 0) return;
        // 最多2个
        data = data.slice(0,2);
        btns.html('');

        $.each(data, function(i, v) {
            btn = $(tpl.replace('{text}', v[0]).replace('{i}', i));
            btns.append(btn);
            params = $.isArray(v[2]) ? v[2] : [];
            
            btn.on(evt, function(e) {        
                //将事件对象作为回调的第一个参数
                params.unshift(e);  
                
                if($.isFunction(v[1])){
                    v[1].apply(v[3], params);
                }
            });  
        });
    };

    /**
     * 创建遮罩
     */
    var _createMask = function() {
        var id = 'dialog_js_mask',
            cls = 'dialog-cls-mask',
            mask = $('#' + id);

        if(mask.length === 0) {
            mask = $('<div class="hide ' + cls + '" id="' + id + '">').appendTo('body');
        }

        return mask;
    };

    // 设置前缀
    var setPrefix = function(prefix/*, preCls*/) {
        _prefix = prefix || _prefix;
    };

    /**
     * 设置标题
     */
    var setTitle = function(title) {
        var titleNode = $(_prefix + '_title');
        titleNode.html(title); 
        return titleNode;
    };

    /**
     * 设置内容
     */
    var setContent = function(content) {
        var contentNode = $(_prefix + '_content');
        contentNode.html(content);
        return contentNode;
    };
    
    /**
     * 设置位置
     */
    var pos = function(pos) {
        pos = [].concat(pos);
        
        var dialog = $(_prefix + '_box');

        dialog.css({ 
            top : pos[0] || (_winH - dialog.height()) / 2, 
            left : pos[1] || (_winW - dialog.width()) / 2
        });

        return dialog;
    };
    
    /**
     * 是否开启锁屏
     */
    var _lock = function(lock) {
        if (_isLock) return;

        if (lock) {
            _isLock = true;
            $('#dialog_js_mask').removeClass('hide');
        }
    };
    
    /**
     * 关闭锁屏
     */
    var _unLock = function() {
        if (!_isLock) return;

        _isLock = false;
        $('#dialog_js_mask').addClass('hide');
    };

    // 显示或隐藏
    var _show = function(tag) {
        if (tag || tag == null) {
            $(_prefix + '_box').removeClass('hide');
        } else {
            $(_prefix + '_box').addClass('hide');
        }
    };
    
    /**
     * 关闭方法: 隐藏
     */
    var hide = function(prefix) {
        setPrefix(prefix);
        _unLock();
        _show(false);
    };

    /**
     * 显示方法
     */
    var show = function(cfg) {
        cfg = $.extend(true, {}, defaultCfg, cfg);

        setPrefix(cfg.prefix);
        _createMask();
        // _createBox(cfg.zIndex);

        setTitle(cfg.title);
        setContent(cfg.content);
        _createBtns(cfg.btns);

        _lock(cfg.lock);

        _show();
        pos(cfg.pos);
        _time(cfg.time);
    };
    
    /**
     * 定时关闭
     */
    var _time = function(time) {
        if ($.type(time) != 'number') 
            return;

        clearTimeout(_timeout);
        _timeout = setTimeout(function() {
            hide('#dialog_msg'); //暂时写死
        }, time);
    };
    
    /**
     * 默认配置
     */
    var defaultCfg = {
        // id前缀，记得加#
        prefix: '',

        // 内容
        content: '加载中...',
        
        // 标题
        title: '',

        //二维数组：text、handler、args、context 
        btns: [["确&nbsp;定", function(){hide();} , [] , null]],   
        
        // 位置，单个y坐标值 或 一个表示[y x]坐标的数组
        pos: [],
        
        // 自动关闭时间(毫秒)
        time: null,
        
        // 是否锁屏
        lock: true

    };
    
    module.exports = $.dialog = {
        show: show,
        hide: hide,
        mask:_createMask,
        lock:_lock,
        pos: pos,
        setTitle: setTitle,
        setContent: setContent,
        setPrefix: setPrefix
    };
    
});
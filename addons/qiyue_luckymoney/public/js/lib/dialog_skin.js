define('lib/dialog_skin', function(require, exports, module) {
    var html = 
        '<div class="dialog-cls-box hide dialog-loading-box" id="dialog_loading_box">' +
            '<div class="dialog-cls-wrap dialog-loading-wrap" id="dialog_loading_wrap">' +
               '<img class="dialog-loading-icon" src="'+sysinfo.MODULE_URL+'/public/images/loading.gif"/>' +
               '<div class="dialog-loading-content" id="dialog_loading_content">加载中...</div>' +
            '</div>' +
        '</div>' +
        
        '<div class="dialog-cls-box hide dialog-msg-box" id="dialog_msg_box">' +
            '<div class="dialog-cls-wrap dialog-msg-wrap" id="dialog_msg_wrap">' +
                '<div class="dialog-msg-content" id="dialog_msg_content">内容</div>' +
            '</div>' +
        '</div>' +
        
        '<div class="dialog-cls-box hide dialog-confirm-box" id="dialog_confirm_box">' +
            '<div class="dialog-cls-wrap dialog-confirm-wrap" id="dialog_confirm_wrap">' +
                '<div class="dialog-confirm-title" id="dialog_confirm_title">提示</div>' +
                '<div class="dialog-confirm-content" id="dialog_confirm_content">内容</div>' +
                '<div class="dialog-confirm-btns" id="dialog_confirm_btns">' +
                    '<!-- <a href="javascript:;" class="dialog-confirm-btn">确定</a> -->' +
                    '<!-- <a class="dialog-confirm-btn dialog-confirm-btn-0">取消</a> -->' +
                    '<!-- <a class="dialog-confirm-btn dialog-confirm-btn-1">确定</a> -->' +
                '</div>' +
            '</div>' +
        '</div>';
   
    var css = 
        '.dialog-cls-mask {height:100%;width:100%;position:fixed;top:0;left:0;border:none;z-index:999;background-color:rgba(0,0,0,0.5);}' +
        
        '.hide {display:none;}' +
        
        '.dialog-cls-box div, .dialog-cls-box a {font-family: "微软雅黑";}' +
        
        '.dialog-cls-box a {-webkit-tap-highlight-color: rgba(0, 0, 0, 0);}' +

        '.dialog-cls-box {position: fixed;z-index: 1000; width: 85%;}' +

        '.dialog-cls-wrap {background: #f9dabb; border-left:8px solid #cbb483; border-right:8px solid #cbb483;}' +

        '.dialog-loading-box {}' +
        '.dialog-loading-box .dialog-loading-wrap {padding:2.5em 0;}' +
        '.dialog-loading-box .dialog-loading-icon {width: 40px;height: 40px;display: block;margin: 0 auto;}' +
        '.dialog-loading-box .dialog-loading-content {color:#000;padding: 12px 10px 0 20px;text-align: center;font-size: 14px;}' +
        
        '.dialog-msg-box{}' +
        '.dialog-msg-box .dialog-msg-wrap {overflow: hidden;padding: 12px;}' +
        '.dialog-msg-box .dialog-msg-content {font-size: 1.1em;color:#69584d;text-align:center;vertical-align:middle; word-warp:break-word; word-break:break-all;}' +

        '.dialog-confirm-box {min-width: 80%}' +
        '.dialog-confirm-box .dialog-confirm-wrap {background: white;opacity: 1;padding: 0;}' +
        '.dialog-confirm-box .dialog-confirm-title {font-weight: bold;text-align: center;font-size: 16px;padding: 20px 20px 0;}' +
        '.dialog-confirm-box .dialog-confirm-content {text-align: center;font-size: 16px;padding: 14px 20px 24px;border-bottom: 1px solid #ccc;}' +
        '.dialog-confirm-box .dialog-confirm-btns {height: 44px;}' +
        '.dialog-confirm-box .dialog-confirm-btns .dialog-confirm-btn {background: white;text-decoration: none;color: #2E8FFB;font-size: 18px;border-radius: 7px;line-height: 44px;text-align: center;display: block;}' +
        '.dialog-confirm-box .dialog-confirm-btns a:hover{background: #ccc;}' +
        '.dialog-confirm-box .dialog-confirm-btns .dialog-confirm-btn-0 {float:left;width: 49%;box-sizing: border-box;border-right: 1px solid #ccc;border-radius: 0 0 0 7px;}' +
        '.dialog-confirm-box .dialog-confirm-btns .dialog-confirm-btn-1 {float: right;width: 50%;border-radius: 0 0 7px 0;}';
    
    var dialog = require("./dialog");

    // html css添加到页面
    $('head').append($('<style type="text/css">' + css + '</style>'));
    $('body').append(html);

    // 正在加载
    $.loading = {
        show : function(content,lock) {
            dialog.show({
                prefix: '#dialog_loading',
                lock: lock,
                content: content
            });
        },
        hide: function() {
         	dialog.hide('#dialog_loading');
        }
    };

    // 信息提示
    $.message = {
        show: function(cfg) {
            cfg = cfg || {};

            dialog.show({
                pos: cfg.pos,
                prefix: '#dialog_msg',
                lock: cfg.lock || true,
                time: cfg.time || 3000, //默认停留3秒
                content: cfg.content
            });
        },
        hide: function() {
            dialog.hide('#dialog_msg');
        }
    };

    // 确认弹层
    $.confirm = {
        show: function(cfg) {
            dialog.show({
                prefix: '#dialog_confirm',
                title: cfg.title,
                btns: cfg.btns,
                content: cfg.content
            });
        },
        hide: function() {
            dialog.hide('#dialog_confirm');
        }
    };

    module.exports = {
        loading: $.loading,
        message: $.message,
        confirm: $.confirm
    };

});
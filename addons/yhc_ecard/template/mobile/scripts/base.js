function showToast(msg , callback){
    var $mod = $('<div>\
        <div class="weui_mask_transparent"></div>\
        <div class="weui_toast">\
            <i class="weui_icon_toast"></i>\
            <p class="weui_toast_content">'+msg+'</p>\
        </div>\
    </div>');

    $mod.appendTo("body");
    setTimeout(function(){
        $mod.remove();
        callback && callback();
    } , 2000);
}


function showAlert(msg){
    var $mod = $('<div id="weui_dialog_alert" class="weui_dialog_alert">\
        <div class="weui_mask"></div>\
        <div class="weui_dialog">\
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>\
            <div class="weui_dialog_bd">'+msg+'</div>\
            <div class="weui_dialog_ft">\
                <a href="javascript:;" class="weui_btn_dialog primary" onclick="$(\'#weui_dialog_alert\').remove()">确定</a>\
            </div>\
        </div>\
    </div>');

    $mod.appendTo("body");
}


function showConfirm(title , msg , callback){
    var $mod = $('<div class="weui_dialog_confirm">\
        <div class="weui_mask"></div>\
        <div class="weui_dialog">\
            <div class="weui_dialog_hd"><strong \class="weui_dialog_title">'+title+'</strong></div>\
            <div class="weui_dialog_bd">'+msg+'</div>\
            <div class="weui_dialog_ft">\
                <a href="javascript:;" class="weui_btn_dialog default box-cancel">取消</a>\
                <a href="javascript:;" class="weui_btn_dialog primary box-ok">确定</a>\
            </div>\
        </div>\
    </div>');

    $mod.delegate(".box-ok" , "click" , function(){
        callback();
        $mod.remove();
    }).delegate(".box-cancel" , "click" , function(){
        $mod.remove();
    });

    $mod.appendTo("body");

}


$("body").delegate(".js_qr" , "click" , function(){

    var url = $(this).data("url");

    var $mod = $('<div id="qr_dialog" class="qr_dialog">\
        <div class="weui_mask"></div>\
        <div class="weui_dialog">\
            <div class="weui_dialog_bd"><img src="'+url+'" /></div>\
            <div class="weui_dialog_ft">\
                <a href="javascript:;" class="weui_btn_dialog primary" onclick="$(\'#qr_dialog\').remove()">确定</a>\
            </div>\
        </div>\
    </div>');

    $mod.appendTo("body");
});


function hideWxMenu(){
    wx.ready(function(){
        wx.hideOptionMenu();
    });
}
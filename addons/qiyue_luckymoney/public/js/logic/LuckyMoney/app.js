// 公共脚本
define(function(require,exports,module){  
    require("../../lib/dialog_skin");
    // 初始化
    var _init = function(callback) {
        callback && callback();
    };

    return {
        init: _init
    };
});



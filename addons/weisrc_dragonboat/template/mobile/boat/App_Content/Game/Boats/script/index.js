/*
 主入口
 jslover@20150504
 */
define(function (require, exports, module) {
    var $ = require('zepto');
    var Music = require('script/music.js');
    var Common = require('script/common.js');
    var $mask = Common.$mask;
    var Game = require('script/game.js');
    Game.init();
    var Action = {
        share: function () {
            $('.div-share').show();
        }
        , closePop: function ($a) {
            var ruleid = $("#ruleid").val();
            var weid = $("#weid").val();
            var url = 'index.php?i='+weid+'&c=entry&id='+ruleid+'&do=GetGameStatus&m=weisrc_dragonboat#';
            $.ajax
            ({
                url: url,
                type:'POST',
                data: {},
                dataType:'json',
                error: function () {
                    alert('网络通讯异常，请稍后再试！');
                },
                success: function (result) {
                    if (result.success == 1) {
                        var $box = $a.parent().removeClass('show').addClass('hide');
                        var isSubmitBox = $box[0].id == 'box-submit';
                        if (isSubmitBox) {
                            $('.light-box').removeClass('show');
                        }
                        setTimeout(function () {
                            $box.removeClass('hide').hide();
                            if (isSubmitBox) {
                                Game.replay();
                            }
                        },500);
                        $mask.hide();
                    } else if (result.success == 0) {
                        alert(result.msg);
                    } else {
                        alert('未知状态');
                    }
                }
            });

            //var $box = $a.parent().removeClass('show').addClass('hide');
            //var isSubmitBox = $box[0].id == 'box-submit';
            //if (isSubmitBox) {
            //    $('.light-box').removeClass('show');
            //}
            //setTimeout(function () {
            //    $box.removeClass('hide').hide();
            //    if (isSubmitBox) {
            //        Game.replay();
            //    }
            //},500);
            //$mask.hide();
        }
        , switchMenu: function ($a) {
            var $p = $a.parent().toggleClass('open');
            if ($p.hasClass('open')) {
                Game.pause();
            } else {
                Game.go();
            }
        }
        , replay: function ($a) {
            var ruleid = $("#ruleid").val();
            var weid = $("#weid").val();
            var url = 'index.php?i='+weid+'&c=entry&id='+ruleid+'&do=GetGameStatus&m=weisrc_dragonboat#';
            $.ajax
            ({
                url: url,
                type:'POST',
                data: {},
                dataType:'json',
                error: function () {
                    alert('网络通讯异常，请稍后再试！');
                },
                success: function (result) {
                    if (result.success == 1) {
                        Game.replay();
                        this.gameGo();
                    } else if (result.success == 0) {
                        alert(result.msg);
                    } else {
                        alert('未知状态');
                    }
                }
            });

            //location.reload();
            if ($a.hasClass('btn-replay-1')) {
                $('#box-submit').find('.pop-a-close').trigger('click');
            } else {

            }
        }
        , submit: function () {
            var point = $("#result-distance").html();
            var name = $("#txt-name").val();
            var mobilePhone = $("#txt-tel").val();

            if (!name) {
                alert("姓名不能为空!");
                return;
            }
            if (!mobilePhone) {
                alert("手机号不能为空!");
                return;
            }
            var phoneRegex = /^[(]?0\d{2,3}[)-]?\d{7,8}|\d{11}$/;
            if (!phoneRegex.test(mobilePhone)) {
                alert("请先填写正确的手机号码!");
                return;
            }
            $('.post-tip').show();
            //提交结果
            var ruleid = $("#ruleid").val();
            var weid = $("#weid").val();
            var url = 'index.php?i='+weid+'&c=entry&id='+ruleid+'&do=SaveUserinfo&m=weisrc_dragonboat#';
            $.ajax
            ({
                url: url,
                type:'POST',
                data: {username: name, mobilePhone: mobilePhone},
                dataType:'json',
                error: function () {
                    alert('网络通讯异常，请稍后再试！');
                },
                success: function (result) {
                    if (result.success == 1) {
                        $('.post-tip').hide();
                        $('#box-submit .pop-a-close,.menu-switch,.btn-rank').trigger('click');
                        Music.bg.stop();
                    } else if (result.success == 0) {
                        alert(error || "网络忙，请重试！");
                        $('.post-tip').hide();
                        return;
                    } else {
                        alert('未知状态');
                    }
                }
            });
        }
        , showRule: function ($a) {
            $('.pop-box').hide().removeClass('show');
            $('#box-rule').show().addClass('show');
            $mask.show();
        }
        , showPrize: function ($a) {
            $('.pop-box').hide().removeClass('show');
            $('#box-prize').show().addClass('show');
            $mask.show();
        }
        , showRank: function ($a) {
            $('.pop-box').hide().removeClass('show');
            $('#box-rank').show().addClass('show');
            $mask.show();

            var ruleid = $("#ruleid").val();
            var weid = $("#weid").val();
            var url = 'index.php?i='+weid+'&c=entry&id='+ruleid+'&do=GetGameRank&m=weisrc_dragonboat#';
            $.ajax
            ({
                url: url,
                type:'POST',
                data: {},
                dataType:'json',
                error: function () {
                    alert('网络通讯异常，请稍后再试！');
                },
                success: function (result) {
                    $('#box-rank .ul-rank').html(Html.getRankList(result.Ranks));
                    if (result.MyRank && result.MyRank.No) {
                        $('#box-rank .pop-a-share').html('我当前排名第' + result.MyRank.No);
                    }
                }
            });
        }
        , goGamePage: function () {
            var ruleid = $("#ruleid").val();
            var weid = $("#weid").val();
            var url = 'index.php?i='+weid+'&c=entry&id='+ruleid+'&do=GetGameStatus&m=weisrc_dragonboat#';

            $.ajax
            ({
                url: url,
                type:'POST',
                data: {},
                dataType:'json',
                error: function () {
                    alert('网络通讯异常，请稍后再试！');
                },
                success: function (result) {
                    if (result.success == 1) {
                        $('#loader,#cover').remove();
                        $('#container').show();
                    } else if (result.success == 0) {
                        alert(result.msg);
                    } else {
                        alert('未知状态');
                    }
                }
            });
        }
        , muteMusic: function ($a) {
            if ($a.hasClass('on')) {
                Music.mute(false);
            } else {
                Music.mute(true);
            }
        }
        , gameGo: function () {
            Game.go();
            $('.open').removeClass('open');
        }
        , hideThis: function ($a) {
            $a.hide();
        }
    };

    var Html = {
        getRankList: function (list) {
            if (list == null || list.length == 0) {
                return '<div class="tip">暂无排行</div>';
            }
            var html = '';
            html += '<ul class="ul-rank">';
            $.each(list,function(i,item){
                if(i<3){
                    html += '<li class="istop">';
                }else{
                    html += '<li>';
                }
                html += '<em>'+(i+1)+'</em>';
                html += '<img src="' + item.HeadImgUrl + '" />';
                html += '<div>';
                html += item.NickName;
                html += '<p>滑行了' + (item.Point) + '米</p>';
                html += '</div>';
                html += '<div class="clear"></div>';
                html += '</li>';
            });
            html += '</ul>';
            return html;
        }
    };


    $('body').delegate('a', 'click', function () {
        var $a = $(this);
        var action = $a.attr('data-action');
        if (Action[action]) {
            Action[action]($a);
        }
    });

    //--------分享----------
    var tenantName = $("#TenantName").val();
    var shareTitle = $("#ShareTitle").val();
    var shareImage = $("#ShareImage").val();
    var shareUrl = $("#ShareUrl").val();
    var shareAppId = $("#ShareAppId").val();
    var reportfunc = {
        success: function () {
            $('.div-share').click();
        }
    }

    if ($("#IsTitleImage").val() == 'True') {
        shareUrl = $("#ShareHomePageUrl").val();
    }
    var shareContent = shareTitle;
});
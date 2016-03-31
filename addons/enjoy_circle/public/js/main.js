/**
* Created by Kirito.H on 2015/6/18.
*/

$(function () {
    var closeThiBox = function (elem) {
     //   $('.mask').hide(0);
    //    $(elem).hide(0);
    };

    function showThiBox(elem) {
        var w, h, mt, ml;
        $('.mask').show(0);
        $(elem).show(0);
        w = $(elem).width();
        h = $(elem).height();
        mt = h / 2;
        ml = w / 2;
        //$(elem).css({'margin-top': -mt, 'margin-left': -ml});
    }

    var showTab = function (elem1, elem2) {
        var index = elem1.attr('data-index'),
      $elem = $(elem2);
        $elem.each(function () {
            if ($(this).attr('data-index') == index) {
                $elem.hide(0);
                $(this).show(0);
            }
        });
    };

    function isWeiXin() {
        var ua = window.navigator.userAgent.toLowerCase();
        return (ua.match(/MicroMessenger/i) == 'micromessenger');
    }

    var browser = {
        versions: function () {
            var u = navigator.userAgent, app = navigator.appVersion;
            return { //移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
                iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1, //是否iPad
                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
            };
        } (),
        language: (navigator.browserLanguage || navigator.language).toLowerCase()
    };

    //share guide
    $('.btnShare').click(function () {
        showThiBox('#shareGuideWx');
        $('.mask').addClass('close');
    });
    $('.btnDownload').click(function () {
        if (isWeiXin()) {
            if (browser.versions.android) {
                showThiBox('#dlGuideAndroid');
            } else {
                showThiBox('#dlGuideIOS');
            }
        } else {
            $(this).attr('href', 'http://www.wefax.cn/app/clientdownload.aspx');
        }
        $('.mask').addClass('close');
    });
    $('.guideBox').click(function () {
        closeThiBox('.guideBox');
    });
    $('.mask').click(function () {
        if ($(this).hasClass('close')) {
            closeThiBox('.thickbox, .guideBox');
        }
    });

    //floatbar
    $('.floatbar .close01').click(function () {
        $(this).parent().hide();
        if ($(this).parent('.floatbar').hasClass('fixed-t')) {
            $('body').removeClass('has-topbar');
        }
        if ($(this).parent('.floatbar').hasClass('fixed-b')) {
            $('body').removeClass('has-footbar');
        }
    });

    //  $('.btnVeriCode').click(function () {
    //    $(this).addClass('disabled').html('<span class="countDown"' + '\>60</span>s后获取');
    //    countDown();
    //  });
    //  function countDown() {
    //    var t = setInterval(function(){
    //      var $cd = $('.countDown'),
    //        txt = parseInt($cd.text());
    //      $cd.text(--txt);
    //    }, 1000);
    //    setTimeout(function(){
    //      clearInterval(t);
    //      $('.btnVeriCode').removeClass('disabled').html('发送验证码')
    //    }, 60000);
    //  }

    $('.thickbox [class*="close"]').click(function () {
        closeThiBox('.thickbox');
    });
    $('#actiRule').click(function () {
        closeThiBox('.thickbox');
    });
    $('.thiBoxTrig').click(function () {
        var target = $(this).attr('data-target');
        showThiBox('#' + target);
        if ($(this).hasClass('tapToClose')) {
            $('.mask').addClass('close');
        }
    });

    //tab
    $('.tab01 .tab-item').click(function () {
        $(this).addClass('curr').siblings('.tab-item').removeClass('curr');
        showTab($(this), $('.tab-cont'));
    })
});


var logs = {
    sender: function (class0, method, ex) {
        $.ajax({
            url: '/LogReceiver/Script',
            type: 'post',
            data: { 'class': class0, 'method': method, 'ex': ex }

        });

    }

};

//活动主函数
var active = function (u, a, v, p, l, i, b, t) {
    try {
        this.visitor = v;
        this.parent = p;
        this.level = l;
        this.icon = i;
        this.baseUrl = b;
        this.title = t;
        var self = this;
        self.Publicity();
        WeixinApi.ready(function (api) {
            api.showOptionMenu();
            var wxData = {
                'appId': '',
                'imgUrl': self.icon,
                "link": self.baseUrl + '?v=' + self.visitor + '&parent=' + self.parent + '&l=' + (parseInt(self.level) + 1),
                "desc": self.title,
                "title": self.title
            };
            var wxCallbacks = {
                favorite: false,
                ready: function () { },
                cancel: function () { },
                fail: function () { },
                confirm: function (resp) {
                    if (resp.err_msg === 'share_timeline:ok' || resp.err_msg === 'general_share:ok') {
                        $.ajax({
                            url: '/activev1/StartShare',
                            type: 'post',
                            success: function (data) {
                                if (data === 'False_Contribution') {
                                    $('#divContribution').modal('show');
                                } else if (data === 'False_ShareLimit') {
                                    $('#divMalicious').modal('show');
                                } else if (data === 'False_IsRepeatShare') {
                                    $('#divIsRepeatShare').modal('show');
                                }
                                var cookevalue = getCookie("isFirst2");
                                if (cookevalue === "") {
                                    setCookie("isFirst2", "true", 10000);
                                    $('#divFirstMark').modal('show');
                                }
                            }
                        });
                    }
                },
                all: function (resp) {
                    
                }
            };
            api.shareToFriend(wxData, wxCallbacks);
            api.shareToTimeline(wxData, wxCallbacks);
            api.shareToWeibo(wxData, wxCallbacks);
            api.generalShare(wxData, wxCallbacks);
        });
    }
    catch (e) {
        logs.sender('active', 'active', e);
    }
};

active.prototype.WeixinVersion = function () {
    if (navigator.userAgent.indexOf('MicroMessenger/6.0.2') != -1 && navigator.userAgent.indexOf('iPhone') != -1) {
        $('#divWeixinVersion').modal('show');
    }
};

//活动状态
active.prototype.ActivityStat = {
    Started: 3,
    Over: 4
};
//活动公示
active.prototype.Publicity = function () {
    $('#divIconContainer').bind('click', function () {
        var isExpand = $(this).data('expand') === 'true';
        if (isExpand) {
            $(this).data('expand', 'false');
            $(this).children('.publicity_down').removeClass('publicity_up');
            $('#divPublicityInfo').slideUp(100);
        } else {
            $(this).data('expand', 'true');
            $(this).children('.publicity_down').addClass('publicity_up');
            if ($.trim($('#divPublicityInfo').html()) === '') {
                $.ajax({
                    url: '/activev1/LoadActivityInfo',
                    type: 'get',
                    dataType: 'json',
                    cache: true,
                    success: function (data) {
                        var html = '';
                        html += '<ul class="publicity-expand">';
                        html += '<li class="publicity-title">奖品设置</li>';
                        var startDate = new Date(parseInt(data.LimitStartTime.substr(6)));
                        var endDate = new Date(parseInt(data.LimitEndTime.substr(6)));
                        $.each(data.PrizeInfos, function (i, n) {
                            html += '<li><span class="publicity-num">' + (i + 1) + '.</span>集 <b>' + n.ShareCount + '</b> 个分享获 <b>' + n.PrizeContent + ' </b>' + (!n.IsLimitPrizeCount ? '' : '共' + n.LimitPrizeCount + '份') + '</li>';
                        });
                        html += '<li class="publicity-title publicity-topline">活动时间</li>';
                        html += '<li>' + active.dateFormat(startDate) + ' — ' + active.dateFormat(endDate) + '</li>';
                        html += '<li class="publicity-title publicity-topline">兑奖方法</li>';
                        html += '<li>' + data.ExchangeMethod + '</li>';
                        html += '<li class="publicity-title publicity-topline">咨询电话</li>';
                        html += '<li>' + data.Phone + '<a href="tel:' + data.Phone + '" class="publicity-call"><span class="publicity-call-icon"></span> 直接拨打</a></li>';
                        html += '</ul>';
                        $('#divPublicityInfo').html(html).slideDown(100);
                    }
                });
            } else {
                $('#divPublicityInfo').slideDown(100);
            }
        }
    });
    $('#divHowContainer').bind('click', function () {
        var isExpand = $(this).data('expand') === 'true';
        if (isExpand) {
            $(this).data('expand', 'false');
            $(this).children('.publicity_down').removeClass('publicity_up');
            $('#divPublicityHow').slideUp(100);
        } else {
            $(this).data('expand', 'true');
            $(this).children('.publicity_down').addClass('publicity_up');
            if ($.trim($('#divPublicityHow').html()) === '') {
                var html = '';
                html += '<ul class="publicity-expand">';
                html += '<li class="clearfix"><div class="publicity-right publicity-pic-1"><span class="publicity-num">1.</span>累集分享个数</div></li>';
                html += '<li class="clearfix"><div class="publicity-left publicity-pic-2"><span class="publicity-num">2.</span>选择奖品</div></li>';
                html += '<li class="clearfix"><div class="publicity-right publicity-pic-3"><span class="publicity-num">3.</span>获得兑奖码</div></li>';
                html += '</ul>';
                $('#divPublicityHow').html(html);
            }
            $('#divPublicityHow').slideDown(100);
        }
    });
};
//活动日期格式化
active.dateFormat = function (date) {
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    if (month < 10) {
        month = '0' + month;
    }
    var day = date.getDate();
    if (day < 10) {
        day = '0' + day;
    }
    var hour = date.getHours();
    if (hour < 10) {
        hour = '0' + hour;
    }
    var minute = date.getMinutes();
    if (minute < 10) {
        minute = '0' + minute;
    }
    return year + '.' + month + '.' + day + ' ' + hour + ':' + minute;
};

//加载集分享信息
active.prototype.loadInfo = function () {
    var self = this;
    $.ajax({
        url: '/activev1/loadinfo',
        type: 'get',
        cache: false,
        success: function (data) {
            var html = '';
            var activityInfo = data.ActivityInfo;
            var visitorInfo = data.VisitorInfo;
            var nextPrizeCount = data.NextPrizeCount;
            html += self.createShareHtml(self.visitor === self.parent, visitorInfo.ChildrenShareCount);
            html += self.createLightHtml(visitorInfo.ChildrenShareCount, nextPrizeCount);
            html += self.createSurplusHtml(activityInfo.ActivityStat === self.ActivityStat.Over, activityInfo.LeaveTime);
            html += self.createPrizeHtml(self.visitor === self.parent, activityInfo, visitorInfo);
            $('#divVisitor').html(html);
            self.bindSelect();
            self.bindExchange();
            self.BindRemind();
        }
    });
};
//生成分享数的html代码
active.prototype.createShareHtml = function (isSelf, shareCount) {
    return '<div class="v-share">' + (isSelf ? '您' : '该用户') + '已集<span class="v-share-count">' + shareCount + '</span>个分享</div>';
};
//生成电灯效果的html代码
active.prototype.createLightHtml = function (childrenShareCount, nextPrizeCount) {
    var html = '<div class="v-share-icon"><div class="v-share-icon-container">';
    if (nextPrizeCount === 0) {
        for (var i = 0; i < childrenShareCount; i++) {
            html += '<span class="v-share-light-color"></span>';
        }
    } else {
        for (var j = 0; j < nextPrizeCount; j++) {
            if (childrenShareCount > j) {
                html += '<span class="v-share-light-color"></span>';
            } else {
                html += '<span class="v-share-light-gray"></span>';
            }
        }
    }
    html += '</div></div>';
    return html;
};
//生成活动剩余时间的html代码
active.prototype.createSurplusHtml = function (isOver, leaveTime) {
    var html = '<div class="v-share-surplus">活动剩余时间：';
    if (isOver) {
        html += '<span class="visitor_over_time">0</span>天<span class="visitor_over_time">0</span>小时<span class="visitor_over_time">0</span>分钟';
    } else {
        html += leaveTime;
    }
    html += '</div>';
    return html;
};
//生成工活动奖品信息的html代码
active.prototype.createPrizeHtml = function (isSelf, activityInfo, visitorInfo) {
    var html = '';
    var self = this;
    if (isSelf) {
        if (visitorInfo.Abnormal === "异常") {
            html += '<div class="v-prize-code">';
            html += '您涉嫌采用非正常方式集分享数量，本次兑奖资格被取消，如需申诉请致电我公司法务部门，直线电话：0512-69373652';
            html += '</div>';
            return html;
        }
        if (visitorInfo.IsGetPrize) {
            html += '<div class="v-prize-code">';
            html += '<p>您已获得</p>';
            html += '<p>' + visitorInfo.GetPrizeInfo.PrizeContent + '</p>';
            html += '<p>领奖密码：' + visitorInfo.PrizeCode + '</p>';
            html += '</div>';
            return html;
        }
        if (activityInfo.ActivityStat === self.ActivityStat.Over) {
            html += '<div>';
            html += '<p>活动已结束</p>';
            html += '</div>';
            return html;
        }
    }
    html = '<div class="v-prize-get clearfix"><span class="v-prize-get-tip">' + (isSelf ? '您' : '该用户') + '已获得：</span>' + (isSelf ? '<a href="javascript:void(0);" class="v-prize-get-remind" id="btnRemind" data-loading-text="正在加载...">获奖提醒</a>' : '') + '</div>';
    var prizeInfos = activityInfo.PrizeInfos;
    var hasSet = false;
    var prizeTemps = [];
    var i;
    for (i = prizeInfos.length - 1; i >= 0; i--) {
        var prizeTempHtml = '';
        var isCanGetPrize = visitorInfo.ChildrenShareCount >= prizeInfos[i].ShareCount;
        var surplusPrizeCount = (prizeInfos[i].IsLimitPrizeCount ? '剩余' + prizeInfos[i].SurplusPrizeCount + '份' : '');
        if (prizeInfos[i].IsLimitPrizeCount) {
            isCanGetPrize = isCanGetPrize && prizeInfos[i].SurplusPrizeCount;
        }
        if (isSelf) {
            if (isCanGetPrize) {
                if (!hasSet) {
                    hasSet = true;
                    prizeTempHtml = '<ul class="v-prize-item-selected clearfix" data-id="' + prizeInfos[i].Id + '" data-name="' + prizeInfos[i].PrizeContent + '">'
						+ '<li class="v-prize-select-iconcontainer"><span class="v-prize-select-icon"></span></li>'
						+ '<li class="v-prize-item-name">' + prizeInfos[i].PrizeContent + '</li>'
						+ '<li class="v-prize-item-surplus">' + surplusPrizeCount + '</li>'
						+ '</ul>';
                    $('#lblPrize').html(prizeInfos[i].PrizeContent);
                } else {
                    prizeTempHtml += '<ul class="v-prize-item-unselect clearfix" data-id="' + prizeInfos[i].Id + '" data-name="' + prizeInfos[i].PrizeContent + '">'
						+ '<li class="v-prize-select-iconcontainer"><span class="v-prize-select-icon"></span></li>'
						+ '<li class="v-prize-item-name">' + prizeInfos[i].PrizeContent + '</li>'
						+ '<li class="v-prize-item-surplus">' + surplusPrizeCount + '</li>'
						+ '</ul>';
                }
            } else {
                prizeTempHtml += '<ul class="v-prize-item-disabled clearfix">'
					+ '<li class="v-prize-select-iconcontainer"><span class="v-prize-select-icon"></span></li>'
					+ '<li class="v-prize-item-name">' + prizeInfos[i].PrizeContent + '</li>'
					+ '<li class="v-prize-item-surplus">' + surplusPrizeCount + '</li>'
					+ '</ul>';
            }
        } else {
            if (isCanGetPrize && !hasSet) {
                hasSet = true;
                prizeTempHtml += '<ul class="v-prize-item-selected-disabled clearfix">'
					+ '<li class="v-prize-select-iconcontainer"><span class="v-prize-select-icon"></span></li>'
					+ '<li class="v-prize-item-name">' + prizeInfos[i].PrizeContent + '</li>'
					+ '<li class="v-prize-item-surplus">' + surplusPrizeCount + '</li>'
					+ '</ul>';
            } else {
                prizeTempHtml += '<ul class="v-prize-item-disabled clearfix">'
					+ '<li class="v-prize-select-iconcontainer"><span class="v-prize-select-icon"></span></li>'
					+ '<li class="v-prize-item-name">' + prizeInfos[i].PrizeContent + '</li>'
					+ '<li class="v-prize-item-surplus">' + surplusPrizeCount + '</li>'
					+ '</ul>';
            }
        }
        prizeTemps.push(prizeTempHtml);
    }
    for (i = prizeInfos.length - 1; i >= 0; i--) {
        html += prizeTemps[i];
    }
    if (isSelf) {
        if (hasSet) {
            html += '<div><button type="button" id="v-prize-get-enable" class="btn-block v-prize-get-enable" data-toggle="modal" >立即兑奖</button></div>';
        } else {
            html += '<div><button type="button" class="btn-block v-prize-get-disable">立即兑奖</button></div>';
        }
    }
    return html;
};
//绑定奖品选择事件
active.prototype.bindSelect = function () {
    $('#divVisitor').on('click', '.v-prize-item-unselect', function () {
        $('#divVisitor .v-prize-item-selected').removeClass('v-prize-item-selected').addClass('v-prize-item-unselect');
        $(this).removeClass('v-prize-item-unselect').addClass('v-prize-item-selected');
        $('#lblPrize').html($(this).data('name'));
    });
    //绑定立即兑奖事件
    $('#v-prize-get-enable').on('click', function () {
        $.ajax({
            url: '/activev1/isAbnormal',
            type: 'post',
            dataType: 'text',
            data: { 'pid': $('#divVisitor .v-prize-item-selected').data('id') },
            success: function (data) {
                if (data === 'True') {
                    $('#divAbnormal').modal('show');
                }
                else {
                    $('#divExchange').modal('show');
                }
            }, error: function () {
                alert('服务器繁忙，请稍后再试');
            }
        });
    });
};

//绑定兑奖事件
active.prototype.bindExchange = function () {
    var self = this;
    $('#btnExchangeSubmit').bind('click', function () {
        var $button = $(this);
        $button.button('loading');
        $.ajax({
            url: '/activev1/StartExchange',
            type: 'post',
            dataType: 'text',
            data: { 'pid': $('#divVisitor .v-prize-item-selected').data('id') },
            success: function (data) {
                $button.button('reset');
                if (data === 'True') {
                    $('#divExchange').modal('hide');
                    self.loadInfo();
                }
            }, error: function () {
                $button.button('reset');
                alert('服务器繁忙，请稍后再试');
            }
        });
    });
};
//绑定提醒事件
active.prototype.BindRemind = function () {
    $('#btnRemind').bind('click', function () {
        var $button = $(this);
        $button.button('loading');
        $.ajax({
            url: '/activev1/GetNoticePhone',
            type: 'get',
            cache: false,
            dataType: 'text',
            success: function (data) {
                $button.button('reset');
                $('#divPhoneValidate').html('');
                $('#divRemind').modal('show');
                if (data == null || data === '') {
                    $('#divPhoneInput').show();
                    $('#divPhoneShow').hide();
                    $('#btnRemindSubmit').unbind('click').bind('click', function () {
                        var phone = $.trim($('#txtRemindPhone').val());
                        if (phone === '') {
                            $('#divPhoneValidate').html('请输入手机号码。');
                        } else if (!/^(?:1\d|1\d)\d{6}(\d{3}|\*{3})$/.test(phone)) {
                            $('#divPhoneValidate').html('手机号码格式不正确。');
                        } else {
                            $.ajax({
                                url: '/activev1/SetNoticePhone',
                                type: 'post',
                                dataType: 'text',
                                data: { 'phone': phone },
                                success: function (data) {
                                    if (data === 'True') {
                                        alert('提醒设置成功！');
                                    } else {
                                        alert('服务器繁忙，请尝试刷新本页后重新再试！');
                                    }
                                    $('#divRemind').modal('hide');
                                }
                            });
                        }
                    });
                } else {
                    data = $.parseJSON(data);
                    $('#divPhoneInput').hide();
                    $('#divPhoneShow').show();
                    $('#txtPhone').val(data.Phone);
                    $('#spanDelPhone').unbind('click').bind('click', function () {
                        $.ajax({
                            url: '/activev1/DelNoticePhone',
                            type: 'post',
                            dataType: 'text',
                            data: { nid: data.Id },
                            success: function (data) {
                                if (data === 'True') {
                                    $('#txtRemindPhone').val('');
                                    $('#divPhoneInput').show();
                                    $('#divPhoneShow').hide();
                                    $('#divPhoneValidate').html('');
                                }
                            }
                        });
                    });
                }
            },
            error: function () {
                $button.button('reset');
                alert('服务器繁忙，请稍后再试！');
            }
        });
    });
};

//读取cookie
getCookie = function (cookiename) {
    if (document.cookie.length > 0) {
        var cStart = document.cookie.indexOf(cookiename + "=");
        if (cStart !== -1) {
            cStart = cStart + cookiename.length + 1;
            var cEnd = document.cookie.indexOf(";", cStart);
            if (cEnd !== -1) cEnd = document.cookie.length;
            return unescape(document.cookie.substring(cStart, cEnd));
        }
        return "";
    }
    return "";
};

//写cookie
setCookie = function (cookiename, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = cookiename + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString()) + ";path=/";

}
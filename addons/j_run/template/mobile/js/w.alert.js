(function($){
    $.fn.w_alert = function(options){
        var defaults = {
            isForm:'false',
            title:'请输入内容',
            spanInfo:'内容',
            placeholder:'请输入手机号',
			nickname:'请输入名称',
			type:'post',
			url:'site.php'
        }
        var options = $.extend(defaults,options);
        var _this = this;



        var createSpan = function(){
            var html = '<div><span style="background: white;display: block;text-align: center;padding: 10px 4px;">'+options.spanInfo+'</span></div><div><input style="-webkit-appearance: none;width: 100%;height: 40px;border: none;background: green;color: white;" value="确认" id="ok"  type="button"/></div>';
            return html;
        }

        var createForm = function(){
            var html = '<div><input id="nickname" name="nickname" style="-webkit-appearance: none;width: 100%;height: 40px;border: none;text-indent: 20px;" placeholder="'+options.nickname+'" type="text"/><input id="tel" name="tel" style="margin-top:1px;-webkit-appearance: none;width: 100%;height: 40px;border: none;text-indent: 20px;" placeholder="'+options.placeholder+'" type="text"/></div><div><input style="-webkit-appearance: none;background: green;color: white;width: 100%;height: 40px;border: none;" value="确认" id="submit"  type="button"/></div>';
            return html;
        }

        var createAll = function(str){
            $('body').append('<div class="dialog" style="width: '+$(window).width()+'px;height: '+$(window).height()+'px;position: fixed;left: 0;top: 0;background: rgba(0,0,0,0.5);z-index: 99999999;">' +
                '<div style="width: 260px;position: absolute;left: 50%;margin-left: -130px;overflow: hidden;-webkit-border-radius: 5px;top: 10%;" class="inbox"><h4 style="text-align: center;background-color: red;padding: 6px 0;color: white;">'+options.title+'</h4>' +
                ''+str+'</div></div>');
				

            $(window).bind('touchmove',function(e){
                e.preventDefault();
            })
        }
        options.isForm == false ? createAll(createSpan()) : createAll(createForm());

        $('.inbox').bind('click',function(){
            stopPropagation();
            return;
        })
        $('.dialog,#ok').bind('click',function(){
            stopPropagation();
            $('.dialog').remove();
            $(window).unbind('touchmove');
        })


        var stopPropagation = function (event){
            var e=window.event || event;
            if(e.stopPropagation){
                e.stopPropagation();
            }else{
                e.cancelBubble = true;
            }
        }
	
    }
})(jQuery);
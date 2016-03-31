(function($) {  
      $.fn.extend({        
          roll: function(options) { 
                       var defaults = {
                            speed:1           
                       };
                       var options = $.extend(defaults, options); 
                       var speed=(document.all) ? options.speed : Math.max(1,options.speed-1);
                       if ($(this) == null) return ; 
                       var $container = $(this);
                       var $content = $("#content");
                       var init_left = $container.width();
                       $content.css({left:parseInt(init_left) + "px"});
                       var This = this;
                       var time = setInterval(function(){This.move($container,$content,speed);},20); //setInterval会改变this指向，即使加了This=this，也要用匿名函数封装，这里调试了n久
                       
                       $container.bind("mouseover",function()
                      {
                             clearInterval(time);
                      });
                       $container.bind("mouseout",function()
                      {
                             time = setInterval(function(){This.move($container,$content,speed);},20);
                      });
                       
                       return this;    
         },
          move:function($container,$content,speed){
                       var container_width = $container.width();
                       var content_width = $content.width();
                       if (parseInt($content.css("left")) + content_width > 0)
                       {
                            $content.css({left:parseInt($content.css("left")) - speed + "px"});
                       }
                       else
                       {
                            $content.css({left:parseInt(container_width) + "px"});
                       }
          }
   });
})(jQuery);

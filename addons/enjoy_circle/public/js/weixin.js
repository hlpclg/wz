$(document).ready(function(){
	$(document).click(function (e) {
		var drag = $(".bottom_review"),
			dragel = $(".bottom_review")[0],
			target = e.target;
		if (dragel !== target && !$.contains(dragel, target)) {
			drag.hide();
		}
	});
	// 评论按钮显示
	$(document).on("click", ".po-cmt .c-icon", function(event) {
				$(this).parents(".po-hd").find(".review").show();
	});
	// 评论输入激活
	$(document).on("click", ".review", function(event) {
				var  index = $(this).parents(".po-cmt").attr("rel");
				$(this).hide();
				$(".bottom_review").show();
				$(".bottom_review").attr("rel",index);
	});

	// 评论点击
	$(document).on("click", ".review", function(event) {
				$(this).hide();
				$(".txt_review").val("");	
				$(".bottom_review").show();
				$(".txt_review").focus();
	});
	
	// 弹出灰层点击隐藏
	$(".grey-bg").click(function(){
		 $(".pop_del").hide();
         $(this).hide();
	})
	
	// 评论详情删除点击
	$(document).on("click", ".p_img_del", function(event) {
		$(this).parents(".po-cmt").find(".post").remove();
		$(this).hide();
		// ajax code---------------
		// ajax_fn.detail_del_ajax(id,data);
		// ajax code---------------			 
	});
	
	//提交评论数据 AJAX
	$(".submit_btn").click(function(){
			
		var id = $(this).parents(".bottom_review").attr("rel");
		var username =  $("#user_name").text();
		var data = $(".txt_review").val();
		$("#"+id).find(".cmt-wrap div").addClass("cmt-list");
		$("#"+id).find(".r").show();
		
		$("#"+id).find(".cmt-list").append("<p><span>"+username+"：</span>"+data+"</p>");
		
		// ajax code---------------
		// ajax_fn.review_ajax(id,data);
		// ajax code---------------
		$(".bottom_review").hide();
	})
	
	//评论删除弹出层
	$(document).on("click", ".cmt-list p", function(event) {
		var id = $(this).parents(".cmt-list").attr("id");
		var index = $(this).index() ;
		$.popConfirm.show(id+" "+index);
		
	})
	
		
	// 评论删除点击删除 AJAX
	$(document).on("click", ".pop_del", function(event) {
			var rel = $(this).attr("rel");
			var arry = rel.split(" ");
		    $("#"+arry[0] +" p").eq(arry[1]).remove();
			$.popConfirm.hide();
			
			// ajax code---------------
			// ajax_fn.del_ajax(id);
			// ajax code---------------
			
			//清理样式
			cmtList(arry[0]);
	});
});


var  ajax_fn = {

	//删除 AJAX 	
	del_ajax:function(d_id){
		     $.ajax({
				 type: "GET",
				 url: "test.json",
				 data: {"id":d_id},
				 dataType: "json",
				 success: function(data){
					
				 }
			 });
  
		
	},
	//回复 AJAX 
	review_ajax:function(d_id,d_cont){
			  $.ajax({
				 type: "GET",
				 url: "test.json",
				 data: {"id":d_id, "cont":d_cont},
				 dataType: "json",
				 success: function(data){
					
				 }
			 });
	},
	//回复 AJAX 
	detail_del_ajax:function(d_id,d_cont){
			  $.ajax({
				 type: "GET",
				 url: "test.json",
				 data: {"id":d_id, "cont":d_cont},
				 dataType: "json",
				 success: function(data){
					
				 }
			 });
	}
}



//清理样式
function cmtList(obj){
	 if($("#"+obj +" p").length == 0){
		 $("#"+obj ).removeClass("cmt-list");
		$("#"+obj ).parents(".po-cmt").find(".r").hide();
	}
}

//弹出层
$.popConfirm={
    yesfn:function(){

    },
    show:function(index){
      var html="";

        html="<div class='pop_del' rel='"+index+"'>删除</div>"

        $("body").append(html);
        $(".grey-bg").show();
    },
    hide:function(){
        $(".pop_del").hide();
        $(".grey-bg").hide();
    }
}

//下拉加载
$.pagescroll = {
    check:true,
    addHTML:"",
    cls:null,
    index:2,
    ajax_fn:function(){

    },
    // 初始化
    init:function(cls) {
        this.cls = cls;
        $(".lodding").show();
        $(window).bind('scroll',function(){$.pagescroll.show()});
    },
    // 加载
    show:function() {

        if($(window).scrollTop()+$(window).height()+200>=$(document).height()){
            $.pagescroll.ajaxRead();
        }
    },
    ajaxRead:function() {

        if($.pagescroll.check){
            $.pagescroll.check = false;
            $('.lodding').show();
            $.pagescroll.ajax_fn();

        }
    },
    // 填充数据
    setHtml:function(data) {
        if(data == 0){
            $.pagescroll.loadedEnd("已经全部显示");
            return;
        }
        $(".lodding").hide();
        $(this.cls).append(data);
        $.pagescroll.check = true;
    },
    // 加载结束
    loadedEnd:function(msg){
        $('.lodding').html(msg);
        $('.lodding').addClass("正在加载..");
    },
    // 加载错误
    error:function(msg){
        $.pagescroll.check = false;
    }
};
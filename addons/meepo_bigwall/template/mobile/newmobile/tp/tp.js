var voteid;
//点击"投票"进入频道
function progressBar(){
	$(".voteResult li").each(function(i){
		var num=($(this).find(".percent").text())
		$(this).find(".progressBar span").animate({"width":num},1000)
	})
	
	//获取投票列表
	$("#voteTitle").empty();
    jQuery.ajax({
    	url:"/pc/vote_voteList.do",
    	type:"post",
    	async:false,//设置为同步,才能在下拉列表加载完成之后获取文本内容赋值给页面
    	success:function(data){
    		var data = JSON.parse(data);
    		var idx=1;
    		var color;
    		$.each(data.voteList,function(i,val){
    			$("#voteTitle").append('<option c="'+val.content+'" value='+val.id+'>'+val.name+'</option>');
    			idx++;
    		})
    	}
    });
    
    //获取下拉列表的文本赋值给页面展示
	var content = $("#voteTitle  option:selected").text();
	$("#content").text(content);
	
	//给下拉列表添加默认选中事件
	$("#voteTitle").trigger("change");
}

$(function(){
	$("#voteTitle").change(function(){
		$("#vote").empty();
		$("#vote1").empty();
		voteid = $("#voteTitle option:selected").val();
		if(voteid==undefined){
			voteid = 0;
		}
		$("#vote").empty();
	    jQuery.ajax({
	    	url:"/pc/vote_getVoteStat.do",
	    	data:{"voteTitle.id":voteid},
	    	type:"post",
	    	async:false,//设置为同步,渲染完数据之后才动态加载柱状样式
	    	success:function(data){
	    		var data = JSON.parse(data);
	    		var idx=1;
	    		var color;
	    		$.each(data.statList,function(i,val){
	    			if(idx==1){
	    				color="pinkBar";
	    			}else if(idx==2){
	    				color="greenBar";
	    			}else if(idx==3){
	    				color="blueBar";
	    			}else{
	    				color="yellowBar";
	    			}
	    			var len = parseInt(data.statList.length);
	    			var chu,yu,left;
	    			chu = len/2;
	    			yu = len%2;
	    			left = yu+Math.floor(chu);
	    			if(len<=10){
		    			if(i<5){
		    			$("#vote").append('<li class="clearfix"><h6>'+idx+'、'+val.content+'</h6><div class="progressBar"><span class="'+color+'"></span>'+
								              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
		    			}else{
		    			$("#vote1").append('<li class="clearfix"><h6>'+idx+'、'+val.content+'</h6><div class="progressBar"><span class="'+color+'"></span>'+
						              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
		    			}
	    			}else{
		    			if(i<left){
			    			$("#vote").append('<li class="clearfix"><h6>'+idx+'、'+val.content+'</h6><div class="progressBar"><span class="'+color+'"></span>'+
									              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
			    			}else{
			    			$("#vote1").append('<li class="clearfix"><h6>'+idx+'、'+val.content+'</h6><div class="progressBar"><span class="'+color+'"></span>'+
							              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
			    			}
	    			}
	    			idx++;
	    		})
	    	}
	    });
	    
	    //获取参与人数
	    jQuery.ajax({
	    	url:"/pc/vote_getVoteSum.do",
	    	data:{"voteTitle.id":voteid},
	    	type:"post",
	    	async:false,
	    	success:function(data){
	    	    $("#sum").empty().text(data);
	    	}
	    });
	    
	    //动态加载柱状图样式
		$(".voteResult li").each(function(i){
			var num=($(this).find(".percent").text())
			$(this).find(".progressBar span").animate({"width":num},1000)
		})
		
	    //获取下拉列表的文本赋值给页面展示
		var content = $("#voteTitle  option:selected").text();
		$("#content").text(content);
		$("#content").next().empty().append($("#voteTitle  option:selected").attr("c"));
	});
	
})
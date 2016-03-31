var tagList ="" ;
var numList ="" ;
var　buttonurl;
//点击"抽奖"进入频道
function progressLuckBar(){
	
	//获取奖项列表
	if($("#tagid option").length==1){
	   jQuery.ajax({
	    	url:"/pc/luck_luckTagList.do",
	    	type:"post",
	    	success:function(data){
	    		var data = JSON.parse(data);
	    		var idx=1;
	    		var color;
	    		if(data.luckMap.tagList!=''){
		    		$.each(data.luckMap.tagList,function(i,val){
		    			$("#tagid").append('<option value='+val.id+'>'+val.tag_name+'</option>');
		    			idx++;
		    		})
	    		}
	    		if(data.luckMap.map!=''){
		    		if(data.luckMap.map.num_tag==1){
		    	    	$("#tagid").append("<option value='0'>按人数抽奖</option>");
		    		}
		    		
		    		 //设置抽奖活动信息
		    		var title=data.luckMap.map.name;
		    		 title=title.length>5?title.substring(0,5):title;
		    		 $("#luckTitle").text(title);
		    		 $("#luck_img").attr("src",((data.luckMap.map.imgurl)!=''&&(data.luckMap.map.imgurl)!=null)?data.luckMap.map.imgurl:"/wxscreen/web/common/images/lotteryDefault.png");
		    		 $("#luckid").val(data.luckMap.map.id);
		    		 //设置当前商家抽奖规则
					 $("#tagExclude").val(data.luckMap.map.tag_exclude);
					 $("#numExclude").val(data.luckMap.map.num_exclude);
	    	}
	    	}
	    });
	}
 
    var signNum = getSignSum();//getCount(1);//默认签到人数
    $("#may_num").html(signNum);
    $("#lucknum").text(0);
    
    //获取当前商家的中间用户
	$.post("/pc/luck_getAlreadyUser.do",function(data){
	    var data = JSON.parse(data);
        if(data.map.tagList.length>0){
        	for(var i=0;i<data.map.tagList.length;i++){
        		tagList+=data.map.tagList[i].openid+",";
        	}
        }
        if(data.map.numList.length>0){
        	for(var i=0;i<data.map.numList.length;i++){
        		numList+=data.map.numList[i].openid+",";
        	}
        }
})
  getCurrentInfo();//频道切换回来之后,根据选中的奖项查询出中奖用户和抽奖人数等
}

function getCurrentInfo(){
	var option = $("#tagid option:selected").val();
	if(option>-1){//选中按奖项或者按人数
	var luckid = $("#luckid").val();
	$.post("/pc/luck_getExcludeCount.do",{"luckid":luckid,"luckTag.id":option},function(data){
		    var data = JSON.parse(data);
		    var signNum = getSignSum();
		    var joinNum = signNum;
		    //给页面隐藏的奖品名称和按奖项抽奖人数赋值
		    $("#luckname").val(data.map.luck_name);
		    $("#tagNum").val(data.map.tagNum);
		    if(option==0){
		    	var numExclude = $("#numExclude").val();
		    	if(numExclude==1){
				    joinNum = parseInt(signNum)-parseInt(data.map.num==undefined?0:data.map.num);
				    $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}else{
		    		 $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}
		    }
		    if(option>0){
		    	var tagExclude = $("#tagExclude").val();
		    	if(tagExclude==1){
				    joinNum = parseInt(signNum)-parseInt(data.map.num==undefined?0:data.map.num);
				    $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}else{
		    		 $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}
		    }
	})

	//如果选中的奖项进行了抽奖,则把抽到的记录展示出来
	getLuckUser(luckid,option);
  }
}

//改变奖项
function changeLuck(){
	var option = $("#tagid option:selected").val();
	if(option==0){//当按人数抽奖,显示输入条件
		$("#num_input").show();
		$("#num_flag").show();
		$("#endNum").hide();
		$("#tag_flag").hide();
	}else{
	    $("#tag_flag").show();
	    $("#endBtn").hide();
	    $("#num_input").hide();
	    $("#num_flag").hide();
	}
	if(option>-1){//选中按奖项或者按人数
	var luckid = $("#luckid").val();
	$.post("/pc/luck_getExcludeCount.do",{"luckid":luckid,"luckTag.id":option},function(data){
		    var data = JSON.parse(data);
		    var signNum = getSignSum();
		    var joinNum = signNum;
		    //给页面隐藏的奖品名称和按奖项抽奖人数赋值
		    $("#luckname").val(data.map.luck_name);
		    $("#tagNum").val(data.map.tagNum);
		    if(option==0){
		    	var numExclude = $("#numExclude").val();
		    	if(numExclude==1){
				    joinNum = parseInt(signNum)-parseInt(data.map.num==undefined?0:data.map.num);
				    $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}else{
		    		 $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}
		    }
		    if(option>0){
		    	var tagExclude = $("#tagExclude").val();
		    	if(tagExclude==1){
				    joinNum = parseInt(signNum)-parseInt(data.map.num==undefined?0:data.map.num);
				    $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}else{
		    		 $("#may_num").html(joinNum>0?joinNum:0);//设置参加抽奖人数
		    	}
		    }
	})

	//如果选中的奖项进行了抽奖,则把抽到的记录展示出来
	getLuckUser(luckid,option);
  }
}

function getLuckUser(luckid,option){
    jQuery.ajax({
    	url:"/pc/luck_luckUserList.do",
    	data:{"luckTag.luckid":luckid,"luckTag.id":option},//,"type":type
    	type:"post",
    	async:false,//设置为同步
    	success:function(data){
		    var data = JSON.parse(data);
		    $("#lotteryName").siblings().remove();
		    var length = data.luckMap.luckList.length;
		    if(length>0){//奖项不为空
		    $(".lotteryDefault").hide();
            $.each(data.luckMap.luckList,function(i,val){
    			$("#lotteryName").after('<li id="'+val.openid+'"><p class="prize">'+val.luckName+'</p><i class="sn">'+parseInt(i+1)+'</i>'+
						'<p class="man"><img src="'+val.imgurl+'" />'+val.name+'</p><i class="delLottery"  onclick=confirmLayer("'+val.openid+'",'+val.id+')></i></li>');
				//给中奖列表动态加删除按钮
    			$(".lotteryName li").on({
					mouseenter: function(){
						$(this).addClass("act");
					},
					mouseleave: function(){
						$(this).removeClass("act");
					}
				})
            })
		    }
            $("#lucknum").text(length);//设置获奖人数
    	}
    });
}

function showLayer(i){
	$("#layer"+i).fadeIn();
	$("body").append("<div class=\"layerBlank\"></div>");
};
function closeLayer(o){
	$(o).parents(".layerStyle").hide();
	$("div").remove(".layerBlank");
};

function confirmLayer(openid,luckid){
	$("#layer2").fadeIn();
	$("body").append("<div class=\"layerBlank\"></div>");
	$("#layer2 :button:eq(0)").off().on("click",function(){
		delLuckUser(openid,luckid);
    })

};
var idx;
//删除中奖用户
function delLuckUser(openid,luckid){
	var option = $("#tagid option:selected").val();
    jQuery.ajax({
    	url:"/pc/luck_removeLuckUser.do",
    	data:{"luckUser.openid":openid,"luckUser.id":luckid},
    	type:"post",
    	async:false,//设置为同步
    	success:function(data){
    		//设置获奖名单人数
    		var alreadyNum = $("#luck_index").children("li").length;
    		$("#lucknum").text(alreadyNum);
    		//设置参加抽奖人数
    		var tagExclude = $("#tagExclude").val();
    		var numExclude = $("#numExclude").val();
//			var base = $(".lotteryTit").find("p em").html();
    		var base = $("#may_num").html();
			if(option>0){
				if(tagExclude==1){//按奖项抽不可重复中奖
			    tagList=tagList.replace(data+",","");
				$("#may_num").html(parseInt(base)+1>0?parseInt(base)+1:0);
				}
			}else{
				if(numExclude==1){//按人数抽不可重复中奖
				numList=numList.replace(data+",","");
				$("#may_num").html(parseInt(base)+1>0?parseInt(base)+1:0);
				}
			}
   		 $("#luck_img").attr("src","/wxscreen/web/common/images/lotteryDefault.png");
   		 $("#luck_name").empty();
    	}
    });
    var luckid = $("#luckid").val();
    getLuckUser(luckid,option);
}

//重新抽奖,数据库抽奖数据和页面排重数据同步清空
function reset(){
	//alert("before:"+tagList);
	var option = $("#tagid option:selected").val();
	if(option>-1){
	var alreadyNum = $("#luck_index").children("li").length;
	var tagExclude = $("#tagExclude").val();
	var numExclude = $("#numExclude").val();
	if(alreadyNum>0){
		$("#layer4").fadeIn();
		$("body").append("<div class=\"layerBlank\"></div>");
	    $("#layer4 :button:eq(0)").off().on("click",function(){
			$.post("/pc/luck_resetLuckUser.do",{"luckTag.id":option},function(data){
				var data = JSON.parse(data);
				$("#lotteryName").siblings().remove();
				$("#lucknum").text(0);
				var base = $("#may_num").html();
				$("#layer4").hide();
				$("div").remove(".layerBlank");
				$.each(data.list,function(i,val){
					if(option>0){
						if($("#tagExclude").val()==1){//按奖项抽不可重复中奖
					    tagList=tagList.replace(val.openid+",","");
					    $("#may_num").html(parseInt(base)+parseInt(data.list.length));
						}
					}else{
						if($("#numExclude").val()==1){//按人数抽不可重复中奖
						numList=numList.replace(val.openid+",","");
						$("#may_num").html(parseInt(base)+parseInt(data.list.length));
						}
					}
				})
			//	alert("after:"+tagList);
				$("#luck_img").attr("src","/wxscreen/web/common/images/lotteryDefault.png");
				$("#luck_name").empty();
		    })
	    })
   }
 }
}

//停止抽奖,选出中奖用户
function endBtn(){
	$("#startBtn").show();
	$("#endBtn").hide();
	var open = false;
	run(open);
}

//点击"开始抽奖"
var ret;
function startBtn(){
	var option = $("#tagid option:selected").val();
	if(option==-1){
		showLayer(1);
		return;
	}
	var base = $("#may_num").html();
	if(parseInt(base)==0){
		showLayer(6);
		return ;
	}

    var alreadyNum = $("#luck_index").children("li").length;//抽中的人数
    var tagNum = $("#tagNum").val()==""?0:$("#tagNum").val();//限制抽取人数
    if(alreadyNum==parseInt(tagNum)){
    	showLayer(3);
    	return;
    }
    $("#tagid").attr("disabled","disabled");
	$(".lotteryDefault").hide();
	$("#startBtn").hide();
	$("#endBtn").show();
	
	var open = true;
	run(open);//调用抽奖方法
}

function run(open){
	if(open){
		timer=setInterval(change,50);
	}else{
		change("gain");//点击"停止抽奖"时设置一个标识,根据标识实现选出中奖用户
		clearInterval(timer);
	}
}

function change(ret){
	var openidList = getSignData();
	var alldata = new Array();
	var newalldata = new Array();
	var option = $("#tagid option:selected").val();
	var tagExclude = $("#tagExclude").val();
	var numExclude = $("#numExclude").val();
	for(var i=0;i<openidList.length;i++){
		alldata[i]=getSignData().eq(i).find("input").val();
		if(option>0 && tagExclude==1){//按奖项抽奖,并且排重
			var temp = new Array();
			temp = alldata[i].split("|");
			var index = tagList.indexOf(temp[3]);
			if(index==-1){
				newalldata.push(alldata[i]);
			}
		}
	}
	//将排重之后的数据作为抽奖的基数
	if(option>0 && tagExclude==1){
		if(newalldata!=null && newalldata.length>0){
			alldata = null;
			alldata = newalldata;
		}
	}
	var num = alldata.length - 1;
	
	var randomVal = Math.round(Math.random() * num);
	var prizeName = alldata[randomVal];
	var msg = new Array();
	msg = prizeName.split("|");
	$("#luck_img").attr("src",msg[2]);
	$("#luck_name").text(msg[0]);
	if(ret=="gain"){//点击"停止抽奖",选定中奖用户
		$("#tagid").removeAttr("disabled");
	    jQuery.ajax({
	    	url:"/pc/luck_saveLuckUser.do",
	    	data:{"luckUser.openid":msg[3],"luckUser.luckTagId":option},
	    	type:"post",
	    	async:false,//设置为同步
	    	success:function(data){
	    		if(parseInt(data)>0){
	    		   idx = $("#luck_index li").length;
	    			$("#lotteryName").after('<li id="'+msg[3]+'"><p class="prize">'+$("#luckname").val()+'</p><i class="sn">'+parseInt(idx+1)+'</i>'+
							'<p class="man"><img src="'+msg[2]+'" />'+msg[0]+'</p><i class="delLottery"  onclick=confirmLayer("'+msg[3]+'",'+data+');></i></li>');
					var lucknum = $("#luck_index").children("li").last().index();
					$("#lucknum").text(lucknum);//动态设置获奖人数
					idx++;
					$(".lotteryName li").on({
						mouseenter: function(){
							$(this).addClass("act");
						},
						mouseleave: function(){
							$(this).removeClass("act");
						}
					})
	    		}
	    	}
	    });
		
	    if(option>0){//按奖项抽奖
			if($("#tagExclude").val()==1){//取按奖项的规则
				tagList+=msg[3]+",";
				var base = $("#may_num").html();
				$("#may_num").html(parseInt(base-1)>=0?parseInt(base-1):0);
			}
	    }
	}
}

//////////////按人数抽奖//////////////////
function changeClick(){
	$("#startNum").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#startNum").unbind('click');
	$("#startNum").click(function(){  
	   alert("正在进行，不能点击");
	});  
		
	$("#endNum").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#endNum").unbind('click');
	$("#endNum").click(function(){  
	  alert("正在进行，不能点击");
	});  
	
	$("#newLuckButton").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#newLuckButton").unbind('click');
	$("#newLuckButton").click(function(){  
	  alert("正在进行，不能点击");
	});  
	
}

function recoverClick(){
	$("#startNum").attr('onclick',''); 
	$("#endNum").attr('onclick',''); 
	$("#newLuckButton").attr('onclick','');
	
	$("#startNum").unbind('click');
	$("#endNum").unbind('click');
	$("#newLuckButton").unbind('click');
	
	$("#startNum").attr('onclick','start()');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#endNum").attr('onclick','stop()');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#newLuckButton").attr('onclick','javascript:reset();');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
}

var isChange=true;
var num;
var timer;
var numPrizeName;
//首次启动方法
function start(i){
	$(".lotteryDefault").hide();
	if(i!=2){
		//按人数抽奖,如果选择的人数大于可抽奖的人数,则把可抽奖人数赋予选择的值
		num=parseInt($("#num option:selected").val());
		var base = $("#may_num").html();
		if(parseInt(base)==0){
			showLayer(6);
			return ;
		}
		$("#tagid").attr("disabled","disabled");
		var numExclude = $("#numExclude").val();
		if(numExclude==1){
			if(num>parseInt(base)){
				num = base;
			}
		}
	}
	timer=setInterval(function(){
		changeNum();
	},50)
	$("#startNum").hide();
	$("#endNum").show();
}

//首次结束方法
function stop(){
	$("#startNum").show();
	$("#endNum").hide();
	clearInterval(timer);
	var base = $("#may_num").html();
	if(num>0 && base!="0"){
    	checked();//选中操作
	}else{//全部人数抽完后,跳出逻辑
		return ;
	}
	num--;
	if(num>0){
    	$("#startNum").val("开始抽奖("+num+")");
	}else{
		$("#tagid").removeAttr("disabled");
		$("#startNum").val("开始抽奖");
	}
	if(num){
		if(isChange){
			changeClick();
			isChange = false;
		}
		
		setTimeout(function(){
			start(2);
		},2000);
		setTimeout(stop,5000);
	}else{
		isChange = true;
		recoverClick();
	}
}

//按人数自动抽奖,选出中奖者
function checked(){
	    var comment = $("#comment").val().trim();
	    if(comment==''){
	    	comment = "参与奖";
	    }
		var msg = new Array();
		msg = numPrizeName.split("|");
	    jQuery.ajax({
	    	url:"/pc/luck_saveLuckUser.do",
	    	data:{"luckUser.openid":msg[3],"luckUser.luckTagId":0,"luckUser.perAward":comment},
	    	type:"post",
	    	async:false,//设置为同步
	    	success:function(data){
	    	if(parseInt(data)>0){
	    		idx = $("#luck_index li").length;
				$("#lotteryName").after('<li id="'+msg[3]+'"><p class="prize">'+comment+'</p><i class="sn">'+parseInt(idx+1)+'</i>'+
						'<p class="man"><img src="'+msg[2]+'" />'+msg[0]+'</p><i class="delLottery"  onclick=confirmLayer("'+msg[3]+'",'+data+');></i></li>');
				var lucknum = $("#luck_index").children("li").last().index();
				$("#lucknum").text(lucknum);//动态设置获奖人数
				idx++;
				$(".lotteryName li").on({
					mouseenter: function(){
						$(this).addClass("act");
					},
					mouseleave: function(){
						$(this).removeClass("act");
					}
				})
	    	  }
	    	}
	    });
	    //按人数抽奖,并且不能重复中奖,走下面规则
	    var numExclude = $("#numExclude").val();
	    if(numExclude==1){
			var base = $("#may_num").html();
			$("#may_num").html(parseInt(base-1)>=0?parseInt(base-1):0);
			if($("#numExclude").val()==1){
				numList+=msg[3]+",";
			}
			base = $("#may_num").html();
			if(base==0){
//				alert("全部人数已经抽奖完毕");
				showLayer(7);
				return ;
			}
	    }
}

function changeNum(){
	var openidList = getSignData();
	var alldata = new Array();
	var newalldata = new Array();
	var option = $("#tagid option:selected").val();
	var numExclude = $("#numExclude").val();
	for(var i=0;i<openidList.length;i++){
		alldata[i]=getSignData().eq(i).find("input").val();
		if(numExclude==1){//根据后台配置规则排重
			var temp = new Array();
			temp = alldata[i].split("|");
			var index = numList.indexOf(temp[3]);
			if(index==-1){
				newalldata.push(alldata[i]);
			}
	 }
	}
	//将排重之后的数据作为抽奖的基数
	if(numExclude==1){
		if(newalldata!=null && newalldata.length>0){
			alldata = null;
			alldata = newalldata;
		}
	}
	var num = alldata.length - 1;
	var randomVal = Math.round(Math.random() * num);
    numPrizeName = alldata[randomVal];
	var msg = new Array();
	msg = numPrizeName.split("|");
	$("#luck_img").attr("src",msg[2]);
	$("#luck_name").text(msg[0]);
}
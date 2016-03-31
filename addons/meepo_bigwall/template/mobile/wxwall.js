/**********************
 * 微信墙前端js
 * mark
 **********************/
//基础配置
var ws_config = {
	updateTime     : 0,
	autoCheckTime  : 5000,     //毫秒
	sayTaskTime    : 4000
}
  	
//消息列表
var ws_say={
	index:0, //下一次将跳转的下标
	indexMax:0, //判断下标最大，如果出现新数据，会直接从此下标开始轮播
	indexDetail:0, //大图展示的下标
	page:3,
	runkey:1, //1运行0停止  
	news:"",
	list:""
}

//取得统计个数据：1,签到;2,留言墙
function getCount(type){
	if(type==1){
		return $("#signlist").children('li.user-had').length;
	}else if(type==2){
		return ws_say.list.length;
	}
}
  		    
var task = null; //检查最新任务
var sayTask = null; //显示留言墙
var isCheck = 1; //1检查，0不检查
  	
function setUpdateTime(t){
	$("#checkTime").val(t);
}

//取得参数
function getConfig(key){
	if(key=='sayTaskTime'){
		return ws_config.sayTaskTime;
	}else if(key=='autoCheckTime'){
		return ws_config.autoCheckTime;
	}
}
  	
/**
 * 初始化方法
 */
function init(){
  	//initSay();//初始化留言墙数据
  	//initSignin();//初始化签到
  	//startTask(); //开始执行更新程序
  	//changeWall(1);//运行签到墙
	$(".loader").hide();
}


//初始化一条数据
//初始签到
function initSignin(){
	var signCount = getSignSum();
	var s00 = 28-signCount%28;
	for(i=0; i<s00; i++){
		$("#signlist").append('<li class="user-no"><div class="play"></div></li>');
	}
}
  	
/**
  * 第一次取留言墙，取所有
  */
function initSay(){
  	var checkTime = $("#checkTime").val();
  	$.getJSON("/pc/say_getNewAjax.do?utime=0&endtime="+checkTime+"&jsoncallback="+new Date().getTime(), function(msg){
		
		ws_say.list = msg.list;
		initSayContent();//初始一屏
		$(".loader").hide();
	});
}
  	
//启动任务
function startTask(){
  	task = setInterval("checkData()",getConfig('autoCheckTime'));
}
  	
//启动留言墙任务
function startSayTask(){
  	if(sayTask==null){
  		sayTask = setInterval("showSay(1)",getConfig('sayTaskTime'));
  		$("#but_play").removeClass("btnPlay").addClass("btnPause");
  	}
}

//停止留言墙任务
function stopSayTask(){
	if(sayTask!=null){
  		window.clearInterval(sayTask);
  		sayTask=null;
  	}
}
  	
/**
 * 判断是否有最新签到和发言
 */
function checkData(){
  	if(isCheck==1){
   		$.getJSON("/pc/sign_hadNewAjax.do?utime="+$("#checkTime").val()+"&jsoncallback="+new Date().getTime(), function(msg){
			setUpdateTime(msg.time);
			if(msg.hadsign==1 || msg.hadsay==1){
				isCheck=0 //停止轮循
				updateData(msg.hadsign,msg.hadsay);
			}
		});
  	}
}
  	
//更新数据
function updateData(hadsign,hadsay){
  	if(hadsign==1){
  		updateSignList();
  	}
  	if(hadsay==1){
  		updateSayList()
  	}
  	isCheck=1;
}
  	
/**
 * 获取最新签到
 */
function updateSignList(){
	var updateTime = $("#signUpdateTime").val();
  	var checkTime = $("#checkTime").val();
  	$.getJSON("/pc/sign_getNewAjax.do?utime="+updateTime+"&endtime="+checkTime+"&jsoncallback="+new Date().getTime(), function(msg){
		$("#signUpdateTime").val(checkTime);
		showSignList(msg.list);
	});
}
  	
//更新签到
function showSignList(d){
  	if(d!=null && d.length>0){
  		stopSignTask();
  		var obj = "";
  		var list = d;
  		stopSignTask2();
		//最新签到
		var content="";
		for (i = 0; i < list.length; i++) {
			$("[id='"+list[i].openid+"']").remove();
			//如果是拉黑或删除
			if(list[i].status==0 || list[i].type==2){
				break;
			}
			if(list[i].content.length>8){
				content = '<p style="font-size:35px;">'+list[i].content+'</p>';
			}else{
				content = '<p>'+list[i].content+'</p>';
			}
			obj = '<li id="'+list[i].openid+'" class="user-had">'+
						'<div class="play">'+
		    				'<input type="hidden" name="p'+list[i].sex+'" value="'+list[i].name+'|'+list[i].sex+'|'+list[i].imgurl+'|'+list[i].openid+'">'+
							'<div class="avatar" ><img src="'+list[i].imgurl+'" /><p>'+list[i].name+'</p></div>'+
							'<div class="siginWords"><i class="leftside"></i>'+content+'</div>'+					
						'</div>'+
					'</li>';
			$("#signlist").prepend(obj);
		}
		checkUserHead();
		runSign();//重新轮播
  	}
}

//检查用户墙布满情况，如果不满最后一墙，用空头像补充
function checkUserHead(){
	var signCount = getSignSum();
	var headNoSum = 28-signCount%28;
	var old_headNoSum = $("#signlist").children("li.user-no").length;
	if(headNoSum>old_headNoSum){
		for(var i=0; i<headNoSum-old_headNoSum; i++){
			$("#signlist").append('<li class="user-no"><div class="play"></div></li>');
		}
	}else if(old_headNoSum > headNoSum){
		var c00 = old_headNoSum-headNoSum;
		$("#signlist").children("li.user-no").slice(0,c00).remove();
	}
}

  	
/**
  * 获取最新发言墙
  */
function updateSayList(){
  	var updateTime = $("#sayUpdateTime").val();
  	var checkTime = $("#checkTime").val();
  	$.getJSON("/pc/say_getNewAjax.do?utime="+updateTime+"&endtime="+checkTime+"&jsoncallback="+new Date().getTime(), function(msg){
		$("#sayUpdateTime").val(checkTime);
		var list = msg.list;
		for(i=0;i<list.length;i++){
			ws_say.list.push(list[i]);
		}
		//更新-20150423 有新数据的话，开始播最新的
		//最新的定义为，第一次刷新后，又新加的未播放的，开始位置
		ws_say.index = ws_say.indexMax;
		ws_say.indexDetail = ws_say.indexMax;
		runSay(1);
	});
}
  	
//获取当前签到人数
function getSignSum(){
  	return $("#signlist").children("li.user-had").length;
}

//获取当前签到数据
function getSignData(){
	return $("#signlist").children("li.user-had");
}
  	
//运行留言墙,1开始0结束
function runSay(type){
  	if(type==1){
   		startSayTask();
   	}else if(type==0){
   		stopSayTask();
   		$("#but_play").removeClass("btnPause").addClass("btnPlay");
   	}
}

/**
 * 初使化留言墙内容 
 */
function initSayContent(){
	$("#saylist").html('');
	addSayList1();
}
  	
//发言墙显示
//type   1为正序2为倒序
function showSay(type){
	//检查下标
  	checkSayIndex();
  	//查看是否开打留言墙-当前未打开则不运行
  	if(checkSayHide() && checkSayDetailHide()){
  		stopSayTask();
  		return ;
  	}
  	
  	//已到最后的话自动停止
  	//---20150422 更新，到最后要返回第一页去
  	if(!checkSayHide()){//如果是列表找开
	  	if(ws_say.index==ws_say.list.length){
	  		wallPage('min');
	  		return ;
	  	}
	}else if(!checkSayDetailHide()){//如果是详情打开
		if(ws_say.indexDetail==ws_say.list.length){
	  		wallPage('min');
	  		return ;
	  	}
	}
  	
  	if(type==1){//正方向
  		if(!checkSayHide()){//如果是列表找开
		  	addSayList1();//正序
			var noSays = $("#saylist").children('li').length;
			if(noSays>3){
				msgWallCss();//动效
			}
  		}else if(!checkSayDetailHide()){//如果是详情打开
  			addSayDetial1();
  		}
  	}else{//反方向
		if(!checkSayHide()){//如果是列表找开
		  	addSayList2();//倒序
			var noSays = $("#saylist").children('li').length;
			if(noSays>3){
				msgWallCss2();//动效
			}
  		}else if(!checkSayDetailHide()){//如果是详情打开
  			addSayDetial2();
  		}
  	}
}

/**
 * 返回发言墙第一页
 * 注：现在已调用了返回第一页功能
   此方法是为了，如果在返回第一页加个不同的动效，就需要调此方法
 */
function returnSayInto(){
	var list = ws_say.list;
	var con = "";
	var getcount = 0; 
	var j=0;
	if(ws_say.list.length>3){//如果条数据大余3，往回滚，因为小于等于3的话只会有一页
		getcount = 3;
	}
	var contentInfo = "";
	for(n=0;n<getcount;n++){
		j = n;
 		//添加内容-txt或img
	  	if(list[j].contenttype=='text'){
	  		if(list[j].content.length<11){
	   			contentInfo='<p class="msgTxt">'+list[j].content+'</p>';
	  		}else if(list[j].content.length>10 ){
	  			contentInfo='<p class="msgTxt" style="font-size:32px;height:45px;line-height:45px;">'+list[j].content+'</p>';
	  		}
	  	}else if(list[j].contenttype=='image'){
	  		contentInfo = '<p class="msgImg"><img src="'+list[j].content+'" /></p>';
	  	}
 		//拼接显示内容
		con = con+'<li onmouseover="showOpenSayInfo(this)" onmouseout="hideOpenSayInfo(this)" onClick="openSayInfo('+j+')">'+
				'<div class="userAvatar">'+
					'<i class="avatarFrame"></i>'+
					'<img src="'+list[j].imgurl+'" class="avatar" />'+
				'</div>'+
				'<div class="msgBox" >'+
					'<h3>'+list[j].name+'：</h3>'+
					contentInfo+
				'</div>'+
				'<div class="detailBtn" ></div>'+
			'</li>';
		ws_say.index = j+1;
  	}
  	$("#saylist").prepend(con);
}

//正方向添加-列表
function addSayList1(){
	
	var list = ws_say.list;
    
	var con = "";
	var j=0;
	var i = ws_say.index;
	//alert(i+"/"+ws_say.indexMax);
	var contentInfo = "";
	var noSays = $("#saylist").children('li').length;
	if(noSays==3){
		noSays = 0;
	}
	for(n=0;n<(ws_say.page-noSays);n++){
  		j = i+n;
   		if(j < list.length){
   			//添加内容-txt或img
   			if(list[j].contenttype=='text'){
   				if(list[j].content.length<11){
	   				contentInfo='<p class="msgTxt">'+list[j].content+'</p>';
   				}else if(list[j].content.length>10 ){
   					contentInfo='<p class="msgTxt" style="font-size:32px;height:45px;line-height:45px;">'+list[j].content+'</p>';
   				}
   			}else if(list[j].contenttype=='image'){
   				contentInfo = '<p class="msgImg"><img src="'+list[j].content+'" /></p>';
   			}
   			//拼接显示内容
			con = con+'<li onmouseover="showOpenSayInfo(this)" onmouseout="hideOpenSayInfo(this)" onClick="openSayInfo('+j+')">'+
						'<div class="userAvatar">'+
							'<i class="avatarFrame"></i>'+
							'<img src="'+list[j].imgurl+'" class="avatar" />'+
						'</div>'+
						'<div class="msgBox colorStyle" >'+
							'<h3>'+list[j].name.sub(30,'...')+'：</h3>'+
							contentInfo+
						'</div>'+
						'<div class="contIcon detailBtn" ></div>'+
					'</li>';
			ws_say.index = j+1;
			if(ws_say.index>ws_say.indexMax){
				ws_say.indexMax =  ws_say.index;
			}
   		}else{
   			break;
   		}
  	}
  	$("#saylist").append(con);
}
//反方向添加
function addSayList2(){
	var list = ws_say.list;
	var con = "";
	var j=0;
	var i = ws_say.index;
	var contentInfo = "";
	for(n=0;n<ws_say.page;n++){
  		j = i+n;
   		if(j < list.length){
   			//添加内容-txt或img
   			if(list[j].contenttype=='text'){
   				if(list[j].content.length<11){
	   				contentInfo='<p class="msgTxt">'+list[j].content+'</p>';
   				}else if(list[j].content.length>10){
   					contentInfo='<p class="msgTxt" style="font-size:32px;height:45px;line-height:45px;">'+list[j].content+'</p>';
   				}
   			}else if(list[j].contenttype=='image'){
   				contentInfo = '<p class="msgImg"><img src="'+list[j].content+'" /></p>';
   			}
   			//拼接显示内容
			con = con+'<li style="display:none;" onmouseover="showOpenSayInfo(this)" onmouseout="hideOpenSayInfo(this)" onClick="openSayInfo('+j+')">'+
						'<div class="userAvatar">'+
							'<i class="avatarFrame"></i>'+
							'<img src="'+list[j].imgurl+'" class="avatar" />'+
						'</div>'+
						'<div class="msgBox colorStyle" >'+
							'<h3>'+list[j].name.sub(30,'...')+'：</h3>'+
							contentInfo+
						'</div>'+
						'<div class="contIcon detailBtn" ></div>'+
					'</li>';
			ws_say.index = j+1;
   		}else{
   			runSay(0);
   			break;
   		}
  	}
  	$("#saylist").prepend(con);
}
//正向添加发言详情
function addSayDetial1(){
	var content = getSayDetail(ws_say.indexDetail);
	$("#sayDetailList").append('<li>'+content+'</li>');
	ws_say.indexDetail = ws_say.indexDetail+1; //预下一个
	//如果超过最大值要去更新最大值
	if(ws_say.indexDetail > ws_say.indexMax){
		ws_say.indexMax =  ws_say.indexDetail;
	}
	//同步列表下标，保证列表下标为前一个，这样能够对上
	if(ws_say.indexDetail>1){
		ws_say.index = ws_say.indexDetail-2;
	}else{
		ws_say.index = 0;
	}
	var scrollWidth=$(".msgDetail").width();
	$("#sayDetailList").animate({"marginLeft":-scrollWidth},1000,function(){
		$("#sayDetailList li:lt(1)").remove();
		$(".msgDetail ul").css("marginLeft",0);
	});
}

//反向添加发言详情
function addSayDetial2(){
	var content = getSayDetail(ws_say.indexDetail-1);
	$("#sayDetailList").prepend('<li>'+content+'</li>');
	var scrollWidth=$(".msgDetail").width();
	$("#sayDetailList").css("marginLeft",-scrollWidth);
	$("#sayDetailList").animate({"marginLeft":0},1000,function(){
		$("#sayDetailList li:gt(0)").remove();
	});
	
}

//发言墙动效-向下滚（新）
function msgWallCss(){
	var scrollHei=$(".msgWall").height();
	$(".msgWall ul").animate({"marginTop":-scrollHei},1000,function(){
		removeMagWallLi(2);
		$(".msgWall ul").css("marginTop",0)
	});
}

//发言墙动效-向上滚（旧）
function msgWallCss2(){
	var scrollHei=$(".msgWall").height();
	$(".msgWall li").slice(0,3).show();
	$(".msgWall ul").css("marginTop",-scrollHei);
	$(".msgWall ul").stop().animate({"marginTop":0},1000,function(){
		$(".siginWall ul").css("marginTop",0);
		removeMagWallLi(1);
	});
}

//消除发言墙多余数据
//type  1为正，2为反
function removeMagWallLi(type){
	//gt 大于 //lt 小于
	if(type==1){
		$("#saylist li:gt(2)").remove();
	}else if(type==2){
		$("#saylist li:lt(3)").remove();
	}
}

//显示查看发言详情样式
function showOpenSayInfo(obj){
	$(obj).children('.detailBtn').show();
	if(ws_say.runkey == 1){
		runSay(0);
	}
}

//隐藏查看发言详情样式
function hideOpenSayInfo(obj){
	$(obj).children('.detailBtn').hide();
	if(ws_say.runkey == 1){
		runSay(1);
	}
}

/**
 * 暂停发言墙详情滚动
 */
function showOpenSayDetail(obj){
	if(ws_say.runkey == 1){
		runSay(0);
	}
}

/**
 * 开始发言墙详情滚动
 */
function hideOpenSayDetail(obj){
	if(ws_say.runkey == 1){
		runSay(1);
	}
}

//弹出发言详情
function openSayInfo(sindex){
	var list = ws_say.list;
	var content = getSayDetail(sindex);
	$("#sayDetailList").html('');
	$("#sayDetailList").append('<li>'+content+'</li>');
	$(".msgWall").fadeOut("fast");
	$(".msgDetail").slideDown();
	ws_say.indexDetail = sindex+1; //下一个标
}

/**
 * 拼装发言墙详情数据
 */
function getSayDetail(sindex){
	var content = '';
	var list = ws_say.list;
	if(sindex > list.length){
		return '';
	}else{
		var say_msgContent = '';
		if(list[sindex].contenttype=='text'){
			if(list[sindex].content.length>40){
				say_msgContent = '<div class="contShow colorStyle">'+
									'<p style="font-size:32px;">'+list[sindex].content+'</p>'+
								 '</div>';
			}else{
				say_msgContent = '<div class="contShow colorStyle">'+
									'<p>'+list[sindex].content+'</p>'+
								 '</div>';
			}
		}else if(list[sindex].contenttype=='image'){
			say_msgContent = '<div class="imgShow">'+
								'<div class="zoomBox">'+
									'<img style="height:330px;width:auto;" src="'+list[sindex].content+'" />'+
								'</div>'+
							 '</div>';
		}
		content = '<div class="contBox msgDetailIn" onmouseover="showOpenSayDetail(this)" onmouseout="hideOpenSayDetail(this)">'+
					'<i class="contIcon msgClose" onClick="closeMsgDetailIn()"></i>'+
					'<div class="msgBar borderStyle">'+
						'<dl class="msgFor clearfix">'+
							'<dt>'+
								'<i class="avatarFrame"></i>'+
								'<img src="'+list[sindex].imgurl+'" class="avatar"/>'+
							'</dt>'+
							'<dd class="colorStyle" id="showSayTitle">'+
								'<h3>'+list[sindex].name.sub(20,'...')+'：</h3>'+
								'<p>来自微信</p>'+
							'</dd>'+
						'</dl>'+
						say_msgContent +
					'</div>'+
				'</div>';
	}
	return content;
}

/**
 * 关闭详情页
 */
function closeMsgDetailIn(){
	//关闭详情
	$(".msgDetail").fadeOut("fast");
	$("#sayDetailList").html('');
	//初使化列表
	ws_say.index = ws_say.indexDetail-1;
	initSayContent();
	$(".msgWall").slideDown();
}

function checkSayOpen(){
	$("p").is(":hidden")
}
  	
//检测留言墙翻墙下标，防止数据出范围崩溃
//小于最小取最小，大于最大取最大
function checkSayIndex(){
  	if(ws_say.indexDetail<=0){
  		ws_say.indexDetail=0;
  	}else if(ws_say.indexDetail>=ws_say.list.length){
  		ws_say.indexDetail=ws_say.list.length;
  	}
  	
  	if(ws_say.index<=0){
  		ws_say.index=0;
  	}else if(ws_say.index>=ws_say.list.length){
  		ws_say.index=ws_say.list.length;
  	}
}
  	
//切换墙
function changeWall(obj){
  	if(obj==1){
  		$("#siginWall").show();
  		$("body").append("<script src='/wxscreen/web/js/signWallV1.0.js'></script>");
		siginWallInit();
  	}else if(obj==2){
  		$("#msgWall").show();
  		ws_say.runkey = 1;
  		runSay(1);
  	}
}
  	
//检查发言墙是否已经不显示当前层
function checkSayHide(){
	return $('.msgWall').is(':hidden');
}

//检查签到墙是否已经不显示当前层
function checkSignHide(){
	return $('.siginWall').is(':hidden');
}

//检查签到墙是否已经不显示当前层
function checkSayDetailHide(){
	return $('.msgDetail').is(':hidden');
}

//公页间隔时间控制  	
var playButTimeKey = 0;
function setTimePlayButKey(){
	playButTimeKey=0;
}

//发言墙是否滚到最大
function sayIndexIsMax(){
	return ws_say.index>=ws_say.list.length;
}

//发言墙是否滚到最小
function sayIndexIsMin(){
	return ws_say.index<=3;	
}

//分页标签控制--判断
function wallPage(key){
	if(playButTimeKey!=0){
		return ;
	}
	playButTimeKey = 1;
	setTimeout("setTimePlayButKey()",1200);
	if(!checkSayHide() || !checkSayDetailHide()){//发言墙
	  	if(key=='next'){
	  		stopSayTask();//此处关闭只是暂时，接下来还要开，所以不去改变按钮状态
	  		if(!sayIndexIsMax()){
	  			showSay(1);
	  		}
	  	}else if(key=='last'){
	  		stopSayTask();
  			if(!checkSayHide()){//如果是列表打开
  				if(!sayIndexIsMin()){
			  		var noSays = $("#saylist").children('li').length;
			  		ws_say.index = ws_say.index-noSays-ws_say.page;
			  		showSay(2);
	  			}
	  		}else if(!checkSayDetailHide()){//如果是详情打开
	  			if(ws_say.indexDetail>1){
		  			ws_say.indexDetail = ws_say.indexDetail-1;
		  			showSay(2);
	  			}
	  		}
	  	}else if(key=='max'){
	  		stopSayTask();
		  	if(!checkSayHide()){//如果是列表打开
  				if(!sayIndexIsMax()){
			  		ws_say.index = ws_say.list.length - ws_say.page;
			  		showSay(1);
			  	}
	  		}else if(!checkSayDetailHide()){//如果是详情打开
	  			if(ws_say.list.length>ws_say.indexDetail){
		  			ws_say.indexDetail = ws_say.list.length-1;
		  			showSay(1);
	  			}
	  		}
	  	}else if(key=='min'){
	  		stopSayTask();
	  		if(!checkSayHide()){//如果是列表打开
  				if(!sayIndexIsMin()){
			  		ws_say.index=0;
			  		showSay(2);
		  		}
	  		}else if(!checkSayDetailHide()){//如果是详情打开
	  			if(ws_say.indexDetail>1){
		  			ws_say.indexDetail = 1;
	  				showSay(2);
	  			}
	  		}
	  	}else if(key=='play'){
	  		openSay();
	  		return ;
	  	}
	  	runSay(1);
	  	ws_say.runkey = 1;
	}else if(!checkSignHide()){
		setSignPage(key);
	}
}

//开始、暂停留言墙
function openSay(){
  	if(sayTask==null){
  		ws_say.runkey = 1;
  		runSay(1);
  	}else{
  		ws_say.runkey = 0;
  		runSay(0);
  	}
}

//全屏
function fullScreen(element){
	if(element.requestFullscreen) {
    element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
}

//返回登录
function jumpLogin(){
	location.href = "/pc/login.do";
}





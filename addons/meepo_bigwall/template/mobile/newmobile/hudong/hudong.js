var alldatapep=[],intermandata = [], interwomandata = [], idxinter = 1, idxall = 1, totalall = 0, totalsex = 0,havechange=true;
// 点击"现场互动"进入频道
function progressInteract() {
//	$("#endInteract").hide();
//	$("#endInteractz").hide();
//	$("#startInteractz").hide();
	var signNum = getCount(1);// 默认签到人数
	$("#interact_Num").find("p em").text(signNum);

	inipep();
	changeInter();
	if(alldatapep.length<=0)
		{
		inialldatapep();
		}
	if(intermandata.length<=0&&interwomandata.length<=0)
		{
		interdata();
		}
	
	totalpep();

}
function inipep() {
	document.getElementById("intersex").style.display = "none";
	document.getElementById("coutOnep").style.display = "block";
	var select = $('#coutOne');
	select.empty();
	for ( var i = 1; i < 20; i++) {
		select.append('<option value="' + i + '">' + i + '</option>');
	}

}
function changeInter() {
	var sele = $("#seltyp").find("option:selected").text();
	if (sele == '随机选人') {
		inipep();
		document.getElementById("interact_index").style.display = "block";
		document.getElementById("interact_index1").style.display = "none";
		document.getElementById("autobtn").style.display = "none";
		document.getElementById("btndiv").style.display = "block";
		var lucknum = $("#interact_index").children("li").last().index();
		$("#interactnum").html(lucknum > 0 ? lucknum : 0);
		//$("#startInteract").show();
		

	} else if (sele == '自选人数性别') {
		inisexpep();
		//interdata();
		document.getElementById("interact_index").style.display = "none";
		document.getElementById("interact_index1").style.display = "block";
		document.getElementById("btndiv").style.display = "none";
		document.getElementById("autobtn").style.display = "block";
		var lucknum = $("#interact_index1").children("li").last().index();
		$("#interactnum").html(lucknum > 0 ? lucknum : 0);
		//$("#startInteractz").show();
	}

}
function inisexpep() {
	document.getElementById("coutOnep").style.display = "none";
	document.getElementById("intersex").style.display = "block";
	var select = $('#intersexm');
	select.empty();
	for ( var i = 1; i < 20; i++) {
		select.append('<option value="' + i + '">' + i + '</option>');
	}
	var selectw = $('#intersexw');
	selectw.empty();
	for ( var i = 1; i < 20; i++) {
		selectw.append('<option value="' + i + '">' + i + '</option>');
	}

}
function interdata() {
	var select = $("input[name^='p1']");
	select.each(function(i, o) {
		var inida=$(o).val();
		if(inida.length>0)
			{
			intermandata[i] = $(o).val();
			}
	});
	var select = $("input[name^='p2']");
	select.each(function(i, o) {
		var inida=$(o).val();
		if(inida.length>0)
			{
			
			interwomandata[i] = $(o).val();
			}
	});
	var num = intermandata.length;
	var num1 = interwomandata.length;
	// totalpair=num+num1;

}

var numauto, numautosexm = 0, numautosexw;
var timerauto, timerauto1, timerautom, timerautow;
var allflag, mans, womans, flas = '';
// var numPrizeName;
// 首次启动方法
function startauto(i) {
	var signNum = getCount(1);// 默认签到人数
	var lucknum = $("#interact_index").children("li").last().index();
	var lucknum1 = $("#interact_index1").children("li").last().index();
	lucknum1 = parseInt(lucknum1);
	lucknum = parseInt(lucknum);
	if (lucknum < 0) {
		lucknum = 0;
	}
	if (lucknum1 < 0) {
		lucknum1 = 0;
	}
	var base = parseInt(signNum) - parseInt(lucknum) - parseInt(lucknum1);
	base=parseInt(base);

	if(base>0)
		{
		$("#interactDefault").hide();
		$("#startInteract").hide();
		$("#endInteract").show();	
		}else{
			 alert(" 参加活动人数不足！ 	");
				$("#startInteract").show();
				$("#endInteract").hide();
				numauto=0;
			 return false;
			}
	$("#seltyp").attr("disabled","disabled");
	if ($("#coutOnep").css("display") == "block") {
		if (i != 2) {
			numauto = parseInt($("#coutOne option:selected").val());
		}
		timerauto = setInterval(function() {
			interactChange(allflag);
		}, 50)
	}
}
// 首次结束方法
function stopauto() {
	console.log("点击全部停止的事件");
	//$("#interactDefault").hide();
	$("#startInteract").hide();
	$("#endInteract").show();
	var nu=numauto-1;
	if(parseInt(numauto)==0)
		{
		nu=parseInt($("#coutOne option:selected").val());
		}
	
	
	$("#endInteract").val("停止互动抽奖("+nu+")");
	clearInterval(timerauto);
	console.log("11111111");
	if (numauto > 0) {
		console.log("222222222");
		interactChange("allflag");
	} else {
		$("#interactDefault").hide();
		$("#startInteract").show();
		$("#endInteract").hide();
		havechange=true;
		 resetclick();
		clearTimeout(timerauto1);
		return;
	}
	numauto--;
	console.log("我是随机的"+numauto);
	if (numauto) {
		if(havechange){
			removeClick();
			havechange = false;
		}
		setTimeout(function() {
			startauto(2);
		}, 2000);
		timerauto1 = setTimeout(stopauto, 5000);
	} else {
		$("#seltyp").removeAttr("disabled");
		$("#interactDefault").hide();
		$("#startInteract").show();
		$("#endInteract").hide();
		havechange=true;
		 resetclick();
	}

}
function inialldatapep()
{
	var openidList = getSignData();
	//var alldatapep = new Array();
	for ( var i = 0; i < openidList.length; i++) {
		alldatapep[i] = getSignData().eq(i).find("input").val();
	}	
}
function interactChange(ret) {
/*	var openidList = getSignData();
	var alldata = new Array();
	for ( var i = 0; i < openidList.length; i++) {
		alldata[i] = getSignData().eq(i).find("input").val();
	}*/
	var num = alldatapep.length - 1;
	var randomVal = Math.round(Math.random() * num);
	var prizeName = alldatapep[randomVal];
	var msg = new Array();
		if(prizeName==undefined)
			{
			randomVal = Math.round(Math.random() * num);
			prizeName = alldatapep[randomVal+1];
			msg = prizeName.split("|");
			}
		else
			{
			msg = prizeName.split("|");
			}
		
	$("#interactImg").attr("src", msg[2]);
	$("#interactText").text(msg[0]);
	if (ret == "allflag") {// 点击"停止抽奖",选定中奖用户
		$("#interactName")
				.after(
						'<li id="'
								+ msg[3]
								+ '"><p class="prize" ><span id="descd"></span></p><i class="sn">'
								+ idxinter
								+ '</i><p class="man"><img src="'
								+ msg[2]
								+ '" />'
								+ msg[0]
								+ '</p>'
								+ '<i class="delLottery" id="'+prizeName+'" onclick=delInteract(this);></i></li>');

		var lucknum = $("#interact_index").children("li").last().index();
		$("#interactnum").html(lucknum);// 动态设置获奖人数
		var aa = $("#desc").val();
		$("#descd").html(aa);
		alldatapep.splice(randomVal, 1);
		idxinter++;
		$("#interact_index").children("li").on({
			mouseenter : function() {
				$(this).addClass("act");
			},
			mouseleave : function() {
				$(this).removeClass("act");
			}
		})
		totalpep();
	}
}

// 删除中奖用户
function delInteract(t) {
	$("#clearhudong1").fadeIn();
	 $("#clearhudong1 :button:eq(0)").off().on("click",function(){
	$("#clearhudong1").hide();
	console.log($(t).parent().attr("id") + "-=-====-=-=");
	var lise = $(t).parent().attr("id") + " .sn";
	//var delnum = $('#' + lise + '').text();
	var delnum = $(t).prev().prev().text();
	console.log("传递之前"+delnum);
	var old=$(t).attr("id");
	$(t).parent().remove();
	alldatapep.push(old);
	sortall(delnum);
	// 设置参加抽奖人数
	var base = $("#interact_Num").find("p em").html();
	$("#interact_Num").find("p em").html(parseInt(base) + 1);
	$("#interactImg").attr("src", "/wxscreen/web/common/images/onSiteDefault.png");
	$("#interactText").text('***');
	 })
}
function sortall(delnum) {
	console.log("随机的开始排序"+delnum);
	var snList = $("#interact_index li");
	for ( var i = 0; i < snList.length; i++) {
		var cc = snList.eq(i).find(".sn").text();
		console.log("cccc++=="+cc);
		cc=parseInt(cc);
		delnum=parseInt(delnum);
		if (cc > delnum) {
			snList.eq(i).find(".sn").text(parseInt(cc) - 1);
			console.log(snList.eq(i).find(".sn").text()+"输出");
		}
	}
	var idxs = snList.length + 1;
	idxinter = idxinter - 1;
	// 设置获奖名单人数
	var alreadyNum = $("#interact_index").children("li").length;
	$("#interactnum").text(alreadyNum);

}
// 点击"开始抽奖"
function startInteract(i) {
/*	if(havechange)
		{
		resetclick1();
		}*/
	var signNum = getCount(1);// 默认签到人数
	var lucknum = $("#interact_index").children("li").last().index();
	var lucknum1 = $("#interact_index1").children("li").last().index();
	lucknum1 = parseInt(lucknum1);
	lucknum = parseInt(lucknum);
	if (lucknum < 0) {
		lucknum = 0;
	}
	if (lucknum1 < 0) {
		lucknum1 = 0;
	}
	var base = parseInt(signNum) - parseInt(lucknum) - parseInt(lucknum1);
	base=parseInt(base);
	var mansource=intermandata.length;
    var womansource=interwomandata.length;
    var manqu=parseInt($("#intersexm option:selected").val());
    var womanqu=parseInt($("#intersexw option:selected").val());
    

	if (base>0)
		{
		$("#interactDefault").hide();
		$("#startInteractz").hide();
		$("#endInteractz").show();
		
		}
	else
		{
			alert("人员不足，请重新选择人数");
			$("#startInteractz").show();
			$("#endInteractz").hide();
			return false;
		}

	if (flas == '') {
		if (i != 3) {
			numautosexm = parseInt($("#intersexm option:selected").val());
			console.log("我是初始化的" + numautosexm);
		}
		//vali();
		    	if(parseInt(mansource)<numautosexm)
				{
				alert("男生不足，请重新选择人数");
				$("#startInteractz").show();
				$("#endInteractz").hide();
				havechange=true;
				resetclick1();
				console.log("=======|||||aoaooa");
				return false;
				
				}
		    	var numautosexws = parseInt($("#intersexw option:selected").val());
			if(parseInt(womansource)<numautosexws)
			{
			alert("女生不足，请重新选择人数");
			$("#startInteractz").show();
			$("#endInteractz").hide();
			havechange=true;
			resetclick1();
			return false;
			
			}
		var num1 = interwomandata.length;
		num1=parseInt(num1);
		if (num1>0)
		{
			console.log("女的大于1kaishi");
		}
	else
		{
		 alert("参加活动女生人数不足！");
			$("#startInteractz").show();
			$("#endInteractz").hide();
		 return false;
		}
		$("#seltyp").attr("disabled","disabled");
		timerautom = setInterval(function() {
			interactChangeman(mans);
		}, 50)
	}

}
function interactRunsex(open) {

	if (open) {
		timer = setInterval(interactChangeman, 50);
		// timer=setInterval(interactChangewoman,50);
	} else {
		interactChangeman("man");
		// interactChangewoman("woman");
		clearInterval(timer);
	}
}
function interactChangewoman(woman) {
	var num = intermandata.length;
	var num1 = interwomandata.length-1;
	var randomVal = Math.round(Math.random() * num1);
	var prizeName = interwomandata[randomVal];
	var msg = new Array();
	msg = prizeName.split("|");
	$("#interactImg").attr("src", msg[2]);
	$("#interactText").text(msg[0]);
	if (woman == "woman") {// 点击"停止抽奖",选定中奖用户
		$("#interactName1")
				.after(
						'<li id="sex'
								+ msg[3]
								+ '"><p class="prize" ><span id="descdsex'
								+ msg[3]
								+ '">花姑娘的干活</span></p><i class="sn">'
								+ idxall
								+ '</i><p class="man"><img src="'
								+ msg[2]
								+ '" />'
								+ msg[0]
								+ '</p>'
								+ '<i class="delLottery" id="'+prizeName+'" onclick=delInteractsex(this);></i></li>');
		var lucknum = $("#interact_index1").children("li").last().index();
		$("#interactnum").html(lucknum);// 动态设置获奖人数
		var aa = $("#desc").val();
		var descdsexm = "descdsex" + msg[3];
		$('#' + descdsexm + '').html(aa);
		interwomandata.splice(randomVal, 1);
		idxall++;
		$("#interact_index1").children("li").on({
			mouseenter : function() {
				$(this).addClass("act");
			},
			mouseleave : function() {
				$(this).removeClass("act");
			}
		})
	}
	totalpep();
}
// 停止抽奖,选出中奖用户
function endInteract() {
	console.log("-----------++++++");
	removeClick1();
	$("#interactDefault").hide();
	$("#endInteractz").hide();
	$("#startInteractz").show();
	
	$("#endInteractz").val("停止互动抽奖("+numautosexm+")");
	clearInterval(timerautom);
	if (numautosexm > 0) {
		interactChangeman("man");
	}
	numautosexm--;
	
	var num = intermandata.length;
	num=parseInt(num);
		if (num>0)
		{
		console.log("男的大于1");
		}
	else
		{
		 alert("参加活动男生人数不足！");
			$("#startInteractz").show();
			$("#endInteractz").hide();
			havechange=true;
			resetclick1();
		 return false;
		}
	if (numautosexm) {
		if(havechange){
			removeClick1();
			havechange = false;
		}
		setTimeout(function() {
			startInteract(3);
		}, 2000);
		setTimeout(endInteract, 5000);
	}
	else
	{
	$("#seltyp").removeAttr("disabled");
	havechange=true;
	resetclick1();
	}

	if (numautosexm == 0) {
		// flas='have';
		startautowoman();
		stopautowoman();
	}
}

function startautowoman(i) {
	var signNum = getCount(1);// 默认签到人数
	var lucknum = $("#interact_index").children("li").last().index();
	var lucknum1 = $("#interact_index1").children("li").last().index();
	lucknum1 = parseInt(lucknum1);
	lucknum = parseInt(lucknum);
	if (lucknum < 0) {
		lucknum = 0;
	}
	if (lucknum1 < 0) {
		lucknum1 = 0;
	}
	var base = parseInt(signNum) - parseInt(lucknum) - parseInt(lucknum1);
	base=parseInt(base);
	var mansource=intermandata.length;
    var womansource=interwomandata.length;
    var manqu=parseInt($("#intersexm option:selected").val());
    var womanqu=parseInt($("#intersexw option:selected").val());
	if (base>0)
		{
		$("#interactDefault").hide();
		$("#startInteractz").hide();
		$("#endInteractz").show();
		if (i != 4) {
			console.log("等于444");
			numautosexw = parseInt($("#intersexw option:selected").val());
		}
		timerautow = setInterval(function() {
			interactChangewoman(womans);
		}, 50);
		if(parseInt(womansource)<numautosexw)
		{
		alert("女生不足，请重新选择人数");
		$("#startInteractz").show();
		$("#endInteractz").hide();
		numautosexw=0;
		clearInterval(timerautow);
		havechange=true;
		resetclick1();
		
		return false;
		
		}

		}
	else
		{
		$("#interact_Num").find("p em").html(base);
			alert("人员不足，请重新选择人数");
			$("#startInteractz").show();
			$("#endInteractz").hide();
			clearInterval(timerautow);
			havechange=true;
			resetclick1();
			return false;
		}




}

function stopautowoman() {
	/*	if(havechange)
	{
	resetclick1();
	}*/
	removeClick1();
	$("#interactDefault").hide();
	$("#endInteractz").hide();
	$("#startInteractz").show();
	var nuw=numautosexw-1;
	$("#endInteractz").val("停止互动抽奖("+nuw+")");
	clearInterval(timerautow);
	if (numautosexw > 0) {
		interactChangewoman("woman");
		console.log("哎呀" + numautosexw);
	}else {
		$("#interactDefault").hide();
		$("#startInteract").show();
		$("#endInteract").hide();
		clearInterval(timerautow);
		resetclick1();
		flas = '';
	}
	numautosexw--;
	//console.log("哎呀" + numautosexw);
	var num1 = interwomandata.length;
	if (num1>=0&&numautosexw>0)
	{
		console.log("女的大于1stop");
	}
else
	{
	// alert("参加活动女生人数不足！==");
		$("#startInteractz").show();
		$("#endInteractz").hide();
		clearInterval(timerautow);
		flas = '';
		resetclick1();
		 return false;
	}
	if (numautosexw) {
		setTimeout(function() {
			startautowoman(4);
		}, 2000);
		setTimeout(stopautowoman, 5000);
	} else {
		$("#interactDefault").hide();
		$("#startInteract").show();
		$("#endInteract").hide();
		//removeClick1();
		resetclick1();
		clearInterval(timerautow);
		flas = '';
	}

}

function interactChangeman(man) {
	
	var num = intermandata.length-1;
	var num1 = interwomandata.length;
	var randomVal = Math.round(Math.random() * num);

	var prizeName = intermandata[randomVal];
	var msg = new Array();
	//console.log(prizeName+"男人")
	if (prizeName.length > 0) {
		msg = prizeName.split("|");
	} else {
		return;
	}
	$("#interactImg").attr("src", msg[2]);
	$("#interactText").text(msg[0]);
	if (man == "man") {// 点击"停止抽奖",选定中奖用户
		$("#interactName1")
				.after(
						'<li id="sex'
								+ msg[3]
								+ '"><p class="prize" ><span id="descdsex'
								+ msg[3]
								+ '">花姑娘的干活</span></p><i class="sn">'
								+ idxall
								+ '</i><p class="man"><img src="'
								+ msg[2]
								+ '" />'
								+ msg[0]
								+ '</p>'
								+ '<i class="delLottery" id="'+prizeName+'"onclick=delInteractsex(this);></i></li>');

		var lucknum = $("#interact_index1").children("li").last().index();
		$("#interactnum").html(lucknum);// 动态设置获奖人数
		var aa = $("#desc").val();

		var descdsexm = "descdsex" + msg[3];
		$('#' + descdsexm + '').html(aa);
		intermandata.splice(randomVal, 1);
		idxall++;
		$("#interact_index1").children("li").on({
			mouseenter : function() {
				$(this).addClass("act");
			},
			mouseleave : function() {
				$(this).removeClass("act");
			}
		})
		totalpep();
	}

}

function delInteractsex(t) {
	var lise = $(t).parent().attr("id") + " .sn";
	//var delnum = $('#' + lise + '').text();
	var delnum = $(t).prev().prev().text();
	var del=$(t).attr("id");
	var mg=del.split("|");
	console.log(mg[1]+"pppppppp")
	if(mg[1]==1)
		{
		intermandata.push(del);
		}
	if(mg[1]==2)
	{
		interwomandata.push(del);
	}
	//intermandata = [], interwomandata = []
	$(t).parent().remove();
	sortsex(delnum);
	var lucknum = $("#interact_index1").children("li").last().index();
	$("#interactnum").html(lucknum > 0 ? lucknum : 0);// 动态设置获奖人数
	totalpep();
	$("#interactImg").attr("src", "/wxscreen/web/common/images/onSiteDefault.png");
	$("#interactText").text('***');
}

function totalpep() {
	var signNum = getCount(1);// 默认签到人数
	var lucknum = $("#interact_index").children("li").last().index();
	var lucknum1 = $("#interact_index1").children("li").last().index();
	lucknum1 = parseInt(lucknum1);
	lucknum = parseInt(lucknum);
	if (lucknum < 0) {
		lucknum = 0;
	}
	if (lucknum1 < 0) {
		lucknum1 = 0;
	}
	var base = parseInt(signNum) - parseInt(lucknum) - parseInt(lucknum1);
	$("#interact_Num").find("p em").html(parseInt(base));
}

function sortsex(delnum) {
	console.log("开始重新排序了");
	var snList = $("#interact_index1 li");
	for ( var i = 0; i < snList.length; i++) {
		var cc = snList.eq(i).find(".sn").text();
		if (cc > delnum) {
			console.log(cc)
			snList.eq(i).find(".sn").text(cc - 1);
		}
	}
	var idxs = snList.length + 1;
	idxall = idxall - 1;
	// 设置获奖名单人数
	var alreadyNum = $("#interact_index1").children("li").length;
	$("#interactnum").text(alreadyNum);

}

// 全部清空
function interactReset() {

	$("#clearhudong").fadeIn();
	 $("#clearhudong :button:eq(0)").off().on("click",function(){
			$("#clearhudong").hide();
		if ($("#interact_index").css("display") == "block") {
			var alreadyNum = $("#interact_index").children("li").length;
			$("#interactName").siblings().remove();
			$("#interactnum").html(0);
			// var base = $("#interact_Num").find("p em").html();
			// $("#interact_Num").find("p
			// em").html(parseInt(base)+parseInt(alreadyNum));
			totalpep();
			idxinter = 1;
			inialldatapep();
		}
		
		if ($("#interact_index1").css("display") == "block") {
			var alreadyNum = $("#interact_index1").children("li").length;
			$("#interactName1").siblings().remove();
			$("#interactnum").html(0);
			// var base = $("#interact_Num").find("p em").html();
			// $("#interact_Num").find("p
			// em").html(parseInt(base)+parseInt(alreadyNum));
			totalpep();
			idxall = 1;
			interdata();
		}
		$("#interactImg").attr("src", "/wxscreen/web/common/images/onSiteDefault.png");
		$("#interactText").text('***');
	 })
}

function vali()
{
	var num = intermandata.length;
	var num1 = interwomandata.length;
	num=parseInt(num);
	num1=parseInt(num1);
	if (num>0)
		{
		console.log("男的大于1");
		}
	else
		{
		 alert("参加活动男生人数不足！");
			$("#startInteractz").show();
			$("#endInteractz").hide();
			havechange=true;
			resetclick1();
		 return false;
		}
	
	if (num1>0)
	{
		console.log("女的大于1vail");
	}
else
	{
	 alert("参加活动女生人数不足！----");
		$("#startInteractz").show();
		$("#endInteractz").hide();
	 return false;
	}
}



function removeClick()
{
	console.log("removeClick()=====");
	$("#startInteract").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#startInteract").unbind('click');
	$("#startInteract").click(function(){  
	   alert("正在进行，不能点击");
	});  
		
	$("#endInteract").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#endInteract").unbind('click');
	$("#endInteract").click(function(){  
	  alert("正在进行，不能点击");
	});  
}

function resetclick()
{
	console.log("resetclick()|||||||||||||||||||||=====");
	$("#startInteract").attr('onclick',''); 
	$("#endInteract").attr('onclick',''); 
	$("#startInteract").unbind('click');
	$("#endInteract").unbind('click');
	$("#startInteract").attr('onclick','startauto()');
	$("#endInteract").attr('onclick','stopauto()');
}
function removeClick1()
{
	console.log("removeClick()=====");
	$("#startInteractz").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#startInteractz").unbind('click');
	$("#startInteractz").click(function(){  
	   alert("正在进行，不能点击");
	});  
		
	$("#endInteractz").attr('onclick','');  //此方法如不起作用，可使用“ $(this).unbind('click');”  代替  
	$("#endInteractz").unbind('click');
	$("#endInteractz").click(function(){  
	  alert("正在进行，不能点击");
	});  
}

function resetclick1()
{
	console.log("resetclick()|||||||||||||||||||||=====");
	$("#startInteractz").attr('onclick',''); 
	$("#endInteractz").attr('onclick',''); 
	$("#startInteractz").unbind('click');
	$("#endInteractz").unbind('click');
	$("#startInteractz").attr('onclick','startInteract()');
	$("#endInteractz").attr('onclick','endInteract()');
}
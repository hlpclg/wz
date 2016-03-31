var idxs = 1 ,mandata=[], womandata=[],totalpair=0,curgroupId='',delmanflag='',delwomanflag='',delflag=true; 


function progressPairBar(){
//	$("#pairstartBtn1").show();
//	$("#pairendBtn1").hide();
	if(mandata.length<=0&&womandata.length<=0)
		{
		indata();
		}
	
	$("#pairyuan").html(totalpair);
	//changeman('');
    //changewoman('');
	
}
function indata()
{
	var select = $("input[name^='p1']");
	select.each(function(i, o){
		mandata[i] = $(o).val();
	});
	var select = $("input[name^='p2']");
	select.each(function(i, o){
		womandata[i] = $(o).val();
	});
	var num = mandata.length;
	var num1 = womandata.length;

	 totalpair=num+num1;
		console.log(totalpair+"我是初始化的值");

}
function changeman(man,flag)
{
	var num = mandata.length-1 ;
	var num1 = mandata.length ;
	var num2 = womandata.length;
	if(num1< 1)
		{
		console.log("现在拥有的男人"+num1);
		console.log("现在拥有的nv人"+num2);
		$("#pairstartBtn1").hide();
		alert("参加对对碰的男生不足");
		console.log("参加对对碰的男生不足");
		return false;
		}
	console.log(num+"现在几个人");
	var randomVal = Math.round(Math.random() * num);

	
	var prizeName = mandata[randomVal];
	console.log(randomVal+"选择的那个"+prizeName);
	var msg = new Array();
	
/*			if(num==0)
				{
				msg = mandata.split("|");

				}
			else
				{*/
				msg = prizeName.split("|");

				/*}*/

	$(".left").find("dt img").attr("src",msg[2]);
	$(".left").find("dd span").text(msg[0]);

	if(man=="man1"){
		$("#pairlist").prepend('<li class="clearfix" id="idxs'+idxs+'" ><i class="sn">'+idxs+'</i><div class="pairMan clearfix"><dl  id="idxsm'+idxs+'" name="'+prizeName+'"><dt><img src="'+msg[2]+'" /></dt><dd><span>'+msg[0]+'</span></dd><i onclick=delMan(this) class="delMan"></i></dl><dl id="idxsw'+idxs+'"><dt><img src="/wxscreen/web/images/avatar11.png" /></dt><dd><span>慕容晓晓晓</span></dd><i onclick=delWoman(this) class="delWoman"></i></dl><i onclick=delPair(this) class="delPair"></i></li>');
		idxs++;
		closeShow();
		mandata.splice(randomVal, 1);
		
	}
	if(flag==1)
		{
		delmanflag.find("dt img").attr("src",msg[2]);
		delmanflag.find("dd span").text(msg[0]);
		delmanflag.attr("name",prizeName);
		mandata.splice(randomVal, 1);
		curgroupId='';
		delflag=true;
		}

	}

function changewoman(woman,flag)
{
	var num = womandata.length-1;
	var randomVal = Math.round(Math.random() * num);
	var prizeName = womandata[randomVal];
	var msg = new Array();
	if(prizeName.length>0)
		{
		msg = prizeName.split("|");
		}
	else
		{
		return;
		}
	
	$(".right").find("dt img").attr("src",msg[2]);
	$(".right").find("dd span").text(msg[0]);
	if(woman=="woman1"){
		var sel="idxsw"+(idxs-1);
		$('#'+sel+'').find("dt img").attr("src",msg[2]);
		$('#'+sel+'').find("dd span").text(msg[0]);
		$('#'+sel+'').attr("name",prizeName);
		womandata.splice(randomVal, 1);
	}
	if(flag==1)
	{
		delwomanflag.find("dt img").attr("src",msg[2]);
		delwomanflag.find("dd span").text(msg[0]);
		delwomanflag.attr("name",prizeName);
/*		$('#'+curgroupId+'').find("dt img").attr("src",msg[2]);
		$('#'+curgroupId+'').find("dd").text(msg[0]);*/
		womandata.splice(randomVal, 1);
		curgroupId='';
		delflag=true;
	}
	}
function pairstartBtn1(){
	var num = mandata.length;
	var num2 = womandata.length;
	$("#btnret").attr('disabled',true); 
	
	$("#tabm a").attr('disabled',true); 
	if((num<1||num2<1)&&curgroupId.length<1)
		{
		$("#pairstartBtn1").attr('disabled',true); 
		if(num<1)
			{
			alert("配对男生不足");
			}
		if(num2<1)
		{
		alert("配对女生不足");
		}
		$("#btnret").attr('disabled',false);
		return;
		}
	$("#pairstartBtn1").hide();
	$("#pairendBtn1").show();
	
	if(curgroupId.length<1)
		{
		var opens = true;
		console.log("抽取全部的开始");
		run1(opens);//调用抽奖方法
		}
	else
		{
		  if (curgroupId.indexOf('m')>-1)
			  {
			  //单独抽男的
			  var openm=true;
			  console.log("抽取男的开始");
			  runm(openm);
			  
			  }
		  if (curgroupId.indexOf('w')>-1)
		  {
		  //单独抽女的
			  var openw=true;
			  console.log("抽取女的开始");
			  runw(openw);
		  }
		}
	
}

function runm(openm){
	if(openm){
		console.log("开始男的");
		timerm=setInterval(changeman,50);
		console.log("开始男的aaaaa");
	}else{
		console.log("停止男的");
		changeman("",1);
		clearInterval(timerm);
	}
}
function runw(openw){
	if(openw){
		console.log("开始女的");
		timerw=setInterval(changewoman,50);
	}else{
		console.log("停止女的");
		changewoman("",1);
		clearInterval(timerw);
	}
}
function run1(opens){
	if(opens){
		timerw=setInterval(changewoman,50);
		timerm=setInterval(changeman,50);
	}else{
		changeman("man1");
		changewoman("woman1");
		clearInterval(timerm);
		clearInterval(timerw);
	}
}
function pairendBtn1(){
	$("#pairstartBtn1").show();
	$("#pairendBtn1").hide();

			if(curgroupId.length<1)
			{
			var opens = false;
			console.log("停止全部");
			run1(opens);//调用抽奖方法

			totalpair=totalpair-2;
			}
		else
			{
			  if (curgroupId.indexOf('m')>-1)
				  {
				  //单独抽男的
				  var openm=false;
				  console.log("停止男的");
				  runm(openm);
					totalpair=totalpair-1;
				  
				  }
			  if (curgroupId.indexOf('w')>-1)
			  {
			  //单独抽女的
				  var openw=false;
				  console.log("停止女的");
				  runw(openw);
				  totalpair=totalpair-1;
			  
			  }
			}
			 var cou=$("#pairlist li").length;
				$("#ccou").html(cou);
		$("#pairyuan").html(totalpair);	
		$("#btnret").attr('disabled',false); 
}

function btnret(){	
	
		$("#clearall").fadeIn();
		 $("#clearall :button:eq(0)").off().on("click",function(){
			 
				$("#clearall").hide();
			if(delflag)
			{
			
					$("#pairlist").empty();
					$("#pairstartBtn1").show();
					 var cou=$("#pairlist li").length;
						$("#ccou").html(cou);
						 idxs = 1;
						 indata();
							changeman('');
						    changewoman('');
							$("#pairstartBtn1").attr('disabled',false); 
							$("#pairyuan").html(totalpair);	
							$("#pairyuan").html(totalpair);
							$(".right").find("dt img").attr("src",'/wxscreen/web/common/images/pairDefault.png');
							$(".right").find("dd span").text('***');
							$(".left").find("dt img").attr("src",'/wxscreen/web/common/images/pairDefault.png');
							$(".left").find("dd span").html('***');
				
			}else
				{
				console.log(delflag+"我是其他");
				alert("区域中还有未完成的配对，请先完成！");
				return ;
				}
		 });
}

function closeShow(){
	$(".pairName li").on({
		mouseenter: function(){
			$(this).addClass("act");
		},
		mouseleave: function(){
			$(this).removeClass("act");
		}
	});
		$(".pairMan dl").on({
			mouseenter: function(){
				$(this).addClass("cur");
			},
			mouseleave: function(){
				$(this).removeClass("cur");
			}
		});
}


function delPair(cur)
{	
	
	$("#clearhudong1").fadeIn();
	 $("#clearhudong1 :button:eq(0)").off().on("click",function(){
	$("#clearhudong1").hide();
	$("#pairstartBtn1").attr('disabled',false); 
		if(delflag)
		{
					var delli=$( cur ).parent().parent().find(".sn").text();
					$( cur ).parent().parent().remove();
					totalpair=totalpair+2;
					var delWoman=$( cur ).prev().attr("name");
					console.log(delWoman+"|||||");
					var delMan=$( cur ).prev().prev().attr("name");
					console.log(delMan+"========");
					mandata.push(delMan);
					womandata.push(delWoman);
					sorts(delli);
					 var cou=$("#pairlist li").length;
						$("#ccou").html(cou);
				$("#pairyuan").html(totalpair);
				$(".right").find("dt img").attr("src",'/wxscreen/web/common/images/pairDefault.png');
				$(".right").find("dd span").text('***');
				$(".left").find("dt img").attr("src",'/wxscreen/web/common/images/pairDefault.png');
				$(".left").find("dd span").html('***');
		}else
			{
			console.log(delflag+"我是其他");
			alert("区域中还有未完成的配对，请先完成！");
			return ;
			}
	 })
}
function sorts(delli)
{
	var snList=$("#pairlist li");
	for(var i=0;i<snList.length;i++){
		var cc=snList.eq(i).find(".sn").text();
		cc=parseInt(cc);
		if(cc>delli)
		{
			snList.eq(i).find(".sn").text(cc-1);
		}
	}
	idxs=snList.length+1;
}
function delMan(cur)
{
	$("#diagpeng").fadeIn();
	 $("#diagpeng :button:eq(0)").off().on("click",function(){
	$("#pairstartBtn1").attr('disabled',false); 
	$("#diagpeng").hide();
	if(delflag)
		{
		console.log(delflag+"我是正常");
	
		delmanflag=$( cur ).parent();
		$( cur ).parent().find("dt img").attr("src",'');
		$( cur ).parent().find("dd span").text('');
		var delmanss=$( cur ).parent().attr("name");
		mandata.push(delmanss);
		$( cur ).parent().attr("name",'');
		totalpair=totalpair+1;
		$("#pairyuan").html(totalpair);	
		var rightimg=$( cur ).parent().next().find("dt img").attr("src");
		var righttxt=$( cur ).parent().next().find("dd span").text();
		$(".right").find("dt img").attr("src",rightimg);
		$(".right").find("dd span").text(righttxt);
		$(".left").find("dt img").attr("src",'/wxscreen/web/common/images/pairDefault.png');
		$(".left").find("dd span").text('***');
		curgroupId='m';
		delflag=false;
		
		}
	else
		{
		console.log(delflag+"我是其他");
		alert("区域中还有未完成的配对，请先完成！");
		return ;
		}
	 })
}

function delWoman(cur)
{
	$("#diagpeng").fadeIn();
	 $("#diagpeng :button:eq(0)").off().on("click",function(){
	$("#pairstartBtn1").attr('disabled',false); 
	$("#diagpeng").hide();
	if(delflag)
		{
			delwomanflag=$( cur ).parent();
			$( cur ).parent().find("dt img").attr("src",'');
			$( cur ).parent().find("dd span").text('');
			var delwoman=$( cur ).parent().attr("name");
			womandata.push(delwoman);
			$( cur ).parent().attr("name",'');
			totalpair=totalpair+1;
			$("#pairyuan").html(totalpair);	
			var leftimg=$( cur ).parent().prev().find("dt img").attr("src");
			var lefttxt=$( cur ).parent().prev().find("dd span").text();
			$(".left").find("dt img").attr("src",leftimg);
			$(".left").find("dd span").text(lefttxt);
			$(".right").find("dt img").attr("src",'/wxscreen/web/common/images/pairDefault.png');
			$(".right").find("dd span").text('***');
			curgroupId='w';
			delflag=false;
		}
		else
		{
		console.log(delflag+"我是其他");
		alert("区域中还有未完成的配对，请先完成！");
		return ;
		}
	 })
}









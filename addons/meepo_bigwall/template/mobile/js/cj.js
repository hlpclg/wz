  $(function(){
	var cj_gogo;
	var cj_id = 0;
	var cj_jadge = 1;
	var cj_retime = 0;
	var cj_per= new Array(); 
	var cjstart_btn = $(".startLotteryBtn");
	//var url="?i=2&c=entry&do=choujiang&m=wxwall&wxref=mp.weixin.qq.com&wxref=mp.weixin.qq.com#wechat_redirect";
	var url = "{php echo $this->buildSiteUrl($this->createMobileUrl('choujiang'))}";
	var action = 'ready';
   
		function cj_ready() {
		cjstart_btn.children("span").text("正在准备数据…");
		cjstart_btn='';
		$('.lotteryUserNum').html('<img src="../addons/wxwall/template/mobile/cj_plug/images/loading.gif" />');
		$('.winUserNum').html('<img src="../addons/wxwall/template/mobile/cj_plug/images/loading.gif" />');
		  $(".lotteryImg").attr("src","../addons/wxwall/template/mobile/cj_plug/images/pair-default.jpg");
		  $(".lotteryName").html("... ...");
		   cj_per= new Array(); 
			$.getJSON(url,{action:action},function(json){
			if(json){
				 $.each(json, function(i,v){
					cj_per.push(new Array(v['id'],v['avatar'],v['nickname']));
				});
				   $(".lotteryUserNum").html(cj_per.length);
					$(".winUserNum").text(cj_id);
				}else{
					 $(".lotteryUserNum").html('0');
					$(".winUserNum").text(cj_id);
				}
					cjstart_btn = $(".startLotteryBtn");
					cjstart_btn.children("span").text("开始抽奖");
				});
		}
	$(".btnLottery").click(function(){
		
		oopen=switchto(oopen,'cj_layer');
		cj_ready();
		});
	function cj_start(){
		if(cj_retime == '0'){
				cjstart_btn.hide();
			cjstart_btn.attr('class','btn-start stopLotteryBtn');
			cjstart_btn = $(".stopLotteryBtn");
				$('#lotteryNumSel').attr('disabled',true);
			}
				//var obj = eval(json);//通过eval() 函数可以将JSON字符串转化为对象
				var len = cj_per.length;
			if(len>0){
				cj_gogo = setInterval(function(){
					var num = Math.floor(Math.random()*len);
					//var id = obj[num]['id'];
					var cj_id = cj_per[num][0];
					var v = cj_per[num][2];
                  	var avatar = cj_per[num][1];
                  $(".lotteryImg").attr("src",avatar);
                  $(".lotteryName").html(v);
                    $("#cj_mid").val(cj_id); 
					$("#cj_mid").attr('name',num);
					
				},100);
				cj_jadge = 0;
       			cjstart_btn.children("span").text("停止");
				cjstart_btn.fadeIn(1000);
			}else{
				cjstart_btn.show();
				cjstart_btn.attr('class','btn-start startLotteryBtn');
				cjstart_btn = $(".startLotteryBtn");
				alert("墙上的用户已经抽完，请多上来几位！！");
			}
	
		
	}

	function cj_stop(){
			  clearInterval(cj_gogo);
				$('#lotteryNumSel').attr('disabled',false);
			  var cj_mid = $("#cj_mid").val();
			  var del_num= $("#cj_mid").attr('name');
			  cj_id = cj_id + 1;
				cj_per.splice(del_num,1);
			  		var c = new Array( 
					  cj_id,
                  	  $("#cj_mid").val(),
					  $(".lotteryName").html(),
                	  $(".lotteryImg").attr("src")
					  );
					var r = cj_M(c)
               		$(".winUserList").prepend(r);
					$(".winUserNum").text(cj_id);
					$(".lotteryUserNum").text(cj_per.length);
				cjstart_btn.hide();
				var cjcache_bin = cjstart_btn;
				cjstart_btn ='';
			  $.post("{php echo $this->createMobileUrl('cj',array('action'=>'ok'))}",{id:cj_mid},function(msg){
				  if(msg==1){
							cj_jadge = 1;
							cjcache_bin.children("span").text("开始抽奖");
							cjcache_bin.fadeIn(500);
							cjcache_bin.attr('class','btn-start startLotteryBtn');
							cjstart_btn = $(".startLotteryBtn");
							if(cj_retime == 1){
								$(".sstartLotteryBtn").attr('class','btn-start startLotteryBtn');
								cjstart_btn = $(".startLotteryBtn");
								cjstart_btn.children("span").text("开始抽奖");
							}
						if(cj_retime > 1){
							cj_retime = cj_retime-1;
								recj();
									}
				  }
			  });
		}
	function recj(){
	
		cj_start();
		setTimeout(cj_stop,2000);
	}
	$(document).on('click','.startLotteryBtn', function(){
		cj_retime = $('#lotteryNumSel').val();
		if(cj_retime == 1){
		cj_retime =0;
		}
		if(cj_retime != '0'){
			if(cj_retime > parseInt($(".lotteryUserNum").text())){
				alert('真没这么多人呀~~~');
				}else{
				cjstart_btn.children("span").text("正在抽奖...");
				cjstart_btn.attr('class','btn-start sstartLotteryBtn');
				cjstart_btn = $(".1tartLotteryBtn");
				recj();
			}
				}else{
					cj_start();
					//cj_gogo = setInterval(show_number,100);
				}

	});
	$(document).on('click','.stopLotteryBtn', function(){
				cj_stop();
	});
	function cj_M(d) {
        var e = ' <li class="clearfix" data-id="' + d[0] + '">        <p class="head-part cjleft">          <span class="num-p winUserRankNum"><em>' + d[0] + '</em></span>          <a href="javascript:;"><img width="50" height="50" src="' +  d[3] + '" alt=""></a>        </p>        <a href="javascript:;" class="nick-name cjleft winUserName">' +  d[2] + '</a>        <a href="javascript:void(0);" class="del delWinUser" data-id="' + d[1] + '" style="display: none;">×</a>      </li>';
        return e
    }
	$('#cj_resetBtn').click(function(){
       if(confirm("确定重置摇奖池吗？")){
				cjstart_btn.hide();
				$('#cj_resetBtn').hide();
				clearInterval(cj_gogo);
				cjstart_btn.children("span").text("正在准备数据…");
			  $.post("{php echo $this->createMobileUrl('cj',array('action'=>'reset'))}",{},function(msg){
				  if(msg==2){
					cj_jadge = 1;
					cj_id = 0;
					$(".winUserList").empty();
						
					cjstart_btn.fadeIn(1000);
					$('#cj_resetBtn').fadeIn(1000);
						cj_ready();
				  }else{
						$('#cj_resetBtn').show();
						cjstart_btn.show();
					  }
			 	 });
			}
	});
$(document).keydown(function (event)
    {    
           if (event.keyCode == 67) {
				$('.btnLottery').click();
            }
			if(oopen == 'cj_layer'){
				if(event.keyCode == 32){
					cjstart_btn.click();
					}}
    });  
	
});

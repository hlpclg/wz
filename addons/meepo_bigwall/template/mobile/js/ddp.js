  $(function(){
	var _gogo;
	var id = 0;
	var jadge = 1;
	var start_btn = $("#ddp_startBtn");
	$(document).keydown(function (event)
    {    
           if (event.keyCode == 68) {
				$('.btnDdp').click();
            }
			if(oopen == 'ddp_layer'){
				if(event.keyCode == 32){
					$('#ddp_startBtn').click();
					}}
    });  

		function ready() {
			$.getJSON('ddp_plug/ddp_data.php?action=ready',function(json){
      	  $("#ddp_userName").html("♂共" +json[0]+ "位");
          $("#ddp_toUserName").html("♀共" + json[1]+ "位");
		   $("#ddp_userCount").html(json[2]);
			$("#ddp_matchedGroupNum").text(id);
			start_btn.children("span").text("开始对对碰");
				});
		}
	$(".btnDdp").click(function(){
		
		oopen=switchto(oopen,'ddp_layer');
		ready();
		});
	start_btn.click(function(){
		if(jadge){
				start_btn.hide();
		$.getJSON('ddp_plug/ddp_data.php',function(json){
			if(json[0] && json[1]){
				//var obj = eval(json);//通过eval() 函数可以将JSON字符串转化为对象
				var len = json[0].length;
				_gogo = setInterval(function(){
					var num = Math.floor(Math.random()*len);
					//var id = obj[num]['id'];
					var id = json[0][num].id;
					var v = json[0][num].nickname;
                  	var avatar = json[0][num].avatar;
			  var tolen = json[1].length;
			  var tonum = Math.floor(Math.random()*tolen);
			  //var id = obj[num]['id'];
			  var toid = json[1][tonum].id;
			  var tov = json[1][tonum].nickname;
			  var toavatar = json[1][tonum].avatar;
                  $("#ddp_userAvatar").attr("src",avatar);
                  $("#ddp_userName").html(v);
                    $("#mid").val(id); 
					
                  $("#ddp_toUserAvatar").attr("src",toavatar);
                  $("#ddp_toUserName").html(tov);
                    $("#tomid").val(toid); 
				},100);
				jadge = 0;
       			start_btn.children("span").text("停止");
				start_btn.fadeIn(1000);
			}else{
				start_btn.show();
				alert("参与对对碰的女/男嘉宾不足，赶紧上墙来参加吧！");
			}
		});
				//_gogo = setInterval(show_number,100);
		}else{
			  clearInterval(_gogo);
			  var mid = $("#mid").val();
			  var tomid = $("#tomid").val();
			  id = id + 1;
			  		var c = new Array( 
					  id,
                  	  $("#mid").val(),
					  $("#ddp_userName").html(),
                	  $("#ddp_userAvatar").attr("src"),
                      $("#tomid").val(),
                      $("#ddp_toUserName").html(),
					  $("#ddp_toUserAvatar").attr("src")
					  );
					var r = M(c)
               		$("#ddp_matchedUserBox").prepend(r);
					$("#ddp_matchedGroupNum").text(id);
					$("#ddp_userCount").text($("#ddp_userCount").text()-2);
				start_btn.hide();
			  $.post("ddp_plug/ddp_data.php?action=ok",{id:mid,toid:tomid},function(msg){
				  if(msg==1){
					jadge = 1;
					start_btn.children("span").text("开始对对碰");
					start_btn.fadeIn(1000);
				  }
			  });
			}
	});
	function M(d) {
        var e = '      <li class="clearfix ddp_matchedUserOne" data-id="' + d[0] + '">        <span class="num-p num-p-pair ddp_viewOrder"><em>' + d[0] + '</em></span>        <div class="wrap-pair-per">          <span class="icon-heart2"></span>          <div class="pair-sel ddp_matchedUser" data-id="' + d[1] + '">            <a><img width=50 height=50 class="ddp_matchedUserAvatar" src="' +  d[3] + '" alt=""></a>            <a class="pair-name ddp_matchedUserName">' +  d[2] + '</a>            <a href="javascript:void(0);" class="del2 delMatchedUserBtn">×</a>          </div>          <div class="pair-sel ddp_matchedToUser" data-id="' +  d[4] + '">            <a><img width=50 height=50 class="ddp_matchedToUserAvatar" src="' + d[6] + '" alt=""></a>            <a class="pair-name ddp_matchedToUserName">' +  d[5] + '</a>            <a href="javascript:void(0);" class="del2 delMatchedUserBtn">×</a>          </div>        </div>        <a href="javascript:void(0);" class="del del3 delMatchedAllBtn">×</a>      </li>';
        return e
    }
	$('#ddp_resetBtn').click(function(){
       if(confirm("确定要拆散这些有缘人吗？")){
				start_btn.hide();
				$('#ddp_resetBtn').hide();
				clearInterval(_gogo);
				start_btn.children("span").text("开始对对碰");
			  $.post("ddp_plug/ddp_data.php?action=reset",{},function(msg){
				  if(msg==2){
					jadge = 1;
					id = 0;
					$("#ddp_matchedUserBox").empty();
							ready();
					start_btn.fadeIn(1000);
					$('#ddp_resetBtn').fadeIn(1000);
				  }else{
						$('#ddp_resetBtn').show();
						start_btn.show();
					  }
			 	 });
			}
	});

});
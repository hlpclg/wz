define(['jquery.min','w.alert'],function(){

	return {
		game:function(){
			//$('#i-ranking').show();

			if($('#stuts1').val() != 'zt'){
				$('#i-relay').show();
				
			}
			if($('#stuts3').val() == 'pt'){

				$('#i-ranking').show();
			//	$('#i-invite').show();
			//	$("#i-again").show();
				$('#i-relay').hide();
				$('#i-start').hide();
				$('#i-invite').show();

			}
			if($('#stuts3').val() == 'pf'){
				alert("dd");
				$('#i-start').show();
				$('#i-relay').hide();
			}
			if($('#stuts2').val() == 'pf'){
				$('#i-self').show();
				$('#i-invite-continue').hide();
				$('#i-ranking').show();
			}
			if($('#stuts2').val() == 'pt'){
				$('#i-ranking').show();
				$('#i-back').show();
			}
			if($('#a').val() == '可以兑换'){
				$('#i-reward').show();
				$('#i-reward').bind('click',function(){
					event.preventDefault();
					$('#box').show();
				})
				
			}
			if($('#a').val() == '已兑换'){
				$('#i-xinxi').show();
				
			}
			$('#i-xinxi').bind('click',function(){
				$('#code').show();
			})
			$('#i-reward').bind('click',function(){
                $(this).w_alert({
                    isForm:true,
					url:$('#furl').val(),
					title:'请输入您的手机号',
                })
				$('#submit').bind('click',function(){
					event.preventDefault();
					if($('#tel').val().length == 0){
						alert('请输入手机号码！');
					}else{
						window.location.href = $('#furl').val();
					}
				})
            })
			$('#i-xinxi').bind('click',function(){
                $(this).w_alert({
                    isForm:false,
					title:'您的兑奖码',
					spanInfo:$('#code').val(),
                })
            })
			
			
			
		}
	}
})
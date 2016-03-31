$(function() {
	setInterval(function(){
		var firstLi=$(".scrollBox li").first(),
			heiLi=$(".scrollBox li").first().height()-10,
			len=$(".scrollBox li").length;
		if(len>1){
			$(".scrollBox ul").animate({"marginTop":-heiLi+"px"},1000,function(){
				$(this).append(firstLi);
				$(this).css("marginTop",10+"px")
			})
		}else{
			return false;
		}
	},5000)
	
	//全局的ajax访问，处理ajax清求时sesion超时 
	$.ajaxSetup({ 
		contentType : "application/x-www-form-urlencoded;charset=utf-8", 
		complete : function(XMLHttpRequest, textStatus) { 
			var sessionstatus = XMLHttpRequest.status; // 通过XMLHttpRequest取得响应头，sessionstatus， 
			if (sessionstatus == 403) { 
//				如果超时就处理 ，指定要跳转的页面 
				window.location.href='/pc/login.do';
			} 
		} 
	}); 
})
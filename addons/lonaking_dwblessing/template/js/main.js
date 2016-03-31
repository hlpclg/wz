var api_action_url;
var action_url;
var main = {
	
	init : function(){
		api_action_url = $("input[name='api_action']").val();
	},

	event: function(){
		/*提交*/
		$("#submit").click(function(){
			var newName = $("input[name='newName']").val();
			if(newName == null || newName == "null" || newName ==""){
				alert("姓名不能为空");
				return ;
			}
			var data = {
				"name" : newName
			};
			var forward =$.post(api_action_url, data,function(result){
				location.href = result;
			});
		});
		
	},
	
	funs : {
		
	},
};
$(function(){
	main.init();
	main.event();
});
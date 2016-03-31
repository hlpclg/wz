//商家举报
var report = {
	//默认可以提交
	cansubmit: true,
	//页面初始化
	init: function () {
		try {
			//绑定举报的提交按钮
			$('#btnSubmit').bind('click', function () {
				//验证举报事由不能为空
				var reason = $.trim($('#txtReason').val());
				if (reason == '') {
					alert('请输入举报事由。');
					return;
				}
				//验证举报者姓名不能为空
				var name = $.trim($('#txtName').val());
				if (name == '') {
					alert('请输入您的名字。');
					return;
				}
				//验证举报者电话不能为空
				var phone = $.trim($('#txtPhone').val());
				if (phone == '') {
					alert('请输入电话号码。');
					return;
				}
				//验证举报者电话格式
				var isMobile = /^(?:1\d|1\d)\d{6}(\d{3}|\*{3})$/;
				var isPhone = /^((0\d{2,3})-)(\d{7,8})$/;
				if (!isMobile.test(phone) && !isPhone.test(phone)) {
					alert('请输入正确的电话号码。');
					return;
				}
				//如果没有被阻止提交，则将举报信息提交到服务器
				if (report.cansubmit) {
					alert('您的举报信息已提交。');
					history.go(-1);
				}
				//设为无法提交，防止重复提交
				report.cansubmit = false;
			});
		} catch (e) {
			logs.send('report', 'init', e);
		}
	}
};
<?php
$id = $_GPC['id']?$_GPC['id']:0;

if(empty($id)){
	header("Location: http://www.baidu.com");
}

// 添加那个啥
if(checksubmit()){
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['uid'] = $_W['member']['uid'];
	$data['companyid'] = $_GPC['companyid'];
	$data['positionid'] = $_GPC['positionid'];
	$data['name'] = $_GPC['name'];
	$data['phone'] = $_GPC['phone'];
	$data['payment'] = $_GPC['payment'];
	$result = pdo_insert('jy_wei_invitation',$data);
	if($result){
		$msg = "您的申请已成功提交";
	}else{
		$msg = "网络错误，申请失败";
	}
	echo "<script>alert('".$msg."');</script>";
}

$sql = "SELECT * FROM ".tablename('jy_wei_position')." WHERE `id`=:id AND `status`=:status AND `uniacid`=:uniacid";
$position = pdo_fetch($sql,array(":id"=>$id,':status'=>1,':uniacid'=>$_W['uniacid']));

$sql = "SELECT * FROM ".tablename('jy_wei_position')." WHERE `companyid`=:id AND `status`=:status AND `uniacid`=:uniacid";
$positions = pdo_fetchall($sql,array(":id"=>$position['companyid'],':status'=>1,':uniacid'=>$_W['uniacid']));

$sql = "SELECT * FROM ".tablename('jy_wei_company')." WHERE `id`=:id AND `status`=:status AND `uniacid`=:uniacid";
$company = pdo_fetch($sql,array(":id"=>$position['companyid'],':status'=>1,':uniacid'=>$_W['uniacid']));

// 再次验证
if(!$company){
	exit('该公司已取消招聘');
}else if(!$position){
	exit('该职位已取消招聘');
}

include $this->template('position');
?>
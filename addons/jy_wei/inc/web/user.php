<?php
$title = "申请的用户";

$ops = array('display', 'delete');
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';


// 展示
if($op=='display'){
	$companyid = $_GPC['company']?$_GPC['company']:0;
	if(empty($companyid)){
		$sql = 'SELECT i.*,m.nickname,m.avatar,c.name as company,p.name as position FROM '
		.tablename('jy_wei_invitation')." AS i LEFT JOIN "
		.tablename('mc_members')." AS m ON i.uid=m.uid LEFT JOIN"
		.tablename('jy_wei_company')." AS c ON i.companyid=c.id LEFT JOIN"
		.tablename('jy_wei_position')." AS p ON i.positionid=p.id"
		." WHERE i.status=:status AND i.uniacid=:uniacid";
		$cache = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));
	}else{
		$sql = 'SELECT i.*,m.nickname,m.avatar,c.name as company,p.name as position FROM '
		.tablename('jy_wei_invitation')." AS i LEFT JOIN "
		.tablename('mc_members')." AS m ON i.uid=m.uid LEFT JOIN"
		.tablename('jy_wei_company')." AS c ON i.companyid=c.id LEFT JOIN"
		.tablename('jy_wei_position')." AS p ON i.positionid=p.id"
		." WHERE i.status=:status AND i.uniacid=:uniacid AND i.companyid=:companyid";
		$cache = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid'],':companyid'=>$companyid));
	}
	
	$sql = "SELECT * FROM ".tablename('jy_wei_company')." WHERE status=:status AND uniacid=:uniacid";
	$company = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));
	include $this->template('user_display');
}

// 删除
if($op=='delete'){
	$id = $_GPC['id'];
	$result = pdo_update('jy_wei_invitation',array('status'=>0),array('id'=>$id));
	if($result){
		message("操作成功",$this->createWebUrl('user'),"success");
	}else{
		message("操作失败",$this->createWebUrl('user'),"error");
	}
}
?>
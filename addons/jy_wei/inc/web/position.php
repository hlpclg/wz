<?php
$title = "标签管理";

$ops = array('display', 'set', 'delete'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
// 获取动作

// 展示
if($op=='display'){
	$sql = 'SELECT * FROM '.tablename('jy_wei_position')." WHERE `status`=:status AND `uniacid`=:uniacid AND `companyid`<>0";
	$cache = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));
	$sql = 'SELECT * FROM '.tablename('jy_wei_company')." WHERE `status`=:status AND `uniacid`=:uniacid AND `id`=:id";
	foreach($cache as $key=>$value){
		$cache[$key]['company'] = pdo_fetch($sql,array(':status'=>1,':uniacid'=>$_W['uniacid'],':id'=>$value['companyid']));
	}
	include $this->template('position_display');
}

// 编辑+添加
if($op=='set'){

	load()->func('tpl');

	// 添加所有公司
	$id =  $_GPC['id'] ? $_GPC['id']:0;

	if(checksubmit()){
		
		// 提取数据
		$companyid = $_GPC['company'];
		if($companyid==null){
			$companyid = array();
		}
		$companyid[] = "0";

		$data = array();
		$data['name'] = $_GPC['name'];
		$data['payment'] = $_GPC['payment'];
		$data['description'] = $_GPC['description'];
		$data['uniacid'] = $_W['uniacid'];
		
		if($id){
			$result = pdo_update('jy_wei_position',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
		}else{
			foreach($companyid as $key=>$value){
				$data['companyid'] = $value;
				$result = pdo_insert('jy_wei_position',$data);
			}
		}
		if($result){
			message("添加成功",$this->createWebUrl('position'),"success");
		}else{
			message("添加失败",$this->createWebUrl('position'),"error");
		}
	}

	$sql = 'SELECT * FROM '.tablename('jy_wei_company')." WHERE `status`=:status AND `uniacid`=:uniacid";
	$company = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));

	// 获取Label company数组
	if($id){
		$sql = "SELECT * from ".tablename('jy_wei_position')." WHERE `id`=:id AND `status`=:status AND `uniacid`=:uniacid";
		$cache = pdo_fetch($sql,array(':id'=>$id,':status'=>1,':uniacid'=>$_W['uniacid']));
	}else{
		$cache = array();
	}
	include $this->template('position_set');
}

// 删除
if($op=='delete'){
	$id = $_GPC['id'];
	$result = pdo_update('jy_wei_position',array('status'=>0),array('id'=>$id));
	if($result){
		message("删除成功",$this->createWebUrl('position'),"success");
	}else{
		message("删除失败",$this->createWebUrl('position'),"error");
	}
}
?>
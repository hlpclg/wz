<?php
$title = "标签管理";

$ops = array('display', 'set', 'delete'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
// 获取动作

// 展示
if($op=='display'){
	$sql = 'SELECT * FROM '.tablename('jy_wei_label')." WHERE `status`=:status AND `uniacid`=:uniacid GROUP BY `unicode`";
	$cache = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));
	include $this->template('label_display');
}

// 编辑+添加
if($op=='set'){
	// 添加所有公司
	$unicode =  $_GPC['unicode'] ? $_GPC['unicode']:0;

	if(checksubmit()){
		
		// 提取数据
		$companyid = $_GPC['company'];
		if($companyid==null){
			$companyid = array();
		}
		$companyid[] = "0";

		$data = array();
		$data['name'] = $_GPC['name'];
		$data['unicode'] = MD5($_GPC['name']);
		$data['description'] = $_GPC['description'];
		$data['uniacid'] = $_W['uniacid'];
		
		// 删了再插入
		if($unicode){
			$result = pdo_delete('jy_wei_label',array('unicode'=>$unicode,'uniacid'=>$_W['uniacid']));
		}
		foreach($companyid as $key=>$value){
			$data['companyid'] = $value;
			$result = pdo_insert('jy_wei_label',$data);
		}
		$unicode = $data['unicode'];
	}

	$sql = 'SELECT * FROM '.tablename('jy_wei_company')." WHERE `status`=:status AND `uniacid`=:uniacid";
	$company = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));

	// 获取Label company数组

	if($unicode){
		$sql = "SELECT * from ".tablename('jy_wei_label')." WHERE `unicode`=:unicode AND `status`=:status AND `uniacid`=:uniacid";
		$cache = pdo_fetch($sql,array(':unicode'=>$unicode,':status'=>1,':uniacid'=>$_W['uniacid']));
		$temp = pdo_fetchall($sql,array(':unicode'=>$unicode,':status'=>1,':uniacid'=>$_W['uniacid']));
		$companycache = array();
		foreach($temp as $key=>$value){
			$companycache[] = $value['companyid'];
		}
	}else{
		$cache = array();
	}
	include $this->template('label_set');
}

// 删除
if($op=='delete'){
	$unicode = $_GPC['unicode'];
	//$sql = "SELECT id from ".tablename('jy_wei_keyword');
	//$result = pdo_update('jy_wei_keyword',array('status'=>0),array('id'=>$id));
	$result = pdo_delete('jy_wei_label',array('unicode'=>$unicode,'uniacid'=>$_W['uniacid']));
	if($result){
		message("删除成功",$this->createWebUrl('label'),"success");
	}else{
		message("删除失败",$this->createWebUrl('label'),"error");
	}
}
?>
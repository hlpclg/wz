<?php
$title = "公司管理";

$ops = array('display', 'set', 'delete'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
// 获取动作

// 展示
if($op=='display'){
	$sql = 'SELECT * FROM '.tablename('jy_wei_company')." WHERE `status`=:status AND `uniacid`=:uniacid";
	$cache = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));
	include $this->template('company_display');
}

// 编辑+添加
if($op=='set'){
	load()->func('tpl');
	$id =  $_GPC['id'] ?  $_GPC['id']:0;
	if(checksubmit()){
		$data = array();
		$id = $_GPC['id'];
		$data['title'] = $_GPC['title'];
		$data['name'] = $_GPC['name'];
		$data['shortname'] = $_GPC['shortname'];
		$data['url'] = $_GPC['url'];
		$data['logo'] = $_GPC['logo'];
		$data['banner'] = $_GPC['banner'];
		$data['propagenda'] = $_GPC['propagenda'];
		$data['description'] = $_GPC['description'];
		$data['sharetitle'] = $_GPC['sharetitle'];
		$data['sharedescription'] = $_GPC['sharedescription'];
		$data['shareimage'] = $_GPC['shareimage'];
		$data['uniacid'] = $_W['uniacid'];
		if($id){
			$result = pdo_update('jy_wei_company',$data,array('id'=>$id));
		}else{
			$result = pdo_insert('jy_wei_company',$data);
			$id = pdo_insertid();
		}

		// 添加标签还有关键字
		$keyword = $_GPC['keyword'];
		$label = $_GPC['label'];

		pdo_delete('jy_wei_keyword',array('companyid'=>$id));
		pdo_delete('jy_wei_label',array('companyid'=>$id));

		foreach($keyword as $key => $value){
			$sql = "SELECT * FROM ".tablename('jy_wei_keyword')." WHERE `unicode`=:unicode AND `status`=:status AND `uniacid`=:uniacid";
			$temp = pdo_fetch($sql,array(':unicode'=>$value,':status'=>1,':uniacid'=>$_W['uniacid']));
			$tempkeyword = array();
			$tempkeyword['uniacid'] = $_W['uniacid'];
			$tempkeyword['name'] = $temp['name'];
			$tempkeyword['description'] = $temp['description'];
			$tempkeyword['unicode'] = $value;
			$tempkeyword['companyid'] = $id;
			pdo_insert('jy_wei_keyword',$tempkeyword);
		}

		foreach($label as $key => $value){
			$sql = "SELECT * FROM ".tablename('jy_wei_label')." WHERE `unicode`=:unicode AND `status`=:status AND `uniacid`=:uniacid";
			$temp = pdo_fetch($sql,array(':unicode'=>$value,':status'=>1,':uniacid'=>$_W['uniacid']));
			$templabel = array();
			$templabel['uniacid'] = $_W['uniacid'];
			$templabel['name'] = $temp['name'];
			$templabel['description'] = $temp['description'];
			$templabel['unicode'] = $value;
			$templabel['companyid'] = $id;
			pdo_insert('jy_wei_label',$templabel);
		}

	}
	// 标签
	$sql = 'SELECT * FROM '.tablename('jy_wei_label')." WHERE `status`=:status AND `uniacid`=:uniacid GROUP BY `unicode`";
	$label = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));
	// 关键字
	$sql = 'SELECT * FROM '.tablename('jy_wei_keyword')." WHERE `status`=:status AND `uniacid`=:uniacid GROUP BY `unicode`";
	$keyword = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid']));

	$labelarr = array();
	$keywordarr = array();

	if($id){
		$sql = "SELECT * from ".tablename('jy_wei_company')." WHERE `id`=:id AND `status`=:status AND `uniacid`=:uniacid";
		$cache = pdo_fetch($sql,array(':id'=>$id,':status'=>1,':uniacid'=>$_W['uniacid']));

		$sql = 'SELECT * FROM '.tablename('jy_wei_label')." WHERE `status`=:status AND `uniacid`=:uniacid AND `companyid`=:companyid";
		$templabel = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid'],':companyid'=>$id));
		foreach ($templabel as $key => $value) {
			$labelarr[] = $value['unicode'];
		}

		$sql = 'SELECT * FROM '.tablename('jy_wei_keyword')." WHERE `status`=:status AND `uniacid`=:uniacid AND `companyid`=:companyid";
		$tempkeyword = pdo_fetchall($sql,array(':status'=>1,':uniacid'=>$_W['uniacid'],':companyid'=>$id));
		foreach ($tempkeyword as $key => $value) {
			$keywordarr[] = $value['unicode'];
		}
	}else{
		$cache = array();
	}
	include $this->template('company_set');
}

// 删除
if($op=='delete'){
	$id = $_GPC['id'];
	$result = pdo_update('jy_wei_company',array('status'=>0),array('id'=>$id));
	if($result){
		message("删除成功",$this->createWebUrl('Company'),"success");
	}else{
		message("删除失败",$this->createWebUrl('Company'),"error");
	}
}
?>
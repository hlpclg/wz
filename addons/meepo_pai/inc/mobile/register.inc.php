<?php
global $_W,$_GPC;

checkauth();
$uid = $_W['member']['uid'];
$setting = pdo_fetch("SELECT * FROM ".tablename('meepo_pai_set')." WHERE uniacid='{$_W['uniacid']}'");

$register = pdo_fetch("SELECT * FROM ".tablename('meepo_pai')." WHERE uid='{$uid}' AND uniacid ='{$_W['uniacid']}' limit 1");

if($_W['ispost']){
	
	$date = array(
		'nickname'=>$_GPC['__input']['nickname'],
		'school'=>$_GPC['__input']['school'],
		'sex'=>intval($_GPC['__input']['sex']),
	);
	$date['uid'] =intval($_GPC['__input']['uid']);
	if(!empty($uid)){
		unset($date['uid']);
		pdo_update('meepo_pai',$date,array('uid'=>$uid));
	}else{
		pdo_insert('meepo_pai',$date);
	}
	
	exit('success');
}

include $this->template('register');
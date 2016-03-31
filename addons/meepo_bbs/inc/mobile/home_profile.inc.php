<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->model('mc');
load()->func('tpl');
checkauth();
$uid = $_W['member']['uid'];
$fields = array('realname','mobile','address');
$profile = mc_fetch($uid,$fields);

$system_fields = array();
$system_fields['realname']['title'] = '真实姓名';
$system_fields['mobile']['title'] = '手机号(手机号一旦填写不可更改)';
$system_fields['address']['title'] = '地址';

$titles = array();
$titles['realname'] = '真实姓名';
$titles['mobile'] = '手机号';
$titles['address'] = '地址';

if($_W['ispost']){
	$data = array();
	$data['realname'] = trim($_GPC['realname']);
	$data['mobile'] = $_GPC['mobile'];
	$data['address'] = trim($_GPC['address']);
	
	pdo_update('mc_members',$data,array('uniacid'=>$_W['uniacid'],'uid'=>$uid));
	message('修改个人资料成功！',$this->createMobileUrl('home'),success);
}
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
include $this->template($tempalte.'/templates/home/profile');
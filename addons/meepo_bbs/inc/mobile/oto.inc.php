<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

global $_W,$_GPC;
load()->model('mc');
load()->func('tpl');
checkauth();
$uid = $_W['member']['uid'];
$fields = array('realname','mobile','address','company');
$profile = mc_fetch($uid,$fields);

$message = '请认真填写下面的资料，通过审核您可具备本公众号优惠券、折扣券等核销权限！';

$system_fields = array();
$system_fields['realname']['title'] = '真实姓名';
$system_fields['mobile']['title'] = '手机号(手机号一但填写不可更改)';
$system_fields['address']['title'] = '地址';
$system_fields['company']['title'] = '企业名称';

$titles['realname'] = '真实姓名';
$titles['mobile'] = '手机号(手机号一但填写不可更改)';
$titles['address'] = '地址';
$titles['company'] = '企业名称';

$sql = "SELECT * FROM ".tablename('meepo_bbs_o2o_user')." WHERE uid = :uid";
$params = array(':uid'=>$uid);
$o2o = pdo_fetch($sql,$params);


if(!empty($o2o)){
	$mid = $uid;
	$sql = "SELECT password FROM ".tablename('activity_coupon_password')." WHERE uid = :uid AND uniacid = :uniacid";
	$params = array(':uid'=>$mid,':uniacid'=>$_W['uniacid']);
	$password = pdo_fetchcolumn($sql,$params);
	if($o2o['status'] == 1){
		$message = '恭喜您，您的审核已通过，您已具备本公众号优惠券、折扣券等核销权限！如密码泄露请重新更改密码！';
	}
}
/* 
$sql = "SELECT * FROM ".tablename('mc_members')." WHERE uniacid = :uniacid ";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);

foreach ($list as $li){
	if(!empty($li['company'])){
		$sql = "SELECT openid,acid FROM ".tablename('mc_mapping_fans')." WHERE acid = :acid AND uid = :uid";
		$params = array(':acid'=>$_W['acid'],':uid'=>$li['uid']);
		$fans = pdo_fetch($sql,$params);
		$data = array();
		$data['realname'] = trim($li['realname']);
		$data['mobile'] = intval($li['mobile']);
		$data['address'] = trim($li['address']);
		$data['company'] = trim($li['company']);
		$data = array();
		$data['uid'] = $li['uid'];
		$data['uniacid'] = $li['uniacid'];
		$data['time'] = time();
		$data['openid'] = $fans['openid'];
		$data['acid'] = $_W['acid'];
		
		$sql = "SELECT * FROM ".tablename('meepo_bbs_o2o_user')." WHERE uid = :uid";
		$params = array(':uid'=>$li['uid']);
		$o2o = pdo_fetch($sql,$params);
		
		if(!empty($o2o)){
			pdo_update('meepo_bbs_o2o_user',$data,array('id'=>$o2o['id']));
		}else{
			pdo_insert('meepo_bbs_o2o_user',$data);
		}
	}
	
}
die('111'); */
if($_W['ispost']){
	$data = array();
	$data['realname'] = trim($_GPC['realname']);
	$data['mobile'] = $_GPC['mobile'];
	$data['address'] = trim($_GPC['address']);
	$data['company'] = trim($_GPC['company']);
	pdo_update('mc_members',$data,array('uniacid'=>$_W['uniacid'],'uid'=>$uid));
	$data = array();
	$data['uid'] = $uid;
	$data['uniacid'] = $_W['uniacid'];
	$data['time'] = time();
	$data['openid'] = $_W['openid'];
	$data['status'] = 0;
	$data['acid'] = $_W['acid'];
	if(!empty($_GPC['password'])){
		$password = trim($_GPC['password']);
		$sql = "SELECT * FROM ".tablename('activity_coupon_password')." WHERE uid != :uid AND password = :password AND uniacid = :uniacid ";
		$params = array(':uid'=>$uid,':password'=>$password,':uniacid'=>$_W['uniacid']);
		$isexit = pdo_fetchcolumn($sql,$params);
	
		if(empty($isexit)){
			pdo_update('activity_coupon_password',array('password'=>$password),array('uid'=>$uid));
		}else{
			message('此密码已有人使用，请更换其他密码！如密码泄露，请及时更改！！',referer(),error);
		}
	}
	if(!empty($o2o)){
		pdo_update('meepo_bbs_o2o_user',$data,array('id'=>$o2o['id']));
		message('数据更新成功，如密码泄露，请及时更改！！',referer(),success);
		
	}else{
		pdo_insert('meepo_bbs_o2o_user',$data);
		message('提交成功，请耐心等待审核！',$this->createMobileUrl('home'),success);
	}
	
}
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
include $this->template($tempalte.'/templates/home/profile');
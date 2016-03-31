<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->model('activity');
load()->model('mc');
checkauth();

$uid = intval($_GPC['uid']);
$id = intval($_GPC['id']);
$type = trim($_GPC['type']);
$recid = intval($_GPC['recid']);

if(empty($uid)){
	message('会员不存在或已删除(uid)');
}

if(empty($id)){
	message('卡券不存在或已删除(id)');
}

if(empty($recid)){
	message('卡券不存在或已删除(recid)');
}
if(empty($type)){
	message('卡券类型不存在或已删除(type)');
}

$mid = $_W['member']['uid'];

$sql = "SELECT * FROM ".tablename('meepo_bbs_o2o_user')." WHERE `uid` = :uid AND `status` = :status ";
$params = array(':uid'=>$mid,'status'=>1);
$oto_user = pdo_fetch($sql,$params);
if(empty($oto_user)){
	message('对不起，您没有核销权限！',$this->createMobileUrl('close'),error);
}
$sql = "SELECT password FROM ".tablename('activity_coupon_password')." WHERE uid = :uid AND uniacid = :uniacid";
$params = array(':uid'=>$mid,':uniacid'=>$_W['uniacid']);
$password = pdo_fetchcolumn($sql,$params);

if(empty($password)){
	message('对不起，您没有核销权限！',$this->createMobileUrl('close'),error);
}
//clerk
$sql = 'SELECT * FROM ' . tablename('activity_coupon_password') . " WHERE `uniacid` = :uniacid AND `password` = :password";
$clerk = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':password' => $password));

$clerk['user'] = mc_fetch($clerk['uid']);

$other = array();
$other['clerkname'] = $clerk['user']['realname'];
$other['clerkcompany'] = $clerk['user']['company'];
$other['clerkmobile'] = $clerk['user']['mobile'];


if($type == 'coupon'){
	$status = activity_coupon_use1($uid, $id, $clerk['name'],$recid,'meepo_bbs');
	$coupon = activity_coupon_info($id, $_W['uniacid']);
	$other['clerktitle'] = $coupon['title'];
	$other['clerkmoney'] = $coupon['discount'];
	$other['clerksn'] = $coupon['couponsn'];
	if (!is_error($status)) {
		//插入核销记录
		$data = array();
		$data['uid'] = $clerk['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['type'] = $type;
		$data['time'] = time();
		$data['cid'] = $id;
		pdo_insert('meepo_bbs_o2o_user_log',$data);
		if($_W['account']['level']>=4){
			send_template($uid,'mobile_use_coupon',$other);
		}
		message('折扣券核销成功成功！核销人：'.$clerk['name'], '', 'success');
	} else {
		message($status['message'], '', 'error');
	}
}

if($type == 'token'){
	$status = activity_token_use1($uid, $id, $clerk['name'],$recid,'meepo_bbs');
	$coupon = activity_token_info($id, $_W['uniacid']);
	$other['clerktitle'] = $coupon['title'];
	$other['clerkmoney'] = $coupon['discount'];
	$other['clerksn'] = $coupon['couponsn'];
	if (!is_error($status)) {
		$data = array();
		$data['uid'] = $clerk['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['type'] = $type;
		$data['time'] = time();
		$data['cid'] = $id;
		pdo_insert('meepo_bbs_o2o_user_log',$data);
		
		send_template($uid,'mobile_use_token',$other);
		message('代金券使用成功！核销人：'.$clerk['name'],'', 'success');
	} else {
		message($status['message'], '', 'error');
	}
}

message('开发中');
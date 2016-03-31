<?php /*折翼天使资源社区 www.zheyitianshi.com*/
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
load()->model('mc');
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';

//init
checkauth();
load()->model('activity');
load()->model('mc');
$creditnames = array();
$unisettings = uni_setting($uniacid, array('creditnames'));
if (!empty($unisettings) && !empty($unisettings['creditnames'])) {
	foreach ($unisettings['creditnames'] as $key=>$credit) {
		$creditnames[$key] = $credit['title'];
	}
}


$sql = 'SELECT `status` FROM ' . tablename('mc_card') . " WHERE `uniacid` = :uniacid";
$cardstatus = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));


$dos = array('display', 'post', 'mine', 'use');
$do = in_array($_GPC['op'], $dos) ? $_GPC['op'] : 'display';
if($do == 'display') {
	$is_card = pdo_fetchall("SELECT name FROM ".tablename('modules')." WHERE issystem = 0 AND iscard = 0", array(), 'name');
	$condition = ' AND use_module = 0 ';
	if(!empty($is_card)) {
		$is_card_str = "'" . implode("','", array_keys($is_card)) . "'";
		$condition = " AND (use_module = 0 OR (use_module = 1 AND couponid IN (SELECT couponid FROM ".tablename('activity_coupon_modules')." WHERE uniacid = {$_W['uniacid']} AND module IN ({$is_card_str}))))";
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('activity_coupon'). " WHERE uniacid = :uniacid AND type = :type AND endtime > :endtime {$condition}" , array(':uniacid' => $_W['uniacid'], ':type' => 2, ':endtime' => TIMESTAMP));
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$lists = pdo_fetchall('SELECT couponid,title,thumb,type,credittype,credit,endtime,description FROM ' . tablename('activity_coupon') . " WHERE uniacid = :uniacid AND type = :type AND endtime > :endtime {$condition} ORDER BY endtime ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $_W['uniacid'], ':type' => 2, ':endtime' => TIMESTAMP));
	$pager = pagination($total, $pindex, $psize);
}
if($do == 'post') {
	$id = intval($_GPC['id']);
	$result = array('errorcode' => -1, 'errormsg' => '');
	$token = activity_token_info($id, $_W['uniacid']);
	if(empty($token)){
		$result['errormsg'] = '没有指定的礼品兑换';
		message($result, '', 'ajax');
	}
	$credit = mc_credit_fetch($_W['member']['uid'], array($token['credittype']));
	if ($credit[$token['credittype']] < $token['credit']) {
		$result['errormsg'] = "您的 {$creditnames[$token['credittype']]} 数量不够,无法兑换.";
		message($result, '', 'ajax');
	}

	$ret = activity_token_grant($_W['member']['uid'], $id, '', '用户使用' . $token['credit'] . $creditnames[$token['credittype']] . '兑换');
	if(is_error($ret)) {
		$result['errormsg'] = $ret['message'];
		message($result, '', 'ajax');
	}
	//代金券和折扣券的兑换记录,使用记录在表(activity_coupon_record)中
	mc_credit_update($_W['member']['uid'], $token['credittype'], -1 * $token['credit'], array($_W['member']['uid'], '礼品兑换:' . $token['title'] . ' 消耗 ' . $creditnames[$token['credittype']] . ':' . $token['credit']));
	$result['errorcode'] = 0;
	$result['errormsg'] = "兑换成功,您消费了 {$token['credit']} {$creditnames[$token['credittype']]}";
	message($result, '', 'ajax');
}
if($do == 'mine') {
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$params = array(':uid' => $_W['member']['uid']);
	$filter['used'] = '1';
	$type = 1;
	if($_GPC['type'] == 'used') {
		$filter['used'] = '2';
		$type = 2;
	}
	$coupon = activity_token_owned($_W['member']['uid'], $filter, $pindex, $psize);
	$data = $coupon['data'];
	$total = $coupon['total'];
	unset($coupon);
	$pager = pagination($total , $pindex, $psize);
}
if($do == 'use') {
	$id = intval($_GPC['id']);
	$data = activity_token_owned1($_W['member']['uid'], array('couponid' => $id, 'used' => 1));
	$data = $data['data'][$id];
	if($_W['ispost']) {
		load()->model('user');
		$password = $_GPC['password'];
		$sql = 'SELECT * FROM ' . tablename('activity_coupon_password') . " WHERE `uniacid` = :uniacid AND `password` = :password";
		$clerk = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':password' => $password));
		
		
		if(!empty($clerk)) {
			
			$clerk['user'] = mc_fetch($clerk['uid']);
			$coupon = activity_token_info($id, $_W['uniacid']);
			$other = array();
			$other['clerkname'] = $clerk['user']['realname'];
			$other['clerkcompany'] = $clerk['user']['company'];
			$other['clerkmobile'] = $clerk['user']['mobile'];
			$other['clerktitle'] = $coupon['title'];
			$other['clerkmoney'] = $coupon['discount'];
			$other['clerksn'] = $coupon['couponsn'];
			
			$status = activity_token_use1($_W['member']['uid'], $id, $clerk['name'],$data['recid'],'meepo_bbs');
			if (!is_error($status)) {
				$data = array();
				$data['uid'] = $clerk['uid'];
				$data['uniacid'] = $_W['uniacid'];
				$data['type'] = 'coupon';
				$data['time'] = time();
				$data['cid'] = $id;
				
				send_template($_W['openid'],'mobile_use_token',$other);
				message('代金券使用成功！核销人：'.$clerk['name'], $this->createMobileUrl('activity_token',array('op'=>'mine','type' =>'used')), 'ajax');
			} else {
				message($status['message'], $this->createMobileUrl('activity_token',array('op'=>'mine','type' => $_GPC['type'])), 'ajax');
			}
		}
		message('密码错误！', referer(), 'ajax');
	}
}
include $this->template($tempalte.'/templates/activity/token');


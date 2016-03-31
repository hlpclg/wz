<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('app');
$dos = array('display', 'credits', 'address', 'card', 'mycard', 'record', 'mobile', 'email', 'barcode', 'qrcode', 'consume', 'card_qrcode', 'addressadd');
$do = in_array($do, $dos) ? $do : 'display';
load()->func('tpl');
load()->model('user');


if ($do == 'credits') {
	$where = '';
	$params = array(':uid' => $_W['member']['uid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 15;
	
	if (empty($starttime) || empty($endtime)) {
		$starttime =  strtotime('-1 month');
		$endtime = time();
	}
	if ($_GPC['time']) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$where = ' AND `createtime` >= :starttime AND `createtime` < :endtime';
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	
	$sql = 'SELECT `realname`, `avatar` FROM ' . tablename('mc_members') . " WHERE `uid` = :uid";
	$user = pdo_fetch($sql, array(':uid' => $_W['member']['uid']));
	if ($_GPC['credittype']) {
		
		if ($_GPC['type'] == 'order') {
			$sql = 'SELECT * FROM ' . tablename('mc_credits_recharge') . " WHERE `uid` = :uid $where LIMIT " . ($pindex - 1) * $psize. ',' . $psize;
			$orders = pdo_fetchall($sql, $params);
			foreach ($orders as &$value) {
				$value['createtime'] = date('Y-m-d', $value['createtime']);
				$value['fee'] = number_format($value['fee'], 2);
				if ($value['status'] == 1) {
					$orderspay += $value['fee'];
				}
				unset($value);
			}
			
			$ordersql = 'SELECT COUNT(*) FROM ' .tablename('mc_credits_recharge') . "WHERE `uid` = :uid {$where}";
			$total = pdo_fetchcolumn($ordersql, $params);
			$orderpager = pagination($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
			template('mc/bond');
			exit();
		}
		$where .= " AND `credittype` = '{$_GPC['credittype']}'";
	}
	
	
	$sql = 'SELECT `num` FROM ' . tablename('mc_credits_record') . " WHERE `uid` = :uid $where";
	$nums = pdo_fetchall($sql, $params);
	$pay = $income = 0;
	foreach ($nums as $value) {
		if ($value['num'] > 0) {
			$income += $value['num'];
		} else {
			$pay += abs($value['num']);
		}
	}
	$pay = number_format($pay, 2);
	$income = number_format($income, 2);
	
	$sql = 'SELECT * FROM ' . tablename('mc_credits_record') . " WHERE `uid` = :uid {$where} ORDER BY `createtime` DESC LIMIT " . ($pindex - 1) * $psize.','. $psize;
	$data = pdo_fetchall($sql, $params);
	foreach ($data as $key=>$value) {
		$data[$key]['credittype'] = $creditnames[$data[$key]['credittype']]['title'];
		$data[$key]['createtime'] = date('Y-m-d H:i', $data[$key]['createtime']);
		$data[$key]['num'] = number_format($value['num'], 2);
	}
	
	$pagesql = 'SELECT COUNT(*) FROM ' .tablename('mc_credits_record') . "WHERE `uid` = :uid {$where}";
	$total = pdo_fetchcolumn($pagesql, $params);
	$pager = pagination($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
}


if ($do == 'address') {
	$where = ' WHERE 1';
	$params = array(':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
	if (!empty($_GPC['addid'])) {
		$where .= ' AND `id` = :id';
		$params[':id'] = intval($_GPC['addid']);
	}
	$where .= ' AND `uniacid` = :uniacid AND `uid` = :uid';
	$sql = 'SELECT * FROM ' . tablename('mc_member_address') . $where;
	if (empty($params[':id'])) {
		$psize = 10;
		$pindex = max(1, intval($_GPC['page']));
		$sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$addresses = pdo_fetchall($sql, $params);
		$sql = 'SELECT COUNT(*) FROM ' . tablename('mc_member_address') . $where;
		$total = pdo_fetchcolumn($sql, $params);
		$pager = pagination($total, $pindex, $psize);
	} else {
		$address = pdo_fetch($sql, $params);
	}
}

if ($do == 'addressadd') {
	
	if (checksubmit('submit')) {
		$address = $_GPC['address'];
		if (empty($address['username'])) {
			message('请输入您的姓名', referer(), 'error');
		}
		if (empty($address['mobile'])) {
			message('请输入您的手机号', referer(), 'error');
		}
		if (empty($address['zipcode'])) {
			message('请输入您的邮政编码', referer(), 'error');
		}
		if (empty($address['province'])) {
			message('请输入您的所在省', referer(), 'error');
		}
		if (empty($address['city'])) {
			message('请输入您的所在市', referer(), 'error');
		}
		if (empty($address['district'])) {
			message('请输入您的所在区', referer(), 'error');
		}
		if (empty($address['address'])) {
			message('请输入您的详细地址', referer(), 'error');
		}
		$address['uniacid'] = $_W['uniacid'];
		$address['uid'] = $_W['fans']['uid'];
		
		if (!empty($_GPC['addid'])) {
			if (pdo_update('mc_member_address', $address, array('id' => intval($_GPC['addid']), 'uid' => $_W['fans']['uid']))) {
				message('修改收货地址成功', url('mc/bond/address'), 'success');
			} else {
				message('修改收货地址失败，请稍后重试', url('mc/bond/address'), 'error');
			}
		}
		
		if (pdo_insert('mc_member_address', $address)) {
			message('地址添加成功', url('mc/bond/address'), 'success');
		}
	}
}


if ($do == 'card') {
	$mcard = pdo_fetch('SELECT * FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
	if(!empty($mcard)) {
		header('Location:' . url('mc/bond/mycard'));
	}
	
	$sql = 'SELECT * FROM ' . tablename('mc_card') . "WHERE `uniacid` = :uniacid AND `status` = '1'";
	$setting = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));

	if (!empty($setting)) {
		$setting['color'] = iunserializer($setting['color']);
		$setting['background'] = iunserializer($setting['background']);
		$setting['fields'] = iunserializer($setting['fields']);
		$setting['grant'] = iunserializer($setting['grant']);
		$coupon_id = intval($setting['grant']['coupon']);
		if($coupon_id > 0) {
			$coupon = pdo_fetch('SELECT couponid,title,type FROM ' . tablename('activity_coupon') . ' WHERE uniacid = :uniacid AND couponid = :couponid', array(':uniacid' => $_W['uniacid'], ':couponid' => $coupon_id));
		}
	} else {
		message('公众号尚未开启会员卡功能', url('mc'), 'error');
	}

	if(!empty($setting['fields'])) {
		$fields = array('email');
		foreach($setting['fields'] as $li) {
			if($li['bind'] == 'birth') {
				$fields[] = 'birthyear';
				$fields[] = 'birthmonth';
				$fields[] = 'birthday';
			} elseif($li['bind'] == 'reside') {
				$fields[] = 'resideprovince';
				$fields[] = 'residecity';
				$fields[] = 'residedist';
			} else {
				$fields[] = $li['bind'];
			}
		}
		$member_info = mc_fetch($_W['member']['uid'], $fields);
		$reregister = 0;
		if(strlen($member_info['email']) == 39 && strexists($member_info['email'], '@012wz.com')) {
			$member_info['email'] = '';
			$reregister = 1;
		}
	}
	if (checksubmit('submit')) {
		$data = array();
		if (!empty($setting['fields'])) {
			foreach ($setting['fields'] as $row) {
				if (!empty($row['require']) && empty($_GPC[$row['bind']])) {
					message('请输入'.$row['title'].'！', referer(), 'info');
				}
				if(!empty($row['require']) && $row['bind'] == 'birth') {
					if (empty($_GPC['birth']['year']) || empty($_GPC['birth']['month']) || empty($_GPC['birth']['day'])) {
						message('请输入完整的出生日期！', referer(), 'info');
					}
				}
				if(!empty($row['require']) && $row['bind'] == 'reside') {
					if (empty($_GPC['reside']['province']) || empty($_GPC['reside']['city']) || empty($_GPC['reside']['district'])) {
						message('请输入完整的居住地！', referer(), 'info');
					}
				}
				$data[$row['bind']] = $_GPC[$row['bind']];
			}
		}
		$check = mc_check($data);
		if(is_error($check)) {
			message($check['message'], '', 'error');
		}
		
		$sql = 'SELECT COUNT(*)  FROM ' . tablename('mc_card_members') . " WHERE `uid` = :uid AND `cid` = :cid AND uniacid = :uniacid";
		$count = pdo_fetchcolumn($sql, array(':uid' => $_W['member']['uid'], ':cid' => $_GPC['cardid'], ':uniacid' => $_W['uniacid']));
		if ($count >= 1) {
			message('抱歉,您已经领取过该会员卡.', referer(), 'error');
		}
		if($setting['format_type'] == 1) {
			$cardsn = $data['mobile'];
		} else {
			
			$cardsn = $_GPC['format'];
			preg_match_all('/(\*+)/', $_GPC['format'], $matchs);
			if (!empty($matchs)) {
				foreach ($matchs[1] as $row) {
					$cardsn = str_replace($row, random(strlen($row), 1), $cardsn);
				}
			}
			preg_match('/(\#+)/', $_GPC['format'], $matchs);
			$length = strlen($matchs[1]);
			$pos = strpos($_GPC['format'], '#');
			$cardsn = str_replace($matchs[1], str_pad($_GPC['snpos']++, $length - strlen($number), '0', STR_PAD_LEFT), $cardsn);
			pdo_update('mc_card', array('snpos' => $_GPC['snpos']), array('uniacid' => $_W['uniacid'], 'id' => $_GPC['cardid']));
		}

		$record = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'uid' => $_W['member']['uid'],
			'cid' => $_GPC['cardid'],
			'cardsn' => $cardsn,
			'status' => '1',
			'createtime' => TIMESTAMP,
			'endtime' => TIMESTAMP
		);
		if(pdo_insert('mc_card_members', $record)) {
			if(!empty($data)){
				mc_update($_W['member']['uid'], $data);
			}
						$notice = '';
			if($setting['grant']['credit1'] > 0) {
				$log = array(
					$_W['member']['uid'],
					"领取会员卡，赠送{$setting['grant']['credit1']}积分"
				);
				mc_credit_update($_W['member']['uid'], 'credit1', $setting['grant']['credit1'], $log);
				$notice .= "赠送【{$setting['grant']['credit1']}】积分";
			}
			if($setting['grant']['credit2'] > 0) {
				$log = array(
					$_W['member']['uid'],
					"领取会员卡，赠送{$setting['credit2']['credit1']}余额"
				);
				mc_credit_update($_W['member']['uid'], 'credit2', $setting['grant']['credit2'], $log);
				$notice .= ",赠送【{$setting['grant']['credit2']}】余额";
			}
			if($setting['grant']['coupon'] > 0 && !empty($coupon)) {
				if($coupon['type'] == 1) {
					$status = activity_coupon_grant($_W['member']['uid'], $coupon['couponid'], 'card', '领取会员卡，赠送优惠券');
				} else {
					$status = activity_token_grant($_W['member']['uid'], $coupon['couponid'], 'card', '领取会员卡，赠送优惠券');
				}
				if(!is_error($status)) {
					$notice .= ",赠送【{$coupon['title']}】优惠券";
				}
			}

			$time = date('Y-m-d H:i');
			$url = murl('mc/bond/mycard/', array(), true, true);
			$info = "【{$_W['account']['name']}】- 领取会员卡通知\n";
			$info .= "您在{$time}成功领取会员卡，{$notice}。\n\n";
			$info .= "<a href='{$url}'>点击查看详情</a>";
			$status = mc_notice_custom_text($info, $_W['openid']);
			message("领取会员卡成功<br>{$notice}", url('mc/bond/mycard'), 'success');
		} else {
			message('领取会员卡失败.', referer(), 'error');
		}
	}
}


if ($do == 'mycard') {
	$mcard = pdo_fetch('SELECT * FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
	if(empty($mcard)) {
		header('Location:' . url('mc/bond/card'));
	}
	if(empty($mcard['openid']) && !empty($_W['openid'])) {
		pdo_update('mc_card_members', array('openid' => $_W['openid']), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
	}
	if (!empty($mcard['status'])) {
		$setting = pdo_fetch('SELECT * FROM ' . tablename('mc_card') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
		if(!empty($setting)) {
			$setting['color'] = iunserializer($setting['color']);
			$setting['background'] = iunserializer($setting['background']);
			if(!empty($setting['discount']) && $setting['discount_type'] > 0) {
				$setting['discount'] = iunserializer($setting['discount']);
			}
			if(!empty($setting['nums']) && $setting['nums_status'] == 1) {
				$setting['nums'] = iunserializer($setting['nums']);
			}
			if(!empty($setting['times']) && $setting['times_status'] == 1) {
				$setting['times'] = iunserializer($setting['times']);
			}
			$unisetting = uni_setting($_W['uniacid'], array('recharge'));
			$recharge =  $unisetting['recharge'];
		}
	}
}


if($do == 'consume') {
	load()->model('card');
	$setting = card_setting();
		$stores = pdo_fetchall('SELECT id,business_name FROM ' . tablename('activity_stores') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']), 'id');

	if(checksubmit()) {
		$credit = floatval($_GPC['credit']);
		$discount_credit = $credit;
		$store_id = intval($_GPC['store_id']);
		$store_str = (!$store_id || empty($stores[$store_id])) ? '未知' : $stores[$store_id]['business_name'];
		if(!$credit) {
			message('请输入消费金额', referer(), 'error');
		}
				if($setting['discount_type'] > 0 && !empty($setting['discount'])) {
			$discount = $setting['discount'][$_W['member']['groupid']];
			if(!empty($discount['condition']) && !empty($discount['discount']) && $credit >= $discount['condition']) {
				if($setting['discount_type'] == 1) {
					$discount_credit = $credit - $discount['discount'];
					$discount_str = "，该会员属于【{$_W['member']['groupname']}】，可享受【满{$discount['condition']}元减{$discount['discount']}元】，最终支付【{$discount_credit}】元";
				} else {
					$rate = $discount['discount'] * 10;
					$discount_credit = $credit * $rate;
					$discount_str = "，该会员属于【{$_W['member']['groupname']}】，可享受【满{$discount['condition']}元打{$rate}折】，最终支付【{$discount_credit}】元";
				}
				if($discount_credit < 0) {
					$discount_credit = 0;
				}
			}
		}

		if($_W['member']['credit2'] < $discount_credit) {
			message('余额不足', referer(), 'error');
		}
				if($setting['grant_rate'] > 0) {
			$credit1 = $discount_credit * $setting['grant_rate'];
			$log_credit1 = array(
				$_W['member']['uid'],
				"使用会员卡消费【{$discount_credit}】元,消费返积分比率：【1:{$setting['grant_rate']}】,共赠送积分{$credit1}"
			);
			mc_credit_update($_W['member']['uid'], 'credit1', $credit1, $log_credit1);
			$discount_str .= "，消费返积分比率：【1:{$setting['grant_rate']}】,共赠送积分{$credit1}";
		}
		$log_credit2 = array(
			$_W['member']['uid'],
			"使用会员卡消费【{$credit}】元 {$discount_str},消费门店：{$store_str}",
			'card',
			0,
			$store_id
		);
		mc_credit_update($_W['member']['uid'], 'credit2', -$discount_credit, $log_credit2);
		mc_notice_credit2($_W['openid'], $_W['member']['uid'], -$discount_credit, $credit1, $store_str);
		message("消费成功，共扣除余额{$discount_credit}元，赠送{$credit1}积分", url('mc/bond/mycard'), 'success');
	}

	if($setting['discount_type'] != 0 && !empty($setting['discount'])) {
		$discount = $setting['discount'];
		if(!empty($discount[$_W['member']['groupid']])) {
			$tips = "您当前会员组 {$_W['member']['groupname']} ,可享受满 {$discount[$_W['member']['groupid']]['condition']}元";
			if($setting['discount_type'] == 2) {
				$rate = $discount[$_W['member']['groupid']]['discount'] * 10;
				$tips .= "打{$rate}折";
			} else {
				$tips .= "减{$discount[$_W['member']['groupid']]['discount']}元";
			}
			$mine_discount = $discount[$_W['member']['groupid']];
		}
	}
	$url = $_W['siteroot'] . 'app' . ltrim(murl('clerk/card', array('uid' => $_W['member']['uid'])), '.');
	template('mc/consume');
	exit();
}


if($do == 'card_qrcode') {
	require_once('../framework/library/qrcode/phpqrcode.php');
	$errorCorrectionLevel = "L";
	$matrixPointSize = "8";
	$url = $_W['siteroot'] . 'app' . ltrim(murl('clerk/card', array('uid' => $_W['member']['uid'])), '.');
	QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
	exit();
}


if ($do == 'barcode') {
	$cardsn = $_W['member']['uid'];
	$barcode_path = '../framework/library/barcode/';
		require_once($barcode_path . 'class/BCGFontFile.php');
	require_once($barcode_path . 'class/BCGColor.php');
	require_once($barcode_path . 'class/BCGDrawing.php');
	require_once($barcode_path . 'class/BCGcode39.barcode.php');
	$color_black = new BCGColor(0, 0, 0);
	$color_white = new BCGColor(255, 255, 255);
	
	$drawException = null;
	try {
		$code = new BCGcode39();
		$code->setScale(2);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->parse($cardsn);
	} catch(Exception $exception) {
		$drawException = $exception;
	}
	
	$drawing = new BCGDrawing('', $color_white);
	if($drawException) {
		$drawing->drawException($drawException);
	} else {
		$drawing->setBarcode($code);
		$drawing->draw();
	}
	header('Content-Type: image/png');
	header('Content-Disposition: inline; filename="barcode.png"');
	$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
}


if ($do == 'qrcode') {
	require_once('../framework/library/qrcode/phpqrcode.php');
	$errorCorrectionLevel = "L";
	$matrixPointSize = "8";
	$cardsn = $_W['member']['uid'];
	QRcode::png($cardsn, false, $errorCorrectionLevel, $matrixPointSize);
}

if($do == 'record') {
	$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']), array('nums_text', 'times_text'));
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
	$type = trim($_GPC['type']);
	$where = ' WHERE uniacid = :uniacid AND uid = :uid AND type = :type';
	$params = array(
		':uniacid' => $_W['uniacid'],
		':uid' => $_W['member']['uid'],
		':type' => $type,
	);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_record') . $where, $params);
	$limit = ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ', ' . $psize;
	$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_record') . $where . $limit, $params);
	$pager = pagination($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
}

if($do == 'mobile') {
	$profile = mc_fetch($_W['member']['uid'], array('mobile'));
	$mobile_exist = empty($profile['mobile']) ? 0 : 1;
	if(checksubmit('submit')) {
		if($mobile_exist == 1) {
			$oldmobile = trim($_GPC['oldmobile']) ? trim($_GPC['oldmobile']) : message('请填写原手机号');
			$password = trim($_GPC['password']) ? trim($_GPC['password']) : message('请填写密码');
			$mobile = trim($_GPC['mobile']) ? trim($_GPC['mobile']) : message('请填写新手机号');
			if(!preg_match(REGULAR_MOBILE, $mobile)) {
				message('新手机号格式有误', '', 'error');
			}
			$info = pdo_fetch('SELECT uid, password, salt FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid AND mobile = :mobile AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':mobile' => $oldmobile, ':uid' => $_W['member']['uid']));
			if(!empty($info)) {
				if($info['password'] == md5($password . $info['salt'] . $_W['config']['setting']['authkey'])) {
					pdo_update('mc_members', array('mobile' => $mobile), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
					message('修改手机号成功', url('mc/home'), 'success');
				} else {
					message('密码输入错误', '', 'error');
				}
			} else {
				message('原手机号输入错误', '', 'error');
			}
		} else {
			$mobile = trim($_GPC['mobile']) ? trim($_GPC['mobile']) : message('请填写手机号');
			if(!preg_match(REGULAR_MOBILE, $mobile)) {
				message('手机号格式有误', '', 'error');
			}
			$password = trim($_GPC['password']);
			if(empty($password) || strlen($password) < 6) {
				message('密码不能少于6位');
			}
			$repassword = trim($_GPC['repassword']);
			if($password != $repassword) {
				message('两次输入密码不一致');
			}
			$is_exist = pdo_fetch('SELECT uid FROM ' . tablename('mc_members') . ' WHERE uniacid = :uniacid AND mobile = :mobile AND uid != :uid', array(':uniacid' => $_W['uniacid'], ':mobile' => $mobile, ':uid' => $_W['member']['uid']));
			if(!empty($is_exist)) {
				message('该手机号已被绑定,换个手机号试试', '', 'error');
			}
			$salt = random(8);
			$password = md5($password . $salt . $_W['config']['setting']['authkey']);
			pdo_update('mc_members', array('mobile' => $mobile, 'salt' => $salt, 'password' => $password), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
			message('修改手机号成功', url('mc/home'), 'success');
		}
	}
}

if($do == 'email') {
	$profile = mc_fetch($_W['member']['uid'], array('uid', 'email', 'salt'));
	if ($_W['member']['email'] == md5($_W['openid']).'@012wz.com') {
		$reregister = true;
	}
	if(checksubmit('submit')) {
		$type = intval($_GPC['type']);
		$data = array();
		if ($type == 1) {
			if ($reregister) {
				$data['email'] = $_GPC['email'];
				$emailexists = pdo_fetch("SELECT uid FROM ".tablename('mc_members')." WHERE email = :email AND uniacid = :uniacid AND uid != :uid ", array(':email' => $data['email'], ':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
				if (!empty($emailexists['uid'])) {
					message('抱歉，该E-Mail地址已经被注册，请更换。', '', 'error');
				}
			}
			if (empty($_GPC['password'])) {
				message('请输入您的密码', '', 'error');
			}
			$data['password'] = md5($_GPC['password'] . $profile['salt'] . $_W['config']['setting']['authkey']);
			pdo_update('mc_members', $data, array(
				'uid' => $profile['uid']
			));
			message('修改帐号密码成功！', url('mc/home'), 'success');
		} else {
			$data['username'] = $_GPC['username'];
			$data['password'] = $_GPC['oldpassword'];
			if (empty($data['username']) || empty($data['password'])) {
				message('抱歉，用户名或是密码没有填写。', '', 'error');
			}

			$pars_tmp[':uniacid'] = $_W['uniacid'];
			if(preg_match(REGULAR_MOBILE, $data['username'])) {
				$sql_tmp .= ' AND `mobile`=:mobile';
				$pars_tmp[':mobile'] = $data['username'];
			} else {
				$sql_tmp .= ' AND `email`=:email';
				$pars_tmp[':email'] = $data['username'];
			}
			$member = pdo_fetch("SELECT `uid`,`salt`,`password` FROM " . tablename('mc_members') . " WHERE `uniacid`=:uniacid " . $sql_tmp, $pars_tmp);
			if (empty($member)) {
				message('抱歉，用户不存或是已经被删除', '', 'error');
			}
			
			$hash = md5($data['password'] . $member['salt'] . $_W['config']['setting']['authkey']);
			if($member['password'] != $hash) {
				message('抱歉，您输入的密码有误', '', 'error');
			}
			
			pdo_update('mc_mapping_fans', array('uid' => $member['uid']), array(
				'acid' => $_W['acid'],
				'openid' => $_W['openid'],
			));

						$member_old = mc_fetch($_W['member']['uid']);
			$member_new = mc_fetch($member['uid']);
			if(!empty($member_old) && !empty($member_new)) {
				$ignore = array('email', 'password', 'uid', 'uniacid', 'salt', 'credit1', 'credit2', 'credit3','credit4','credit5');
				$profile_update = array();
				foreach($member_old as $key => $value) {
					if(!in_array($key, $ignore)) {
						if(empty($member_new[$key]) && !empty($member_old[$key])) {
							$profile_update[$key] = $member_old[$key];
						}
					}
				}
				$profile_update['credit1'] = $member_old['credit1'] + $member_new['credit1'];
				$profile_update['credit2'] = $member_old['credit2'] + $member_new['credit2'];
				$profile_update['credit3'] = $member_old['credit3'] + $member_new['credit3'];
				$profile_update['credit4'] = $member_old['credit4'] + $member_new['credit4'];
				$profile_update['credit5'] = $member_old['credit5'] + $member_new['credit5'];
				pdo_update('mc_members', $profile_update, array('uid' => $member['uid'], 'uniacid' => $_W['uniacid']));
				pdo_delete('mc_members', array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']));
								pdo_update('activity_coupon_record', array('uid' => $member['uid']), array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']));
				pdo_update('activity_exchange_trades', array('uid' => $member['uid']), array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']));
				pdo_update('activity_exchange_trades_shipping', array('uid' => $member['uid']), array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']));
								pdo_update('mc_credits_record', array('uid' => $member['uid']), array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']));
				pdo_update('mc_card_members', array('uid' => $member['uid']), array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid']));
			}
			message('重新绑定帐号成功！', url('mc/home'), 'success');
		}
	}
}
template('mc/bond');
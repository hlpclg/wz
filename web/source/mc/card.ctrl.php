<?php
/**
 * [WEIZAN System] Copyright (c) 2015 012WZ.COM
 * WeiZan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('mc_card');
$_W['page']['title'] = '会员卡管理 - 会员中心';

$dos = array('display', 'manage', 'delete', 'coupon', 'submit', 'modal', 'record', 'notice', 'care', 'credit', 'recommend', 'stat');
$do = in_array($do, $dos) ? $do : 'display';
load()->model('mc');
$setting = pdo_fetch("SELECT * FROM ".tablename('mc_card')." WHERE uniacid = '{$_W['uniacid']}'");
if ($do == 'display') {
	if ($_W['ispost'] && $_W['isajax']) {
		$sql = 'SELECT `uniacid` FROM ' . tablename('mc_card') . " WHERE `uniacid` = :uniacid";
		$status = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));
		if (empty($status)) {
			$open = array('uniacid' => $_W['uniacid']);
			pdo_insert('mc_card', $open);
		}
		$data['status'] = intval($_GPC['status']);
		if (false === pdo_update('mc_card', $data, array('uniacid' => $_W['uniacid']))) {
			exit('error');
		}
		exit('success');
	}
	$groups = $_W['account']['groups'];
	$fields = mc_fields();
	if (!empty($setting)) {
		if(!empty($setting['color'])) {
			$setting['color'] = iunserializer($setting['color']);
		} else {
			$setting['color'] = array();
		}
		$setting['background'] = iunserializer($setting['background']);
		$setting['fields'] = iunserializer($setting['fields']);
		if(!empty($setting['fields'])) {
			foreach($setting['fields'] as $field) {
				$re_fields[] = $field['bind'];
			}
		}
		if(empty($setting['logo'])) {
			$setting['logo'] = 'images/global/card/logo.png';
		}
		if(!empty($setting['discount'])) {
			$setting['discount'] = iunserializer($setting['discount']);
		} else {
			$setting['discount'] = array();
		}
		if(!empty($setting['grant'])) {
			$setting['grant'] = iunserializer($setting['grant']);
			$coupon_id = intval($setting['grant']['coupon']);
			if($coupon_id > 0) {
				$coupon = pdo_fetch('SELECT * FROM ' . tablename('activity_coupon') . ' WHERE uniacid = :uniacid AND couponid = :couponid', array(':uniacid' => $_W['uniacid'], ':couponid' => $coupon_id));
			}
		} else {
			$setting['grant'] = array();
		}

		if(!empty($setting['times'])) {
			$setting['times'] = iunserializer($setting['times']);
		} else {
			$setting['times'] = array(
				array('recharge' => 100, 'time' => 10),
				array('recharge' => 200, 'time' => 20),
				array('recharge' => 300, 'time' => 30)
			);
		}

		if(!empty($setting['nums'])) {
			$setting['nums'] = iunserializer($setting['nums']);
		} else {
			$setting['nums'] = array(
				array('recharge' => 100, 'num' => 30),
				array('recharge' => 200, 'num' => 60),
				array('recharge' => 300, 'num' => 90)
			);
		}
	}
	$uni_setting = uni_setting();
	$recharge = $uni_setting['recharge'];

	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('请输入会员卡名称！');
		}
		if (empty($_GPC['format'])) {
			message('请输入会员卡的卡号生成格式！');
		}

		$data = array(
			'title' => $_GPC['title'],
			'color' => iserializer(array(
				'title' => $_GPC['color-title'],
				'number' => $_GPC['color-number'],
				'name' => $_GPC['color-name'],
				'credit' => $_GPC['color-credit'],
				'rank' => $_GPC['color-rank'],
			)),
			'background' => iserializer(array(
				'background' => $_GPC['background'],
				'image' => $_GPC[$_GPC['background'].'-bg'],
			)),
			'logo' => $_GPC['logo'],
			'format' => $_GPC['format'],
			'description' => trim($_GPC['description']),
			'discount_type' => intval($_GPC['discount_type']),
			'grant_rate' => intval($_GPC['grant_rate']),
			'offset_rate' => intval($_GPC['offset_rate']),
			'offset_max' => intval($_GPC['offset_max']),
		);
				$grant = array(
			'credit1' => intval($_GPC['grant']['credit1']),
			'credit2' => intval($_GPC['grant']['credit2']),
			'coupon' => intval($_GPC['grant']['coupon']),
		);
		$data['grant'] = iserializer($grant);

				$discount = array();
		foreach($groups as $row) {
			$discount[$row['groupid']] = array(
				'condition_1' => intval($_GPC['condition_1'][$row['groupid']]),
				'discount_1' => intval($_GPC['discount_1'][$row['groupid']]),
				'condition_2' => intval($_GPC['condition_2'][$row['groupid']]),
				'discount_2' => intval($_GPC['discount_2'][$row['groupid']]),
			);
		}
		$data['discount'] = iserializer($discount);

		$data['times_status'] = intval($_GPC['times_status']);
		$data['times_text'] = trim($_GPC['times_text']);
		if(!empty($_GPC['times']['recharge'])) {
			$data['times'] = array();
			foreach($_GPC['times']['recharge'] as $key => $val) {
				$val = floatval($val);
				$time = intval($_GPC['times']['time'][$key]);
				if($val <= 0 || $time <= 0) continue;
				$data['times'][$val] = array(
					'recharge' => $val,
					'time' => $time
				);
			}
			$data['times'] = iserializer($data['times']);
		}

		$data['nums_status'] = intval($_GPC['nums_status']);
		$data['nums_text'] = trim($_GPC['nums_text']);
		if(!empty($_GPC['nums']['recharge'])) {
			$data['nums'] = array();
			foreach($_GPC['nums']['recharge'] as $key => $val) {
				$val = floatval($val);
				$num = intval($_GPC['nums']['num'][$key]);
				if($val <= 0 || $num <= 0) continue;
				$data['nums'][$val] = array(
					'recharge' => $val,
					'num' => $num
				);
			}
			$data['nums'] = iserializer($data['nums']);
		}

		$data['fields'][] = array('title' => '姓名', 'require' => 1, 'bind' => 'realname');
		$data['fields'][] = array('title' => '手机号', 'require' => 1, 'bind' => 'mobile');
		if (!empty($_GPC['fields'])) {
			foreach ($_GPC['fields']['title'] as $index => $row) {
				if (empty($_GPC['fields']['title'][$index]) || $_GPC['fields']['bind'][$index] == 'mobile' || $_GPC['fields']['bind'][$index] == 'realname') {
					continue;
				}
				$data['fields'][] = array(
					'title' => $_GPC['fields']['title'][$index],
					'require' => intval($_GPC['fields']['require'][$index]),
					'bind' => $_GPC['fields']['bind'][$index],
				);
			}
		}

		$data['fields'] = iserializer($data['fields']);
		if (!empty($setting)) {
			pdo_update('mc_card', $data, array('uniacid' => $_W['uniacid']));
		} else {
			$data['uniacid'] = $_W['uniacid'];
			pdo_insert('mc_card', $data);
		}
		message('会员卡设置成功！', url('mc/card/display'), 'success');
	}
	template('mc/card');
}

if ($do == 'manage') {
	if ($_W['ispost']) {
		$status = array('status' => intval($_GPC['status']));
		if (false === pdo_update('mc_card_members', $status, array('uniacid' => $_W['uniacid'], 'id' => $_GPC['cardid']))) {
			exit('error');
		}
		exit('success');
	}
	if ($setting['status'] == 0) {
		message('会员卡功能未开启', url('mc/card'), 'error');
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$param = array(':uniacid' => $_W['uniacid']);
	$cardsn = trim($_GPC['cardsn']);
	if(!empty($cardsn)) {
		$where .= ' AND a.cardsn LIKE :cardsn';
		$param[':cardsn'] = "%{$cardsn}%";
	}
	$status = isset($_GPC['status']) ? intval($_GPC['status']) : -1;
	if ($status >= 0) {
		$where .= " AND a.status = :status";
		$param[':status'] = $status;
	}
	$birth = isset($_GPC['birth']) ? intval($_GPC['birth']) : -1;
	if ($birth >= 0) {
		$time = strtotime(date('Y-m-d')) + $birth * 86400;
		$month = date('m', $time);
		$day = date('d', $time);
		$where .= " AND (b.birthmonth = :month) AND (b.birthday = :day)";
		$param[':month'] = $month;
		$param[':day'] = $day;
	}
	$num = isset($_GPC['num']) ? intval($_GPC['num']) : -1;
	if($num >= 0) {
		if(!$num) {
			$where .= " AND a.nums = 0";
		} else {
			$where .= " AND a.nums > 0";
		}
	}

	$endtime = isset($_GPC['endtime']) ? intval($_GPC['endtime']) : -1;
	if($endtime >= 0) {
		$where .= " AND a.endtime <= :endtime";
		$param[':endtime'] = strtotime($endtime . 'days');
	}

	$keyword = trim($_GPC['keyword']);
	if(!empty($keyword)) {
		$where .= " AND (b.mobile LIKE '%{$keyword}%' OR b.realname LIKE '%{$keyword}%')";
	}
	$sql = 'SELECT a.*, b.realname, b.groupid, b.credit1, b.credit2, b.mobile, b. birthmonth, b.birthday FROM ' . tablename('mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where ORDER BY a.id DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
	$list = pdo_fetchall($sql, $param);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where", $param);
	$pager = pagination($total, $pindex, $psize);
	foreach ($list as &$value) {
		$value['is_birth'] = 0;
		if($value['birthmonth'] == date('m') && $value['birthday'] == date('d')) {
			$value['is_birth'] = 1;
		}
	}
	$has_members = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid']));
	if($has_members) {
		for($i = 0; $i < 3; $i++) {
			$time = strtotime(date('Y-m-d')) + $i * 86400;
			$month = date('m', $time);
			$day = date('d', $time);
			$uids = pdo_getall('mc_card_members', array('uniacid' => $_W['uniacid']), array('uid'));
			$uids = implode(', ', array_keys($uids));
			$sql = 'SELECT COUNT(*) FROM ' . tablename('mc_members') . " WHERE uniacid = :uniacid AND uid IN ({$uids})AND birthmonth = :month AND birthday = :day";
			$param = array(':uniacid' => $_W['uniacid'], ':month' => $month, ':day' => $day);
			$total[$i] = intval(pdo_fetchcolumn($sql, $param));
		}
	}
	template('mc/card');
}

if ($do == 'delete') {
	$cardid = intval($_GPC['cardid']);
	if (pdo_delete('mc_card_members',array('id' =>$cardid))) {
		message('删除会员卡成功',url('mc/card/manage'),'success');
	} else {
		message('删除会员卡失败',url('mc/card/manage'),'error');
	}
}

if($do == 'coupon') {
	$title = trim($_GPC['keyword']);
	$condition = ' WHERE uniacid = :uniacid AND (amount-dosage>0) AND starttime <= :time AND endtime >= :time';
	$param = array(
		':uniacid' => $_W['uniacid'],
		':time' => TIMESTAMP,
	);
	$data = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . $condition, $param);
	if(empty($data)) {
		exit('empty');
	}
	template('mc/coupon-model');
	exit();
}

if($do == 'modal') {
	$uid = intval($_GPC['uid']);
	$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if(empty($card)) {
		exit('error');
	}
	template('mc/card-model');
	exit();
}

if($do == 'submit') {
	load()->model('mc');
	$uid = intval($_GPC['uid']);
	$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if(empty($card)) {
		message('用户会员卡信息不存在', referer(), 'error');
	}
	$type = trim($_GPC['type']);
	if($type == 'nums_plus') {
		$fee = floatval($_GPC['fee']);
		$tag = intval($_GPC['nums']);
		if(!$fee && !$tag) {
			message('请完善充值金额和充值次数', referer(), 'error');
		}
		$total_num = $card['nums'] + $tag;
		pdo_update('mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'nums',
			'model' => 1,
			'fee' => $fee,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动添加{$tag}次，添加后总次数为{$total_num}次",
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_nums_plus($card['openid'], $setting['nums_text'], $tag, $total_num);
	}

	if($type == 'nums_times') {
		$tag = intval($_GPC['nums']);
		if(!$tag) {
			message('请填写消费次数', referer(), 'error');
		}
		if($card['nums'] < $tag) {
			message('当前用户的消费次数不够', referer(), 'error');
		}
		$total_num = $card['nums'] - $tag;
		pdo_update('mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'nums',
			'model' => 2,
			'fee' => 0,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "消费1次，管理员手动减1次，消费后总次数为{$total_num}次",
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_nums_times($card['openid'], $card['cardsn'], $setting['nums_text'], $total_num);
	}

	if($type == 'times_plus') {
		$fee = floatval($_GPC['fee']);
		$endtime = strtotime($_GPC['endtime']);
		$days = intval($_GPC['days']);
		if($endtime <= $card['endtime'] && !$days) {
			message('服务到期时间不能小于会员当前的服务到期时间或未填写延长服务天数', '', 'error');
		}
		$tag = floor(($endtime - $card['endtime']) / 86400);
		if($days > 0) {
			$tag = $days;
			if($card['endtime'] > TIMESTAMP) {
				$endtime = $card['endtime'] + $days * 86400;
			} else {
				$endtime = strtotime($days . 'days');
			}
		}
		pdo_update('mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$endtime = date('Y-m-d', $endtime);
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'times',
			'model' => 1,
			'fee' => $fee,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_times_plus($card['openid'], $card['cardsn'], $setting['times_text'], $fee, $tag, $endtime);
	}

	if($type == 'times_times') {
		$endtime = strtotime($_GPC['endtime']);
		if($endtime > $card['endtime']) {
			message("该会员的{$setting['times_text']}到期时间为：" . date('Y-m-d', $card['endtime']) . ",您当前在进行消费操作，设置到期时间不能超过" . date('Y-m-d', $card['endtime']) , '', 'error');
		}
		$flag = intval($_GPC['flag']);
		if($flag) {
			$endtime = TIMESTAMP;
		}
		$tag = floor(($card['endtime'] - $endtime) / 86400);
		pdo_update('mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$endtime = date('Y-m-d', $endtime);
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'times',
			'model' => 2,
			'fee' => 0,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_times_times($card['openid'], "您好，您的{$setting['times_text']}到期时间已变更", $setting['times_text'], $endtime);
	}
	message('操作成功', referer(), 'success');
}

if($do == 'record') {
	$uid = intval($_GPC['uid']);
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	$where = ' WHERE uniacid = :uniacid AND uid = :uid';
	$param = array(':uniacid' => $_W['uniacid'], ':uid' => $uid);

	$type = trim($_GPC['type']);
	if(!empty($type)) {
		$where .= ' AND type = :type';
		$param[':type'] = $type;
	}
	if(empty($_GPC['endtime']['start'])) {
		$starttime = strtotime('-30 days');
		$endtime = TIMESTAMP;
	} else {
		$starttime = strtotime($_GPC['endtime']['start']);
		$endtime = strtotime($_GPC['endtime']['end']) + 86399;
	}
	$where .= ' AND addtime >= :starttime AND addtime <= :endtime';
	$param[':starttime'] = $starttime;
	$param[':endtime'] = $endtime;

	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_record') . " {$where}", $param);
	$list = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_record') . " {$where} {$limit}", $param);
	$pager = pagination($total, $pindex, $psize);
	template('mc/card');
}

if($do == 'notice') {
	$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
	if($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";

		$addtime = intval($_GPC['addtime']);
		$where = ' WHERE uniacid = :uniacid AND type = 1';
		$param = array(':uniacid' => $_W['uniacid']);

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_notices') . " {$where}", $param);
		$notices = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_notices') . " {$where} {$limit}", $param);
		$pager = pagination($total, $pindex, $psize);
	}

	if($op == 'post') {
		$id = intval($_GPC['id']);
		if($id > 0) {
			$notice = pdo_get('mc_card_notices', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if(empty($notice)) {
				message('通知不存在或已被删除', referer(), 'error');
			}
		}
		if(checksubmit()) {
			$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('通知标题不能为空');
			$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('通知内容不能为空');
			$data = array(
				'uniacid' => $_W['uniacid'],
				'type' => 1,
				'uid' => 0,
				'title' => $title,
				'thumb' => trim($_GPC['thumb']),
				'groupid' => intval($_GPC['groupid']),
				'content' => htmlspecialchars_decode($_GPC['content']),
				'addtime' => TIMESTAMP
			);
			if($id > 0) {
				pdo_update('mc_card_notices', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
			} else {
				pdo_insert('mc_card_notices', $data);
			}
			message('发布通知成功', url('mc/card/notice') , 'success');
		}
	}

	if($op == 'del') {
		$id = intval($_GPC['id']);
		pdo_delete('mc_card_notices', array('uniacid' => $_W['uniacid'], 'id' => $id));
		message('删除成功', referer(), 'success');
	}
	template('mc/card-notice');
}

if($do == 'care') {
	$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
	if($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";

		$where = ' WHERE uniacid = :uniacid';
		$param = array(':uniacid' => $_W['uniacid']);

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_care') . " {$where}", $param);
		$cares = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_care') . " {$where} {$limit}", $param);
		$pager = pagination($total, $pindex, $psize);
	}

	if($op == 'post') {
		$id = intval($_GPC['id']);
		if($id > 0) {
			$care = pdo_get('mc_card_care', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if(empty($care)) {
				message('节日关怀不存在或已被删除', referer(), 'error');
			}
			if($care['couponid'] > 0) {
				$coupon = pdo_get('activity_coupon', array('uniacid' => $_W['uniacid'], 'couponid' => $care['couponid']));
			}
		} else {
			$care = array(
				'time' => 18
			);
		}
		if(checksubmit()) {
			$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('节日标题不能为空');
			$data = array(
				'uniacid' => $_W['uniacid'],
				'title' => $title,
				'type' => intval($_GPC['type']),
				'groupid' => intval($_GPC['groupid']),
				'credit1' => intval($_GPC['credit1']),
				'credit2' => intval($_GPC['credit2']),
				'couponid' => intval($_GPC['couponid']),
				'granttime' => strtotime($_GPC['granttime']),
				'days' => intval($_GPC['days']),
				'time' => intval($_GPC['time']),
				'show_in_card' => intval($_GPC['show_in_card']),
				'content' => trim($_GPC['content']),
				'sms_notice' => intval($_GPC['sms_notice']),
			);
			if($id > 0) {
				pdo_update('mc_card_care', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
			} else {
				pdo_insert('mc_card_care', $data);
			}
			message('发布节日关怀成功', url('mc/card/care') , 'success');
		}
	}

	if($op == 'del') {
		$id = intval($_GPC['id']);
		pdo_delete('mc_card_care', array('uniacid' => $_W['uniacid'], 'id' => $id));
		message('删除成功', referer(), 'success');
	}
	template('mc/card-care');
}

if($do == 'credit') {
	$set = pdo_get('mc_card_credit_set', array('uniacid' => $_W['uniacid']));
	if(empty($set)) {
		$set = array();
	} else {
		$set['share'] = iunserializer($set['share']);
		$set['sign'] = iunserializer($set['sign']);
	}
	if(checksubmit()) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'sign' => array(
				'everydaynum' => intval($_GPC['sign']['everydaynum']),
				'lastday' => intval($_GPC['sign']['lastday']),
				'lastnum' => intval($_GPC['sign']['lastnum']),
			),
			'share' => array(
				'times' => intval($_GPC['share']['times']),
				'num' => intval($_GPC['share']['num']),
			),
			'content' => htmlspecialchars_decode($_GPC['content']),
		);
		$data['sign'] = iserializer($data['sign']);
		$data['share'] = iserializer($data['share']);
		if(empty($set['uniacid'])) {
			pdo_insert('mc_card_credit_set', $data);
		} else {
			pdo_update('mc_card_credit_set', $data, array('uniacid' => $_W['uniacid']));
		}
		message('积分策略更新成功', referer(), 'success');
	}
	template('mc/card-credit');
}

if($do == 'recommend') {
	$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
	if($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";

		$addtime = intval($_GPC['addtime']);
		$where = ' WHERE uniacid = :uniacid';
		$param = array(':uniacid' => $_W['uniacid']);

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_recommend') . " {$where}", $param);
		$recommends = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_recommend') . " {$where} {$limit}", $param);
		$pager = pagination($total, $pindex, $psize);
	}

	if($op == 'post') {
		$id = intval($_GPC['id']);
		if($id > 0) {
			$recommend = pdo_get('mc_card_recommend', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if(empty($recommend)) {
				message('推荐不存在或已被删除', referer(), 'error');
			}
		}
		if(checksubmit()) {
			$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('推荐标题不能为空');
			$content = trim($_GPC['url']) ? trim($_GPC['url']) : message('推荐链接不能为空');
			$data = array(
				'uniacid' => $_W['uniacid'],
				'title' => $title,
				'thumb' => trim($_GPC['thumb']),
				'url' => trim($_GPC['url']),
				'displayorder' => intval($_GPC['displayorder']),
				'addtime' => TIMESTAMP
			);
			if($id > 0) {
				pdo_update('mc_card_recommend', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
			} else {
				pdo_insert('mc_card_recommend', $data);
			}
			message('发布推荐成功', url('mc/card/recommend') , 'success');
		}
	}

	if($op == 'del') {
		$id = intval($_GPC['id']);
		pdo_delete('mc_card_recommend', array('uniacid' => $_W['uniacid'], 'id' => $id));
		message('删除成功', referer(), 'success');
	}

	template('mc/card-recommend');
}

if($do == 'stat') {
	$now = strtotime(date('Y-m-d'));
	$starttime = empty($_GPC['time']['start']) ? $now - 30*86400 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$num = ($endtime + 1 - $starttime) / 86400;
	if($_W['isajax']) {
		$stat = array();
		for($i = 0; $i < $num; $i++) {
			$time = $i * 86400 + $starttime;
			$key = date('m-d', $time);
			$stat[$key] = 0;
		}
		$data = pdo_fetchall('SELECT id,createtime FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));
		if(!empty($data)) {
			foreach($data as $da) {
				$key = date('m-d', $da['createtime']);
				$stat[$key] += 1;
			}
		}

		$out['label'] = array_keys($stat);
		$out['datasets'] = array_values($stat);
		exit(json_encode($out));
	}

	$total = floatval(pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid'])));
	$today = floatval(pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
	$yesterday = floatval(pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')))));
	template('mc/card-stat');
}



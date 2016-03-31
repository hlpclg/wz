<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$title = "礼品兑换";
checkauth();
$request_id = intval($_GPC['request_id']);
$goods_id = intval($_GPC['id']);
if (!empty($_GPC['id']))
{
	$fans = fans_search($_W['fans']['from_user'], array('credit1', 'realname', 'mobile', 'residedist'));
	$goods_info = pdo_fetch("SELECT * FROM ".tablename('meepo_bbs_credit_goods')." WHERE id = $goods_id AND uniacid = '{$_W['uniacid']}'");
	$replicated = pdo_fetch("SELECT * FROM " . tablename('meepo_bbs_credit_request') . "  WHERE goods_id = $goods_id AND id= $request_id AND uniacid = '{$_W['uniacid']}' AND uid = '{$_W['member']['uid']}'");  // 重复兑换同一个订单会提示
	if (!empty($replicated)) {
		$last_time = date('H:i:s',$replicated['createtime']);
		message("您在{$last_time}已经成功兑换【{$goods_info['title']}】。不要提交重复的订单",
		$this->createMobileUrl('home_credit_goods_myrequest'),
		"success");
	}

	if ($goods_info['amount'] <= 0) {
		message('商品已经兑空，请重新选择商品！',
		$this->createMobileUrl('home_credit_goods'),
		'error');
	}

	// 0表示无限制
	if ($goods_info['per_user_limit'] > 0) {
		$goods_limit = pdo_fetch("SELECT count(*) as per_user_limit FROM " . tablename('meepo_bbs_credit_request') . "  WHERE goods_id = $goods_id AND uniacid = '{$_W['uniacid']}' AND uid = '{$_W['member']['uid']}'");

		if ($goods_limit['per_user_limit'] >= $goods_info['per_user_limit']) {
			message("本商品每个用户最多可兑换".$goods_info['per_user_limit']."件，您已经达到最大限制，请重新选择商品！",
			$this->createMobileUrl('home_credit_goods'),
			'error');
		}
	}

	if ($fans['credit1'] < $goods_info['cost']) {
		message('该奖品仅支持系统积分兑换，积分不足, 请重新选择商品！<br>当前商品所需积分:'.$goods_info['cost'].'<br>您的积分:'.$fans['credit1'] .'(系统)'
				. '<br><br>小提示：<br>每日签到，可以赚取积分哦',
				$this->createMobileUrl('home_credit_goods'),
				'error');
	}

	if ( $goods_info['cost'] > $fans['credit1']) {
		message("系统出现未知错误，请重试或与管理员联系", "", "error");
	}
	$credit = 0 - $goods_info['cost'];
	$uid = mc_openid2uid($_W['fans']['from_user']);
	$err = mc_credit_update($uid, 'credit1', $credit, '积分换礼品');
	if (is_error($err)) {
		message("系统出现未知错误，请重试或与管理员联系", "", "error");
	}
	// fans_update($_W['fans']['from_user'], array('credit1' => $credit));


	$data = array(
			'amount' => $goods_info['amount'] - 1
	);
	pdo_update('meepo_bbs_credit_goods', $data, array('uniacid' => $_W['uniacid'], 'id' => $goods_id));


	$data = array(
			'realname' => !empty($_GPC['realname'])?$_GPC['realname']:$fans['realname'],
			'mobile' => !empty($_GPC['mobile'])?$_GPC['mobile']:$fans['mobile'],
			'residedist' => !empty($_GPC['residedist'])?$_GPC['residedist']:$fans['residedist'],
	);
	fans_update($_W['fans']['from_user'], $data);

	$data = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $_W['member']['uid'],
			'openid' => $_W['openid'],
			'realname' => trim($_GPC['realname']),
			'mobile' => trim($_GPC['mobile']),
			'residedist' => trim($_GPC['residedist']),
			'note' => trim($_GPC['note']),
			'goods_id' => $goods_id,
			'status' => 'new',
			'createtime' => TIMESTAMP
	);
	if(empty($request_id)){
		pdo_insert('meepo_bbs_credit_request', $data);
	}else{
		pdo_update('meepo_bbs_credit_request',$data,array('id'=>$request_id));
	}
	pdo_insert('meepo_bbs_credit_request', $data);
	message("积分兑换成功！从系统积分中扣除{$f_cost}分。",
	$this->createMobileUrl('home_credit_goods_myrequest', array('op' => 'display')),
	'success');
}
else
{
	message('请选择要兑换的商品！', $this->createMobileUrl('home_credit_goods'), 'error');
}
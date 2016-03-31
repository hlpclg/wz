<?php
/**
 * [WEIZAN System] Copyright (c) 2015 012WZ.COM
 * WeiZan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function clerk_check() {
	global $_W;
	if(empty($_W['openid'])) {
		return error(-1, '获取粉丝openid失败');
	}
	$data = pdo_get('activity_coupon_password', array('uniacid' => $_W['uniacid'], 'openid' => $_W['fans']['from_user']));
	if(empty($data)) {
		return error(-1, '不是操作店员');
	}
	return $data;
}


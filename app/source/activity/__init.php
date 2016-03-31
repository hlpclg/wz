<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
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

if($do == 'token_qrcode') {
	require_once('../framework/library/qrcode/phpqrcode.php');
	$errorCorrectionLevel = "L";
	$matrixPointSize = "8";
	$token_id = intval($_GPC['id']);
	$url = $_W['siteroot'] . 'app' . ltrim(murl('clerk/token', array('uid' => $_W['member']['uid'], 'id' => $token_id)), '.');
	QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
	exit();
}



<?php

defined('IN_IA') or exit('Access Denied');
error_reporting(0);
global $_W;
load()->func('file');
if (!in_array($do, array('local'))) {
	exit('Access Denied');
}

if ($do == 'local') {
	$title = trim($_GPC['keyword']);
	$condition = ' WHERE uniacid = :uniacid AND (amount-dosage>0) AND endtime > :time';
	$param = array(
		':uniacid' => $_W['uniacid'],
		':time' => TIMESTAMP,
	);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('activity_coupon') . $condition, $param);
	$data = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . $condition . ' ORDER BY couponid DESC LIMIT ' . ($pindex - 1) * $psize . ', ' . $psize, $param, 'couponid');
	if(!empty($data)) {
		foreach($data as &$da) {
			$da['starttime_cn'] = date('Y-m-d', $da['starttime']);
			$da['endtime_cn'] = date('Y-m-d', $da['endtime']);
		}
	}
	message(array('page'=> pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '2', 'ajaxcallback'=>'null')), 'items' => $data), '', 'ajax');
}
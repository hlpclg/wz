<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

$eid = intval($_GPC['eid']);
if(!empty($eid)) {
	$sql = 'SELECT * FROM ' . tablename('modules_bindings') . ' WHERE `eid`=:eid';
	$entry = pdo_fetch($sql, array(':eid' => $eid));
	$_GPC['m'] = $entry['module'];
} else {
	$entry = array(
		'module' => $_GPC['m'],
		'do' => $_GPC['do'],
		'state' => $_GPC['state'],
		'direct' => 0
	);
}
if(empty($entry) || empty($entry['do'])) {
	message('非法访问.');
}

$_GPC['__entry'] = $entry['title'];
$_GPC['__state'] = $entry['state'];

define('IN_MODULE', $entry['module']);

$site = WeUtility::createModuleSite($entry['module']);
if(!is_error($site)) {
	$method = 'doMobile' . ucfirst($entry['do']);
	exit($site->$method());
}
exit();

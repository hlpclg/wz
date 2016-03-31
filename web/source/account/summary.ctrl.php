<?php
/**
 * [WEIZAN System] Copyright (c) 2015 012WZ.COM
 * WeiZan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$acid = intval($_GPC['acid']);
$uniacid = intval($_GPC['uniacid']);

$account = account_fetch($acid);
if(empty($account)) {
	message('公众号不存在或已被删除', '', 'error');
}
$_W['page']['title'] = $account['name'] . ' - 公众号详细信息';

$st = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : strtotime('-30day');
$et = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : strtotime(date('Y-m-d'));
$starttime = min($st, $et);
$endtime = max($st, $et);
$day_num = ($endtime - $starttime) / 86400 + 1;
$endtime += 86399;
$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;
if($_W['isajax'] && $_W['ispost']) {
	$days = array();
	$datasets = array();
	for($i = 0; $i < $day_num; $i++){
		$key = date('m-d', $starttime + 86400 * $i);
		$days[$key] = 0;
		$datasets['flow1'][$key] = 0;
		$datasets['flow2'][$key] = 0;
		$datasets['flow3'][$key] = 0;
		$datasets['flow4'][$key] = 0;
	}

		$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 1));
	foreach($data as $da) {
		$key = date('m-d', $da['followtime']);
		if(in_array($key, array_keys($days))) {
			$datasets['flow1'][$key]++;
		}
	}

		$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 0));
	foreach($data as $da) {
		$key = date('m-d', $da['unfollowtime']);
		if(in_array($key, array_keys($days))) {
			$datasets['flow2'][$key]++;
		}
	}

		$data0 = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 0));
	$data1 = pdo_fetchall('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => $starttime, ':endtime' => $endtime, ':follow' => 1));
	foreach($data1 as $da) {
		$key = date('m-d', $da['followtime']);
		if(in_array($key, array_keys($days))) {
			$day[date('m-d', $da['followtime'])] ++;
			$datasets['flow3'][$key]++;
		}
	}
	foreach($data0 as $da) {
		$key = date('m-d', $da['unfollowtime']);
		if(in_array($key, array_keys($days))) {
			$datasets['flow3'][$key]--;
		}
	}

		for($i = 0; $i < $day_num; $i++){
		$key = date('m-d', $starttime + 86400 * $i);
		$datasets['flow4'][$key] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime < ' . ($starttime + 86400 * $i + 86439), array(':acid' => $acid, ':uniacid' => $uniacid, ':follow' => 1));;
	}

	$shuju['label'] = array_keys($days);
	$shuju['datasets'] = $datasets;
	
	if ($day_num == 1) {
		$day_num = 2;
		$shuju['label'][] = $shuju['label'][0];
		
		foreach ($shuju['datasets']['flow1'] as $ky => $va) {
			$k = $ky;
			$v = $va;
		}
		$shuju['datasets']['flow1']['-'] = $v;
		
		foreach ($shuju['datasets']['flow2'] as $ky => $va) {
			$k = $ky;
			$v = $va;
		}
		$shuju['datasets']['flow2']['-'] = $v;
		
		foreach ($shuju['datasets']['flow3'] as $ky => $va) {
			$k = $ky;
			$v = $va;
		}
		$shuju['datasets']['flow3']['-'] = $v;
		
		foreach ($shuju['datasets']['flow4'] as $ky => $va) {
			$k = $ky;
			$v = $va;
		}
		$shuju['datasets']['flow4']['-'] = $v;
	}

	$shuju['datasets']['flow1'] = array_values($shuju['datasets']['flow1']);
	$shuju['datasets']['flow2'] = array_values($shuju['datasets']['flow2']);
	$shuju['datasets']['flow3'] = array_values($shuju['datasets']['flow3']);
	$shuju['datasets']['flow4'] = array_values($shuju['datasets']['flow4']);
	exit(json_encode($shuju));
}
$uniaccount = pdo_fetchcolumn('SELECT name FROM ' . tablename('uni_account') . ' WHERE uniacid = :uniacid', array(':uniacid' => $account['uniacid']));
$uid = pdo_fetchcolumn('SELECT uid FROM ' . tablename('uni_account_users') . ' WHERE uniacid = :uniacid', array(':uniacid' => $account['uniacid']));
$username = pdo_fetchcolumn('SELECT username FROM ' . tablename('users') . ' WHERE uid = :uid', array(':uid' => $uid));

$scroll = intval($_GPC['scroll']);
$add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')), ':follow' => 1));
$cancel_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')), ':follow' => 0));
$jing_num = $add_num - $cancel_num;
$total_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':endtime' => strtotime(date('Y-m-d')), ':follow' => 1));

$today_add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 1));
$today_cancel_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 0));
$today_jing_num = $today_add_num - $today_cancel_num;
$today_total_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime <= :endtime', array(':acid' => $acid, ':uniacid' => $uniacid, ':endtime' => TIMESTAMP, ':follow' => 1));
template('account/summary');
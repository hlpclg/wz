<?php
/**
 * [WEIZAN System] Copyright (c) 2015 012WZ.COM
 * WeiZan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('mc_tplnotice');
$_W['page']['title'] = '会员微信通知-会员中心';
$dos = array('set');
$do = in_array($do, $dos) ? $do : 'set';

if($do == 'set') {
	if(checksubmit()) {
		$data = array(
			'type' => trim($_GPC['type']),
			'recharge' => trim($_GPC['recharge']),
			'credit1' => trim($_GPC['credit1']),
			'credit2' => trim($_GPC['credit2']),
			'group' => trim($_GPC['group']),
			'nums_plus' => trim($_GPC['nums_plus']),
			'nums_times' => trim($_GPC['nums_times']),
			'times_plus' => trim($_GPC['times_plus']),
			'times_times' => trim($_GPC['times_times']),
		);
		$data = iserializer($data);
		pdo_update('uni_settings', array('tplnotice' => $data), array('uniacid' => $_W['uniacid']));
		message('设置通知模板成功', referer(), 'success');
	}
	$setting = uni_setting($_W['uniacid'], '*', true);
	$tpl = $setting['tplnotice'];
	if(!is_array($tpl)) {
		$tpl = array();
	}
	template('mc/tplnotice');
}

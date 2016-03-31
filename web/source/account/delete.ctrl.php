<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$uniacid = intval($_GPC['uniacid']);
$acid = intval($_GPC['acid']);

if (!empty($acid)) {
	$account = account_fetch($acid);
	if (empty($account)) {
		message('子公众号不存在或是已经被删除');
	}
	$state = uni_permission($uid, $uniacid);
	if($state != 'founder' && $state != 'manager') {
		message('没有该公众号操作权限！', url('accound/display'), 'error');
	}
	$uniaccount = uni_fetch($account['uniacid']);
	if ($uniaccount['default_acid'] == $acid) {
		message('默认子公众号不能删除');
	}
	$uniacid = $account['uniacid'];
	pdo_delete('account', array('acid' => $acid));
	pdo_delete('account_wechats', array('acid' => $acid, 'uniacid' => $uniacid));
	cache_delete("unicount:{$uniacid}");
	cache_delete('account:auth:refreshtoken:'.$acid);
	$oauth = uni_setting($uniacid, array('oauth'));
	if($oauth['oauth']['account'] == $acid) {
		$acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . " WHERE uniacid = :id AND level = 4 AND secret != '' AND `key` != ''", array(':id' => $uniacid));
		pdo_update('uni_settings', array('oauth' => iserializer(array('account' => $acid, 'host' => $oauth['oauth']['host']))), array('uniacid' => $uniacid));
	}
	@unlink(IA_ROOT . '/attachment/qrcode_'.$acid.'.jpg');
	@unlink(IA_ROOT . '/attachment/headimg_'.$acid.'.jpg');
	message('删除子公众号成功！', referer(), 'success');
}
if (!empty($uniacid)) {
	$account = pdo_fetch("SELECT * FROM ".tablename('uni_account')." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
	if (empty($account)) {
		message('抱歉，帐号不存在或是已经被删除', url('account/display'), 'error');
	}
	$state = uni_permission($uid, $uniacid);
	if($state != 'founder' && $state != 'manager') {
		message('没有该公众号操作权限！', url('accound/display'), 'error');
	}
	if($_GPC['uniacid'] == $_W['uniacid']) {
		isetcookie('__uniacid', '');
	}
	cache_delete("unicount:{$uniacid}");
	$modules = array();
		$rules = pdo_fetchall("SELECT id, module FROM ".tablename('rule')." WHERE uniacid = '{$uniacid}'");
	if (!empty($rules)) {
		foreach ($rules as $index => $rule) {
			$deleteid[] = $rule['id'];
			if (empty($modules[$rule['module']])) {
				$file = IA_ROOT . '/framework/builtin/'.$rule['module'].'/module.php';
				if (file_exists($file)) {
					include_once $file;
				}
				$modules[$rule['module']] = WeUtility::createModule($rule['module']);
			}
			if (method_exists($modules[$rule['module']], 'ruleDeleted')) {
				$modules[$rule['module']]->ruleDeleted($rule['id']);
			}
		}
		pdo_delete('rule', "id IN ('".implode("','", $deleteid)."')");
	}
	
	$subaccount = pdo_fetchall("SELECT acid FROM ".tablename('account')." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
	if (!empty($subaccount)) {
		foreach ($subaccount as $account) {
			@unlink(IA_ROOT . '/attachment/qrcode_'.$account['acid'].'.jpg');
			@unlink(IA_ROOT . '/attachment/headimg_'.$account['acid'].'.jpg');
		}
		$acid = intval($_GPC['acid']);
		if (empty($acid)) {
			load()->func('file');
			rmdirs(IA_ROOT . '/attachment/images/' . $uniacid);
			@rmdir(IA_ROOT . '/attachment/images/' . $uniacid);
			rmdirs(IA_ROOT . '/attachment/audios/' . $uniacid);
			@rmdir(IA_ROOT . '/attachment/audios/' . $uniacid);
		}
	}
	
		$tables = array(
		'account','account_wechats', 'activity_coupon',
		'activity_coupon_allocation','activity_coupon_modules','activity_coupon_password',
		'activity_coupon_record','activity_exchange','activity_exchange_trades','activity_exchange_trades_shipping',
		'activity_modules', 'core_attachment','core_paylog','core_queue','core_resource',
		'wechat_attachment','coupon','coupon_location','coupon_modules',
		'coupon_record','coupon_setting','cover_reply', 'mc_card','mc_card_members','mc_chats_record','mc_credits_recharge','mc_credits_record',
		'mc_fans_groups','mc_groups','mc_handsel','mc_mapping_fans','mc_mapping_ucenter','mc_mass_record',
		'mc_member_address','mc_member_fields','mc_members','menu_event',
		'qrcode','qrcode_stat', 'rule','rule_keyword','site_article','site_category','site_multi','site_nav','site_slide',
		'site_styles','site_styles_vars','stat_keyword','stat_msg_history',
		'stat_rule','uni_account','uni_account_modules','uni_account_users','uni_settings', 'uni_group', 'uni_verifycode','users_permission',
	);
	if (!empty($tables)) {
		foreach ($tables as $table) {
			$tablename = str_replace($GLOBALS['_W']['config']['db']['tablepre'], '', $table);
			pdo_delete($tablename, array( 'uniacid'=> $uniacid));
		}
	}
}
message('公众帐号信息删除成功！', url('account/display'), 'success');
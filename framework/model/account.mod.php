<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan isNOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

define('WEIXIN_ROOT', 'https://mp.weixin.qq.com');


function uni_create_permission($uid, $type = 1) {
	$groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('users') . ' WHERE uid = :uid', array(':uid' => $uid));
	$groupdata = pdo_fetch('SELECT maxaccount, maxsubaccount FROM ' . tablename('users_group') . ' WHERE id = :id', array(':id' => $groupid));
	$list = pdo_fetchall('SELECT uniacid FROM ' . tablename('uni_account_users') . ' WHERE uid = :uid AND role = :role ', array(':uid' => $uid, ':role' => 'owner'));
	foreach ($list as $item) {
		$uniacids[] = $item['uniacid'];
	}
	unset($item);
	$uniacidnum = count($list);
		if ($type == 1) {
		if ($uniacidnum >= $groupdata['maxaccount']) {
			return error('-1', '您所在的用户组最多只能创建' . $groupdata['maxaccount'] . '个主公号');
		}
	} elseif ($type == 2) {
		$subaccountnum = 0;
		if (!empty($uniacids)) {
			$subaccountnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('account') . ' WHERE uniacid IN (' . implode(',', $uniacids) . ')');
		}
		if ($subaccountnum >= $groupdata['maxsubaccount']) {
			return error('-1', '您所在的用户组最多只能创建' . $groupdata['maxsubaccount'] . '个子公号');
		}
	}
	return true;
}


function uni_owned($uid = 0) {
	global $_W;
	$uid = empty($uid) ? $_W['uid'] : intval($uid);
	$uniaccounts = array();
	$founders = explode(',', $_W['config']['setting']['founder']);
	if (in_array($uid, $founders)) {
		$uniaccounts = pdo_fetchall("SELECT * FROM " . tablename('uni_account') . " ORDER BY `uniacid` DESC", array(), 'uniacid');
	} else {
		$uniacids = pdo_fetchall("SELECT uniacid FROM " . tablename('uni_account_users') . " WHERE uid = :uid", array(':uid' => $uid), 'uniacid');
		if (!empty($uniacids)) {
			$uniaccounts = pdo_fetchall("SELECT * FROM " . tablename('uni_account') . " WHERE uniacid IN (" . implode(',', array_keys($uniacids)) . ") ORDER BY `uniacid` DESC", array(), 'uniacid');
		}
	}
	
	return $uniaccounts;
}


function uni_permission($uid = 0, $uniacid = 0) {
	global $_W;
	$uid = empty($uid) ? $_W['uid'] : intval($uid);
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$founders = explode(',', $_W['config']['setting']['founder']);
	if (in_array($uid, $founders)) {
		return 'founder';
	}

	$sql = 'SELECT `role` FROM ' . tablename('uni_account_users') . ' WHERE `uid`=:uid AND `uniacid`=:uniacid';
	$pars = array();
	$pars[':uid'] = $uid;
	$pars[':uniacid'] = $uniacid;
	$role = pdo_fetchcolumn($sql, $pars);
	return in_array($role, array('manager', 'owner')) ? 'manager' : 'operator';
}


function uni_accounts($uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$accounts = pdo_fetchall("SELECT w.*, a.type, a.isconnect FROM " . tablename('account') . " a INNER JOIN " . tablename('account_wechats') . " w USING(acid) WHERE a.uniacid = :uniacid ORDER BY a.acid ASC", array(':uniacid' => $uniacid), 'acid');
	return $accounts;
}


function uni_fetch($uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$cachekey = "uniaccount:{$uniacid}";
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$account = uni_account_default($uniacid);
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $uniacid));
	load()->model('user');
	$owner = user_single(array('uid' => $owneruid));
	$account['uid'] = $owner['uid'];
	$account['starttime'] = $owner['starttime'];
	$account['endtime'] = $owner['endtime'];
	load()->model('mc');
	$account['groups'] = mc_groups($uniacid);
	$account['grouplevel'] = pdo_fetchcolumn('SELECT grouplevel FROM ' . tablename('uni_settings') . ' WHERE uniacid = :uniacid', array(':uniacid' => $uniacid));
	cache_write($cachekey, $account);
	return $account;
}


function uni_modules($enabledOnly = true) {
	global $_W;
	$cachekey = "unimodules:{$_W['uniacid']}:{$enabledOnly}";
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $_W['uniacid']));
	load()->model('user');
	$owner = user_single(array('uid' => $owneruid));
		if (empty($owner)) {
		$groupid = '-1';
	} else {
		$groupid = $owner['groupid'];
	}
	$extend = pdo_getall('uni_account_group', array('uniacid' => $_W['uniacid']), array(), 'groupid');
	if (!empty($extend)) {
		$groupid = '-2';
	}
	if (empty($groupid)) {
		$modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " WHERE issystem = 1 ORDER BY issystem DESC, mid ASC", array(), 'name');
	} elseif ($groupid == '-1') {
		$modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " ORDER BY issystem DESC, mid ASC", array(), 'name');
	} else {
		$group = pdo_fetch("SELECT id, name, package FROM ".tablename('users_group')." WHERE id = :id", array(':id' => $groupid));
		if (!empty($group)) {
			$packageids = iunserializer($group['package']);
		} else {
			$packageids = array();
		}
		if (!empty($extend)) {
			foreach ($extend as $extend_packageid => $row) {
				$packageids[] = $extend_packageid;
			}
		}
		if (in_array('-1', $packageids)) {
			$modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " ORDER BY issystem DESC, mid ASC", array(), 'name');
		} else {
			$wechatgroup = pdo_fetchall("SELECT `modules` FROM " . tablename('uni_group') . " WHERE id IN ('".implode("','", $packageids)."') OR uniacid = '{$_W['uniacid']}'");
			$ms = array();
			$mssql = '';
			if (!empty($wechatgroup)) {
				foreach ($wechatgroup as $row) {
					$row['modules'] = iunserializer($row['modules']);
					if (!empty($row['modules'])) {
						foreach ($row['modules'] as $modulename) {
							$ms[$modulename] = $modulename;
						}
					}
				}
				$mssql = " OR `name` IN ('".implode("','", $ms)."')";
			}
			$modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " WHERE issystem = 1{$mssql} ORDER BY issystem DESC, mid ASC", array(), 'name');
		}
	}
	if (!empty($modules)) {
		$ms = implode("','", array_keys($modules));
		$ms = "'{$ms}'";
		$mymodules = pdo_fetchall("SELECT `module`, `enabled`, `settings` FROM " . tablename('uni_account_modules') . " WHERE uniacid = '{$_W['uniacid']}' AND `module` IN ({$ms}) ORDER BY enabled DESC", array(), 'module');
	}
	if (!empty($mymodules)) {
		foreach ($mymodules as $name => $row) {
			if ($enabledOnly && !$modules[$name]['issystem']) {
				if ($row['enabled'] == 0 || empty($modules[$name])) {
					unset($modules[$name]);
					continue;
				}
			}
			if (!empty($row['settings'])) {
				$modules[$name]['config'] = iunserializer($row['settings']);
			}
			$modules[$name]['enabled'] = $row['enabled'];
		}
	}
	foreach ($modules as $name => &$row) {
		if ($row['issystem'] == 1) {
			$row['enabled'] = 1;
		} elseif (!isset($row['enabled'])) {
			$row['enabled'] = 1;
		}
		if (empty($row['config'])) {
			$row['config'] = array();
		}
		if (!empty($row['subscribes'])) {
			$row['subscribes'] = iunserializer($row['subscribes']);
		}
		if (!empty($row['handles'])) {
			$row['handles'] = iunserializer($row['handles']);
		}
		unset($modules[$name]['description']);
	}
	cache_write($cachekey, $modules);
	return $modules;
}


function uni_groups($groupids = array()) {
	$condition = ' WHERE uniacid = 0';
	if (!is_array($groupids)) {
		$groupids = array($groupids);
	}
	if (!empty($groupids)) {
		foreach ($groupids as $i => $row) {
			$groupids[$i] = intval($row);
		}
		unset($row);
		$condition .= " AND id IN (" . implode(',', $groupids) . ")";
	}
	$list = pdo_fetchall("SELECT * FROM " . tablename('uni_group') . $condition . " ORDER BY id ASC", array(), 'id');
	if (in_array('-1', $groupids)) {
		$list[-1] = array('id' => -1, 'name' => '所有服务');
	}
	if (in_array('0', $groupids)) {
		$list[0] = array('id' => 0, 'name' => '基础服务');
	}
	if (!empty($list)) {
		foreach ($list as &$row) {
			if (!empty($row['modules'])) {
				$modules = iunserializer($row['modules']);
				if (is_array($modules)) {
					$row['modules'] = pdo_fetchall("SELECT name, title FROM " . tablename('modules') . " WHERE name IN ('" . implode("','", $modules) . "')");
				}
			}
			if (!empty($row['templates'])) {
				$templates = iunserializer($row['templates']);
				if (is_array($templates)) {
					$row['templates'] = pdo_fetchall("SELECT name, title FROM " . tablename('site_templates') . " WHERE id IN ('" . implode("','", $templates) . "')");
				}
			}
		}
	}
	return $list;
}


function uni_templates() {
	global $_W;
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $_W['uniacid']));
	load()->model('user');
	$owner = user_single(array('uid' => $owneruid));
		if (empty($owner)) {
		$groupid = '-1';
	} else {
		$groupid = $owner['groupid'];
	}
	if (empty($groupid)) {
		$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE name = 'default'", array(), 'id');
	} elseif ($groupid == '-1') {
		$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " ORDER BY id ASC", array(), 'id');
	} else {
		$group = pdo_fetch("SELECT id, name, package FROM ".tablename('users_group')." WHERE id = :id", array(':id' => $groupid));
		$packageids = iunserializer($group['package']);
		$extend = pdo_getall('uni_account_group', array('uniacid' => $_W['uniacid']), array(), 'groupid');
		if (!empty($extend)) {
			foreach ($extend as $extend_packageid => $row) {
				$packageids[] = $extend_packageid;
			}
		}
		if(!is_array($packageids)) {
			return array();
		}
		if (in_array('-1', $packageids)) {
			$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " ORDER BY id ASC", array(), 'id');
		} else {
			$wechatgroup = pdo_fetchall("SELECT `templates` FROM " . tablename('uni_group') . " WHERE id IN ('".implode("','", $packageids)."') OR uniacid = '{$_W['uniacid']}'");
			$ms = array();
			$mssql = '';
			if (!empty($wechatgroup)) {
				foreach ($wechatgroup as $row) {
					$row['templates'] = iunserializer($row['templates']);
					if (!empty($row['templates'])) {
						foreach ($row['templates'] as $templateid) {
							$ms[$templateid] = $templateid;
						}
					}
				}
				$mssql = " `id` IN ('".implode("','", $ms)."')";
			}
			$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') .(!empty($mssql) ? " WHERE $mssql" : '')." ORDER BY id DESC", array(), 'id');
		}
	}
	if (empty($templates)) {
		$templates = pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE id = 1 ORDER BY id DESC", array(), 'id');
	}
	return $templates;
}


function uni_setting($uniacid = 0, $fields = '*', $force_update = false) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : $uniacid;
	$cachekey = "unisetting:{$uniacid}";
	$unisetting = array();
	if(!$force_update) {
		$unisetting = cache_load($cachekey);
	}
	if (empty($unisetting)) {
		$unisetting = pdo_fetch("SELECT * FROM " . tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		if (!empty($unisetting)) {
			$serialize = array('site_info', 'menuset', 'stat', 'oauth', 'passport', 'uc', 'notify', 'creditnames', 'default_message', 'creditbehaviors', 'shortcuts', 'payment', 'recharge', 'tplnotice');
			foreach ($unisetting as $key => &$row) {
				if (in_array($key, $serialize)) {
					$row = iunserializer($row);
				}
			}
		}
		cache_write($cachekey, $unisetting);
	}
	if (is_array($fields)) {
		return array_elements($fields, $unisetting);
	}
	return $unisetting;
}


function uni_account_default($uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$account = pdo_fetch("SELECT w.*, a.default_acid FROM ".tablename('uni_account')." a LEFT JOIN ".tablename('account_wechats')." w ON a.default_acid = w.acid WHERE a.uniacid = :uniacid", array(':uniacid' => $uniacid), 'acid');
	if (empty($account['acid'])) {
		$default_acid = pdo_fetchcolumn("SELECT acid FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid ORDER BY level DESC", array(':uniacid' => $_W['uniacid']));
		$account = pdo_fetch("SELECT w.* FROM " . tablename('uni_account') . " AS a, " . tablename('account_wechats') ." AS w WHERE w.acid = '{$default_acid}'");
	}
	$account['type'] = pdo_fetchcolumn("SELECT type FROM ".tablename('account')." WHERE acid = :acid", array(':acid' => $account['acid']));
	return $account;
}

function uni_user_permission_exist($uid = 0, $uniacid = 0) {
	global $_W;
	$uid = intval($uid) > 0 ? $uid : $_W['uid'];
	$uniacid = intval($uniacid) > 0 ? $uniacid : $_W['uniacid'];
	if($_W['role'] == 'founder' || $_W['role'] == 'manager') {
		return true;
	}
	$is_exist = pdo_fetch('SELECT id FROM ' . tablename('users_permission') . ' WHERE `uid`=:uid AND `uniacid`=:uniacid', array(':uid' => $uid, ':uniacid' => $uniacid));
	if(empty($is_exist)) {
		return true;
	} else {
		return error(-1, '');
	}
}

function uni_user_permission($type = 'system', $uid = 0, $uniacid = 0) {
	global $_W;
	$uid = empty($uid) ? $_W['uid'] : intval($uid);
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$sql = 'SELECT `permission` FROM ' . tablename('users_permission') . ' WHERE `uid`=:uid AND `uniacid`=:uniacid AND `type`=:type';
	$pars = array();
	$pars[':uid'] = $uid;
	$pars[':uniacid'] = $uniacid;
	$pars[':type'] = $type;
	$data = pdo_fetchcolumn($sql, $pars);
	$permission = array();
	if(!empty($data)) {
		$permission = explode('|', $data);
	}
	return $permission;
}

function uni_user_permission_check($permission_name, $is_html = true, $action = '') {
	global $_W, $_GPC;
	$status = uni_user_permission_exist();
	if(!is_error($status)) {
		return true;
	}
	$m = trim($_GPC['m']);
	$do = trim($_GPC['do']);
	$eid = intval($_GPC['eid']);
	if($action == 'reply') {
		$system_modules = system_modules();
		if(!empty($m) && !in_array($m, $system_modules)) {
			$permission_name = $m . '_rule';
			$users_permission = uni_user_permission($m);
		}
	} elseif($action == 'cover' && $eid > 0) {
		$entry = pdo_fetch('SELECT * FROM ' . tablename('modules_bindings') . ' WHERE `eid`=:eid', array(':eid' => $eid));
		if(!empty($entry)) {
			$permission_name = $m . '_cover_' . trim($entry['do']);
			$users_permission = uni_user_permission($entry['module']);
		}
	} elseif($action == 'nav') {
				if(!empty($m)) {
			$permission_name = "{$m}_{$do}";
			$users_permission = uni_user_permission($m);
		} else {
			return true;
		}
	} else {
		$users_permission = uni_user_permission('system');
	}
	if(!isset($users_permission)) {
		$users_permission = uni_user_permission('system');
	}
	if($users_permission[0] != 'all' && !in_array($permission_name, $users_permission)) {
		if($is_html) {
			message('您没有进行该操作的权限', referer(), 'error');
		} else {
			return false;
		}
	}
	return true;
}


function uni_user_module_permission_check($action = '', $module_name = '') {
	global $_GPC;
	$status = uni_user_permission_exist();
	if(!is_error($status)) {
		return true;
	}
	$do = $_GPC['do'];
	$m = $_GPC['m'];
	if(!empty($do) && !empty($m)) {
		$is_exist = pdo_fetch('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE module=:module AND do = :do AND entry = :entry', array(':module' => $m, ':do' => $do, ':entry' => 'menu'));
		if(empty($is_exist)) {
			return true;
		}
	}
	if(empty($module_name)) {
		$module_name = IN_MODULE;
	}
	$permission = uni_user_permission($module_name);
	if(empty($permission) || ($permission[0] != 'all' && !empty($action) && !in_array($action, $permission))) {
		return false;
	}
	return true;
}


function account_types() {
	static $types;
	if (empty($types)) {
		$types = array();
		$types['wechat'] = array(
			'title' => '微信',
			'name' => 'wechat',
			'sn' => '1',
			'table' => 'account_wechats'
		);
	}
	return $types;
}


function account_create($uniacid, $account) {
	$accountdata = array('uniacid' => $uniacid, 'type' => $account['type'], 'hash' => random(8));
	pdo_insert('account', $accountdata);
	$acid = pdo_insertid();
	$account['acid'] = $acid;
	$account['token'] = random(32);
	$account['encodingaeskey'] = random(43);
	$account['uniacid'] = $uniacid;
	unset($account['type']);
	pdo_insert('account_wechats', $account);
	return $acid;
}


function account_fetch($acid) {
	$account = pdo_fetch("SELECT w.*, a.type, a.isconnect FROM " . tablename('account') . " a INNER JOIN " . tablename('account_wechats') . " w USING(acid) WHERE acid = :acid", array(':acid' => $acid));
	$uniacid = $account['uniacid'];
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $uniacid));
	load()->model('user');
	$owner = user_single(array('uid' => $owneruid));
	$account['uid'] = $owner['uid'];
	$account['starttime'] = $owner['starttime'];
	$account['endtime'] = $owner['endtime'];
	load()->model('mc');
	$account['groups'] = mc_groups($uniacid);
	$account['grouplevel'] = pdo_fetchcolumn('SELECT grouplevel FROM ' . tablename('uni_settings') . ' WHERE uniacid = :uniacid', array(':uniacid' => $uniacid));
	return $account;
}


function account_weixin_login($username = '', $password = '', $imgcode = '') {
	global $_W, $_GPC;
	if (empty($username) || empty($password)) {
		$username = $_W['account']['username'];
		$password = $_W['account']['password'];
	}
	$auth['token'] = cache_load('wxauth:' . $username . ':token');
	$auth['cookie'] = cache_load('wxauth:' . $username . ':cookie');
	load()->func('communication');
	if (!empty($auth['token']) && !empty($auth['cookie']) && 0) {
		$response = ihttp_request(WEIXIN_ROOT . '/home?t=home/index&lang=zh_CN&token=' . $auth['token'], '', array('CURLOPT_REFERER' => 'https://mp.weixin.qq.com/', 'CURLOPT_COOKIE' => $auth['cookie']));
		if (is_error($response)) {
			return false;
		}
		if (strexists($response['content'], '登录超时')) {
			cache_delete('wxauth:' . $username . ':token');
			cache_delete('wxauth:' . $username . ':cookie');
		}
		return true;
	}
	$loginurl = WEIXIN_ROOT . '/cgi-bin/login?lang=zh_CN';
	$post = array(
		'username' => $username,
		'pwd' => $password,
		'imgcode' => $imgcode,
		'f' => 'json',
	);
		$code_cookie = $_GPC['code_cookie'];
	$response = ihttp_request($loginurl, $post, array('CURLOPT_REFERER' => 'https://mp.weixin.qq.com/', 'CURLOPT_COOKIE' => $code_cookie));
	if (is_error($response)) {
		return false;
	}

	$data = json_decode($response['content'], true);
	if ($data['base_resp']['ret'] == 0) {
		preg_match('/token=([0-9]+)/', $data['redirect_url'], $match);
		cache_write('wxauth:' . $username . ':token', $match[1]);
		cache_write('wxauth:' . $username . ':cookie', implode('; ', $response['headers']['Set-Cookie']));
		isetcookie('code_cookie', '', -1000);
	} else {
		$data['ErrCode'] = $data['base_resp']['ret'];
		switch ($data['ErrCode']) {
			case "-1":
				$msg = "系统错误，请稍候再试。";
				break;
			case "-23":
				$msg = "微信公众帐号或密码错误。";
				break;
			case "-3":
				$msg = "微信公众帐号密码错误，请重新输入。";
				break;
			case "-4":
				$msg = "不存在该微信公众帐户。";
				break;
			case "-5":
				$msg = "您的微信公众号目前处于访问受限状态。";
				break;
			case "-6":
				$msg = "登录受限制，需要输入验证码，稍后再试！";
				break;
			case "-7":
				$msg = "此微信公众号已绑定私人微信号，不可用于公众平台登录。";
				break;
			case "-8":
				$msg = "微信公众帐号登录邮箱已存在。";
				break;
			case "-200":
				$msg = "因您的微信公众号频繁提交虚假资料，该帐号被拒绝登录。";
				break;
			case "-94":
				$msg = "请使用微信公众帐号邮箱登陆。";
				break;
			case "10":
				$msg = "该公众会议号已经过期，无法再登录使用。";
				break;
			case "-27":
				$msg = "验证码输入错误。";
				break;
			default:
				$data['ErrCode'] = -2;
				$msg = "未知的返回。";
		}
		return error($data['ErrCode'], $msg);
	}
	return true;
}


function account_weixin_basic($username) {
	global $wechat;
	$response = account_weixin_http($username, WEIXIN_ROOT . '/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN');
	if (is_error($response)) {
		return array();
	}
	$info = array();
	preg_match('/fakeid=([0-9]+)/', $response['content'], $match);
	$fakeid = $match[1];
	$image = account_weixin_http($username, WEIXIN_ROOT . '/misc/getheadimg?fakeid=' . $fakeid);
	if (!is_error($image) && !empty($image['content'])) {
		$info['headimg'] = $image['content'];
	}
	$image = account_weixin_http($username, WEIXIN_ROOT . '/misc/getqrcode?fakeid=' . $fakeid . '&style=1&action=download');
	if (!is_error($image) && !empty($image['content'])) {
		$info['qrcode'] = $image['content'];
	}
	preg_match('/(gh_[a-z0-9A-Z]+)/', $response['meta'], $match);
	$info['original'] = $match[1];
	preg_match('/名称([\s\S]+?)<\/li>/', $response['content'], $match);
	$info['name'] = trim(strip_tags($match[1]));
	preg_match('/微信号([\s\S]+?)<\/li>/', $response['content'], $match);
	$info['account'] = trim(strip_tags($match[1]));
	preg_match('/介绍([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
	$info['signature'] = trim(strip_tags($match[2]));
	preg_match('/认证情况([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
	$temp['level'] = trim(strip_tags($match[2]));
	preg_match('/类型([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response['content'], $match);
	$temp['type'] = trim(strip_tags($match[2]));

		$info['level'] = 1;
	$is_key_secret = 1;
	if (strexists($temp['type'], '订阅号')) {
		if (strexists($temp['level'], '微信认证')) {
			$info['level'] = 3;
		}
	} elseif (strexists($temp['type'], '服务号')) {
		$info['level'] = 2;
		if (strexists($temp['level'], '微信认证')) {
			$info['level'] = 4;
		}
	}
	if ($is_key_secret == 1) {
		$authcontent = account_weixin_http($username, WEIXIN_ROOT . '/advanced/advanced?action=dev&t=advanced/dev&lang=zh_CN');
		preg_match_all("/value\:\"(.*?)\"/", $authcontent['content'], $match);
		$info['key'] = $match[1][2];
		$info['secret'] = $match[1][3];
		unset($match);
	}
	preg_match_all("/(?:country|province|city): '(.*?)'/", $response['content'], $match);
	$info['country'] = trim($match[1][0]);
	$info['province'] = trim($match[1][1]);
	$info['city'] = trim($match[1][2]);
	return $info;
}

function account_weixin_interface($username, $account) {
	global $_W;
	$response = account_weixin_http($username, WEIXIN_ROOT . '/advanced/callbackprofile?t=ajax-response&lang=zh_CN',
		array(
			'url' => $_W['siteroot'].'api.php?id='.$account['id'],
			'callback_token' => $account['token'],
			'encoding_aeskey' => $account['encodingaeskey'],
			'callback_encrypt_mode' => '0',
			'operation_seq' => '203038881',
	));
	if (is_error($response)) {
		return $response;
	}
	$response = json_decode($response['content'], true);
	if (!empty($response['base_resp']['ret'])) {
		return error($response['ret'], $response['msg']);
	}
	$response = account_weixin_http($username, WEIXIN_ROOT . '/misc/skeyform?form=advancedswitchform', array('f' => 'json', 'lang' => 'zh_CN', 'flag' => '1', 'type' => '2', 'ajax' => '1', 'random' => random(5, 1)));
	if (is_error($response)) {
		return $response;
	}
	return true;
}

function account_weixin_http($username, $url, $post = '') {
	global $_W;
	if (empty($_W['cache']['wxauth:'.$username.':token']) || empty($_W['cache']['wxauth:'.$username.':cookie'])) {
		cache_load('wxauth:'.$username.':token');
		cache_load('wxauth:'.$username.':cookie');
	}
	$auth = $_W['cache'];
	return ihttp_request($url . '&token=' . $auth['wxauth:'.$username.':token'], $post, array('CURLOPT_COOKIE' => $auth['wxauth:'.$username.':cookie'], 'CURLOPT_REFERER' => WEIXIN_ROOT . '/advanced/advanced?action=edit&t=advanced/edit&token='.$auth['wxauth:'.$username.':token']));
}

function account_weixin_userlist($pindex = 0, $psize = 1, &$total = 0) {
	global $_W;
	$url = WEIXIN_ROOT . '/cgi-bin/contactmanagepage?t=wxm-friend&lang=zh_CN&type=0&keyword=&groupid=0&pagesize='.$psize.'&pageidx='.$pindex;
	$response = account_weixin_http($_W['account']['username'], $url);
	$html = $response['content'];
	preg_match('/PageCount \: \'(\d+)\'/', $html, $match);
	$total = $match[1];
	preg_match_all('/"fakeId" : "([0-9]+?)"/', $html, $match);
	return $match[1];
}

function account_weixin_send($uid, $message = '') {
	global $_W;
	$username = $_W['account']['username'];
	if (empty($_W['cache']['wxauth'][$username])) {
		cache_load('wxauth:'.$username.':');
	}
	$auth = $_W['cache']['wxauth'][$username];
	$url = WEIXIN_ROOT . '/cgi-bin/singlesend?t=ajax-response&lang=zh_CN';
	$post = array(
		'ajax' => 1,
		'content' => $message,
		'error' => false,
		'tofakeid' => $uid,
		'token' => $auth['token'],
		'type' => 1,
	);
	$response = ihttp_request($url, $post, array(
		'CURLOPT_COOKIE' => $auth['cookie'],
		'CURLOPT_REFERER' => WEIXIN_ROOT . '/cgi-bin/singlemsgpage?token='.$auth['token'].'&fromfakeid='.$uid.'&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN',
	));
}

function account_txweibo_login($username, $password, $verify = '') {
	$cookie = cache_load("txwall:$username");
	if (!empty($cookie)) {
		$response = ihttp_request('http://t.qq.com', '', array(
			'CURLOPT_COOKIE' => $cookie,
			'CURLOPT_REFERER' => 'http://t.qq.com/',
			"User-Agent" => "Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0",
		));
		if (!strexists($response['content'], '登录框')) {
			return $cookie;
		}
	}
	$loginsign = '';

	$loginui = 'http://ui.ptlogin2.qq.com/cgi-bin/login?appid=46000101&s_url=http%3A%2F%2Ft.qq.com';
	$response = ihttp_request($loginui);
	preg_match('/login_sig:"(.*?)"/', $response['content'], $match);
	$loginsign = $match[1];
	
	$checkloginurl = 'http://check.ptlogin2.qq.com/check?uin='.$username.'&appid=46000101&r='.TIMESTAMP;
	$response = ihttp_request($checkloginurl);
	$cookie = implode('; ', $response['headers']['Set-Cookie']);
	preg_match_all("/'(.*?)'/", $response['content'], $match);
	list($needVerify, $verify1, $verify2) = $match[1];
	if (!empty($needVerify)) {
		if (empty($verify)) {
			return error(1, '请输入验证码！');
		}
		$verify1 = $verify;
		$cookie .= '; ' . cache_load('txwall:verify');
	}
	$verify2 = pack('H*', str_replace('\x', '', $verify2));
	$temp = md5($password, true);
	$temp = strtoupper(md5($temp . $verify2));
	$password = strtoupper(md5($temp . strtoupper($verify1)));
	$loginurl = "http://ptlogin2.qq.com/login?u={$username}&p={$password}&verifycode={$verify1}&login_sig={$loginsign}&low_login_enable=1&low_login_hour=720&aid=46000101&u1=http%3A%2F%2Ft.qq.com&ptredirect=1&h=1&from_ui=1&dumy=&fp=loginerroralert&g=1&t=1&dummy=&daid=6&";
	$response = ihttp_request($loginurl, '', array(
		'CURLOPT_COOKIE' => $cookie,
		'CURLOPT_REFERER' => 'http://t.qq.com/',
		"User-Agent" => "Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0",
	));
	$info = explode("'", $response['content']);
	if ($info[1] != 0) {
		return error('1', $info[9]);
	}
	$response = ihttp_request($info[5]);
	$cookie = implode('; ', $response['headers']['Set-Cookie']);
	cache_write("txwall:$username", $cookie);
	return $cookie;
}


function uni_setmeal($uniacid = 0) {
	global $_W;
	if(!$uniacid) {
		$uniacid = $_W['uniacid'];
	}
	$owneruid = pdo_fetchcolumn("SELECT uid FROM ".tablename('uni_account_users')." WHERE uniacid = :uniacid AND role = 'owner'", array(':uniacid' => $uniacid));
	if(empty($owneruid)) {
		$user = array(
			'uid' => -1,
			'username' => '创始人',
			'timelimit' => '未设置',
			'groupid' => '-1',
			'groupname' => '所有服务'
		);
		return $user;
	}
	load()->model('user');
	$groups = pdo_getall('users_group', array(), array('id', 'name'), 'id');
	$owner = user_single(array('uid' => $owneruid));
	$user = array(
		'uid' => $owner['uid'],
		'username' => $owner['username'],
		'groupid' => $owner['groupid'],
		'groupname' => $groups[$owner['groupid']]['name']
	);
	if(empty($owner['endtime'])) {
		$user['timelimit'] = date('Y-m-d', $owner['starttime']) . ' ~ 无限制' ;
	} else {
		if($owner['endtime'] <= TIMESTAMP) {
			$add = ' <strong class="text-danger"> 已到期</strong>';
		}
		$user['timelimit'] = date('Y-m-d', $owner['starttime']) . ' ~ ' . date('Y-m-d', $owner['endtime']) . $add;
	}
	return $user;
}


function uni_is_multi_acid($uniacid = 0) {
	global $_W;
	if(!$uniacid) {
		$uniacid = $_W['uniacid'];
	}
	$cachekey = "unicount:{$uniacid}";
	$nums = cache_load($cachekey);
	$nums = intval($nums);
	if(!$nums) {
		$nums = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('account_wechats') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
		cache_write($cachekey, $nums);
	}
	if($nums == 1) {
		return false;
	}
	return true;
}

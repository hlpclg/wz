<?php
/**
 * [WeiZan System] Copyright (c) 2014 012wz.com
 * WeiZan is  a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class Abc_newsModuleSite extends WeModuleSite {

	public function doMobileDetail() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('news_reply') . " WHERE `id`=:id";
		$row = pdo_fetch($sql, array(':id'=>$id));
		if (!empty($row['url'])) {
			header("Location: ".$row['url']);
		}
		$row = istripslashes($row);
		$row['content'] = formot_content($row['content']);
		$row['title'] = formot_content($row['title']);
		$row['description'] = formot_content($row['description']);
		if($_W['os'] == 'android' && $_W['container'] == 'wechat' && $_W['account']['account']) {
			$subscribeurl = "weixin://profile/{$_W['account']['account']}";
		} else {
			$sql = 'SELECT `subscribeurl` FROM ' . tablename('account_wechats') . " WHERE `acid` = :acid";
			$subscribeurl = pdo_fetchcolumn($sql, array(':acid' => intval($_W['acid'])));
		}
		include $this->template('detail');
	}
}

function formot_content($content = ''){
	global $_W;
	if(empty($content)){
		return $content;
	}
	load()->model('mc');
	$user = mc_fetch($_W['member']['uid']);
	$replace = pdo_fetchall("SELECT * FROM ".tablename('abc_replace')." WHERE uniacid = :uniacid ",array(':uniacid'=>$_W['uniacid']));
	//print_r($replace);
	foreach ($replace as $re){
		$content = str_replace($re['replace'], $user[$re['name']], $content);
	}
	return $content;
}
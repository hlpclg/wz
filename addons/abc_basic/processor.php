<?php
/**
 * 文字回复模块处理程序
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Abc_basicModuleProcessor extends WeModuleProcessor {
	public function respond() {
		checkauth();
		$sql = "SELECT * FROM " . tablename('basic_reply') . " WHERE `rid` IN ({$this->rule})  ORDER BY RAND() LIMIT 1";
		$reply = pdo_fetch($sql);
		$reply['content'] = htmlspecialchars_decode($reply['content']);
		$reply['content'] = str_replace(array('<br>', '&nbsp;'), array("\n", ' '), $reply['content']);
		$reply['content'] = strip_tags($reply['content'], '<a>');
		//替换要替换的内容
		$reply['content'] = formot_content($reply['content']);
		return $this->respText($reply['content']);
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
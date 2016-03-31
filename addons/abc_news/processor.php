<?php
/**
 * 新版图文回复模块处理程序
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Abc_newsModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;		
		$sql = "SELECT * FROM " . tablename('news_reply') . " WHERE rid = :id ORDER BY displayorder DESC, id ASC LIMIT 8";
		$commends = pdo_fetchall($sql, array(':id'=>$rid));
		$news = array();
		foreach($commends as $c) {
			$row = array();
			$row['title'] = formot_content($c['title']);
			$row['description'] = formot_content($c['description']);
			!empty($c['thumb']) && $row['picurl'] = tomedia($c['thumb']);
			$row['url'] = empty($c['url']) ? $this->createMobileUrl('detail', array('id' => $c['id'])) : $c['url'];
			$news[] = $row;
		}
		return $this->respNews($news);
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
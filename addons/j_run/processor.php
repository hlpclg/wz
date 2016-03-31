<?php
/**
 * 捷讯约跑模块处理程序
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_runModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$reply = pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid = :rid", array(':rid' => $this->rule));
		if (!empty($reply)) {
			$rid=$this->rule;
			$response[] = array(
				'title' => $reply['title'],
				'description' => $reply['description'],
				'picurl' => $_W['attachurl'].$reply['cover'],
				'url' => $this->createMobileUrl('index',array('rid'=>$rid,'r'=>TIMESTAMP)),
			);
			return $this->respNews($response);
		}
	}
}
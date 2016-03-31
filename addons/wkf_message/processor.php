<?php
defined('IN_IA') or exit('Access Denied');

class Wkf_messageModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('message_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if($row==false){
			return ;
		}
		return $this->respNews(array(
			'Title' => $row['title'],
			'Description' => $row['description'],
			'PicUrl' => empty($row['thumb'])?'':$_W['attachurl'].$row['thumb'],
			'Url' => $this->createMobileUrl('list', array('id' => $rid)),
		));
	}
}
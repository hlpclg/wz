<?php

class CustomMsg
{
	private static $t_msg = 'stat_msg_history';

	public function sendText($weid, $openid, $msg)
	{
		yload()->classs('quick_center', 'wechatapi');
		$_wechatapi = new WechatAPI();
		$ret = $_wechatapi->sendText($openid, $msg);
		$this->addCustomMsg($weid, $openid, $msg);
		return $ret;
	}

	private function addCustomMsg($weid, $from_user, $msg)
	{
		pdo_insert(self::$t_msg, array('uniacid' => $weid, 'rid' => 0, 'kid' => 0, 'from_user' => $from_user, 'module' => 'default', 'message' => $msg, 'type' => 'custom', 'createtime' => time()));
	}

	public function getCustomMsg($weid, $from_user, $limit = 1000)
	{
		return pdo_fetchall('SELECT * FROM ' . tablename(self::$t_msg) . ' WHERE uniacid=:weid AND from_user=:from_user AND message <> \'\' ORDER BY id DESC LIMIT ' . $limit, array(':weid' => $weid, ':from_user' => $from_user));
	}
}
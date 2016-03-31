<?php
/**
 * 真心话朋友圈模块订阅器
*
* @author 小义
* @url http://bbs.012wz.com/
*/
defined('IN_IA') or exit('Access Denied');

class Enjoy_circleModuleReceiver extends WeModuleReceiver {
	public function receive() {
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看www.zheyitianShi.Com文档来编写你的代码
		$type = $this->message['type'];
		if($this->message['event'] == 'unsubscribe') {
			pdo_update('enjoy_circle_fans', array(
			'subscribe' => 0,
			'subscribe_time' => '',
			), array('wopenid' => $this->message['fromusername'],'uniacid' => $GLOBALS['_W']['uniacid']));
		}elseif($this->message['event'] == 'subscribe') {
			pdo_update('enjoy_circle_fans', array(
			'subscribe' => 1,
			'subscribe_time' => time(),
			), array('wopenid' => $this->message['fromusername'],'uniacid' => $GLOBALS['_W']['uniacid']));
		
		}
	}
}
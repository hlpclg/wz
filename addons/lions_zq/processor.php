<?php
/**
 * 月饼武道会模块处理程序
 *
 * @author AndyLions
 * @url http://www.haobama.net/
 */
defined('IN_IA') or exit('Access Denied');

class Lions_zqModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
	}
}
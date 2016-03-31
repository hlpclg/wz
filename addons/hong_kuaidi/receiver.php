<?php
/**
 * 智能快递（带广告位）模块订阅器
 *
 * @author 微标志
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Hong_kuaidiModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看www.zheyitianShi.Com文档来编写你的代码
	}
}
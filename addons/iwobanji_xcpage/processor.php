<?php
/**
 * 官方示例模块处理程序
 *
 * @author www.zheyitianShi.Com团队
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Iwobanji_xcpageModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
		return $this->respText($content);
	}
}
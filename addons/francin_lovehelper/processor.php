<?php
/**
 * 帮TA传情模块处理程序
 *
 * @author 刘星
 * @url http://www.xingdong001.com
 */
defined('IN_IA') or exit('Access Denied');

class LovehelperModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
}
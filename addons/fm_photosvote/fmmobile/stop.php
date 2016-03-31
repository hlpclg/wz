<?php
/**
 * 女神来了模块定义
 *
 * @author www.zheyitianShi.Com科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		$status = $_GPC['status'];
		if ($status == '-1') {
			$title = $reply['title'] . ' 即将开始哦 - ';
			$stopbg = toimage($reply['nostart']);
		}elseif ($status == '0') {
			$title = $reply['title'] . ' 暂停中哦 - ';
			$stopbg = toimage($reply['stopping']);
		}elseif ($status == '1') {
			$title = $reply['title'] . ' 已经停止了，期待下一次吧！';
			$stopbg = toimage($reply['end']);
		}
		$toye = $this->_stopllq('stop');
		include $this->template($toye);
	
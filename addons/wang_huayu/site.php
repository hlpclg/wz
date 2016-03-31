<?php
/**
 * 花语模块微站定义
 *
 * @author 王健同學
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Wang_huayuModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面		
		include $this->template('index');
	}

}
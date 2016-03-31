<?php
/**
 * 帮TA传情模块定义
 *
 * @author 刘星
 * @url http://www.xingdong001.com
 */
defined('IN_IA') or exit('Access Denied');

class LovehelperModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
    
		if(checksubmit()) {
			$this->saveSettings($data);
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}
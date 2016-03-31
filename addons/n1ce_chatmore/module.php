<?php
/**
 * 多回复推送模块定义
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class N1ce_chatmoreModule extends WeModule {
	
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(
                'one' => $_GPC['one'],
				'two' => $_GPC['two'],
				'three' => $_GPC['three'],
                'four' => $_GPC['four'],      
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		
		include $this->template('setting');
	}

}
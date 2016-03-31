<?php
/**
 * 关注送积分模块定义
 *
 * @author 别具一格
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Ju_creditModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$creditnames = uni_setting($_W['uniacid'], array('creditnames'));
		if($creditnames) {
			foreach($creditnames['creditnames'] as $index=>$creditname) {
				if($creditname['enabled'] == 0) {
					unset($creditnames['creditnames'][$index]);
				}
			}
			$scredit = implode(', ', array_keys($creditnames['creditnames']));
		} else {
			$scredit = '';
		}
		if(checksubmit()) {
			$cfg = array(
                'sub_type' => $_GPC['sub_type'],
                'sub_num' => $_GPC['sub_num'],
                'unsub_type' => $_GPC['unsub_type'],
                'unsub_num' => $_GPC['unsub_num'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}
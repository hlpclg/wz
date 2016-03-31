<?php
/**
 * 敲钟上市
 *
 * @author ewei
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_shangshiModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit('submit')) {
			$dat['followurl'] = $_GPC['followurl'];
                                                      $dat['copyright'] = $_GPC['copyright'];
                                                      $dat['followneed'] = intval($_GPC['followneed']);
			$this->saveSettings($dat);
			message('配置参数更新成功！', referer(), 'success');
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}
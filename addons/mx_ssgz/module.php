
<?php
/**
 * 手撕鬼子模块定义
 *
 * @author 网络
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Mx_ssgzModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$settingdata = $_GPC['settings'];
            if ($this->saveSettings($settingdata)) {
                message('保存成功', 'refresh');
            }
		}
		if (empty($_W['token'])) {
            $settings['s_title'] = '小鬼子哪里跑？';
            $settings['s_content'] = '我撕了30个小鬼子？';
            $settings['s_img'] = $_W['siteroot'].'addons/mx_nanshen/icon.jpg';
        }
		//这里来展示设置项表单
		load()->func('tpl');
		include $this->template('setting');
	}

}
?>
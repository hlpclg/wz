<?php

/**
 * 商户管理模块定义
 *
 * @author www.zheyitianShi.Com团队
 * @url http://bbs.012wz.com/forum.php?mod=forumdisplay&fid=36&filter=typeid&typeid=1
 */
defined('IN_IA') or exit('Access Denied');

class We7_businessModule extends WeModule {

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit('submit')) {
            $cfg = array(
                'range' => intval($_GPC['range']),
                'info' => $_GPC['info']
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        if (!isset($settings['range'])) {
            $settings['range'] = '5';
        }
        include $this->template('setting');
    }

}

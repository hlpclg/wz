<?php
/**
 * 微商圈

 *
 */

defined('IN_IA') or exit('Access Denied');
include "../addons/weisrc_businesscenter/model.php";

class weisrc_businesscenterModule extends WeModule
{
    public $modulename = 'weisrc_businesscenter';
    public $_debug = '1';

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if(checksubmit()) {
            $cfg = array();
            $cfg['appid'] = $_GPC['appid'];
            $cfg['secret'] = $_GPC['secret'];
            if($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }
}
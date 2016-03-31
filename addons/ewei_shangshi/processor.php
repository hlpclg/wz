<?php
/**
 * 敲钟上市
 *
 * @author ewei
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_shangshiModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $this->module['config']['picurl'] = $_W['attachurl'] . $this->module['config']['picurl'];
        return $this->respNews($this->module['config']);
    }

}

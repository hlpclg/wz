<?php
/**
 * 牛郎追织女模块微站定义
 *
 * @author lonaking
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Lonaking_niulangflyModuleSite extends WeModuleSite {

    /**
     * 玩游戏主页
     */
	public function doMobilePlay() {
	    global $_W, $_GPC;

	    $html = array(
	        'jsconfig' => $_W['account']['jssdkconfig'],
	        'web_config' => $this->module['config'],
	        'share_logo' => $_W['attachurl'].$this->module['config']['share_pic'],
	    );
	    
	    
	    include $this->template('index');
	}

}
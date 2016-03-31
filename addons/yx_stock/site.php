
<?php
/**
 * 炒股票模块微站定义
 *
 * @author 瘦干干
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Yx_stockModuleSite extends WeModuleSite {

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
	    
	    
	    include $this->template('game');
	}

}
?>
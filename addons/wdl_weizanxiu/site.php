<?php
/**
 * www.zheyitianShi.Com秀模块微站定义
 *
 * @author 800083075
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class wdl_weizanxiuModuleSite extends WeModuleSite {
	public function doWebindex() {
	    global $_W, $_GPC;
	    include $this->template('index');
	}

}
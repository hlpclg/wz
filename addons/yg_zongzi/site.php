<?php
/**
 * 吃种子模块微站定义
 *
 * @author 宇光
 * @url http://bbs.weihezi.cc/
 */


defined('IN_IA') or exit('Access Denied');
load()->func('communication');
class Yg_zongziModuleSite extends WeModuleSite {



  
	
	public function doMobileImport() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC,$_W;
		
	    include $this->template('index');
	}

}
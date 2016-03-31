<?php
/**
 * 扫码看视频模块微站定义
 *
 * @author gmega
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Scan_videoModuleSite extends WeModuleSite {

	public function doMobileCover1() {
		//这个操作被定义用来呈现 功能封面
	}
	public function doMobileCover2() {
		//这个操作被定义用来呈现 功能封面
	}
	public function doWebRule1() {
		//这个操作被定义用来呈现 规则列表
	}
	public function doWebRule2() {
		//这个操作被定义用来呈现 规则列表
	}
	public function doWebYkAccount() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	// public function doWebDptManage() {
	// 	include $this->template('departments');
	// }
	// public function doWebVideoManage() {
	// 	//这个操作被定义用来呈现 管理中心导航菜单
	// }
	public function doWebInfo() {
		//这个操作被定义用来呈现 管理中心导航菜单
		include $this->template('info');
	}
	public function doMobileHome1() {
		//这个操作被定义用来呈现 微站首页导航图标
	}
	public function doMobileHome2() {
		//这个操作被定义用来呈现 微站首页导航图标
	}
	public function doMobileProfile1() {
		//这个操作被定义用来呈现 微站个人中心导航
	}
	public function doMobileProfile2() {
		//这个操作被定义用来呈现 微站个人中心导航
	}
	public function doMobileShortcut1() {
		//这个操作被定义用来呈现 微站快捷功能导航
	}
	public function doMobileShortcut2() {
		//这个操作被定义用来呈现 微站快捷功能导航
	}

}
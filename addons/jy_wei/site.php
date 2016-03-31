<?php
defined('IN_IA') or exit('Access Denied');

class Jy_weiModuleSite extends WeModuleSite {

	// 载入逻辑方法
	private function getLogic($_name,$type="web",$auth=false){
		global $_W,$_GPC;
		if($type=='web'){
			$this->checkLogin();
			include_once 'inc/web/'.strtolower(substr($_name,5)).'.php';
		}
		else if($type=='mobile'){
			if($auth){
				$this->checkAuth();
				include_once 'inc/func/isauth.php';
			}
			include_once 'inc/mobile/'.strtolower(substr($_name,8)).'.php';
			// 添加日志
			include_once 'inc/func/log.php';
			// 结束添加日志
		}else if($type=='func'){
			$this->checkAuth();
			include_once 'inc/func/'.strtolower(substr($_name,8)).'.php';
		}
	}

	// 授权验证
	public function doMobileAuth(){
		$this->getLogic(__FUNCTION__,'func');
	}

	// 手机端首页
	public function doMobileIndex(){
		$this->getLogic(__FUNCTION__,'mobile',true);
	}

	// 手机端职位页面
	public function doMobilePosition(){
		$this->getLogic(__FUNCTION__,'mobile',true);
	}

	// 公司设置
	public function doWebCompany(){
		$this->getLogic(__FUNCTION__,'web');
	}

	// 标签管理
	public function doWebLabel(){
		$this->getLogic(__FUNCTION__,'web');
	}

	// 关键字管理
	public function doWebKeyword(){
		$this->getLogic(__FUNCTION__,'web');
	}

	// 职位
	public function doWebPosition(){
		$this->getLogic(__FUNCTION__,'web');
	}

	// 个人中心
	public function doWebUser(){
		$this->getLogic(__FUNCTION__,'web');
	}

	// 统计中心
	public function doWebLog(){
		$this->getLogic(__FUNCTION__,'web');
	}

}
<?php
/**
 * 粽子大战模块微站定义
 *
 * @author 步飞凌云
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Zzplane_hhyModuleSite extends WeModuleSite {

	public function doMobileFrom() {
		global $_W, $_GPC;
		$yobyurl = "http://".$_SERVER['HTTP_HOST']."/addons/zzplane_hhy";
		$attachurl = $_W['attachurl'];
		$settings=$this->module['config'];
		//这个操作被定义用来呈现 功能封面
        include $this->template('index');
	}
	public function doWebSetting() {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		$settings=$this->module['config'];
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$data = array(
				'share_title'	=>	$_GPC['share_title'],
				'share_desc'	=>	$_GPC['share_desc'],
				'share_img'	=>	$_GPC['share_img'],
				'yindao_title'	=>	$_GPC['yindao_title'],
				'yindao_link'	=>	$_GPC['yindao_link'],
			);
			if($this->saveSettings($data)){
				message('保存成功', 'refresh');
			}
		}
			load()->func('tpl');
        include $this->template('setting');
		//这个操作被定义用来呈现 管理中心导航菜单
	}

}
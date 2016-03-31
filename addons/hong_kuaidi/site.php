<?php
/**
 * 智能快递（带广告位）模块微站定义
 *
 * @author 微标志
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Hong_kuaidiModuleSite extends WeModuleSite {

	public function doWebAddrule() {
		//这个操作被定义用来呈现 规则列表
	}
	public function doWebAds() {
		global $_W, $_GPC;
		$settings=$this->module['config'];
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$data = array(
				'kuaidi_title'	=>	$_GPC['kuaidi_title'],
				'kuaidi_url'	=>	$_GPC['kuaidi_url'],
				'kuaidi_img'	=>	$_GPC['kuaidi_img']
			);
			if($this->saveSettings($data)){
				message('保存成功', 'refresh');
			}
		}
			load()->func('tpl');
        include $this->template('setting');
	}

}
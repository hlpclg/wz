<?php /*折翼天使资源社区 www.zheyitianshi.com*/
/**
 * 微论坛模块定义
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_bbsModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$setting = $this->module['config'];
		
		$path = IA_ROOT . '/addons/meepo_bbs/template/mobile/';
		if (is_dir($path)) {
			$apis = array();
			if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$stylesResults[] = $file;
					}
				}
			}
		}
		foreach ($stylesResults as $item){
			if(file_exists($path.$item.'/preview.png')){
				$stylesResult[] = $item;
			}else{
				
			}
		}
		
		if(!empty($_GPC['name'])){
			$dat = array();
			$dat['name'] = $_GPC['name'];
			$this->saveSettings($dat);
			message('模板设置成功',referer(),'success');
		}
		
		//这里来展示设置项表单
		include $this->template('settings');
	}
}
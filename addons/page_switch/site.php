<?php
/**
 * 红包模块数据库管理
 *
 * @author Gorden
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Page_switchModuleSite extends WeModuleSite {

	public function doMobileindex(){
		$num=$this->module['config']['num'];
		$pictures=json_decode($this->module['config']['pictures']);
		include $this->template('index');

	}
	public function doWebUpload() {
		global $_W, $_GPC;
		$ops = array('display', 'edit', 'delete'); // 只支持此 3 种操作.
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		$num=$this->module['config']['num'];
		if (checksubmit()) {
			$data = $_GPC['picture'];
			
			$pictures=array();
			for($i=0;$i<$num;$i++){
				if(empty($data[$i])){
					message('部分图片未上传！！','','error');
					break;
				}
				$pictures[$i]=$data[$i];
			}
			
			$this->module['config']['pictures']=json_encode($pictures);
			if (!$this->saveSettings($this->module['config'])) {
				message('保存信息失败','','error');   // 保存失败
			} else {
				message('保存信息成功','','success'); // 保存成功
			}
		}
		load()->func('tpl');
		$picture=json_decode($this->module['config']['pictures']);
		include $this->template('upload');
	}
}
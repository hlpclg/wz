<?php
/**
 * 大逃杀模块微站定义
 *
 * @author 借捏
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Sinrch_dataoshaModuleSite extends WeModuleSite {

	public function doWebSetting() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		load()->func('tpl');
		$subject = pdo_fetch("SELECT * FROM ".tablename(sinrch_dataosha_setting)." WHERE weid = '{$weid}' ORDER BY id DESC LIMIT 1");
		if (checksubmit()) {
			$data = array(
				'subscribe_num' => $_GPC['subscribe_num'],
				'subscribe_skill' => $_GPC['subscribe_skill'],
				'subscribe_url' => $_GPC['subscribe_url'],
				'share_title' => $_GPC['share_title'],
				'share_desc' => $_GPC['share_desc'],
				'photo' => $_GPC['photo']
			);
			if(empty($subject)){
				$data['weid'] = $weid;
				pdo_insert(sinrch_dataosha_setting, $data);	
			}else{
              pdo_update(sinrch_dataosha_setting, $data, array('weid' => $weid));
			}
            message('更新成功！', referer(), 'success');
		}
		include $this->template('setting');
	}
	public function doMobileBattle(){
		global $_W, $_GPC;
		load()->func('tpl');
			$sql="SELECT * FROM ".tablename(sinrch_dataosha_setting)." WHERE weid = '{$_W['uniacid']}'";
			$arr = pdo_fetchall($sql);
			$subscribe_num=$arr['0']['subscribe_num'];
			$subscribe_skill=$arr['0']['subscribe_skill'];
			$subscribe_url=$arr['0']['subscribe_url'];
			$share_desc=$arr['0']['share_desc'];
			$share_title=$arr['0']['share_title'];
			$photo=$arr['0']['photo'];
			$data = array(
				'subscribe_num' => $subscribe_num+1
			);
			$weid=$_W['uniacid'];
			pdo_update(sinrch_dataosha_setting, $data, array('weid' => $weid));
		include $this->template('index');
	}
}
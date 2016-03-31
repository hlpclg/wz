<?php
/**
 * meepo微相亲模块定义
 *
 * @author meepo_zam
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_weixiangqinModule extends WeModule {
	
	public function ruleDeleted($rid = 0) {
		//pdo_delete($this->tablename, array('rid' => $rid));
	}
	 public function settingsDisplay($settings) {
		global $_GPC, $_W;
		load()->func('tpl');
		if(checksubmit()) {
           
			$cfg = array();
			$cfg['appid'] = $_GPC['appid'];
			$cfg['secret'] = $_GPC['secret'];
			$cfg['bilv'] = intval($_GPC['bilv']);//充值比率
			$cfg['picnum'] = intval($_GPC['picnum']);
			$cfg['isstatus']=intval($_GPC['isstatus']);
			$cfg['sharenum'] = intval($_GPC['sharenum']);
			$cfg['kefuimg'] = $_GPC['kefuimg'];
			/******增加首页图文配置函数****/
			$cfg['title'] = $_GPC['title'];
			$cfg['description'] = $_GPC['description'];
			$cfg['picurl'] = $_GPC['picurl'];
			$cfg['huodongtitle'] = $_GPC['huodongtitle'];
			$cfg['huodongurl'] = $_GPC['huodongurl'];
			$cfg['huodongpicurl'] = $_GPC['huodongpicurl'];
			$cfg['kefuphone'] = $_GPC['kefuphone'];
			//增加首页菜单项自定义
			$cfg['firstcard'] = $_GPC['firstcard'];
			$cfg['secondcard'] = $_GPC['secondcard'];
			$cfg['thirdcard'] = $_GPC['thirdcard'];
			
            $cfg['fourcard'] = $_GPC['fourcard'];
			$cfg['fivecard'] = $_GPC['fivecard'];
			$cfg['yingcang'] = intval($_GPC['yingcang']);
			$cfg['maxnum'] = intval($_GPC['maxnum']);
			$cfg['accounterweima'] = $_GPC['accounterweima'];
			$cfg['awardjifen'] = intval($_GPC['awardjifen']);
			$cfg['jifenurl'] = $_GPC['jifenurl'];
            $cfg['telephoneconfirm'] = intval($_GPC['telephoneconfirm']);
			$cfg['smsuid'] = $_GPC['smsuid'];
			$cfg['smskey'] = $_GPC['smskey'];
			$cfg['lowurl'] = $_GPC['lowurl'];
            $cfg['chatpay'] = intval($_GPC['chatpay']);
			$cfg['tuijiannum'] = intval($_GPC['tuijiannum']);
			$cfg['baoyue'] = intval($_GPC['baoyue']);
			$cfg['guize'] = $_GPC['guize'];
			$cfg['tjstatus'] = intval($_GPC['tjstatus']);
			$cfg['tjjifen'] = intval($_GPC['tjjifen']);
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		
		if(!isset($settings['sharenum'])) {
			$settings['sharenum'] = 3;
		}
		if(!isset($settings['isstatus'])) {
			$settings['isstatus'] = 1;
		}
		if(!isset($settings['bilv'])) {
			$settings['bilv'] = 100;
		}
		if(!isset($settings['huodongtitle'])) {
			$settings['huodongtitle'] = '交友';
		}
		
		
		if(!isset($settings['yingcang'])) {
			$settings['yingcang'] = 1;
		}
		if(!isset($settings['telephoneconfirm'])) {
			$settings['telephoneconfirm'] = 0;
		}
		
		
		
		load()->func('tpl');
		include $this->template('setting');
	}
}
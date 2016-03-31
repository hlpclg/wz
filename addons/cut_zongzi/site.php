<?php
/**
 * 切粽子模块微站定义
 *
 * @author desmond
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Cut_zongziModuleSite extends WeModuleSite {

	public function doWebRecords(){
		global $_GPC,$_W;
		$_accounts = $accounts = uni_accounts();
		load()->model('mc');
		if(empty($accounts) || !is_array($accounts) || count($accounts) == 0){
			message('请指定公众号');
		}
		if(!isset($_GPC['acid'])){
			$account = array_shift($_accounts);
			if($account !== false){
				$acid = intval($account['acid']);
			}
		} else {
			$acid = intval($_GPC['acid']);
			if(!empty($acid) && !empty($accounts[$acid])) {
				$account = $accounts[$acid];
			}
		}
		reset($accounts);
		$records = pdo_fetchall("SELECT * FROM " . tablename('cut_zongzi_billboard') . " WHERE `uniacid`=:uniacid ORDER BY `score` DESC",array(':uniacid'=>$_W['uniacid']));
		if(!empty($records)){
			foreach ($records as $key => $item) {
				$records[$key]['user'] = mc_fansinfo($item['openid'],$acid, $_W['uniacid']);
			}
		}
		include $this->template('records');
	}

	public function doMobileBillboard(){
		global $_GPC,$_W;
		$hasExists = pdo_fetch("SELECT * FROM ".tablename('cut_zongzi_billboard')." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_GPC['openid']));
		if($hasExists && ($hasExists['score'] < $_GPC['score'])){
			pdo_update('cut_zongzi_billboard', array('score'=>$_GPC['score']), array('uniacid'=>$_W['uniacid'],'openid'=>$_GPC['openid']));
		}else{
			pdo_insert('cut_zongzi_billboard', array('uniacid'=>$_W['uniacid'],'openid'=>$_GPC['openid'],'score'=>$_GPC['score']));
		}
		$record = pdo_fetch("SELECT * FROM ".tablename('cut_zongzi_billboard')." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_GPC['openid']));
		$record['cur_score'] = $_GPC['score'];
		$record['top_score'] = pdo_fetchcolumn("SELECT `score` FROM ".tablename('cut_zongzi_billboard')." WHERE `uniacid`=:uniacid ORDER BY `score` DESC LIMIT 1",array('uniacid'=>$_W['uniacid']));
		$records = pdo_fetchall("SELECT * FROM " . tablename('cut_zongzi_billboard') . " WHERE `uniacid`=:uniacid ORDER BY `score` DESC LIMIT 10",array(':uniacid'=>$_W['uniacid']));
		$str = '';
		if($records){
			foreach ($records as $key => $item) {
				$userinfo = mc_fansinfo($item['openid']);
				$nickname = empty($userinfo)? '匿名' : $userinfo['nickname'];
				$str .= '<tr  style="height:30px;"><td>'.$nickname.'</td><td>'.$item['score'].'</td></tr>';
			}
		}
		$record['billboard'] = $str;
		echo json_encode($record);
		exit;
	}

	public function doMobileRule1() {
		global $_W,$_GPC;
		$setting = pdo_fetchcolumn("SELECT `setting_value` FROM " . tablename('cut_zongzi_settings') . " WHERE `uniacid`=:uniacid AND `setting_key`='require_subscribe'",array(':uniacid'=>$_W['uniacid']));
		$judge['require'] = false;
		if($setting && $setting == 1){
			$judge['require'] = true;
			$judge['subscribe'] = $this->requreSubscribe();
			$judge['thumb'] = pdo_fetchcolumn("SELECT `setting_value` FROM " . tablename('cut_zongzi_settings') . " WHERE `uniacid`=:uniacid AND `setting_key`='thumb'",array(':uniacid'=>$_W['uniacid']));
		}
		include $this->template('index');
	}

	private function requreSubscribe(){
		global $_W;
		$hasSubscribe = 1;
		if(isset($_W['fans']['from_user']) && !empty($_W['fans']['from_user'])){
			$openid = $_W['fans']['from_user'];
			$userinfo = mc_fansinfo($openid);
			if(!$userinfo || ($userinfo['follow']==0)){
				$hasSubscribe = 0;
			}
		}else{
			$oauthAccount = $_W['oauth_account'];
			if(empty($oauthAccount)){
				message('未指定网页授权公众号, 无法获取用户信息.','','error');
			}
			$userinfo = mc_oauth_userinfo();
			$level = pdo_fetchcolumn("SELECT `level` FROM ".tablename('account_wechats')." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
			
			if($level == 4){
				if($userinfo['subscribe'] == 0){
					$hasSubscribe = 0;
				}
			}else{
				$unionid = isset($userinfo['unionid']) ? $userinfo['unionid'] : '';
				
				if(empty($unionid)){
					message('获取unionid失败,请确认公众号已接入微信开放平台','','error');
				}
				$fieldsExist = pdo_fieldexists('mc_mapping_fans','unionid');
				if(!$fieldsExist){
					pdo_query("ALTER TABLE ".tablename('mc_mapping_fans')." ADD column unionid varchar(255) default null");
				}
				$openid = pdo_fetchcolumn("SELECT `openid` FROM ".tablename('mc_mapping_fans'). " WHERE `unionid`=:unionid AND `uniacid`=:uniacid ", array(':unionid'=>$unionid, ':uniacid'=>$_W['account']['uniacid']));
				if(empty($openid)){
					$hasSubscribe = 0;
				}else{
					$userinfo = mc_fansinfo($openid);
					if(!$userinfo || ($userinfo['follow']==0)){
						$hasSubscribe = 0;
					}
				}
				
			}
		}

		
		return array('openid'=>$openid,'subscribe'=>$hasSubscribe);
	}
	public function doWebSetting() {
		global $_W,$_GPC;
		if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'require'){
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_key'] = 'require_subscribe';
			$data['setting_value'] = $_GPC['require'] == 1 ? 1 : 0;
			if($data['setting_value'] == 1){
				$fieldsExist = pdo_fieldexists('mc_mapping_fans','unionid');
				if(!$fieldsExist){
					pdo_query("ALTER TABLE ".tablename('mc_mapping_fans')." ADD column unionid varchar(255) default null");
				}
			}
			pdo_query("DELETE FROM ".tablename('cut_zongzi_settings')." WHERE `uniacid`=:uniacid AND `setting_key`='require_subscribe'",array(':uniacid'=>$_W['uniacid']));
			pdo_insert('cut_zongzi_settings', $data);
			echo pdo_insertid();
			exit;
		}elseif(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'thumb'){
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_key'] = 'thumb';
			$data['setting_value'] = $_GPC['thumb'];
			
			pdo_query("DELETE FROM ".tablename('cut_zongzi_settings')." WHERE `uniacid`=:uniacid AND `setting_key`='thumb'",array(':uniacid'=>$_W['uniacid']));
			pdo_insert('cut_zongzi_settings', $data);
			echo pdo_insertid();
			exit;
		}
		
		$levels = array('1'=>'订阅号','2'=>'服务号','3'=>'认证订阅号','4'=>'认证服务号');
		$level = pdo_fetchcolumn("SELECT `level` FROM ".tablename('account_wechats')." WHERE `uniacid`=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		$setting['level'] = $levels[$level];
		$settings = pdo_fetchall("SELECT * FROM ".tablename('cut_zongzi_settings')." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
		if(!empty($setting)){
			foreach ($settings as $key => $item) {
				$setting[$item['setting_key']] = $item['setting_value'];
			}
		}
		load()->func('tpl');
		include $this->template('settings');
	}


}
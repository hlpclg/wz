<?php
/**
 * 全民总动员模块订阅器
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_fenModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
		$type = $this->message['type'];
		$uniacid = $_W['uniacid'];//统一公众账号
		$acid = $_W['acid'];//子公众账号
		$from = $this->message['from'];
			
		if(empty($acid)){
			$acid = pdo_fetchcolumn("SELECT acid FROM ".tablename('mc_mapping_fans')." WHERE openid = '{$from}'");
		}
		$accounts = uni_accounts();
		if(!empty($acid) && !empty($accounts[$acid])) {
			$account = $accounts[$acid];
		}
		checkauth();
		$uid = $_W['member']['uid'];//uid email mobile
		if($account['level'] > 1){
			$acc = WeAccount::create($acid);
			$fan = $acc->fansQueryInfo($from, true);
		}else{
			$fan = array();
		}
		if(!empty($fan)) {
			$data['nickname'] = $fan['nickname'];
			$data['gender'] = $fan['sex'];
			$data['residecity'] = $fan['city'] ? $fan['city'] . '市' : '';
			$data['resideprovince'] = $fan['province'] ? $fan['province'] . '省' : '';
			$data['nationality'] = $fan['country'];
			$data['avatar'] = rtrim($fan['headimgurl'], '0') . 132;
			pdo_update('mc_members',$data,array('uid'=>$uid));
		}
	}
}
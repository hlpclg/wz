<?php
/**
 * 关注送积分模块订阅器
 *
 * @author 别具一格
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Ju_creditModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
		load()->model('mc');
		$type = $this->message['type'];
		$event = $this->message['event'];
		$openid = $this->message['from'];
		$config = $this->module['config'];
		$acc = WeAccount::create($_W['acid']);
		if ($event == 'subscribe' && $config['sub_num'] != 0 && !empty($config['sub_type'])) {
			$log = pdo_fetch("SELECT id FROM ".tablename('ju_credit_log')." WHERE uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
			if (empty($log)) {
				$uid = mc_openid2uid($openid);
				$result = mc_credit_update($uid,$config['sub_type'],$config['sub_num'],array('0'=>'1','1'=>'关注平台奖励'));
				if ($result) {
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'openid' => $openid,
						'subscribetime' => time(),
						'unsubscribetime' => 0,
						'follow' => 1,
						);
					pdo_insert('ju_credit_log', $insert);
					$this->sendText($acc,$this->message['from'],'感谢您的关注，赠送您'.$config['sub_num'].'！');
				}
			}else{
				pdo_update('ju_credit_log', array('follow'=>1,'subscribetime'=>time()), array('id'=>$log['id']));
			}
		}elseif ($event == 'unsubscribe' && $config['unsub_num'] != 0) {
			$log = pdo_fetch("SELECT id FROM ".tablename('ju_credit_log')." WHERE uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
			$uid = mc_openid2uid($openid);
			if (empty($log)) {
				$result = mc_credit_update($uid,$config['unsub_type'],'-'.$config['unsub_num'],array('0'=>'1','1'=>'取消关注平台扣除'));
				if ($result) {
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'openid' => $openid,
						'subscribetime' => 0,
						'unsubscribetime' => time(),
						'follow' => 0,
						);
					pdo_insert('ju_credit_log', $insert);
				}
			}else{
				$result = mc_credit_update($uid,$config['unsub_type'],'-'.$config['unsub_num'],array('0'=>'1','1'=>'取消关注平台扣除'));
				pdo_update('ju_credit_log', array('follow'=>0,'subscribetime' => 0,'unsubscribetime'=>time()), array('id'=>$log['id']));
			}
		}
		
	}

	private function sendText($acc,$openid,$content){
		$send['touser'] = trim($openid);
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($content));
		$data = $acc->sendCustomNotice($send);
		return $data;
	}
}
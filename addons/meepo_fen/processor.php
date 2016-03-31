<?php
/**
 * 全民总动员模块处理程序
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('INC_PATH',IA_ROOT.'/addons/meepo_fen/inc/');
include INC_PATH.'core/class/mload.class.php';
mload()->func('common');
load()->model('activity');
define('MB_ROOT', str_replace('processor.php', '', str_replace('\\', '/', __FILE__)));
class Meepo_fenModuleProcessor extends WeModuleProcessor {
	public function send($record, $user){
		global $_W;
		$uniacid = $_W['uniacid'];
		$api = $this->module['config'];
		if (empty($api)) {
			return error(-2, '系统还未开放，没填写API');
		}
		$fee = floatval($record['fee']) * 100;
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$pars = array();
		$pars['nonce_str'] = random(32);
		$pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%d', time());
		$pars['mch_id'] = $api['mchid'];
		$pars['wxappid'] = $api['appid'];
		$pars['nick_name'] = $_W['account']['name'];
		$pars['send_name'] = $_W['account']['name'];
		$pars['re_openid'] = $record['openid'];
		$pars['total_amount'] = $fee;
		$pars['min_value'] = $pars['total_amount'];
		$pars['max_value'] = $pars['total_amount'];
		$pars['total_num'] = 1;
		$pars['wishing'] = '感谢您'.$_W['account']['name'].'东家，你的体现金额已发放，注意查收！'.$record['fee'].'元';
		$pars['client_ip'] = $api['ip'];
		$pars['act_name'] = '吴迪生物东家佣金发放';
		$pars['remark'] = '尊敬的东家：'.$user['nickname'].'您的佣金已通过红包发放，请注意查收';
		$pars['logo_imgurl'] = tomedia($api['logo']);
		$pars['share_content'] = '哇,发财了！我在'.$_W['account']['name'].'赚了'.$record['fee'].'元佣金，已经到账啦！赶紧来玩吧！';
		$pars['share_imgurl'] = tomedia($api['logo']);
		$pars['share_url'] = '';
		ksort($pars, SORT_STRING);
		$string1 = '';
		foreach ($pars as $k => $v) {
			$string1.= "{$k}={$v}&";
		}
		$string1.= "key={$api['password']}";
		$pars['sign'] = strtoupper(md5($string1));
		$xml = array2xml($pars);
		$extras = array();
		$extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $uniacid;
		$extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $uniacid;
		$extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $uniacid;
	
		load()->func('communication');
		$procResult = null;
		$resp = ihttp_request($url, $xml, $extras);
		if (is_error($resp)) {
			$procResult = $resp;
		} else {
			$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
			$dom = new DOMDocument();
			if ($dom->loadXML($xml)) {
				$xpath = new DOMXPath($dom);
				$code = $xpath->evaluate('string(//xml/return_code)');
				$ret = $xpath->evaluate('string(//xml/result_code)');
				if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
					$procResult = true;
				} else {
					$error = $xpath->evaluate('string(//xml/err_code_des)');
					$procResult = error(-2, $error);
				}
			} else {
				$procResult = error(-1, 'error response');
			}
		}
		if (is_error($procResult)) {
			return false;
		} else {
			return true;
		}
	}
	public function respond() {
		global $_W;
		$acid = $_W['acid'];//子公众账号
		/*心得：fans分子公众账号,members只有统一公众账号*/
		$from = $this->message['from'];
		$time = $this->message['time'];
		
		checkauth();
		load()->model('mc');
		
		$uid = $_W['member']['uid'];
		$set = getset('meepo_fen');
		
		$fans = get_user();
		
		if(empty($fans)){
			//新会员
			set_user();
			//检查积分，发放积分
			if($set['credit1']>0){
				mc_credit_update($uid,'credit1',$set['sysset']['credit1'],array($uid,'新关注用户赠送'));
			}
			//检查余额，发放余额
			if($set['credit2']>0){
				mc_credit_update($uid,'credit2',$set['sysset']['credit2'],array($uid,'新关注用户赠送'));
			}
			
			//检查红包，发放红包
			if(!empty($set['cash'])){
				$user = mc_fetch($uid);
				$record = array();
				$record['id'] = $uid;
				$record['fee'] = floatval($set['sysset']['cash']);
				$record['openid'] = $from;
				
				if($return = $this->send($record, $user)){
					$text = '红包发放成功!';
				}else{
					$text = '红包发放失败';
				}
			}
			
			//检查代金券，发放代金券
			if(!empty($set['token_couponid'])){
				activity_token_grant($uid,$set['sysset']['token_couponid'],$module='meepo_fen',$remark='新会员关注，赠送');
			}
			
			//检查折扣券，发放折扣券
			if(!empty($set['coupon_couponid'])){
				activity_coupon_grant($uid,$set['sysset']['coupon_couponid'],$module='meepo_fen',$remark='新会员关注，赠送');
			}
			
			//检查实物，发放实物
			if(!empty($set['goods_couponid'])){
				activity_goods_grant($uid,$set['sysset']['goods_couponid']);
			}
			
			$reply = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_reply')." WHERE uniacid = '{$_W['uniacid']}'");
			$content = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_basic')." WHERE id = '{$reply['new_basic_id']}'");
			
		}else{
			//老会员
			//啥都不干
			//检查红包，发放红包
			$reply = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_reply')." WHERE uniacid = '{$_W['uniacid']}'");
			$content = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_basic')." WHERE id = '{$reply['old_basic_id']}'");
		}
		$sql = "SELECT COUNT(*) FROM ".tablename('mc_mapping_fans')." WHERE uniacid = :uniacid";
		$params = array(':uniacid'=>$_W['uniacid']);
		$num = pdo_fetchcolumn($sql,$params);
		
		$sql = "SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid AND uniacid = :uniacid";
		$params = array(':openid'=>$_W['openid'],':uniacid'=>$_W['uniacid']);
		$fan = pdo_fetch($sql,$params);
		$replace = array(
				'follow_time' => date('Y-m-d h:i:s',$fan['followtime']),
				'pan_name'=>$set['sysset']['pan_name'],
				'follow_num'=>$set['sysset']['fans_num']+$num,
		);
		$content = $content['content'];
		$contentStr = formot_content($replace,$content);
		
		return $this->respText($contentStr);
	}
	
	public function formot_content2($data,$replace,$content = ''){
		global $_W;
		if(empty($content)){
			return $content;
		}
		if(is_array($content)){
			foreach ($content as $key => &$con) {
				$cont[$key] = $this->formot_content2($con);
			}
			return $cont;
		}else{
			foreach ($replace as $re){
				$content = str_replace($re['replace'], $data[$re['name']], $content);
			}
			return $content;
		}
	}
}

function get_user(){
	global $_W;
	$user = pdo_fetch("SELECT uid FROM ".tablename('meepo_fen_user')." WHERE uniacid = '{$_W['uniacid']}' AND uid = '{$_W['member']['uid']}'");
	return $user;
}

function set_user(){
	global $_W;
	$user = array();
	$user['uid'] = $_W['member']['uid'];
	$user['uniacid'] = $_W['uniacid'];
	$row = pdo_insert('meepo_fen_user',$user);
	
	return $row;
}

function get_set(){
	global $_W;
	$set = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_set')." WHERE uniacid = '{$_W['uniacid']}'");
	$set = iunserializer($set['set']);
	
	return $set;
}

	
function formot_content($replace,$content ){
	$content = preg_replace('/#follow_time#/si', $replace['follow_time'], $content);
	$content = preg_replace('/#pan_name#/si', $replace['pan_name'], $content);
	$content = preg_replace('/#follow_num#/si', $replace['follow_num'],$content);
	return $content;
}
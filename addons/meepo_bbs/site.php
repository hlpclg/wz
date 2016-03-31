<?php /*折翼天使资源社区 www.zheyitianshi.com*/
/**
 * 微论坛模块微站定义
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));
define('INC_PATH',ROOT_PATH.'inc/');
if(file_exists(INC_PATH.'core/function/forum.func.php')){
	include INC_PATH.'core/function/forum.func.php';
}
if(file_exists(INC_PATH.'core/function/delete.func.php')){
	include INC_PATH.'core/function/delete.func.php';
}
if(file_exists(INC_PATH.'core/function/qiniu.mod.php')){
	include INC_PATH.'core/function/qiniu.mod.php';
}
class Meepo_bbsModuleSite extends WeModuleSite {
	
	public function __construct(){
		global $_W,$_GPC;
		$set = getSet();
		if($_W['os'] == 'mobile') {
			
			load()->model('mc');
			//插入分享点赞页
			
			$fan = mc_fansinfo($_W['openid'], $_W['acid'], $_W['uniacid']);
			_mc_login(array('uid' => intval($fan['uid'])));
			
			//pdo_update('meepo_bbs_user');
			$log = array('log'=>$user['nickname'].'进入页面','type'=>'scan');
			
			$user = mc_fetch($_W['member']['uid'],array('nickname','avatar'));
			$user = !empty($user)?$user:$_SESSION['userinfo'];
			if(empty($user['avatar'])){
				if($_W['account']['level']>3){
					$user = mc_oauth_userinfo($_W['acid']);
				}
			}
			
			
			$_W['member']['avatar'] = $user['avatar'];
			$_W['member']['nickname'] = $user['nickname'];
			
			if(!empty($_W['openid'])){
				$_W['member']['role'] = 'openid';
			}
			if(!empty($_W['fans'])){
				$_W['member']['role'] = 'fans';
			}
			if(!empty($_W['member']['id'])){
				$_W['member']['role'] = 'member';
			}
			global $_share;
			
		} else {
			$do = $_GPC['do'];
			$doo = $_GPC['doo'];
			$act = $_GPC['act'];
			global $frames;
			$frames = getModuleFrames('meepo_bbs');
			_calc_current_frames2($frames);
		}
		
	}
	protected function pay($params = array(), $mine = array()) {
		global $_W;
		if(!$this->inMobile) {
			message('支付功能只能在手机上使用');
		}
		$params['module'] = $this->module['name'];
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		if($params['fee'] <= 0) {
			$pars['from'] = 'return';
			$pars['result'] = 'success';
			$pars['type'] = 'alipay';
			$pars['tid'] = $params['tid'];
			$site = WeUtility::createModuleSite($pars[':module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				exit($site->$method($pars));
			}
		}
	
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$pay = $setting['payment'];
		
		$sql = "SELECT * FROM ".tablename('meepo_bbs_ec_chong_log')." WHERE tid = :tid";
		$par = array(':tid'=>$params['tid']);
		$ec_chong_log = pdo_fetch($sql,$par);
		if(!empty($ec_chong_log)){
			unset($pay['credit']);
			unset($pay['delivery']);
			unset($pay['credit2']);
		}
		
		if (empty($_W['member']['uid'])) {
			$pay['credit']['switch'] = false;
		}
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}
		$iscard = pdo_fetchcolumn('SELECT iscard FROM ' . tablename('modules') . ' WHERE name = :name', array(':name' => $params['module']));
		$you = 0;
		if($pay['card']['switch'] == 2 && !empty($_W['openid'])) {
			if($_W['card_permission'] == 1 && !empty($params['module'])) {
				$cards = pdo_fetchall('SELECT a.id,a.card_id,a.cid,b.type,b.title,b.extra,b.is_display,b.status,b.date_info FROM ' . tablename('coupon_modules') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.cid = b.id WHERE a.acid = :acid AND a.module = :modu AND b.is_display = 1 AND b.status = 3 ORDER BY a.id DESC', array(':acid' => $_W['acid'], ':modu' => $params['module']));
				$flag = 0;
				if(!empty($cards)) {
					foreach($cards as $temp) {
						$temp['date_info'] = iunserializer($temp['date_info']);
						if($temp['date_info']['time_type'] == 1) {
							$starttime = strtotime($temp['date_info']['time_limit_start']);
							$endtime = strtotime($temp['date_info']['time_limit_end']);
							if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
								continue;
							} else {
								$param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
								$num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
								if($num <= 0) {
									continue;
								} else {
									$flag = 1;
									$card = $temp;
									break;
								}
							}
						} else {
							$deadline = intval($temp['date_info']['deadline']);
							$limit = intval($temp['date_info']['limit']);
							$param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
							$record = pdo_fetchall('SELECT addtime,id,code FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
							if(!empty($record)) {
								foreach($record as $li) {
									$time = strtotime(date('Y-m-d', $li['addtime']));
									$starttime = $time + $deadline * 86400;
									$endtime = $time + $deadline * 86400 + $limit * 86400;
									if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
										continue;
									} else {
										$flag = 1;
										$card = $temp;
										break;
									}
								}
							}
							if($flag) {
								break;
							}
						}
					}
				}
				if($flag) {
					if($card['type'] == 'discount') {
						$you = 1;
						$card['fee'] = sprintf("%.2f", ($params['fee'] * ($card['extra'] / 100)));
					} elseif($card['type'] == 'cash') {
						$cash = iunserializer($card['extra']);
						if($params['fee'] >= $cash['least_cost']) {
							$you = 1;
							$card['fee'] = sprintf("%.2f", ($params['fee'] -  $cash['reduce_cost']));
						}
					}
					load()->classs('coupon');
					$acc = new coupon($_W['acid']);
					$card_id = $card['card_id'];
					$time = TIMESTAMP;
					$randstr = random(8);
					$sign = array($card_id, $time, $randstr, $acc->account['key']);
					$signature = $acc->SignatureCard($sign);
					if(is_error($signature)) {
						$you = 0;
					}
				}
			}
		}
	
		if($pay['card']['switch'] == 3 && $_W['member']['uid']) {
			$cards = array();
			if(!empty($params['module'])) {
				$cards = pdo_fetchall('SELECT a.id,a.couponid,b.type,b.title,b.discount,b.condition,b.starttime,b.endtime FROM ' . tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND a.module = :modu AND b.condition <= :condition AND b.starttime <= :time AND b.endtime >= :time  ORDER BY a.id DESC', array(':uniacid' => $_W['uniacid'], ':modu' => $params['module'], ':time' => TIMESTAMP, ':condition' => $params['fee']), 'couponid');
				if(!empty($cards)) {
					$condition = '';
					if($iscard == 1) {
						$condition = " AND grantmodule = '{$params['module']}'";
					}
					foreach($cards as $key => &$card) {
						$has = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . ' WHERE uid = :uid AND uniacid = :aid AND couponid = :cid AND status = 1' . $condition, array(':uid' => $_W['member']['uid'], ':aid' => $_W['uniacid'], ':cid' => $card['couponid']));
						if($has > 0){
							if($card['type'] == '1') {
								$card['fee'] = sprintf("%.2f", ($params['fee'] * $card['discount']));
								$card['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $card['discount']));
							} elseif($card['type'] == '2') {
								$card['fee'] = sprintf("%.2f", ($params['fee'] -  $card['discount']));
								$card['discount_cn'] = $card['discount'];
							}
						} else {
							unset($cards[$key]);
						}
					}
				}
			}
			if(!empty($cards)) {
				$cards_str = json_encode($cards);
			}
		}
		include $this->template('common/paycenter');
	}
	
	public function payResult($params) {
		global $_W;
		load()->model('mc');
		//打赏有限
		$set = getSet();
		$sql = "SELECT * FROM ".tablename('meepo_bbs_begging')." WHERE tid = :tid ";
		$par = array(':tid'=>$params['tid']);
		$begging = pdo_fetch($sql,$par);
		
		if(!empty($begging)){
			if($begging['status'] == 0){
				$fee = $params['fee'];
				$data = array('status' => $params['result'] == 'success' ? 1 : -1);
				if ($params['type'] == 'wechat') {
					$data['transid'] = $params['tag']['transaction_id'];
				}
				pdo_update('meepo_bbs_begging', $data, array('tid' => $params['tid']));
				
				if ($params['result'] == 'success') {
					if(!empty($set['begging'])){
						//直接发红包接口
					}else{
						//充值到余额
						if($fee>0){
							$credit = 'credit2';
							$fee = $fee;
							$paydata = array('wechat' => '微信', 'alipay' => '支付宝');
							$fuid = mc_openid2uid($begging['fopenid']);
							$fuser = mc_fetch($fuid,array('nickname'));
							$record[] = $fuid;
							$record[] = $fuser['nickname'].'通过'.$paydata[$params['type']].'打赏' . $fee.'元';
							mc_credit_update($begging['uid'], $credit, $fee, $record);
							//发送消息
							
							//插入回复内容 并返回改帖子
							$data = array();
							$data['content'] = trim($begging['content']);
							$data['tid'] = intval($begging['ttid']);
							$data['uniacid'] = $begging['uniacid'];
							$data['uid'] = $fuid;
							$data['create_at'] = $begging['time'];
							$data['fid'] = intval($begging['fid']);
							$data['thumb'] = $begging['thumb'];
							$data['beggingid'] = $begging['id'];
							
							pdo_insert('meepo_bbs_topic_replie',$data);
						}
					}
				}
			}
			if ($params['from'] == 'return') {
				message('',$this->createMobileUrl('forum_topic',array('id'=>$begging['ttid'])),success);
			}
		}
		
		
		$sql = "SELECT * FROM ".tablename('meepo_bbs_ec_chong_log')." WHERE tid = :tid";
		$par = array(':tid'=>$params['tid']);
		$ec_chong_log = pdo_fetch($sql,$par);
		if(!empty($ec_chong_log)){
			if (empty($ec_chong_log['status'])) {
				$fee = $params['fee'];
				$data = array('status' => $params['result'] == 'success' ? 1 : -1);
				if ($params['type'] == 'wechat') {
					$data['transid'] = $params['tag']['transaction_id'];
				}
				pdo_update('meepo_bbs_ec_chong_log', $data, array('tid' => $params['tid']));
				if ($params['result'] == 'success') {
					if(empty($ec_chong_log['type'])) {
						message('站点积分行为参数配置错误,请联系服务商', '', 'error');
					}
					if($ec_chong_log['type'] == 'credit1'){
						//充值积分
						$credit = 'credit1';
						$fee = floatval($ec_chong_log['num']);
						$paydata = array('wechat' => '微信', 'alipay' => '支付宝');
						$record[] = $params['user'];
						$record[] = '用户通过' . $paydata[$params['type']] . '充值' . $fee;
						$uid = $ec_chong_log['uid'];
						$re = mc_credit_update($uid, $credit, $fee, $record);
						if(is_error($re)){
							$msg = $re['msg'];
						}
					}
					if($ec_chong_log['type'] == 'credit2'){
						//充值余额
						$credit = 'credit2';
						$fee = floatval($ec_chong_log['num']);
						$paydata = array('wechat' => '微信', 'alipay' => '支付宝');
						$record[] = $params['user'];
						$record[] = '用户通过' . $paydata[$params['type']] . '充值' . $fee;
						$uid = $ec_chong_log['uid'];
						$re = mc_credit_update($uid, $credit, $fee, $record);
						if(is_error($re)){
							$msg = $re['msg'];
						}
					}
				}
			}
			if ($params['from'] == 'return') {
				if(!empty($msg)){
					message('支付失败！',  $this->createMobileUrl('home') , 'error');
				}
				if ($params['result'] == 'success') {
					message('支付成功！',  $this->createMobileUrl('home'), 'success');
				} else {
					message('支付失败！',  $this->createMobileUrl('home') , 'error');
				}
			}
		}
	}
}

function checkBbsUser(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_user')." WHERE openid = :openid AND uniacid = :uniacid";
	$params = array(':openid'=>$_W['openid'],':uniacid'=>$_W['uniacid']);
	$bbs_user = pdo_fetch($sql,$params);
		
	if(!empty($_W['openid'])){
		if(empty($bbs_user)){
			$data = array();
			$data['uniacid'] = $_W['uniacid'];
			$data['openid'] = $_W['openid'];
			$data['acid'] = $_W['acid'];
			$data['time'] = time();
			$data['online'] = 1;
			$data['uid'] = $_W['member']['uid'];
			$data['ip'] = getip();
			pdo_insert('meepo_bbs_user',$data);
		}else{
			$data = array();
			$data['time'] = time();
			$data['ip'] = getip();
			$data['online'] = 1;
			pdo_update('meepo_bbs_user',$data,array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
		}
	}
	//清理长时间没有活动的会员
	$time = time()-60*60*2;
	$sql = "UPDATE ".tablename('meepo_bbs_user')." SET online = 0 WHERE time <= '{$time}' AND uniacid = '{$_W['uniacid']}' ";
	//$params = array(':time'=>(time()-60*60*2),':uniacid'=>$_W['uniacid']);
	pdo_query($sql);
}

function insertLog($log){
	global $_W;
	$log['openid'] = $_W['openid'];
	$log['uniacid'] = $_W['uniacid'];
	$log['time'] = time();
	
	pdo_insert('meepo_bbs_log',$log);
}

function getModuleFrames($name){
	global $_W;
	$sql = "SELECT * FROM ".tablename('modules')." WHERE name = :name limit 1";
	$params = array(':name'=>$name);
	$module = pdo_fetch($sql,$params);
	
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :name ";
	$params = array(':name'=>$name);
	$module_bindings = pdo_fetchall($sql,$params);
	
	$frames = array();
	
	$frames['index']['title'] = '首页';
	$frames['index']['active'] = 'active';
	$frames['index']['items'] = array();
	$frames['index']['items']['index']['url'] = url('site/entry/index/',array('doo'=>'index','op'=>'index','m'=>$name));
	$frames['index']['items']['index']['title'] = '管理首页';
	$frames['index']['items']['index']['actions'] = array();
	$frames['index']['items']['index']['active'] = '';
	$frames['index']['items']['threadclass']['url'] = url('site/entry/threadclass/',array('m'=>$name));
	$frames['index']['items']['threadclass']['title'] = '版块管理';
	$frames['index']['items']['threadclass']['actions'] = array();
	$frames['index']['items']['threadclass']['active'] = '';
	$frames['index']['items']['threadclass']['append']['title'] = '最新帖子';
	$frames['index']['items']['threadclass']['append']['url'] = url('site/entry/manage/',array('m'=>$name));
	$frames['index']['items']['custommenu']['url'] = url('site/entry/fast/',array('m'=>$name));
	$frames['index']['items']['custommenu']['title'] = '常用操作';
	$frames['index']['items']['custommenu']['active'] = '';
	
	$frames['index']['items']['rss']['url'] = url('site/entry/index/',array('doo'=>'index','op'=>'rss','m'=>$name));
	$frames['index']['items']['rss']['title'] = 'RSS抓取';
	$frames['index']['items']['rss']['active'] = '';
	
	$frames['operate']['title'] = '运营';
	$frames['operate']['items'] = array();
	/* $frames['operate']['items']['announce']['url'] = url('site/entry/index/',array('doo'=>'operate','op'=>'announce','m'=>$name));
	$frames['operate']['items']['announce']['title'] = '站点公告';
	$frames['operate']['items']['announce']['actions'] = array();
	$frames['operate']['items']['announce']['active'] = ''; */
	$frames['operate']['items']['adv']['url'] = url('site/entry/adv/',array('m'=>$name));
	$frames['operate']['items']['adv']['title'] = '站点广告';
	$frames['operate']['items']['adv']['actions'] = array();
	$frames['operate']['items']['adv']['active'] = '';
	$frames['operate']['items']['tasks']['url'] = url('site/entry/task/',array('m'=>$name));
	$frames['operate']['items']['tasks']['title'] = '任务大厅';
	$frames['operate']['items']['tasks']['actions'] = array();
	$frames['operate']['items']['tasks']['active'] = '';
	$frames['operate']['items']['tasks']['append']['title'] = '一键导入';
	$frames['operate']['items']['tasks']['append']['url'] = url('site/entry/',array('do'=>'task','op'=>'one','m'=>$name));
	
	$frames['operate']['items']['credit']['url'] = url('site/entry/credit/',array('m'=>$name));
	$frames['operate']['items']['credit']['title'] = '积分兑换';
	$frames['operate']['items']['credit']['actions'] = array();
	$frames['operate']['items']['credit']['active'] = '';
	
	$frames['operate']['items']['rand']['url'] = url('site/entry/rand/',array('m'=>$name));
	$frames['operate']['items']['rand']['title'] = '批刷浏览量';
	$frames['operate']['items']['rand']['actions'] = array();
	$frames['operate']['items']['rand']['active'] = '';
	
	/* $frames['operate']['items']['magics']['url'] = url('site/entry/index/',array('doo'=>'magics','op'=>'magics','m'=>$name));
	$frames['operate']['items']['magics']['title'] = '道具中心';
	$frames['operate']['items']['magics']['actions'] = array();
	$frames['operate']['items']['magics']['active'] = '';
	
	$frames['operate']['items']['medals']['url'] = url('site/entry/index/',array('doo'=>'medals','op'=>'medals','m'=>$name));
	$frames['operate']['items']['medals']['title'] = '勋章中心';
	$frames['operate']['items']['medals']['actions'] = array();
	$frames['operate']['items']['medals']['active'] = '';*/
	
	$frames['operate']['items']['ec']['url'] = url('site/entry/index/',array('doo'=>'ec','op'=>'config','m'=>$name));
	$frames['operate']['items']['ec']['title'] = '电子商务';
	$frames['operate']['items']['ec']['actions'] = array();
	$frames['operate']['items']['ec']['active'] = '';
	
	$frames['oto']['title'] = 'O2O管理';
	$frames['oto']['items'] = array();
	
	$frames['oto']['items']['oto_user']['url'] = url('site/entry/oto_user',array('m'=>$name));
	$frames['oto']['items']['oto_user']['title'] = 'o2o核销员管理';
	$frames['oto']['items']['oto_user']['active'] = '';
	
	$frames['oto']['items']['oto_user_log']['url'] = url('site/entry/oto_user_log',array('m'=>$name));
	$frames['oto']['items']['oto_user_log']['title'] = 'o2o核销记录';
	$frames['oto']['items']['oto_user_log']['active'] = '';
	
	$frames['begging']['title'] = '微打赏';
	$frames['begging']['items'] = array();
	
	$frames['begging']['items']['manage']['url'] = url('site/entry/index',array('doo'=>'begging','op'=>'manage','m'=>$name));
	$frames['begging']['items']['manage']['title'] = '打赏管理';
	$frames['begging']['items']['manage']['active'] = '';
	
	$frames['begging']['items']['list']['url'] = url('site/entry/index',array('doo'=>'begging','op'=>'list','m'=>$name));
	$frames['begging']['items']['list']['title'] = '打赏帖子';
	$frames['begging']['items']['list']['active'] = '';
	
	$frames['begging']['items']['post']['url'] = url('site/entry/index',array('doo'=>'begging','op'=>'post','m'=>$name));
	$frames['begging']['items']['post']['title'] = '添加打赏帖子';
	$frames['begging']['items']['post']['active'] = '';
	
	$frames['template']['title'] = '消息及群发';
	$frames['template']['items'] = array();
	
	$frames['template']['items']['template']['url'] = url('site/entry/index',array('doo'=>'template','op'=>'template','m'=>$name));
	$frames['template']['items']['template']['title'] = '模板库管理';
	$frames['template']['items']['template']['active'] = '';
	
	$frames['menu']['title'] = '基础设置';
	$frames['menu']['items'] = array();
	
	$frames['menu']['items']['set']['url'] = url('site/entry/',array('do'=>'set','m'=>$name));
	$frames['menu']['items']['set']['title'] = '系统设置';
	$frames['menu']['items']['set']['active'] = '';
	
	$frames['menu']['items']['qiniu']['url'] = url('site/entry/',array('do'=>'qiniu','m'=>$name));
	$frames['menu']['items']['qiniu']['title'] = '七牛云设置';
	$frames['menu']['items']['qiniu']['active'] = '';
	
	$frames['menu']['items']['nav']['url'] = url('site/entry/',array('do'=>'nav','m'=>$name));
	$frames['menu']['items']['nav']['title'] = '首页导航管理';
	$frames['menu']['items']['nav']['active'] = '';
	$frames['menu']['items']['nav']['append'] = array();
	$frames['menu']['items']['nav']['append']['title'] = '一键配置';
	$frames['menu']['items']['nav']['append']['url'] = url('site/entry/',array('do'=>'oneconfig','op'=>nav,'m'=>$name));
	/* if(!empty($module['settings'])){
		$frames['menu']['items']['settings']['url'] = url('profile/module/',array('do'=>'setting','m'=>$name));
		$frames['menu']['items']['settings']['title'] = '模板管理';
		$frames['menu']['items']['settings']['active'] = '';
	} */
	if($_W['role'] == 'founder'){
		$frames['founder']['title'] = '总管理员特权';
		$frames['founder']['items'] = array();
		//管理员菜单 系统在线升级
	
		$frames['founder']['items']['help']['url'] = url('site/entry/',array('do'=>'sysset','m'=>$name));
		$frames['founder']['items']['help']['title'] = '后台版权';
		$frames['founder']['items']['help']['active'] = ''; 
		
		/* $frames['founder']['items']['permission']['url'] = url('site/entry/',array('do'=>'permission','m'=>$name));
		$frames['founder']['items']['permission']['title'] = '分权管理';
		$frames['founder']['items']['permission']['active'] = ''; */
	}
	/* 
	if($_W['role'] == 'manager' || $_W['role'] == 'founder'){
		$frames['manager']['title'] = '管理员特权';
		$frames['manager']['items'] = array();
	} */
	
	return $frames;
}

function _calc_current_frames2(&$frames) {
	global $_W,$_GPC,$frames;
	if(!empty($frames) && is_array($frames)) {
		foreach($frames as &$frame) {
			foreach($frame['items'] as &$fr) {
				$query = parse_url($fr['url'], PHP_URL_QUERY);
				parse_str($query, $urls);
				if(defined('ACTIVE_FRAME_URL')) {
					$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
					parse_str($query, $get);
				} else {
					$get = $_GET;
				}
				if(!empty($_GPC['a'])) {
					$get['a'] = $_GPC['a'];
				}
				if(!empty($_GPC['c'])) {
					$get['c'] = $_GPC['c'];
				}
				if(!empty($_GPC['do'])) {
					$get['do'] = $_GPC['do'];
				}
				if(!empty($_GPC['doo'])) {
					$get['doo'] = $_GPC['doo'];
				}
				if(!empty($_GPC['op'])) {
					$get['op'] = $_GPC['op'];
				}
				if(!empty($_GPC['m'])) {
					$get['m'] = $_GPC['m'];
				}
				$diff = array_diff_assoc($urls, $get);
				
				if(empty($diff)) {
					$fr['active'] = ' active';
					$frame['active'] = ' active';
				}
			}
		}
	}
}



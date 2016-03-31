<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
checkauth();
load()->model('mc');

$_W['page']['sitename'] = '';
//积分、余额
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
$set = getSet();
$uid = $_W['member']['uid'];
$type = !empty($_GPC['type'])?trim($_GPC['type']):'credit2';

$user = mc_fetch($_W['member']['uid'],array('nickname'));
$username = $user['nickname'];
if(empty($username)){
	$username = 'uid:'.$user['id'];
}
if(empty($username)) {
	message('您的用户信息不完整,请完善用户信息后再充值', '', 'error');
}

if(checksubmit('submit')){
	$data = array();
	
	if($type=='credit2'){
		$fee = floatval($_GPC['money']);
		if($fee <= 0) {
			message('支付错误, 金额小于0');
		}
		$num = $fee;
		
		$sql = "SELECT * FROM ".tablename('meepo_bbs_ec_chong_log')." WHERE uniacid = :uniacid AND uid = :uid AND fee = :fee AND num = :num AND type = :type AND status = :status";
		$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid'],':fee'=>$fee,':num'=>$num,':type'=>$type,':status'=>0);
		$ec_chong_log = pdo_fetch($sql,$params);
		if(empty($ec_chong_log)){
		$ec_chong_log = array();
			$ec_chong_log['tid'] = date('YmdHi').random(10, 1);
			$ec_chong_log['type'] = $type;
			$ec_chong_log['fee'] = $fee;
			$ec_chong_log['num'] = $num;
			$ec_chong_log['time'] = TIMESTAMP;
			$ec_chong_log['uid'] = $_W['member']['uid'];
			$ec_chong_log['uniacid'] = $_W['uniacid'];
		
			if (!pdo_insert('meepo_bbs_ec_chong_log',$ec_chong_log)) {
				message('创建充值订单失败，请重试！', $this->createMobileUrl('ec_chong',array('type'=>'credit1')), 'error');
			}
		
		}
		$params = array(
				'tid' => $ec_chong_log['tid'],
				'ordersn' => $ec_chong_log['tid'],
				'title' => '系统充值'.$fee.'余额',
				'fee' => $ec_chong_log['fee'],
				'user' => $_W['member']['uid'],
		);
		$this->pay($params);
	}
	
	if($type == 'credit1'){
		$fee = floatval($_GPC['money']);
		if($fee <= 0) {
			message('支付错误, 金额小于0',referer(),error);
		}
		
		if(empty($set['settingnew'])){
			message('系统配置错误或未开启积分充值，请联系站点管理员',referer(),error);
		}
		
		$ec_ratio = floatval($set['settingnew']['ec_ratio']);
		$ec_mincredits = floatval($set['settingnew']['ec_mincredits']);
		$ec_maxcredits = floatval($set['settingnew']['ec_maxcredits']);
		$ec_maxcreditspermonth = $set['settingnew']['ec_maxcreditspermonth'];
		
		if(empty($ec_ratio)){
			message('系统配置有无',referer(),error);
		}
		$num = $fee * $ec_ratio;
		if($num < $ec_mincredits){
			message('单次最低充值额度不能少于'.$ec_mincredits.'积分',referer(),error);
		}
		
		if($num > $ec_maxcredits){
			message('单次最低充值额度不能大于'.$ec_maxcredits.'积分',referer(),error);
		}
		
		$sql = "SELECT * FROM ".tablename('meepo_bbs_ec_chong_log')." WHERE uniacid = :uniacid AND uid = :uid AND fee = :fee AND num = :num AND type = :type AND status = :status";
		$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid'],':fee'=>$fee,':num'=>$num,':type'=>$type,':status'=>0);
		$ec_chong_log = pdo_fetch($sql,$params);
		if(empty($ec_chong_log)){
			$ec_chong_log = array();
			$ec_chong_log['tid'] = date('YmdHi').random(10, 1);
			$ec_chong_log['type'] = $type;
			$ec_chong_log['fee'] = $fee;
			$ec_chong_log['num'] = $num;
			$ec_chong_log['time'] = TIMESTAMP;
			$ec_chong_log['uid'] = $_W['member']['uid'];
			$ec_chong_log['uniacid'] = $_W['uniacid'];
		
			if (!pdo_insert('meepo_bbs_ec_chong_log',$ec_chong_log)) {
				message('创建充值订单失败，请重试！', $this->createMobileUrl('ec_chong',array('type'=>'credit1')), 'error');
			}
		}
		$params = array(
				'tid' => $ec_chong_log['tid'],
				'ordersn' => $ec_chong_log['tid'],
				'title' => '系统充值'.$num.'积分',
				'fee' => $ec_chong_log['fee'],
				'user' => $_W['member']['uid'],
		);
		$this->pay($params);
	}
}else{
	include $this->template($tempalte.'/templates/home/ec_chong');
}

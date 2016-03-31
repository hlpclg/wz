<?php /*折翼天使资源社区 www.zheyitianshi.com*/
load()->model('mc');
$actions = array();

$act = trim($_GPC['act']);


if($act == 'waitting'){
	ob_start();
	include $this->template('web/index/waitting');
	$data = ob_get_contents();
	ob_clean();
	die($data);
}

if($act == 'topic'){
	ob_start();
	include $this->template('web/index/topic');
	$data = ob_get_contents();
	ob_clean();
	die($data);
}


if($act == 'useronline'){
	$start = intval($_GPC['start']);
	$sql = "SELECT * FROM ".tablename('meepo_bbs_user')." WHERE uniacid = :uniacid AND online = :online limit $start,20";
	$params = array(':uniacid'=>$_W['uniacid'],':online'=>1);
	$list = pdo_fetchall($sql,$params);
	foreach ($list as $li){
		if(empty($li['uid'])){
			$li['nickname'] = '游客';
			$li['avatar'] = $_W['siteroot'].'addons/meepo_bbs/template/mobile/default/img/avatar.png';
		}else{
			$user = mc_fetch($li['uid'],array('nickname','avatar'));
			$li['avatar'] = $user['avatar'];
			$li['nickname'] = $user['nickname'];
		}
		
		$lists[] = $li;
	}
	ob_start();
	include $this->template('web/index/useronline');
	$data = ob_get_contents();
	ob_clean();
	die($data);
}

$day_num = !empty($settings['stat']['msg_maxday']) ? $settings['stat']['msg_maxday'] : 30;
if($_W['ispost']) {
	$starttime = strtotime("-{$day_num} day");
	$endtime = time();
	$data_hit = pdo_fetchall("SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime", array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));

	for($i = $day_num; $i >= 0; $i--){
		$key = date('m-d', strtotime('-' . $i . 'day'));
		$days[] = $key;
		$datasets[$key] = 0;
	}

	foreach($data_hit as $da) {
		$key1 = date('m-d', $da['createtime']);
		if(in_array($key1, $days)) {
			$datasets[$key1]++;
		}
	}

	$todaytimestamp = strtotime(date('Y-m-d'));
	$monthtimestamp = strtotime(date('Y-m'));
	$stat['month'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND createtime >= '$monthtimestamp'", array(':uniacid' => $_W['uniacid']));
	$stat['today'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND createtime >= '$todaytimestamp'", array(':uniacid' => $_W['uniacid']));
	$stat['rule'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
	$stat['m_name'] = $m_name;

	exit(json_encode(array('key' => $days, 'value' => array_values($datasets), 'stat' => $stat)));
}
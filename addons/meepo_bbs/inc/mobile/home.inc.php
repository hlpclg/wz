<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->model('mc');
$set = getSet();
$table = 'meepo_bbs_home';
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
//我的主页
$uid = $_W['member']['uid'];
$user = mc_fetch($uid);

$setting = uni_setting($_W['uniacid'], 'creditbehaviors');
$user['credit1_title'] = $setting['creditnames']['credit1']['title'];
$user['credit2_title'] = $setting['creditnames']['credit2']['title'];
$user['credit3_title'] = $setting['creditnames']['credit3']['title'];
$user['credit4_title'] = $setting['creditnames']['credit4']['title'];
$user['credit5_title'] = $setting['creditnames']['credit5']['title'];

$mytopics = getMyTopics();
$mytopics_total = getTotalMyTopics();

$sql = "SELECT * FROM ".tablename('modules')." WHERE name = :name ";
$params = array(':name'=>'meepo_nsign');
$nsign = pdo_fetch($sql,$params);

$user['group'] = getGroupTitle($user['groupid']);

$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_home_message')." WHERE status = 0 AND toopenid = :toopenid ";
$params = array(':toopenid'=>$uid);
$messagenum = pdo_fetchcolumn($sql,$params);

$todo_num = $all_num = 0;
$usertasks = array();
$list = pdo_fetchall("SELECT * FROM ".tablename('meepo_bbs_task_user')." WHERE uid='{$_W['member']['uid']}'");
foreach ($list as $value){
	$usertasks[$value['taskid']] = $value;
	$done_num++;
}
$tasklist = array();
$query = '';
$list = pdo_fetchall('SELECT * FROM '.tablename('meepo_bbs_task')." WHERE uniacid = '{$_W['uniacid']}' AND available='1' ORDER BY displayorder");
foreach ($list as $value){
	if((empty($value['maxnum']) || $value['maxnum']>$value['num']) &&
		(empty($value['starttime']) || $value['starttime'] <= time()) &&
		(empty($value['endtime']) || $value['endtime'] >= time())) {
			$all_num++;
			
			$allownext = 0;
			$lasttime = $usertasks[$value['taskid']]['dateline'];
			if(empty($lasttime)) {
				$allownext = 1;
			} elseif($value['nexttype'] == 'day') {
				if(date('Ymd', time()) != date('Ymd', $lasttime)) {
					$allownext = 1;
				}
			} elseif ($value['nexttype'] == 'hour') {
				if(date('YmdH', time()) != date('YmdH', $lasttime)) {
					$allownext = 1;
				}
			} elseif ($value['nexttime']) {
				if(time()-$lasttime >= $value['nexttime']) {
					$allownext = 1;
				}
			}
			if($allownext) {
				$todo_num++;
			}
	}
}

$tasknum = $all_num-$todo_num;


include $this->template($tempalte.'/templates/home/index');
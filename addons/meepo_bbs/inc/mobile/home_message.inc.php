<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->model('mc');
$set = getSet();
$table = 'meepo_bbs_home';
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
//我的主页
$uid = $_W['member']['uid'];
$user = mc_fetch($uid);

$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_home_message')." WHERE type > 0 AND toopenid = :toopenid AND status = :status";
$params = array(':toopenid'=>$uid,':status'=>0);
$replymenum = pdo_fetchcolumn($sql,$params);

$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_home_message')." WHERE type = 0 AND toopenid = :toopenid AND status = :status";
$params = array(':toopenid'=>$uid,':status'=>0);
$messagenum = pdo_fetchcolumn($sql,$params);

$con = " toopenid = :toopenid ";
$params = array(':toopenid'=>$uid);
if(empty($_GPC['type'])){
	$con .= "AND type > 0 ";
}

if(!empty($_GPC['type'])){
	$con .= " AND type = 0 ";
}
$start = !empty($_GPC['start'])?intval($_GPC['start']):0;

$sql = "SELECT * FROM ".tablename('meepo_bbs_home_message')." WHERE $con ORDER BY status ASC , time DESC limit $start,38";
$lists = pdo_fetchall($sql,$params);

foreach ($lists as $li){
	$uid = mc_openid2uid($li['fromopenid']);
	$user1 = mc_fetch($uid);
	$li['nickname'] = $user1['nickname'];
	$li['avatar'] = $user1['avatar'];
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$li['tid']);
	$topic = pdo_fetch($sql,$params);
	
	$li['title'] = $topic['title'];
	
	//pdo_update('meepo_bbs_home_message',array('status'=>1),array('id'=>$li['id']));
	$list[] = $li;
}


if(!empty($_W['isajax'])){
	ob_start();
	include $this->template($tempalte.'/templates/home/message_ajax');
	$data = ob_get_contents();
	ob_clean();
	die($data);
}else{
	include $this->template($tempalte.'/templates/home/message');
}

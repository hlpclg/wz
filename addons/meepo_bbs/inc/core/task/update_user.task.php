<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

checkauth();
$uid = $_W['member']['uid'];

$sql = "SELECT * FROM ".tablename('mc_members')." WHERE uid = :uid AND uniacid = :uniacid";
$params = array(':uid'=>$uid,':uniacid'=>$_W['uniacid']);
$user = pdo_fetch($sql,$params);

if(empty($user['mobile']) || empty($user['realname']) || empty($user['address'])){
	$done = 0;
}else{
	$done = 1;
}

if($done) {
	$task['done'] = 1;//任务完成
	$task['result'] = '恭喜您已完成首次完善个人信息任务';

} else {
	$url = murl('entry',array('m'=>'meepo_bbs','do'=>'home_profile'));
	//任务完成向导
	$task['guide'] = '<ul class="list">
				<a class="iteem item-icon-right" href="'.$url.'">
					<h2>立即去完善</h2>
				</a>
			</ul>'; //指导用户如何参与任务的文字说明。支持html代码

}
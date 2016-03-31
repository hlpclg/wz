<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

checkauth();
$uid = $_W['member']['uid'];

$sql = "SELECT id FROM ".tablename('meepo_bbs_topics')." WHERE uid = :uid AND uniacid = :uniacid";
$params = array(':uid'=>$uid,':uniacid'=>$_W['uniacid']);
$id = pdo_fetchcolumn($sql,$params);

if(empty($id)){
	$done = 0;
}else{
	$done = 1;
}

if($done) {
	$task['done'] = 1;//任务完成
	$task['result'] = '恭喜您已完成首次发帖任务';

} else {
	$url = murl('entry',array('m'=>'meepo_bbs','do'=>'forum_post'));
	//任务完成向导
	$task['guide'] = '<ul class="list">
				<a class="item item-icon-right" href="'.$url.'">
					<h2>立即去发帖</h2>
				</a>
			</ul>'; //指导用户如何参与任务的文字说明。支持html代码

}
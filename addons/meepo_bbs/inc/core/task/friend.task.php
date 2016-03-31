<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

checkauth();
$uid = $_W['member']['uid'];

$sql = "SELECT id FROM ".tablename('meepo_sub_log')." WHERE fid = :fid limit 1";
$params = array(':fid'=>$fid);
$id = pdo_fetchcolumn($sql,$params);

if(empty($id)){
	$done = 0;
}else{
	$done = 1;
}

if($done) {
	$task['done'] = 1;//任务完成
	$task['result'] = '恭喜您已完成呼朋引伴任务';

} else {
	$url = murl('entry',array('m'=>'meepo_bbs','do'=>'forum'));
	//任务完成向导
	$task['guide'] = '<ul class="list">
				<a class="item item-icon-right" href="'.$url.'">
					<h2>找个好的帖子，推荐给好友吧！</h2>
				</a>
			</ul>'; //指导用户如何参与任务的文字说明。支持html代码

}
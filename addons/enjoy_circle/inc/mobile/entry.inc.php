<?php
define('IN_MOBILE', true);
$user_agent  = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {
	die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
}
global $_W, $_GPC;
$uniacid=$_W['uniacid'];
$openid=$_W['openid'];
//$openid='oxVpDsz1uykcnu1wsH4xZ_gZzcV8';
$ulist=$this->auth($uniacid,$openid);
// var_dump($ulist);
// exit();

$uid=$_GPC['uid'];
if(empty($uid)){
	$user=$ulist;
	$account = account_fetch($_W['uniacid']);
	$level=$account['level'];
	//判断公众号类别
	if($level<4){
	//说明是从主入口进入的,默认关注
	pdo_query("update ".tablename('enjoy_circle_fans')." set subscribe=1 where uid=".$ulist['uid']."");
	}
}else {
	$user=pdo_fetch("select * from ".tablename('enjoy_circle_fans')." where uid=".$uid."");
}
	

$actdetail=pdo_fetch("select * from ".tablename('enjoy_circle_reply')." where uniacid=".$uniacid."");
//循环查出话题对应的评论
if($ulist['subscribe']==1){
	//说明关注过了
	$limit="";
}else {
	//没关注
	$limit="LIMIT 5";
}
//查询话题列表
$topics=pdo_fetchall("select * from ".tablename('enjoy_circle_topic')." where uniacid=".$uniacid." order by hot desc,createtime desc ".$limit."");


for($i=0;$i<count($topics);$i++){
	$topics[$i]['time']=$this->formatDate($topics[$i]['createtime']);
	$topics[$i]['comments']=pdo_fetchall("select * from ".tablename('enjoy_circle_comment')." where uniacid=".$uniacid." and tid=".$topics[$i]['tid']." order by hot desc,createtime desc limit 5");
for ($j=0;$j<count($topics[$i]['comments']);$j++){
	//判断评论是自己的还是别人的
	//var_dump($topics[$i]['comments'][$j]['cuid']);
	if($topics[$i]['comments'][$j]['cuid']==$ulist['uid']){
		//说明是自己的
		$topics[$i]['comments'][$j]['review']=1;
	}else {
		//别人的
		$topics[$i]['comments'][$j]['review']=0;
	}
}
//判断自己是否已经【评论过此话题
$topics[$i]['mycom']=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_comment')." where uniacid=".$uniacid." and tid=".$topics[$i]['tid']." and cuid=".$ulist[uid]." ");

}



// //查询中奖名单
// $rolls=pdo_fetchall("select nickname,money,img from ".tablename('enjoy_guess_roll')." where uniacid=".$uniacid." order by money desc limit 5");
//随机获取一个话题
$topic=pdo_fetch("select * from ".tablename('enjoy_circle_topic')." where uniacid=".$uniacid." ORDER BY RAND() LIMIT 1");
//分享

//$sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('share', array('tid' => $topic['tid']));
$sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('entry', array('uid'=>$user['uid']));
$sharetitle = $this->shareth($actdetail['share_title'],$ulist['nickname']);
$sharecontent = $this->shareth($actdetail['share_content'],$ulist['nickname']);
if(empty($actdetail[share_icon])){
	$actdetail[share_icon]=$ulist['avatar'];
}
include $this->template('entry');
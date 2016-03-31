<?php
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$openid=$_W['openid'];
$tid=$_GPC['tid'];
$ulist=$this->auth($uniacid,$openid);
$user_agent  = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {
	die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
}
$actdetail=pdo_fetch("select * from ".tablename('enjoy_circle_reply')." where uniacid=".$uniacid."");
$uid=$_GPC['uid'];
if(empty($uid)){
	$user=$ulist;
}else {
	$user=pdo_fetch("select * from ".tablename('enjoy_circle_fans')." where uid=".$uid."");
}
$item=pdo_fetch("select * from ".tablename('enjoy_circle_topic')." where uniacid=".$uniacid." and tid=".$tid);
$item['time']=$this->formatDate($item['createtime']);
	$more=pdo_fetchall("select * from ".tablename('enjoy_circle_comment')." where uniacid=".$uniacid." and tid=".$tid." order by hot desc,createtime desc");
	for ($j=0;$j<count($more);$j++){
		//判断评论是自己的还是别人的
		//var_dump($topics[$i]['comments'][$j]['cuid']);
		if($more[$j]['cuid']==$ulist['uid']){
			//说明是自己的
			$more[$j]['review']=1;
		}else {
			//别人的
			$more[$j]['review']=0;
		}
	}
	//判断自己是否已经【评论过此话题
	$mycom=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_comment')." where uniacid=".$uniacid." and tid=".$tid." and cuid=".$ulist[uid]."");

// var_dump($more);
// exit();
	//分享
	$sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('share', array('tid' => $tid,'uid'=>$user['uid']));
	$sharetitle = $this->shareth($actdetail['share_title'],$ulist['nickname']);
	$sharecontent = $this->shareth($actdetail['share_content'],$ulist['nickname']);
	
	if(empty($actdetail[share_icon])){
		$actdetail[share_icon]=$ulist['avatar'];
	}
include $this->template('more');
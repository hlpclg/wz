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
	//$more=pdo_fetchall("select * from ".tablename('enjoy_circle_comment')." where uniacid=".$uniacid." and tid=".$tid." order by hot desc,createtime desc");
	//判断自己是否已经【评论过此话题
	$mycom=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_comment')." where uniacid=".$uniacid." and tid=".$tid." and cuid=".$ulist[uid]."");

// var_dump($more);
// exit();

if($mycom>0){
	//评论过了直接跳转朋友圈
	header("location:".$this->createMobileUrl('entry',array('uid'=>$user['uid']))."");
}else{
	//回复
	
	if(checksubmit('submit')){
		$comment=$_GPC['comment'];
		//var_dump($ulist);
		$data=array(
				'uniacid'=>$uniacid,
				'tid'=>$tid,
				'comment'=>$comment,
				'nickname'=>$ulist['nickname'],
				'cuid'=>$ulist['uid'],
				'createtime'=>TIMESTAMP
		);
		$res=pdo_insert('enjoy_circle_comment',$data);
		if($res>0){
			header("location:".$this->createMobileUrl('entry',array('uid'=>$user['uid']))."");
		}
	}
	
	include $this->template('share');
}
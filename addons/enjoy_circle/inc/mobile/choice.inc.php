<?php
define('IN_MOBILE', true);
$user_agent  = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {
	die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
}
global $_W, $_GPC;
$uniacid=$_W['uniacid'];
$openid=$_W['openid'];
$ulist=$this->auth($uniacid,$openid);
//查询话题列表
$topics=pdo_fetchall("select * from ".tablename('enjoy_circle_topic')." where uniacid=".$uniacid." order by hot desc,createtime desc limit 10");
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
include $this->template('choice');
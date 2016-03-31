<?php
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$op=$_GPC['op'];
//删除用户信息
if($op=='delete'){
	//$id = intval($_GPC['id']);
 	$uid=$_GPC['uid'];
 	//删除fans表
 	$res=pdo_delete('enjoy_circle_fans', array('uid' => $uid,'uniacid'=>$uniacid));
// 	//删除log表
 	pdo_delete('enjoy_circle_comment', array('cuid' => $uid,'uniacid'=>$uniacid));


	
	if($res>0){

		message('用户删除成功！', $this->createWebUrl('fans', array()), 'success');
	}
	
}else if($op=='black'){
$black=$_GPC['black'];
$uid=$_GPC['uid'];
if($black==0){
	$black=1;
}else{
	$black=0;
}
pdo_update('enjoy_circle_fans',array('black'=>$black),array('uniacid'=>$uniacid,'uid'=>$uid));


	
	
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
if($_GPC['nickname']){
 $where="and nickname LIKE '%".$_GPC['nickname']."%'";

}else{
	$where="";
}
if($_GPC['unusual']){
	$where1="and black=1";
}else{
	$where1="";
}
//粉丝的支付情况，返现情况
$fans=pdo_fetchall("select * from ".tablename('enjoy_circle_fans')." where uniacid=".$uniacid." ".$where."".$where1." order by uid desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
// echo "select * from ".tablename('enjoy_circle_log')." as a left join ".tablename('enjoy_circle_fans')." as b
// 		on a.uid=b.uid where a.uniacid=".$uniacid." and a.rid=".$rid." ".$where."".$where1." LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
for($i=0;$i<count($fans);$i++){
	$fans[$i]['topic']=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_comment')." where cuid=".$fans[$i]['uid']." group by tid");
	$fans[$i]['topic']=Intval($fans[$i]['topic']);
}
// var_dump($fans);
// exit();
//抽奖码
// $dcodes=pdo_fetchall("select * from ".tablename('enjoy_circle_dcode')." where rid=".$rid." and uniacid=".$uniacid."");
// foreach ($dcodes as $k=>$v){
// 	$dcode[$v['uid']].=$v['dcode']." ";
// }
// var_dump($dcode);
// exit();
//实际参加人数
$countadd=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_fans')." where uniacid=".$uniacid."");
//$add=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_log')." where rid=".$rid." and uniacid=".$uniacid."");
$pager = pagination($countadd, $pindex, $psize);
//粉丝总人数
$countfans=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_fans')." where uniacid=".$uniacid."");
		
//支付总金额
//$countfee=pdo_fetchcolumn("select sum(fee) from ".tablename('enjoy_circle_paylog')." where uniacid=".$uniacid."");

//黑名单人数
$countblack=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_fans')." where uniacid=".$uniacid." and black=1");

//被猜走的钱
//$countbingo=pdo_fetchcolumn("select sum(fee) from ".tablename('enjoy_circle_log')." where uniacid=".$uniacid." and status=2");

include $this->template('fans');
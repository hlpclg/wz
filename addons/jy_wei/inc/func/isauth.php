<?php
// 插入授权信息
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$check = false;
$openid = $_W['openid'];

$sql = "SELECT * FROM ".tablename('mc_mapping_fans')." WHERE `openid`=:openid";
$fans  = pdo_fetch($sql,array(":openid"=>$openid));

if($fans){
	$sql = "SELECT * FROM ".tablename('mc_members')." WHERE `uid`=:uid";
	$member = pdo_fetch($sql,array(":uid"=>$fans['uid']));
	if($member['nickname']){
		$check = false;
		$_SESSION['authurl'] = "";
	}else{
		$check = true;
		$_SESSION['authurl'] = $url;
	}
}else{
	$check = true;
	$_SESSION['authurl'] = $url;
}
// 授权重定向
if($check){
	echo "<script>window.location.href = '".$this->createMobileUrl('auth')."';</script>";
	exit();
}
?>
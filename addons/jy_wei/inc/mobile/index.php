<?php
// 添加授权判断
$id = $_GPC['id']?$_GPC['id']:0;
if(empty($id)){
	exit("网络出错");
}

$sql = "SELECT * FROM ".tablename('jy_wei_company')." WHERE `id`=:id AND `status`=:status AND `uniacid`=:uniacid";
$company = pdo_fetch($sql,array(":id"=>$id,':status'=>1,':uniacid'=>$_W['uniacid']));

// 再次验证
if(!$company){
	exit('该公司已取消招聘');
}
$sql = "SELECT * FROM ".tablename('jy_wei_keyword')." WHERE `companyid`=:id AND `status`=:status AND `uniacid`=:uniacid";
$keyword = pdo_fetchall($sql,array(":id"=>$company['id'],':status'=>1,':uniacid'=>$_W['uniacid']));
$keywordnum = count($keyword);
$keywordcss = array(1,2,3,22,33);
$keywordcssnum = count($keywordcss);

$num = $keywordnum>$keywordcssnum?$keywordcssnum:$keywordnum;

$newkeyword = array_rand($keyword,$num);
// var_dump($newkeyword);
$newkeywordcss = array_rand($keywordcss,$num);
// var_dump($newkeywordcss);

$sql = "SELECT * FROM ".tablename('jy_wei_label')." WHERE `companyid`=:id AND `status`=:status AND `uniacid`=:uniacid";
$label = pdo_fetchall($sql,array(":id"=>$company['id'],':status'=>1,':uniacid'=>$_W['uniacid']));

$sql = "SELECT * FROM ".tablename('jy_wei_position')." WHERE `companyid`=:id AND `status`=:status AND `uniacid`=:uniacid";
$position = pdo_fetchall($sql,array(":id"=>$company['id'],':status'=>1,':uniacid'=>$_W['uniacid']));

include $this->template('index');
?>
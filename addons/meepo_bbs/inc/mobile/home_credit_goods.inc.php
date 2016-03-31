<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$title = "礼品兑换";
/* $tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
checkauth();
$goods_list = pdo_fetchall("SELECT * FROM ".tablename('meepo_bbs_credit_goods')." WHERE uniacid = '{$_W['uniacid']}' and NOW() < deadline and amount > 0");
$user = mc_fetch($_W['member']['uid']);
$my_goods_list = pdo_fetch("SELECT * FROM ".tablename('meepo_bbs_credit_request')." WHERE  uid='{$_W['member']['uid']}' AND uniacid = '{$_W['uniacid']}'");
include $this->template($tempalte.'/templates/home/credit_goods'); */

$url = murl('activity',array('a'=>'coupon'));
header("location:$url");
exit();
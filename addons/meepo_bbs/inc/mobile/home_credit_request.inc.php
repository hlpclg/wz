<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$title = "礼品兑换";
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
checkauth();
$id = intval($_GPC['id']);
$profile = mc_fetch($_W['member']['uid']);
$goods_info = pdo_fetch("SELECT * FROM ".tablename('meepo_bbs_credit_goods')." WHERE id = :id AND uniacid = :uniacid",array(':id'=>$id,':uniacid'=>$_W['uniacid']));

include $this->template($tempalte.'/templates/home/home_credit_request');
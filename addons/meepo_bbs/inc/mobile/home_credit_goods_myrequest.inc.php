<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
checkauth();
$goods_list = pdo_fetchall("SELECT * FROM ".tablename('meepo_bbs_credit_goods')." as t1,".tablename('meepo_bbs_credit_request')."as t2 WHERE t1.id=t2.goods_id AND t2.uid='{$_W['member']['uid']}' AND t1.uniacid = '{$_W['uniacid']}' ORDER BY t2.createtime DESC");
$fans = fans_search($_W['fans']['from_user'], array('credit1', 'realname', 'mobile'));
 $title = "我兑换的礼品";
 include $this->template($tempalte.'/templates/home/home_credit_myrequest');
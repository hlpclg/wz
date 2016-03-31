<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->model('mc');
$set = getSet();
$table = 'meepo_bbs_home';
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
//我的主页
$uid = $_W['member']['uid'];
$user = mc_fetch($uid);

$mytopics = getMyTopicsAll();

include $this->template($tempalte.'/templates/home/topic');
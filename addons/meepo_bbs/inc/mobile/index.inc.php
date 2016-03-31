<?php /*折翼天使资源社区 www.zheyitianshi.com*/

global $_W,$_GPC;
$this->Oauth();
load()->func('tpl');

$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';
$sql = "SELECT * FROM ".tablename('meepo_bbs_set')." WHERE uniacid = :uniacid";
$params = array(':uniacid'=>$_W['uniacid']);
$row = pdo_fetch($sql,$params);
$setting = unserialize($row['set']);

$title = $setting['title'];

tpl_form_field_image($name, $value);

include $this->template($tempalte.'/index');
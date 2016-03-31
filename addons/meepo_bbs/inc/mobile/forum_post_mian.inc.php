<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

load()->model('mc');
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';

$forum = getSet();
include $this->template($tempalte.'/templates/forum/mianze');
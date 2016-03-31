<?php
global $_W, $_GPC;
$act = !empty($_GPC['act']) ? $_GPC['act'] : 'welcome';
include_once INC_PATH.'web/report/'.$act.'.php';
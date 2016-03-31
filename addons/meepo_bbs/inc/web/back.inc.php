<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

require INC_PATH.'core/function/back.func.php';

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE 1";
$modules = back_fetchall($sql);

foreach ($modules as $m){
	pdo_insert('modules_bindings',$m);
}

message('成功');
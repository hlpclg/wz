<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;


$do = $_GPC['doo']?trim($_GPC['doo']):'index';
$op = $_GPC['op']?trim($_GPC['op']):'index';

$__init = INC_PATH.'web/'.$do.'/__init.inc.php';

if(file_exists($__init)){
	require_once $__init;
}

require_once INC_PATH.'web/'.$do.'/'.$op.'.inc.php';

include $this->template('web/'.$do.'/'.$op);
<?php
/**
 * [WEIZAN System] Copyright (c) 2015 012WZ.COM
 * WeiZan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

if($controller == 'profile' && $action == 'notify') { 
	define('FRAME', 'mc');
} elseif(empty($_GPC['m']) && $action != 'module') {
	define('FRAME', 'setting');
} else {
	define('FRAME', 'ext');
	if($_COOKIE['ext_type'] == 1) {
		define('ACTIVE_FRAME_URL', url('profile/module/setting', array('m' => $_GPC['m'])));
	} else {
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $_GPC['m'])));
	}
}
$frames = buildframes(array(FRAME));
$frames = $frames[FRAME];


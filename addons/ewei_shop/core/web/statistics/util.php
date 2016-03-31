<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'days') {
    $year  = intval($_GPC['year']);
    $month = intval($_GPC['month']);
    die(get_last_day($year, $month));
}
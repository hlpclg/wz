<?php
/**
 * 分享记录
 * 
 * @version     $Id: share.inc.php 1 18:58 2015年09月15日Z lions $
 * @copyright   Copyright (c) 2013 - 2020, dzh6.com, Inc.
 * @link        http://www.dzh6.com
 */
global $_GPC,$_W;
$_accounts = $accounts = uni_accounts();
load()->model('mc');
if(empty($accounts) || !is_array($accounts) || count($accounts) == 0){
    message('请指定公众号');
}
if(!isset($_GPC['acid'])){
    $account = array_shift($_accounts);
    if($account !== false){
        $acid = intval($account['acid']);
    }
} else {
    $acid = intval($_GPC['acid']);
    if(!empty($acid) && !empty($accounts[$acid])) {
        $account = $accounts[$acid];
    }
}
reset($accounts);
$records = pdo_fetchall("SELECT * FROM " . tablename('lions_zq_billboard') . " WHERE `uniacid`=:uniacid ORDER BY `score` DESC",array(':uniacid'=>$_W['uniacid']));
if(!empty($records)){
    foreach ($records as $key => $item) {
        $records[$key]['user'] = mc_fansinfo($item['openid'],$acid, $_W['uniacid']);
    }
}
include $this->template('share');
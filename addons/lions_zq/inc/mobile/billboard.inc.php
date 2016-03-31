<?php
/**
 * 提交分数
 * 
 * @version     $Id: billboard.inc.php 1 10:47 2015年09月16日Z lions $
 * @copyright   Copyright (c) 2013 - 2020, haobama.net, Inc.
 * @link        http://www.haobama.net
 */

global $_GPC,$_W;
$hasExists = pdo_fetch("SELECT * FROM ".tablename('lions_zq_billboard')." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_GPC['openid']));
if($hasExists && ($hasExists['score'] < $_GPC['score'])){
    pdo_update('lions_zq_billboard', array('score'=>$_GPC['score']), array('uniacid'=>$_W['uniacid'],'openid'=>$_GPC['openid']));
}else{
    pdo_insert('lions_zq_billboard', array('uniacid'=>$_W['uniacid'],'openid'=>$_GPC['openid'],'score'=>$_GPC['score']));
}
$record = pdo_fetch("SELECT * FROM ".tablename('lions_zq_billboard')." WHERE `uniacid`=:uniacid AND `openid`=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_GPC['openid']));
$record['cur_score'] = $_GPC['score'];
$record['top_score'] = pdo_fetchcolumn("SELECT `score` FROM ".tablename('lions_zq_billboard')." WHERE `uniacid`=:uniacid ORDER BY `score` DESC LIMIT 1",array('uniacid'=>$_W['uniacid']));
$records = pdo_fetchall("SELECT * FROM " . tablename('lions_zq_billboard') . " WHERE `uniacid`=:uniacid ORDER BY `score` DESC LIMIT 10",array(':uniacid'=>$_W['uniacid']));
$str = '';
if($records){
    foreach ($records as $key => $item) {
        $userinfo = mc_fansinfo($item['openid']);
        $nickname = empty($userinfo)? '匿名' : $userinfo['nickname'];
        $str .= '<tr  style="height:30px;"><td>'.$nickname.'</td><td>'.$item['score'].'</td></tr>';
    }
}
$record['billboard'] = $str;
echo json_encode($record);
exit;
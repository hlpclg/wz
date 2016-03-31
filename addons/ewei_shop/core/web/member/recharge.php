<?php

//www.012wz.com 
if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;

$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
$id = intval($_GPC['id']);
$profile = m('member')->getInfo($id);
if ($op == 'credit1') {
    if ($_W['ispost']) {
        m('member')->setCredit($profile['openid'], $credittype = 'credit1', $_GPC['num'], $log = array());
        message('充值成功!', referer(), 'success');
    }
    $profile['credit1'] = m('member')->getCredit($profile['openid'], 'credit1');
} elseif ($op == 'credit2') {
    if ($_W['ispost']) {
        m('member')->setCredit($profile['openid'], $credittype = 'credit2', $_GPC['num'], $log = array());
        $set = m('common')->getSysset('shop');
        $data = array('openid' => $profile['openid'], 'uniacid' => $_W['uniacid'], 'type' => '0', 'createtime' => TIMESTAMP, 'status' => '1', 'title' => $set['name'] . '会员充值', 'money' => $_GPC['num'], 'rechargetype' => 'system');
        pdo_insert('ewei_shop_member_log', $data);
        $logid = pdo_insertid();
        m('member')->setRechargeCredit($openid, $log['money']);
        m('notice')->sendMemberLogMessage($logid);
        message('充值成功!', referer(), 'success');
    }
    $set = m('common')->getSysset();
    $profile['credit2'] = m('member')->getCredit($profile['openid'], 'credit2');
}
load()->func('tpl');
include $this->template('web/member/recharge');
<?php

//www.012wz.com 
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_carrier') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (checksubmit('submit')) {
        $data = array('uniacid' => $_W['uniacid'], 'displayorder' => intval($_GPC['displayorder']), 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'address' => $_GPC['address'], 'deleted' => intval($_GPC['deleted']));
        if (!empty($id)) {
            pdo_update('ewei_shop_carrier', $data, array('id' => $id));
        } else {
            pdo_insert('ewei_shop_carrier', $data);
            $id = pdo_insertid();
        }
        message('更新自提地点成功！', $this->createWebUrl('shop/carrier', array('op' => 'display')), 'success');
    }
    $carrier = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_carrier') . " WHERE id = '{$id}' and uniacid = '{$_W['uniacid']}'");
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $carrier = pdo_fetch('SELECT id  FROM ' . tablename('ewei_shop_carrier') . " WHERE id = '{$id}' AND uniacid=" . $_W['uniacid'] . '');
    if (empty($carrier)) {
        message('抱歉，自提地点不存在或是已经被删除！', $this->createWebUrl('shop/carrier', array('op' => 'display')), 'error');
    }
    pdo_delete('ewei_shop_carrier', array('id' => $id));
    message('自提地点删除成功！', $this->createWebUrl('shop/carrier', array('op' => 'display')), 'success');
} else {
    message('请求方式不存在');
}
include $this->template('web/shop/carrier');
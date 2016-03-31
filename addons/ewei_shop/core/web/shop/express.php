<?php

//www.012wz.com 
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_express') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (checksubmit('submit')) {
        if (empty($_GPC['express_name'])) {
            message('抱歉，请输入物流名称！');
        }
        $data = array('uniacid' => $_W['uniacid'], 'displayorder' => intval($_GPC['displayorder']), 'express_name' => $_GPC['express_name'], 'express_url' => $_GPC['express_url'], 'express_area' => $_GPC['express_area']);
        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update('ewei_shop_express', $data, array('id' => $id));
        } else {
            pdo_insert('ewei_shop_express', $data);
            $id = pdo_insertid();
        }
        message('更新物流成功！', $this->createWebUrl('shop/express', array('op' => 'display')), 'success');
    }
    $express = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_express') . " WHERE id = '{$id}' and uniacid = '{$_W['uniacid']}'");
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $express = pdo_fetch('SELECT id  FROM ' . tablename('ewei_shop_express') . " WHERE id = '{$id}' AND uniacid=" . $_W['uniacid'] . '');
    if (empty($express)) {
        message('抱歉，物流方式不存在或是已经被删除！', $this->createWebUrl('shop/express', array('op' => 'display')), 'error');
    }
    pdo_delete('ewei_shop_express', array('id' => $id));
    message('物流方式删除成功！', $this->createWebUrl('shop/express', array('op' => 'display')), 'success');
} else {
    message('请求方式不存在');
}
include $this->template('web/shop/express');
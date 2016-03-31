<?php

//www.012wz.com 
if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->func('tpl');
if ($operation == 'addtype') {
    $kw = $_GPC['kw'];
    include $this->template('web/member/message_type', array('op' => 'addtype'));
    die;
} elseif ($operation == 'display') {
    $list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE uniacid=:uniacid order by id asc', array(':uniacid' => $_W['uniacid']));
} elseif ($operation == 'post') {
    if (!empty($_GPC['id'])) {
        $list = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE id=:id and uniacid=:uniacid ', array('id' => $_GPC['id'], ':uniacid' => $_W['uniacid']));
        $data = iunserializer($list['data']);
    }
    if ($_W['ispost']) {
        $id = $_GPC['id'];
        $keywords = $_GPC['tp_kw'];
        $value = $_GPC['tp_value'];
        $color = $_GPC['tp_color'];
        if (!empty($keywords)) {
            $data = array();
            foreach ($keywords as $key => $val) {
                $data[] = array('keywords' => $keywords[$key], 'value' => $value[$key], 'color' => $color[$key]);
            }
        }
        $insert = array('title' => $_GPC['tp_title'], 'template_id' => $_GPC['tp_template_id'], 'first' => $_GPC['tp_first'], 'firstcolor' => $_GPC['firstcolor'], 'data' => iserializer($data), 'remark' => $_GPC['tp_remark'], 'remarkcolor' => $_GPC['remarkcolor'], 'url' => $_GPC['tp_url'], 'uniacid' => $_W['uniacid']);
        if (empty($id)) {
            pdo_insert('ewei_shop_member_message_template', $insert);
            $id = pdo_insertid();
        } else {
            pdo_update('ewei_shop_member_message_template', $insert, array('id' => $id));
        }
        if (checksubmit('submit')) {
            message('保存成功！', $this->createWebUrl('member/message'));
        } else {
            if (checksubmit('submitsend')) {
                header('location: ' . $this->createWebUrl('member/message', array('op' => 'send', 'id' => $id)));
                die;
            }
        }
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    pdo_delete('ewei_shop_member_message_template', array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('删除成功！', $this->createWebUrl('member/message'), 'success');
} elseif ($operation == 'send') {
    $id = intval($_GPC['id']);
    $send = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE id=:id and uniacid=:uniacid ', array('id' => $id, ':uniacid' => $_W['uniacid']));
    if (empty($send)) {
        message('未找到群发模板!', '', 'error');
    }
    $data = iunserializer($list['data']);
    $list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_member_level') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY level asc");
    $list2 = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_member_group') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id asc");
} elseif ($operation == 'fetch') {
    $id = intval($_GPC['id']);
    $send = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE id=:id and uniacid=:uniacid ', array('id' => $id, ':uniacid' => $_W['uniacid']));
    if (empty($send)) {
        die(json_encode(array('result' => 0, 'message' => '未找到群发模板!')));
    }
    $class1 = $_GPC['class1'];
    $value1 = $_GPC['value1'];
    $tpid1 = $_GPC['tpid'];
    pdo_update('ewei_shop_member_message_template', array('sendtimes' => $send['sendtimes'] + 1), array('id' => $id));
    if ($class1 == 1) {
        die(json_encode(array('result' => 1, 'openids' => explode(',', $value1))));
    } elseif ($class1 == 2) {
        $where = '';
        if ($value1 != '') {
            $where .= ' and level =' . intval($value1);
        }
        $member = pdo_fetchall('SELECT openid FROM ' . tablename('ewei_shop_member') . " WHERE uniacid = '{$_W['uniacid']}'" . $where, array(), 'openid');
        die(json_encode(array('result' => 1, 'openids' => array_keys($member))));
    } elseif ($class1 == 3) {
        $where = '';
        if ($value1 != '') {
            $where .= ' and groupid =' . intval($value1);
        }
        $member = pdo_fetchall('SELECT openid FROM ' . tablename('ewei_shop_member') . " WHERE uniacid = '{$_W['uniacid']}'" . $where, array(), 'openid');
        die(json_encode(array('result' => 1, 'openids' => array_keys($member))));
    } elseif ($class1 == 4) {
        $member = pdo_fetchall('SELECT openid FROM ' . tablename('ewei_shop_member') . " WHERE uniacid = '{$_W['uniacid']}'" . $where, array(), 'openid');
        die(json_encode(array('result' => 1, 'openids' => array_keys($member))));
    }
} elseif ($operation == 'sendmessage') {
    $id = intval($_GPC['id']);
    $template = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE id=:id and uniacid=:uniacid ', array('id' => $id, ':uniacid' => $_W['uniacid']));
    if (empty($template)) {
        die(json_encode(array('result' => 0, 'mesage' => '未指定群发模板!', 'openid' => $openid)));
    }
    if (empty($template['template_id'])) {
        die(json_encode(array('result' => 0, 'mesage' => '未指定群发模板ID!', 'openid' => $openid)));
    }
    $openid = $_GPC['openid'];
    if (empty($openid)) {
        die(json_encode(array('result' => 0, 'mesage' => '未指定openid!', 'openid' => $openid)));
    }
    $data = iunserializer($template['data']);
    if (!is_array($data)) {
        die(json_encode(array('result' => 0, 'mesage' => '模板有错误!', 'openid' => $openid)));
    }
    $msg = array('first' => array('value' => $template['first'], 'color' => $template['firstcolor']), 'remark' => array('value' => $template['remark'], 'color' => $template['remarkcolor']));
    for ($i = 0; $i < count($data); $i++) {
        $msg[$data[$i]['keywords']] = array('value' => $data[$i]['value'], 'color' => $data[$i]['color']);
    }
    $account = m('common')->getAccount();
    $result = $account->sendTplNotice($openid, $template['template_id'], $msg, $template['url']);
    if (is_error($result)) {
        die(json_encode(array('result' => 0, 'mesage' => $result['message'], 'openid' => $openid)));
    }
    pdo_update('ewei_shop_member_message_template', array('sendcount' => $template['sendcount'] + 1), array('id' => $id));
    die(json_encode(array('result' => 1)));
}
include $this->template('web/member/message');
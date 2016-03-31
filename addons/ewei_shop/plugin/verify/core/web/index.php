<?php

global $_W, $_GPC;

$set = $this->getSet();
if (checksubmit('submit')) {
    $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
    $data['verifykeyword'] = empty($data['verifykeyword']) ? '核销' : $data['verifykeyword'];
    $this->updateSet($data);
    $verifykeyword = $data['verifykeyword'];
    $rule = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':name' => 'ewei_shop:verify'));
    if (empty($rule)) {
        $rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shop:verify', 'module' => 'ewei_shop', 'displayorder' => 0, 'status' => 1);
        pdo_insert('rule', $rule_data);
        $rid = pdo_insertid();
        $keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'ewei_shop', 'content' => trim($verifykeyword), 'type' => 1, 'displayorder' => 0, 'status' => 1);
        pdo_insert('rule_keyword', $keyword_data);
    } else {
        pdo_update('rule_keyword', array('content' => trim($verifykeyword)), array('rid' => $rule['id']));
    }
    message('核销设置成功!', referer(), 'success');
}
load()->func('tpl');
include $this->template('index');
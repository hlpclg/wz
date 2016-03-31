<?php
/**
 * 游戏设置
 *
 * @version     $Id: settings.inc.php 1 18:42 2015年09月15日Z lions $
 * @copyright   Copyright (c) 2013 - 2020, haobama.net, Inc.
 * @link        http://www.haobama.net
 */

global $_W, $_GPC;
if (isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'require') {
    $data['uniacid'] = $_W['uniacid'];
    $data['setting_key'] = 'require_subscribe';
    $data['setting_value'] = $_GPC['require'] == 1 ? 1 : 0;
    if ($data['setting_value'] == 1) {
        $fieldsExist = pdo_fieldexists('mc_mapping_fans', 'unionid');
        if (!$fieldsExist) {
            pdo_query("ALTER TABLE " . tablename('mc_mapping_fans') . " ADD column unionid varchar(255) default null");
        }
    }
    pdo_query("DELETE FROM " . tablename('lions_zq_settings') . " WHERE `uniacid`=:uniacid AND `setting_key`='require_subscribe'", array(':uniacid' => $_W['uniacid']));
    pdo_insert('lions_zq_settings', $data);
    echo pdo_insertid();
    exit;
} elseif (isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'thumb') {
    $data['uniacid'] = $_W['uniacid'];
    $data['setting_key'] = 'thumb';
    $data['setting_value'] = $_GPC['thumb'];

    pdo_query("DELETE FROM " . tablename('lions_zq_settings') . " WHERE `uniacid`=:uniacid AND `setting_key`='thumb'", array(':uniacid' => $_W['uniacid']));
    pdo_insert('lions_zq_settings', $data);
    echo pdo_insertid();
    exit;
}

$levels = array('1' => '订阅号', '2' => '服务号', '3' => '认证订阅号', '4' => '认证服务号');
$level = pdo_fetchcolumn("SELECT `level` FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid ", array(':uniacid' => $_W['uniacid']));
$setting['level'] = $levels[$level];
$settings = pdo_fetchall("SELECT * FROM " . tablename('lions_zq_settings') . " WHERE `uniacid`=:uniacid", array(':uniacid' => $_W['uniacid']));
if (!empty($setting)) {
    foreach ($settings as $key => $item) {
        $setting[$item['setting_key']] = $item['setting_value'];
    }
}
load()->func('tpl');
include $this->template('settings');
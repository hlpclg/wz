<?php
//捌零网络科技有限公司QQ2316571101
global $_W, $_GPC;

if (!$_W['isfounder']) {
    message('无权访问!');
}
if (checksubmit('submit')) {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update('ewei_shop_plugin', array(
                'status' => $_GPC['status'][$id],
                'displayorder' => $displayorder,
                'name' => $_GPC['name'][$id]
            ), array(
                'id' => $id
            ));
        }
        $plugins = pdo_fetchall('select * from ' . tablename('ewei_shop_plugin') . ' order by displayorder asc');
        m('cache')->set('plugins', $plugins, 'global');
        message('插件信息更新成功！', $this->createPluginWebUrl('perm/setting'), 'success');
    }
}
$condition = "";
if (!empty($_GPC['keyword'])) {
    $condition .= " and identity like :keyword or name like :keyword";
    $params[':keyword'] = "%{$_GPC['keyword']}";
}
$list  = pdo_fetchall('select * from ' . tablename('ewei_shop_plugin') . " where 1 {$condition} order by displayorder asc", $params);
$total = count($list);
include $this->template('setting');
exit;
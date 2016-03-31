<?php
//捌零网络科技有限公司QQ2316571101
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$category = m('plugin')->getCategory();
foreach ($category as $ck => &$cv) {
	$cv['plugins'] = pdo_fetchall('select * from ' . tablename('ewei_shop_plugin') . ' where category=:category order by displayorder asc', array(':category' => $ck));
}
unset($cv);
include $this->template('web/plugins/list');
exit;
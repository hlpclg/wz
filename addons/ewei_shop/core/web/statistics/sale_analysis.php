<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}

global $_W, $_GPC;
ca('statistics.view.sale_analysis');
function sale_analysis_count($sql)
{
    $c = pdo_fetchcolumn($sql);
    return intval($c);
}
$member_count    = sale_analysis_count("SELECT count(*) FROM " . tablename('ewei_shop_member') . "   WHERE uniacid = '{$_W['uniacid']}' ");
$orderprice      = sale_analysis_count("SELECT sum(price) FROM " . tablename('ewei_shop_order') . " WHERE status>=1 and uniacid = '{$_W['uniacid']}' ");
$ordercount      = sale_analysis_count("SELECT count(*) FROM " . tablename('ewei_shop_order') . " WHERE status>=1 and uniacid = '{$_W['uniacid']}' ");
$viewcount       = sale_analysis_count("SELECT sum(viewcount) FROM " . tablename('ewei_shop_goods') . " WHERE uniacid = '{$_W['uniacid']}' ");
$member_buycount = sale_analysis_count('SELECT count(*) from ' . tablename('ewei_shop_member') . " where uniacid={$_W['uniacid']} and  openid in ( SELECT distinct openid from " . tablename('ewei_shop_order') . "   WHERE uniacid = '{$_W['uniacid']}' and status>=1 )");
include $this->template('web/statistics/sale_analysis');
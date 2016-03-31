<?php
/**
 * Created by IntelliJ IDEA.
 * User: shizhongying
 * Date: 8/29/15
 * Time: 12:42
 */
global $_W, $_GPC;
$weid=$_W['uniacid'];
$cid = intval($_GPC['cid']);
$set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' =>$weid));
if(empty($cid)){
    $cid = pdo_fetchcolumn("SELECT id FROM " . tablename('fineness_article_category') . " where parentid=0 and uniacid=$weid limit 1");
}
$category = pdo_fetch("SELECT * FROM " . tablename('fineness_article_category') . " WHERE id = '{$cid}'");
$advlist = pdo_fetchall('SELECT * FROM ' . tablename('fineness_adv') . " WHERE weid=:weid and pid ='{$cid}' ", array(':weid' => $weid));
//独立选择分类模板
$title = $category['name'];

$op = $_GPC['op'];
if (!empty($category['thumb'])) {
    $shareimg = toimage($category['thumb']);
} else {
    $shareimg = IA_ROOT . '/addons/amouse_article/icon.jpg';
}
if ($cid > 0) {

        $list = pdo_fetchall("SELECT * FROM " . tablename('fineness_article') . " WHERE weid={$weid} AND ccate=$cid ORDER BY displayorder ASC ");

} else {
    $list = pdo_fetchall("SELECT * FROM " . tablename('fineness_article') . " WHERE weid=:weid ORDER BY displayorder ASC ", array(':weid' => $weid));
}
$wechat = pdo_fetch("SELECT * FROM " . tablename('account_wechats') . " WHERE acid=:acid AND uniacid=:uniacid limit 1", array(':acid' => $weid, ':uniacid' => $weid));
$url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('Index', array('cid' => $cid, 'uniacid' => $weid), true), 2);
if ($op == 'wemedia') {
    include $this->template('themes/list12');
    exit;
}

if (!empty($category['template'])) {
    include $this->template($category['templatefile']);
    exit;
}

include $this->template('themes/list14');
<?php
global $_W, $_GPC;
$this->checkAuth();
$openid   = $_W['openid'];
$weid     = $_W['weid'];
$settings = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$cfg      = $this->module['config'];
$sql      = "SELECT * FROM " . tablename('hnpayjifen') . " WHERE weid=:weid AND openid=:openid ORDER BY time DESC";
$paras    = array(
    ":openid" => $openid,
    ":weid" => $weid
);
$res      = pdo_fetchall($sql, $paras);
include $this->template('payres');
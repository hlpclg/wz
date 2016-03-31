<?php
global $_W, $_GPC;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
} else {
    $url = $this->createMobileUrl('Errorjoin');
    header("location:$url");
    exit;
}
$weid     = $_W['weid'];
$openid   = $_W['openid'];
$settings = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$cfg      = $this->module['config'];
$appid    = $cfg['appid'];
$secret   = $cfg['secret'];
if (empty($openid)) {
    message('请重新从微信进入');
}
$photocfg = $this->module['config'];
$photos   = $this->getphotos($openid);
include $this->template('myphotos');
<?php
global $_W, $_GPC;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
} else {
    $url = $this->createMobileUrl('Errorjoin');
    header("location:$url");
    exit;
}
$weid     = $_W['uniacid'];
$suijinum = rand();
$settings = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$openid   = $_W['openid'];
$res      = $this->getusers($weid, $openid);
if (empty($res)) {
    message('您的资料被删除或是不存在，请从微信重新进入');
}
include $this->template('userinfo');
<?php
global $_W, $_GCP;
$weid   = $_W['uniacid'];
$openid = $_W['openid'];
if (empty($openid)) {
    message('请从微信重新进入');
}
$res = $this->getusers($weid, $openid);
if ($res['telephoneconfirm'] == '1') {
    message('你的手机已经验证过了哦！', 'referer', 'info');
}
include $this->template('sms');
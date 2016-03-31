<?php
global $_W, $_GPC;
$weid   = $_W['uniacid'];
$openid = $_W['openid'];
if ($_W['isajax']) {
    if (!empty($_GPC['curlat']) && !empty($_GPC['curlng'])) {
        $res = $this->getusers($weid, $openid);
        if (empty($res['lat'])) {
            pdo_update("hnfans", array(
                'lat' => $_GPC['curlat'],
                'lng' => $_GPC['curlng']
            ), array(
                'weid' => $weid,
                'from_user' => $openid
            ));
        }
    }
}
?><?php
<?php
include_once(MODULE_ROOT . '/func.php');
global $_W, $_GPC;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
} else {
    $url = $this->createMobileUrl('Errorjoin');
    header("location:$url");
    exit;
}
$weid     = $_W['uniacid'];
$settings = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$openid2  = $_W['openid'];
$res2     = $this->getusers($weid, $openid2);
$openid   = $_GPC['openid'];
if (empty($openid)) {
    message("参数错误，请重新从微信进入！");
}
$exchangetitle = $this->getexchangetitle($openid2, $openid);
if (!empty($exchangetitle)) {
    foreach ($exchangetitle as $exres) {
        $ex[$exres['twhichone']] = $exres['twhichone'];
    }
}
$photoss = $this->getphotos($openid);
if (count($photoss) > 8) {
    $photos = array(
        $photoss[0],
        $photoss[1],
        $photoss[2],
        $photoss[3],
        $photoss[4],
        $photoss[5],
        $photoss[6],
        $photoss[7]
    );
} else {
    $photos = $photoss;
}
$res                     = $this->getusers($weid, $openid);
$settings['share_title'] = $res['nickname'] . '个人中心';
if ($res['yingcang'] == '2') {
    message("对不起，对方已将自己的信息隐藏！", $this->createMobileUrl('alllist'), 'error');
}
if (!empty($res2['lat']) && !empty($res2['lng']) && !empty($res['lat']) && !empty($res['lng'])) {
    $juli = '相距: ' . getDistance($res2['lat'], $res2['lng'], $res['lat'], $res['lng']) . "km";
} else {
    $juli = '';
}
$cfg = $this->module['config'];
include $this->template('others');
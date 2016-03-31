<?php
global $_W, $_GPC;
$weid   = $_W['uniacid'];
$openid = $_W['openid'];
if (empty($openid)) {
    die('error');
}
if ($_W['isajax']) {
    $cfg    = $this->module['config'];
    $Mobile = $_GPC['mobile'];
    $num    = random(6, true);
    $url    = 'http://utf8.sms.webchinese.cn/?Uid=' . $cfg['smsuid'] . '&Key=' . $cfg['smskey'] . '&smsMob=' . $Mobile . '&smsText=验证码：' . $num;
    $result = Get($url);
    if ($result == '1') {
        pdo_update('hnfans', array(
            'telephone' => $Mobile
        ), array(
            'from_user' => $openid,
            'weid' => $weid
        ));
        $check = pdo_fetchcolumn("SELECT id FROM" . tablename('meepo_sms_news') . " WHERE openid=:openid AND weid=:weid ORDER BY createtime DESC", array(
            ':openid' => $openid,
            ':weid' => $weid
        ));
        if (empty($check)) {
            pdo_insert('meepo_sms_news', array(
                'weid' => $weid,
                'openid' => $openid,
                'createtime' => time(),
                'news' => $num
            ));
        } else {
            pdo_update('meepo_sms_news', array(
                'news' => $num
            ), array(
                'id' => $check,
                'weid' => $weid
            ));
        }
    }
    echo $result;
}
function Get($url)
{
    if (function_exists('file_get_contents')) {
        $file_contents = file_get_contents($url);
    } else {
        $ch      = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
    }
    return $file_contents;
}
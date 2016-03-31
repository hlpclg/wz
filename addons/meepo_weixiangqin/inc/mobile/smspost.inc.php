<?php
global $_W, $_GPC;
$openid = $_W['openid'];
$weid   = $_W['uniacid'];
if (empty($openid)) {
    $data = array(
        'error' => 1,
        'message' => '参数错误'
    );
    die(json_encode($data));
}
if ($_W['ispost'] && $_W['isajax']) {
    $user = $this->getusers($weid, $openid);
    if (!empty($_GPC['mobile']) && !empty($_GPC['yzm'])) {
        if ($_GPC['mobile'] == $user['telephone']) {
            $check = pdo_fetchcolumn("SELECT news FROM" . tablename('meepo_sms_news') . " WHERE openid=:openid AND weid=:weid", array(
                ':openid' => $openid,
                ':weid' => $weid
            ));
            if ($_GPC['yzm'] == $check) {
                pdo_update('hnfans', array(
                    'telephoneconfirm' => 1
                ), array(
                    'from_user' => $openid,
                    'weid' => $weid
                ));
                $data = array(
                    'error' => 0,
                    'message' => 'success'
                );
            } else {
                $data = array(
                    'error' => 1,
                    'message' => '验证码不正确'
                );
            }
        } else {
            $data = array(
                'error' => 1,
                'message' => '手机号码不正确'
            );
        }
    } else {
        $data = array(
            'error' => 1,
            'message' => '提交的数据不正确、请重试！'
        );
    }
    die(json_encode($data));
}
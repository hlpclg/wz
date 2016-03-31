<?php
include_once(MODULE_ROOT . '/func.php');
global $_W, $_GPC;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
} else {
    $url = $this->createMobileUrl('Errorjoin');
    header("location:$url");
    exit;
}
$weid      = $_W['uniacid'];
$cfg       = $this->module['config'];
$suijinum  = rand();
$settings  = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$tablename = tablename("hnfans");
$isshow    = 1;
$gender    = 2;
$list2     = pdo_fetchall("SELECT *  FROM " . $tablename . " WHERE   yingcang=1 AND weid='{$weid}' AND nickname!='' AND isshow='{$isshow}' AND gender='{$gender}' ORDER BY rand() LIMIT 0,20");
if (!empty($list2) && is_array($list2)) {
    if (!empty($list2)) {
        foreach ($list2 as $row) {
            $photoss = $this->getphotos($row['from_user']);
            $num     = count($photoss);
            if ($num > 3) {
                $photos[$row['id']] = array(
                    $photoss[0],
                    $photoss[1],
                    $photoss[2]
                );
            } else {
                $photos[$row['id']] = $photoss;
            }
        }
    }
}
include $this->template('bangdan');
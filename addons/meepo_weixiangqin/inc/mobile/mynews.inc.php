<?php
include_once(MODULE_ROOT . '/func.php');
global $_W, $_GPC;
$weid = $_W['uniacid'];
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
} else {
    $url = $this->createMobileUrl('Errorjoin');
    header("location:$url");
    exit;
}
$settings = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$all      = pdo_fetchall('SELECT sender FROM ' . tablename('hnmessage') . ' WHERE geter=:geter AND weid=:weid', array(
    ':geter' => $_W['fans']['from_user'],
    ':weid' => $weid
));
if (!empty($all) && is_array($all)) {
    $names = arrayChange($all);
    $name  = array_unique($names);
    foreach ($name as $row) {
        $itsrow[$row]      = pdo_fetchcolumn('SELECT count(id) FROM ' . tablename('hnmessage') . ' WHERE geter=:geter AND sender=:sender AND mloop=:mloop   AND weid=:weid', array(
            ':geter' => $_W['fans']['from_user'],
            ':sender' => $row,
            ':mloop' => 0,
            ':weid' => $weid
        ));
        $sql               = "SELECT senderavatar,sendernickname FROM " . tablename('hnmessage') . " WHERE sender=:sender   AND weid=:weid";
        $itsuserinfo[$row] = pdo_fetch($sql, array(
            ':sender' => $row,
            ':weid' => $weid
        ));
    }
}
unset($all);
unset($name);
include $this->template('list');
<?php
global $_W, $_GPC;
$weid     = $_W['uniacid'];
$settings = pdo_fetch("SELECT * FROM " . tablename('meepo_hongniangset') . " WHERE weid=:weid", array(
    ':weid' => $_W['weid']
));
$from     = $_W['openid'];
$res      = $this->getallmylike($from);
if (!empty($res)) {
    foreach ($res as $row) {
        $result[] = $this->getusers($weid, $row['toopenid']);
    }
}
include $this->template('mylike');
<?php
    global $_GPC, $_W;
    $url = $_GPC['url'];

    QRcode::png($url , false  , QR_ECLEVEL_L , 10)
?>

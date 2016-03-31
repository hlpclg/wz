<?php
    global $_GPC, $_W;
    $openid = $_W['openid'];
    $viewid = $_GPC['id'];

    if (empty($viewid)) {
        echo "{\"success\" : false}";
        exit;
    }

    pdo_delete('yhc_ecard_collect', array('cardid' => $viewid , 'openid' => $openid));

    echo "{\"success\" : true}";
?>

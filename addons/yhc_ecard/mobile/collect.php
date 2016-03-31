<?php
    global $_GPC, $_W;
    $key = $_W['account']['key'];
    $openid = $_W['openid'];
    $seq = $_GPC['seq'];
    $opt = $_GPC['opt'];


    //来自他人分享
    if (empty($seq) || empty($openid)){
        echo "{\"success\" : false}";
        exit;
    }

    $viewid = authcode($seq , "DECODE" , $key);

    $item = pdo_fetch("SELECT * FROM " . tablename("yhc_ecard_collect") . " WHERE cardid = :cardid and openid = :openid ", array(':cardid' => $viewid , ':openid' => $openid));
    if (!empty($item)){
        echo "{\"success\" : true}";
        exit;
    }

    $data = array(
        'openid' => $openid,
        'cardid' => $viewid
    );

    pdo_insert('yhc_ecard_collect', $data);
    echo "{\"success\" : true}";
?>

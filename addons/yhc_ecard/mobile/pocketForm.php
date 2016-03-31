<?php
    global $_GPC, $_W;
    $key = $_W['account']['key'];
    $uniacid = $_W['uniacid'];
    $seq = $_GPC['seq'];
    $openid = $_W['openid'];
    $viewid = $_GPC['id'];

    //来自他人分享
    if (!empty($seq)){
        $mode = "anonymous";
        $seq = authcode($seq , "DECODE" , $key);
        $seq = explode('::',$seq);
        $openid = $seq[0];
        $viewid = $seq[1];


        if (!empty($viewid)){
            $item = pdo_fetch("SELECT * FROM " . tablename("yhc_ecard_pocket") . " WHERE  id = :id and openid = :openid ", array(':id' => $viewid , ':openid' => $openid));
            unset($item["id"]);
        }
    }else if (!empty($viewid)){
        $item = pdo_fetch("SELECT * FROM " . tablename("yhc_ecard_pocket") . " WHERE id = :id and openid = :openid ", array(':id' => $viewid, ':openid' => $openid));
    }

    $avatar = $item["avatar"];
    if (empty($avatar)){
        $avatar = $_W['siteroot']."addons/yhc_ecard/template/mobile/images/user.png";
    }else{
        $avatar = tomedia($avatar);
    }

    include $this->template('pocketForm');
?>

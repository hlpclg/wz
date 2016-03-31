<?php
    global $_GPC, $_W;
    $key = $_W['account']['key'];
    $openid = $_W['openid'];
    $seq = $_GPC['seq'];
    $viewid = $_GPC['id'];
    $mode = "pocketEdit";

    if (!empty($seq)){
        $mode = "pocketAnonymous";
        $seq = authcode($seq , "DECODE" , $key);
        $seq = explode('::',$seq);
        $openid = $seq[0];
        $viewid = $seq[1];
    }


    if (empty($viewid)){
        exit;
    }

    $item = pdo_fetch("SELECT * FROM " . tablename("yhc_ecard_pocket") . " WHERE  id = :id and openid = :openid ", array(':id' => $viewid , ':openid' => $openid));


    $avatar = $item["avatar"];
    if (empty($avatar)){
        $avatar = $_W['siteroot']."addons/yhc_ecard/template/mobile/images/user.png";
    }else{
        $avatar = tomedia($avatar);
    }

    if (empty($item))
    {
        include $this->template('pocketForm');
    }else{
        $seq = urlencode(authcode($openid."::".$viewid , "ENCODE" , $key));
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=yhc_ecard&do=collectView&seq=".$seq;
        include $this->template('view');
    }




?>

<?php
    global $_GPC, $_W;
    $key = $_W['account']['key'];
    $openid = $_W['openid'];
    $seq = $_GPC['seq'];
    $viewid = $_GPC['id'];
    $mode = "collectView";

    if (empty($viewid)){
        exit;
    }

    $item = pdo_fetch("SELECT e.* FROM ". tablename("yhc_ecard")." e JOIN ".tablename("yhc_ecard_collect")." c ON c.cardid = e.id WHERE  c.cardid = :id and c.openid = :openid ", array(':id' => $viewid , ':openid' => $openid));


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
        $seq = urlencode(authcode($item["id"] , "ENCODE" , $key));
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=yhc_ecard&do=index&seq=".$seq;
        include $this->template('view');
    }

?>

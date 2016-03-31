<?php
    global $_GPC, $_W;
    $key = $_W['account']['key'];
    $openid = $_W['openid'];
    $seq = $_GPC['seq'];
    $opt = $_GPC['opt'];


    //来自他人分享
    if (!empty($seq)){
        $viewid = authcode($seq , "DECODE" , $key);

        $item = pdo_fetch("SELECT * FROM " . tablename("yhc_ecard") . " WHERE id = :id ", array(':id' => $viewid));
    }else{
        $item = pdo_fetch("SELECT * FROM " . tablename("yhc_ecard") . " WHERE  openid = :openid ", array(':openid' => $openid));
    }

    $avatar = $item["avatar"];
    if (empty($avatar)){
        $avatar = $_W['siteroot']."addons/yhc_ecard/template/mobile/images/user.png";
    }else{
        $avatar = tomedia($avatar);
    }

    if (empty($item) || $opt == "edit")
    {
        include $this->template('profileForm');
    }elseif ($item["openid"] == $openid){
        $seq = urlencode(authcode($item["id"] , "ENCODE" , $key));
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=yhc_ecard&do=index&seq=".$seq;

        if(!empty($item["website"]) && substr($item["website"] , 0 , 7) != "http://"){

            $website_link = "http://".$item["website"];
        }



        include $this->template('profile');
    }else{
        $mode = "anonymousCollect";
        $seq = urlencode(authcode($item["id"] , "ENCODE" , $key));
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=yhc_ecard&do=index&seq=".$seq;
        include $this->template('view');
    }


?>

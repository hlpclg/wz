<?php
    global $_GPC, $_W;
    $openid = $_W['openid'];
    $avatar = "";

    if (empty($openid)){
        echo "{\"success\" : false}";
        exit;
    }

    if (empty($_GPC['name'])) {
        echo "{\"success\" : false}";
        exit;
    }



    if (!empty($_GPC['serverid'])) {
        $avatar = Weixin::downloadImage($_GPC['serverid'] , date("d-Hms").floor(microtime()*1000));
    }


    $data = array(
        'openid' => $openid,
        'avatar' => $avatar,
        'name' => $_GPC['name'],
        'mobile' => $_GPC['mobile'],
        'phone' => $_GPC['phone'],
        'address' => $_GPC['address'],
        'email' => $_GPC['email'],
        'company' => $_GPC['company'],
        'position' => $_GPC['position'],
        'website' => $_GPC['website']
    );


    $item = pdo_fetch("SELECT * FROM ".tablename('yhc_ecard')." WHERE openid = :openid ", array(':openid' => $openid)); 



    if (empty($item)) {
        pdo_insert('yhc_ecard', $data);
    } else {
        if(empty($avatar)){
            unset($data["avatar"]);
        }
        pdo_update('yhc_ecard', $data, array('id' => $item["id"]));
    }


    echo "{\"success\" : true}";
?>

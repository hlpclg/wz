<?php
    global $_GPC, $_W;
    $openid = $_W['openid'];
    $viewid = $_GPC['id'];
    $remote = $_GPC['remote'];
    $avatar = "";

    if (empty($remote) && empty($_GPC['name'])) {
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
        'website' => $_GPC['website'],
        'remote' => $remote
    );


    if (empty($viewid)) {
        pdo_insert('yhc_ecard_pocket', $data);
    } else {
        unset($data["remote"]);
        if(empty($avatar)){
            unset($data["avatar"]);
        }
        pdo_update('yhc_ecard_pocket', $data, array('id' => $viewid , 'openid' => $openid));
    }


    echo "{\"success\" : true}";
?>

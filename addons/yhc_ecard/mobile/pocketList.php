<?php
    global $_GPC, $_W;
    $uniacid = $_W['uniacid'];
    $openid = $_W['openid'];
    $keyword = $_GPC['keyword'];

    if(empty($keyword)){
        $list = pdo_fetchall("SELECT id,name,'pocket' AS type FROM ".tablename('yhc_ecard_pocket')." WHERE openid = :openid UNION ALL SELECT e.id,e.name,'collect' AS type FROM ims_yhc_ecard e JOIN ims_yhc_ecard_collect c ON c.cardid = e.id WHERE c.openid = :openid", array(':openid' => $openid)); 
    }else{
        $list = pdo_fetchall("SELECT id,name,'pocket' AS type FROM ".tablename('yhc_ecard_pocket')." WHERE openid = :openid and name like :keyword UNION ALL SELECT e.id,e.name,'collect' AS type FROM ims_yhc_ecard e JOIN ims_yhc_ecard_collect c ON c.cardid = e.id WHERE c.openid = :openid and e.name like :keyword", array(':openid' => $openid , 'keyword' => '%'.$keyword.'%')); 
    }

    include $this->template('pocketList');
?>

<?php
    //shizhongying qq:800083075
    global $_GPC, $_W;
    $op= $_GPC['op'] ? $_GPC['op'] : 'display';
    $weid= $_W['uniacid'];
    if($op == 'display') {
        $pindex= max(1, intval($_GPC['page']));
        $psize= 20; //每页显示
        $condition= "WHERE `weid` = $weid";
        if(!empty($_GPC['keyword'])) {
            $condition .= " AND name LIKE '%".$_GPC['keyword']."%'";
        }
        $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_weicard2_fans')." $condition LIMIT ".($pindex -1) * $psize.','.$psize);
        $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('amouse_weicard2_fans').$condition);
        $pager= pagination($total, $pindex, $psize);
    }elseif($op == 'setstatus') {
        $id= intval($_GPC['id']);
        pdo_update('amouse_weicard2_fans', array('status'=>1), array('id' =>$id));
        message('禁用成功！', 'refresh');
    }elseif($op == 'deleteop') { //删除
        if(isset($_GPC['delete'])) {
            $ids= implode(",", $_GPC['delete']);
            $sqls= "delete from  ".tablename('amouse_weicard2_fans')."  where id in(".$ids.")";
            pdo_query($sqls);
            message('删除成功！', referer(), 'success');
        }
        $id= intval($_GPC['id']);
        $temp= pdo_delete("amouse_weicard2_fans", array("weid" => $_W['uniacid'],'id' => $id));
        message('删除数据成功！', $this->createWebUrl('weicard', array('op' => 'display')), 'success');
    }

    include $this->template('web/weicard');
?>

<?php
/**
 * Created by IntelliJ IDEA.
 * User: shizhongying
 * Date: 8/29/15
 * Time: 10:31
 */

global $_GPC, $_W;
$op= $_GPC['op'] ? $_GPC['op'] : 'display';
if($op == 'display') {
    $pindex= max(1, intval($_GPC['page']));
    $psize= 20; //每页显示
    if(!empty($_GPC['keyword'])) {
        $condition .= " WHERE title LIKE '%".$_GPC['keyword']."%'";
    }
    $list= pdo_fetchall('SELECT * FROM '.tablename('fineness_adv_er')." $condition LIMIT ".($pindex -1) * $psize.','.$psize);
    $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('fineness_adv_er').$condition);
    $pager= pagination($total, $pindex, $psize);
}elseif($op == 'post') {
    $id= intval($_GPC['id']);
    load()->func('tpl');
    if($id > 0) {
        $adv= pdo_fetch('SELECT * FROM '.tablename('fineness_adv_er')." WHERE id=:id",array(':id' => $id));
    }else{
        $adv['type']='0';
    }

    if(checksubmit('submit')) {
        $title= trim($_GPC['title']) ? trim($_GPC['title']) : message('请填写广告名称！');
        $logo= trim($_GPC['thumb']) ? trim($_GPC['thumb']) : message('请上传广告图片！');
        $insert= array('title' => $title,
            'link' => $_GPC['link'],
            'status'=>0,
            'type'=>$_GPC['type'],
            'description'=>$_GPC['description'],
            'thumb' => $_GPC['thumb'] );

        if(!empty($_FILES['thumb']['tmp_name'])) {
            file_delete($_GPC['thumb-old']);
            $upload= file_upload($_FILES['thumb']);
            if(is_error($upload)) {
                message($upload['message'], '', 'error');
            }
            $data['thumb']= $upload['path'];
        }

        if(empty($id)) {
            pdo_insert('fineness_adv_er', $insert);
        } else {
            if(pdo_update('fineness_adv_er', $insert, array('id' => $id)) === false) {
                message('更新广告数据失败, 请稍后重试.', 'error');
            }
        }
        message('更新广告数据成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
    }
}elseif($op == 'deleteop') { //删除
    if(isset($_GPC['delete'])) {
        $ids= implode(",", $_GPC['delete']);
        $sqls= "delete from  ".tablename('fineness_adv_er')."  where id in(".$ids.")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }
    $id= intval($_GPC['id']);
    $temp= pdo_delete("fineness_adv_er", array('id' => $id));
    message('删除数据成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
}

include $this->template('adv');
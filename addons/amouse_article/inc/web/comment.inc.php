<?php
/**
 * Created by IntelliJ IDEA.
 * User: shizhongying
 * Date: 8/29/15
 * Time: 10:31
 */

global $_GPC, $_W;
$op= $_GPC['op'] ? $_GPC['op'] : 'list';
$articleid = $_GPC['articleid'];
if($op == 'list') {
    $pindex= max(1, intval($_GPC['page']));
    $psize= 20; //每页显示
    $condition = "WHERE weid = $weid";
    $status = $_GPC['status'];
    if(!empty($_GPC['keyword'])) {
        $condition .= " WHERE author LIKE '%".$_GPC['keyword']."%'";
    }
    if ($status != '') {
        $condition .= " AND status = '" .$status. "'";
    }
    $list= pdo_fetchall('SELECT * FROM '.tablename('fineness_comment')." $condition LIMIT ".($pindex -1) * $psize.','.$psize);
    $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('fineness_comment').$condition);
    $pager= pagination($total, $pindex, $psize);

}elseif($op=='view'){
    $articleid= intval($_GPC['articleid']);
    $id= intval($_GPC['id']);
    $art = pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id = :id" , array(':id' => $articleid));
    $item = pdo_fetch("SELECT * FROM ".tablename('fineness_comment')." WHERE id = :id" , array(':id' => $id));
    if (empty($item)) {
        message('抱歉，导航不存在或是已经删除！', '', 'error');
    }
}elseif($op == 'delete') { //删除
    if(isset($_GPC['delete'])) {
        $ids= implode(",", $_GPC['delete']);
        $sqls= "delete from  ".tablename('fineness_comment')."  where id in(".$ids.")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }
    $id= intval($_GPC['articleid']);
    $temp= pdo_delete("fineness_comment", array('id' => $id));
    message('删除数据成功！', $this->createWebUrl('comment', array('op' => 'list','articleid'=>$articleid)), 'success');
}elseif($op=='vervify'){
    $id= intval($_GPC['id']);
    $recommed=$_GPC['status'];
    $articleid = $_GPC['articleid'];
    if($recommed==1){
        $msg='审核';
    }
    if($id > 0) {
        pdo_update('fineness_comment',array('status' =>$recommed), array('id' => $id)) ;
        message($msg.'成功！', $this->createWebUrl('comment', array('op' => 'list','articleid'=>$articleid)), 'success');
    }
}elseif ($op == 'post') {
    $id = intval($_GPC['id']);
    $articleid = $_GPC['articleid'];
    $art = pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id = :id" , array(':id' => $articleid));
    if ($id>0) {
        $item = pdo_fetch("SELECT * FROM ".tablename('fineness_comment')." WHERE id = :id" , array(':id' => $id));
        if (empty($item)) {
            message('抱歉，评论不存在或是已经删除！', '', 'error');
        }
    }
    if (checksubmit('submit')) {
        $data = array(
            'js_cmt_reply' => $_GPC['js_cmt_reply'],
            'updatetime' => TIMESTAMP,
        );
        pdo_update('fineness_comment', $data, array('id' => $id));
        message('评论作者回复成功！', $this->createWebUrl('comment', array('op' => 'list','articleid'=>$articleid)), 'success');
    }
}
include $this->template('comment');
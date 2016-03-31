<?php
defined('IN_IA') or exit('Access Denied');

class xhw_faceModuleSite extends WeModuleSite {

public function doMobileitem() {

		global $_GPC, $_W;
		$id= $_GPC['id'];
		$weid= $_W['uniacid'];
		$share= $_GET['share'];
		$sql = "SELECT * FROM " . tablename('xhw_face') . "WHERE `id` = $id";
		$arr= pdo_fetchall($sql);
		$description= $arr[0]['description'];
		$picurl= $arr[0]['picurl'];
		$grade=$arr[0]['grade'];
		$sql = "SELECT link FROM " . tablename('xhw_face_link') . "WHERE `weid` = $weid";
        $link= pdo_fetchcolumn($sql);
		include $this->template('item');
	}
public function doMobilelist() {
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));		
		$condition = "weid = '{$_W['uniacid']}'";
		$psize = pdo_fetchcolumn('SELECT number FROM ' . tablename(xhw_face_link)." WHERE $condition");
		$sql="SELECT * FROM ".tablename(xhw_face)." WHERE $condition ORDER BY -grade LIMIT ".($pindex - 1) * $psize.','.$psize;//按大小排序
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(xhw_face)." WHERE $condition");
		$pager = pagination($total, $pindex, $psize);		
	
		include $this->template('list');
	}
public function dowebsetting() {

		global $_GPC, $_W;
		$weid= $_W['uniacid'];
		$sql = "SELECT * FROM " . tablename('xhw_face_link') . "WHERE `weid` = $weid";
		$arr= pdo_fetch($sql);
		 if(checksubmit()) {
            $api_key=$_GPC['api_key'];
			$api_secret=$_GPC['api_secret'];
			$link=$_GPC['link'];
			$number=$_GPC['number'];
			$id=$_GPC['id'];
			$weid= $_W['uniacid'];
			$data = array('api_key' =>$api_key ,'api_secret'=>$api_secret,'link'=>$link,'number'=>$number);
			if (!empty($id)) {
                pdo_update('xhw_face_link', $data, array('weid' => $weid));
            }
            else {
            	$data['weid'] = $weid;
                pdo_insert('xhw_face_link',$data);
            }
		 	message("保存成功","refresh");
		}
		include $this->template('setting');
	}	
public function doWebPicture() {
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = "weid = '{$_W['uniacid']}'";
		if (!empty($_GPC['keywords'])) {
			$condition .= " AND id = '{$_GPC['keywords']}'";
		}
		$sql="SELECT * FROM ".tablename(xhw_face)." WHERE $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(xhw_face)." WHERE $condition");
		$pager = pagination($total, $pindex, $psize);		

		include $this->template('picture');
	}	
	public function doWebpost(){
		global $_W,$_GPC;
		$id = (int) $_GPC['id'];
		// 删除
		if($_GPC['op']=='delete'){
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename(xhw_face)." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，信息不存在或是已经被删除！');
			}
			pdo_delete(xhw_face, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('post');
	}

}



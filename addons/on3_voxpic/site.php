<?php
/**
 * 留声墙模块微站定义
 *
 * @author On3
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class On3_voxpicModuleSite extends WeModuleSite {

	public $tab_items = 'vp_items';

	public function doWebIndexsys() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$list = pdo_fetchall('SELECT * FROM'.tablename($this->tab_items)." WHERE uniacid = :uniacid",array(":uniacid"=>$_W['uniacid']));
		include $this->template('indexsys');
	}

	public function doMobileIndex(){
		global $_W,$_GPC;
		$foo = $_GPC['foo'];
		$openid = $_W['openid'];
		if($foo =='show'){
			$pageIndex = max(1, intval($_GPC['page']));
			$pageSize = 10;
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->tab_items)." WHERE uniacid = :uniacid",array(":uniacid"=>$_W['uniacid']));
			$listall = pdo_fetchall('SELECT t1.id,img,summary,voice,t2.nickname,t1.openid FROM'.tablename($this->tab_items)." AS t1 JOIN ".tablename('mc_mapping_fans')." AS t2 ON t1.openid = t2.openid WHERE t1.uniacid = :uniacid ORDER BY t1.createtime LIMIT " . ($pageIndex - 1) * $pageSize . ',' . $pageSize,array(':uniacid'=>$_W['uniacid']));
			$result = array();
			$result['isok'] = true;
			if($total-$pageSize*$pageIndex<0){
				$result['hasMore'] = false;
			}else{
				$result['hasMore'] = true;
			}
		foreach ($listall as $key => $value) {
			$tempList[] = array('index' => $key,'id' => $value['id'],'face'=>toimage($value['img']),'voicePath'=> $value['voice']);
		}
			$result['list'] = $tempList;
			return	json_encode($result);
		}
		include $this->template('index');
	}

	public function doMobileMypage(){
		global $_W,$_GPC;
		$foo = $_GPC['foo'];
		$openid = $_W['openid'];
		if(!empty($openid)){
			$user = pdo_fetch('SELECT * FROM'.tablename('mc_mapping_fans')." WHERE openid = :openid",array(':openid'=>$openid));
		}
		if($foo =='show'){
			if(!empty($openid)){
				$pageIndex = max(1, intval($_GPC['page']));
				$pageSize = 10;
				$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->tab_items)." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
				$uniacid = $_W['uniacid'];
				$listall = pdo_fetchall('SELECT t1.id,img,summary,voice,t2.nickname,t1.openid FROM'.tablename($this->tab_items)." AS t1 JOIN ".tablename('mc_mapping_fans')." AS t2 ON t1.openid = t2.openid WHERE t1.uniacid = $uniacid AND t1.openid  = '{$openid}' LIMIT " . ($pageIndex - 1) * $pageSize . ',' . $pageSize);
				$result = array();
				$result['isok'] = true;
				if($total-$pageSize*$pageIndex<0){
					$result['hasMore'] = false;
				}else{
					$result['hasMore'] = true;
				}
				foreach ($listall as $key => $value) {
					$tempList[] = array('index' => $key,'id' => $value['id'],'face'=>toimage($value['img']),'voicePath'=> toimage($value['voice']));
				}
				$result['list'] = $tempList;
				return	json_encode($result);
			}
		}
		include $this->template('mypage');
	}

	public function doMobileDetails(){
		global $_W,$_GPC;
		$data = pdo_fetch('SELECT * FROM'.tablename('vp_reply')." WHERE uniacid = :uniacid LIMIT 1",array(':uniacid'=>$_W['uniacid']));
		$id = $_GPC['id']?intval($_GPC['id']):message('缺失重要的参数..','','error');
		$item = pdo_fetch('SELECT * FROM'.tablename($this->tab_items)." AS t1 JOIN ".tablename('mc_mapping_fans')." AS t2 ON t1.openid = t2.openid WHERE t1.uniacid = :uniacid AND t1.id = :id",array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		include $this->template('detail');
	}

	public function doWebItemslist() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$op = $_GPC['op'];
		if($op=='del'){
			$id = $_GPC['id'];
			if(empty($id)){
				message('您要删除的条目不存在..',referer(),'error');
			}
			$dat = pdo_fetch('SELECT * FROM'.tablename($this->tab_items)." WHERE uniacid = :uniacid AND id = :id",array(':uniacid'=>$_W['uniacid'],':id'=>$id));
			if(empty($dat)){
				message('您要删除的资源已经被删除',referer(),'error');
			}
			load()->func('file');
			if(!empty($dat['img'])){
				@file_delete($dat['img']);
			}
			if(!empty($dat['voice'])){
				@file_delete($dat['voice']);
			}
			pdo_delete($this->tab_items,array('uniacid'=>$_W['uniacid'],'id'=>$id));
			message('删除成功',referer(),'success');
		}
		$pageIndex = max(1, intval($_GPC['page']));
		$pageSize = 1;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->tab_items).'WHERE uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM'.tablename($this->tab_items)." WHERE uniacid = :uniacid LIMIT ".($pageIndex - 1) * $pageSize . ',' . $pageSize,array(':uniacid'=>$_W['uniacid']));
		$pager = pagination($total, $pageIndex, $pageSize);
		include $this->template('itemslist');
	}
}
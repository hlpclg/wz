<?php
/**
 * 
 *
 * 
 */
defined('IN_IA') or exit('Access Denied');
class Xf_vktvModuleSite extends WeModuleSite {

	public function doWebClassify(){
		global $_W,$_GPC;
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		$departments = pdo_fetchAll("SELECT * FROM".tablename('vktv_reply')." WHERE weid='{$_W['weid']}'");
		//print_r($departments);exit;
		if ($op == 'post') {
			if (!empty($_GPC['id'])) {
				$item = pdo_fetch("SELECT * FROM".tablename('vktv_classify')." WHERE id='{$_GPC['id']}'");
			}
			
			$data = array(
				'weid'          => $_W['weid'],
				'sort'          => intval($_GPC['sort']),
				'ser_window'    => $_GPC['ser_window'],
				'department_id' => $_GPC['department_id'],
				'phone'         => $_GPC['phone'],
				'ser_picurl'    => $_GPC['ser_picurl'],
				'ser_info'      => htmlspecialchars_decode($_GPC['ser_info']),
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert('vktv_classify',$data);
				}else{
					//print_r($data);exit;
					pdo_update('vktv_classify',$data,array("id" => $_GPC['id']));
				}
				message("更新成功",referer(),'success');
			}
		}elseif( $op == 'display'){
			$classify = pdo_fetchAll("SELECT * FROM".tablename('vktv_classify')." WHERE weid='{$_W['weid']}'");
			$list = array();
			foreach ($classify as $key => $value) {
				$list[$key]['id'] = $value['id'];
				$list[$key]['sort'] = $value['sort'];
				$list[$key]['ser_window'] = $value['ser_window'];
				$departments = pdo_fetch("SELECT * FROM".tablename('vktv_reply')." WHERE id='{$value['department_id']}'");
				$list[$key]['department'] = $departments['department'];
			}
		}elseif( $op == 'delete'){
			pdo_delete("vktv_classify",array('id' => $_GPC['id'] ));
			message("删除成功",referer(),'success');
		}
		include $this->template('classify');
	}
	public function doWebProject(){
		global $_GPC,$_W;
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		if ($op == 'post') {
			$classify = pdo_fetchAll("SELECT * FROM".tablename('vktv_classify')." WHERE weid='{$_W['weid']}'");
			if (!empty($_GPC['id'])) {
				$item = pdo_fetch("SELECT * FROM".tablename('vktv_project')." WHERE id='{$_GPC['id']}'");
			}
			$data = array(
				'weid'            => $_GPC['weid'],
				'sort'            => $_GPC['sort'],
				'ser_name'        => $_GPC['ser_name'],
				'classify_id'     => $_GPC['classify_id'],
				'kbox'            => $_GPC['kbox'],
				'price'           => $_GPC['price'],
				'classify_picurl' => $_GPC['classify_picurl'],
				'ishow'           => intval($_GPC['ishow']),
				'project_info'    => htmlspecialchars_decode($_GPC['project_info']),
				'total'           => intval($_GPC['total']),
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert("vktv_project",$data);
				}else{
					pdo_update("vktv_project",$data,array('id' => $_GPC['id']));
				}
				message("更新成功",referer(),'success');
			}
		}elseif ($op == 'display') {
			$projects = pdo_fetchAll("SELECT * FROM".tablename('vktv_project')." WHERE weid='{$_W['weid']}'");
			$list = array();
			foreach ($projects as $key => $value) {
				$list[$key]['id'] = $value['id'];
				$list[$key]['sort'] = $value['sort'];
				$list[$key]['ser_name'] = $value['ser_name'];
				$classify = pdo_fetch("SELECT * FROM".tablename('vktv_classify')." WHERE id='{$value['classify_id']}'");
				$list[$key]['classify_name'] = $classify['ser_window'];
				$list[$key]['kbox'] = $value['kbox'];
				$list[$key]['price'] = $value['price'];
			}
			//print_r($list);exit;
		}elseif ($op == 'delete'){
			pdo_delete("vktv_project",array('id' => $_GPC['id']));
			message(" 删除成功",referer(),'success');
		}

		include $this->template('project');
	}
	public function doWebPoster(){
		global $_GPC,$_W;
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		if($op == 'post'){
			if (!empty($_GPC['id'])) {
				$item = pdo_fetch("SELECT * FROM".tablename('vktv_poster')." WHERE id='{$_GPC['id']}'");
				$thumbs = unserialize($item['thumb']);
			}
			$depms = pdo_fetchAll("SELECT * FROM".tablename('vktv_reply')." WHERE weid='{$_W['weid']}'");
			$data = array(
				'weid'          => $_W['weid'],
				'thurl'         => $_GPC['thurl'],
				'title'         => $_GPC['title'],
				'thumb'         => serialize($_GPC['thumb']),
				'department_id' => intval($_GPC['department_id']),
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert('vktv_poster',$data);
				}else{
					pdo_update('vktv_poster',$data,array('id' => $_GPC['id']));
				}
				message('更新成功',referer(),'success');
			}
		}elseif($op == 'display'){
			$posters = pdo_fetchAll("SELECT * FROM".tablename('vktv_poster')." WHERE weid='{$_W['weid']}'");
			$list = array();
			foreach ($posters as $key => $value) {
				$list[$key]['id'] = $value['id'];
				$dep = pdo_fetch(" SELECT * FROM".tablename('vktv_reply')." WHERE id='{$value['department_id']}'"); 
				$list[$key]['department_name'] = $dep['department'];
				$list[$key]['thurl'] = $value['thurl'];
			}
		}elseif ($op == 'delete') {
			pdo_delete('vktv_poster',array('id' => $_GPC['id']));
			message("删除成功",referer(),'success');
		}

		include $this->template('poster');
	}
	public function doWebComments(){
		global $_GPC,$_W;
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		if ($op == 'post') {
			$deps = pdo_fetchAll("SELECT * FROM".tablename('vktv_reply')." WHERE weid='{$_W['weid']}'");
			if (!empty($_GPC['id'])) {
				$item = pdo_fetch(" SELECT * FROM".tablename('vktv_comments')." WHERE id='{$_GPC['id']}'");
			}
			$data = array(
				'weid'          => $_W['weid'],
				'sort'          => $_GPC['sort'],
				'title'         => $_GPC['title'],
				'lead_name'     => $_GPC['lead_name'],
				'lead_position' => $_GPC['lead_position'],
				'lead_picurl'   => $_GPC['lead_picurl'],
				'info'          => htmlspecialchars_decode($_GPC['info']),
				'department_id' => $_GPC['department_id'],
				'comm_content'  => htmlspecialchars_decode($_GPC['comm_content']),
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert("vktv_comments",$data);
				}else{
					pdo_update("vktv_comments",$data,array('id' => $_GPC['id']));
				}
				message("更新成功",referer(),'success');
			}
		}elseif ($op == 'display') {
			$comments = pdo_fetchAll("SELECT * FROM".tablename('vktv_comments')." WHERE weid='{$_W['weid']}'");
			foreach ($comments as $key => $value) {
				$dep = pdo_fetch(" SELECT * FROM".tablename('vktv_reply')." WHERE id='{$value['department_id']}'");
				$comments[$key]['department_name'] = $dep['department'];
			}
			//print_r($comments);exit;
		}elseif ($op == 'delete') {
			pdo_delete("vktv_comments",array('id' => $_GPC['id']));
			message("删除成功",referer(),'success');
		}
		include $this->template('comments');
	}
	public function doWebOrders(){
		global $_GPC,$_W;
		$orders = pdo_fetchAll("SELECT * FROM".tablename('vktv_reservation')."WHERE weid='{$_W['weid']}'");
		$total = count($orders);
		if ($_GPC['op'] == 'delete') {
			pdo_delete("vktv_reservation",array('id' => $_GPC['id']));
			message('删除成功',referer(),'success');
		}
		include $this->template('orders');
	}
	public function doWebDetail(){
		global $_GPC,$_W;
		$userinfo = pdo_fetch("SELECT * FROM".tablename('vktv_reservation')."WHERE id='{$_GPC['id']}'");
		if ($_W['ispost']) {
			$data = array(
				'remate' => intval($_GPC['remate']),
				'kfinfo' => $_GPC['kfinfo'],
			);
			pdo_update('vktv_reservation',$data,array('id' => $_GPC['id']));
			message('修改成功',referer(),'success');
		}
		include $this->template('detail');
	}
	public function doMobileIndex(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$posters = pdo_fetch("SELECT * FROM".tablename('vktv_poster')."WHERE weid='{$_W['weid']}' AND department_id='{$id}'");
		$thumbs = unserialize($posters['thumb']);

		include $this->template('index');
	}
	public function doMobileDepartment(){
		global $_GPC,$_W;
		$detail = pdo_fetch("SELECT * FROM".tablename('vktv_reply')."WHERE id='{$_GPC['id']}'");
		include $this->template('department');
	}
	public function doMobileClassify(){
		global $_GPC,$_W;
		$title = '服务窗口';
		$classify = pdo_fetchAll("SELECT * FROM".tablename('vktv_classify')."WHERE weid='{$_W['weid']}' AND department_id='{$_GPC['id']}'");
		//print_r($classify);exit;
		include $this->template('classify');
	}
	public function doMobileCdetail(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$classify = pdo_fetch("SELECT * FROM".tablename('vktv_classify')."WHERE id='{$id}'");
		$projects = pdo_fetchAll("SELECT * FROM".tablename('vktv_project')."WHERE classify_id='{$id}'");
		//print_r($projects);exit;
		include $this->template('cdetail');
	}
	public function doMobileComments(){
		global $_GPC,$_W;
		$title = '客户点评';
		$id = intval($_GPC['id']);
		$comment = pdo_fetchAll("SELECT * FROM".tablename('vktv_comments')."WHERE department_id='{$id}'");
		include $this->template('comments');
	}
	public function doMobileCmdetail(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$cmdetail = pdo_fetch("SELECT * FROM".tablename('vktv_comments')."WHERE id='{$id}'");
		//print_r($cmdetial);exit;
		include $this->template('cmdetail');
	}
	public function doMobileReservation(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$project = pdo_fetch("SELECT * FROM".tablename('vktv_project')."WHERE id='{$id}'");

		include $this->template('reservation');
	}
	public function doMobileyysave(){
		global $_GPC,$_W;
		if ($_W['ispost']) {
			$data = array(
				'truename'   => $_GPC['truename'],
				'mobile'     => $_GPC['mobile'],
				'ser_name'   => $_GPC['ser_name'],
				'createtime' => TIMESTAMP,
				'remate'     => '0',
				'info'       => $_GPC['info'],
				'openid'     => $_W['fans']['from_user'],
				'weid'       => $_W['weid'],
				'reid'       => $_GPC['reid'],
			);
			$project = pdo_fetch("SELECT * FROM".tablename('vktv_project')."WHERE id='{$_GPC['reid']}'");
			$classify = pdo_fetch("SELECT * FROM".tablename('vktv_classify')."WHERE id='{$project['classify_id']}'");
			$total = $project['total'];
			$recount = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('vktv_reservation')."WHERE weid='{$_W['weid']}'");
			if ($recount>=$total) {
				$url = $this->createMobileUrl('classify',array('department_id' => $classify['department_id']));
				echo json_encode(array('errno'=>3,'msg'=>"非常抱歉,该服务已满,您可以看看别的服务.",'url'=>$url));
				exit;
			}
			pdo_insert('vktv_reservation',$data);
			$id = pdo_insertid();
			if ($id) {
				 $url = $this->createMobileUrl('mylist');
				 $arr=array('errno'=>1,'url'=>$url);
				  echo json_encode($arr);exit;
			}else{
				 $arr=array('errno'=>2);
           		 echo json_encode($arr);exit;
			}
		}
	}
	public function doMobileMylist(){
		global $_GPC,$_W;
		$rebs = pdo_fetchAll("SELECT * FROM".tablename('vktv_reservation')."WHERE openid='{$_W['fans']['from_user']}'");
		if ($_GPC['op'] == 'delete') {
			pdo_delete("vktv_reservation",array('id' => $_GPC['id']));
			message('删除成功',referer(),'success');
		}
		include $this->template('mylist');
	}
}
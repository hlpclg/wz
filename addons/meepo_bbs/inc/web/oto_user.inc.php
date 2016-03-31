<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$fo = $_GPC['fo'];
if($_W['ispost']){
	if(!empty($_GPC['delete'])){
		$select = $_GPC['select'];
		foreach ($select as $key) {
			pdo_delete('meepo_bbs_o2o_user',array('id'=>$key));
		}
		message('删除数据成功',referer(),success);
	}

	if(!empty($_GPC['upload'])){
		$select = $_GPC['select'];
		$in = db_create_in($select,'b.id');
		$sql = "SELECT b.*,m.realname,m.mobile,m.company,m.address FROM ".tablename('meepo_bbs_o2o_user')." as b LEFT JOIN ".tablename('mc_members')
		." AS m ON b.uid = m.uid WHERE ".$in;
		$params = array(':uniacid'=>$_W['uniacid']);
		$list = pdo_fetchall($sql,$params);


		//导出
		include_once ('../framework/library/phpexcel/PHPExcel.php');
		$objPHPExcel = new PHPExcel();
		$objDrawing = new PHPExcel_Worksheet_Drawing();

		$objPHPExcel->getProperties()->setCreator("Meepo");
		$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
		$objPHPExcel->getProperties()->setTitle("Meepo");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '手机号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '地址');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '公司');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

		foreach ($list as $key => $value) {

			$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), $value['realname']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['mobile']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['address']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), $value['company']);
			$objPHPExcel->getActiveSheet()->getStyle('D'.($key+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				
		}

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="resume.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');

		exit();
	}

	if(!empty($_GPC['uploadall'])){
		$in = db_create_in($select,'b.id');
		$sql = "SELECT b.*,m.realname,m.mobile,m.address,m.company FROM ".tablename('meepo_bbs_o2o_user')." as b LEFT JOIN ".tablename('mc_members')
		." AS m ON b.uid = m.uid ";
		$params = array(':uniacid'=>$_W['uniacid']);
		$list = pdo_fetchall($sql,$params);


		//导出
		include_once ('../framework/library/phpexcel/PHPExcel.php');
		$objPHPExcel = new PHPExcel();
		$objDrawing = new PHPExcel_Worksheet_Drawing();

		$objPHPExcel->getProperties()->setCreator("Meepo");
		$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
		$objPHPExcel->getProperties()->setTitle("Meepo");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '电话');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '地址');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '公司');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

		foreach ($list as $key => $value) {
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), $value['realname']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['mobile']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['address']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), $value['company']);
			$objPHPExcel->getActiveSheet()->getStyle('D'.($key+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				
		}

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="resume.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');

		exit();
	}
}

if(empty($fo)){
	$id = $_GPC['id'];

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	if (!empty($_GPC['realname'])) {
		$condition .= " AND m.realname LIKE '%{$_GPC['realname']}%'";
	}

	if(isset($_GPC['company'])){
		$condition .= " AND m.company LIKE '%{$_GPC['company']}%'";
	}
	
	if(isset($_GPC['mobile'])){
		$condition .= " AND m.mobile LIKE '%{$_GPC['mobile']}%'";
	}

	if(isset($_GPC['address'])){
		$condition .= " AND m.address LIKE '%{$_GPC['address']}%'";
	}
	
	if(isset($_GPC['status'])){
		$status = intval($_GPC['status']);
		$condition .= " AND b.status = '{$status}'";
	}
	$sql = "SELECT b.*,m.realname,m.mobile,m.address,m.company FROM ".tablename('meepo_bbs_o2o_user')." as b LEFT JOIN ".tablename('mc_members')." as m ON b.uid = m.uid "
			." WHERE b.uniacid = :uniacid {$condition} ORDER BY createtime DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);

	foreach ($lists as $li) {
		if($li['status'] == 0){
			$li['status'] = '待审核';
			$li['shenhe'] = $this->createWebUrl('oto_user',array('fo'=>'status','status'=>1,'id'=>$li['id']));
			$li['shenhetitle'] = '通过审核';
		}else if($li['status'] == 1){
			$li['status'] = '通过';
			$li['shenhe'] = $this->createWebUrl('oto_user',array('fo'=>'status','status'=>0,'id'=>$li['id']));
			$li['shenhetitle'] = '取消权限';
		}else{
			$li['status'] = '失败';
			$li['shenhe'] = $this->createWebUrl('oto_user',array('fo'=>'status','status'=>1,'id'=>$li['id']));
			$li['shenhetitle'] = '恢复权限';
		}
		
		$li['delete'] = $this->createWebUrl('oto_user',array('fo'=>'delete','id'=>$li['id']));
		$li['reply'] = $this->createWebUrl('oto_user',array('fo'=>'status','status'=>1,'id'=>$li['id']));
		$li['false'] = $this->createWebUrl('oto_user',array('fo'=>'status','status'=>2,'id'=>$li['id']));
		$li['log'] = $this->createWebUrl('oto_user_log',array('uid'=>$li['uid']));
		$list[] = $li;
	}

	$params = array(':uniacid'=>$_W['uniacid']);
	$total = pdo_fetchcolumn(
			'SELECT COUNT(*) FROM ' . tablename('meepo_bbs_o2o_user') . " as b "
			." left join ".tablename('mc_members')." as m on b.uid = m.uid "
			." WHERE b.uniacid = :uniacid {$condition} ", $params);
	$pager = pagination($total, $pindex, $psize);

	include $this->template('oto_user');
}

if($fo == 'delete'){
	$id = $_GPC['id'];

	pdo_delete('meepo_bbs_o2o_user',array('id'=>$id));

	message('操作成功',referer(),success);
}


if($fo == 'status'){
	$id = $_GPC['id'];
	if(empty($id)){
		message('参数错误',referer(),error);
	}
	$status = intval($_GPC['status']);
	load()->model('mc');
	$row = pdo_fetch("SELECT * FROM ".tablename('meepo_bbs_o2o_user')." WHERE id = :id",array(':id'=>$id));
	$user = mc_fetch($row['uid'],array('realname','uid','nickname','mobile','address','company'));
	if($status == 1){
		//插入店员和密码
		$data = array();
		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $user['realname'];
		$data['password'] = randompassword();
		$data['uid'] = $row['uid'];
		
		$sql = "SELECT * FROM ".tablename('activity_coupon_password')." WHERE uid = :uid AND uniacid = :uniacid";
		$params = array(':uid'=>$row['uid'],':uniacid'=>$_W['uniacid']);
		$isexit = pdo_fetch($sql,$params);
		
		if(empty($isexit)){
			pdo_insert('activity_coupon_password',$data);
			$password = $data['password'];
		}else{
			$password = $isexit['password'];
		}
		
		$content = '您的核销权限已通过审核，密码为：'.$password;
		$uid = $row['uid'];
		$sql = "SELECT openid,acid FROM ".tablename('mc_mapping_fans')." WHERE uniacid = :uniacid AND uid = :uid";
		$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$uid);
		$fans = pdo_fetch($sql,$params);
		$send = array();
		
		if(empty($_W['acid'])){
			$_W['acid'] = $row['acid'];
		}
		if(empty($_W['acid'])){
			$_W['acid'] = $fans['acid'];
		}
		if(empty($_W['acid'])){
			message('请选择公众账号',referer(),error);
		}
		
		if(empty($row['openid'])){
			$row['openid'] = $fans['openid'];
		}
		
		$other = array();
		$other['password'] = $password;
		$return = send_template($row['openid'],'web_o2o_user',$other);
		if(is_error($return)){
			message($return['message'],referer(),error);
		}
		
	}else{
		$uid = $row['uid'];
		$sql = "SELECT openid,acid FROM ".tablename('mc_mapping_fans')." WHERE uniacid = :uniacid AND uid = :uid";
		$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$uid);
		$fans = pdo_fetch($sql,$params);
		if(empty($_W['acid'])){
			$_W['acid'] = $row['acid'];
		}
		if(empty($_W['acid'])){
			$_W['acid'] = $fans['acid'];
		}
		if(empty($_W['acid'])){
			message('请选择公众账号',referer(),error);
		}
		
		if(empty($row['openid'])){
			$row['openid'] = $fans['openid'];
		}
		
		if($_W['account']['level']>=4){
			$return = send_template($row['openid'],'web_o2o_user_false');
		}
		
		if(is_error($return)){
			message($return['message'],referer(),error);
		}
	}
	
	pdo_update('meepo_bbs_o2o_user',array('status'=>$status),array('id'=>$id));
	
	message('操作成功',referer(),success);
}


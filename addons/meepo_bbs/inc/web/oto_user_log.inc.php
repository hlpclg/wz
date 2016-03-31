<?php /*折翼天使资源社区 www.zheyitianshi.com*/ 
global $_W,$_GPC;
$fo = $_GPC['fo'];
if($_W['ispost']){
	if(!empty($_GPC['delete'])){
		$select = $_GPC['select'];
		foreach ($select as $key) {
			pdo_delete('meepo_bbs_o2o_user_log',array('id'=>$key));
		}
		message('删除数据成功',referer(),success);
	}

	if(!empty($_GPC['upload'])){
		$select = $_GPC['select'];
		$in = db_create_in($select,'b.id');
		$sql = "SELECT b.*,m.realname,m.mobile,m.address,m.company FROM ".tablename('meepo_bbs_o2o_user_log')." as b LEFT JOIN ".tablename('mc_members')." as m ON b.uid = m.uid "
				." WHERE {$in} ORDER BY b.time DESC ";
		$lists = pdo_fetchall($sql,$paramss);
		
		//导出
		include_once ('../framework/library/phpexcel/PHPExcel.php');
		$objPHPExcel = new PHPExcel();
		$objDrawing = new PHPExcel_Worksheet_Drawing();

		$objPHPExcel->getProperties()->setCreator("Meepo");
		$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
		$objPHPExcel->getProperties()->setTitle("Meepo");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '操作人');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '类型');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '标题');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '时间');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

		foreach ($lists as $key => $value) {
			if($value['type'] == 'coupon'){
				$value['type'] = '折扣券';
			}
			if($value['type'] == 'token'){
				$value['type'] = '代金券';
			}
			$sql = "SELECT title FROM ".tablename('activity_coupon')." WHERE couponid = :couponid ";
			$params = array(':couponid'=>$value['cid']);
			$value['title'] = pdo_fetchcolumn($sql,$params);
			$value['time'] = date('Y-m-d',$value['time']);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), $value['realname']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['type']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['title']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), $value['time']);
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
		$sql = "SELECT * FROM ".tablename('meepo_bbs_o2o_user_log')." WHERE uniacid = :uniacid ORDER BY time DESC";
		$params = array(':uniacid'=>$_W['uniacid']);
		$list = pdo_fetchall($sql,$params);


		//导出
		include_once ('../framework/library/phpexcel/PHPExcel.php');
		$objPHPExcel = new PHPExcel();
		$objDrawing = new PHPExcel_Worksheet_Drawing();

		$objPHPExcel->getProperties()->setCreator("Meepo");
		$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
		$objPHPExcel->getProperties()->setTitle("Meepo");

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '操作人');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '类型');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '标题');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '时间');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

		foreach ($list as $key => $value) {
			if($value['type'] == 'coupon'){
				$value['type'] = '折扣券';
			}
			if($value['type'] == 'token'){
				$value['type'] = '代金券';
			}
			$sql = "SELECT title FROM ".tablename('activity_coupon')." WHERE couponid = :couponid ";
			$params = array(':couponid'=>$value['cid']);
			$value['title'] = pdo_fetchcolumn($sql,$params);
			$value['time'] = date('Y-m-d',$value['time']);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['type']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['title']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), $value['time']);
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
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = " b.uniacid = :uniacid ";
	$paramss = array(':uniacid'=>$_W['uniacid']);
	if (!empty($_GPC['uid'])) {
		$uid = intval($_GPC['uid']);
		$condition .= " AND b.uid = :uid ";
		$paramss[':uid'] = $uid;
	}
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
	
	$sql = "SELECT b.*,m.realname,m.mobile,m.address,m.company FROM ".tablename('meepo_bbs_o2o_user_log')." as b LEFT JOIN ".tablename('mc_members')." as m ON b.uid = m.uid "
			." WHERE {$condition} ORDER BY b.time DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	
	$lists = pdo_fetchall($sql,$paramss);
	if(!empty($lists)){
		foreach ($lists as $li) {
			if($li['type'] == 'coupon'){
				$li['type'] = '折扣券';
			}
			if($li['type'] == 'token'){
				$li['type'] = '代金券';
			}
			$sql = "SELECT * FROM ".tablename('activity_coupon')." WHERE couponid = :couponid ";
			$params = array(':couponid'=>$li['cid']);
			$item = pdo_fetch($sql,$params);
			$li['title'] = $item['title'];
			$li['time'] = date('Y-m-d',$li['time']);
		
			$li['delete'] = $this->createWebUrl('oto_user_log',array('fo'=>'delete','id'=>$li['id']));
			$list[] = $li;
		}
	}
	

	$total = pdo_fetchcolumn(
			'SELECT COUNT(*) FROM ' . tablename('meepo_bbs_o2o_user_log') . " as b "
			." left join ".tablename('mc_members')." as m on b.uid = m.uid "
			." WHERE {$condition} ", $paramss);
	$pager = pagination($total, $pindex, $psize);

	include $this->template('oto_user_log');
}

if($fo == 'delete'){
	$id = $_GPC['id'];
	pdo_delete('meepo_bbs_o2o_user_log',array('id'=>$id));

	message('操作成功',referer(),success);
}


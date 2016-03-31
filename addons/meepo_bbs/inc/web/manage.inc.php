<?php /*折翼天使资源社区 www.zheyitianshi.com*/
//乞讨活动后台管理
global $_W,$_GPC;
$forum = getSet();
$fo = $_GPC['fo'];
if($_W['ispost']){
	if(!empty($_GPC['delete'])){
		$select = $_GPC['select'];
		foreach ($select as $key) {
			pdo_delete('meepo_bbs_topics',array('id'=>$key));
		}
		message('删除数据成功',referer(),success);
	}

	if(!empty($_GPC['upload'])){
		$select = $_GPC['select'];
		$in = db_create_in($select,'b.id');
		$sql = "SELECT b.*,m.avatar,m.nickname FROM ".tablename('meepo_bbs_topics')." as b LEFT JOIN ".tablename('mc_members')
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '昵称');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '头像');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '帖子标题');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '参与时间');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

		foreach ($list as $key => $value) {
			$value['createtime'] = date('Y-m-d',$value['createtime']);
			$value['avatar'] = tomedia($value['avatar']);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), $value['nickname']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['avatar']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), '标题：'.$value['title']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), $value['createtime']);
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
		$sql = "SELECT b.*,m.avatar,m.nickname FROM ".tablename('meepo_bbs_topics')." as b LEFT JOIN ".tablename('mc_members')
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

		$objPHPExcel->getActiveSheet()->setCellValue('A1', '昵称');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '头像');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '帖子标题');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '参与时间');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

		foreach ($list as $key => $value) {
			$value['createtime'] = date('Y-m-d',$value['createtime']);
			$value['avatar'] = tomedia($value['avatar']);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), $value['nickname']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['avatar']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), '标题：'.$value['title']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), $value['createtime']);
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
	$typeid = $_GPC['typeid'];
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND m.nickname LIKE '%{$_GPC['keyword']}%'";
	}
	if(!empty($_GPC['typeid'])){
		$condition .= " AND b.fid = '{$typeid}' ";
	}
	
	if(isset($_GPC['status'])){
		$condition .= " AND b.status = '{$_GPC['status']}' ";
	}
	
	if(isset($_GPC['tab'])){
		$condition .= " AND b.tab = '{$_GPC['tab']}' ";
	}
	$sql = "SELECT b.*,m.avatar,m.nickname FROM ".tablename('meepo_bbs_topics')." as b LEFT JOIN ".tablename('mc_members')." as m ON b.uid = m.uid "
	." WHERE b.uniacid = :uniacid {$condition} ORDER BY createtime DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);
	
	foreach ($lists as $li) {
		$li['createtime'] = date('Y-m-d',$li['createtime']);
		
		if(!empty($li['avatar']) && strlen($li['avatar'])>5){
			$li['avatar'] = tomedia($li['avatar']);
		}else{
			$li['avatar'] = tomedia($forum['logo']);
		}
		
		$li['detail'] = $this->createWebUrl('forum_post',array('tid'=>$li['id']));
		$li['delete'] = $this->createWebUrl('manage',array('fo'=>'delete','id'=>$li['id']));
		$li['jing'] = $this->createWebUrl('manage',array('fo'=>'tab','tab'=>'jing','id'=>$li['id']));
		$li['top'] = $this->createWebUrl('manage',array('fo'=>'tab','tab'=>'top','id'=>$li['id']));
		$li['lock'] = $this->createWebUrl('manage',array('fo'=>'tab','tab'=>'lock','id'=>$li['id']));
		$li['deleteall'] = $this->createWebUrl('manage',array('fo'=>'deleteall','uid'=>$li['uid']));
		
		$sql = "SELECT name FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid";
		$params = array(':typeid'=>$li['fid']);
		$li['ctitle'] = pdo_fetchcolumn($sql,$params);
		$li['toptitle'] = '置顶';
		$li['jingtitle'] = '加精';
		$li['locktitle'] = '锁定';
		if($li['tab'] == 'new'){
			$li['tab'] = '最新';
		}else if($li['tab'] == 'top'){
			$li['tab'] = '置顶';
			$li['top'] = $this->createWebUrl('manage',array('fo'=>'tab','tab'=>'','id'=>$li['id']));
			$li['toptitle'] = '取消置顶';
		}else if($li['tab'] == 'jing'){
			$li['tab'] = '加精';
			$li['jing'] = $this->createWebUrl('manage',array('fo'=>'tab','tab'=>'','id'=>$li['id']));
			$li['jingtitle'] = '取消加精';
		}else if($li['tab'] == 'lock'){
			$li['tab'] = '锁定';
			$li['lock'] = $this->createWebUrl('manage',array('fo'=>'tab','tab'=>'lock','id'=>$li['id']));
			$li['locktitle'] = '取消锁定';
		}else{
			$li['tab'] = '普通';
		}
		
		$li['zan'] = $this->createWebUrl('manage',array('fo'=>'addnum','op'=>'zan','id'=>$li['id']));
		$li['kan'] = $this->createWebUrl('manage',array('fo'=>'addnum','op'=>'kan','id'=>$li['id']));
		$li['fenxiang'] = $this->createWebUrl('manage',array('fo'=>'addnum','op'=>'fenxiang','id'=>$li['id']));
		
		$list[] = $li;
	}
	
	$params = array(':uniacid'=>$_W['uniacid']);
	$total = pdo_fetchcolumn(
			'SELECT COUNT(*) FROM ' . tablename('meepo_bbs_topics') . " as b "
			." left join ".tablename('mc_members')." as m on b.uid = m.uid "
			." WHERE b.uniacid = :uniacid {$condition} ", $params);
	$pager = pagination($total, $pindex, $psize);
	
	include $this->template('manage');
}

if($fo == 'addnum'){
	$id = intval($_GPC['id']);
	if($_GPC['op']=='zan'){
		//点赞+5
		pdo_insert('meepo_bbs_topic_like',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_like',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_like',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_like',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_like',array('tid'=>$id,'time'=>time(),'num'=>1));
	}
	if($_GPC['op']=='kan'){
		//浏览+5
		pdo_insert('meepo_bbs_topic_read',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_read',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_read',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_read',array('tid'=>$id,'time'=>time(),'num'=>1));
		pdo_insert('meepo_bbs_topic_read',array('tid'=>$id,'time'=>time(),'num'=>1));
	}
	if($_GPC['op']=='fenxiang'){
		//分享+5
		pdo_insert('meepo_bbs_topic_share',array('tid'=>$id,'time'=>time(),'num'=>1));
	}
	message('操作成功',referer(),success);
}

if($fo == 'delete'){
	$id = $_GPC['id'];
	
	pdo_delete('meepo_bbs_topics',array('id'=>$id));
	pdo_delete('meepo_bbs_topic_share',array('tid'=>$id));
	pdo_delete('meepo_bbs_topic_like',array('tid'=>$id));
	pdo_delete('meepo_bbs_topic_replie',array('tid'=>$id));
	
	message('操作成功',referer(),success);
}


if($fo == 'deleteall'){
	$uid = $_GPC['uid'];
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uid = :uid";
	$params = array(':uid'=>$uid);
	$topics = pdo_fetchall($sql,$params);
	
	foreach ($topics as $topic){
		$id = $topic['id'];
		pdo_delete('meepo_bbs_topic_share',array('tid'=>$id));
		pdo_delete('meepo_bbs_topic_like',array('tid'=>$id));
		pdo_delete('meepo_bbs_topic_replie',array('tid'=>$id));
	}
	
	pdo_delete('meepo_bbs_topics',array('uid'=>$uid));
	
	pdo_insert('meepo_bbs_blacklist',array('uid'=>$uid,'time'=>time()));
	message('操作成功',referer(),success);
}


if($fo == 'detail'){
	message('暂未开放',referer(),success);
}

if($fo == 'tab'){
	$id = $_GPC['id'];
	pdo_update('meepo_bbs_topics',array('tab'=>trim($_GPC['tab'])),array('id'=>$id));
	message('操作成功',referer(),success);
}

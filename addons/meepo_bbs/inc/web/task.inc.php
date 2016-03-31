<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->func('tpl');
$list = $thevalue = array();
$taskid = empty($_GET['taskid'])?0:intval($_GET['taskid']);
$table = 'meepo_bbs_task';
$op = $_GPC['op'];
if(checksubmit('tasksubmit')) {
	$_POST['name'] = ihtmlspecialchars($_POST['name']);
	$_POST['filename'] = str_replace(array('..', '/', '\\'), array('', '', ''), $_POST['filename']);
	if(empty($_POST['filename'])) {
		message('请选择上传的文件',referer(),error);
	}
	$starttime = empty($_GPC['time']['start'])?0:$_GPC['time']['start'];
	$endtime = empty($_GPC['time']['end'])?0:$_GPC['time']['end'];
	
	$setarr = array(
		'name' => $_POST['name'],
		'note' => trim($_POST['note']),
		'filename' => $_POST['filename'],
		'image' => trim($_POST['image']),
		'available' => intval($_POST['available']),
		'starttime' => strtotime($_GPC['time']['start']),
		'endtime' => strtotime($_GPC['time']['end']),
		'nexttype' => trim($_POST['nexttype']),
		'credit' => intval($_POST['credit']),
		'maxnum' => intval($_POST['maxnum']),
		'displayorder' => intval($_POST['displayorder']),
		'uniacid'=>$_W['uniacid']
	);
	
	$setarr['nexttime'] = $setarr['nexttype']=='time'?intval($_POST['nexttime']):0;
	
	if(empty($taskid)) {
		pdo_insert($table,$setarr);
	} else {
		pdo_update($table,$setarr,array('taskid'=>$taskid));
	}
	message('提交成功',$this->createWebUrl('task'),success);
}
if($_GET['op'] == 'one'){
	$filelist = array(
			array('filename'=>'first_like.task.php','name'=>'首次点赞','note'=>'首次点赞，奖励积分'),
			array('filename'=>'friend.task.php','name'=>'邀请好友','note'=>'邀请好友，奖励积分'),
			array('filename'=>'first_share.task.php','name'=>'首次转发','note'=>'首次看帖，奖励积分'),
			array('filename'=>'first_read.task.php','name'=>'首次看帖','note'=>'首次转发，奖励积分'),
			array('filename'=>'update_user.task.php','name'=>'完善个人信息','note'=>'完善个人信息，奖励积分'),
			array('filename'=>'first_post.task.php','name'=>'首次发帖','note'=>'首次发帖，奖励积分！'),
			
	);
	foreach ($filelist as $key=>$li){
		$data = array();
		$data['uniacid']=$_W['uniacid'];
		$data['available'] = 1;
		$data['num'] = 0;
		$data['maxnum'] = 1000;
		$data['image'] = $_W['siteroot'].'/addons/meepo_bbs/icon.jpg';
		$data['starttime'] = time();
		$data['endtime'] = time() + 2*7*60*60*24;
		$data['credit'] = 10;
		$data['displayorder'] = $key;
		$data['filename'] = $li['filename'];
		$data['name'] = $li['name'];
		$data['note'] = $li['note'];
		
		$sql = "SELECT taskid FROM ".tablename('meepo_bbs_task')." WHERE filename = :filename AND uniacid = :uniacid ";
		$params = array(':filename'=>$li['filename'],':uniacid'=>$_W['uniacid']);
		$taskid = pdo_fetchcolumn($sql,$params);
		
		if($taskid){
			$data = array();
			$data['starttime'] = time();
			$data['endtime'] = time() + 2*7*60*60*24;
			pdo_update('meepo_bbs_task',$data,array('taskid'=>$taskid));
		}else{
			pdo_insert('meepo_bbs_task',$data);
		}
		
	}
	message('一键导入成功',referer(),success);
}

if($_GET['op'] == 'edit') {
	$sql = "SELECT * FROM ".tablename($table)." WHERE taskid = :taskid ";
	$params = array(':taskid'=>$taskid);
	$thevalue = pdo_fetch($sql,$params);
	if($thevalue) {
		$thevalue['starttime'] = $thevalue['starttime']?date('Y-m-d H:i:s', $thevalue['starttime']):'';
		$thevalue['endtime'] = $thevalue['endtime']?date('Y-m-d H:i:s', $thevalue['endtime']):'';
	}

} elseif ($_GET['op'] == 'add') {
	$thevalue = array('taskid'=>0, 'available'=>1, 'nexttime'=>0, 'credit'=>0);
} elseif ($_GET['op'] == 'delete') {
	pdo_delete($table,array('taskid'=>$taskid));
	//pdo_delete($table_user,array('taskid'=>$taskid));
	message('操作成功',$this->createWebUrl('task'),success);
} else {
	$sql = "SELECT * FROM ".tablename($table)." WHERE uniacid = :uniacid ORDER BY displayorder";
	$params = array(':uniacid'=>$_W['uniacid']);
	$tasks = pdo_fetchall($sql,$params);
	foreach ($tasks as $task){
		$task['image'] = tomedia($task['image']);
		$task['starttime'] = $task['starttime']?date("Y-m-d H:i:s", $task['starttime']) : 'N/A';
		$task['endtime'] = $task['endtime']?date("Y-m-d H:i:s", $task['endtime']) : 'N/A';
		$task['image'] = empty($task['image'])?'image/task.gif':$task['image'];
		$list[] = $task;
	}
	$actives = array('view' => ' class="active"');
}
$nexttypearr = array($thevalue['nexttype'] => ' selected');
$nextimestyle = $thevalue['nexttype']=='time'?'':'none';

$availables = array($thevalue['available'] => ' checked');

include $this->template('task');
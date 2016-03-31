<?php
global $_GPC,$_W;
require MODULE_ROOT.'/model.php';
$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';
$handles = array('display', 'post', 'delete');
$VideoModel = new videoModel();

if(in_array($operation, $handles)){
	if('display' == $operation){
		$videos = $VideoModel->all();
	}elseif('post' == $operation){
		$id = intval($_GPC['id']);
		
		if(checksubmit('submit')){
			$videoName = $_GPC['video-name'];
			$videoCode = $_GPC['video-code'];
			if(empty($videoName) || empty($videoCode)){
				message('视频名称及代码信息不能为空！', '', 'error');
			}
			$data = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'sv_video_name' => $videoName,
				'sv_video_code' => $videoCode,
				'sv_video_time' => TIMESTAMP,
				);
			
			if (!empty($id)) {
				$VideoModel->modify($data, $id);
			} else {
				$id = $VideoModel->add($data);
			}
			message('更新视频成功！', $this->createWebUrl('videomanage', array('op' => 'display')), 'success');
		}

		if(!empty($id)){
			$video = $VideoModel->item($id);
			if (empty($video)) {
				message('抱歉，视频信息不存在或是已经删除！', '', 'error');
			}
		}
	}else{
		message('抱歉，视频信息不建议删除，可尝试修改！', '', 'error');
	}

	include $this->template('videos');
}else{
	message('非法操作！', '', 'error');
}


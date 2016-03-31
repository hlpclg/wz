<?php
global $_GPC,$_W;
require MODULE_ROOT.'/model.php';
$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';
$handles = array('display', 'post', 'delete');
$QrModel = new qrModel();
$DptModel = new dptModel();
$VideoModel = new videoModel();

if(in_array($operation, $handles)){
	if('display' == $operation){
		$qrs = $QrModel->all();
		$dpts = $DptModel->all();
		$videos = $VideoModel->all();
		$Dpts = array();
		foreach ($dpts as $key => $item) {
			$Dpts[$item['sv_dpt_id']] = $item['sv_dpt_name'];
		}
		$Videos = array();
		foreach ($videos as $key => $item) {
			$Videos[$item['sv_video_id']] = $item['sv_video_name'];
		}
		foreach ($qrs as $key => $item) {
			$keyword = 'scan_video_'.$item['sv_qr_id'];
			$qrs[$key]['dptName'] = $Dpts[$item['dptid']];
			$qrs[$key]['videoName'] = $Videos[$item['videoid']];
			$qrs[$key]['keyword'] = $keyword;
			$qrs[$key]['hasQr'] = $QrModel->isValidKeyword($keyword,$item['uniacid'],$item['acid']);
		}
	}elseif('post' == $operation){
		$id = intval($_GPC['id']);
		
		if(checksubmit('submit')){
			
			$qrdpt = $_GPC['qr-dpt'];
			$qrvideo = $_GPC['qr-video'];
			if(empty($qrdpt) || empty($qrvideo)){
				message('必须选择一个门店及一个视频！', '', 'error');
			}else{
				//第一步，创建门店与视频的关联,获得场景ID
				$item = $QrModel->add($qrdpt, $qrvideo);
				//第二步，将关联ID作为关键词，制作永久二维码
				$res = $QrModel->createQr('scan_video_'.$item['sv_qr_id'], $item['uniacid'], $item['acid']);
				if(is_array($res)){
					message("公众平台返回接口错误. <br />错误代码为: {$res['errorcode']} <br />错误信息为: {$res['message']}");
				}else{
					message('恭喜，生成带参数二维码成功！', $this->createWebUrl('qrmanage', array('op' => 'display')), 'success');
				}
			}
			
		}
		
		if(!empty($id)){
			//重新生成某二维码
			message('免费版不支持修改，请点击链接查看付费服务！', $this->createWebUrl('info', array()), 'error');
		}else{
			//制作二维码所需信息：
			$dpts = $DptModel->all();
			$videos = $VideoModel->all();
			$acidarr = uni_accounts($_W['uniacid']);
		}
	}else{
		message('抱歉，二维码信息不建议删除！', '', 'error');
	}

	include $this->template('qrs');
}else{
	message('非法操作！', '', 'error');
}


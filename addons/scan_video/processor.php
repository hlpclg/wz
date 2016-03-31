<?php
/**
 * 扫码看视频模块处理程序
 *
 * @author gmega
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Scan_videoModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		// return $this->respText(MODULE_URL);
		$rule = explode('_', $content);
		WeUtility::logging('debug_content', $content, 'scan_video');
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		$QrModel = new qrModel();
		$res = $QrModel->item($rule[2]);
		// WeUtility::logging('debug_res', $res,'scan_video');
		if(empty($res)){
			return $this->respText('未知对应的视频信息');
		}else{
			$VideoModel = new videoModel();
			$video = $VideoModel->item($res['videoid']);
			// WeUtility::logging('debug_video', $video,'scan_video');
			if(empty($video) || empty($video['sv_video_code'])){
				return $this->respText('视频信息不存在');
			}else{
				$new = array(array('title'=>'点击看视频',
									'description'=>'视频名称：'.$video['sv_video_name'],
									'picurl'=>$_W['siteroot'].'/addons/scan_video/images/cover.png',
									'url'=>$video['sv_video_code']));
				return $this->respNews($new);
			}
		}
		
	}
}

class videoModel{
	public $tb = 'sv_videos';

	public function all() {
		global $_W;
		return pdo_fetchall('SELECT * FROM '.tablename($this->tb)." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
	}

	public function item($id){
		return pdo_fetch("SELECT * FROM ".tablename($this->tb)." WHERE `sv_video_id`=:id",array(':id'=>$id));
	}
}


class qrModel{
	public $tb = 'sv_qr';

	public function all() {
		global $_W;
		return pdo_fetchall('SELECT * FROM '.tablename($this->tb)." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
	}

	public function item($id){
		return pdo_fetch("SELECT * FROM ".tablename($this->tb)." WHERE `sv_qr_id`=:id",array(':id'=>$id));
	}

}
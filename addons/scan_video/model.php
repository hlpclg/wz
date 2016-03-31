<?php
class dptModel{
	public $tb = 'sv_dpt';

	public function all() {
		global $_W;
		return pdo_fetchall('SELECT * FROM '.tablename($this->tb)." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
	}

	public function item($id){
		return pdo_fetch("SELECT * FROM ".tablename($this->tb)." WHERE `sv_dpt_id`=:id",array(':id'=>$id));
	}

	public function modify($data, $id){
		pdo_update($this->tb, $data, array('sv_dpt_id' => $id));
	}

	public function add($data){
		pdo_insert($this->tb, $data);
		
		return pdo_insertid();
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

	public function modify($data, $id){
		pdo_update($this->tb, $data, array('sv_video_id' => $id));
	}

	public function add($data){
		pdo_insert($this->tb, $data);
		return pdo_insertid();
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

	public function modify($data, $id){
		pdo_update($this->tb, $data, array('sv_qr_id' => $id));
	}

	/**
	 * 根据门店ID，视频ID，创建链接ID（二维码ID）
	 * 若链接ID不存在，则新增；若存在，则忽略
	 * @return array 要添加的门店ID 与视频ID的关联ID
	 * */
	public function add($dpt, $video){
		global $_W,$_GPC;
		$acid = intval($_GPC['acid']);
		$links = array();
		$res = pdo_fetch("SELECT * FROM ".tablename($this->tb)." WHERE `uniacid`=:uniacid AND `acid`=:acid AND `dptid`=:dptid AND `videoid`=:videoid",array(':uniacid'=>$_W['uniacid'], ':acid'=>$acid, ':dptid'=>$dpt, ':videoid'=>$video));
		if(empty($res)){
			$data = array('uniacid'=>$_W['uniacid'],
						  'acid'=>$acid,
						  'dptid'=>$dpt,
						  'videoid'=>$video,
						  );
			pdo_insert($this->tb, $data);
			$id = pdo_insertid();
		}else{
			$id = $res['sv_qr_id'];
		}
		$res = pdo_fetch("SELECT * FROM " . tablename($this->tb) . " WHERE `sv_qr_id` = :id ", array(':id'=>$id));
		return $res;
	}

	/**
	 * 根据提供信息，使用系统内置生成二维码的方式来生成二维码
	 * 生成的二维码详细信息依然保存在系统二维码表qrcode中
	 * @param $keyWord string 二维码关键词
	 * */
	public function createQr($keyword){
		global $_W,$_GPC;
		$acid = intval($_GPC['acid']);
		
		load()->func('communication');
		$res = pdo_fetch("SELECT * FROM ".tablename('qrcode')." WHERE `keyword`=:keyword AND `acid` = :acid AND `uniacid` = :uniacid",array(':keyword'=>$keyword,':acid' => $acid, ':uniacid'=>$_W['uniacid']));
		if(!empty($res)){
			//关键词已存在，不重复制作二维码
			return true;
		}

		$barcode = array(
				'expire_seconds' => '',
				'action_name' => '',
				'action_info' => array(
					'scene' => array('scene_id' => ''),
				),
		);
		
		$uniacccount = WeAccount::create($acid);
		
		$qrcid = pdo_fetchcolumn("SELECT `qrcid` FROM ".tablename('qrcode')." WHERE `acid` = :acid AND `model` = '2' ORDER BY `qrcid` DESC", array(':acid' => $acid));
		$barcode['action_info']['scene']['scene_id'] = !empty($qrcid) ? ($qrcid+1) : 1;
		if ($barcode['action_info']['scene']['scene_id'] > 100000) {
			message('抱歉，永久二维码已经生成最大数量，请先删除一些。');
		}
		$barcode['action_name'] = 'QR_LIMIT_SCENE';
		$result = $uniacccount->barCodeCreateFixed($barcode);
		
		if(!is_error($result)) {
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $acid,
				'qrcid' => $barcode['action_info']['scene']['scene_id'],
				'keyword' => $keyword,
				'name' => '扫码看视频',
				'model' => 2,//永久型二维码
				'ticket' => $result['ticket'],
				'expire' => $result['expire_seconds'],
				'createtime' => TIMESTAMP,
				'status' => '1',
			);
			$fieldExists = pdo_fieldexists('qrcode', 'type');
			if($fieldExists){
				$insert['type'] = 'scene';
			}
			pdo_insert('qrcode', $insert);
			
			return true;
		} else {
			return $result;
		}
	}

	/**
	 * 判断是否存在对应关键字的二维码
	 * @return boolean 
	 * */
	public function isValidKeyword($keyword,$uniacid,$acid){
		$res = pdo_fetch("SELECT * FROM ".tablename('qrcode')." WHERE `keyword`=:keyword AND `acid` = :acid AND `uniacid` = :uniacid",array(':keyword'=>$keyword,':acid' => $acid, ':uniacid'=>$uniacid));
		if(!empty($res)){
			return $res;
		}
		return false;
	}

}

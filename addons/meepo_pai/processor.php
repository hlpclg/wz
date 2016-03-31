<?php
/**
 * 全民自拍模块处理程序
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_paiModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W, $engine;
		if (!in_array($this->message['type'], array('text', 'image'))) {
			return false;
		}
		
		if ($this->message['type'] == 'image') {
			checkauth();
			$uid = $_W['member']['uid'];
			$user = pdo_fetch("SELECT * FROM ".tablename('meepo_pai')." WHERE uid='{$uid}' AND uniacid = '{$_W['uniacid']}'");
		
			if(!$user){
				pdo_insert('meepo_pai',array('uid'=>$uid,'num'=>0,'time'=>time(),'uniacid'=>$_W['uniacid']));
			}
		
			$data['src_img'] = $this->message['picurl'];
			pdo_update('meepo_pai',$data,array('uid'=>$uid));
			if (empty($user['nickname'])) {
				return $this->respText('只需再完成下面最后一步，即可成功报名全民自拍比赛 <a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('register')).'">点击报名</a>');
			}else{
				return $this->respText('上传图片成功 <a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('index')).'">点击报名</a>');
			}
		}
	}
}
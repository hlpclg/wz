<?php
/**
 * 腾讯多客服模块处理程序
 *
 * @author n1ce   QQ：800083075
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class N1ce_newchatModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$content = $this->message['content'];
		$wait = $this->module['config']['wait'];
		$openid = $_W['fans']['from_user'];
		$start1 = $this->module['config']['start1'];
		$start2 = $this->module['config']['start2'];
		$end1 = $this->module['config']['end1'];
		$end2 = $this->module['config']['end2'];
		$busy = $this->module['config']['busy'];
		$nhour = date('H', TIMESTAMP);
		$flag = 0;
		if($start1 == 0 && $end1 == 23) {
			$flag = 1;
		} elseif($start1 != '-1' && ($nhour >= $start1) && ($nhour <= $end1)) {
			$flag = 1;
		} elseif($start2 != '-1' &&  ($nhour >= $start2) && ($nhour <= $end2)) {
			$flag = 1;
		} else {
			$flag = 0;
		}

		if($flag == 1) {
			
			//$fans=$this->fansInfo($openid);
			//$name=$fans['nickname'];
			//$this->sendtext("$name",$openid);
			$this->sendtext("$wait",$openid);
			return $this->respCustom($reply['content']);
		} else {
			$content = $_W['account']['name'].'提醒您，客服在线时间为：' . $start1 .'时~' . $end1 . '时';
			if($start2 != '-1') {
				$content .= ',' . $start2 . '时~' . $end2 . '时';
			}
			$reply['content'] = $content;
			$this->sendtext("$busy",$openid);
			return $this->respText($reply['content']);
		}

		/*$wait=$replay['wait'];
		$openid = $_W['fans']['from_user'];
		//$fans=$this->fansInfo($openid);
		//$name=$fans['nickname'];
		//$this->sendtext("$name",$openid);
		$this->sendtext("$wait",$openid);
		return $this->respCustom($reply['content']);*/
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
	/*private function fansInfo($openid){
			global $_W;
			$acc = WeAccount::create($_W['account']['acid']);
			$data = $acc->fansQueryInfo($openid);
			return $data;
		}*/

	private function sendtext($txt,$openid){
		global $_W;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;
	}
}
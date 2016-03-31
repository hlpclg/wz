
<?php
/**
 * 留声墙模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');

class On3_voxpicModuleProcessor extends WeModuleProcessor {

	public $tab_reply = 'vp_reply';
	public $tab_items = 'vp_items';
	public $tab_tabs = 'vp_tab';

	public function respond() {
		global $_GPC,$_W;
		$rid = $this->rule;
		$message = $this->message;
		$content = $message['content'];//触发的内容
		$openid = $message['from'];//用户的openid
		$createtime = $message['createtime'];//消息发送的时间
		$msgtype = $message['msgtype'];//消息类型
		$reply = pdo_fetch('SELECT * FROM'.tablename($this->tab_reply)." WHERE uniacid = :uniacid AND rid = :rid",array(':uniacid'=>$_W['uniacid'],':rid'=>$rid));
		$tabid = pdo_fetchcolumn('SELECT tabid FROM'.tablename($this->tab_tabs)." WHERE uniacid = :uniacid AND openid = :openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		if(empty($reply)){
			return $this->respText('您要参加的活动还没开始哦..请旁边玩会儿..O(∩_∩)O哈哈~');
		}
		if($this->inContext){
			if($content ==$reply['quit']){
					$this->endContext();
					if(empty($tabid)){
						return $this->respText('一定要再回来制作一张留声卡哦..');
					}
					return $this->respText('真棒,您的留声卡已经制作喽..<a href="'.$this->createMobileUrl('index').'">点击查看</a>');
			}
			if($msgtype=='image'&&empty($tabid)){
				$img = $this->copyMedia('IMG',$message['picurl']);
				$insert = array('img'=>$img,'uniacid'=>$_W['uniacid'],'openid'=>$openid,'createtime'=>TIMESTAMP);
				pdo_insert($this->tab_items,$insert);
				$id = pdo_insertid();
				pdo_insert($this->tab_tabs,array('uniacid'=>$_W['uniacid'],'tabid'=>$id,'openid'=>$openid));
				$this->refreshContext(60*5);//刷新过期时间
				return $this->respText($reply['txt_note']."\n".$reply['voc_note']."完成制作请按\"".$reply['quit']."\"");
			}
			if($msgtype=='text'&&!empty($tabid)){
				pdo_update($this->tab_items,array('summary'=>$content),array('id'=>$tabid));
				$this->refreshContext(60*5);//刷新过期时间
				return $this->respText($reply['voc_note']."完成制作请按\"".$reply['quit']."\"");
			}
			if($msgtype=='voice'&&!empty($tabid)){
				$filename = $this->copyMedia('VOX',$message['mediaid']);
				$amrurl = toimage($filename);
				pdo_update($this->tab_items,array('amr'=>$amrurl),array('id'=>$tabid));
				if(!empty($amrurl)){
					$mp3 = $this->amrTomp3($amrurl);
					pdo_update($this->tab_items,array('voice'=>$mp3),array('id'=>$tabid));
				}
				pdo_delete($this->tab_tabs,array('id'=>$tabid));
				$this->endContext();
				return $this->respText('真棒,您的留声卡已经制作喽..<a href="'.$this->createMobileUrl('index').'">点击查看</a>');
			}
			return $this->respText($reply['welcome']."\n"."完成制作请按\"".$reply['quit']."\"");
		}else{
			pdo_delete($this->tab_tabs,array('uniacid'=>$_W['uniacid'],'openid'=>$openid));
			$this->beginContext(60*5);//开启上下文模式默认设置为5分钟..
			return $this->respText($reply['welcome']."完成制作请按\"".$reply['quit']."\"");
		}
	}

	private function copyMedia($type,$arg){
		global $_GPC,$_W;
		load()->func('file');
		load()->func('communication');
		if(empty($arg)||empty($type)){
			return '';
		}
		if(empty($arg)||empty($type)){
			return '';
		}
		if(strtoupper($type)=='IMG'){
			$dat =  ihttp_get($arg);
			if($dat['code']==200){
				$imgtype = '.'.substr($dat['headers']['Content-Type'], intval(strpos($dat['headers']['Content-Type'],'/'))+1);
				if(!empty($imgtype)){
					$filename = TIMESTAMP.$imgtype;
					$bool =  file_write('/on3_voxpic/img/'.$filename,$dat['content']);
					if($bool){
						return 'on3_voxpic/img/'.$filename;
					}
				}
			}
				return '';
		}elseif(strtoupper($type)=='VOX'){
			load()->classs('weixin.account');
			$wxObj= WeixinAccount::create($_W['uniacid']);
			$access_token = $wxObj->fetch_token();
			$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s';
			$source = sprintf($url,$access_token,$arg);
			$dat = ihttp_get($source);
			if($dat['code']==200){
				$filename = TIMESTAMP.'.amr';
				if(!empty($dat['content'])){
					$bool = file_write('/on3_voxpic/vox/'.$filename,$dat['content']);
					if($bool){
						return 'on3_voxpic/vox/'.$filename;
					}
				}
			}
			return '';
		}
		return '';
	}
	private function amrTomp3($url){
		load()->func('file');
		$apiurl = 'http://api.yizhancms.com/video/index.php?i=1&f=%s';
		if(!empty($url)){
			$file=base64_encode(base64_encode($url));//加密地址
			$amrurl = sprintf($apiurl,$file);
			$record=file_get_contents($amrurl);
			$dat=(array)json_decode($record);//处理返回值
			if(!empty($dat['f'])){
				$index=strripos($dat['f'], '/')+1;
				$filename = 'on3_voxpic/mp3/'.substr($dat['f'], $index);
				mkdirs(dirname(ATTACHMENT_ROOT.'/'.$filename));
				$bool = copy($dat['f'],ATTACHMENT_ROOT.'/'.$filename);
				if($bool){
					return $filename;
				}
			}
		}
		return '';
	}
}
?>
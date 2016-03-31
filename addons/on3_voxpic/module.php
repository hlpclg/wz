<?php
/**
 * 留声墙模块定义
 *
 * @author On3
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class On3_voxpicModule extends WeModule {

	public $tab_reply = 'vp_reply';

	public function fieldsFormDisplay($rid = 0) {
		global $_W,$_GPC;
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		$dat = pdo_fetch('SELECT * FROM'.tablename($this->tab_reply)." WHERE uniacid = :uniacid AND rid = :rid",array(':uniacid'=>$_W['uniacid'],':rid'=>$rid));
		$record = pdo_fetch('SELECT * FROM'.tablename($this->tab_reply)." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
		if(!empty($record)&&$rid==0){
			die("<script>alert('留声墙只允许添加一条触发记录...');history.go(-1);</script>");
		}
		if(empty($dat)){
			$dat = array('title'=>$_W['account']['name'].'的留声墙',
				'welcome'=>'发送照片制作好看又好玩的留声卡吧.',
				'txt_note'=>'哎呦,厉害啦,发送一段文字为照片添加心情吧',
				'voc_note'=>'哎呦,厉害啦,发送一段语音为照片添加声音吧..',
				'quit'=>'。');
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_W,$_GPC;
		$dat = $_GPC['dat'];
		foreach ($dat as $k => &$v) {
			if(empty($v)&&$k!='url'){
				message('必填项没有填写完整..',referer(),'error');
				exit();
			}
			$v = trim($v);
		}
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_W,$_GPC;
		$dat = pdo_fetch('SELECT * FROM'.tablename($this->tab_reply)." WHERE uniacid = :uniacid AND rid = :rid",array(':uniacid'=>$_W['uniacid'],':rid'=>$rid));
		$data = $_GPC['dat'];
		if(empty($dat)){
			$data['uniacid'] = $_W['uniacid'];
			$data['rid']=$rid;
			if(!strexists(strtolower($data['url']),'http')){
				$data['url'] = 'http://'.$data['url'];
			}
			pdo_insert($this->tab_reply,$data);
		}else{
			if(!strexists(strtolower($data['url']),'http')){
				$data['url'] = 'http://'.$data['url'];
			}
			pdo_update($this->tab_reply,$data,array('uniacid'=>$_W['uniacid'],'rid'=>$rid));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		pdo_delete($this->tab_reply,array('rid'=>$rid,'uniacid'=>$_W['uniacid']));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		include $this->template('setting');
	}

}
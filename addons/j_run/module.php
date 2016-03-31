<?php
/**
 * 捷讯约跑模块定义
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_runModule extends WeModule {
	
	public function fieldsFormDisplay($rid = 0) {
		global $_W, $_GPC;
		$reply = pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid = :rid", array(':rid' => $rid));
		load()->func('tpl');
		$list = pdo_fetchall("SELECT * FROM ".tablename('j_run_ad')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		$adlist=@explode(',',$reply['adlist']);
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_W, $_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'weid'=> $_W['uniacid'],
			'cover' => $_GPC['cover'],
			'thumb_top' => $_GPC['thumb_top'],
			'thumb_share' => $_GPC['thumb_share'],
			'thumb_end' => $_GPC['thumb_end'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'rule' => trim($_GPC['rule']),
			'starttime' => strtotime($_GPC['starttime']),
			'endtime' => strtotime($_GPC['endtime']),
			'status' => intval($_GPC['status']),
			'need' => intval($_GPC['need']) ? intval($_GPC['need']) : 200,
			'gzurl' => $_GPC['gzurl'],
			'gift' => $_GPC['gift']  ? $_GPC['gift'] : '奖品',
			'ranking' => intval($_GPC['ranking']),
			'adlist' => implode(',',$_GPC['adlist']),
			'copyright' => $_GPC['copyright'],
			'code' => $_GPC['code'],
			'gametype' => intval($_GPC['gametype']),
			'gametime' => intval($_GPC['gametime']),
			'slogan' => trim($_GPC['slogan']),
			
			'img_loadImg' => trim($_GPC['img_loadImg']),
			'img_personImg' => trim($_GPC['img_personImg']),
			'img_personsImg' => trim($_GPC['img_personsImg']),
			'img_treeImage' => trim($_GPC['img_treeImage']),
			'img_green' => trim($_GPC['img_green']),
			'img_road' => trim($_GPC['img_road']),
			'img_sun' => trim($_GPC['img_sun']),
			'img_cloud1' => trim($_GPC['img_cloud1']),
			'img_cloud2' => trim($_GPC['img_cloud2']),
			'img_cloud3' => trim($_GPC['img_cloud3']),
			'ad' => trim($_GPC['ad']),
			'img_fullbg' => trim($_GPC['img_fullbg']),
			'img_personImg_girl' => trim($_GPC['img_personImg_girl']),
			'img_personsImg_girl' => trim($_GPC['img_personsImg_girl']),
			'open_bg' => intval($_GPC['open_bg']),
			'music' => trim($_GPC['music']),
			'issex' => intval($_GPC['issex']),
			'modol' => intval($_GPC['modol']),
			'speed' => floatval($_GPC['speed']) ? floatval($_GPC['speed']) : 0.3,
			'speedStep' => floatval($_GPC['speedStep']) ? floatval($_GPC['speedStep']) : 0.5,
			'share_title' => trim($_GPC['share_title']) ? trim($_GPC['share_title']):"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！",
			'helpnum' => intval($_GPC['helpnum']),
			'appid' => trim($_GPC['appid']),
			'secret' => trim($_GPC['secret']),
		);
		if (empty($id)) {
			pdo_insert('j_run_reply', $insert);
		} else {
			pdo_update('j_run_reply', $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		load()->func('file');
		pdo_delete('j_run_reply', array('id'=>$rid));
		return true;
	}

	public function settingsDisplay($settings) {
		global $_GPC, $_W;
        if (checksubmit()) {
            $cfg = array(
                'key_wordtime' => intval($_GPC['key_wordtime']),
				'key_appid' => trim($_GPC['key_appid']),
				'key_secret' => trim($_GPC['key_secret']),
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		load()->func('tpl');
		include $this->template('setting');
	}

}
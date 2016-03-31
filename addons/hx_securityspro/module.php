<?php
/**
 * 防伪码增强版模块定义
 *
 * @author 华轩科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_securitysproModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if($rid==0){
			$reply = array(
				'tnumber' =>3,				
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename('hx_securityspro_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'weid' => $_W['weid'],
			'tnumber' => $_GPC['tnumber'],
			'Reply' => $_GPC['Reply'],
			'Failure' => $_GPC['Failure']
		);
		if (empty($id)) {
			pdo_insert('hx_securityspro_reply', $insert);
		}else{
			pdo_update('hx_securityspro_reply', $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		$replies = pdo_fetchall("SELECT id,rid FROM ".tablename('hx_securityspro_reply')." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
				$ridid[] =$row['rid'];
			}
		}
		pdo_delete('hx_securityspro_reply', "id IN ('".implode("','", $deleteid)."')");
		return true;
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$this->saveSettings($dat);
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}
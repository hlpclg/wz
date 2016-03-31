<?php
/**
 * zam微信邮件模块定义
 *
 * @author Meepo_zam
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Zam_weimailsModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		if(checksubmit()) {
			$cfg = array();
			$cfg['type'] = $_GPC['type'];
			$cfg['size'] = intval($_GPC['size']);
			$cfg['mailadd'] = $_GPC['mailadd'];
			$cfg['password'] = $_GPC['password'];
			$cfg['smtp'] = $_GPC['smtp'];
			$cfg['headtitle'] = $_GPC['headtitle'];
			$cfg['logo'] = $_GPC['logo'];
			 
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['size'])) {
			$settings['size'] =10;
		}
		if(!isset($settings['type'])) {
			$settings['type'] ='xml,jpg,jpeg,css';
		}
		if(!isset($settings['mailadd'])) {
			$settings['mailadd'] = '龙哥专用邮箱284099857@qq.com';
		}
		if(!isset($settings['smtp'])) {
			$settings['smtp'] = 'smtp.qq.com';
		}
		if(!isset($settings['headtitle'])) {
			$settings['headtitle'] = 'MEEPO大学生联盟致力于打造最强校园联盟！';
		}
		include $this->template('setting');
	}

}
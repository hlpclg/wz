<?php
/**
 * 全民总动员模块定义
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/meepo_fen');
class Meepo_fenModule extends WeModule {
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
		load()->model('account');
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			load()->func('file');
			mkdirs(MB_ROOT . '/cert');
			$r = true;
			if (!empty($_GPC['cert'])) {
				$ret = file_put_contents(MB_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
				$r = $r && $ret;
			}
			if (!empty($_GPC['key'])) {
				$ret = file_put_contents(MB_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
				$r = $r && $ret;
			}
			if (!empty($_GPC['ca'])) {
				$ret = file_put_contents(MB_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
				$r = $r && $ret;
			}
			if (!$r) {
				message('证书保存失败, 请保证 /addons/microb_redpack/cert/ 目录可写');
			}
			$input = array_elements(array(
					'appid',
					'secret',
					'mchid',
					'password',
					'ip',
					'minnum',
					'logo'
			) , $_GPC);
			$input['appid'] = trim($input['appid']);
			$input['secret'] = trim($input['secret']);
			$input['mchid'] = trim($input['mchid']);
			$input['password'] = trim($input['password']);
			$input['ip'] = trim($input['ip']);
			$input['minnum'] = trim($input['minnum']);
			$input['logo'] = trim($input['logo']);
			if ($this->saveSettings($input)) {
				message('保存参数成功', 'refresh');
			}
		}
		
		$setting = uni_setting($_W['uniacid'], array('payment'));
		$accounts = array();
		if(!empty($setting['payment']['wechat']['account'])){
			$accounts = account_fetch($setting['payment']['wechat']['account']);
		}
		
		if(empty($settings['minnum'])) {
			$settings['minnum'] = '100.00';
		}
		if(empty($settings['appid'])) {
			$settings['appid'] = $accounts['key'];
		}
		if(empty($settings['secret'])) {
			$settings['secret'] =  $accounts['secret'];
		}
		if (empty($settings['ip'])) {
			$settings['ip'] = $_SERVER['SERVER_ADDR'];
		}
		if(empty($settings['mchid'])){
			if(!empty($setting['payment']['wechat']['mchid'])){
				$settings['mchid'] = $setting['payment']['wechat']['mchid'];
			}
			
		}
		if(empty($settings['password'])){
			if(!empty($setting['payment']['wechat']['apikey'])){
				$settings['password'] = $setting['payment']['wechat']['apikey'];
			}
		}
		
		//这里来展示设置项表单
		include $this->template('settings');
	}

}
<?php
/**
 * 积分充值模块微站定义
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_credit1ModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//积分充值入口
		global $_W, $_GPC;
		if (empty($_W['member']['uid'])) {
			checkauth();
		}
		$credit1_lv = $this->module['config']['credit1_lv']?$this->module['config']['credit1_lv']:1;
		$username = $_W['member']['email'] ? $_W['member']['email'] : $_W['member']['mobile'];
		if(empty($username)) {
			message('您的用户信息不完整,请完善用户信息后再充值', '', 'error');
		}
		if (checksubmit('submit', true) || !empty($_GPC['ajax'])) {
			$fee = floatval($_GPC['money']);
			if($fee <= 0) {
				message('支付错误, 积分小于0');
			}
			$chargerecord = pdo_fetch("SELECT * FROM ".tablename('mc_credits_recharge')." WHERE uniacid = :uniacid AND uid = :uid AND fee = :fee AND status = '0'", array(
					':uniacid' => $_W['uniacid'],
					':uid' => $_W['member']['uid'],
					':fee' => $fee*$credit1_lv,
			));
			if (empty($chargerecord)) {
				$chargerecord = array(
						'uid' => $_W['member']['uid'],
						'uniacid' => $_W['uniacid'],
						'tid' => date('YmdHi').random(10, 1),
						'fee' => $fee*$credit1_lv,
						'status' => 0,
						'createtime' => TIMESTAMP,
				);
				if (!pdo_insert('mc_credits_recharge', $chargerecord)) {
					message('创建充值订单失败，请重试！', $this->createMobileUrl('index'), 'error');
				}
			}
			$params = array(
					'tid' => $chargerecord['tid'],
					'ordersn' => $chargerecord['tid'],
					'title' => '系统充值积分',
					'fee' => $chargerecord['fee'],
					'user' => $_W['member']['uid'],
			);
			$this->pay($params);
		} else {
			include $this->template('index');
		}
	}
	protected function pay($params = array()) {
		global $_W;
		$params['module'] = $this->module['name'];
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$pay = $setting['payment'];
		$pay['credit']['switch'] = false;
		$pay['delivery']['switch'] = false;
		include $this->template('common/paycenter');
	}
	public function payResult($params) {
		load()->model('mc');
		$status = pdo_fetchcolumn("SELECT status FROM ".tablename('mc_credits_recharge')." WHERE tid = :tid", array(':tid' => $params['tid']));
		if (empty($status)) {
			$credit1_lv = $this->module['config']['credit1_lv']?$this->module['config']['credit1_lv']:1;
			$fee = $params['fee']/$credit1_lv;
			$data = array('status' => $params['result'] == 'success' ? 1 : -1);
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
				$params['user'] = mc_openid2uid($params['user']);
			}
			pdo_update('mc_credits_recharge', $data, array('tid' => $params['tid']));
			if ($params['result'] == 'success' && $params['from'] == 'notify') {
				$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
				//a:2:{s:8:"activity";s:7:"credit1";s:8:"currency";s:7:"credit2";}
				$credit = $setting['creditbehaviors']['activity'];
				if(empty($credit)) {
					message('站点积分行为参数配置错误,请联系服务商', '', 'error');
				} else {
					$paydata = array('wechat' => '微信', 'alipay' => '支付宝');
					$record[] = $params['user'];
					$record[] = '用户通过' . $paydata[$params['type']] . '充值' . $fee.'积分';
					mc_credit_update($params['user'], $credit, $fee, $record);
				}
			}
		}
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				message('支付成功！', '../../app/' . url('mc/home'), 'success');
			} else {
				message('支付失败！', '../../app/' . url('mc/home'), 'error');
			}
		}
	}
}
<?php
defined('IN_IA') or exit('Access Denied');

class Water_superModuleProcessor extends WeModuleProcessor
{
	public $shoptable = 'water_super_shop';
	public $employeetable = 'water_super_employees';

	public function respond()
	{
		global $_W, $_GPC;
		$content = $this->message['content'];
		$openid = $this->message ['from'];
		$employee = pdo_fetch("SELECT * FROM " . tablename($this->employeetable) . " WHERE openid =:openid and uniacid = :uniacid", array(':openid' => $openid, ':uniacid' => $_W ['uniacid']));
		if ($employee) {
			return $this->respText('您已经注册，如果需要修改信息请联系管理员');
		}
		$info = explode("#", $content);
		if (count($info) != 3) {
			return $this->respText('请输入正确的增加员工指令：新增员工密码#员工姓名#员工手机号码');
		}
		if (empty($info[1])) {
			return $this->respText('新增员工姓名不能为空');
		}
		if (!preg_match('/1[3458]{1}\\d{9}$/', $info[2])) {
			return $this->respText('新增员工手机号码格式不正确');
		}
		$needaudit = 1;
		if ($system['needaudit'] == 2) {
			$needaudit = 2;
		} else {
			$needaudit = 1;
		}
		$data = array('uniacid' => $_W ['uniacid'], 'openid' => $openid, 'employeename' => $info[1], 'tel' => $info[2], 'employeestate' => $needaudit, 'sumorders' => 0, 'workstate' => 1);
		pdo_insert($this->employeetable, $data);
		$system = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
		if ($needaudit == 2) {
			return $this->respText('新增员工成功，需要管理员在后台审核才能通过');
		} else {
			return $this->respText('新增员工成功');
		}
	}
}
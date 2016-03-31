<?php
defined('IN_IA') or exit ('Access Denied');

class Water_superModuleSite extends WeModuleSite
{
	public $addresstable = 'water_super_address';
	public $orderstable = 'water_super_orders';
	public $exprestable = 'water_super_express';
	public $shoptable = 'water_super_shop';
	public $rnumbertbale = 'water_super_rnumber';
	public $cardnumbertbale = 'water_super_cardnumber';
	public $membertable = 'water_super_members';
	public $employeetable = 'water_super_employees';
	public $coupontable = 'water_super_coupon';
	public $couponrecordtable = 'water_super_coupon_record';
	public $goodstable = 'water_super_goods';
	public $citytable = 'water_super_citys';
	public $areatable = 'water_super_areas';
	public $cookieflag = '2015yueleorder';
	public $cookieip = 'eorderclientip';
	public $USERID = '';
	public $orderstate0 = '0';
	public $orderstate1 = '1';
	public $orderstate2 = '2';
	public $orderstate3 = '3';
	public $orderstate4 = '4';
	public $orderstate5 = '5';
	public $ordertype01 = 'wash';
	public $ordertype02 = 'recharge';
	public $unPay = 0;
	public $hasPay = 1;
	public $paytype1 = 1;
	public $paytype2 = 2;
	public $paytype3 = 3;
	public $paytype4 = 4;
	public $workstate0 = 0;
	public $workstate1 = 1;
	public $workstate2 = 2;

	public function doMobileWorkOrder()
	{
		global $_GPC, $_W;
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			$workopenid = $_GPC ['workopenid'];
		}
		if (!$workopenid) {
			message('工作人员入口不正确');
		}
		$employee = pdo_fetch("SELECT * FROM " . tablename($this->employeetable) . " WHERE employeestate = 2 and openid =:openid and uniacid = :uniacid", array(':openid' => $workopenid, ':uniacid' => $_W ['uniacid']));
		if (!$employee) {
			message('没有找到您的工作信息或您的信息正在审核中，请联系管理员');
		}
		$systeminfo = pdo_fetch("SELECT shopname,dangmf,weixf,zhifb FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$condition = " customercity = '" . $employee['city'] . "'";
		if ($employee['area'] != '0') {
			$condition .= " and customerarea = '" . $employee['area'] . "'";
		}
		$orderList = pdo_fetchall("SELECT * FROM " . tablename($this->orderstable) . " WHERE " . $condition . " and  ordertype = 'wash' and uniacid = '" . $_W ['uniacid'] . "' and orderstate = 0 order by id desc");
		include $this->template('workordering');
	}

	public function doMobileMyWorkOrder()
	{
		global $_GPC, $_W;
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			message('工作人员入口不正确');
		}
		$systeminfo = pdo_fetch("SELECT shopname,dangmf,weixf,zhifb,isygdj FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$orderList = pdo_fetchall("SELECT * FROM " . tablename($this->orderstable) . " WHERE ordertype = 'wash' and uniacid = '" . $_W ['uniacid'] . "' and orderstate in (1,2,3,4) and workopenid = '" . $workopenid . "' order by id desc");
		include $this->template('myworkorder');
	}

	public function doMobileMyWorkEndOrder()
	{
		global $_GPC, $_W;
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			message('工作人员入口不正确');
		}
		$systeminfo = pdo_fetch("SELECT shopname,dangmf,weixf,zhifb FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$orderList = pdo_fetchall("SELECT * FROM " . tablename($this->orderstable) . " WHERE ordertype = 'wash' and uniacid = '" . $_W ['uniacid'] . "' and orderstate = 5 and workopenid = '" . $workopenid . "' order by id desc");
		include $this->template('myworkendorder');
	}

	public function doMobileOrderDeal()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['id']);
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			$workopenid = $_GPC ['workopenid'];
		}
		if (!$workopenid) {
			message('工作人员入口不正确' . $orderid);
		}
		if ($orderid) {
			$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
			if ($orderinfo) {
				$data = array('orderstate' => $this->orderstate1, 'workopenid' => $workopenid);
				pdo_update($this->orderstable, $data, array('id' => $orderid));
				$employee = pdo_fetch("SELECT * FROM " . tablename($this->employeetable) . " WHERE openid =:openid and uniacid = :uniacid", array(':openid' => $workopenid, ':uniacid' => $_W ['uniacid']));
				$employdata = array('workstate' => $this->workstate1, 'sumorders' => $employee ['sumorders'] + 1);
				pdo_update($this->employeetable, $employdata, array('id' => $employee['id']));
				$this->createOrderExpress($orderid, $this->orderstate1, $employee ['id'], $employee ['employeename'], $employee ['tel']);
				$this->doMobileMyWorkOrder();
			} else {
				message('订单不存在或已被删除');
			}
		} else {
			message('工作人员入口不正确');
		}
	}

	public function doMobileOrderWUpdate()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['id']);
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			$workopenid = $_GPC ['workopenid'];
		}
		if (!$workopenid) {
			message('工作人员入口不正确' . $orderid);
		}
		$orderstate = intval($_GPC ['orderstate']);
		if (!$orderstate) {
			message('入口不正确');
		}
		if ($orderid) {
			$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
			if ($orderinfo) {
				$newstate = $orderstate + 1;
				if ($newstate >= 4) {
					$newstate = 4;
				}
				$data = array('orderstate' => $newstate, 'paytime' => date("Y-m-d H:i:s"));
				pdo_update($this->orderstable, $data, array('id' => $orderid));
				$employee = pdo_fetch("SELECT * FROM " . tablename($this->employeetable) . " WHERE openid =:openid and uniacid = :uniacid", array(':openid' => $workopenid, ':uniacid' => $_W ['uniacid']));
				$this->createOrderExpress($orderid, $newstate, $employee ['id'], $employee ['employeename'], $employee ['tel']);
				$this->doMobileMyWorkOrder();
			} else {
				message('订单不存在或已被删除');
			}
		} else {
			message('工作人员入口不正确');
		}
	}

	public function doMobileQueryddh()
	{
		global $_GPC, $_W;
		$workopenid = $_W ['fans'] ['from_user'];
		$ddh = $_GPC['ddh'];
		if (empty($ddh)) {
			message('订单号为空！');
		}
		if (!$workopenid) {
			message('工作人员入口不正确');
		}
		$systeminfo = pdo_fetch("SELECT shopname,dangmf,weixf,zhifb,isygdj FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$orderList = pdo_fetchall("SELECT * FROM " . tablename($this->orderstable) . " WHERE ordercode like '%" . $ddh . "%' and ordertype = 'wash' and uniacid = '" . $_W ['uniacid'] . "' and orderstate in (1,2,3,4) and workopenid = '" . $workopenid . "' order by id desc");
		include $this->template('myworkorder');
	}

	public function doMobileQdordercost()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['id']);
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			$workopenid = $_GPC ['workopenid'];
		}
		if (!$workopenid) {
			message('工作人员入口不正确' . $orderid);
		}
		$ordercost = floatval($_GPC ['ordercost']);
		if ($ordercost <= 0) {
			message('订单价格不正确');
		}
		if ($orderid) {
			$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
			if ($orderinfo) {
				$data = array('ordercost' => $ordercost, 'paystate' => 0,);
				pdo_update($this->orderstable, $data, array('id' => $orderid));
				$systeminfo = pdo_fetch("SELECT mbxx,utopayordermid,iswww,fuwuname,kefutel FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
				if ($systeminfo['mbxx'] == 2) {
					if (!empty($systeminfo['utopayordermid'])) {
						$yhorderurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('topayorder', array('id' => $orderid));
						$dataxx = array('touser' => $orderinfo['openid'], 'template_id' => $systeminfo['utopayordermid'], 'url' => $yhorderurl, 'topcolor' => '#FF0000',);
						if ($systeminfo['iswww'] == "IT") {
							$dataxx['data'] = array('first' => array('value' => '您好，你有如下商品需要付款。', 'color' => '#173177',), 'keyword1' => array('value' => $orderinfo['ordercode'], 'color' => '#173177',), 'keyword2' => array('value' => $systeminfo['fuwuname'], 'color' => '#173177',), 'keyword3' => array('value' => $ordercost . '元', 'color' => '#173177',), 'keyword4' => array('value' => '待付款', 'color' => '#173177',), 'remark' => array('value' => '请及时付款，如有问题，欢迎拨打客服电话：' . $systeminfo['kefutel'], 'color' => '#173177',),);
						} else {
							$dataxx['data'] = array('first' => array('value' => '您好，你有如下商品需要付款。', 'color' => '#173177',), 'keyword1' => array('value' => $systeminfo['fuwuname'], 'color' => '#173177',), 'keyword2' => array('value' => $ordercost . '元', 'color' => '#173177',), 'remark' => array('value' => '请及时付款，如有问题，欢迎拨打客服电话：' . $systeminfo['kefutel'], 'color' => '#173177',),);
						}
						$token = $this->getToken();
						$this->sendMBXX($token, $dataxx);
					}
				}
				$this->doMobileMyWorkOrder();
			} else {
				message('订单不存在或已被删除');
			}
		} else {
			message('工作人员入口不正确');
		}
	}

	public function doMobileQRSHOrder()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['id']);
		$openid = $_W ['fans'] ['from_user'];
		if ($orderid && $openid) {
			$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
			if ($orderinfo) {
				$data = array('orderstate' => 5, 'paytime' => date("Y-m-d H:i:s"));
				pdo_update($this->orderstable, $data, array('id' => $orderid));
				$this->createOrderExpress($orderid, 5, '', '', '');
				$this->doMobileOrderend();
			} else {
				message('订单不存在或已被删除');
			}
		} else {
			message('入口不正确');
		}
	}

	public function doMobileWorkOrderCancel()
	{
		global $_GPC, $_W;
		$id = intval($_GPC ['id']);
		pdo_delete($this->exprestable, array('orderid' => $id));
		pdo_delete($this->orderstable, array('id' => $id));
		$this->doMobileMyWorkOrder();
	}

	public function doMobileOrderDangmf()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['id']);
		$workopenid = $_W ['fans'] ['from_user'];
		if (!$workopenid) {
			$workopenid = $_GPC ['workopenid'];
		}
		if (!$workopenid) {
			message('工作人员入口不正确' . $orderid);
		}
		$ordercost = floatval($_GPC ['ordercost']);
		if (!$ordercost || $ordercost < 0) {
			message('付款金额不正确');
		}
		if ($orderid) {
			$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
			if ($orderinfo) {
				$data = array('orderstate' => $this->orderstate2, 'paystate' => $this->hasPay, 'paytype' => $this->paytype1, 'ordercost' => $ordercost);
				$member = pdo_fetch("SELECT * FROM " . tablename($this->membertable) . " WHERE openid = :openid and uniacid = :uniacid", array(':openid' => $orderinfo ['openid'], ':uniacid' => $_W ['uniacid']));
				if ($member) {
					pdo_update($this->membertable, array('real_cost' => $member ['real_cost'] + $ordercost, 'jifen' => $member ['jifen'] + $ordercost), array('id' => $member ['id']));
					pdo_update($this->orderstable, $data, array('id' => $orderid));
					$this->doMobileMyWorkOrder();
				} else {
					message('找不到客户信息');
				}
			} else {
				message('订单不存在或已被删除');
			}
		} else {
			message('工作人员入口不正确');
		}
	}

	public function doMobileIndex()
	{
		global $_GPC, $_W;
		$openid = $_W ['fans'] ['from_user'];
		if (empty ($openid) || $openid == '') {
			message('请移步公众号内发送关键字再进来吧');
		}
		$systeminfo = pdo_fetch("SELECT shopname,kefutel,indexad,xc1,xc2,xc3,goodsinfourl,smsdx,smsuid,smspwd,imglb1,imglb2,imglb3,imgurl1,imgurl2,imgurl3,pczjs,pfwfw,pxctp1,pxctp2,template FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$needreg = false;
		if ($systeminfo['smsdx'] == 1 && $systeminfo['smsuid'] != '' && $systeminfo['smspwd'] != '') {
			if (empty($_W['member']['uid'])) {
				checkauth();
			}
			$mem = pdo_fetch("SELECT mem.mobile,mem.uid,mapp.follow FROM " . tablename('mc_mapping_fans') . " mapp left join " . tablename('mc_members') . " mem on mapp.uid = mem.uid  WHERE mapp.openid = '" . $openid . "'");
			if (empty($mem['uid'])) {
				message('系统错误，找不到会员表信息');
			}
			$phone = $mem['mobile'];
			$uid = $mem['uid'];
			$_SESSION['uid'] = $uid;
			$mobile = $_SESSION['mobile'];
			if (empty($phone)) {
				$needreg = true;
			}
			if ($mobile) {
				pdo_update('mc_members', array('mobile' => $mobile), array('uid' => $uid));
				$_SESSION['mobile'] = '';
				$needreg = false;
			}
		}
		if ($needreg) {
			include $this->template('reg');
		} else {
			$member = pdo_fetch("SELECT * FROM " . tablename($this->membertable) . " WHERE uniacid = :uniacid and openid = :openid ", array(':uniacid' => $_W ['uniacid'], ':openid' => $openid));
			if (empty ($member)) {
				$userinfo = array('uniacid' => $_W ['uniacid'], 'openid' => $openid, 'createtime' => date("Y-m-d H:i:s"), 'cardnumber' => $this->createCardNum(), 'memberstate' => 1);
				pdo_insert($this->membertable, $userinfo);
			}
			$name = 'index';
			if ($systeminfo['template']) {
				$name = $systeminfo['template'];
			}
			$isjmmb = "1";
			if (strstr($systeminfo['goodsinfourl'], "http")) {
				$isjmmb = "0";
			}
			include $this->template($name);
		}
	}

	public function doMobileFuwufw()
	{
		global $_GPC, $_W;
		$systeminfo = pdo_fetch("SELECT shopname FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$sql = "SELECT * FROM " . tablename($this->citytable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id";
		$citylist = pdo_fetchall($sql);
		include $this->template('fanwei');
	}

	public function doMobileJiamu()
	{
		global $_GPC, $_W;
		$systeminfo = pdo_fetch("SELECT shopname,goodsinfourl FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		include $this->template('jiami',$systeminfo['goodsinfourl']);
	}

	public function doMobileConfirmfee()
	{
		global $_GPC, $_W;
		$isajax = $_GPC ['isajax'];
		if ($isajax == 'true') {
			$isygdj = $_GPC ['isygdj'];
			if ($isygdj && intval($isygdj) == 1) {
				setcookie('isygdj', 1, time() + 3600);
			} else {
				$fee = $_GPC ['allcostfee'];
				if ($fee && floatval($fee) > 0) {
					setcookie('allcostfee', $fee, time() + 3600);
				} else {
					die(json_encode(array('result' => 0, 'error' => '亲，请先选择服务内容哦')));
				}
			}
			$detail = $_GPC ['detail'];
			setcookie('detail', $detail, time() + 3600);
			die(json_encode(array('result' => 1, 'error' => 'right')));
		} else {
			$systeminfo = pdo_fetch("SELECT shopname,kefutel,sltxc,sltts,isygdj FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
			$goodslist = pdo_fetchall("SELECT * FROM " . tablename($this->goodstable) . " WHERE uniacid = :uniacid and isjj = 1 ", array(':uniacid' => $_W ['uniacid']));
			$nojjgoodslist = pdo_fetchall("SELECT * FROM " . tablename($this->goodstable) . " WHERE uniacid = :uniacid and isjj = 0 ", array(':uniacid' => $_W ['uniacid']));
			include $this->template('confirmfee');
		}
	}

	public function doMobileXiadan()
	{
		global $_GPC, $_W;
		if (checksubmit()) {
			$ordercost = $_COOKIE ['allcostfee'];
			$detail = $_COOKIE ['detail'];
			$isygdj = $_COOKIE ['isygdj'];
			if ($isygdj && intval($isygdj) == 1) {
				$ordercost = 0;
			} else {
				if (empty ($ordercost) || $ordercost == '' || floatval($ordercost) < 0) {
					message('抱歉，之前的订单已过期，请重新选择商品再下单', $this->createMobileUrl('index'), 'error');
				}
			}
			$orderdetail = "";
			$tmparray = explode("#", $detail);
			if (is_array($tmparray)) {
				foreach ($tmparray as $item) {
					$every = explode("*", $item);
					if (is_array($every)) {
						$goodsid = $every[0];
						$number = $every[1];
						$goods = $this->getGoodsById($goodsid);
						$orderdetail .= $goods['goodsname'] . "x" . $number . $goods['danwei'] . " ";
					}
				}
			}
			$openid = $_W ['fans'] ['from_user'];
			$addressid = intval($_GPC ['address_id']);
			$theaddress = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE id = :id", array(':id' => $addressid));
			$fuwuriqi = $_GPC ['washing_date'];
			$fuwushijian = $_GPC ['washing_time'];
			$runNum = $this->createNum();
			$data = array('uniacid' => $_W ['uniacid'], 'openid' => $openid, 'ordercode' => $runNum, 'ordertime' => date("Y-m-d H:i:s"), 'fuwuriqi' => $fuwuriqi, 'fuwushijian' => $fuwushijian, 'addressid' => $addressid, 'customername' => $theaddress ['customername'], 'customertel' => $theaddress ['tel'], 'customercity' => $theaddress ['customercity'], 'customerarea' => $theaddress ['customerarea'], 'xiangxdz' => $theaddress ['xiangxdz'], 'ordertype' => $this->ordertype01, 'detail' => $orderdetail, 'ordercost' => floatval($ordercost), 'paystate' => $this->unPay, 'orderstate' => $this->orderstate0);
			pdo_insert($this->orderstable, $data);
			$orderid = pdo_insertid();
			$this->createOrderExpress($orderid, $this->orderstate0, 0, '', '');
			setcookie('allcostfee', '', time());
			setcookie('isygdj', '', time());
			setcookie('detail', '', time());
			$systeminfo = pdo_fetch("SELECT smsdx,mbxx,smsuid,smspwd,unewordermid,wnewordermid,kefutel,iswww FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
			if ($systeminfo['mbxx'] == 2) {
				if (!empty($systeminfo['unewordermid'])) {
					$yhorderurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('ordering', array('openid' => $openid));
					$data = array('touser' => $openid, 'template_id' => $systeminfo['unewordermid'], 'url' => $yhorderurl, 'topcolor' => '#FF0000',);
					if ($systeminfo['iswww'] == "IT") {
						$data['data'] = array('first' => array('value' => '您好，您的订单已经提交成功', 'color' => '#173177',), 'keyword1' => array('value' => $runNum, 'color' => '#173177',), 'keyword2' => array('value' => date('m/d H:i', TIMESTAMP), 'color' => '#173177',), 'keyword3' => array('value' => $ordercost . '元', 'color' => '#173177',), 'remark' => array('value' => '谢谢您的惠顾，如有问题，欢迎拨打客服电话：' . $systeminfo['kefutel'], 'color' => '#173177',),);
					} else {
						$data['data'] = array('first' => array('value' => '您好，您的订单已经提交成功', 'color' => '#173177',), 'keyword1' => array('value' => $runNum, 'color' => '#173177',), 'keyword2' => array('value' => date('m/d H:i', TIMESTAMP), 'color' => '#173177',), 'keyword3' => array('value' => $ordercost . '元', 'color' => '#173177',), 'keyword4' => array('value' => '尚未支付', 'color' => '#173177',), 'remark' => array('value' => '谢谢您的惠顾，如有问题，欢迎拨打客服电话：' . $systeminfo['kefutel'], 'color' => '#173177',),);
					}
					$token = $this->getToken();
					$this->sendMBXX($token, $data);
				}
				if (!empty($systeminfo['wnewordermid'])) {
					$data2 = array('touser' => '', 'template_id' => $systeminfo['wnewordermid'], 'url' => '', 'topcolor' => '#FF0000',);
					if ($systeminfo['iswww'] == "IT") {
						$data2['data'] = array('first' => array('value' => '亲，您有新订单需要处理哦', 'color' => '#173177',), 'keyword1' => array('value' => $runNum, 'color' => '#173177',), 'keyword2' => array('value' => '预约服务', 'color' => '#173177',), 'keyword3' => array('value' => $ordercost . '元', 'color' => '#173177',), 'keyword4' => array('value' => date('m/d H:i', TIMESTAMP), 'color' => '#173177',), 'keyword5' => array('value' => $theaddress ['customername'], 'color' => '#173177',), 'remark' => array('value' => $theaddress ['customercity'] . '-' . $theaddress ['customerarea'] . '-' . $theaddress ['xiangxdz'], 'color' => '#173177',),);
					} else {
						$data2['data'] = array('first' => array('value' => '亲，您有新订单需要处理哦', 'color' => '#173177',), 'keyword1' => array('value' => $runNum, 'color' => '#173177',), 'keyword2' => array('value' => '订单金额：' . $ordercost . '元', 'color' => '#173177',), 'keyword3' => array('value' => $theaddress ['customername'], 'color' => '#173177',), 'keyword4' => array('value' => $theaddress ['tel'], 'color' => '#173177',), 'keyword5' => array('value' => $theaddress ['customercity'] . '-' . $theaddress ['customerarea'] . '-' . $theaddress ['xiangxdz'], 'color' => '#173177',), 'remark' => array('value' => '点击这里查看处理详细订单', 'color' => '#173177',),);
					}
					$area = $this->getAddressByName($theaddress ['customerarea']);
					if (empty($area)) {
						message('请检查服务城市和区域设置');
					}
					$sql = "SELECT openid,areaid FROM " . tablename($this->employeetable) . " WHERE cityid ='" . $area['cityid'] . "' and employeestate = 2 and uniacid = '" . $_W ['uniacid'] . "' order by employeestate desc";
					$workers = pdo_fetchall($sql);
					foreach ($workers as $work) {
						$send = true;
						if (intval($work['areaid']) > 0) {
							if ($work['areaid'] != $area['id']) {
								$send = false;
							}
						}
						if ($send) {
							$data2['touser'] = $work['openid'];
							$data2['url'] = $_W['siteroot'] . 'app/' . $this->createMobileUrl('workorder', array('workopenid' => $work['openid']));;
							$token = $this->getToken();
							$this->sendMBXX($token, $data2);
						}
					}
				}
			}
			if ($systeminfo['smsdx'] == 1) {
				$area = $this->getAddressByName($theaddress ['customerarea']);
				if (empty($area)) {
					message('请检查服务城市和区域设置');
				}
				$sql = "SELECT openid,areaid,tel FROM " . tablename($this->employeetable) . " WHERE cityid ='" . $area['cityid'] . "' and employeestate = 2 and uniacid = '" . $_W ['uniacid'] . "' order by employeestate desc";
				$workers = pdo_fetchall($sql);
				$ii = 0;
				$telstring = '';
				foreach ($workers as $work) {
					$send = true;
					if (intval($work['areaid']) > 0) {
						if ($work['areaid'] != $area['id']) {
							$send = false;
						}
					}
					if ($send) {
						if ($ii == 0) {
							$telstring .= $work['tel'];
						} else {
							$telstring .= ',' . $work['tel'];
						}
						$ii++;
					}
				}
				$content = '新订单：服务时间:' . $fuwuriqi . '-' . $fuwushijian . ',客户:' . $theaddress ['customername'] . ',地址：' . $theaddress ['customercity'] . $theaddress ['customerarea'] . $theaddress ['xiangxdz'] . ',电话：' . $theaddress ['tel'];
				$result = $this->sendMobileMSG($systeminfo['smsuid'], $systeminfo['smspwd'], $telstring, $content);
				$arr = explode("&", $result);
				if (is_array($arr)) {
					$tmp1 = $arr[1];
					$statearr = explode("=", $tmp1);
					if (is_array($statearr)) {
						$state = $statearr[1];
						if ($state != '100') {
							message('短信发送失败，请联系管理员：' . $state);
						}
					} else {
						message('短信发送失败，请联系管理员!');
					}
				} else {
					message('短信发送失败，请联系管理员!!');
				}
			}
			$this->doMobileOrdering();
		} else {
			$openid = $_W ['fans'] ['from_user'];
			$addressList = pdo_fetchall("SELECT * FROM " . tablename($this->addresstable) . " WHERE openid = :openid", array(':openid' => $openid));
			$firstaddress = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE openid = :openid", array(':openid' => $openid));
			$addresscount = count($addressList);
			include $this->template('xiadan');
		}
	}

	public function doMobileNewaddress()
	{
		global $_GPC, $_W;
		$openid = $_W ['fans'] ['from_user'];
		if (checksubmit()) {
			if (!$openid) {
				message('$openid为空！');
			}
			$addressid = intval($_GPC ['addressid']);
			if ($addressid) {
				$address = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE id = " . $addressid);
				if (empty ($address)) {
					message('抱歉，地址信息不存在或是已经被删除！');
				}
			}
			$data = array('customername' => $_GPC ['customername'], 'tel' => $_GPC ['tel'], 'customercity' => $_GPC ['customercity'], 'customerarea' => $_GPC ['customerarea'], 'xiangxdz' => $_GPC ['xiangxdz']);
			if (empty ($addressid)) {
				$data ['uniacid'] = $_W ['uniacid'];
				$data ['openid'] = $openid;
				pdo_insert($this->addresstable, $data);
			} else {
				pdo_update($this->addresstable, $data, array('id' => $addressid));
			}
			if ($_GPC ['from'] == 'U') {
				$this->doMobileUaddress();
			} else {
				$addressList = pdo_fetchall("SELECT * FROM " . tablename($this->addresstable) . " WHERE openid = :openid", array(':openid' => $openid));
				$firstaddress = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE openid = :openid", array(':openid' => $openid));
				$addresscount = count($addressList);
				include $this->template('xiadan');
			}
		} else {
			$sqlCity = "SELECT id,cityname FROM " . tablename($this->citytable) . " where uniacid = '" . $_W ['uniacid'] . "'  order by id asc ";
			$firstCity = pdo_fetch($sqlCity);
			if (!$firstCity) {
				echo "设置服务城市和城市中区域";
				exit ();
			}
			$addressid = intval($_GPC ['addressid']);
			if ($addressid) {
				$address = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE id = " . $addressid);
				if (empty ($address)) {
					message('抱歉，地址信息不存在或是已经被删除！');
				}
			} else {
				$member = pdo_fetch("SELECT mem.mobile,mem.realname,mem.address FROM " . tablename('mc_mapping_fans') . " mapp left join " . tablename('mc_members') . " mem on mapp.uid = mem.uid  WHERE mapp.openid = '" . $openid . "'");
				$address['customername'] = $member['realname'];
				$address['tel'] = $member['mobile'];
				$address['xiangxdz'] = $member['address'];
			}
			$sqlArea = "SELECT id,areaname FROM " . tablename($this->areatable) . " where uniacid = '" . $_W ['uniacid'] . "' and  cityid = '" . $firstCity ['id'] . "' order by id asc ";
			$firstArea = pdo_fetch($sqlArea);
			$systeminfo = pdo_fetch("SELECT shopname,cityunicode,areaunicode FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
			$cityStr = $systeminfo ['cityunicode'];
			if (!strstr($cityStr, '\\u')) {
				$cityStr = str_replace('u', '\u', $cityStr);
			}
			$areaStr = $systeminfo ['areaunicode'];
			if (!strstr($areaStr, '\\u')) {
				$areaStr = str_replace('u', '\u', $areaStr);
			}
			include $this->template('newaddress');
		}
	}

	public function doMobileDeleteAddressAjax()
	{
		global $_W, $_GPC;
		$addressid = intval($_GPC ['addressid']);
		if (!$addressid) {
			die(json_encode(array("code" => 1)));
		}
		$address = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE id = " . $addressid);
		if (empty ($address)) {
			die(json_encode(array("code" => 1)));
		}
		pdo_delete($this->addresstable, array('id' => $addressid));
		die(json_encode(array('code' => 2)));
	}

	public function doMobileDeleteAddress()
	{
		global $_W, $_GPC;
		$addressid = intval($_GPC ['addressid']);
		if (!$addressid) {
			message('缺少参数：地址ID');
		}
		$address = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE id = " . $addressid);
		if (empty ($address)) {
			message('抱歉，地址信息不存在或是已经被删除！');
		}
		pdo_delete($this->addresstable, array('id' => $addressid));
		message('删除成功！', referer(), 'success');
	}

	public function doMobileOrdering()
	{
		global $_GPC, $_W;
		$_SESSION['ordertype'] = 'wash';
		$openid = $_W ['fans'] ['from_user'];
		if (!$openid) {
			$openid = $_GPC ['openid'];
		}
		$systeminfo = pdo_fetch("SELECT shopname,dangmf,weixf,zhifb,fuwuname,ddzt0,ddzt1,ddzt2,ddzt3,ddzt4,isygdj FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$orderList = pdo_fetchall("SELECT * FROM " . tablename($this->orderstable) . " WHERE ordertype = 'wash' and orderstate in (0,1,2,3,4) and openid = '" . $openid . "' order by id desc");
		include $this->template('ordering');
	}

	public function doMobileOrderend()
	{
		global $_GPC, $_W;
		$openid = $_W ['fans'] ['from_user'];
		if (!$openid) {
			$openid = $_GPC ['openid'];
		}
		$systeminfo = pdo_fetch("SELECT shopname,fuwuname,ddzt5 FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$orderList = pdo_fetchall("SELECT * FROM " . tablename($this->orderstable) . " WHERE ordertype = 'wash' and orderstate = 5 and openid = :openid order by id desc", array(':openid' => $openid));
		include $this->template('ordered');
	}

	public function doMobileOrderShow()
	{
		global $_GPC, $_W;
		$systeminfo = pdo_fetch("SELECT shopname,fuwuname,ddzt0,ddzt1,ddzt2,ddzt3,ddzt4,ddzt5 FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$orderid = intval($_GPC ['id']);
		$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
		$employee = pdo_fetch("SELECT employeename,tel FROM " . tablename($this->employeetable) . " WHERE employeestate = 2 and openid =:openid and uniacid = :uniacid", array(':openid' => $orderinfo['workopenid'], ':uniacid' => $_W ['uniacid']));
		$expresinfoList = pdo_fetchall("SELECT * FROM " . tablename($this->exprestable) . " WHERE orderid = :orderid order by expresstime desc ", array(':orderid' => $orderid));
		include $this->template('orderdetail');
	}

	public function doMobileOrderCancel()
	{
		global $_GPC, $_W;
		$id = intval($_GPC ['id']);
		if (empty($id)) {
			message('id为空');
		}
		pdo_delete($this->exprestable, array('orderid' => $id));
		pdo_delete($this->orderstable, array('id' => $id));
		$this->doMobileOrdering();
	}

	public function doMobileToPayOrder()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['id']);
		$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
		$systeminfo = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		include $this->template('payorder');
	}

	public function doMobileDoPayOrder()
	{
		global $_GPC, $_W;
		$orderid = intval($_GPC ['orderid']);
		$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
		$openid = $_W ['fans'] ['from_user'];
		if (empty ($openid) || empty ($orderinfo)) {
			message('付款链接错误, 未找到指定订单.', '', 'error');
		}
		if (empty ($openid)) {
			message('空值');
		}
		$paytype = $_GPC ['paytype'];
		if (empty ($paytype)) {
			message('支付类型错误，请联系管理员');
		}
		if ($paytype == 'zaixian') {
			$params ['tid'] = $orderid;
			$params ['user'] = $openid;
			$params ['fee'] = floatval($_GPC ['ordercost']);
			$params ['title'] = $_W ['account'] ['name'];
			$params ['ordersn'] = $_GPC ['ordercode'];
			$params ['virtual'] = false;
			$this->pay($params);
		} else {
			message('您选择了当面现金支付，请等待工作人员联系您即可', $this->createMobileUrl('ordering'), 'success');
		}
	}

	public function payResult($params)
	{
		global $_W;
		$ordertype = $_SESSION['ordertype'];
		if ($ordertype != 'recharge') {
			$fee = floatval($params ['fee']);
			$paystate = $params['result'] == 'success' ? 1 : 0;
			$orderid = intval($params ['tid']);
			$openid = $params ['user'];
			$orderinfo = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = :id", array(':id' => $orderid));
			if (empty ($orderinfo)) {
				message('订单找不到，请联系管理员');
				return;
			}
			$data = array('ordercost' => $fee, 'paytime' => date("Y-m-d H:i:s"), 'paystate' => $paystate,);
			$payby = '';
			if ($params ['type'] == 'wechat') {
				$data ['transid'] = $params ['tag'] ['transaction_id'];
				$data ['paytype'] = $this->paytype2;
				$payby = '微信支付';
			} elseif ($params ['type'] == 'alipay') {
				$data ['paytype'] = $this->paytype3;
				$payby = '支付宝';
			} elseif ($params ['type'] == 'credit') {
				$data ['paytype'] = $this->paytype4;
				$payby = '余额';
				$memsql = "SELECT uid FROM " . tablename(mc_mapping_fans) . " WHERE openid = '$openid'";
				$mapping_fans = pdo_fetch($memsql);
				if ($paystate == 1) {
				}
			}
			pdo_update($this->orderstable, $data, array('id' => $orderid,));
			$systeminfo = pdo_fetch("SELECT mbxx,upayordermid,iswww FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
			if ($systeminfo['mbxx'] == 2 && !empty($systeminfo['upayordermid'])) {
				$yhorderurl = $this->createMobileUrl('ordering', array('openid' => $openid));
				$dataxx = array('touser' => $openid, 'template_id' => $systeminfo['upayordermid'], 'url' => $yhorderurl, 'topcolor' => '#FF0000',);
				if ($systeminfo['iswww'] == "IT") {
					$dataxx['data'] = array('first' => array('value' => '您好，您的订单已付款成功', 'color' => '#173177',), 'orderMoneySum' => array('value' => $fee . '元', 'color' => '#173177',), 'orderProductName' => array('value' => $orderinfo['ordercode'], 'color' => '#173177',), 'remark' => array('value' => '谢谢您的惠顾', 'color' => '#173177',),);
				} else {
					$dataxx['data'] = array('first' => array('value' => '您好，您的订单已付款成功', 'color' => '#173177',), 'keyword1' => array('value' => $orderinfo['ordercode'], 'color' => '#173177',), 'keyword2' => array('value' => $fee . '元', 'color' => '#173177',), 'remark' => array('value' => '谢谢您的惠顾', 'color' => '#173177',),);
				}
				$token = $this->getToken();
				$this->sendMBXX($token, $dataxx);
			}
			$_SESSION['ordertype'] = '';
			if ($params ['from'] == 'return') {
				message('支付成功！!', '../../' . $this->createMobileUrl('ordering'), 'success');
			}
		} else {
			load()->model('mc');
			$status = pdo_fetchcolumn("SELECT status FROM " . tablename('mc_credits_recharge') . " WHERE tid = :tid", array(':tid' => $params['tid']));
			if (empty($status)) {
				$fee = $params['fee'];
				$data = array('status' => $params['result'] == 'success' ? 1 : -1);
				if ($params['type'] == 'wechat') {
					$data['transid'] = $params['tag']['transaction_id'];
					$params['user'] = mc_openid2uid($params['user']);
				}
				pdo_update('mc_credits_recharge', $data, array('tid' => $params['tid']));
				if ($params['result'] == 'success' && $params['from'] == 'return') {
					$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
					$credit = $setting['creditbehaviors']['currency'];
					if (empty($credit)) {
						message('站点积分行为参数配置错误,请联系服务商', '', 'error');
					} else {
						$systeminfo = pdo_fetch("SELECT recharge,shopname FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
						$tmparray = explode("#", $systeminfo['recharge']);
						$charge1 = 0;
						$charge1_send = 0;
						$charge2 = 0;
						$charge2_send = 0;
						$charge3 = 0;
						$charge3_send = 0;
						if (is_array($tmparray)) {
							$first = $tmparray[0];
							$second = $tmparray[1];
							$third = $tmparray[2];
							$charge1arr = explode("-", $first);
							if (is_array($charge1arr)) {
								$charge1 = $charge1arr[0];
								$charge1_send = $charge1arr[1];
							}
							$charge2arr = explode("-", $second);
							if (is_array($charge2arr)) {
								$charge2 = $charge2arr[0];
								$charge2_send = $charge2arr[1];
							}
							$charge3arr = explode("-", $third);
							if (is_array($charge3arr)) {
								$charge3 = $charge3arr[0];
								$charge3_send = $charge3arr[1];
							}
						}
						$send = 0;
						if (floatval($fee) >= floatval($charge1)) {
							$send = floatval($charge1_send);
						}
						if (floatval($fee) >= floatval($charge2)) {
							$send = floatval($charge2_send);
						}
						if (floatval($fee) >= floatval($charge3)) {
							$send = floatval($charge3_send);
						}
						$paydata = array('wechat' => '微信', 'alipay' => '支付宝');
						$record[] = $params['user'];
						$record[] = '用户通过' . $paydata[$params['type']] . '充值' . $fee . '赠送' . $send;
						$fee = $send + floatval($fee);
						mc_credit_update($params['user'], $credit, $fee, $record);
					}
				}
			} else {
				message('**' . mc_openid2uid($params['user']));
			}
			$_SESSION['ordertype'] = '';
			if ($params['from'] == 'return') {
				if ($params['result'] == 'success') {
					message('支付成功！', '../../' . $this->createMobileUrl('usercenter'), 'success');
				} else {
					message('支付失败！', '../../' . $this->createMobileUrl('usercenter'), 'error');
				}
			}
		}
	}

	public function doMobileUsercenter()
	{
		global $_GPC, $_W;
		$systeminfo = pdo_fetch("SELECT shopname,cardlogo,kefutel,kefuwx,yjfkurl FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$openid = $_W ['fans'] ['from_user'];
		if (empty ($openid)) {
			message("入口不正确");
		}
		$userinfo = pdo_fetch("SELECT cardnumber FROM " . tablename($this->membertable) . " WHERE uniacid = :uniacid and openid = :openid", array(':uniacid' => $_W ['uniacid'], ':openid' => $openid));
		$member = pdo_fetch("SELECT mem.credit1,mem.credit2,mem.mobile FROM " . tablename('mc_mapping_fans') . " mapp left join " . tablename('mc_members') . " mem on mapp.uid = mem.uid  WHERE mapp.openid = '" . $openid . "'");
		$jifen = 0.00;
		$yue = 0.00;
		if ($member['credit1']) {
			$jifen = $member['credit1'];
		}
		if ($member['credit2']) {
			$yue = $member['credit2'];
		}
		include $this->template('usercenter');
	}

	public function doMobileBangdstk()
	{
		global $_GPC, $_W;
		$systeminfo = pdo_fetch("SELECT shopname FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		include $this->template('shitika');
	}

	public function doMobileUaddress()
	{
		global $_GPC, $_W;
		$openid = $_W ['fans'] ['from_user'];
		if (empty ($openid)) {
			message("入口不正确");
		}
		if ($_GPC ['op'] == "delete") {
			$addressid = intval($_GPC ['addressid']);
			if ($addressid) {
				$address = pdo_fetch("SELECT * FROM " . tablename($this->addresstable) . " WHERE id = " . $addressid);
				if (empty ($address)) {
					message('抱歉，地址信息不存在或是已经被删除！');
				} else {
					pdo_delete($this->addresstable, array('id' => $addressid));
				}
			}
		}
		$systeminfo = pdo_fetch("SELECT shopname FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$addressList = pdo_fetchall("SELECT * FROM " . tablename($this->addresstable) . " WHERE uniacid = :uniacid and openid = :openid", array(':uniacid' => $_W ['uniacid'], ':openid' => $openid));
		include $this->template('userdizhis');
	}

	public function doMobileShowmore()
	{
		global $_GPC, $_W;
		$systeminfo = pdo_fetch("SELECT shopname,kefutel,kefuwx FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		include $this->template('gengduo');
	}

	public function doMobileChangjwt()
	{
		global $_GPC, $_W;
		$systeminfo = $this->getShopInfo();
		include $this->template('changjwt');
	}

	public function doMobileXieyi()
	{
		global $_GPC, $_W;
		$systeminfo = $this->getShopInfo();
		include $this->template('xieyi');
	}

	public function dowebEmployee()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$state = $_GPC ['state'];
		if (!$state) {
			$state = '2';
		}
		$sql = "SELECT * FROM " . tablename($this->employeetable) . " WHERE employeestate = '" . $state . "'  and   uniacid = '" . $_W ['uniacid'] . "' order by id desc";
		$sql2 = 'SELECT COUNT(*) FROM ' . tablename($this->employeetable) . " WHERE employeestate = '" . $state . "'  and uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC";
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn($sql2);
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('employee');
	}

	public function doWebShowEmployee()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$sql1 = 'SELECT id as id,cityname as name FROM ' . tablename($this->citytable) . ' WHERE `uniacid` = :uniacid';
		$citygory = pdo_fetchall($sql1, array(':uniacid' => $_W['uniacid']), 'id');
		if (!empty($citygory)) {
			$parent = array();
			foreach ($citygory as $cid => $cate) {
				$parent[$cate['id']] = $cate;
			}
		}
		$sql2 = 'SELECT id as id,cityid as parentid,areaname as name FROM ' . tablename($this->areatable) . ' WHERE `uniacid` = :uniacid';
		$areagory = pdo_fetchall($sql2, array(':uniacid' => $_W['uniacid']), 'id');
		if (!empty($areagory)) {
			$children = array();
			foreach ($areagory as $cid => $cate) {
				$children[$cate['parentid']][] = $cate;
			}
		}
		if ($_GPC ['op'] == 'delete') {
			$employeeid = intval($_GPC ['employeeid']);
			$employee = pdo_fetch("SELECT id FROM " . tablename($this->employeetable) . " WHERE id = " . $employeeid);
			if (empty ($employee)) {
				message('抱歉，员工信息不存在或是已经被删除！');
			}
			pdo_delete($this->employeetable, array('id' => $employeeid));
			message('删除成功！', referer(), 'success');
		}
		if ($_GPC ['op'] == 'update') {
			$employeeid = intval($_GPC ['employeeid']);
			$employee = pdo_fetch("SELECT id FROM " . tablename($this->employeetable) . " WHERE id = " . $employeeid);
			if (empty ($employee)) {
				message('抱歉，员工信息不存在或是已经被删除！');
			}
			pdo_update($this->employeetable, array('employeestate' => 2), array('id' => $employeeid));
			message('审核成功！', referer(), 'success');
		}
		$employeeid = intval($_GPC ['employeeid']);
		if ($employeeid) {
			$employee = pdo_fetch("SELECT * FROM " . tablename($this->employeetable) . " WHERE id= " . $employeeid);
			if (empty ($employee)) {
				message('抱歉，员工信息不存在或是已经被删除！');
			}
		} else {
			message('员工入口不正确');
		}
		if (checksubmit()) {
			$employee['cityid'] = $_GPC['fuzqy']['parentid'];
			$employee['areaid'] = $_GPC['fuzqy']['childid'];
			$cityname = $this->getCityNameById($employee['cityid']);
			$areaname = '0';
			if ($employee['areaid'] != '0') {
				$areaname = $this->getAreaNameById($employee['areaid']);
			}
			$data = array('employeename' => $_GPC ['employeename'], 'tel' => $_GPC ['tel'], 'sumorders' => $_GPC ['sumorders'], 'cityid' => $_GPC['fuzqy']['parentid'], 'areaid' => $_GPC['fuzqy']['childid'], 'city' => $cityname, 'area' => $areaname,);
			pdo_update($this->employeetable, $data, array('id' => $employeeid));
			message('更新成功！', referer(), 'success');
		}
		include $this->template('showemployee');
	}

	public function dowebMember()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$state = $_GPC ['state'];
		if (!$state) {
			$state = '1';
		}
		$sql = "SELECT * FROM " . tablename($this->membertable) . " WHERE  memberstate = '" . $state . "'  and uniacid = '" . $_W ['uniacid'] . "' order by id desc";
		$sql2 = 'SELECT COUNT(*) FROM ' . tablename($this->membertable) . " WHERE  memberstate = '" . $state . "' and uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC";
		if ($_GPC ['cardnumber']) {
			$sql = "SELECT * FROM " . tablename($this->membertable) . " WHERE cardnumber like '%" . $_GPC ['cardnumber'] . "%'  and memberstate = '" . $state . "'  and uniacid = '" . $_W ['uniacid'] . "' order by id desc";
			$sql2 = 'SELECT COUNT(*) FROM ' . tablename($this->membertable) . " WHERE cardnumber like '%" . $_GPC ['cardnumber'] . "%' and  memberstate = '" . $state . "' and uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC";
		}
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn($sql2);
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('member');
	}

	public function doWebShowMember()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		if ($_GPC ['op'] == 'delete') {
			$memberid = intval($_GPC ['memberid']);
			$member = pdo_fetch("SELECT id FROM " . tablename($this->membertable) . " WHERE id = " . $memberid);
			if (empty ($member)) {
				message('抱歉，会员信息不存在或是已经被删除！');
			}
			pdo_delete($this->orderstable, array('openid' => $member ['openid']));
			pdo_delete($this->membertable, array('id' => $memberid));
			message('删除成功！', referer(), 'success');
		}
		if ($_GPC ['op'] == 'update') {
			$memberid = intval($_GPC ['memberid']);
			$member = pdo_fetch("SELECT * FROM " . tablename($this->membertable) . " WHERE id = " . $memberid);
			if (empty ($member)) {
				message('抱歉，会员信息不存在或是已经被删除！');
			}
			if ($member ['memberstate'] != 2) {
				pdo_update($this->membertable, array('memberstate' => 2), array('id' => $memberid));
			} else {
				pdo_update($this->membertable, array('memberstate' => 1), array('id' => $memberid));
			}
			message('会员状态修改成功！', referer(), 'success');
		}
		$memberid = intval($_GPC ['memberid']);
		if ($memberid) {
			$member = pdo_fetch("SELECT * FROM " . tablename($this->membertable) . " WHERE id= " . $memberid);
			if (empty ($member)) {
				message('抱歉，会员信息不存在或是已经被删除！');
			}
		} else {
			message('会员入口不正确');
		}
		if (checksubmit()) {
			$data = array('membername' => $_GPC ['membername'], 'tel' => $_GPC ['tel'], 'real_cost' => floatval($_GPC ['real_cost']), 'balance' => floatval($_GPC ['balance']));
			pdo_update($this->membertable, $data, array('id' => $memberid));
			message('更新成功！', referer(), 'success');
		}
		include $this->template('showmember');
	}

	public function dowebOrder()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$state = $_GPC ['state'];
		if (!$state) {
			$state = '0';
		}
		$search = "and 1=1 ";
		if ($_GPC ['ordercode']) {
			$search = $search . " and ordercode like '%" . $_GPC ['ordercode'] . "%' ";
		}
		$sql = "SELECT * FROM " . tablename($this->orderstable) . " WHERE ordertype = 'wash' " . $search . " and orderstate = '" . $state . "'  and uniacid = '" . $_W ['uniacid'] . "' order by fuwuriqi desc,fuwushijian desc LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->orderstable) . " WHERE ordertype = 'wash' " . $search . " and orderstate = '" . $state . "'  and  uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC");
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('order');
	}

	public function doWebShowOrder()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		if ($_GPC ['op'] == 'delete') {
			$orderid = intval($_GPC ['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id = " . $orderid);
			if (empty ($order)) {
				message('抱歉，订单不存在或是已经被删除！');
			}
			pdo_delete($this->orderstable, array('id' => $orderid));
			pdo_delete($this->exprestable, array('orderid' => $orderid));
			message('删除成功！', referer(), 'success');
		}
		$orderid = intval($_GPC ['orderid']);
		if ($orderid) {
			$order = pdo_fetch("SELECT * FROM " . tablename($this->orderstable) . " WHERE id= " . $orderid);
		}
		if (checksubmit()) {
			$data = array('customername' => $_GPC ['customername'], 'customertel' => $_GPC ['customertel'], 'xiangxdz' => $_GPC ['xiangxdz'], 'detail' => $_GPC ['detail'], 'ordercost' => floatval($_GPC ['ordercost']));
			if (intval($_GPC ['prostate']) == 1 && intval($_GPC ['nextstate']) == 1) {
				message('订单状态置换错误');
			}
			if (intval($_GPC ['prostate']) == 1) {
				$data ['orderstate'] = $order ['orderstate'] - 1;
				if ($data ['orderstate'] <= 0) {
					$data ['orderstate'] = 0;
				}
			}
			if (intval($_GPC ['nextstate']) == 1) {
				$data ['orderstate'] = $order ['orderstate'] + 1;
				if ($data ['orderstate'] >= 5) {
					$data ['orderstate'] = 5;
				}
			}
			if (intval($_GPC ['paystate']) == 1) {
				if (floatval($_GPC ['ordercost']) <= 0) {
					message('付款金额为0！');
				}
				$data ['paystate'] = $this->hasPay;
				$data ['paytype'] = $this->paytype1;
			} else {
				$data ['paystate'] = $this->unPay;
				$data ['paytype'] = 0;
			}
			pdo_update($this->orderstable, $data, array('id' => $orderid));
			message('更新成功！', referer(), 'success');
		}
		include $this->template('showorder');
	}

	public function dowebGoods()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$sql = "SELECT * FROM " . tablename($this->goodstable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->goodstable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC");
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('goods');
	}

	public function doWebAddGoods()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$goodsid = intval($_GPC ['goodsid']);
		if ($goodsid) {
			$goods = pdo_fetch("SELECT * FROM " . tablename($this->goodstable) . " WHERE id= " . $goodsid);
		}
		if ($_GPC ['op'] == 'delete') {
			$goodsid = intval($_GPC ['goodsid']);
			$goods = pdo_fetch("SELECT id FROM " . tablename($this->goodstable) . " WHERE id = " . $goodsid);
			if (empty ($goods)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
			pdo_delete($this->goodstable, array('id' => $goodsid));
			message('删除成功！', referer(), 'success');
		}
		if (checksubmit()) {
			$data = array('goodsname' => $_GPC ['goodsname'], 'goodsinfo' => htmlspecialchars_decode($_GPC ['goodsinfo']), 'goodsphoto' => $_GPC ['goodsphoto'], 'danwei' => $_GPC ['danwei'], 'isjj' => intval($_GPC ['isjj']), 'goodsprice' => floatval($_GPC ['goodsprice']));
			if (!empty ($goodsid)) {
				pdo_update($this->goodstable, $data, array('id' => $goodsid));
			} else {
				$data ['uniacid'] = $_W ['uniacid'];
				pdo_insert($this->goodstable, $data);
				$goodsid = pdo_insertid();
			}
			message('更新成功！', referer(), 'success');
		}
		include $this->template('addgoods');
	}

	public function dowebCity()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$sql = "SELECT * FROM " . tablename($this->citytable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->citytable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC");
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('city');
	}

	public function doWebAddCity()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$cityid = intval($_GPC ['cityid']);
		if ($cityid) {
			$city = pdo_fetch("SELECT * FROM " . tablename($this->citytable) . " WHERE id= " . $cityid);
		}
		if ($_GPC ['op'] == 'delete') {
			$cityid = intval($_GPC ['cityid']);
			$city = pdo_fetch("SELECT id FROM " . tablename($this->citytable) . " WHERE id = " . $cityid);
			if (empty ($city)) {
				message('抱歉，城市不存在或是已经被删除！');
			}
			pdo_delete($this->citytable, array('id' => $cityid));
			pdo_delete($this->areatable, array('cityid' => $cityid));
			$this->refererCityUnicode();
			message('删除成功！', referer(), 'success');
		}
		if (checksubmit()) {
			$data = array('cityname' => $_GPC ['cityname'], 'cityinfo' => htmlspecialchars_decode($_GPC ['cityinfo']), 'cityphoto' => $_GPC ['cityphoto'], 'cityunicode' => json_encode($_GPC ['cityname']));
			$isNewcity = false;
			if (!empty ($cityid)) {
				pdo_update($this->citytable, $data, array('id' => $cityid));
			} else {
				$data ['uniacid'] = $_W ['uniacid'];
				pdo_insert($this->citytable, $data);
				$cityid = pdo_insertid();
				$isNewcity = true;
			}
			$this->refererCityUnicode();
			if ($isNewcity) {
				message('新增城市成功！切记要添加该城市下的区域', referer(), 'success');
			} else {
				message('更新成功！', referer(), 'success');
			}
		}
		include $this->template('addcity');
	}

	public function dowebArea()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$cityid = intval($_GPC ['cityid']);
		if (!$cityid) {
			message('入口不正确！');
		} else {
			$city = pdo_fetch("SELECT * FROM " . tablename($this->citytable) . " WHERE id= " . $cityid);
			if (!$city) {
				message('抱歉，城市不存在或是已经删除！');
			}
		}
		$sql = "SELECT * FROM " . tablename($this->areatable) . " WHERE cityid ='" . $cityid . "' and uniacid = '" . $_W ['uniacid'] . "' ORDER BY id LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->areatable) . " WHERE cityid ='" . $cityid . "' and uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC");
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('area');
	}

	public function doWebAddArea()
	{
		global $_W, $_GPC;
		$cityid = intval($_GPC ['cityid']);
		if (!$cityid) {
			message('入口不正确！');
		} else {
			$city = pdo_fetch("SELECT * FROM " . tablename($this->citytable) . " WHERE id= " . $cityid);
			if (!$city) {
				message('抱歉，城市不存在或是已经删除！');
			}
		}
		load()->func('tpl');
		$areaid = intval($_GPC ['areaid']);
		if ($areaid) {
			$area = pdo_fetch("SELECT * FROM " . tablename($this->areatable) . " WHERE id= " . $areaid);
		}
		if ($_GPC ['op'] == 'delete') {
			$areaid = intval($_GPC ['areaid']);
			$area = pdo_fetch("SELECT id FROM " . tablename($this->areatable) . " WHERE id = " . $areaid);
			if (empty ($city)) {
				message('抱歉，城市不存在或是已经被删除！');
			}
			pdo_delete($this->areatable, array('id' => $areaid));
			$this->refererAreaUnicode();
			message('删除成功！', referer(), 'success');
		}
		if (checksubmit()) {
			$data = array('areaname' => $_GPC ['areaname'], 'areaunicode' => json_encode($_GPC ['areaname']));
			if (!empty ($areaid)) {
				pdo_update($this->areatable, $data, array('id' => $areaid));
			} else {
				$data ['uniacid'] = $_W ['uniacid'];
				$data ['cityid'] = $cityid;
				pdo_insert($this->areatable, $data);
				$areaid = pdo_insertid();
			}
			$this->refererAreaUnicode();
			message('更新成功！', referer(), 'success');
		}
		include $this->template('addarea');
	}

	public function dowebTheme()
	{
		global $_W, $_GPC;
		$pageNumber = max(1, intval($_GPC ['page']));
		$pageSize = 10;
		$sql = "SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->shoptable) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC");
		$pager = pagination($total, $pageNumber, $pageSize);
		include $this->template('shop');
	}

	public function dowebBaobiao()
	{
		global $_W, $_GPC;
		$todayorder = pdo_fetch("SELECT count(*) as cnt  FROM " . tablename($this->orderstable) . " WHERE to_days( `ordertime` ) = to_days(now( ))  and uniacid = '{$_W['uniacid']}'");
		$toadyAddOrder = $todayorder ['cnt'] <= 0 ? 0 : $todayorder ['cnt'];
		$allorder = pdo_fetch("SELECT count(*) as cnt  FROM " . tablename($this->orderstable) . " WHERE  uniacid = '{$_W['uniacid']}'");
		$allorderSum = $allorder ['cnt'] <= 0 ? 0 : $allorder ['cnt'];
		$todaymember = pdo_fetch("SELECT count(*) as cnt  FROM " . tablename($this->membertable) . " WHERE to_days( `createtime` ) = to_days(now( ))  and uniacid = '{$_W['uniacid']}'");
		$toadyAddMember = $todaymember ['cnt'] <= 0 ? 0 : $todaymember ['cnt'];
		$allmember = pdo_fetch("SELECT count(*) as cnt  FROM " . tablename($this->membertable) . " WHERE  uniacid = '{$_W['uniacid']}'");
		$memberSum = $allmember ['cnt'] <= 0 ? 0 : $allmember ['cnt'];
		$todayPay = pdo_fetch("SELECT sum(ordercost) as cnt  FROM " . tablename($this->orderstable) . " WHERE to_days( `paytime` ) = to_days(now( )) and paystate = 1  and uniacid = '{$_W['uniacid']}'");
		$toadyAddPay = $todayPay ['cnt'] <= 0 ? 0 : $todayPay ['cnt'];
		$allPay = pdo_fetch("SELECT sum(ordercost) as cnt  FROM " . tablename($this->orderstable) . " WHERE  uniacid = '{$_W['uniacid']}' and paystate = 1 ");
		$allPaySum = $allPay ['cnt'] <= 0 ? 0 : $allPay ['cnt'];
		include $this->template('baobiao');
	}

	public function dowebAddMessage()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$message = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		if (empty($message)) {
			message('请先点击商家信息完善信息！');
		}
		if (checksubmit()) {
			$data = array('unewordermid' => $_GPC ['unewordermid'], 'upayordermid' => $_GPC ['upayordermid'], 'utopayordermid' => $_GPC ['utopayordermid'], 'wnewordermid' => $_GPC ['wnewordermid'], 'smsuid' => $_GPC ['smsuid'], 'smspwd' => $_GPC ['smspwd'], 'smsyzmb' => $_GPC ['smsyzmb'], 'iswww' => $_GPC ['iswww'],);
			pdo_update($this->shoptable, $data, array('id' => $message['id']));
			$message = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		}
		include $this->template('addmessage');
	}

	public function dowebSetOrderState()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$shop = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		if (empty($shop)) {
			message('请先点击商家设置完善店铺信息！');
		}
		if (checksubmit()) {
			$data = array('ddzt0' => $_GPC ['ddzt0'], 'ddzt1' => $_GPC ['ddzt1'], 'ddzt2' => $_GPC ['ddzt2'], 'ddzt3' => $_GPC ['ddzt3'], 'ddzt4' => $_GPC ['ddzt4'], 'ddzt5' => $_GPC ['ddzt5'],);
			pdo_update($this->shoptable, $data, array('id' => $shop['id']));
			$shop = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		}
		include $this->template('setOrderState');
	}

	public function dowebSystemOptions()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$shop = pdo_fetch("SELECT id,needaudit,isygdj,dangmf,weixf,zhifb,smsdx,mbxx FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		if (empty($shop)) {
			message('请先点击商家设置完善店铺信息！');
		}
		if (checksubmit()) {
			$data = array('needaudit' => $_GPC ['needaudit'], 'isygdj' => intval($_GPC ['isygdj']), 'dangmf' => $_GPC ['paytype'] [0], 'weixf' => $_GPC ['paytype'] [1], 'zhifb' => $_GPC ['paytype'] [2], 'smsdx' => $_GPC ['tztype'] [0], 'mbxx' => $_GPC ['tztype'] [1],);
			pdo_update($this->shoptable, $data, array('id' => $shop['id']));
			$shop = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		}
		include $this->template('systemOptions');
	}

	public function dowebFengge()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$shop = pdo_fetch("SELECT id,indexad,xc1,xc2,xc3,imglb1,imglb2,imglb3,imgurl1,imgurl2,imgurl3,pfwfw,pczjs,pxctp1,pxctp2,template,goodsinfourl FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		if (empty($shop)) {
			message('请先点击商家设置完善店铺信息！');
		}
		if (checksubmit()) {
			$data = array('indexad' => $_GPC ['indexad'], 'xc1' => $_GPC ['xc1'], 'xc2' => $_GPC ['xc2'], 'xc3' => $_GPC ['xc3'], 'imglb1' => $_GPC ['imglb1'], 'imglb2' => $_GPC ['imglb2'], 'imglb3' => $_GPC ['imglb3'], 'imgurl1' => $_GPC ['imgurl1'], 'imgurl2' => $_GPC ['imgurl2'], 'imgurl3' => $_GPC ['imgurl3'], 'template' => $_GPC ['template'], 'goodsinfourl' => $_GPC ['goodsinfourl'], 'pfwfw' => $_GPC ['pfwfw'], 'pczjs' => $_GPC ['pczjs'], 'pxctp1' => $_GPC ['pxctp1'], 'pxctp2' => $_GPC ['pxctp2'],);
			pdo_update($this->shoptable, $data, array('id' => $shop['id']));
			$shop = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		}
		include $this->template('fengge');
	}

	public function dowebAddShop()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$shopid = intval($_GPC ['shopid']);
		if ($shopid) {
			$shop = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE id= " . $shopid);
		} else {
			$shop = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid= " . $_W ['uniacid']);
		}
		if ($_GPC ['op'] == 'delete') {
			$shopid = intval($_GPC ['shopid']);
			$shop = pdo_fetch("SELECT id FROM " . tablename($this->shoptable) . " WHERE id = " . $shopid);
			if (empty ($shop)) {
				message('抱歉，店铺不存在或是已经被删除！');
			}
			pdo_delete($this->shoptable, array('id' => $shopid));
			message('删除成功！', referer(), 'success');
		}
		if (checksubmit()) {
			$data = array('shopname' => $_GPC ['shopname'], 'sltxc' => $_GPC ['sltxc'], 'sltts' => $_GPC ['sltts'], 'kefutel' => $_GPC ['kefutel'], 'fuwuname' => $_GPC ['fuwuname'], 'cardlogo' => $_GPC ['cardlogo'], 'addemployeepwd' => $_GPC ['addemployeepwd'], 'kefuwx' => $_GPC ['kefuwx'], 'recharge' => $_GPC ['recharge'], 'yjfkurl' => $_GPC ['yjfkurl'],);
			if (!empty ($shopid)) {
				pdo_update($this->shoptable, $data, array('id' => $shopid));
			} else {
				$data ['uniacid'] = $_W ['uniacid'];
				pdo_insert($this->shoptable, $data);
				$themeid = pdo_insertid();
			}
			message('更新成功！', referer(), 'success');
		}
		include $this->template('addshop');
	}

	public function refererCityUnicode()
	{
		global $_W, $_GPC;
		$systeminfo = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		if (!$systeminfo) {
			message('请先进行系统设置完善店面信息！');
		}
		$sqlCity = "SELECT id,cityname,cityunicode FROM " . tablename($this->citytable) . " where uniacid = '" . $_W ['uniacid'] . "'  order by id asc ";
		$cityList = pdo_fetchall($sqlCity);
		$cityStr = '[';
		for ($i = 0; $i < count($cityList); $i++) {
			$city = $cityList [$i];
			$cityStr = $cityStr . $city ['cityunicode'];
			if ($i != count($cityList) - 1) {
				$cityStr = $cityStr . ",";
			} else {
				$cityStr = $cityStr . "]";
			}
		}
		pdo_update($this->shoptable, array('cityunicode' => $cityStr), array('id' => $systeminfo ['id']));
		$this->refererAreaUnicode();
	}

	public function refererAreaUnicode()
	{
		global $_W, $_GPC;
		$systeminfo = pdo_fetch("SELECT * FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		if (!$systeminfo) {
			message('请先进行系统设置完善店面信息！');
		}
		$sqlCity = "SELECT id,cityname,cityunicode FROM " . tablename($this->citytable) . " where uniacid = '" . $_W ['uniacid'] . "'  order by id asc ";
		$cityList = pdo_fetchall($sqlCity);
		$areaStr = '[';
		for ($i = 0; $i < count($cityList); $i++) {
			$city = $cityList [$i];
			$cityid = $city ['id'];
			$sqlArea = "SELECT id,areaname,areaunicode FROM " . tablename($this->areatable) . " where uniacid = '" . $_W ['uniacid'] . "' and  cityid = '" . $cityid . "' order by id asc ";
			$areaList = pdo_fetchall($sqlArea);
			$areaName = '';
			for ($k = 0; $k < count($areaList); $k++) {
				$area = $areaList [$k];
				$areaName = $areaName . $area ['areaunicode'];
				if ($k != count($areaList) - 1) {
					$areaName = $areaName . ",";
				}
			}
			if (empty ($areaName) || '' == $areaName) {
				$areaName = $city ['cityunicode'];
			}
			$areaName = "[" . $areaName . "]";
			$areaStr = $areaStr . $areaName;
			if ($i != count($cityList) - 1) {
				$areaStr = $areaStr . ",";
			} else {
				$areaStr = $areaStr . "]";
			}
		}
		pdo_update($this->shoptable, array('areaunicode' => $areaStr), array('id' => $systeminfo ['id']));
	}

	public function getAreasCount($cityid)
	{
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->areatable) . " WHERE cityid = {$cityid} and uniacid = '{$_W['uniacid']}'");
		return $result ['cnt'] <= 0 ? 0 : $result ['cnt'];
	}

	public function getOrdersCountByCS($cityName, $orderstate)
	{
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->orderstable) . " WHERE orderstate = '" . $orderstate . "' and customercity = '" . $cityName . "' and uniacid = '{$_W['uniacid']}'");
		return $result ['cnt'] <= 0 ? 0 : $result ['cnt'];
	}

	public function getWordingOrdersCountByCity($cityName)
	{
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->orderstable) . " WHERE orderstate in (1,2,3,4) and customercity = '" . $cityName . "' and uniacid = '{$_W['uniacid']}'");
		return $result ['cnt'] <= 0 ? 0 : $result ['cnt'];
	}

	public function getOrdersCountByArea($orderstate, $cityName, $areaname)
	{
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->orderstable) . " WHERE orderstate = '" . $orderstate . "' and customercity = '" . $cityName . "' and customerarea = '" . $areaname . "' and uniacid = '{$_W['uniacid']}'");
		return $result ['cnt'] <= 0 ? 0 : $result ['cnt'];
	}

	public function getWorkingOrdersCountByArea($cityName, $areaname)
	{
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->orderstable) . " WHERE orderstate in (1,2,3,4) and customercity = '" . $cityName . "' and customerarea = '" . $areaname . "' and uniacid = '{$_W['uniacid']}'");
		return $result ['cnt'] <= 0 ? 0 : $result ['cnt'];
	}

	public function sendMobileMSG($uid, $pwd, $mobile, $content)
	{
		$url = 'http://api.sms.cn/mt/?uid=' . $uid . '&pwd=' . md5($pwd . $uid) . '&mobile=' . $mobile . '&mobileids=' . $mobile . mt_rand() . '&content=' . $content . '&encode=utf8';
		$result = file_get_contents($url);
		return $result;
	}

	public function getToken()
	{
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W ['uniacid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}

	public function sendMBXX($access_token, $data)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
		ihttp_post($url, json_encode($data));
	}

	public function doMobileSendVerifyCode()
	{
		global $_W, $_GPC;
		$tel = $_GPC ['tel'];
		if (empty($tel)) {
			die(json_encode(array("result" => 0, "code" => '手机号码不正确')));
		}
		$_SESSION['tel'] = $tel;
		$_SESSION['rand'] = random(4, true);
		$systeminfo = pdo_fetch("SELECT smsuid,smspwd,smsyzmb FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$content = '您的短信验证码为' . $_SESSION['rand'] . ',请及时验证。';
		$tmparray = explode("#", $systeminfo['smsyzmb']);
		if (is_array($tmparray)) {
			$content = $tmparray[0] . $_SESSION['rand'] . $tmparray[1];
		}
		$result = $this->sendMobileMSG($systeminfo['smsuid'], $systeminfo['smspwd'], $tel, $content);
		$arr = explode("&", $result);
		if (is_array($arr)) {
			$tmp1 = $arr[1];
			$statearr = explode("=", $tmp1);
			if (is_array($statearr)) {
				$state = $statearr[1];
				if ($state == '100') {
					die(json_encode(array("result" => 1, "code" => '验证码已发送')));
				} else {
					die(json_encode(array('result' => 0, 'code' => 'error:' . $state)));
				}
			} else {
				die(json_encode(array('result' => 0, 'code' => 'error')));
			}
		} else {
			die(json_encode(array('result' => 0, 'code' => 'error')));
		}
	}

	public function doMobileVerifyCode()
	{
		global $_W, $_GPC;
		$tel = $_GPC ['tel'];
		$code = $_GPC ['code'];
		$tel2 = $_SESSION['tel'];
		$code2 = $_SESSION['rand'];
		if ($tel == $tel2 && $code == $code2) {
			$_SESSION['mobile'] = $tel;
			$_SESSION['tel'] = '';
			$_SESSION['rand'] = '';
			die(json_encode(array('result' => 1, 'msg' => '验证正确')));
		} else {
			die(json_encode(array('result' => 0, 'msg' => '验证码错误')));
		}
	}

	public function doMobileRecharge()
	{
		global $_W, $_GPC;
		$openid = $_W ['fans'] ['from_user'];
		if (empty($openid)) {
			message('入口错误');
		}
		$_SESSION['ordertype'] = 'recharge';
		$systeminfo = pdo_fetch("SELECT recharge,shopname,imglb1 FROM " . tablename($this->shoptable) . " WHERE uniacid = :uniacid", array(':uniacid' => $_W ['uniacid']));
		$tmparray = explode("#", $systeminfo['recharge']);
		$charge1 = 0;
		$charge1_send = 0;
		$charge2 = 0;
		$charge2_send = 0;
		$charge3 = 0;
		$charge3_send = 0;
		if (is_array($tmparray)) {
			$first = $tmparray[0];
			$second = $tmparray[1];
			$third = $tmparray[2];
			$charge1arr = explode("-", $first);
			if (is_array($charge1arr)) {
				$charge1 = $charge1arr[0];
				$charge1_send = $charge1arr[1];
			}
			$charge2arr = explode("-", $second);
			if (is_array($charge2arr)) {
				$charge2 = $charge2arr[0];
				$charge2_send = $charge2arr[1];
			}
			$charge3arr = explode("-", $third);
			if (is_array($charge3arr)) {
				$charge3 = $charge3arr[0];
				$charge3_send = $charge3arr[1];
			}
		}
		if (checksubmit()) {
			if ($_GPC['fee'] == '' && $_GPC['fee_2'] == '') {
				message('参数错误');
			}
			$fee1 = floatval($_GPC['fee']);
			$fee2 = floatval($_GPC['fee_2']);
			$fee = $fee1;
			if ($fee2 > $fee1) {
				$fee = $fee2;
			}
			if ($fee <= 0) {
				message('支付错误, 金额小于0');
			}
			if (empty($_W['member']['uid'])) {
				message('请先注册会员');
			}
			$chargerecord = pdo_fetch("SELECT * FROM " . tablename('mc_credits_recharge') . " WHERE uniacid = :uniacid AND uid = :uid AND fee = :fee AND status = '0'", array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':fee' => $fee,));
			if (empty($chargerecord)) {
				$chargerecord = array('uid' => $_W['member']['uid'], 'uniacid' => $_W['uniacid'], 'tid' => date('YmdHi') . random(10, 1), 'fee' => $fee, 'status' => 0, 'createtime' => TIMESTAMP,);
				if (!pdo_insert('mc_credits_recharge', $chargerecord)) {
					message('创建充值订单失败，请重试！', url('entry', array('m' => 'recharge', 'do' => 'pay')), 'error');
				}
			}
			$params['tid'] = $chargerecord['tid'];
			$params['ordersn'] = $chargerecord['tid'];
			$params['user'] = $openid;
			$params['fee'] = $fee;
			$params['title'] = '系统余额充值';
			$this->pay($params);
		} else {
			$member = pdo_fetch("SELECT mem.credit2 FROM " . tablename('mc_mapping_fans') . " mapp left join " . tablename('mc_members') . " mem on mapp.uid = mem.uid  WHERE mapp.openid = '" . $openid . "'");
			$yue = 0.00;
			if ($member['credit2']) {
				$yue = $member['credit2'];
			}
			include $this->template('recharge');
		}
	}

	protected function pay($params = array())
	{
		global $_W;
		$ordertype = $_SESSION['ordertype'];
		if (empty($_W['member']['uid'])) {
			checkauth();
		}
		if ($ordertype == 'recharge') {
			$params['module'] = $this->module['name'];
			$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
			$pars = array();
			$pars[':uniacid'] = $_W['uniacid'];
			$pars[':module'] = $params['module'];
			$pars[':tid'] = $params['tid'];
			$log = pdo_fetch($sql, $pars);
			if (!empty($log) && $log['status'] == '1') {
				message('这个订单已经支付成功, 不需要重复支付.');
			}
			$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
			if (!is_array($setting['payment'])) {
				message('没有有效的支付方式, 请联系网站管理员.');
			}
			$pay = $setting['payment'];
			$pay['credit']['switch'] = false;
			$pay['delivery']['switch'] = false;
			include $this->template('common/paycenter');
		} else {
			parent::pay($params);
		}
	}

	private function createOrderExpress($orderid, $orderstate, $employeeid, $employeename, $employeetel)
	{
		global $_W;
		$orderExpress = pdo_fetch("SELECT * FROM " . tablename($this->exprestable) . " WHERE uniacid = :uniacid and  orderid= :orderid and orderstate =:orderstate", array(':uniacid' => $_W ['uniacid'], ':orderid' => $orderid, ':orderstate' => $orderstate));
		if ($orderExpress) {
			return;
		}
		$expressdata = array('uniacid' => $_W ['uniacid'], 'orderid' => $orderid, 'expresstime' => date("Y-m-d H:i:s"), 'orderstate' => $orderstate, 'employeename' => $employeename, 'employeetel' => $employeetel, 'employeeid' => $employeeid);
		pdo_insert($this->exprestable, $expressdata);
	}

	private function createNum()
	{
		global $_W;
		$f = date('Ymd');
		$rnumber = pdo_fetch("SELECT * FROM " . tablename($this->rnumbertbale) . " WHERE uniacid = :uniacid order by id desc ", array(':uniacid' => $_W ['uniacid']));
		$maxNum = 0;
		if (!empty ($rnumber)) {
			$maxNum = $rnumber ['id'];
		}
		pdo_insert($this->rnumbertbale, array('uniacid' => $_W ['uniacid']));
		$lastnum = $maxNum;
		if ($rnumber ['id'] < 9) {
			$lastnum += 1;
			return $f . '00000' . $lastnum;
		} else if ($maxNum < 99) {
			return $f . '0000' . $lastnum;
		} else if ($maxNum < 999) {
			return $f . '000' . $lastnum;
		} else if ($maxNum < 9999) {
			return $f . '000' . $lastnum;
		} else if ($maxNum < 99999) {
			return $f . '000' . $lastnum;
		} else if ($maxNum < 999999) {
			return $f . '00' . $lastnum;
		} else if ($maxNum < 9999999) {
			return $f . '0' . $lastnum;
		} else {
			return $f . $lastnum;
		}
	}

	private function createCardNum()
	{
		global $_W;
		$f = '188';
		$rnumber = pdo_fetch("SELECT * FROM " . tablename($this->cardnumbertbale) . " WHERE uniacid = :uniacid order by id desc ", array(':uniacid' => $_W ['uniacid']));
		$maxNum = 0;
		if (!empty ($rnumber)) {
			$maxNum = $rnumber ['id'];
		}
		pdo_insert($this->cardnumbertbale, array('uniacid' => $_W ['uniacid']));
		$lastnum = $maxNum;
		if ($rnumber ['id'] < 9) {
			$lastnum += 1;
			return $f . '00000' . $lastnum;
		} else if ($maxNum < 99) {
			return $f . '0000' . $lastnum;
		} else if ($maxNum < 999) {
			return $f . '000' . $lastnum;
		} else if ($maxNum < 9999) {
			return $f . '000' . $lastnum;
		} else if ($maxNum < 99999) {
			return $f . '000' . $lastnum;
		} else if ($maxNum < 999999) {
			return $f . '00' . $lastnum;
		} else if ($maxNum < 9999999) {
			return $f . '0' . $lastnum;
		} else {
			return $f . $lastnum;
		}
	}

	private function getAddressByName($areaname)
	{
		global $_W;
		$sql = "select id,cityid from" . tablename($this->areatable) . " where uniacid =:uniacid AND areaname=:areaname";
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':areaname'] = $areaname;
		$area = pdo_fetch($sql, $pars);
		return $area;
	}

	private function getCityNameById($id)
	{
		global $_W;
		$sql = "select cityname from" . tablename($this->citytable) . " where id =:id";
		$pars = array();
		$pars[':id'] = $id;
		$city = pdo_fetch($sql, $pars);
		return $city['cityname'];
	}

	private function getAreaNameById($id)
	{
		global $_W;
		$sql = "select areaname from" . tablename($this->areatable) . " where id =:id";
		$pars = array();
		$pars[':id'] = $id;
		$area = pdo_fetch($sql, $pars);
		return $area['areaname'];
	}

	private function getGoodsById($id)
	{
		global $_W;
		$sql = "select goodsname,danwei from" . tablename($this->goodstable) . " where id =:id";
		$pars = array();
		$pars[':id'] = $id;
		$goods = pdo_fetch($sql, $pars);
		return $goods;
	}
}
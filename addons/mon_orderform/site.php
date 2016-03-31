<?php
/**
 * codeMonkey:2463619823
 */
defined('IN_IA') or exit('Access Denied');
define("MON_ORDER", "mon_orderform");
define("MON_ORDER_RES", "../addons/" . MON_ORDER . "/");
require_once IA_ROOT . "/addons/" . MON_ORDER . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_ORDER . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_ORDER . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_ORDER . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_ORDER . "/WxPayPubHelper/WxPayPubHelper.php";

/**
 * Class Mon_BatonModuleSite
 */
class Mon_OrderformModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__zlv1";
	public static $USER_CB_PAGE_SIZE = 10;
	public $mOrderSetting;
    public static $STATUS_OVER = 1;//已提交
    public static $STATUS_UNPAY =2;//未付款
    public static $STATUS_PAY_OVER = 3;//已付款
	public static $STATUS_ORDER_CLOSE = 4;// 关闭订单
	public static $PAY_ONLINE = 1;
	public static $PAY_OFFLINE = 2;
	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->mOrderSetting = $this->findOrdersetting();
		$this->oauth = new Oauth2('', '');
	}

	public function doWebFormManage()
	{
		global $_W, $_GPC;
		$where = '';
		$params = array();
		$params[':weid'] = $this->weid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `oname` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_ORDER_FORM) . " WHERE weid =:weid " . $where . " ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ORDER_FORM) . " WHERE weid =:weid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_ORDER_ITEM, array("fid" => $id));
			pdo_delete(DBUtil::$TABLE_ORDER_ORDER, array("fid" => $id));
			pdo_delete(DBUtil::$TABLE_ORDER_FORM, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template("form_manage");
	}


	public function doWebOrderItemlist()
	{
		global $_W, $_GPC;
		$where = '';
		$fid = $_GPC['fid'];
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM, $fid);
		$params = array();
		$params[':fid'] = $_GPC['fid'];
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `iname` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_ORDER_ITEM) . " WHERE fid =:fid " . $where . " ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ORDER_ITEM) . " WHERE fid =:fid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_ORDER_ITEM, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template("order_item_manage");
	}


	public function doWebOrderItemEdit()
	{

		global $_W, $_GPC;
		$fid = $_GPC['fid'];
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM, $fid);
		$iid = $_GPC['iid'];
         if ($iid != '') {
			 $item = DBUtil::findById(DBUtil::$TABLE_ORDER_ITEM,$iid);
		 }


		load()->func('tpl');

		if (checksubmit('submit')) {
			$data = array(
				'fid' => $fid,
				'iname' => $_GPC['iname'],
				'ititle' => $_GPC['ititle'],
				'ititle_pg' => $_GPC['ititle_pg'],
				'ititle_url' => $_GPC['ititle_url'],
				'i_summary' => $_GPC['i_summary'],
				'y_price' => $_GPC['y_price'],
				'x_price' => $_GPC['x_price'],
				'i_desc' => htmlspecialchars_decode($_GPC['i_desc']),
				'o_tel' => $_GPC['o_tel'],
				'pay_type' => $_GPC['pay_type'],
				'o_num' => $_GPC['o_num'],
				'displayorder' => $_GPC['displayorder'],
				'createtime' => TIMESTAMP
			);

			if (empty ($iid)) {
				DBUtil::create(DBUtil::$TABLE_ORDER_ITEM, $data);
				message('添加成功！', referer(), 'success');

			} else {
				DBUtil::updateById(DBUtil::$TABLE_ORDER_ITEM, $data, $iid);
				message('修改成功！', referer(), 'success');
			}

		}

		include $this->template("order_item_edit");
	}

	/**
	 * 参加用户
	 */
	public function  doWebOrderList()
	{
		global $_W, $_GPC;
		$fid = $_GPC['fid'];
		$iid = $_GPC['iid'];
		$params = array();
		$where = ' 1=1 ';

		$status = $_GPC['status'];
		if (!empty($status)) {
			$where.=" and o.status=:status";
			$params[':status'] = $status;
		}

		if ($fid !='') {
			$where.=" and o.fid=:fid";
			$params[':fid'] = $fid;
		}

		if ($iid !='') {
			$where.=" and o.iid=:iid";
			$params[':iid'] = $iid;
		}


		if (isset($_GPC['keyword'])) {
			$where .= ' AND utel Like :keywords ';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 50;
			$list = pdo_fetchall("SELECT i.iname as iname , o.* FROM " . tablename(DBUtil::$TABLE_ORDER_ORDER) .
				" o left join ".tablename(DBUtil::$TABLE_ORDER_ITEM)." i on o.iid=i.id  where" . $where
				. " ORDER BY createtime desc LIMIT  ". ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ORDER_ORDER) . " o  WHERE " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_ORDER_ORDER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		} else if($operation == 'cl') {
			$id = $_GPC['id'];
			DBUtil::updateById(DBUtil::$TABLE_ORDER_ORDER,array('status'=>$this::$STATUS_ORDER_CLOSE),$id);
			message('订单处理成功！', referer(), 'success');
		}

		include $this->template("order_list");

	}

	/**
	 * author: www.zheyitianShi.Com
	 * 订单详细
	 */
	public function doWebOrderDetail() {
		global $_W, $_GPC;
		$oid = $_GPC['oid'];
        $fid = $_GPC['fid'];
		$order = DBUtil::findById(DBUtil::$TABLE_ORDER_ORDER, $oid);
		$item = DBUtil::findById(DBUtil::$TABLE_ORDER_ITEM, $order['iid']);
		include $this->template("order_detail");
	}

	public  function doWebOrderDownload() {

		global $_GPC,$_W;

		$fid = $_GPC['fid'];
		$iid = $_GPC['iid'];
		$params = array();
		$where = ' 1=1 ';


		$dc = $_GPC['dc'];

		$status = $_GPC['status'];
		if (!empty($status)) {
			$where.=" and o.status=:status";
			$params[':status'] = $status;
		}

		if ($fid !='') {
			$where.=" and o.fid=:fid";
			$params[':fid'] = $fid;
		}

		if ($iid !='') {
			$where.=" and o.iid=:iid";
			$params[':iid'] = $iid;
		}

		$list = pdo_fetchall("SELECT i.iname as iname , o.* FROM " . tablename(DBUtil::$TABLE_ORDER_ORDER) .
			" o left join ".tablename(DBUtil::$TABLE_ORDER_ITEM)." i on o.iid=i.id  where" . $where
			. " ORDER BY createtime desc  ", $params);

		$tableheader = array($this->encode("openid",$dc), $this->encode("昵称",$dc),$this->encode("姓名",$dc),$this->encode("手机号",$dc)
		,$this->encode('原价',$dc ),$this->encode('现价',$dc ), $this->encode('总价',$dc ), $this->encode('数量',$dc ),
			$this->encode('支付方式',$dc ), $this->encode('状态',$dc ),$this->encode('下单时间',$dc), $this->encode('预约时间',$dc),
			$this->encode('用户备注',$dc ));
		$html = "\xEF\xBB\xBF";
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {

			$html .= $value['openid'] . "\t ,";
			$html .= $this->encode( $value['nickname'],$dc )  . "\t ,";
			$html .= $this->encode( $value['uname'],$dc )  . "\t ,";
			$html .= $this->encode( $value['utel'],$dc )  . "\t ,";
			$html .= $this->encode( $value['o_yprice'],$dc )  . "\t ,";
			$html .= $this->encode( $value['o_xprice'],$dc )  . "\t ,";
			$html .= $this->encode( $value['zf_price'],$dc )  . "\t ,";
			$html .= $this->encode( $value['ordernum'],$dc )  . "\t ,";
			if ($value['pay_type']==1) {
				$html .= $this->encode( '立即支付',$dc )  . "\t ,";
			}

			if ($value['pay_type']==2) {
				$html .= $this->encode( '现场支付',$dc )  . "\t ,";
			}

			if ($value['pay_type']==2) {
				$html .= $this->encode( $this->getStatusText($value['status']),$dc )  . "\t ,";
			}
			$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\t ,";
			$html .= ($value['ordertime'] == 0 ? '' : date('Y-m-d',$value['ordertime'])) . "\t ,";
			$html .= $this->encode( $value['remark'],$dc )  . "\t ,";

		}
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=订单.xls");
		echo $html;
		exit();
	}




	/**
	 * author: www.zheyitianShi.Com
	 * 删除摇一摇
	 */
	public function doWebDeleteform()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $fid) {
			$id = intval($fid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_ORDER_ITEM, array("fid" => $id));
			pdo_delete(DBUtil::$TABLE_ORDER_ORDER, array("fid" => $id));
			pdo_delete(DBUtil::$TABLE_ORDER_FORM, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}
	public function doWebDeleteformItem()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $iid) {
			$id = intval($iid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_ORDER_ORDER, array("iid" => $id));
			pdo_delete(DBUtil::$TABLE_ORDER_ITEM, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteOrder()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $oid) {
			$id = intval($oid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_ORDER_ORDER, array("id" => $id));

		}
		echo json_encode(array('code' => 200));
	}


	/**
	 * author: codeMonkey QQ:246361982
	 * 设置
	 */
	public function doWebOrderSetting()
	{
		global $_GPC,$_W;
		$ordersetting = DBUtil::findUnique(DBUtil::$TABLE_ORDER_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret']),
				'mchid' => trim($_GPC['mchid']),
				'shkey' => trim($_GPC['shkey'])
			);

			if (!empty($ordersetting)) {
				DBUtil::updateById(DBUtil::$TABLE_ORDER_SETTING, $data, $ordersetting['id']);
			} else {

				DBUtil::create(DBUtil::$TABLE_ORDER_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('OrderSetting', array(
				'op' => 'display'
			)), 'success');
		}


		include $this->template("ordersetting");
	}

	/**
	 * author: www.zheyitianShi.Com
	 * 模板设置
	 */
	public function doWebTemplateSetting() {

		global $_GPC,$_W;
		$ordertempalte = DBUtil::findUnique(DBUtil::$TABLE_ORDER_TEMPLATE, array(':weid' => $this->weid));
		if (checksubmit('submit')) {

			$data = array(
				'weid' => $this->weid,
				'ordertid' => trim($_GPC['ordertid']),
				'orderenable' => $_GPC['orderenable'],
				'paytid' => $_GPC['paytid'],
				'payenable' => $_GPC['payenable'],
				'createtime' => TIMESTAMP
			);

			if (!empty($ordertempalte)) {
				DBUtil::updateById(DBUtil::$TABLE_ORDER_TEMPLATE, $data, $ordertempalte['id']);
			} else {

				DBUtil::create(DBUtil::$TABLE_ORDER_TEMPLATE, $data);
			}
			message('消息模板参数设置成功！', $this->createWebUrl('TemplateSetting', array(
				'op' => 'display'
			)), 'success');
		}

		include $this->template('tempaltesetting');
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 用户信息导出
	 */
	public function  doWebUDownload()
	{

		require_once 'udownload.php';
	}

	public function  doWebRDownload()
	{

		require_once 'rdownload.php';
	}


	/*****************

	 手机
	 *******/

	/**
	 * author: www.zheyitianShi.Com
	 * 首页
	 */
	public function doMobileIndex()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$fid = $_GPC['fid'];
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM,$fid);
		MonUtil::emtpyMsg($form,"订单删除或不存在");
		$items = pdo_fetchall("SELECT * from ".tablename(DBUtil::$TABLE_ORDER_ITEM)." where fid=:fid order by displayorder asc " , array(":fid" => $fid));
		include $this->template("index");
	}

	/**
	 * author: www.zheyitianShi.Com
	 * 具体订单类型
	 */
	public function doMobileOrderItem() {
		global $_W, $_GPC;		
		MonUtil::checkmobile();
		$iid = $_GPC['iid'];
		$item = DBUtil::findById(DBUtil::$TABLE_ORDER_ITEM,$iid);

		MonUtil::emtpyMsg($item,"订单项删除或不存在");
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM, $item['fid']);
		$alreadyCount = $this->findTotalOrderNum($item['id']);
		$item['leftCount'] = $item['o_num'] - $alreadyCount;
		//$openid = "o_-Hajq-MxgT-pvJX7gRMswH8_eM";

		if ($_GPC['openid'] !='') {
			$openid = $_GPC['openid'];
		} else {
			$openid = $_W['fans']['from_user'];
		}

		$order = $this->findOrder($iid, $openid);
		include $this->template("order");
	}

	public function doMobileFormDeatil() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$fid = $_GPC['fid'];
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM,$fid);
		MonUtil::emtpyMsg($form,"订单删除或不存在");
		include $this->template("formdeatil");

	}

	/**
	 * author: www.zheyitianShi.Com
	 */
	public function doMobileaddress() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$fid = $_GPC['fid'];
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM,$fid);
		MonUtil::emtpyMsg($form,"订单删除或不存在");
		include $this->template("form_address");
	}

	/**
	 * author: www.zheyitianShi.Com
	 * 提交订单
	 */
	public function doMobileSubmitOrder() {
		global $_W, $_GPC;
		$fid = $_GPC['fid'];
		$iid = $_GPC['iid'];
		$orderItem = DBUtil::findById(DBUtil::$TABLE_ORDER_ITEM,$iid);
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM, $fid);
		$res = array();

		if (empty($orderItem)) {
			$res['code'] = 500;
			$res['msg'] = '订单项目删除或不存在';
			die(json_encode($res));
		}

		if (empty($form)) {
			$res['code'] = 501;
			$res['msg'] = '订单不删除或不存在';
			die(json_encode($res));
		}
		$openid = $_W['fans']['from_user'];
		//$openid = "o_-Hajq-MxgT-pvJX7gRMswH8_eM";

		if (empty($openid)) {
			$res['code'] = 502;
			$res['msg'] = '请关注公众账号后再进行提交订单哦!';
			die(json_encode($res));
		}

		$dbOrder = $this->findOrder($iid, $openid);

		if (!empty($dbOrder)) {
			$res['code'] = 503;
			$res['msg'] = '已经提交过订单,请不要重复提交哦!';
			die(json_encode($res));
		}

		$alreadyOrderNum = $this->findTotalOrderNum($iid);
        $orderNum = $_GPC['ordernum'];
		if ($orderItem['o_num']-$alreadyOrderNum <= 0) {
			$res['code'] = 503;
			$res['msg'] = '数量已经没有了，下次再来预定吧!';
			die(json_encode($res));
		}

		if ($orderNum > $orderItem['o_num']-$alreadyOrderNum) {
			$res['code'] = 504;
			$res['msg'] = '您的数量已经超出了剩余数量，请重写填写数量!';
			die(json_encode($res));
		}
        $ordertime = strtotime($_GPC['ordertime']);
		$userInfo = $this ->getClientUserInfo($openid);
        $zf_price = $orderItem['x_price'] * $orderNum;
		if ($orderItem['pay_type'] == $this::$PAY_ONLINE) {//在线支付
			$status = $this::$STATUS_UNPAY;
		} else if($orderItem['pay_type'] == $this::$PAY_OFFLINE) { //线下支付
			$status = $this::$STATUS_OVER;
		}

		$orderno = date("YmdHis", TIMESTAMP);
		$orderData = array(
			'fid' => $fid,
			'orderno' =>$orderno,
			'iid' => $iid,
			'acid' => $_W['acid'],
			'openid' => $openid,
			'nickname' => $userInfo['nickname'],
		    'headimgurl' => $userInfo['headimgurl'],
		     'uname' => $_GPC['uname'],
             'utel' =>$_GPC['utel'],
			'ordertime' => $ordertime,
			'ordernum' => $orderNum,
			'o_yprice' => $orderItem['y_price'],
			'o_xprice' => $orderItem['x_price'],
            'zf_price' => $zf_price,
			'pay_type' => $orderItem['pay_type'],
			'remark' => $_GPC['remark'],
			'status' => $status,
			'createtime' => TIMESTAMP
		);

		DBUtil::create(DBUtil::$TABLE_ORDER_ORDER,$orderData);
		$oid = pdo_insertid();
		$res['code'] = 200;
		$res['oid'] = $oid;

		$this->sendOrderTemplateMsg($orderData,$orderItem ,$form);
		$this->sendEmail($orderData, $orderItem, $form);

		die(json_encode($res));
	}


	public function sendEmail($orderData, $orderItem, $form) {

          if ($form['emailenable'] == 1 && !empty($form['email'])) {

			  load()->func('communication');

              $body = "姓名:".$orderData['uname']."\t 手机:".$orderData['utel']."\t数量:".$orderData['ordernum']."\t原价:".$orderData['o_yprice']
				  ."\t现价:".$orderData['o_xprice']."\t支付金额:".$orderData['zf_price'];
			  ihttp_email($form['email'], $orderItem['iname'] . "用户下单提醒", $body . "\n提交订单");

		  }
	}

	/**
	 * author: www.zheyitianShi.Com
	 */
	public function doMobileOrderPay() {
		global $_W, $_GPC ;
		$oid =  $_GPC['oid'];
		$order = DBUtil::findById(DBUtil::$TABLE_ORDER_ORDER, $oid);
		MonUtil::emtpyMsg($order, "订单删除或不存在");
        $item = DBUtil::findById(DBUtil::$TABLE_ORDER_ITEM, $order['iid']);
		$form = DBUtil::findById(DBUtil::$TABLE_ORDER_FORM, $order['fid']);
		if ($order['status'] == $this::$STATUS_UNPAY && $order['pay_type'] == 1) {//立即支付
			$jsApi = new JsApi_pub($this->mOrderSetting);
			$jsApi->setOpenId($order['openid']);

			$unifiedOrder = new UnifiedOrder_pub($this->mOrderSetting);
			$unifiedOrder->setParameter("openid", $order['openid']);//商品描述
			$unifiedOrder->setParameter("body", "预约订单".$item['iname']);//商品描述

			$out_trade_no  = date("YmdHis", TIMESTAMP);
			$unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
			//$unifiedOrder->setParameter("total_fee", 1);//总金额
			$unifiedOrder->setParameter("total_fee", $order['zf_price']*100);//总金额
			$notifyUrl = $_W['siteroot'] . "addons/" . MON_ORDER . "/notify.php";
			$unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
			$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
			$prepay_id = $unifiedOrder->getPrepayId();
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			DBUtil::updateById(DBUtil::$TABLE_ORDER_ORDER, array('outno'=>$out_trade_no), $oid);
		}

		include $this->template("orderPay");

	}

	/**
	 * author: www.zheyitianShi.Com
	 * @param $order
	 * 发送订单模板消息
	 */
	public function sendOrderTemplateMsg($order, $item, $form) {
		$templateMsg = array();
		$template = $this->findTemplateSetting();
		if ($template['orderenable']==1) {
			$templateMsg['template_id'] = $template['ordertid'];
			$templateMsg['touser'] = $order['openid'];
			$templateMsg['url'] = MonUtil::str_murl($this->createMobileUrl('OrderItem',array('openid'=>$order['openid'], 'iid'=>$item['id']),true));
			$templateMsg['topcolor'] = '#FF0000';
			$data = array();
			$data['first'] = array('value'=>"恭喜您提交".$item['iname']."订单成功!", 'color'=>'#173177');
			$data['orderType'] = array('value'=>$item['iname']."订单成功!", 'color'=>'#173177');
			$data['tradeDateTime'] = array('value'=>date('Y-m-d H:i', $order['createtime']), 'color'=>'#173177');
			$data['customerInfo'] = array('value'=>'姓名:'.$order['uname'].' 手机:'.$order['utel'], 'color'=>'#173177');
			$data['orderItemName'] = array('value'=>$form['pname'], 'color'=>'#000000');
			$data['orderItemData'] = array('value'=>$item['iname'], 'color'=>'#173177');
			$data['remark'] = array('value'=>"保存好您的凭证哦!欢迎下次再次预定", 'color'=>'#173177');
			$templateMsg['data'] = $data;
			$jsonData = json_encode($templateMsg);
			WeUtility::logging('info',"发送模板消息数据".$jsonData);
			load()->func('communication');
			$acessToken = $this->getAccessToken();
			$apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$acessToken;
			$result = ihttp_request($apiUrl, $jsonData);
			WeUtility::logging('info',"发送模板消息返回内容".$result);

		}
	}


   public function doMobilePaySuccess() {
	   global $_GPC;
       $oid = $_GPC['oid'];
	   $order = DBUtil::findById(DBUtil::$TABLE_ORDER_ORDER, $oid);
	   $item = DBUtil::findById(DBUtil::$TABLE_ORDER_ITEM, $order['iid']);

	   if (!empty($order) && !empty($item)) {
		   $this->sendPayTemplateMsg($order, $item);
	   }

	   die(json_encode(array('code'=>200)));

   }

	/**
	 * author: www.zheyitianShi.Com
	 * @param $order
	 * @param $item
	 * 发送模板消息
	 */
	public function sendPayTemplateMsg($order, $item) {
		$template = $this->findTemplateSetting();
		if ($template['payenable'] == 1) {
			$templateMsg = array();
			$templateMsg['template_id'] = $template['paytid'];
			$templateMsg['touser'] = $order['openid'];
			$templateMsg['url'] = MonUtil::str_murl($this->createMobileUrl('OrderItem',array('openid'=>$order['openid'], 'iid'=>$item['id']),true));
			$templateMsg['topcolor'] = '#FF0000';
			$data = array();
			$data['first'] = array('value'=>"恭喜".$order['uname']."支付".$item['iname']."成功!", 'color'=>'#173177');
			$data['orderMoneySum'] = array('value'=>$order['zf_price'], 'color'=>'#173177');
			$data['orderProductName'] = array('value'=>$item['iname'], 'color'=>'#173177');
			$data['remark'] = array('value'=>"保存好您的支付凭证哦!欢迎下次再次预定", 'color'=>'#173177');
			$templateMsg['data'] = $data;
			$jsonData = json_encode($templateMsg);
			WeUtility::logging('info',"发送模板消息数据".$jsonData);
			load()->func('communication');
			$acessToken = $this->getAccessToken();
			$apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$acessToken;
			$result = ihttp_request($apiUrl, $jsonData);
			WeUtility::logging('info',"发送支付消息返回内容".$result);
		}
	}


	public  function  getAccessToken () {
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}
	public function getClientUserInfo($openid)
	{
		global $_W;
		if (!empty($openid) && ($_W['account']['level'] == 3 || $_W['account']['level'] == 4)) {

			$access_token = $this->getAccessToken();

			if (empty($access_token)) {
				message("获取accessToken失败");
			}
			$userInfo = $this->oauth->getUserInfo($access_token, $openid);
			return $userInfo;
		}
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $sid
	 * @param $openid
	 * @return bool
	 * 总次数
	 */
	public function  findUserRecordCount($sid, $openid)
	{
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  sid=:sid and openid=:openid ", array(':sid' => $sid, ":openid" => $openid));
		return $count;
	}

	/**
	 * author: www.zheyitianShi.Com
	 * @param $iid
	 * @return bool
	 * 总的已定的数量
	 */
    public  function findTotalOrderNum($iid) {
		$alreadyCount = pdo_fetchcolumn('SELECT sum(ordernum) FROM ' . tablename(DBUtil::$TABLE_ORDER_ORDER) . " WHERE  id=:iid ", array(":iid" => $iid));
		return $alreadyCount;
	}

	public function findOrder($iid,$openid) {
		return DBUtil::findUnique(DBUtil::$TABLE_ORDER_ORDER,array(":iid"=>$iid,":openid"=>$openid));
	}
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $sid
	 * @param $openid
	 * @return bool 查找分享次数
	 */
	public function  findUserDayShareCount($sid, $openid)
	{
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));

		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $sid
	 * @param $openid
	 * @return bool
	 */
	public function  findUserDayAward($sid, $openid)
	{
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT sum(award_count) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}


	public function getCookieUserInof($zid)
	{
		$cookieUserInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $zid);
		return $cookieUserInfo;
	}

	/***************************函数********************************/
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */

	function  encode($value, $dc)
	{

		if ($dc == 1) {
			return $value;
		}

		if ($dc == 2) {
			return iconv("utf-8", "gb2312", $value);
		}

	}

	public function getStatusText($status) {
		switch ($status) {
			case Mon_OrderformModuleSite::$STATUS_OVER:
				return "已下单";
				break;
			case Mon_OrderformModuleSite::$STATUS_UNPAY:
				return "未支付";
				break;
			case Mon_OrderformModuleSite::$STATUS_PAY_OVER:
				return "已支付";
				break;
			case Mon_OrderformModuleSite::$STATUS_ORDER_CLOSE:
				return "已处理";
				break;
		}
	}

	public function findOrdersetting()
	{
		$morsetting = DBUtil::findUnique(DBUtil::$TABLE_ORDER_SETTING, array(":weid" => $this->weid));
		return $morsetting;
	}

	/**
	 * author: www.zheyitianShi.Com
	 * @return bool
	 * 查找模板消息
	 */
	public function findTemplateSetting() {
		$ordertempalte = DBUtil::findUnique(DBUtil::$TABLE_ORDER_TEMPLATE, array(':weid' => $this->weid));
		return $ordertempalte;
	}

}
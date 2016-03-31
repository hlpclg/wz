<?php
/**
 * 
 *
 * 
 */
defined('IN_IA') or exit('Access Denied');
load()->classs('wesession');
require_once IA_ROOT ."/addons/xfeng_community/model.php";
class Xfeng_communityModuleSite extends WeModuleSite {
	
	// function __construct(){
	// 	global $_W, $_GPC;

	// 	// 验证信息
	// 	$auth = array();
	// 	$auth['qq'] = '344100965';	// 必填
	// 	$auth['mname'] = 'xfeng_community'; // 必填
	// 	$auth['key'] = $auth['mname'].$_SERVER['HTTP_HOST'];
	// 	$verify_file = './source/modules/weischool/verify.txt';
	// 	$verify = 0;
	// 	if( file_exists($verify_file) ){
	// 		// 读验证信息
	// 		$htmlfp=@fopen($verify_file,"r");
	// 		$string=@fread($htmlfp,@filesize($verify_file));
	// 		@fclose($htmlfp);
	// 		// 解密
	// 		$verify = authcode($string,'DECODE',$auth['key'],0); 
	// 	}
	// 	if($verify==0){
	// 		message('未取得商业授权，请联系开发者 QQ:'.$auth['qq']);
	// 	}
		
	// }

	//后台程序 inc/web文件夹下
	public function __web($f_name){
		include_once  'inc/web/'.strtolower(substr($f_name,5)).'.inc.php';
	}
	//后台小区信息
	public function doWebRegion(){
		$this->__web(__FUNCTION__);
	}
	//后台小区公告
	public function doWebAnnouncement(){
		$this->__web(__FUNCTION__);
	}
	//后台小区用户
	public function doWebMember(){
		$this->__web(__FUNCTION__);
	}
	//后台小区报修
	public function doWebRepair(){
		$this->__web(__FUNCTION__);
	}
	//后台常用号码
	public function doWebPhone(){
		$this->__web(__FUNCTION__);
	}	
	//后台投诉
	public function doWebReport(){
		$this->__web(__FUNCTION__);
	}
	//后台家政服务
	public function doWebHomemaking(){
		$this->__web(__FUNCTION__);
	}
	//后台房屋租赁
	public function doWebHouselease(){
		$this->__web(__FUNCTION__);
	}
	//后台物业团队介绍
	public  function doWebProperty(){
		$this->__web(__FUNCTION__);	
	}
	//后台导航扩展
	public function doWebnavExtension(){
		$this->__web(__FUNCTION__);	
	}
	//后台幻灯片设置
	public function doWebSlide(){
		$this->__web(__FUNCTION__);	
	}	
	//后台-小区活动
	public function doWebActivity() {
 		$this->__web(__FUNCTION__);
	}
	//后台-常用查询
	public function doWebSearch() {
		$this->__web(__FUNCTION__);
	}
	//后台-二手市场
	public function doWebFled(){
		$this->__web(__FUNCTION__);
	}
	//后台-小区拼车
	public function doWebCarpool(){
		$this->__web(__FUNCTION__);
	}
	//后台-小区商家
	public function doWebBusiness(){
		$this->__web(__FUNCTION__);
	}
	//后台-查物业费
	public function doWebPropertyfree(){
		$this->__web(__FUNCTION__);
	}
	//后台-分类管理
	public function doWebServicecategory(){
		$this->__web(__FUNCTION__);
	}
	//后台-黑名单管理
	public function doWebBlack(){
		$this->__web(__FUNCTION__);
	}
	//后台超市管理
	public function doWebShopping(){
		$this->__web(__FUNCTION__);
	}
	//后台水电煤缴费
	public function doWebCost(){
		$this->__web(__FUNCTION__);
	}
	//后台风格
	public function doWebStyle(){
		$this->__web(__FUNCTION__);
	}
	//后台菜单
	public function doWebNav(){
		$this->__web(__FUNCTION__);
	}
	//通知设置
	public function doWebNotice(){
		$this->__web(__FUNCTION__);
	}
	//前台程序 inc/app文件夹下
	public function __app($f_name){
		include_once  'inc/app/'.strtolower(substr($f_name,8)).'.inc.php';
	}
	//前台手机首页
    public function doMobileHome(){
    	$this->__app(__FUNCTION__);
    }
    //前台手机住户注册页面
    public function doMobileRegister(){
    	$this->__app(__FUNCTION__);	 
    }
    //注册短信验证
    public  function doMobileVerifycode(){
		$this->__app(__FUNCTION__);	 
	}
    //前台个人页面
	public function doMobileMember(){
		$this->__app(__FUNCTION__);	
	}
    //前台手机公告页面
    public function doMobileAnnouncement(){
    	$this->__app(__FUNCTION__);	
    }
    //前台手机常用电话页面
    public function doMobilePhone(){
    	$this->__app(__FUNCTION__);	
    }
    //前台报修
    public function doMobileRepair(){
    	$this->__app(__FUNCTION__);	
    }
	//前台-小区活动首页
	public function doMobileActivity() {
 		$this->__app(__FUNCTION__);
	}
	//前台投诉
    public function doMobileReport(){
    	$this->__app(__FUNCTION__);
    } 
    //前台家政服务
    public function doMobileHomemaking(){
    	$this->__app(__FUNCTION__);
    }
    //前台房屋租赁
   	public function doMobileHouselease(){
   		$this->__app(__FUNCTION__);
   	}
   	//前台团队介绍
    public function doMobileProperty(){
   		$this->__app(__FUNCTION__);
    }
	//前台-小区活动详细页
	public function doMobileDetail(){
		$this->__app(__FUNCTION__);
	}
	//前台-小区活动报名页面
	public function doMobileRes(){
		$this->__app(__FUNCTION__);
	}
	//前台-小区常用查询
	public function doMobileSearch(){
		$this->__app(__FUNCTION__);
	}
	//前台-小区二手市场
	public function doMobileFled(){
		$this->__app(__FUNCTION__);
	}
	//前台-小区拼车
	public function doMobileCar(){
		$this->__app(__FUNCTION__);
	}
	//前台-小区商家
	public function doMobileBusiness(){
		$this->__app(__FUNCTION__);
	}
	//前台-查物业费
	public function doMobilePropertyfree(){
		$this->__app(__FUNCTION__);
	}
	//前台-小区超市
	public function doMobileShopping(){
		$this->__app(__FUNCTION__);
	}
	//前台水电煤缴费
	public function doMobileCost(){
		$this->__app(__FUNCTION__);
	}
	//获取当前公众号所有小区信息
	public function regions(){
		global $_W;
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		return $regions;
	}
	//判断是否注册成为小区用户
    public function changemember(){
    	global $_GPC,$_W;
    	$member  = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid='{$_W['fans']['from_user']}' AND weid='{$_W['uniacid']}'");
		if (empty($member)) {
			header("Location:".$this->createMobileUrl('register'));
			exit;
		}else{
			return $member;
		}
    }
    //报修前台处理提交补充信息，isreply=0为前台提交，isreply=1为后台管理回复
    public function doMobileReply(){
    	global $_GPC,$_W;
    	if($_W['ispost']){
    		$data = array(
				'weid'       =>$_W['weid'],
				'openid'     =>$_W['fans']['from_user'],
				'reportid'   =>$_GPC['id'],
				'isreply'    =>0,
				'content'    =>$_GPC['content'],
				'createtime' =>$_W['timestamp'],
    			);
    		if (pdo_insert('xcommunity_reply',$data)) {
    			message('提交成功',referer(),'success');
    		}
    	} 
    		
    }	
	//报修投诉短信提醒
	public function Resms($content,$mobile){
		global $_W,$_GPC;
		load()->func('communication');
			$region = pdo_fetch("SELECT linkway FROM".tablename('xcommunity_region')."as r left join".tablename('xcommunity_member')."as m on r.id = m.regionid WHERE m.openid = '{$_W['fans']['from_user']}'");
			$tpl_id    = $this->module['config']['reportid'];
			$company   = $this->module['config']['cname'];
			$linkway   = $region['linkway'];
			$tpl_value = urlencode("#content#=$content&#mobile#=$mobile&#company#=$company");
			$appkey    = $this->module['config']['sms_account'];
			$params    = "mobile=".$linkway."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
			$url       = 'http://v.juhe.cn/sms/send';
			//print_r($url);exit;
			$content   = ihttp_post($url,$params);
			
		
	}
	/**
	* 读取excel $filename 路径文件名 $indata 返回数据的编码 默认为utf8
	*以下基本都不要修改
	*/
	public function read($filename,$encode='utf-8'){
		require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		$indata = $objPHPExcel->getSheet(0)->toArray();
		return $indata;
			
	 } 
	 //处理图片上传;
	 public function doMobileimgupload(){
			global $_W,$_GPC;
				
			if(!empty($_GPC['pic'])){
				preg_match("/data\:image\/([a-z]{1,5})\;base64\,(.*)/",$_GPC['pic'],$r);
				$imgname = 'bl'.time().rand(10000,99999).'.'.$r[1];
				$path = IA_ROOT.'/'.$_W['config']['upload']['attachdir'].'/images/';
				$f =fopen($path.$imgname,'w+');
				fwrite($f,base64_decode($r[2]));
				fclose($f);
				$imgurl = $_W['attachurl'].'/images/'.$imgname;
				$is = pdo_insert('xfcommunity_images',array('src'=>$imgurl));
				$id = pdo_insertid();
				if(empty($is)){
				 exit(json_encode(array(
					  'errCode'=>1,
					  'message'=>'上传出现错误',
					  'data'=>array('id'=>$_GPC['t'],'picId'=>$id)
				  )));
				}else{
				  exit(json_encode(array(
					  'errCode'=>0,
					  'message'=>'上传成功',
					  'data'=>array('id'=>$_GPC['id'],'picId'=>$id)
				  )));
				}
			}
			
		} 
		//飞印打印机
		function sendFreeMessage($msg) {
			$API_KEY      = $this->module['config']['api_key'];
			$msg['reqTime'] = number_format(1000*time(), 0, '', '');
			$content = $msg['memberCode'].$msg['msgDetail'].$msg['deviceNo'].$msg['msgNo'].$msg['reqTime'].$API_KEY;
			$msg['securityCode'] = md5($content);
			$msg['mode']=2;

			return $this->sendMessage($msg);
		}
		public function sendMessage($msgInfo){
			load()->func('communication');
			$content = ihttp_post('http://my.feyin.net/api/sendMsg',$msgInfo);
		} 
		public function getCartTotal() {
			global $_W;
			$cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('xcommunity_shopping_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['fans']['from_user']}'");
			return empty($cartotal) ? 0 : $cartotal;
		}
		private function getFeedbackType($type) {
			$types = array(1 => '维权', 2 => '告警');
			return $types[intval($type)];
		}
		private function getFeedbackStatus($status) {
			$statuses = array('未解决', '用户同意', '用户拒绝');
			return $statuses[intval($status)];
		}
	 	function time_tran($the_time) {
		$timediff = $the_time - time();
		$days = intval($timediff / 86400);
		if (strlen($days) <= 1) {
			$days = "0" . $days;
		}
		$remain = $timediff % 86400;
		$hours = intval($remain / 3600);
		;
		if (strlen($hours) <= 1) {
			$hours = "0" . $hours;
		}
		$remain = $remain % 3600;
		$mins = intval($remain / 60);
		if (strlen($mins) <= 1) {
			$mins = "0" . $mins;
		}
		$secs = $remain % 60;
		if (strlen($secs) <= 1) {
			$secs = "0" . $secs;
		}
		$ret = "";
		if ($days > 0) {
			$ret.=$days . " 天 ";
		}
		if ($hours > 0) {
			$ret.=$hours . ":";
		}
		if ($mins > 0) {
			$ret.=$mins . ":";
		}
		$ret.=$secs;
		return array("倒计时 " . $ret, $timediff);
	}
	//设置订单积分
	public function setOrderCredit($orderid, $add = true) {
		global $_W;
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id limit 1", array(':id' => $orderid));
		if (empty($order)) {
			return false;
		}
		$sql = 'SELECT `goodsid`, `total` FROM ' . tablename('xcommunity_shopping_order_goods') . ' WHERE `orderid` = :orderid';
		$orderGoods = pdo_fetch($sql, array(':orderid' => $orderid));
		if (!empty($orderGoods)) {
			$sql = 'SELECT `credit` FROM ' . tablename('xcommunity_shopping_goods') . ' WHERE `id` = :id';
			$credit = pdo_fetchcolumn($sql, array(':id' => $orderGoods['goodsid']));
		}
		//增加积分
		if (!empty($credit)) {
			load()->model('mc');
			load()->func('compat.biz');
			$uid = mc_openid2uid($order['from_user']);
			$fans = fans_search($uid, array("credit1"));
			if (!empty($fans)) {
				if (!empty($add)) {
					mc_credit_update($_W['member']['uid'], 'credit1', $credit * $orderGoods['total'], array('0' => $_W['member']['uid'], '购买商品赠送'));
				} else {
					mc_credit_update($_W['member']['uid'], 'credit1', 0 - $credit * $orderGoods['total'], array('0' => $_W['member']['uid'], '微商城操作'));
				}
			}
		}
	}
	//设置订单商品的库存 minus  true 减少  false 增加
	private function setOrderStock($id = '', $minus = true) {
		$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.total as goodstotal,o.total,o.optionid,g.sales FROM " . tablename('xcommunity_shopping_order_goods') . " o left join " . tablename('xcommunity_shopping_goods') . " g on o.goodsid=g.id "
				. " WHERE o.orderid='{$id}'");
		foreach ($goods as $item) {
			if ($minus) {
				//属性
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('xcommunity_shopping_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] - $item['total'];
				}
				$data['sales'] = $item['sales'] + $item['total'];
				pdo_update('xcommunity_shopping_goods', $data, array('id' => $item['id']));
			} else {
				//属性
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('xcommunity_shopping_goods_option') . " set stock=stock+:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] + $item['total'];
				}
				$data['sales'] = $item['sales'] - $item['total'];
				pdo_update('xcommunity_shopping_goods', $data, array('id' => $item['id']));
			}
		}
	}
	public function payResult($params) {
		global $_W;
		WeSession::start($_W['uniacid'],$_W['fans']['from_user'],60);
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		//判断是否是缴纳物业费用
		if ($_SESSION['type'] == 'profree') {
			pdo_update('xcommunity_propertyfree', array('status' => 1), array('id' => $params['tid']));
			if ($params['from'] == 'return') {
				if ($params['type'] == $credit) {
					message('缴费成功！', $this->createMobileUrl('propertyfree',array('op' => 'display')), 'success');
				} else {
					message('缴费成功！', '../../app/' . $this->createMobileUrl('propertyfree',array('op' => 'display')), 'success');
				}
			}
			exit();

		}
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
		}

		$sql = 'SELECT `goodsid` FROM ' . tablename('xcommunity_shopping_order_goods') . ' WHERE `orderid` = :orderid';
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $params['tid']));
		$sql = 'SELECT `total`, `totalcnf` FROM ' . tablename('xcommunity_shopping_goods') . ' WHERE `id` = :id';
		$goodsInfo = pdo_fetch($sql, array(':id' => $goodsId));
		// 更改库存
		if ($goodsInfo['totalcnf'] == '1' && !empty($goodsInfo['total'])) {
			pdo_update('xcommunity_shopping_goods', array('total' => $goodsInfo['total'] - 1), array('id' => $goodsId));
		}
		pdo_update('xcommunity_shopping_order', $data, array('id' => $params['tid']));

		if ($params['from'] == 'return') {
			//积分变更
			$this->setOrderCredit($params['tid']);
			//邮件提醒
			if (!empty($this->module['config']['noticeemail'])) {
				$order = pdo_fetch("SELECT `price`, `paytype`, `from_user`, `addressid` FROM " . tablename('xcommunity_shopping_order') . " WHERE id = '{$params['tid']}'");
				$ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename('xcommunity_shopping_order_goods') . " WHERE orderid = '{$params['tid']}'", array(), 'goodsid');
				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total FROM " . tablename('xcommunity_shopping_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
				$address = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_address') . " WHERE id = :id", array(':id' => $order['addressid']));
				$body = "<h3>购买商品清单</h3> <br />";
				if (!empty($goods)) {
					foreach ($goods as $row) {
						$body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
					}
				}
				$paytype = $order['paytype'] == '3' ? '货到付款' : '已付款';
				$body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
				$body .= "<h3>购买用户详情</h3> <br />";
				$body .= "真实姓名：{$address['realname']} <br />";
				$body .= "地区：{$address['province']} - {$address['city']} - {$address['area']}<br />";
				$body .= "详细地址：{$address['address']} <br />";
				$body .= "手机：{$address['mobile']} <br />";
				load()->func('communication');
				ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
			}

			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('shopping',array('op' => 'myorder')), 'success');
			}
		}
	}
	private function changeWechatSend($id, $status, $msg = '') {
		global $_W;
		$paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
		if (!empty($paylog['openid'])) {
			$paylog['tag'] = iunserializer($paylog['tag']);
			$acid = $paylog['tag']['acid'];
			$account = account_fetch($acid);
			$payment = uni_setting($account['uniacid'], 'payment');
			if ($payment['payment']['wechat']['version'] == '2') {
				return true;
			}
			$send = array(
					'appid' => $account['key'],
					'openid' => $paylog['openid'],
					'transid' => $paylog['tag']['transaction_id'],
					'out_trade_no' => $paylog['plid'],
					'deliver_timestamp' => TIMESTAMP,
					'deliver_status' => $status,
					'deliver_msg' => $msg,
			);
			$sign = $send;
			$sign['appkey'] = $payment['payment']['wechat']['signkey'];
			ksort($sign);
			$string = '';
			foreach ($sign as $key => $v) {
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$send['app_signature'] = sha1(rtrim($string, '&'));
			$send['sign_method'] = 'sha1';
			$account = WeAccount::create($acid);
			$response = $account->changeOrderStatus($send);
			if (is_error($response)) {
				message($response['message']);
			}
		}
	}
	//模板消息通知提醒
	public function sendtpl($openid,$url,$template_id,$content){
		global $_GPC,$_W;
		load()->classs('weixin.account');
		load()->func('communication');
		$obj = new WeiXinAccount();
		$access_token = $obj->fetch_available_token();
		$data = array(
				'touser' => $openid,
				'template_id' => $template_id,
				'url' => $url,
				'topcolor' => "#FF0000",
				'data' => $content,
			);
		$json = json_encode($data);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		$ret = ihttp_post($url,$json);

    }
}







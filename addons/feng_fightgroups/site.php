<?php
/**
 * 拼团模块微站定义
 *
 * @author www.zheyitianShi.Com
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Feng_fightgroupsModuleSite extends WeModuleSite {
	//会员信息提取
	public function __construct(){
		global $_W;
		load()->model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
		if (empty($profile)) {
			$userinfo = mc_oauth_userinfo();
			if (!empty($userinfo['avatar'])) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'from_user' => $userinfo['openid'],
					'nickname' => $userinfo['nickname'],
					'avatar' => $userinfo['avatar']
				);
				$member = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$userinfo['openid']}'");
				if (empty($member['id'])) {
					pdo_insert('tg_member', $data);
				}else{
					pdo_update('tg_member', $data, array('id' =>$member['id']));
				}
			}
		}
	}

	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为微信端页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	public function checkpay() {
		global $_GPC, $_W;
		$orders = pdo_fetchall("select * from".tablename('tg_order')."where uniacid={$_W['uniacid']} and status = 0 ORDER BY `id` DESC LIMIT 0 , 5");
		foreach ($orders as $key => $value) {
			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') ."WHERE tid = '{$value['orderno']}' AND uniacid = '{$_W['uniacid']}' AND type = 'wechat'");
			$tag = iunserializer($log['tag']);
			$params['result'] = $log['status'] == '1' ? 'success' : 'failed';
			$params['type'] = $log['type'];
			$params['tid'] = $log['tid'];
			$params['uniontid'] = $log['uniontid'];
			$params['user'] = $log['openid'];
			$params['fee'] = $log['fee'];
			$params['tag'] = $tag;
			$data = array('status' => $params['result'] == 'success' ? 1 : 0);
			$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
			$data['pay_type'] = $paytype[$params['type']];
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
			}
			if($params['result'] == 'success'){
				$data['ptime'] = TIMESTAMP;
				$data['starttime'] = TIMESTAMP;
			}
			pdo_update('tg_order', $data, array('id' => $value['id']));
		}
	}
	//支付结果对比
	public function doMobilePay_match() {
		global $_GPC, $_W;
		$orderno = $_GPC['orderno'];
		$type = $_GPC['type'];
		$params = $_GPC['params'];
		$tuan_id = $_GPC['tuan_id'];
		$pay_info = pdo_fetch("select * from".tablename('core_paylog')."where uniacid={$_W['uniacid']} and tid='{$orderno}'");
		$order = pdo_fetch("select * from".tablename('tg_order')."where uniacid={$_W['uniacid']} and orderno= '{$orderno}' ");
		$transidinfo = iunserializer($pay_info['tag']);
		$transid = $transidinfo['transaction_id'];
		if($order['pay_type']==2){
			if(empty($order['transid'])){
				$norder['transid'] = $transid;
				$norder['status'] = 1;
				$norder['ptime'] = TIMESTAMP;
				pdo_update('tg_order', $norder, array('id' => $order['id']));
			}
		}
		if ($params == 0) {
			if($type == 'single'){
				echo "<script>alert(' 支付成功!');location.href='".$this->createMobileUrl('myorder')."';</script>";
				exit;
			}else{
				echo "<script>alert(' 支付成功!'); location.href='".$this->createMobileUrl('group',array('tuan_id' => $tuan_id))."';</script>";
				exit;
			}
		} else {
			if($tuan_id['is_tuan'] == 0){
				echo "<script>alert(' 支付成功!'); location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('myorder')."';</script>";
				exit;
			}else{
				echo "<script> alert(' 支付成功!');location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('group',array('tuan_id' => $tuan_id))."';</script>";
				exit;
			}
		}
	}
	
	public function doMobileUser_refund() {
		global $_GPC, $_W;
		include_once '../addons/feng_fightgroups/WxPay.Api.php';
		$transid = $_GPC['transid'];
		$WxPayApi = new WxPayApi();
		$input = new WxPayRefund();
		load() -> func('communication');
		load()->model('account');
		$accounts = uni_accounts();
		$path_cert = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_cert.pem';//证书路径
		$path_key = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_key.pem';//证书路径
		$key=$this->module['config']['apikey'];//商户支付秘钥（API秘钥）
		$account_info=pdo_fetch("select * from".tablename('account_wechats')."where uniacid={$_W['uniacid']}");//身份标识（appid）
		$appid = $account_info['key'];
		//$appsecret = $accounts[$acid]['secret'];//身份密钥（appsecret）
		$mchid=$this->module['config']['mchid'];//微信支付商户号(mchid)
		$order_out = pdo_fetch("select * from".tablename('tg_order') . "where transid = '{$transid}'");
		$fee = $order_out['price']*100;//退款金额
		$refundid = $transid;//微信订单号
		//message("key=".$key."appid=".$appid."mchid=".$mchid."fee=".$fee."refundid=".$refundid);exit;
		/*$input：退款必须要的参数*/
		$input->SetAppid($appid);
		$input->SetMch_id($mchid);
		$input->SetOp_user_id($mchid);
		$input->SetOut_refund_no($mchid.date("YmdHis"));
		$input->SetRefund_fee($fee);
		$input->SetTotal_fee($fee);
		$input->SetTransaction_id($refundid);
		$result=$WxPayApi->refund($input,6,$path_cert,$path_key,$key);
		pdo_insert('tg_refund_record',array('transid'=>$transid,'createtime'=>TIMESTAMP,'status'=>0));
		if($result['return_code'] == 'SUCCESS'){
			pdo_update('tg_order', array('status' => 4), array('transid' => $transid));
			pdo_update('tg_refund_record', array('status' => 1), array('transid' => $transid));
			echo "<script>alert('退款成功!');location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('mygroup')."';</script>";
			exit;
		}else{
			echo "<script>alert('服务器正忙，请稍后退款!');location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('mygroup')."';</script>";
			exit;
		}
	}

	public function doMobileMore_refund() {
		global $_GPC, $_W;
		include_once '../addons/feng_fightgroups/WxPay.Api.php';
		$transid = $_GPC['transid'];
		$WxPayApi = new WxPayApi();
		$input = new WxPayRefund();
		load() -> func('communication');
		load()->model('account');
		$accounts = uni_accounts();
		$acid = $_W['uniacid'];
		$path_cert = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_cert.pem';//证书路径
		$path_key = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_key.pem';//证书路径
		$key=$this->module['config']['apikey'];//商户支付秘钥（API秘钥）
		$account_info=pdo_fetch("select * from".tablename('account_wechats')."where uniacid={$_W['uniacid']}");//身份标识（appid）
		$appid = $account_info['key'];//身份标识（appid）
//	 	$appsecret = $accounts[$acid]['secret'];//身份密钥（appsecret）
		$mchid=$this->module['config']['mchid'];//微信支付商户号(mchid)
		$order_out = pdo_fetch("select * from".tablename('tg_order') . "where transid = '{$transid}'");
		$fee = $order_out['price']*100;//退款金额
		$refundid = $transid;//微信订单号
		//message("key=".$key."appid=".$appid."mchid=".$mchid."fee=".$fee."refundid=".$refundid);exit;
		/*$input：退款必须要的参数*/
		$input->SetAppid($appid);
		$input->SetMch_id($mchid);
		$input->SetOp_user_id($mchid);
		$input->SetOut_refund_no($mchid.date("YmdHis"));
		$input->SetRefund_fee($fee);
		$input->SetTotal_fee($fee);
		$input->SetTransaction_id($refundid);
		$result=$WxPayApi->refund($input,6,$path_cert,$path_key,$key);
		pdo_insert('tg_refund_record',array('transid'=>$transid,'createtime'=>TIMESTAMP,'status'=>0));
		if($result['return_code'] == 'SUCCESS'){
			pdo_update('tg_order', array('status' => 4), array('transid' => $transid));
			pdo_update('tg_refund_record', array('status' => 1), array('transid' => $transid));
			echo "<script>alert('该团人数已满,我们已退还您的金额!');location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('index')."';</script>";
			exit;
		}else{
			echo "<script>alert('该团人数已满,我们尽快退还您的金额!');location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('index')."';</script>";
			exit;
		}
	}

	//微信端填写收货地址页面
	public function doMobileCreateAdd() {
		global $_GPC, $_W;
        $groupnum=$_GPC['groupnum'];
        $g_id = intval($_GPC['g_id']);
        $tuan_id = intval($_GPC['tuan_id']);
        $share_data = $this->module['config'];
        $all = array('g_id' =>$g_id,'groupnum' =>$groupnum);
    	$operation = $_GPC['op'];
        $id=$_GPC['id'];
        $weid = $_W['uniacid'];
        $openid = $_W['openid'];
    	if ($operation == 'display') {
            if($id){
                $addres = pdo_fetch("SELECT * FROM " . tablename('tg_address')."where id={$id}");
                if(!empty($groupnum)){
                    $addresschange = 1;
                } 
            }  		
        }elseif($operation == 'conf'){
            if(!empty($all)){
                $con = 1;
            } 
        }elseif ($operation == 'post') { 
	        if(!empty($id)){
	            $status = pdo_fetch("SELECT * FROM " . tablename('tg_address')."where id={$id}");
	            $data=array(
	                'openid' => $openid,
	                'uniacid'=>$weid,
	                'cname'=>$_GPC['lxr_val'],
	                'tel'=>$_GPC['mobile_val'],
	                'province'=>$_GPC['province_val'],
	                'city'=>$_GPC['city_val'],
	                'county'=>$_GPC['area_val'],
	                'detailed_address'=>$_GPC['address_val'],
	                'status'=>$status['status'],
	                'addtime'=>time()
	            );
	            if(pdo_update('tg_address',$data,array('id' => $id))){ 
	            	echo 1;
	            	exit;
	            }else{   
	                echo 0;
	                exit;
	            }
	        }else{
	            $data1=array(
		            'openid' => $openid,
		            'uniacid'=>$weid,
		            'cname'=>$_GPC['lxr_val'],
		            'tel'=>$_GPC['mobile_val'],
		            'province'=>$_GPC['province_val'],
		            'city'=>$_GPC['city_val'],
		            'county'=>$_GPC['area_val'],
		            'detailed_address'=>$_GPC['address_val'],
		            'status'=>'1',
		            'addtime'=>time()
	        	);
	        	$moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
	        	pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
	            if(pdo_insert('tg_address',$data1)){
	            	echo 1;
	            	exit;
	            }else{                      
	                echo 0;
	                exit;
	            }                 
	        }   
        }elseif($operation == 'deletes'){
        	if($id){
                if(pdo_delete('tg_address',array('id' => $id ))){
                    echo 1;
                    exit;
                }else{
                    echo 0;
                    exit;
                }        
            }else{
                echo 2;
                exit;
            }
        }elseif($operation == 'moren'){    
            if(!empty($id)){
                $moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
                pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
                if(pdo_update('tg_address',array('status' =>1),array('id' => $id))){
                    echo 1;
                    exit;
                }else{
                    echo 0;
                    exit;
                }
            }else{
                echo 2;
                exit; 
            }
        }
        include $this->template('createadd');
	}

	//我的团
	public function doMobileMyGroup() {
	    global $_W, $_GPC;
		$this->checkpay();
		$share_data = $this->module['config'];
		$orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and openid='{$_W['openid']}' and is_tuan = 1  order by ptime desc");
	    foreach ($orders as $key => $order) {
			$goods = pdo_fetch("SELECT * FROM ".tablename('tg_goods')."WHERE id = {$order['g_id']}");
			$orders[$key]['groupnum'] = $goods['groupnum'];
			$orders[$key]['gprice'] = $goods['gprice'];
			$orders[$key]['gid'] = $goods['id'];
			$orders[$key]['gname'] = $goods['gname'];
			$orders[$key]['gimg'] = $goods['gimg'];
	        $sql2 = "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status = 1";
	        $params2 = array(':tuan_id'=>$order['tuan_id']);
	        $alltuan = pdo_fetchall($sql2, $params2);
	        $item = array();
	        foreach ($alltuan as $num => $all) {
	        	$item[$num] = $all['id'];
	        }
	        $orders[$key]['itemnum'] = count($item);
	        $sql3="SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = :tuan_id and status = 1 and tuan_first =:tuan_first";
	        $params3  = array(':tuan_id' => $order['tuan_id'],':tuan_first'=>1);
	        $tuan_first_order = pdo_fetch($sql3,$params3);
	        $hours=$tuan_first_order['endtime'];
	        $time = time();
	        $date = date('Y-m-d H:i:s',$tuan_first_order['ptime']);
	        $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
	        $date1 = date('Y-m-d H:i:s',$time);
	        $orders[$key]['lasttime'] = strtotime($endtime)-strtotime($date1);
		}
		include $this->template('mygroup');
	}

	//微信端首页
	public function doMobileIndex() {
		global $_W, $_GPC;
		
		$share_data = $this->module['config'];
		if ($this->module['config']['mode'] == 1) {
			$pindex = max(1, intval($_GPC['page'])); //当前页码
			$psize = 10;	//设置分页大小                                                               
			$goodses = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 ORDER BY displayorder desc LIMIT ".(1-1)* $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_goods') . "WHERE uniacid = '{$_W['uniacid']}'"); //记录总数
			$pager = pagination($total, $pindex, $psize);
			include $this->template('simpindex');
		}else{
			$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
			if ($operation=='display') {
				$category = pdo_fetchall("SELECT * FROM " . tablename('tg_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
				foreach ($category as $key => $value) {
					if (!empty($value['description'])) {
						$pindex = max(1, intval($_GPC['page'])); //当前页码
						$psize = intval($value['description']);	//设置分页大小 
						$sqlmess = " LIMIT 0".','.$psize;
					}
					$category[$key]['goodses'] = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 AND fk_typeid = '{$value['id']}' ORDER BY displayorder DESC, id desc".$sqlmess);
				}
				//幻灯片
				$advs = pdo_fetchall("select * from " . tablename('tg_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
				foreach ($advs as &$adv) {
					if (substr($adv['link'], 0, 5) != 'http:') {
						$adv['link'] = "http://" . $adv['link'];
					}
				}
				unset($adv);
			}
			if ($operation=='search') {
				$condition = '';
				if (!empty($_GPC['gid'])) {
					$cid = intval($_GPC['gid']);
					$condition .= " AND fk_typeid = '{$cid}'";
				}
				if (!empty($_GPC['keyword'])) {
					$condition .= " AND gname LIKE '%{$_GPC['keyword']}%'";
				}
				$goodses = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 $condition ");
			}
			include $this->template('index');
		}
	}

	//组团详情
	public function doMobileGroup() {
		global $_W, $_GPC;
		$this->checkpay();
	  	$url=$_W['siteurl'];
	  	$tuan_id = intval($_GPC['tuan_id']);
	  	if(!empty($tuan_id)){
		  	$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
		  	$profileall = pdo_fetchall("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}'");
		    //取得该团所有订单
		    $orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and tuan_id = {$tuan_id} and is_tuan = 1 and status >= 1 and status <= 4");
		    //取一个订单$order
		    $order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE  tuan_id = {$tuan_id} and tuan_first=1");
		   //若没有参团则$myorder为空
		    $myorder = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE openid = '{$_W['openid']}' and tuan_id = {$tuan_id} and status = 1");
		  	if (empty($order['g_id'])) {
		  		echo "<script>alert('组团信息不存在！');location.href='".$this->createMobileUrl('index')."';</script>";
		  		exit;
		  	}else{
		  		$goods = pdo_fetch("SELECT * FROM".tablename('tg_goods')."WHERE id = {$order['g_id']}");
			    //该团购已有订单数count($item),已付款的订单
			    $sql= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status >= 1 and status <= 4 and pay_type <> 0";
			    $params= array(':tuan_id'=>$order['tuan_id']);
			    $alltuan = pdo_fetchall($sql, $params);
			    $item = array();
			    foreach ($alltuan as $num => $all) {
			    	$item[$num] = $all['id'];
			    }
			    //$n ：剩余人数，$nn 该团只有一人
			    $n = intval($goods['groupnum']) - count($item);
			    $nn = intval($goods['groupnum'])-1;
			    $arr = array();
			    for ($i=0; $i <$n ; $i++) { 
			    	$arr[$i]=0;
			    }
			    /*团是否过期*/
			    //团长订单
			    $tuan_first_order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = {$tuan_id} and tuan_first = 1");
			    $hours=$tuan_first_order['endtime'];
			    $time = time();
			    $date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
			    $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
			  
			    $date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
			    $lasttime2 = strtotime($endtime) - strtotime($date1);//剩余时间（秒数）
			    $lasttime = $tuan_first_order['endtime'];
		  	}
			$share_data = $this->module['config'];
			if($share_data['share_imagestatus'] == ''){
				$share_images = $goods['gimg'];
			}
			if($share_data['share_imagestatus'] == 1){
				$share_images = $goods['gimg'];
			}
			if($share_data['share_imagestatus'] == 2){
				$share_images = $profile['avatar'];
			}
			if($share_data['share_imagestatus'] == 3){
				$share_images =$this->module['config']['share_image'];
			}
		  	include $this->template('group');
	  	}else{
	  		echo "<script>alert('参数错误');location.href='".$this->createMobileUrl('index')."';</script>";
	  	}
	}

	//收藏AJAX
	public function doMobilecollect() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'remove';
		if ($operation=='add') {
			if (empty($_GPC['goodsid'])) {
				echo 0;
				exit;
			}else{
				$data=array(
	            'openid' => $_W['openid'],
	            'uniacid'=>$_W['uniacid'],
	            'sid'=>$_GPC['goodsid']
	            );
	            if (pdo_insert('tg_collect', $data)) {
	            	echo 1;
	            }else{
	            	echo 0;
	            }
			}
		}
		if ($operation=='remove') {
			if (empty($_GPC['goodsid'])) {
				echo 0;
				exit;
			}else{
				if (pdo_delete('tg_collect', array('uniacid' =>$_W['uniacid'], 'sid' => $_GPC['goodsid']))) {
					echo 1;
				}else{
					echo 0;
				}
			}
		}
	}

	//我的收藏
	public function doMobilefavorite() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$share_data = $this->module['config'];
		$favorite = pdo_fetchall("SELECT * FROM " . tablename('tg_collect') . " WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$_W['openid']}'");
		if (!empty($favorite)) {
			foreach ($favorite as $key => $value) {
				$goods = pdo_fetch("SELECT * FROM " . tablename('tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND id = '{$value['sid']}'");
				$favorite[$key]['goods'] = $goods;
			}
		}
		include $this->template('favorite');
	}
	//微信端商品详情页
	public function doMobilegooddetails() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端商品详情页ajax
	public function doMobileIndexAjax() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端团购流程详情页
	public function doMobileRules() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端填订单信息确认页面
	public function doMobileOrderConfirm() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端订单详情页面
	public function doMobileOrderDetails() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端订单页面
	public function doMobilemyOrder() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端取消订单
	public function doMobileCancelMyOrder() {
		global $_GPC, $_W;
		$orderno = $_GPC['orderno'];	 //订单现在的状态
		$openid = $_W['openid'];	//用户的openid
		//取消订单的操作
		if (!empty($orderno)) {
			$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE orderno=:id ';
			$params = array(':id'=>$orderno);
			$order = pdo_fetch($sql, $params);
			if(empty($order)){
				message('未找到指定订单.'.$orderno, $this->createMobileUrl('myorder'));
				$tip = 888;
			}else{
				$sql2 = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:gid ';
				$params2 = array(':gid'=>$order['g_id']);
				$goods = pdo_fetch($sql2, $params2);
				$sql3 = 'SELECT * FROM '.tablename('tg_address').' WHERE id=:aid ';
				$params3 = array(':aid'=>$order['addressid']);
				$address = pdo_fetch($sql3, $params3);
				$add = $address['province '].$address['city '].$address['county'].$address['detailed_address'];
				$ret = pdo_update('tg_order', array('status'=>9), array('orderno'=>$orderno));
				$tip = 9999;	
				$m_cancle=$this->module['config']['m_cancle'];
				$cancle=$this->module['config']['cancle'];
				$cancle_remark=$this->module['config']['cancle_remark'];
				$content = "取消订单通知";
				load()->func('communication');
				load()->model('account');
				$access_token = WeAccount::token();
				$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
				
				$url2="";//点击模板详情跳转的地址url2
				$time = date("Y-m-d H:i:s",time());
				$data['touser'] = trim($_W['openid']);
				$openid = trim($_W['openid']);
				$msg_json= '{
                   "touser":"'.$openid.'",
                   "template_id":"'.$m_cancle.'",
                   "url":"'.$url2.'",
                   "topcolor":"#FF0000",
                   "data":{
                       "first":{
                           "value":"'.$cancle.'",
                           "color":"#000000"
                       },
                       "keyword5":{
							"value":"'.$order['price'].'元",
                       		"color":"#000000"
						},
                       "keyword3":{
							 "value":"'.$goods['gname'].'",
                       	     "color":"#000000"
						},
						 "keyword2":{
							 "value":"'.$_W['uniaccount']['name'].'",
                       	     "color":"#000000"
						},
						 "keyword1":{
							 "value":"'.$order['orderno'].'",
                       	     "color":"#000000"
						},
						 "keyword4":{
							 "value":"1\n",
                       	     "color":"#000000"
						},
                       "remark":{
                           "value":"'.$cancle_remark.'",
                           "color":"#0099FF"
                       }
                   }
               }' ;
			   include_once 'message.php';
			   $sendmessage = new WX_message();
			   $res=$sendmessage->WX_request($url,$msg_json);
			}
		}
		$this->doMobileMyOrder();	
	}
	
	//微信端确认收货
	public function doMobileConfirMreceipt() {
		$this -> __mobile(__FUNCTION__);
	}
	//微信端收货地址管理页面
	public function doMobileAddManage() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端个人中心页面
	public function doMobilePerson() {
		$this -> __mobile(__FUNCTION__);
	}
	//支付页面
	public function doMobilePay() {
		$this -> __mobile(__FUNCTION__);
	}

	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为后台页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	
	//后台商品管理页面
	public function doWebGoods() {
		$this -> __web(__FUNCTION__);
	}
	//自定义属性
	public function doWebParam() {
		$tag = random(32);
		global $_GPC;
		include $this->template('param');
	}
	
	//验证拼团模式
	public function checkmode() {
		if (empty($this->module['config']['mode'])) {
			message('请先设置拼团模式', "../web/index.php?c=profile&a=module&do=setting&m=feng_fightgroups", 'warning');
			exit;
		}
	}

	//商品分类
	public function doWebCategory() {
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('tg_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('tg_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('tg_category') . " WHERE id = '$id'");
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('tg_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb']
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('tg_category', $data, array('id' => $id));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
				} else {
					pdo_insert('tg_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('tg_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			pdo_delete('tg_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
	}

	//幻灯片管理
	public function doWebAdv() {
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('tg_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb']
				);
				if (!empty($id)) {
					pdo_update('tg_adv', $data, array('id' => $id));
				} else {
					pdo_insert('tg_adv', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('tg_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('tg_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
			}
			pdo_delete('tg_adv', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
	}

	//后台订单管理页面
	public function doWebOrder() {
		global $_W,$_GPC;
		load()->func('tpl');
		checklogin();
		$this->checkmode();
		$weid = $_W['uniacid'];
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];
			$is_tuan = $_GPC['is_tuan'];
			$condition = " o.uniacid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
			$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}
			if (!empty($_GPC['transid'])) {
				$condition .= " AND o.transid =  '{$_GPC['transid']}'";
			}
			if (!empty($_GPC['pay_type'])) {
				$condition .= " AND o.pay_type = '{$_GPC['pay_type']}'";
			} elseif ($_GPC['pay_type'] === '0') {
				$condition .= " AND o.pay_type = '{$_GPC['pay_type']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.orderno LIKE '%{$_GPC['keyword']}%'";
			}
			 if (!empty($_GPC['member'])) {
				$condition .= " AND (a.cname LIKE '%{$_GPC['member']}%' or a.tel LIKE '%{$_GPC['member']}%')";
			 }
			if ($status != '') {
				if($status == 1){
					$condition .= " AND o.status = '" . intval($status) . "' AND success = 0 ";
				}else{
					$condition .= " AND o.status = '" . intval($status) . "'";
				}
			}
			if ($is_tuan != '') {
				$pp = 1;
				$condition .= " AND o.is_tuan = 1";
			}
			$sql = "select o.* , a.cname,a.tel from ".tablename('tg_order')." o"." left join ".tablename('tg_address')." a on o.addressid = a.id ". " where $condition ORDER BY o.createtime DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);
			$paytype = array (
				'0' => array('css' => 'default', 'name' => '未支付'),
				'1' => array('css' => 'info', 'name' => '余额支付'),
				'2' => array('css' => 'success', 'name' => '在线支付'),
				'3' => array('css' => 'warning', 'name' => '货到付款')
			);
			$orderstatus = array (
				'9' => array('css' => 'default', 'name' => '已取消'),
				'-1' => array('css' => 'default', 'name' => '已关闭'),
				'4' => array('css' => 'default', 'name' => '已退款'),
				'0' => array('css' => 'danger', 'name' => '待付款'),
				'1' => array('css' => 'info', 'name' => '待发货'),
				'2' => array('css' => 'warning', 'name' => '待收货'),
				'3' => array('css' => 'success', 'name' => '已完成')
			);
			foreach ($list as &$value) {
				$s = $value['status'];
				$value['statuscss'] = $orderstatus[$value['status']]['css'];
				$value['status'] = $orderstatus[$value['status']]['name'];
				$value['css'] = $paytype[$value['pay_type']]['css'];
				if ($value['pay_type'] == 2) {
					if (empty($value['transid'])) {
						$value['paytype'] = '微信支付';
					} else {
						$value['paytype'] = '微信支付';
					}
				} else {
					$value['paytype'] = $paytype[$value['pay_type']]['name'];
				}
				$goodsss = pdo_fetch("select * from".tablename('tg_goods')."where id = '{$value['g_id']}'");
				$value['freight'] = $goodsss['freight'];
			}
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " o "." left join ".tablename('tg_address')." a on o.addressid = a.id "." WHERE $condition", $paras);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$is_tuan = intval($_GPC['is_tuan']);
			$item = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
					message('请输入快递单号！');
				}
				pdo_update('tg_order',array('status' => 2,'express' => $_GPC['express'],'expresssn' => $_GPC['expresssn'],),array('id' => $id));
				//发货提醒
				$m_send=$this->module['config']['m_send'];
				$send=$this->module['config']['send'];
				$send_remark=$this->module['config']['send_remark'];
				$content="亲，您的商品已发货!!!";
				load()->func('communication');
				load()->model('account');
				$access_token = WeAccount::token();
				$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
				
				$url2="";//点击模板详情跳转的地址url2
				$time = date("Y-m-d H:i:s",time());
				$openid = trim($item['openid']);
				$msg_json= '{
                       	"touser":"'.$openid.'",
                       	"template_id":"'.$m_send.'",
                       	"url":"'.$url2.'",
                       	"topcolor":"#FF0000",
                       	"data":{
                           	"first":{
                               "value":"'.$send.'",
                               "color":"#000000"
                           	},
                           	"keyword1":{
								"value":"'.$item['orderno'].'",
                           		"color":"#000000"
							},
                           	"keyword2":{
								 "value":"'.$_GPC['express'].'",
                           	     "color":"#000000"
							},
							"keyword3":{
								"value":"'.$_GPC['expresssn'].'",
                           	    "color":"#000000"
							},
                           	"remark":{
                               "value":"'.$send_remark.'",
                               "color":"#0099FF"
                           	}
                       	}
                   	}';
				   	include_once 'message.php';
				   	$sendmessage = new WX_message();
				   	$res=$sendmessage->WX_request($url,$msg_json);
					message('发货操作成功！', referer(), 'success');
				}
			if (checksubmit('cancelsend')) {
				// $item = pdo_fetch("SELECT transid FROM " . tablename('tg_order') . " WHERE id = :id", array(':id' => $id));
				// if (!empty($item['transid'])) {
				// 	$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				// }
				pdo_update('tg_order',array('status' => 1),array('id' => $id));
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				pdo_update('tg_order', array('status' => 3), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('refund')) {
				include_once '../addons/feng_fightgroups/WxPay.Api.php';
				$WxPayApi = new WxPayApi();
				$input = new WxPayRefund();
				load()->model('account');
				load() -> func('communication');
				$accounts = uni_accounts();
				$acid = $_W['uniacid'];
				$path_cert = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_cert.pem';//证书路径
				$path_key = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_key.pem';//证书路径
				$key=$this->module['config']['apikey'];//商户支付秘钥（API秘钥）
				$account_info=pdo_fetch("select * from".tablename('account_wechats')."where uniacid={$_W['uniacid']}");//身份标识（appid）
				$appid = $account_info['key'];//身份标识（appid）
	 			$mchid=$this->module['config']['mchid'];//微信支付商户号(mchid)
				$refund_id = $_GPC['refund_id'];//页面获取的退款订单号
				$refund_ids = pdo_fetch("select * from".tablename('tg_order')."where id={$refund_id}");
				$fee = $refund_ids['price']*100;//退款金额
				$refundid = $refund_ids['transid'];//微信订单号
				/*$input：退款必须要的参数*/
				$input->SetAppid($appid);
				$input->SetMch_id($mchid);
				$input->SetOp_user_id($mchid);
				$input->SetOut_refund_no($mchid.date("YmdHis"));
				$input->SetRefund_fee($fee);
				$input->SetTotal_fee($fee);
				$input->SetTransaction_id($refundid);
				$result=$WxPayApi->refund($input,6,$path_cert,$path_key,$key);

				pdo_insert('tg_refund_record',array('transid'=>$refundid,'createtime'=>TIMESTAMP,'status'=>0));
				if($result['return_code'] == 'SUCCESS'){
					pdo_update('tg_refund_record', array('status' => 1), array('transid' => $refund_id));
					pdo_update('tg_order', array('status' => 4), array('id' => $refund_id));
					$m_ref=$this->module['config']['m_ref'];
					$ref=$this->module['config']['ref'];
					$ref_remark=$this->module['config']['ref_remark'];
					$content = "您已成退款成功";
					$access_token = WeAccount::token();
					$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
					$url2="";//点击模板详情跳转的地址url2
					$time = date("Y-m-d H:i:s",time());
					$data['touser'] = trim($_W['openid']);
					$openid = trim($refund_ids['openid']);
					$msg_json= '{
                       	"touser":"'.$openid.'",
                       	"template_id":"'.$m_ref.'",
                       	"url":"'.$url2.'",
                       	"topcolor":"#FF0000",
                       	"data":{
                           	"first":{
                               "value":"'.$ref.'",
                               "color":"#000000"
                           	},
                           	"reason":{
								"value":"购买失败",
                           		"color":"#000000"
							},
                           	"refund":{
								"value":"'.$refund_ids['price'].'",
                           	    "color":"#000000"
							},
                           	"remark":{
                               "value":"'.$ref_remark.'",
                               "color":"#0099FF"
                           	}
                       	}
                   	}';
					include_once 'message.php';
					$sendmessage = new WX_message();
					$res=$sendmessage->WX_request($url,$msg_json);
					pdo_query("update".tablename('tg_goods')." set gnum=gnum+1 where id = '{$refund_ids['g_id']}'");
					message('退款成功了！', referer(), 'success');
				}else{
					message('退款失败，服务器正忙，请稍等等！', referer(), 'fail');
				}
			}
			if (checksubmit('cancel')) {
				pdo_update('tg_order', array('status' => 1), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('tg_order', array('status' => 0), array('id' => $id));
				// //设置库存
				// $this->setOrderStock($id, false);
				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('tg_order', array('status' => 1, 'pay_type' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
				// //设置库存
				// $this->setOrderStock($id);
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('close')) {
				pdo_update('tg_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}
			if (checksubmit('open')) {
				pdo_update('tg_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}
			// $dispatch = pdo_fetch("SELECT * FROM " . tablename('shopping_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			// if (!empty($dispatch) && !empty($dispatch['express'])) {
			// 	$express = pdo_fetch("select * from " . tablename('shopping_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			// }
			$item['user'] = pdo_fetch("SELECT * FROM " . tablename('tg_address') . " WHERE id = {$item['addressid']}");
			$goods = pdo_fetchall("select * from" . tablename('tg_goods') ."WHERE id={$item['g_id']}");
			$item['goods'] = $goods;
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			$tuan_id = intval($_GPC['tuan_id']);
			if(!empty($tuan_id)){
	            if(pdo_delete('tg_order', array('tuan_id' => $tuan_id))){
	             	message('团订单删除成功', $this->createWebUrl('order', array('op' => 'tuan')), 'success');
	            }	
			}
			if (pdo_delete('tg_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		} elseif ($operation == 'tuan') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$is_tuan = $_GPC['is_tuan'];
			$condition = "uniacid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND tuan_id LIKE '%{$_GPC['keyword']}%'";
			}
			if ($is_tuan != '') {
				$condition .= " AND is_tuan = 1";
			}
			$sql = "select DISTINCT tuan_id from".tablename('tg_order')."where $condition order by createtime desc ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$tuan_id = pdo_fetchall($sql,$paras);
			foreach ($tuan_id as $key => $tuan) {
				$alltuan = pdo_fetchall("select * from".tablename('tg_order')."where tuan_id={$tuan['tuan_id']}");
				$ite1 = array();
				$ite2 = array();
				$ite3 = array();
				$ite4 = array();
				$ite0 = array();
            	foreach ($alltuan as $num => $all) {
            		if ($all['status']==0){
              			$ite0[$num] = $all['id'];
            		}
            		if ($all['status']==1){
              			$ite1[$num] = $all['id'];
            		}
            		if ($all['status']==2){
              			$ite2[$num] = $all['id'];
            		}
            		if ($all['status']==3){
              			$ite3[$num] = $all['id'];
            		}
            		if ($all['status']==4){
              			$ite4[$num] = $all['id'];
            		}
              		$goods = pdo_fetch("select * from".tablename('tg_goods')."where id = {$all['g_id']}");
              	}
              	$tuan_id[$key]['itemnum0'] = count($ite0);
              	$tuan_id[$key]['itemnum1'] = count($ite1);
              	$tuan_id[$key]['itemnum2'] = count($ite2);
              	$tuan_id[$key]['itemnum3'] = count($ite3);
              	$tuan_id[$key]['itemnum4'] = count($ite4);
              	$tuan_id[$key]['tsucc'] = count($ite1) + count($ite2) + count($ite3);
             	$tuan_id[$key]['groupnum'] = $goods['groupnum'];
              	$tuan_first_order = pdo_fetch("SELECT * FROM".tablename('tg_order')."where tuan_id={$tuan['tuan_id']} and tuan_first = 1");
              	$hours=$tuan_first_order['endtime'];
              	$time = time();
              	$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
              	$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
              	$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
              	$lasttime = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
              	$tuan_id[$key]['lasttime'] = $lasttime;
			}
			$total2 = pdo_fetchall("select DISTINCT tuan_id from".tablename('tg_order')."where $condition",$paras);
			$total = count($total2);
			$pager = pagination($total, $pindex, $psize);			
		} elseif ($operation == 'tuan_detail'){
			$tuan_id = intval($_GPC['tuan_id']);//指定团的id
			$is_tuan = intval($_GPC['is_tuan']);
			$orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = {$tuan_id}");
			$ite1 = array();
			$ite2 = array();
			$ite3 = array();
			$ite4 = array();
			$ite0 = array();
			foreach ($orders as $key => $order) {
				if ($order['status']==0){
          			$ite0[$key] = $order['id'];
        		}
        		if ($order['status']==1){
          			$ite1[$key] = $order['id'];
        		}
        		if ($order['status']==2){
          			$ite2[$key] = $order['id'];
        		}
        		if ($order['status']==3){
          			$ite3[$key] = $order['id'];
        		}
        		if ($order['status']==4){
          			$ite4[$key] = $order['id'];
        		}
		        $address = pdo_fetch("SELECT * FROM".tablename('tg_address')."where id={$order['addressid']}");
		        $orders[$key]['cname'] = $address['cname'];
		        $orders[$key]['tel'] = $address['tel'];
		        $orders[$key]['province'] = $address['province'];
		        $orders[$key]['city'] = $address['city'];
		        $orders[$key]['county'] = $address['county'];
		        $orders[$key]['detailed_address'] = $address['detailed_address'];
		        $goods = pdo_fetch("select * from".tablename('tg_goods')."where id={$order['g_id']}");	
				$orders[$key]['freight'] = $goods['freight'];
			}
			$num = count($orders);
			$goodsid  = array();
			foreach ($orders as $key => $value) {
				$goodsid['id'] = $value['g_id'];
			}
			$goods2 = pdo_fetch("SELECT * FROM " . tablename('tg_goods') . " WHERE id = {$goodsid['id']}");
			if (empty($orders)) {
				message("抱歉，该团购不存在!", referer(), "error");
			}
			$goods2['itemnum0'] = count($ite0);
            $goods2['itemnum1'] = count($ite1);
            $goods2['itemnum2'] = count($ite2);
            $goods2['itemnum3'] = count($ite3);
            $goods2['itemnum4'] = count($ite4);
            $goods2['tsucc'] = count($ite1) + count($ite2) + count($ite3);
			foreach ($orders as $key => $value) {
				$it['status'] = $value['status'];
			}
			//是否过期
			$sql2= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and tuan_first = :tuan_first";
			$params2 = array(':tuan_id'=>$tuan_id,':tuan_first'=>1);
			$tuan_first_order = pdo_fetch($sql2, $params2);
			$hours=$tuan_first_order['endtime'];
			$time = time();
			$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
			$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
			$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
			$lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
			//确认发货
			if (checksubmit('confirmsend')) {
				pdo_update('tg_order',array('status' => 2),array('tuan_id' => $tuan_id));
				message('发货操作成功！', referer(), 'success');
			}
			//取消发货
			if (checksubmit('cancelsend')) {
				pdo_update('tg_order',array('status' => 1),array('tuan_id' => $tuan_id));
				message('取消发货操作成功！', referer(), 'success');
			}
			//确认完成订单
			if (checksubmit('finish')) {
				pdo_update('tg_order', array('status' => 3), array('tuan_id' => $tuan_id));
				message('订单操作成功！', referer(), 'success');
			}
			//取消完成订单（状态为已支付）
			if (checksubmit('cancel')) {
				pdo_update('tg_order', array('status' => 1), array('tuan_id' => $tuan_id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			//取消支付
			if (checksubmit('cancelpay')) {
				pdo_update('tg_order', array('status' => 0), array('tuan_id' => $tuan_id));
				message('取消团订单付款操作成功！', referer(), 'success');
			}
			//确认支付
			if (checksubmit('confrimpay')) {
				pdo_update('tg_order', array('status' => 1, 'pay_type' => 2),  array('tuan_id' => $tuan_id));
				message('团订单付款操作成功！', referer(), 'success');
			}
		}elseif($operation == 'import'){
		     //wangxa 增加 excel导入功能
		     
	      	$file=$_FILES['fileName'];
          	$max_size="2000000";
          	$fname=$file['name'];
          	$ftype=strtolower(substr(strrchr($fname,'.'),1));
          //文件格式
          	$uploadfile=$file['tmp_name'];
          
          	if($_SERVER['REQUEST_METHOD']=='POST'){
              	if(is_uploaded_file($uploadfile)){
                   	if($file['size']>$max_size){
                     	echo "Import file is too large"; 
                     	exit;
                   	}
                   	if($ftype!='xls'){
                     	echo "文件后缀格式必须为xls";
                     	exit;   
                   	}
               	}else{
                 	echo "文件名不能为空!";
                 	exit; 
               	} 
          	}
          	include_once 'PHPExcel.php'; 
//            	include_once 'PHPExcel\IOFactory.php';
//            	include_once 'PHPExcel\Reader\Excel5.php';
          	$objReader = PHPExcel_IOFactory::createReader('Excel5');
          	$objPHPExcel = $objReader->load($uploadfile); 
          	$sheet = $objPHPExcel->getSheet(0); 
          	$highestRow = $sheet->getHighestRow();
          	$succ_result=0;
          	$error_result=0;
          	for($j=2;$j<=$highestRow;$j++){
              	$orderNo =  $objPHPExcel->getActiveSheet()->getCell("A$j")->getValue();
              	$expressOrder =  $objPHPExcel->getActiveSheet()->getCell("J$j")->getValue();
              	$expressName  =  $objPHPExcel->getActiveSheet()->getCell("K$j")->getValue();
              	if (!empty($expressOrder) && !empty($expressName)) {
                 	pdo_update('tg_order', array('status' => 2,	'express' => $expressName,'expresssn' => $expressOrder), array('orderno' =>$orderNo));
		          	$succ_result+=1;
					//发货提醒
					$m_send=$this->module['config']['m_send'];
					$send=$this->module['config']['send'];
					$send_remark=$this->module['config']['send_remark'];
					$content="亲，您的商品已发货!!!";
					load()->func('communication');
					load()->model('account');
					$access_token = WeAccount::token();
					$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
					
					$url2="";//点击模板详情跳转的地址url2
					$time = date("Y-m-d H:i:s",time());
					$order = pdo_fetch("select * from".tablename('tg_order')."where orderno={$orderNo}");
					$openid = trim($order['openid']);
					$msg_json= '{
	                   	"touser":"'.$openid.'",
	                   	"template_id":"'.$m_send.'",
	                   	"url":"'.$url2.'",
	                   	"topcolor":"#FF0000",
	                   	"data":{
	                       	"first":{
	                           "value":"'.$send.'",
	                           "color":"#000000"
	                       	},
	                       	"keyword1":{
								"value":"'.$order['orderno'].'",
	                       		"color":"#000000"
							},
	                       	"keyword2":{
								 "value":"'.$order['express'].'",
	                       	     "color":"#000000"
							},
							"keyword3":{
								"value":"'.$order['expresssn'].'",
	                       	    "color":"#000000"
							},
	                       	"remark":{
	                           "value":"'.$send_remark.'",
	                           "color":"#0099FF"
	                       	}
	                   	}
	               	}';
				   	include_once 'message.php';
				   	$sendmessage = new WX_message();
				   	$res=$sendmessage->WX_request($url,$msg_json);
					//结束
              	}else{
                  	if(!empty($orderNo)){
                  		$error_result+=1;
                  	}
                }
            }
		    message('导入发货订单操作成功！成功'.$succ_result.'条，失败'.$error_result.'条', referer(), 'success'); 
		}elseif($operation == 'output'){
			$status = $_GPC['status'];
			$istuan = $_GPC['istuan'];
			$condition=" uniacid={$weid}";
			if($status != ''){
				if($status == 1){
					$orderss = pdo_fetchall("select * from".tablename('tg_order')."where uniacid = '{$_W['uniacid']}' AND is_tuan = 1 AND tuan_first = 1 AND status = 1");
					foreach ($orderss as $key => $value) {
						$goods = pdo_fetch("select * from".tablename('tg_goods')."where id = {$value['g_id']}");
						$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . "WHERE uniacid = '{$_W['uniacid']}' AND tuan_id = '{$value['tuan_id']}' AND status = 1 "); 
						if ($goods['gnum'] == $total) {
							pdo_update('tg_order',array('success'=>0),array('tuan_id'=>$value['tuan_id'],'status'=>1));
						}
					}
					$condition .= " AND status = '" . intval($status) . "' AND success = 0 ";
				}else{
					$condition .= " AND status = '" . intval($status) . "'";
				}
				$condition .= " AND status = '" . intval($status) . "'";
			}
			if($istuan != ''){
				
				$condition .= " AND is_tuan = '" . intval($istuan) . "'";
			}
			$orders = pdo_fetchall("select * from".tablename('tg_order')."where $condition");
			error_reporting(E_ALL);
			date_default_timezone_set('Asia/Shanghai');
			include_once 'PHPExcel.php';
			$objPHPExcel=new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator('http://www.phpernote.com')
						->setLastModifiedBy('http://www.phpernote.com')
						->setTitle('Office 2007 XLSX Document')
						->setSubject('Office 2007 XLSX Document')
						->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
						->setKeywords('office 2007 openxml php')
						->setCategory('Result file');
			if($istuan !=''){
				$sql = "select DISTINCT tuan_id from".tablename('tg_order')."where $condition order by createtime desc";
				$tuan_id = pdo_fetchall($sql);
				foreach ($tuan_id as $key => $tuan) {
					$alltuan = pdo_fetchall("select * from".tablename('tg_order')."where tuan_id={$tuan['tuan_id']}");
					$ite1 = array();
					$ite2 = array();
					$ite3 = array();
					$ite4 = array();
					$ite0 = array();
	            	foreach ($alltuan as $num => $all) {
	            		if ($all['status']==0){
	              			$ite0[$num] = $all['id'];
	            		}
	            		if ($all['status']==1){
	              			$ite1[$num] = $all['id'];
	            		}
	            		if ($all['status']==2){
	              			$ite2[$num] = $all['id'];
	            		}
	            		if ($all['status']==3){
	              			$ite3[$num] = $all['id'];
	            		}
	            		if ($all['status']==4){
	              			$ite4[$num] = $all['id'];
	            		}
	              		$goods = pdo_fetch("select * from".tablename('tg_goods')."where id = {$all['g_id']}");
	              	}
	              	$tuan_id[$key]['itemnum0'] = count($ite0);
	              	$tuan_id[$key]['itemnum1'] = count($ite1);
	              	$tuan_id[$key]['itemnum2'] = count($ite2);
	              	$tuan_id[$key]['itemnum3'] = count($ite3);
	              	$tuan_id[$key]['itemnum4'] = count($ite4);
	              	$tuan_id[$key]['tsucc'] = count($ite1) + count($ite2) + count($ite3);
	             	$tuan_id[$key]['groupnum'] = $goods['groupnum'];
	              	$tuan_first_order = pdo_fetch("SELECT * FROM".tablename('tg_order')."where tuan_id={$tuan['tuan_id']} and tuan_first = 1");
	              	$hours=$tuan_first_order['endtime'];
	              	$time = time();
	              	$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
	              	$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
	              	$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
	              	$lasttime = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
	              	$tuan_id[$key]['lasttime'] = $lasttime;
				}
				$objPHPExcel->setActiveSheetIndex(0)
			            	->setCellValue('A1','团编号')
			            	->setCellValue('B1','团状态')
							->setCellValue('C1','团购商品')
							->setCellValue('D1','团购价格');
				$i=2;			
				foreach($tuan_id as $k=>$item){
					if ($item['lasttime'] > 0) {
						if ($item['tsucc'] == $item['groupnum']){
							$content ="组团成功(共需".$item['groupnum']."人)【待发货".$item['itemnum1']."人，已发货".$item['itemnum2']."人】";
						}
						if ($item['tsucc'] < $item['groupnum']){
							$content="组团中(共需".$item['groupnum']."人)【已付款".$item['itemnum1']."人，还差".$item['groupnum']-$item['tsucc']."人】";
						}
					}else{
					    if ($item['tsucc'] == $item['groupnum']){
					    	$content ="组团成功(共需".$item['groupnum']."人)【待发货".$item['itemnum1']."人，已发货".$item['itemnum2']."人】";
					    }else{
					    	$content="团购失败，团购已过期(共需".$item['groupnum']."人)【待退款".$item['itemnum1']."人，已退款".$item['itemnum4']."人】";
					    }
					}
					$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValue('A'.$i,$item['tuan_id'])
				            ->setCellValue('B'.$i,$content)
							->setCellValue('C'.$i,$goods['gname'])
							->setCellValue('D'.$i,$goods['gprice']);
					$i++;
				}
				$filename=urlencode('团订单信息统计表').'_'.date('Y-m-dHis');
			}else{
				$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValue('A1','订单编号')
				            ->setCellValue('B1','姓名')
				            ->setCellValue('C1','电话')
				            ->setCellValue('D1','总价(元)')
							->setCellValue('E1','状态')
							->setCellValue('F1','下单时间')
							->setCellValue('G1','商品名称')
							->setCellValue('H1','收货地址')
							->setCellValue('I1','微信订单号')
							->setCellValue('J1','快递单号')
							->setCellValue('K1','快递名称');
				$i=2;			
				foreach($orders as $k=>$v){
					$user = pdo_fetch("select * from".tablename('tg_address')."where id={$v['addressid']}");
					$address = $user['province'].$user['city'].$user['county'].$user['detailed_address'];
					$goods = pdo_fetch("select * from".tablename('tg_goods')."where id = {$v['g_id']}");
					if($user){
						$name = $user['cname'];
						$tel = $user['tel'];
					}else{
						$name='';
						$tel='';
					}
					if($v['status'] == 0){
						$statuss = '待付款';
					}elseif($v['status'] == 1){
						$statuss = '待发货';
					}elseif($v['status'] == 2){
						$statuss = '已发货';
					}elseif($v['status'] == 3){
						$statuss = '已完成';
					}elseif($v['status'] == 9){
						$statuss = '已取消';
					}elseif($v['status'] == 4){
						$statuss = '已退款';
					}elseif($v['status'] == -1){
						$statuss = '已关闭';
					}
					$orderno = $v['orderno'];
					$time = date('Y-m-d H:i:s', $v['createtime']);
					$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValueExplicit('A'.$i,$orderno,PHPExcel_Cell_DataType::TYPE_STRING)
				            ->setCellValueExplicit('B'.$i,$name)
				            ->setCellValueExplicit('C'.$i,$tel,PHPExcel_Cell_DataType::TYPE_STRING)
				            ->setCellValueExplicit('D'.$i,$v['price'])
							->setCellValueExplicit('E'.$i,$statuss)
							->setCellValueExplicit('F'.$i,$time)
							->setCellValueExplicit('G'.$i,$goods['gname'])
							->setCellValueExplicit('H'.$i,$address)
							->setCellValueExplicit('I'.$i,$v['transid'],PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValueExplicit('J'.$i,$v['expresssn'],PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValueExplicit('K'.$i,$v['express'],PHPExcel_Cell_DataType::TYPE_STRING);
					$i++;
				}
			}
			$objPHPExcel->getActiveSheet()->setTitle('拼团订单');
			$objPHPExcel->setActiveSheetIndex(0);
			if($status == ''){
				$filename=urlencode('全部订单信息统计表').'_'.date('Y-m-dHis');
			}elseif($status == 1){
				$filename=urlencode('待发货订单信息统计表').'_'.date('Y-m-dHis');
			}elseif($status == 2){
				$filename=urlencode('已发货订单信息统计表').'_'.date('Y-m-dHis');
			}elseif($status == 3){
				$filename=urlencode('已完成订单信息统计表').'_'.date('Y-m-dHis');
			}elseif($status == 9){
				$filename=urlencode('已取消订单信息统计表').'_'.date('Y-m-dHis');
			}else{
				$filename=urlencode('待付款订单信息统计表').'_'.date('Y-m-dHis');
			}
			//生成xls文件
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;	
		}elseif ($operation == 'refundall'){
			include_once '../addons/feng_fightgroups/WxPay.Api.php';
			$WxPayApi = new WxPayApi();
			
			load() -> func('communication');
			load()->model('account');
			$accounts = uni_accounts();
			$allorders = pdo_fetchall("select * from".tablename('tg_order')."where uniacid={$_W['uniacid']} and status = 1");
			$now = time();
			$num=0;
			foreach($allorders as $ke=>$value){
				$endtime = $value['endtime'];
				if($now-$value['starttime'] >$endtime*3600 && $value['transid'] != '' && $value['success']==1){
					$num++;
					$fee = $value['price']*100;
					$refundid = $value['transid'];
					$acid = $_W['uniacid'];
					$path_cert = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_cert.pem';//证书路径
					$path_key = IA_ROOT.'/addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_key.pem';//证书路径
					$key=$this->module['config']['apikey'];//商户支付秘钥（API秘钥）
					$appid=$accounts[$acid]['key'];//身份标识（appid）
		 			$mchid=$this->module['config']['mchid'];//微信支付商户号(mchid)
					/*$input：退款必须要的参数*/
					$input = new WxPayRefund();
					$input->SetAppid($appid);
					$input->SetMch_id($mchid);
					$input->SetOp_user_id($mchid);
					$input->SetOut_refund_no($mchid.date("YmdHis"));
					$input->SetRefund_fee($fee);
					$input->SetTotal_fee($fee);
					$input->SetTransaction_id($refundid);
					$result=$WxPayApi->refund($input,6,$path_cert,$path_key,$key);
					if($result['return_code'] == 'SUCCESS'){
						pdo_update('tg_order', array('status' => 4), array('id' => $value['id']));
						pdo_query("update".tablename('tg_goods')." set gnum=gnum+1 where id = '{$value['g_id']}'");
					}
					
				}
			}
			if($num==0){
				message('未找到已付款且团购过期的微信订单。', referer(), 'fail');
			}else{
				message('一键退款成功！共处理了'.$num.'个订单。', referer(), 'success');
			}
		}
		include $this->template('order');
	}

	//打印机管理
	public function doWebPrint() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);//订单ID
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'print_list';
       if($op == 'print_post') {
		if($id > 0) {
			$item = pdo_fetch('SELECT * FROM ' . tablename('tg_print') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
		} 
		if(empty($item)) {
			$item = array('status' => 1, 'print_nums' => 1);
		}
		if(checksubmit('submit')) {
			$data['status'] = intval($_GPC['status']); 
			$data['mode'] = intval($_GPC['mode']); 
			$data['name'] = !empty($_GPC['name']) ? trim($_GPC['name']) : message('打印机名称不能为空', '', 'error');
			$data['print_no'] = !empty($_GPC['print_no']) ? trim($_GPC['print_no']) : message('机器号不能为空', '', 'error');
			$data['member_code'] = $_GPC['member_code'];
			$data['key'] = !empty($_GPC['key']) ? trim($_GPC['key']) : message('打印机key不能为空', '', 'error');
			$data['print_nums'] = intval($_GPC['print_nums']) ? intval($_GPC['print_nums']) : 1;
			if(!empty($_GPC['qrcode_link']) && (strexists($_GPC['qrcode_link'], 'http://') || strexists($_GPC['qrcode_link'], 'https://'))) {
				$data['qrcode_link'] = trim($_GPC['qrcode_link']);
			}
			$data['uniacid'] = $_W['uniacid'];
			$data['sid'] = $sid;
			if(!empty($item) && $id) {
				pdo_update('tg_print', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
			} else {
				pdo_insert('tg_print', $data);
			}
			message('更新打印机设置成功', $this->createWebUrl('print', array('op' => 'print_list')), 'success'); 
		}
			
		} elseif($op == 'print_list') {
			$data = pdo_fetchall('SELECT * FROM ' . tablename('tg_print') . ' WHERE uniacid = :uniacid ', array(':uniacid' => $_W['uniacid']));
			// include $this->template('print');
		} elseif($op == 'print_del') {
			$id = intval($_GPC['id']);
			pdo_delete('tg_print', array('uniacid' => $_W['uniacid'], 'id' => $id));
			message('删除打印机成功', referer(), 'success');
		} elseif($op == 'log_del') {
			$id = intval($_GPC['id']);
			pdo_delete('tg_order_print', array('uniacid' => $_W['uniacid'], 'id' => $id));
			message('删除打印记录成功', referer(), 'success');
		} elseif($op == 'print_log') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch('SELECT * FROM ' . tablename('tg_print') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
			if(empty($item)) {
				message('打印机不存在或已删除', $this->createWebUrl('print', array('op' => 'print_list')), 'success');
			}
			if(!empty($item['print_no']) && !empty($item['key'])) {
				include_once 'wprint.class.php';
				$wprint = new wprint();
				$status = $wprint->QueryPrinterStatus($item['print_no'], $item['key']);
				if(is_error($status)) {
					$status = '查询打印机状态失败。请刷新页面重试';
				}
			}
			$condition = ' WHERE a.uniacid = :aid AND a.sid = :sid AND a.pid = :pid';
			$params[':aid'] = $_W['uniacid']; 
			$params[':sid'] = $sid; 
			$params[':pid'] = $id; 
			if(!empty($_GPC['oid'])) {
				$oid = trim($_GPC['oid']);
				$condition .= ' AND a.oid = :oid';
				$params[':oid'] = $oid; 
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;

			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order_print') . ' AS a ' . $condition, $params);
			$data = pdo_fetchall('SELECT a.*,b.* FROM ' . tablename('tg_order_print') . ' AS a LEFT JOIN' . tablename('shopping_order') . ' AS b ON a.oid = b.id' . $condition . ' ORDER BY addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
			$pager = pagination($total, $pindex, $psize);
			// include $this->template('print');
	    }
	    include $this->template('print');
    }
	
	//后台会员管理页面
	public function doWebMember() {
		$this -> __web(__FUNCTION__);
	}

	public function __web($f_name){
		global $_W,$_GPC;
		checklogin();
		$weid = $_W['uniacid'];
		load()->func('tpl');
		$this->checkmode();
		include_once  'web/'.strtolower(substr($f_name,5)).'.php';
	}
	
	public function __mobile($f_name){
		global $_W,$_GPC;
		/*checkauth();*/
		$weid = $_W['uniacid'];
		$share_data = $this->module['config'];
		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';
	}

	//支付结果返回
    public function payResult($params) {
		global $_W,$_GPC;
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
		$data['pay_type'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		$goodsId = pdo_fetchcolumn("SELECT `g_id` FROM".tablename('tg_order')."WHERE `orderno` = :orderid ", array(':orderid' => $params['tid']));
		$goodsInfo = pdo_fetch("SELECT * FROM".tablename('tg_goods')."WHERE `id` = :id ", array(':id' => $goodsId));
		
		// //货到付款
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
			$data['starttime'] = TIMESTAMP;
			$data['ptime'] = TIMESTAMP;
		}
		if($params['result'] == 'success'){
			$data['ptime'] = TIMESTAMP;
			$data['starttime'] = TIMESTAMP;
		}

		$tuan_id = pdo_fetch("select * from".tablename('tg_order') . "where orderno = '{$params['tid']}'");
		$goods = pdo_fetch("select * from".tablename('tg_order') . "where id = '{$tuan_id['g_id']}'");
		if ($params['from'] == 'return') {
			
			$pay_suc=$this->module['config']['pay_suc'];
			$pay_remark=$this->module['config']['pay_remark'];
			$m_pay=$this->module['config']['m_pay'];
			//支付成功模板消息提醒
			$content="";
			if($tuan_id['tuan_first']==1){
				$content .= "您已成功付款开团，恭喜您荣升团长，组团成功才会享受优惠哦";
			}else{
				$content .= "您已成功付款参团，组团成功才会享受优惠哦";
			}
			load()->func('communication');
			load()->model('account');
			$access_token = WeAccount::token();
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
			$url2=$_W['siteroot'].'app/'.$this->createMobileUrl('orderdetails', array('id'=>$params['tid']));//点击模板详情跳转的地址url2
			$time = date("Y-m-d H:i:s",time());
			$openid = trim($_W['openid']);
			$msg_json= '{
               	"touser":"'.$openid.'",
               	"template_id":"'.$m_pay.'",
               	"url":"'.$url2.'",
               	"topcolor":"#FF0000",
               	"data":{
                   	"first":{
                       "value":"\n'.$pay_suc.'\n",
                       "color":"#000000"
                   	},
                   	"orderProductName":{
						"value":"'.$goodsInfo['gname'].'\n",
                   		"color":"#000000"
					},
                   	"orderMoneySum":{
						"value":"'.$tuan_id['price'].'\n",
                   	    "color":"#000000"
					},
                   	"remark":{
                       "value":"\n\n'.$pay_remark.'",
                       "color":"#0099FF"
                   	}
               	}
           	}';
		   	include_once 'message.php';
		   	$sendmessage = new WX_message();
		   	$res=$sendmessage->WX_request($url,$msg_json);
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($tuan_id['status'] != 1) {
				pdo_update('tg_order', $data, array('orderno' => $params['tid']));
				// 更改库存
				if (!empty($goodsInfo['gnum'])) {
					pdo_update('tg_goods', array('gnum' => $goodsInfo['gnum'] - 1,'salenum' =>$goodsInfo['salenum'] + 1), array('id' => $goodsId));
				}
			}
			$order_out = pdo_fetch("select * from".tablename('tg_order') . "where orderno = '{$params['tid']}'");
			//判断人数，是否团购成功
			$sql= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status =:status ";
   		    $params= array(':tuan_id'=>$tuan_id['tuan_id'],':status'=>1);
    		$alltuan = pdo_fetchall($sql, $params);
    		$item = array();
    		foreach ($alltuan as $num => $all) {
    			$item[$num] = $all['id'];
				if($all['tuan_first'] == 1){
					$tuan_firstopenid = $all['openid'];
				}
   			}
			$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$tuan_firstopenid}'");
			$n = $goodsInfo['groupnum'] - count($item);
			
			if($n==0){
				pdo_update('tg_order',array('success'=>0),array('tuan_id'=>$tuan_id['tuan_id'],'status'=>1));
				//组团成功模板消息提醒
				$m_tuan=$this->module['config']['m_tuan'];
				$tuan_suc=$this->module['config']['tuan_suc'];
				$tuan_remark=$this->module['config']['tuan_remark'];
				$content="组团成功!!!";
				load()->func('communication');
				load()->model('account');
				$access_token = WeAccount::token();
				$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";
				
				$url2="";//点击模板详情跳转的地址url2
				$time = date("Y-m-d H:i:s",time());
				foreach ($alltuan as $num => $all) {
					$openid = trim($all['openid']);
					$msg_json= '{
	                   	"touser":"'.$openid.'",
	                   	"template_id":"'.$m_tuan.'",
	                   	"url":"'.$url2.'",
	                   	"topcolor":"#FF0000",
	                   	"data":{
	                       	"first":{
	                           "value":"\n'.$tuan_suc.'\n",
	                           "color":"#000000"
	                       	},
	                       	"Pingou_ProductName":{
								"value":"'.$goodsInfo['gname'].'\n",
	                       		"color":"#000000"
							},
	                       	"Weixin_ID":{
								"value":"'.$profile['nickname'].'\n",
	                       	    "color":"#000000"
							},
	                       	"remark":{
	                           "value":"\n\n'.$tuan_remark.'",
	                           "color":"#0099FF"
	                       	}
	                   	}
	               	}';
				   	
				   	$sendme = new WX_message();
				   	$res=$sendme->WX_request($url,$msg_json);
					
				   	//获取所有打印机
	   				$prints = pdo_fetchall('SELECT * FROM ' . tablename('tg_print') . ' WHERE uniacid = :aid AND status = 1', array(':aid' => $_W['uniacid']));
	   				if(!empty($prints)) {
		   				include_once 'wprint.class.php';
		   				//遍历所有打印机
		   				foreach($prints as $li) {
		   					if(!empty($li['print_no']) && !empty($li['key'])) {
		   						$wprint = new wprint();
								if ($li['mode']==1) {
				   					$orderinfo .= "<CB>组团成功</CB><BR>";
					   				$orderInfo .= "商品信息：<BR>";
					   				$orderinfo .= '------------------------------<BR>';
					   				$orderinfo .= "商品名称：{$goodsInfo['gname']}<BR>";
					   				$orderinfo .= '------------------------------<BR>';
					   				$orderinfo .= "用户信息：<BR>";
					   				$orderinfo .= '------------------------------<BR>';
					   				foreach ($alltuan as $row) {
					   					$user = pdo_fetch("select * from".tablename('tg_address')."where id='{$row['addressid']}'");
					   					$orderinfo .= "用户名：{$user['cname']}<BR>";
					   					$orderinfo .= "手机号：{$user['tel']}<BR>";
					   					$orderinfo .= "地址：{$user['province']}{$user['city']}{$user['county']}{$user['detailed_address']}<BR>";
					   					$orderinfo .= '------------------------------<BR>';
					   				}
									$status = $wprint->StrPrint($li['print_no'], $li['key'], $orderinfo, $li['print_nums']);
								}else{
				   					$orderinfo .= "组团成功";
					   				$orderInfo .= "商品信息：";
					   				$orderinfo .= '------------------------------';
					   				$orderinfo .= "商品名称：{$goodsInfo['gname']}";
					   				$orderinfo .= '------------------------------';
					   				$orderinfo .= "用户信息：";
					   				$orderinfo .= '------------------------------';
					   				foreach ($alltuan as $row) {
					   					$user = pdo_fetch("select * from".tablename('tg_address')."where id='{$row['addressid']}'");
					   					$orderinfo .= "用户名：{$user['cname']}";
					   					$orderinfo .= "手机号：{$user['tel']}";
					   					$orderinfo .= "地址：{$user['province']}{$user['city']}{$user['county']}{$user['detailed_address']}";
					   					$orderinfo .= '------------------------------';
					   				}
		   							$status = $wprint->testSendFreeMessage($li['member_code'], $li['print_no'], $li['key'], $orderinfo);
		   						}
		   						if(!is_error($status)) {
		   							$i++;
		   							$data = array(
		   									'uniacid' => $_W['uniacid'],
		   									'sid' => $sid,
		   									'pid' => $li['id'],
		   									'oid' => $id, //订单id
		   									'status' => 1,
		   									'foid' => $status,
		   									'addtime' => TIMESTAMP
		   								);
		   							pdo_insert('tg_order_print', $data);
		   						}
		   					}
		   				}
	   				}
				}
			}elseif($n<0){
				echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('more_refund',array('transid' => $order_out['transid']))."';</script>";
				exit;
			}
			if (empty($params['tiaozhuan'])) {
				if ($params['type'] == $credit) {
					if($tuan_id['is_tuan'] == 0){
						echo "<script>location.href='".$this->createMobileUrl('pay_match',array('type'=>'single','params'=>0))."';</script>";
						exit;
					}else{
						echo "<script>location.href='".$this->createMobileUrl('pay_match',array('tuan_id' => $tuan_id['tuan_id'],'orderno'=>$order_out['orderno'],'params'=>0))."';</script>";
						exit;
					}
				} else {
					if($tuan_id['is_tuan'] == 0){
						echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('pay_match',array('type'=>'single','params'=>1))."';</script>";
						exit;
				    }else{
						echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('pay_match',array('tuan_id' => $tuan_id['tuan_id'],'orderno'=>$order_out['orderno'],'params'=>1))."';</script>";
						exit;
				   	}
				}
			}
		}
	}
}

<?php
/**
 * 官方示例模块微站定义
 *
 * @author www.zheyitianShi.Com团队
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Iwobanji_xcpageModuleSite extends WeModuleSite {
    public $adtable='iwobanji_xcpage_adma';

	public function doWebAdshow(){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$adinfo=pdo_fetch('select * from '.tablename($this->adtable)." where weid={$weid}");
		
		$syserPic=$_W['attachdir'].'qrcode_'.$weid.'.jpg';
		$data['url']=empty($adinfo['url'])?'http://7tea6c.com1.z0.glb.clouddn.com/qrcode_1.jpg':$adinfo['url'];
		$data['copyright']=empty($adinfo['copyright'])?'例如：(c) 2010-2013 爱我班级 版权所有':$adinfo['copyright'];;
		$data['info']=empty($adinfo['info'])?'例如：快速高效为班主任构建班主任、老师、家长、学生一体的家校合作平台。 通过记录成长历程有效沉淀班级文化，保持班级的凝聚力，提升学生班级归属感。':$adinfo['info'];
		$data['title']=empty($adinfo['title'])?'例如：幸福小镇':$adinfo['title'];
        $data['wxh']=empty($adinfo['wxh'])?'微信资料中查询，由一组字母组成':$adinfo['wxh'];
        $data['wxm']=empty($adinfo['wxm'])?'微信资料中查询，由一组汉字组成':$adinfo['wxm'];				
        $data['class']=empty($adinfo['class'])?'例如:高二（3）班':$adinfo['class'];
        $data['classkouling']=empty($adinfo['classkouling'])?' ':$adinfo['classkouling'];
        $data['classslogan']=empty($adinfo['classslogan'])?' ':$adinfo['classslogan'];
		$data['background_img']=empty($adinfo['background_img'])?'http://7tea6c.com1.z0.glb.clouddn.com/qrcode_1.jpg':$adinfo['background_img'];
		$data['group_photo']=empty($adinfo['group_photo'])?'http://7tea6c.com1.z0.glb.clouddn.com/qrcode_1.jpg':$adinfo['group_photo'];

		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入标题！');
			}
			$mData=array(
					'weid'=>$weid,
					'url' => $_GPC['url'],
					'copyright' => $_GPC['copyright'],						
					'info' => $_GPC['info'],
					'wxh' => $_GPC['wxh'],
					'wxm' => $_GPC['wxm'],
				    'class' => $_GPC['class'],
					'classkouling' => $_GPC['classkouling'],
					'classslogan' => $_GPC['classslogan'],
				    'background_img' => $_GPC['background_img'],
				    'group_photo' => $_GPC['group_photo'],
					'title' => $_GPC['title']						
			);		
		
				



			if (empty($adinfo))
			{
				pdo_insert($this->adtable, $mData);
			} else {
				pdo_update($this->adtable, $mData, array('id' => $adinfo['id']));
			}
			
			message('信息更新成功！', $this->createWebUrl('adshow'), 'success');
		}
		$previewUrl=$_W['siteroot'].'app/'.$this->createMobileUrl('adshow');
		load()->func('tpl');
		include $this->template('adshow');		
	}
	public function doMobileAdshow(){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		
		$info=pdo_fetch('select * from '.tablename($this->adtable)." where weid={$weid}");
		include $this->template('mobileshow');
		
	}

	public function doMobileIndex1() {
		//这个操作被定义用来呈现 功能封面
		$title = '支付测试';
		include $this->template('index1');
	}
	public function doMobileIndex2() {
		//这个操作被定义用来呈现 功能封面
		include $this->template('index2');
	}
	
	public function doMobilePay() {
		global $_W, $_GPC;
		//验证用户登录状态，此处测试不做验证
		checkauth();
		
		$params['tid'] = date('YmdH');
		$params['user'] = $_W['member']['uid'];
		$params['fee'] = floatval($_GPC['price']);
		$params['title'] = '测试支付公众号名称';
		$params['ordersn'] = random(5,1);
		$params['virtual'] = false;
		
		if (checksubmit('submit')) {
			if ($_GPC['type'] == 'credit') {
				$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
				$credtis = mc_credit_fetch($_W['member']['uid']);
				//此处需要验证积分数量
				if ($credtis[$setting['creditbehaviors']['currency']] < $params['fee']) {
					message('抱歉，您帐户的余额不够支付该订单，请充值！', '', 'error');
				}
			}
		} else {
			$this->pay($params);
		}
	}
	
	/**
	 * 支付完成后更改业务状态
	 */
	public function payResult($params) {
		/*
		 * $params 结构
		 * 
		 * weid 公众号id 兼容低版本
		 * uniacid 公众号id
		 * result 支付是否成功 failed/success
		 * type 支付类型 credit 积分支付 alipay 支付宝支付 wechat 微信支付  delivery 货到付款
		 * tid 订单号
		 * user 用户id
		 * fee 支付金额
		 * 
		 * 注意：货到付款会直接返回支付失败，请在订单中记录货到付款的订单。然后发货后收取货款
		 */
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		//如果是微信支付，需要记录transaction_id。
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		//此处更改业务方面的记录，例如把订单状态更改为已付款
		//pdo_update('shopping_order', $data, array('id' => $params['tid']));
		
		//如果消息是用户直接返回（非通知），则提示一个付款成功
		if ($params['from'] == 'return') {
			if ($params['type'] == 'credit') {
				message('支付成功！', $this->createMobileUrl('index1'), 'success');
			} elseif ($params['type'] == 'delivery') {
				message('请您在收到货物时付清货款！', $this->createMobileUrl('index1'), 'success');
			} else {
				message('支付成功！', '../../' . $this->createMobileUrl('index1'), 'success');
			}
		}
	}
	
	public function doWebManage1() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$title = '测试标题1';
		include $this->template('manage1');
	}
	public function doWebManage2() {
		//这个操作被定义用来呈现 管理中心导航菜单
		include $this->template('manage2');
	}
	public function doMobileNav1() {
		//这个操作被定义用来呈现 微站首页导航图标
		exit('doMobileNav1');
	}
	public function doMobileNav2() {
		//这个操作被定义用来呈现 微站首页导航图标
		exit('doMobileNav2');
	}
	public function doMobileUc1() {
		//这个操作被定义用来呈现 微站个人中心导航
		exit('doMobileUc1');
	}
	public function doMobileUc2() {
		//这个操作被定义用来呈现 微站个人中心导航
		exit('doMobileUc2');
	}
	public function doMobileQuick1() {
		//这个操作被定义用来呈现 微站快捷功能导航
		exit('doMobileQuick1');
	}
	public function doMobileQuick2() {
		//这个操作被定义用来呈现 微站快捷功能导航
		exit('doMobileQuick2');
	}

}
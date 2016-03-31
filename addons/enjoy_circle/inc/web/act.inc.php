<?php
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
//基本设置

$item=pdo_fetch("select * from ".tablename('enjoy_circle_reply')." where uniacid=".$uniacid."");
$item['title']=empty($item['title'])?'朋友圈':$item['title'];
//$item['rule']=empty($item['title'])?'&lt;div&gt;1、支付1分钱，猜红包金额范围&lt;span style=&quot;color: #ff0000;&quot;&gt;1-100&lt;/span&gt;元。&lt;br /&gt;2、显示他人猜红包金额记录（猜高了或低了）。&lt;br /&gt;3、猜中金额将直接到账你的微信钱包，请注意及时查收。&lt;br /&gt;4、邀请码字母不分大小写，直接输入朋友的邀请码即可使用。&lt;br /&gt;5、邀请码本人自己使用无效，但是使用朋友的邀请码可以多猜一次，请知悉。&lt;br /&gt;6、转发分享自己并不获利，我们不利诱大家分享活动，邀请码是用于促进已参与者朋友间互动，请知悉。&lt;br /&gt;7、为杜绝恶意作弊、刷红包等行为，每个红包限定每人可猜次数为&lt;span style=&quot;color: #ff0000;&quot;&gt;3&lt;/span&gt;次。&lt;br /&gt;8、你也可以支付生成一个新红包，生成红包金额为&lt;span style=&quot;color: #ff0000;&quot;&gt;1-100&lt;/span&gt;元间某一随机数，生成后可自己尝鲜猜一次。&lt;br /&gt;9、严禁任何形式的刷奖、作弊行为，一经发现并核实，悦品有权追回奖励金额，并保留追究法律责任的权利。&lt;br /&gt;10、本活动奖励总金额有限，由&lt;span style=&quot;color: #ff0000;&quot;&gt;招商银行&lt;/span&gt;赞助，授权悦品保留对本次活动最终解释权。&nbsp;&lt;/div&gt;':$item['rule'];
//$item['apic']=empty($item['share_icon'])?'./addons/enjoy_guess/public/images/banner.jpg':$item['apic'];
//$item['share_icon']=empty($item['share_icon'])?'./addons/enjoy_red/template/mobile/images/red/share.jpg':$item['share_icon'];
$item['share_title']=empty($item['share_title'])?'#user#和你交换真心话':$item['share_title'];
$item['share_content']=empty($item['share_content'])?'我是#user#，这句话想说很久了，愿意与我交换真心吗？':$item['share_content'];
// $item['rule']=empty($item['rule'])?'':$item['rule'];
// $item['color']=empty($item['color'])?'#fff':$item['color'];
// $item['bingo']=empty($item['bingo'])?'2':$item['bingo'];
// $item['chance']=empty($item['chance'])?'3':$item['chance'];

//提交
if(checksubmit('submit')){
	//判断是否已经存在这个活动
	$exist=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_circle_reply')." where uniacid=".$uniacid."");
	$data=array(
			'uniacid'=>$uniacid,
			'title'=>$_GPC['title'],
			'subscribe'=>$_GPC['subscribe'],
			'sucai'=>$_GPC['sucai'],
			'exurl'=>$_GPC['exurl'],
			'expic'=>$_GPC['expic'],
			'extitle'=>$_GPC['extitle'],
			'share_icon'=>$_GPC['share_icon'],
			'share_title'=>$_GPC['share_title'],
			'share_content'=>$_GPC['share_content'],
			'ewm'=>$_GPC['ewm'],
			'bgpic'=>$_GPC['bgpic'],

	);
	if($exist>0){
		//update
		$res=pdo_update('enjoy_circle_reply',$data,array('uniacid'=>$uniacid));
		$message="更新活动成功";

	}else{

		//插入数据库
		$res=pdo_insert('enjoy_circle_reply',$data);
		$message="新增活动成功";
			
	}

		
	if($res==1){
		message($message,$this->createWebUrl('act'), 'success');
	}
		
		
		
}

include $this->template('act');
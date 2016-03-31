<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->func('tpl');

$op = $_GPC['op'];

if($op == 'post' || empty($op)){
	$id = $_GPC['id'];
	$sql = "SELECT * FROM ".tablename('meepo_bbs_credit_goods')." WHERE id = :id";
	$params = array(':id'=>$id);
	$item = pdo_fetch($sql,$params);
	
	if($_W['ispost']){
		if (empty($_GPC['title'])) {
			message('请输入兑换商品名称！');
		}
		if (empty($_GPC['cost'])) {
			message('请输入兑换商品需要消耗的积分数量！');
		}
		if (empty($_GPC['price'])) {
			message('请输入商品实际价值！');
		}
		$cost = intval($_GPC['cost']);
		$price = intval($_GPC['price']);
		$amount = intval($_GPC['amount']);
		$per_user_limit = intval($_GPC['per_user_limit']);
		$cost_type = 1;//array_sum($_GPC['cost_type']);
		$data = array(
				'uniacid' => $_W['uniacid'],
				'title' => trim($_GPC['title']),
				'logo' => trim($_GPC['logo']),
				'deadline' => $_GPC['deadline'],
				'amount' => $amount,
				'per_user_limit' => $per_user_limit,
				'cost' => $cost,
				'cost_type' => $cost_type,
				'price' => $price,
				'content' => trim($_GPC['content']),
				'createtime' => TIMESTAMP,
		);
		if (!empty($id)) {
			pdo_update('meepo_bbs_credit_goods', $data, array('id' => $id));
		} else {
			pdo_insert('meepo_bbs_credit_goods', $data);
		}
		message('商品更新成功！', $this->createWebUrl('credit'),'success');
	}
	include $this->template('credit_goods_post');
}

if($op == 'delete'){
	$id = $_GPC['id'];
	
	if(empty($id)){
		message('不存在或已删除',$this->createWebUrl('credit'),error);
	}
	
	pdo_delete('meepo_bbs_credit_goods',array('id'=>$id));
	message('操作成功',$this->createWebUrl('credit'),success);
}

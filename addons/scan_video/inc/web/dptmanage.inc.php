<?php
global $_GPC,$_W;
require MODULE_ROOT.'/model.php';
$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';
$handles = array('display', 'post', 'delete');
$DptModel = new dptModel();

if(in_array($operation, $handles)){
	if('display' == $operation){
		$departments = $DptModel->all();
	}elseif('post' == $operation){
		$id = intval($_GPC['id']);
		
		if(checksubmit('submit')){
			$data = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'sv_dpt_name' => $_GPC['dpt-name'],
				'sv_dpt_time' => TIMESTAMP,
				);
			
			if (!empty($id)) {
				$DptModel->modify($data, $id);
				message('更新门店成功！', $this->createWebUrl('dptmanage', array('op' => 'display')), 'success');
			} else {
				$id = $DptModel->add($data);
				message('添加门店成功！', $this->createWebUrl('dptmanage', array('op' => 'display')), 'success');
			}
			
		}

		if(!empty($id)){
			$department = $DptModel->item($id);
			if (empty($department)) {
				message('抱歉，门店信息不存在或是已经删除！', '', 'error');
			}
		}
	}else{
		message('抱歉，门店信息不建议删除，可尝试修改！', '', 'error');
	}

	include $this->template('departments');
}else{
	message('非法操作！', '', 'error');
}


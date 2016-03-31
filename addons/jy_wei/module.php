<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );

class Jy_weiModule extends WeModule {
	
	public function settingsDisplay($settings) {
		// 声明为全局才可以访问到.
		global $_W, $_GPC;
		// 这里来展示设置项表单
		include $this->template ( 'setting' );
	}
	
	public function fieldsFormDisplay($rid = 0) {
		// 要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		load ()->func ( 'tpl' );
		if (! empty ( $rid )) {
			$reply = pdo_fetch ( "SELECT * FROM " . tablename ( "jy_wei_rule" ) . " WHERE ruleid = :rid ORDER BY `id` DESC", array (
					':rid' => $rid 
			) );
			$company = pdo_fetch ( "SELECT * FROM " . tablename ( "jy_wei_company" ) . " WHERE id = :id ORDER BY `id` DESC", array (
					':id' => $reply['companyid'] 
			) );
		}
		$companys = pdo_fetchall ( "SELECT * FROM " . tablename ( "jy_wei_company" ) . " WHERE uniacid = :uniacid ORDER BY `id` DESC", array (
				':uniacid' => $_W ['uniacid'] 
		) );
		include $this->template ( 'display' );
	}
	
	public function fieldsFormValidate($rid = 0) {
		// 规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}
	
	public function fieldsFormSubmit($rid) {
		// 规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$data = array (
				'ruleid' => $rid,
				'uniacid' => $_W ['uniacid'],
				'companyid' => $_GPC ['companyid'] 
		);
		if ($_GPC ['id']) {
			pdo_update ( "jy_wei_rule", $data, array (
					'id' => $_GPC ['id'] 
			) );
		} else {
			pdo_insert ( "jy_wei_rule", $data );
		}
	}
	public function ruleDeleted($rid) {
		// 删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete ( "jy_wei_rule", array (
				'ruleid' => $rid 
		) );
	}
}
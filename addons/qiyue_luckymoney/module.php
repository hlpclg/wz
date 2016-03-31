<?php
/**
 * 拆红包模块定义
 *
 * @author 冯齐跃
 * @url http://fengqiyue.com/
 */
defined('IN_IA') or exit('Access Denied');

class Qiyue_luckymoneyModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if($rid){
            $item = pdo_fetch("SELECT * FROM " . tablename('qiyue_luckymoney') . " WHERE rid = :rid", array(':rid' => $rid));
            $item['picurl'] = tomedia($item['picurl']);
		}
        if(empty($item['starttime'])){
        	$item['starttime'] = time();
        	$item['endtime'] = time() + 86400 * 7;
        }
		load()->func('tpl');
		include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
		global $_W, $_GPC;
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		if(empty($_GPC['title']) || empty($_GPC['picurl'])){
			return '标题 和 封面图片不能为空！';
		}
		if(empty($_GPC['datelimit']['start']) || empty($_GPC['datelimit']['end'])){
			return '活动起始时间不能为空';
		}
		if(empty($_GPC['ruletxt'])){
			return '活动规则不能为空！';
		}
		return true;
	}

	public function fieldsFormSubmit($rid) {
		global $_W, $_GPC;
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		$add = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'picurl' => $_GPC['picurl'],
			'musicurl' => $_GPC['musicurl'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
			'ruletxt' => $_GPC['ruletxt'],
			'share_imgurl' => $_GPC['share_imgurl'],
			'share_title' => $_GPC['share_title'],
			'share_desc' => $_GPC['share_desc'],
			'share_link' => $_GPC['share_link']
		);
		$check = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$rid));
		if($check){
			pdo_update('qiyue_luckymoney', $add, array('rid'=>$rid));
		}
		else{
			$add['rid'] = $rid;
			pdo_insert('qiyue_luckymoney', $add);
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
        global $_W;
        load()->func('file');
        $picurl = pdo_fetchcolumn("SELECT picurl FROM ".tablename('qiyue_luckymoney')." WHERE rid=".$rid);
        if(!empty($picurl)){
        	file_delete($picurl);
        }
        pdo_delete('qiyue_luckymoney', array('rid'=>$rid));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$this->saveSettings($dat);
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}
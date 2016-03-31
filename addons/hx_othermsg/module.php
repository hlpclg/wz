<?php
/**
 * 特殊消息接口转发模块定义
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_othermsgModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//print_r($_GPC);//exit();
		if (checksubmit()) {
            $cfg = array(
                'pic' => $_GPC['pic'],
                'picurl' => $_GPC['picurl'],
                'pictoken' => $_GPC['pictoken'],
                'voice' => $_GPC['voice'],
                'voiceurl' => $_GPC['voiceurl'],
                'voicetoken' => $_GPC['voicetoken'],
                'video' => $_GPC['video'],
                'videourl' => $_GPC['videourl'],
                'videotoken' => $_GPC['videotoken'],
                'location' => $_GPC['pic'],
                'locationurl' => $_GPC['locationurl'],
                'locationtoken' => $_GPC['locationtoken'],
                'trace' => $_GPC['trace'],
                'traceurl' => $_GPC['traceurl'],
                'tracetoken' => $_GPC['tracetoken'],
                'link' => $_GPC['link'],
                'linkurl' => $_GPC['linkurl'],
                'linktoken' => $_GPC['linktoken'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		include $this->template('setting');
	}

}
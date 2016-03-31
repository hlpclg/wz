<?php
/**
 * 端午节活动模块
 *
 * @author lonaking
 * @url http://bbs.012wz.com/
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class lonaking_dwblessingModuleSite extends WeModuleSite {
	
    /**
     * 展示controller
     */
	public function doMobileShow(){
		global $_GPC, $_W;
		$newName = urldecode($_GPC['to_who']);
		$config = $this->module['config'];
		$title = isset($config['title']) ?  $newName.$config['title'] : $newName.'祝您端午节快乐';
		$copy = isset($config['copyright']) ? $config['copyright'] : "本页面由".$_W['uniaccount']['name']."制作";
		$html = array(
		    "api_action" => $this->createMobileUrl("forward",null,true),
		    "action" => $this->createMobileUrl("show",array("to_who" => $newName)),
		    "newName" => $_GPC["to_who"],
		    "copy" => isset($config['copyright']) ? $config['copyright'] : "本页面由".$_W['uniaccount']['name']."制作",
		    "title" =>isset($config['title']) ?  $newName.$config['title'] : $newName.'祝您端午节快乐',
		);
		include $this->template('show');
	}
	
	/**
	 * 生成跳转地址接口
	 */
	public function doMobileForward(){
	    global $_GPC, $_W;
	    $name = urldecode($_GPC['name']);
	    $data = array(
	        "status" => "200",
	        "msg" => "ok",
	        "data" => $this->createMobileUrl("show",array("to_who"=>$name))
	    );
	    exit($data['data']);
	}
}
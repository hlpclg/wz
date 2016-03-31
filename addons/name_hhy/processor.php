<?php
/**
 * 常用api大全模块处理程序
 *
 * @author 步飞凌云
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Name_hhyModuleProcessor extends WeModuleProcessor {

	public function respond() {
        global $_W;
		$imgurl = $_W['siteroot']."addons/name_hhy/include/img";
        $openid = $_W['openid'];
        $weid = $_W['uniacid'];
        $msg = $this->message['content'];
		$typelist = array(
					'xingming' => '姓名', 
					'nametest' => '姓名测试', 
					'gongjiao' => '公交', 
					'ditie' => '地铁', 
					'xianlu' => '线路'
					);		
		$type = array_search($msg,$typelist);
		if(!$type){$type = $this->lasttype($openid);}


//退出会话窗口
        if($msg == "退出" or $msg == "结束") {
            $this->endContext();
            return $this->respText("您已经成功退出本次会话");
        }
		
        if (! $this->inContext) {
            $this->beginContext(1800);
        }
        $this->refreshContext(1800);

		

		if($type == "xingming" or $type == "nametest" or $type == "namequery" or $type == "ditie"){
			$this->beginContext();
			include('include/name.php'); 
		
			if(!is_array($reply)){return $this->respText($reply);}
			else{return $this->respNews($reply);}
		}
		
		
		

		
		else{
		$reply = $type;
		return $this->respText($reply);
		}
	}
	
	
	
	

	
    private function ifexist($openid, $type, $key)
    {
        $sql = "SELECT id,weid,{$type},openid FROM " . tablename('hongapis') . " WHERE `openid` = '{$openid}' AND `{$type}` = '{$key}' ORDER BY id desc";
        $all = pdo_fetchall($sql);
        if (empty($all))
            return false;
        return true;
    }

    private function lasttype($openid)
    {
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
        if (empty($item['type'])){
		$type = 0;
		}else{
		$type = $item['type'];
		}
        return $type;
    }
	
	
	private function typeupdate($type,$weid,$openid)
	{
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if(empty($item)){
		pdo_insert('apitype',array('type'=>$type,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('apitype', array('type'=>$type), array('openid' => $openid));
		}
	
	
	}
	


	
}
<?php
/**
 * 搭讪管家模块处理程序
 *
 * @author Yoby
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
class YobydashanModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = trim($this->message['content']);
		$openid = $this->message['from'];
		$weid = $_W['uniacid'];
		if('注销'==$content){
			$row9 = pdo_fetch("SELECT id FROM ".tablename('yobydashan_user')." WHERE weid = :weid  and  fromuser=:openid", array(':weid' => $weid,':openid'=>$openid));
		if(empty($row9)){
		return $this->respText("你可能还未登记或已经注销,注册请发送[@]");	
		}else{
			pdo_delete('yobydashan_user', array('weid' => $weid,'fromuser'=>$openid));
			pdo_delete('yobydashan_sms', array('weid' => $weid,'fromuser'=>$openid));
			pdo_delete('yobydashan_friend',array('weid' => $weid,'fromuser'=>$openid));			return $this->respText("注销自己成功!再次注册请发送[@]");	}
		}
		
		//老乡
		if('老乡'==$content){
			$row10 = pdo_fetch("SELECT xi FROM ".tablename('yobydashan_user')." WHERE weid = :weid  and  fromuser=:openid", array(':weid' => $weid,':openid'=>$openid));
		if(empty($row10)){
		return $this->respText("你可能还未登记或已经注销,注册请发送[@]");	
		}else{
		$row11 = pdo_fetchall("SELECT yname,wid FROM ".tablename('yobydashan_user')." where weid=$weid and xi like  '%".$row10['xi']."%' limit 30");
		if($row11){
	$str11= '';
	foreach($row11 as $row11s){
				
			
		$str11 .=$row11s['yname']."(".$row11s['wid'].") \n";
		
	}	
	}else{
		$str11 = "暂无老乡";
	}
	
	
	return $this->respText($str11."\n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");
		
		}
		}
		
		$item = pdo_fetch("SELECT * FROM ".tablename('yobydashan_user')." WHERE weid = :weid and fromuser=:openid" , array(':weid' => $weid,':openid'=>$openid));
		if($item['sex']==1){
			$sexk ="  and sex=2 ";
		}elseif($item['sex']==2){
			$sexk = "  and sex=1  ";
		}else{
			$sexk ='';
		}
	if($item){
		preg_match('/^@(.*)/',$content,$rs);
		
		if(empty($rs[1])){
		//随机搭讪
		
		$item1 = pdo_fetch("SELECT * FROM ".tablename('yobydashan_user')." WHERE weid = :weid".$sexk." ORDER BY RAND() LIMIT 1" , array(':weid' => $weid));	
		if($item1){
		return $this->respText("<a href='".$_W['siteroot'].'app/'.$this->createMobileUrl('send',array('wid'=>$item1['wid'],'yname'=>$item1['yname']))."'>点击这里与".$item1['xi'].$item1['yname'].$this->get_yobysex($item1['sex'])."聊天</a>"."\n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");
		}else{
		return $this->respText("暂无用户,请后台导入或等待用户注册 \n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");	
		}
			
		}else{
		//在此判断是否完全数字
		if(preg_match('/^\d{1,7}$/',$rs[1])){
			//特定编号用户
		$item2 = pdo_fetch("SELECT * FROM ".tablename('yobydashan_user')." WHERE weid = :weid and wid=:wid" , array(':weid' => $weid,':wid'=>$rs[1]));	
		if($item2 ){
			if ($item2['fromuser'] ==$openid){
			return $this->respText("天啊,你无聊到和自己聊天 \n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");	
			}
		return $this->respText("<a href='".$_W['siteroot'].'app/'.$this->createMobileUrl('send',array('wid'=>$item2['wid'],'yname'=>$item2['yname']))."'>点击这里与".$item2['xi'].$item2['yname'].$this->get_yobysex($item2['sex'])."聊天</a>"."\n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");	
		}else{
		return $this->respText("暂无此用户 \n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");	
		}
		
			
		}else{
			//搜索用户
	$item3 = pdo_fetchall("SELECT * FROM ".tablename('yobydashan_user')." where weid=$weid and yname like  '%".$rs[1]."%' limit 30");
	if($item3){
	$str = '';
	foreach($item3 as $item3s){
				
			
		$str .=$item3s['yname']."(".$item3s['wid'].") \n";
		
	}	
	}else{
		$str = "暂无此用户";
	}
	
	return $this->respText($str."\n回复,随机查找【@】,直接联系【@+ID】例如@1,查找用户【@+关键字】例如:@yoby,查找老乡【老乡】,删除自己帐号【注销】");
			
		}
		
		}
		

		
	}else{
		return $this->respText("您还没有注册,<a href='".$_W['siteroot'].'app/'.$this->createMobileUrl('reg',array('openid'=>$openid))."'>点击这里</a>填写资料");
	}

		
		
		
		
		
		
		
		
		
	}

public function get_yobysex($n){
	$sexc = $this->module['config']['sexc'];
	$arr =explode(',',$sexc);
	switch($n){
		case '1' :
		$s = $arr[1];
			break;
		case '2' :
		$s = $arr[2];
			break;		
		default:
		$s = $arr[0];

			break;
	}
	return $s;
}
}
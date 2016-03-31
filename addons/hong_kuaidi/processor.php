<?php
/**
 * 智能快递（带广告位）模块处理程序
 *
 * @author 微标志
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Hong_kuaidiModuleProcessor extends WeModuleProcessor {
	public function respond() {
        global $_W, $_GPC;
		$imgurl = $_W['siteroot']."addons/hong_kuaidi/include/img";
        $openid = $_W['openid'];
        $weid = $_W['uniacid'];
        $msg = $this->message['content'];
		$settings=$this->module['config'];
		$picurl = $attachurl.$settings['kuaidi_img'];
			if(empty($picurl)){$picurl = $imgurl."/kuaidi.jpg";}
		$share_title = $settings['kuaidi_title'];
			if(empty($share_title)){$share_title ="欢迎使用快递查询";}
		$shareurl = $settings['kuaidi_url'];
			if(empty($shareurl)){$shareurl = "http://m.kuaidi100.com";}


//退出会话窗口
        if($msg == "退出" or $msg == "结束") {
            $this->endContext();
            return $this->respText("您已经成功退出本次会话s");
        }
		

		
        if (!$this->inContext) {
            $this->beginContext(1800);
        }
        $this->refreshContext(1800);

//快递查询


		if(preg_match("/[\x7f-\xff]/", $msg) or $this->message['type'] == 'trace' or $this->message['type'] == 'location'){
			$item = pdo_fetch("SELECT * FROM ".tablename('hongapis')." WHERE type='kuaidi' and openid=:openid" , array(':openid'=>$openid));
			if(empty($item['keywords'])){
				$reply = '请输入您要查询的快递单号';
				return $this->respText($reply);
			}else{
				$msg = $item['keywords'];
				$arr = $this->getdetail($msg);
				if(!$arr){
				$reply = '没有快递记录，请核对单号后再试或进入网站查询 <a href="http://m.kuaidi100.com">点击查询</a>';
				return $this->respText($reply);}

				
		 		$news = array();
				$news[] = array(
						'title' =>$share_title,
						'description' =>'',
						'picurl' => $picurl,
						'url' => $shareurl
						);
				$news[] = array(
						'title' =>"您上一次查询的单号：【".$msg."】",
						'description' =>'',
						//'picurl' => $imgurl.'/kuaidi.jpg',
						'url' => $shareurl
						);
				foreach ($arr as $v){
				
				$news[] = array(
						'title' =>$v,
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $shareurl
						);

				
				}
				$news[] = array(
						'title' =>"如果您要查询别的单号,请直接输入单号，回复【退出】退出本对话",
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $shareurl
						);
				$reply = $news;	
				return $this->respNews($reply);
			}
			
			
		}else{
			$reply = $this->getdetail($msg);
						
			
			if(!$reply){
				$reply = '没有快递记录，请核对单号后再试或进入网站查询 <a href="http://m.kuaidi100.com">点击查询</a>';
				return $this->respText($reply);
			}else{
				$arr = $reply;
				
		 		$news = array();
				
				$news[] = array(
						'title' =>$share_title,
						'description' =>'',
						'picurl' => $picurl,
						'url' => $shareurl
						);
				
				$news[] = array(
						'title' =>"您查询的单号：【".$msg."】",
						'description' =>'',
						//'picurl' => $imgurl.'/kuaidi.jpg',
						'url' => $shareurl
						);

				foreach ($arr as $v){
					$news[] = array(
						'title' =>$v,
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $shareurl
						);


				}
				
				
				$news[] = array(
						'title' =>"如果您要查询别的单号,请直接输入单号，回复【退出】退出本对话",
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $shareurl
						);
				$reply = $news;
				$type = "kuaidi";
				$this->keyupdate($msg,$type,$weid,$openid);
				return $this->respNews($reply);		





			}
			
			
			
			
			
			
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

	private function getdetail($data){
		$url = "http://www.kuaidi100.com/query?type=".$this->getcompany($data)."&postid=".$data;
		$get = $this->getcurl($url);
		$arr = json_decode($get,true);
		$message = $arr['message'];
		if(!$message == 'ok'){
		$result = false;}
		else{
		$data = array_slice($arr['data'],0,6);
		$arrs = array();
			foreach ($data as $v){
				$arrs[] = " ".$v['time']."\n".$v['context'];
			}
			
		$result = $arrs;
		}
		return $result;
	
	}
	
	
	private function getcompany($order){
        $name = $this->getcurl("http://www.kuaidi100.com/autonumber/auto?num=".$order);
        $json_result = json_decode($name, true);
        $expres_pinyin_name = $json_result[0]['comCode'];
        if ($expres_pinyin_name == "" || $expres_pinyin_name == null)
            $expres_pinyin_name = "未知";
     return $expres_pinyin_name;
    }

    private function getcurl($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }


	private function lasttype($openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if (empty($item['type'])){
		$type = 0;
		}else{
		$type = $item['type'];
		}
	return $type;
	}
	
	
	private function typeupdate($type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if(empty($item)){
		pdo_insert('hongapitype',array('type'=>$type,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('hongapitype', array('type'=>$type), array('openid' => $openid));
		}
	
	}

	private function keyupdate($key,$type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapis')." WHERE type=:type and openid=:openid" , array(':openid'=>$openid,':type'=>$type));
		if(empty($item)){
		pdo_insert('hongapis',array('type'=>$type,'keywords'=>$key,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('hongapis', array('keywords'=>$key), array('openid' => $openid,'type'=>$type));
		}
	
	}
	
	
	


	
}
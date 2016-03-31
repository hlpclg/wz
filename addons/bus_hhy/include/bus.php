<?php
/**
 * 常用api大全
 *
 * @author 步飞凌云
 * @url http://www.012wz.com
 */

		if($type == "gongjiao" or $msg == "重置" or $type == "xianlu" or $type == "ditie") {
			$type = 'bus';
			typeupdate($type,$weid,$openid);
			$item = pdo_fetch("SELECT * FROM ".tablename('apidaquan')." WHERE type='bus' and openid=:openid" , array(':openid'=>$openid));
			if(empty($item['key']) or $msg == "重置"){
				$reply = '首次使用请回复你的城市 ';
			keyupdate('',$type,$weid,$openid);
			cityupdate('',$type,$weid,$openid);
			}else{
				$url = 'http://api.map.baidu.com/line?region='.$item['city'].'&name='.$item['key'].'&output=html&src=weibiaozhi|wechatmap';
		 		$news = array();
				$news[] = array(
						'title' =>'上次查询：'.$item['city'].'【'.$item['key'].'】详情',
						'description' =>'',
						'picurl' => $imgurl.'/gongjiao.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'如要继续查询，请回复你要查询的线路，如1路或1号线',
						'description' =>'',
						'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'回复【重置】，更换默认城市',
						'description' =>'',
						'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'回复【退出】，退出本次会话',
						'description' =>'',
						'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				
			$reply = $news;  
			
			}
		}else{
			$type = 'bus';
			$item = pdo_fetch("SELECT * FROM ".tablename('apidaquan')." WHERE type='bus' and openid=:openid" , array(':openid'=>$openid));
			if(empty($item['city'])){
				cityupdate($msg,$type,$weid,$openid);
				$reply = '您输入您要查询的公交或地铁线路，如963或1号线';

			}else{
				$url = 'http://api.map.baidu.com/line?region='.$item['city'].'&name='.$msg.'&output=html&src=weibiaozhi|wechatmap';
		 		$news = array();
				$news[] = array(
						'title' =>'点击查看'.$item['city'].'【'.$msg.'】详情',
						'description' =>'',
						'picurl' => $imgurl.'/gongjiao.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'如要继续查询，请回复你要查询的线路，如1路或1号线',
						'description' =>'',
						'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'回复【重置】，更换默认城市',
						'description' =>'',
						'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'回复【退出】，退出本次会话',
						'description' =>'',
						'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				
			$reply = $news;  
			keyupdate($msg,$type,$weid,$openid);
			}
			typeupdate($type,$weid,$openid);

		}










/* ---------------------------------------

华丽的分割线

-----------------------------------------*/

	function lasttype($openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('apitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if (empty($item['type'])){
		$type = 0;
		}else{
		$type = $item['type'];
		}
	return $type;
	}
	
	
	function typeupdate($type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('apitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if(empty($item)){
		pdo_insert('apitype',array('type'=>$type,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('apitype', array('type'=>$type), array('openid' => $openid));
		}
	
	}
	
	
	function cityupdate($key,$type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('apidaquan')." WHERE type=:type and openid=:openid" , array(':openid'=>$openid,':type'=>$type));
		if(empty($item)){
		pdo_insert('apidaquan',array('type'=>$type,'city'=>$key,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('apidaquan', array('city'=>$key), array('openid' => $openid,'type'=>$type));
		}
	
	}

	function keyupdate($key,$type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('apidaquan')." WHERE type=:type and openid=:openid" , array(':openid'=>$openid,':type'=>$type));
		if(empty($item)){
		pdo_insert('apidaquan',array('type'=>$type,'key'=>$key,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('apidaquan', array('key'=>$key), array('openid' => $openid,'type'=>$type));
		}
	
	}

	function getastro($birth){ 
		$month = substr($birth,0,2);
		$day = substr($birth,2,2);

		$signs = array( 
				array('20'=>'水瓶座'), array('19'=>'双鱼座'), 
				array('21'=>'白羊座'), array('20'=>'金牛座'), 
				array('21'=>'双子座'), array('22'=>'巨蟹座'), 
				array('23'=>'狮子座'), array('23'=>'处女座'), 
				array('23'=>'天秤座'), array('24'=>'天蝎座'), 
				array('22'=>'射手座'), array('22'=>'魔羯座') 
		); 
		$key = (int)$month - 1; 
		list($startSign, $signName) = each($signs[$key]); 
		if( $day < $startSign ){ 
			$key = $month - 2 < 0 ? $month = 11 : $month -= 2; 
			list($startSign, $signName) = each($signs[$key]); 
		} 
	return $signName; 
	}


	function getastroresult($ast){

			$astro_list = array('aquarius' => '水瓶座', 'pisces' => '双鱼座', 'aries' => '白羊座', 'taurus' => '金牛座', 'gemini' => '双子座', 'cancer' => '巨蟹座', 'leo' => '狮子座', 'virgo' => '处女座', 'libra' => '天秤座', 'scorpio' => '天蝎座', 'sagittarius' => '射手座', 'capricorn' => '魔羯座');
	
			$astro = array('aquarius' => '水瓶', 'pisces' => '双鱼', 'aries' => '白羊', 'taurus' => '金牛', 'gemini' => '双子', 'cancer' => '巨蟹', 'leo' => '狮子', 'virgo' => '处女', 'libra' => '天秤', 'scorpio' => '天蝎', 'sagittarius' => '射手', 'capricorn' => '魔羯');
			$astro_id = array_search($ast,$astro_list);
			if(!$astro_id){$astro_id = array_search($ast,$astro);}
			if(!$astro_id){
			$reply = "请输入你的星座或你的生日（如0624）, 来分析你今天的s星座运程";}
			else{
			$html = file_get_contents('http://vip.astro.sina.com.cn/astro/view/'.$astro_id.'/day');
			preg_match('/<span[^>]*>([\s\S]*?)<\/span>/',$html,$time);
			preg_match('/<div class="lotconts"[^>]*>([\s\S]*?)<\/div>/',$html,$rss);
			preg_match_all('/<div class="tab"[^>]*>([\s\S]*?)<\/div>/',$html,$rsss);
	
				foreach ($rsss[1] as $v){
					$out = str_replace('<img src="http://image2.sina.com.cn/ast/2007index/tmp/star_php/star.gif" width="18" height="18" />','★',$v);
					$out = str_replace('<p>',':',$out);
					$res =  strip_tags($out);
					$res = trim($res);
					$re[] = $res;
				}
			array_pop($re);
			$result = implode("\n",$re);	
			$reply = strip_tags($time[1])."\n".$result."\n".$rss[1];
			
			pdo_delete('apitype',array('openid'=>$openid));
	
			
			}
			
		
	return $reply;
	}





?>
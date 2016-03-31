<?php
/**
 * 常用api大全
 *
 * @author 步飞凌云
 * @url http://www.012wz.com
 */

		if($type == "xingzuo") {
			$type = 'astro';
			typeupdate($type,$weid,$openid);
			$item = pdo_fetch("SELECT * FROM ".tablename('hongapis')." WHERE type='astro' and openid=:openid" , array(':openid'=>$openid));
			if(empty($item['keywords'])){
				$reply = '请输入你的星座或你的生日（如0624）, 来分析你今天的星座运程. ';
			}else{
				$msg = $item['keywords'];
				if(is_numeric($msg)){
					$msg = getastro($msg);
					$reply = getastroresult($msg);
				}else{
					$reply = getastroresult($msg);
				}
			
			}
		}else{
			$type = 'astro';
			if(is_numeric($msg)){
				$msg = getastro($msg);
				if(!$msg){$reply = '您输入的生日有误，请重新输入四位日期，如0624';}
				$reply = getastroresult($msg);
			}else{
	
				$reply = getastroresult($msg);
			
			}
			typeupdate($type,$weid,$openid);
			keyupdate($msg,$type,$weid,$openid);

		}










/* ---------------------------------------

华丽的分割线

-----------------------------------------*/

	function lasttype($openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if (empty($item['type'])){
		$type = 0;
		}else{
		$type = $item['type'];
		}
	return $type;
	}
	
	
	function typeupdate($type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapitype')." WHERE  openid=:openid" , array(':openid'=>$openid));
		if(empty($item)){
		pdo_insert('hongapitype',array('type'=>$type,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('hongapitype', array('type'=>$type), array('openid' => $openid));
		}
	
	}

	function keyupdate($key,$type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapis')." WHERE type=:type and openid=:openid" , array(':openid'=>$openid,':type'=>$type));
		if(empty($item)){
		pdo_insert('hongapis',array('type'=>$type,'keywords'=>$key,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('hongapis', array('keywords'=>$key), array('openid' => $openid,'type'=>$type));
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
			$reply = "今日运势：\n".strip_tags($time[1])."\n".$result."\n".$rss[1];
			
			
			
			
		 		$news = array();
				$news[] = array(
						'title' =>strip_tags($time[1]),
						'description' =>'',
						'picurl' => 'http://image2.sina.com.cn/ast/2007index/tmp/star_php/'.$astro_id.'_b.gif',
						'url' => $url
						);
				$news[] = array(
						'title' =>"===今日星座运势===\n".$result."\n概述：".$rss[1],
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
			
			
			
			$reply = $news;
			
			
			
			pdo_delete('hongapitype',array('openid'=>$openid));
	
			
			}
			
		
	return $reply;
	}





?>
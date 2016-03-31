<?php
/**
 * 常用api大全
 *
 * @author 步飞凌云
 * @url http://www.012wz.com
 */

		if($type == "kuaidi") {
			$type = 'express';
			typeupdate($type,$weid,$openid);
			$item = pdo_fetch("SELECT * FROM ".tablename('apidaquan')." WHERE type='express' and openid=:openid" , array(':openid'=>$openid));
			if(empty($item['key'])){
				$reply = '请输入您要查询的快递单号';
			}else{
				$msg = $item['key'];
				$last = "\n\n如果您要查询别的单号,请直接输入单号，回复【退出】退出本对话";
				$reply = $ex.getdetail($msg).$last;			
				$arr = getdetail($msg);
				
		 		$news = array();
				$news[] = array(
						'title' =>"欢迎查询：",
						'description' =>'',
						'picurl' => $imgurl.'/kuaidi.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>"您上一次查询的单号为：【".$msg."】",
						'description' =>'',
						//'picurl' => $imgurl.'/kuaidi.jpg',
						'url' => $url
						);
				foreach ($arr as $v){
				
				$news[] = array(
						'title' =>$v,
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);

				
				}
				$news[] = array(
						'title' =>"如果您要查询别的单号,请直接输入单号，回复【退出】退出本对话",
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$reply = $news;			
				
			}
			
		}else{
			$type = 'express';
			typeupdate($type,$weid,$openid);
			$replys = '快递信息查询如下';
			$reply = getdetail($msg);
			if(!$reply){$reply = '快递公司参数异常：单号不存在或者已经过期，请核对后再试或进入网站查询 <a href="http://m.kuaidi100.com">点击查询</a>';}
			else{
				$arr = getdetail($msg);
				
		 		$news = array();
				
				$news[] = array(
						'title' =>"欢迎查询：",
						'description' =>'',
						'picurl' => $imgurl.'/kuaidi.jpg',
						'url' => $url
						);
				
				$news[] = array(
						'title' =>"您查询的单号为：【".$msg."】",
						'description' =>'',
						//'picurl' => $imgurl.'/kuaidi.jpg',
						'url' => $url
						);

				foreach ($arr as $v){
				
				$news[] = array(
						'title' =>$v,
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);

				
				}




				$news[] = array(
						'title' =>"如果您要查询别的单号,请直接输入单号，回复【退出】退出本对话",
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$reply = $news;			
			keyupdate($msg,$type,$weid,$openid);
			}
			

		}
	









/* ---------------------------------------

华丽的分割线

-----------------------------------------*/

	function getdetail($data){
		$url = "http://www.kuaidi100.com/query?type=".getcompany($data)."&postid=".$data;
		$get = getcurl($url);
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
	
	
	function getcompany($order){
        $name = getcurl("http://www.kuaidi100.com/autonumber/auto?num=" . $order);
        $json_result = json_decode($name, true);
        $expres_pinyin_name = $json_result[0]['comCode'];
        if ($expres_pinyin_name == "" || $expres_pinyin_name == null)
            $expres_pinyin_name = "未知";
     return $expres_pinyin_name;
    }

    function getcurl($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }


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
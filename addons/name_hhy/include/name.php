<?php
/**
 * 常用api大全
 *
 * @author 步飞凌云
 * @url http://www.012wz.com
 */


			if($type== "xingming" or $type =="nametest"){
				$type = "namequery";
				typeupdate($type,$weid,$openid);
				$reply = '您输入您要查询的姓名，如：张三';

			}else{
			$url = 'http://m.1518.com/xingming_view.php?word='.urlencode(iconv("UTF-8","GB2312//IGNORE",$msg)).'&submit1=%C8%B7%B6%A8&FrontType=1';
			$message = file_get_contents($url);
			
			$message = iconv("GB2312","UTF-8",$message);
			$getout = pregmessage($message, "<dl>[title]</dl>", 'title', 2);
			$getout1 = strip_tags($getout[0]);
		
		
			
			
			preg_match('/<dt[^>]*>([\s\S]*?)<\/dt>/',$getout[0],$mark);
			preg_match_all('/<dd[^>]*>([\s\S]*?)<\/dd>/',$getout[0],$marks);
			preg_match_all('/<dd[^>]*>([\s\S]*?)<\/dd>/',$getout[1],$gaishu);
			preg_match_all('/<dd[^>]*>([\s\S]*?)<\/dd>/',$getout[2],$zonglun);
			$marks = strip_tags($marks[1][1]);
			$marks = str_replace("    ","",$marks);
			$gaishu = str_replace("<br>","\n",$gaishu[1][0]);
			$zonglun = str_replace("<br>","\n",$zonglun[1][0]);
			$title = "姓名测试：".$msg;
				
		 		$news = array();
				$news[] = array(
						'title' =>$title,
						'description' =>'',
						'picurl' => $imgurl.'/name.jpg',
						'url' => $url
						);
			$news[] = array(
						'title' =>strip_tags($mark[1])."\n\n".$marks.$gaishu.$zonglun,
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'本测试结果仅供娱乐，非科学测算',
						'description' =>'',
						//'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
				$news[] = array(
						'title' =>'回复【退出】，退出本次会话',
						'description' =>'',
					//	'picurl' => $imgurl.'/white.jpg',
						'url' => $url
						);
			
			if(empty($getout[0])){$reply = "暂无结果";}
				
			else{$reply = $news;}  
			keyupdate($msg,$type,$weid,$openid);
			}
			typeupdate($type,$weid,$openid);

		










/* ---------------------------------------

华丽的分割线

-----------------------------------------*/

function pregmessage($message, $rule, $getstr, $limit=1) {
    $result = array('0'=>'');
    $rule = convertrule($rule);     //转义正则表达式特殊字符串
    $rule = str_replace('\['.$getstr.'\]', '\s*(.+?)\s*', $rule);   //解析为正则表达式
    if($limit == 1) {
        preg_match("/$rule/is", $message, $rarr);
        if(!empty($rarr[1])) {
            $result[0] = $rarr[1];
        }
    } else {
        preg_match_all("/$rule/is", $message, $rarr);
        if(!empty($rarr[1])) {
            $result = $rarr[1];
        }
    }
    return $result;
}



function convertrule($rule) {
    $rule = preg_quote($rule, "/");     //转义正则表达式
    $rule = str_replace('\*', '.*?', $rule);
    $rule = str_replace('\|', '|', $rule);
    return $rule;
}




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
	
	
	function cityupdate($key,$type,$weid,$openid){
		$item = pdo_fetch("SELECT * FROM ".tablename('hongapis')." WHERE type=:type and openid=:openid" , array(':openid'=>$openid,':type'=>$type));
		if(empty($item)){
		pdo_insert('hongapis',array('type'=>$type,'city'=>$key,'weid'=>$weid,'openid'=>$openid));}
		else{
		pdo_update('hongapis', array('city'=>$key), array('openid' => $openid,'type'=>$type));
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
			$reply = strip_tags($time[1])."\n".$result."\n".$rss[1];
			
			pdo_delete('apitype',array('openid'=>$openid));
	
			
			}
			
		
	return $reply;
	}





?>
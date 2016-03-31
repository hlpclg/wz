<?php
/**
 * 图文天气模块处理程序
 *
 * @author n1ce
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class n1ce_picwetherModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
		$ak=empty($config['ak'])?"QzgbmMn6BtTtW4hwFI5NLYx2":$config['ak'];
	
		
		$forward=$config['url'];
		$thumb="http://i2.sinaimg.cn/hs/focus/zhuanti/exhibit/2009/hz-2/U3268P361T3D1432F82DT20090120172341.jpg";
	
		//车联网api
		$url="http://api.map.baidu.com/telematics/v2/weather?location=$content&ak=$ak";
				 $fa=file_get_contents($url);
				 $f=simplexml_load_string($fa);
			     //日期处理
				 $city=$f->currentCity;
				 $da1=$f->results->result[0]->date;
				 $da2=$f->results->result[1]->date;
             	 $da3=$f->results->result[2]->date;		
             	 $da4=$f->results->result[3]->date;	
             	 //天气处理
				 $w1=$f->results->result[0]->weather;
				 $w2=$f->results->result[1]->weather;
             	 $w3=$f->results->result[2]->weather;	
             	 $w4=$f->results->result[3]->weather;
				 //风	
				 $p1=$f->results->result[0]->wind;
				 $p2=$f->results->result[1]->wind;
             	 $p3=$f->results->result[2]->wind;
             	 $p4=$f->results->result[3]->wind;
                 //温度
				 $q1=$f->results->result[0]->temperature;
				 $q2=$f->results->result[1]->temperature;
             	 $q3=$f->results->result[2]->temperature;
             	 $q4=$f->results->result[3]->temperature;
                 //昼夜图片 
				 $pic1=$f->results->result[0]->dayPictureUrl;
				 $pic2=$f->results->result[1]->dayPictureUrl;
				 $pic3=$f->results->result[2]->dayPictureUrl;
				 $pic4=$f->results->result[3]->dayPictureUrl;
				 //拼接字符串
				 $d1=$city.$da1.$w1.$p1.$q1;
				 $d2=$city.$da2.$w2.$p2.$q2;
				 $d3=$city.$da3.$w3.$p3.$q3;
				 $d4=$city.$da4.$w4.$p4.$q4;
			
					$array = array();
					$array[0]['title'] ="$d1";
					$array[0]['picurl']=$thumb;
					$array[1]['title'] ="$d2";
					$array[1]['picurl'] ="$pic2";
					$array[2]['title'] ="$d3";
					$array[2]['picurl'] ="$pic3";
					$array[3]['title'] ="$d4";
					$array[3]['picurl'] ="$pic4";
		
	
		if (!empty($forward)){
		   	 $array[0]['url']=$forward;
		   	 $array[1]['url']=$forward;
		   	 $array[2]['url']=$forward;
		   	 $array[3]['url']=$forward;
		   	 $array[4]['url']=$forward;
		}

		return $this->respNews($array);
	}
}
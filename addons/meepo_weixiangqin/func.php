<?php
   function squarePoint($lng, $lat, $distance = 0.5){
		$dlng =  2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
		$dlng = rad2deg($dlng);

		$dlat = $distance/EARTH_RADIUS;
		$dlat = rad2deg($dlat);
		return array(
			'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
			'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
			'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
			'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
		);
	}
   function GetDistance2($lat1, $lng1, $lat2, $lng2)  {  
	   $EARTH_RADIUS = 6378.137;  
	   $radLat1 = rad($lat1);  
	   $radLat2 = rad($lat2);  
	   $a = $radLat1 - $radLat2;  
	   $b = rad($lng1) - rad($lng2);  
	   $s = 2 * asin(sqrt(pow(sin($a/2),2) +  cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));  
	   $s = $s *$EARTH_RADIUS;  
	   $s = round($s * 10000) / 10000;  
	   return $s;  
   }
   function getDistance($lat1, $lng1, $lat2, $lng2,$len_type = 1, $decimal = 2){
        $radLat1 = $lat1 * M_PI / 180;
        $radLat2 = $lat2 * M_PI / 180;
        $a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
        $b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180 ;

        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s = $s * EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type > 1){
            $s /= 1000;
        }
        $s /= 1000;
        return round($s, $decimal);
    }
	function rad($d)  {  
       return $d * 3.1415926535898 / 180.0;  
    }  
	function arrayChange($a){
		static $arr2;
		foreach($a as $v){
			if(is_array($v))
			{
				arrayChange($v);
			}else{

				$arr2[]=$v;
			}
		}
		return $arr2;
	}
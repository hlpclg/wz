<?php
/**
 * 人脸识别模块处理程序
 *
 * @author topone4tvs
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class xhw_faceModuleProcessor extends WeModuleProcessor {
	public function respond() {
	global $_W,$_GPC;	
	$weid=$_W['uniacid'];
	$sql = "SELECT * FROM " . tablename('xhw_face_link') . "WHERE `weid` = $weid";
	$arr= pdo_fetchall($sql);
	$api_key= $arr[0]['api_key'];
	$api_secret= $arr[0]['api_secret'];
    $picurl = $this->message['picurl'];
    // face++ 链接  
	$jsonStr =  
	file_get_contents("http://apicn.faceplusplus.com/v2/detection/detect?url=".$picurl."&api_key=".$api_key."&api_secret=".$api_secret."&attribute=glass,pose,gender,age,race,smiling");  

	$replyDic = json_decode($jsonStr);  
	$resultStr = "";  
	$faceArray = $replyDic->{'face'};  
	if(count($faceArray) == 0){
		return $this->respText('哎呀！识别不出来！请尽量发 正面、清晰的人类全脸 哦！图像识别效率目前还比较低，所以希望见谅，后面我会尽快优化一些功能，先娱乐一下吧！');
	}
	$resultStr .= "测到".count($faceArray)."张脸！<br>";  
	for ($i= 0;$i< count($faceArray); $i++){  
		$resultStr .= "<--第".($i+1)."张脸分析报告--><br>";  
		$tempFace = $faceArray[$i];  
        // 获取所有属性  
		$tempAttr = $tempFace->{'attribute'};  

        // 年龄：包含年龄分析结果  
        // value的值为一个非负整数表示估计的年龄, range表示估计年龄的正负区间  
		$tempAge = $tempAttr->{'age'};  

        // 性别：包含性别分析结果  
        // value的值为Male/Female, confidence表示置信度  
		$tempGenger = $tempAttr->{'gender'};   

        // 种族：包含人种分析结果  
        // value的值为Asian/White/Black, confidence表示置信度  
		$tempRace = $tempAttr->{'race'};       

        // 微笑：包含微笑程度分析结果  
        //value的值为0-100的实数，越大表示微笑程度越高  
		$tempSmiling = $tempAttr->{'smiling'};  

        // 眼镜：包含眼镜佩戴分析结果  
        // value的值为None/Dark/Normal, confidence表示置信度  
		$tempGlass = $tempAttr->{'glass'};     

        // 造型：包含脸部姿势分析结果  
        // 包括pitch_angle, roll_angle, yaw_angle  
        // 分别对应抬头，旋转（平面旋转），摇头  
        // 单位为角度。  
		$tempPose = $tempAttr->{'pose'};  

		//分数
		$grade = 0;

        // 返回性别  
		if($tempGenger->{'value'} === "Male")  {
			$grade += 30;
			$resultStr .= "嗨~ 帅哥！<br>";   
		} else if($tempGenger->{'value'} === "Female")  {
			$grade += 50;
			$resultStr .= "嗨~ 美女！<br>";  
		}

		 //返回年龄  
		$minAge = $tempAge->{'value'} - $tempAge->{'range'};  
		$minAge = $minAge < 0 ? 0 : $minAge;  
		$maxAge = $tempAge->{'value'} + $tempAge->{'range'};  

        //$resultStr .= "年龄：".$minAge."-".$maxAge."岁<br>";  
		$resultStr .= "我猜你".$tempAge->{'value'}."岁左右吧~ (误差 ".$tempAge->{'range'}."岁)<br>";  
		$grade += (100 - $tempAge->{'value'})/2;

        // 返回种族  
		if($tempRace->{'value'} === "Asian")  {
			$grade += 30;
			$resultStr .= "肤色很健康哦~<br>";     
		}
		else if($tempRace->{'value'} === "White") { 
			$grade += 40;
			$resultStr .= "你皮肤好白哟！^ 3^<br>"; 
		}
		else if($tempRace->{'value'} === "Black")  {
			$grade += 10;
			$resultStr .= " 0.0 你有点黑？！！！<br>";    
		}

        // 返回眼镜  
		if($tempGlass->{'value'} === "None") { 
			$grade += 30;
			$resultStr .= "不戴眼镜，看着很清爽哦！<br>";    
		} else if($tempGlass->{'value'} === "Dark")  {
			$grade += 40;
			$resultStr .= "戴个墨镜真是靓极了！<br>";    
		} else if($tempGlass->{'value'} === "Normal"){ 
			$grade += 20;
			$resultStr .= "嘿嘿，戴着眼镜呀，近视几度啦？<br>";    
		}



		$happy = '';
		if(round($tempSmiling->{'value'})>55){
			$grade += round($tempSmiling->{'value'} + 10);
			$happy = '笑得很开心嘛！继续保持哦！';
		}else if(round($tempSmiling->{'value'})>22){
			$grade += round($tempSmiling->{'value'} + 5);
			$happy = '你可以笑得更灿烂点哦！亲~';
		}else{
			$grade += round($tempSmiling->{'value'});
			$happy = '亲，有啥不开心的吗？说出来让我开心一下呗~';
		}

        //返回微笑  
		$resultStr .= "微笑度：".round($tempSmiling->{'value'})."%<br>".$happy."<br>";  

		$resultStr .= "外貌协会专家评分：".$grade."分<br>";
	}    

	if(count($faceArray) === 1){

	}else if(count($faceArray) === 2){  
        // 获取face_id  
		$tempFace = $faceArray[0];  
		$tempId1 = $tempFace->{'face_id'};  
		$tempFace = $faceArray[1];  
		$tempId2 = $tempFace->{'face_id'};  


        // face++ 链接  
  		$jsonStr=file_get_contents("http://apicn.faceplusplus.com/v2/recognition/compare?api_secret=".$api_secret."&api_key=".$api_key."&face_id2=".$tempId2."&face_id1=".$tempId1);  
		$replyDic=json_decode($jsonStr);  

        //取出相似程度  
		$tempResult = $replyDic->{'similarity'};  
		$suggest = '';
		if(round($tempResult)>55){
			$suggest = '哇塞！绝对的夫妻相了！';
		}else if(round($tempResult)>40){
			$suggest = "哎哟，长得挺像！<br>你们快点在一起吧！";
		}else{
			$suggest = '0.0 长得不太一样哦。<>';
		}

		$resultStr .= "<----匹配结果----><br>夫妻相程度：".round($tempResult)."%<br>".$suggest." <br>";  

        //具体分析相似处  
		$tempSimilarity = $replyDic->{'component_similarity'};  
		$tempEye = $tempSimilarity->{'eye'};  
		$tempEyebrow = $tempSimilarity->{'eyebrow'};  
		$tempMouth = $tempSimilarity->{'mouth'};  
		$tempNose = $tempSimilarity->{'nose'};  

		$resultStr .= "~~~~~~~~~~<br>相似度分析：<br>";  
		$resultStr .= "眼睛：".round($tempEye)."%<br>";  
		$resultStr .= "眉毛：".round($tempEyebrow)."%<br>";  
		$resultStr .= "嘴巴：".round($tempMouth)."%<br>";  
		$resultStr .= "鼻子：".round($tempNose)."%<br>";  
		
	}  


    //如果没有检测到人脸  
	if($resultStr === "")  
		$resultStr = "悟空，你又调皮了！照片中木有人脸！ =.=<br>";  

	pdo_insert('xhw_face', array(
	'weid' => $_W['uniacid'],	
	'description' => $resultStr,
	'picurl' => $picurl,
	'grade' => $grade,
				));
	$id = pdo_insertid();
    //返回数据
     return $this->respNews(array(
        	'title' => '哇！识别结果出来了',
        	//'description' => $resultStr,
        	'description' => "点击查看外貌协会专家评分",  	
        	'picurl' => $picurl,
        	'Url' => $this->createmobileUrl('item',array('do'=>'itme', 'id'=>$id,'share'=>'1')),
        ));    
        
	}


//-------------------------------人脸识别（end）---------------------------------------//
    
}

<?php
/**
 * 微旅游微信短控制
 * @author Freedom QQ:350826748
 * @url 
 */
$from_user = $_W['fans']['from_user'];
if($do =='jieshao'){
	$table = tablename('weilvyou_jianjie');
	$item = pdo_fetch("SELECT * FROM {$table} WHERE weid={$weid}");
	include $this->template('jieshao');
}elseif($do =='xiangce'){
	$table = tablename('weilvyou_xiangce');
	if($_GET['ajax']){
		$list = pdo_fetchall("SELECT * FROM {$table} WHERE weid={$weid} ORDER BY sort DESC LIMIT 10");
		foreach ($list as $k => $v) {
			$v['img']= $_W['attachurl'].$v['img'];
			$arr['title'] = $v['title'];
			$arr['ps1'] = array(array('type'=>'title','title'=>$v['title'],'subTitle'=>$v['title']));
			$arr['ps2'] = array(array('type'=>'img','name'=>$v['title'],'img'=>$v['img'],'size'=>array(300,300)),array('type'=>'text','content'=>$v['jianjie']));
			$json[]=$arr;

		}

		#$a ='[{"title":"\u98ce\u666f\u56fe\u518c1","ps1":[{"type":"title","title":"\u98ce\u666f\u56fe\u518c1","subTitle":"\u98ce\u666f\u56fe\u518c1"},{"type":"img","name":"432252_195440687000_2.jpg","img":"http:\/\/eeuu.biz\/\/ups\/2014\/03\/18\/088afe0f22f0a24cf69e19c2db8a0bcc.jpg","size":[687,450]}],"ps2":[{"type":"img","name":"5975061_213635921000_2.jpg","img":"http:\/\/eeuu.biz\/\/ups\/2014\/03\/18\/d7ba734f3c875f7a696484944264207d.jpg","size":[717,450]},{"type":"text","content":"\u84dd\u5929\u767d\u4e91\u9ed1\u571f"},{"type":"img","name":"\u7231\u58c1\u7eb8HD-10101220.jpg","img":"http:\/\/eeuu.biz\/\/ups\/2014\/03\/18\/17a40b5f59bd1c37feed64f18a57178b.jpg","size":[298,450]}]},{"title":"\u98ce\u666f\u76f8\u518c2","ps1":[{"type":"title","title":"\u98ce\u666f\u76f8\u518c2","subTitle":"\u98ce\u666f\u76f8\u518c2"}],"ps2":[{"type":"img","name":"\u7231\u58c1\u7eb8HD-10665387.jpg","img":"http:\/\/eeuu.biz\/\/ups\/2014\/03\/18\/116b095543520a2ca18e1eb7d2ea4892.jpg","size":[298,450]},{"type":"text","content":"\u98ce\u666f\u76f8\u518c2\u76f8\u518c\u4ecb\u7ecd\u76f8\u518c\u4ecb\u7ecd"}]}]';
		#print_r(json_decode($a));
		#echo $a;
		echo json_encode($json);
		exit;
	}
	include $this->template('xiangce');
}elseif($do =='jingdian'){
	$table_jq = tablename('weilvyou_jingqu');
	$table_jd = tablename('weilvyou_jingdian');
	$list = pdo_fetchall("SELECT * FROM {$table_jq} WHERE weid={$weid} ORDER BY sort DESC LIMIT 300");
	if($list){
		foreach ($list as $k => $v) {
			$ids[] = $v['id'];
		}
		$ids = implode(',',$ids);
		if($ids){
			$jingdian = pdo_fetchall("SELECT * FROM {$table_jd} WHERE weid={$weid} AND jid in($ids) ORDER BY sort DESC LIMIT 300");
			if($jingdian){
				foreach ($jingdian as $jk => $jv) {
					foreach ($list as $lk => &$lv) {
						if($jv['jid'] == $lv['id']){
							$lv['son'][] = $jv;
						}
					}
				}
			}
		}
	}
	include $this->template('jingdian');

}elseif($do=='jingdian_pic'){
	if($_GET['ajax']){
		$jid = intval($_GET['jid']);
		$table = tablename('weilvyou_jingdian');
		$table_jq = tablename('weilvyou_jingqu');
		$item = pdo_fetch("SELECT * FROM {$table} WHERE id={$jid} AND weid={$weid}");
		if($item)
		$jingqu = pdo_fetch("SELECT title FROM {$table_jq} WHERE id={$item['jid']} AND weid={$weid}");
		$item['img'] = $_W['attachurl'].$item['img'];
		$imgs['id']=$item['id'];
		$imgs['name']=$item['title'];
		$imgs['tit']=$item['title'];
		$imgs['desc']=$item['jianjie1'];
		
		$imgs['area']=$jingqu['title'];
		$imgs['bimg']=$item['img'];
		$imgs['height']=1600;
		$imgs['width']=1600;
		$imgs['dtitle']=array($item['title']);
		$imgs['dlist']=array($item['jianjie2']);
		$imgs['pics']=array();
		$arr['rooms'][]  = $imgs;
		echo json_encode($arr);
		exit;
	}
	include $this->template('jingdian_pic');
}elseif($do=='yinxiang'){
	$table_yx = tablename('weilvyou_yinxiang');
	$table_dp = tablename('weilvyou_dianping');
	if($_GET['typ']=='getres'){
		$yinxiang = pdo_fetchall("SELECT * FROM {$table_yx} WHERE weid={$weid} ORDER BY sort DESC LIMIT 300");
		$json['msg'] ='ok';
		$json['ret'] ='0';
		
		foreach ($yinxiang as $k => $v) {
			$json['top'][$k]['content'] = $v['title'];
			$json['top'][$k]['count'] = $v['num'];
			$json['top'][$k]['id'] = $v['id'];
			if($from_user == $v['from_user']){
				$json_id =$v['id'];
			}
		}
		$json['user'] = array('content'=>'','count'=>0,'id'=>$json_id?$json_id:'-1');
		$json['sum'] ='0';
		#$str ='{"msg":"ok","ret":"0","user":{"content":"","count":0,"id":-1},"top":[{"content":"\u98ce\u666f\u79c0\u4e3d","count":"0","id":"3"},{"content":"\u6709\u70b9\u4e8c\u53c9","count":"0","id":"4"},{"content":"\u5927\u7237\u7237\u7684","count":"0","id":"5"},{"content":"\u9700\u8981\u516d\u4e2a","count":"0","id":"6"},{"content":"\u8fd8\u5dee\u4e00\u4e2a","count":"0","id":"7"},{"content":"\u7ec8\u4e8e\u641e\u5b9a","count":"0","id":"8"}],"sum":0}';
		echo json_encode($json);
		exit;
	}elseif($_GET['rtyp']=='getdp'){
		
		$list = pdo_fetchall("SELECT * FROM {$table_dp} WHERE weid={$weid} ORDER BY sort DESC LIMIT 300");
		foreach ($list as $k => $v) {
			$json[$k]['name']=$v['xingming'];
			$json[$k]['title']=$v['title'];
			$json[$k]['photo']=$_W['attachurl'].$v['img'];
			$json[$k]['intro']=$v['zhiwei'];
			$json[$k]['reviewDesc']=$v['jianjie1'];
			$json[$k]['reviewTitle']=$v['jianjie2'];
		}
		echo json_encode($json);
		exit;
	}elseif($_GET['typ']=='setres'){
		$post['from_user'] = $from_user;
		$post['title'] = trim($_GET['content']);
		$post['weid'] = $weid;
		$post['dateline'] = $timestamp;
		$result_id = $this->sql_ext($table_yx,$post,$fields);
		if($result_id ){
			$yinxiang = pdo_fetchall("SELECT * FROM {$table_yx} WHERE weid={$weid} ORDER BY sort DESC LIMIT 300");
			$json['msg'] ='ok';
			$json['ret'] ='0';
			$user=array('content'=>$post['title'] ,'count'=>1,'id'=>$result_id);
			foreach ($yinxiang as $k => $v) {
				$json['top'][$k]['content'] = $v['title'];
				$json['top'][$k]['count'] = $v['num'];
				$json['top'][$k]['id'] = $v['id'];
			}
			$json['user'] = $user;
			echo json_encode($json);
		}
		#$a= '{"msg":"ok","ret":0,"user":{"content":"\u672a\u5b8c\u5168\u4fc4","count":1,"id":201},"top":[{"content":"\u5ea6\u5047\u51a0\u519b","count":29,"id":38},{"content":"\u5ab2\u7f8e\u767d\u6ee9","count":25,"id":39},{"content":"\u9b45\u529b\u6c99\u6ee9","count":17,"id":40},{"content":"\u5962\u534e\u914d\u5957","count":12,"id":41},{"content":"\u8d34\u5fc3\u7269\u7ba1","count":10,"id":42},{"content":"\u5e38\u4f4f\u9996\u9009","count":6,"id":43}],"sum":100}';
		exit;
		
	}
	include $this->template('yinxiang');
}else{
	$table = tablename('weilvyou_haibao');
	$item = pdo_fetch("SELECT * FROM {$table} WHERE weid={$weid} LIMIT 4");
	for($i=0;$i<=5;$i++){
		if($item['img'.$i]){
			$item['img'.$i] = $_W['attachurl'].$item['img'.$i];
		}else{
			$item['img'.$i]= IMG."estate_home.png";
		}
	}
	
	include $this->template('index');
}
?>
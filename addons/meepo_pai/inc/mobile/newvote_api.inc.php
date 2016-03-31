<?php
/*
 * meepo_pai
 * 
 * pid
 * sex
 * src_img
 * uid
 * vid
 * num
 * school
 * */

/* 8000 女生
 * 
 * 8102 男生
 * 
 * */
global $_W,$_GPC;
$sex = $_GPC['sex'];//性别

$content = get_newvote_example($sex);
$num_start = rand(1,count($content));
$num_end = $num_start+1;
if(!empty($content) && count($content)>1){
	if($num_end<count($content)){
		//正常显示
		$value = array(
			'status'=>8000,
			'content'=>array($content[$num_start],$content[$num_end])
		);
	}else{
		$value = array(
			'status'=>8104,
			'content'=>array()
		);
	}
}


die(json_encode($value));

function get_newvote_example($sex){
	$return = array();
	$return[] = array(
		'nickname'=>'Betty',
		'school'=>'广东医学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/46f4c77a2bf3cd4145e5ea76e818d841?imageView/2/w/240'
	); 
	$return[] = array(
		'nickname'=>'谢毛毛',
		'school'=>'北京城市学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/6191257b6c0f92627b0a3e5897a3a531?imageView/2/w/240'
	);
	$return[] = array(
		'nickname'=>'谢毛毛',
		'school'=>'北京城市学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/6191257b6c0f92627b0a3e5897a3a531?imageView/2/w/240'
	);
	$return[] = array(
		'nickname'=>'谢毛毛',
		'school'=>'北京城市学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/6191257b6c0f92627b0a3e5897a3a531?imageView/2/w/240'
	);
	$return[] = array(
		'nickname'=>'谢毛毛',
		'school'=>'北京城市学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/6191257b6c0f92627b0a3e5897a3a531?imageView/2/w/240'
	);
	$return[] = array(
		'nickname'=>'谢毛毛',
		'school'=>'北京城市学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/6191257b6c0f92627b0a3e5897a3a531?imageView/2/w/240'
	);
	$return[] = array(
		'nickname'=>'谢毛毛',
		'school'=>'北京城市学院',
		'sex'=>'0',
		'src_img'=>'http://pkphoto.qiniudn.com/6191257b6c0f92627b0a3e5897a3a531?imageView/2/w/240'
	);
	 return $return;
}

function get_newvote($sex){
	global $_W,$_GPC;
	$pais = pdo_fetchall("SELECT p.*,m.realname as realname FROM ".tablename('meepo_pai')." AS p LEFT JOIN ".tablename('mc_members')." AS m ON p.uid = m.uid AND p.sex ='{$sex}' ORDER BY rand() DESC");
	
	return $pais;
}

<?php

$navs = array();
$navs[] = array(
			'title'=>'基本设置',
			'href'=>$this->createWebUrl('setting',array('act'=>'setting','op'=>'base')),
			'fa'=>'fa fa-book',
			'op'=>'base'
		);	
	
$navs[] = array(
			'title'=>'分享设置',
			'href'=>$this->createWebUrl('setting',array('act'=>'setting','op'=>'share')),
			'fa'=>'fa fa-cubes',
			'op'=>'share'
		);
$navs[] = array(
			'title'=>'分销融合',
			'href'=>$this->createWebUrl('setting',array('act'=>'setting','op'=>'affiliate')),
			'fa'=>'fa fa-star',
			'op'=>'affiliate'
		);		
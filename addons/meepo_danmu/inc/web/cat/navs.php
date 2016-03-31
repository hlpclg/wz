<?php

$navs = array();
$navs[] = array(
			'title'=>'链接管理',
			'href'=>$this->createWebUrl('cat',array('act'=>'cat','op'=>'manage')),
			'fa'=>'fa fa-book',
			'op'=>'manage'
		);	
$navs[] = array(
			'title'=>'添加链接',
			'href'=>$this->createWebUrl('cat',array('act'=>'cat','op'=>'post')),
			'fa'=>'fa fa-book',
			'op'=>'post'
		);	
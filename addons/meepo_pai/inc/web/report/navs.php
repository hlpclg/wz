<?php

$navs = array();
$navs[] = array(
			'title'=>'未查看举报',
			'href'=>$this->createWebUrl('report',array('act'=>'report','op'=>'list_no')),
			'fa'=>'fa fa-book',
			'op'=>'list_no'
		);	
$navs[] = array(
			'title'=>'已忽略举报',
			'href'=>$this->createWebUrl('report',array('act'=>'report','op'=>'list_san')),
			'fa'=>'fa fa-book',
			'op'=>'list_san'
		);			
$navs[] = array(
			'title'=>'已确认举报',
			'href'=>$this->createWebUrl('report',array('act'=>'report','op'=>'list_yes')),
			'fa'=>'fa fa-book',
			'op'=>'list_yes'
		);	
$navs[] = array(
			'title'=>'已处理举报',
			'href'=>$this->createWebUrl('report',array('act'=>'report','op'=>'list_done')),
			'fa'=>'fa fa-book',
			'op'=>'list_done'
		);					
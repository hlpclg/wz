<?php

$navs = array();
$navs[] = array(
			'title'=>'投票管理',
			'href'=>$this->createWebUrl('vote',array('act'=>'vote','op'=>'list')),
			'fa'=>'fa fa-book',
			'op'=>'list'
		);	
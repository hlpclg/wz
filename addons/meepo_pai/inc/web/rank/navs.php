<?php

$navs = array();
$navs[] = array(
			'title'=>'男生排名',
			'href'=>$this->createWebUrl('rank',array('act'=>'rank','op'=>'list_boy')),
			'fa'=>'fa fa-book',
			'op'=>'list_boy'
		);	
$navs[] = array(
			'title'=>'女生排名',
			'href'=>$this->createWebUrl('rank',array('act'=>'rank','op'=>'list_girl')),
			'fa'=>'fa fa-book',
			'op'=>'list_girl'
		);

		
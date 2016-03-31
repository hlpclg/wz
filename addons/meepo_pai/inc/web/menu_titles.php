<?php
$menus = array();
$menus[] = array(
	'title'=>'举报管理',
	'url'=>$this->createwebUrl('report'),
	'do'=>'report'
);

$menus[] = array(
	'title'=>'投票管理',
	'url'=>$this->createwebUrl('vote'),
	'do'=>'vote'
);

$menus[] = array(
	'title'=>'排名管理',
	'url'=>$this->createwebUrl('rank'),
	'do'=>'rank'
);

$menus[] = array(
	'title'=>'基础设置',
	'url'=>$this->createwebUrl('setting'),
	'do'=>'setting'
);
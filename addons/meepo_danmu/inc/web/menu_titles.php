<?php
$menus = array();
$menus[] = array(
	'title'=>'基本设置',
	'url'=>$this->createwebUrl('set'),
	'do'=>'set'
);

$menus[] = array(
	'title'=>'链接管理',
	'url'=>$this->createwebUrl('cat'),
	'do'=>'cat'
);

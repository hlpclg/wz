<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
if (!pdo_tableexists('ewei_shop_poster')) {
	$sql = 'CREATE TABLE IF NOT EXISTS ' . tablename('ewei_shop_poster') . ' (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
	      `uniacid` int(11) DEFAULT \'0\',
	      `type` tinyint(3) DEFAULT \'0\' COMMENT \'1 首页 2 小店 3 商城 4 自定义\',
	      `title` varchar(255) DEFAULT \'\',
	      `bg` varchar(255) DEFAULT \'\',
	      `data` text,
	      `keyword` varchar(255) DEFAULT \'\',
	      `times` int(11) DEFAULT \'0\',
	      `follows` int(11) DEFAULT \'0\',
	      `isdefault` tinyint(3) DEFAULT \'0\',
	      `resptitle` varchar(255) DEFAULT \'\',
	      `respthumb` varchar(255) DEFAULT \'\',
	      `createtime` int(11) DEFAULT \'0\',
	      `respdesc` varchar(255) DEFAULT \'\',
	      `respurl` varchar(255) DEFAULT \'\',
	      `waittext` varchar(255) DEFAULT \'\',
	      `oktext` varchar(255) DEFAULT \'\',
	      `subcredit` int(11) DEFAULT \'0\',
	      `submoney` decimal(10,2) DEFAULT \'0.00\',
	      `reccredit` int(11) DEFAULT \'0\',
	      `recmoney` decimal(10,2) DEFAULT \'0.00\',
	      `paytype` tinyint(1) DEFAULT \'0\',
	      `scantext` varchar(255) DEFAULT \'\',
	      `subtext` varchar(255) DEFAULT \'\',
	      `beagent` tinyint(3) DEFAULT \'0\',
	      `bedown` tinyint(3) DEFAULT \'0\',
	      `isopen` tinyint(3) DEFAULT \'0\',
	      `opentext` varchar(255) DEFAULT \'\',
	      `openurl` varchar(255) DEFAULT \'\',
	      PRIMARY KEY (`id`),
	      KEY `idx_uniacid` (`uniacid`),
	      KEY `idx_type` (`type`),
	      KEY `idx_times` (`times`),
	      KEY `idx_isdefault` (`isdefault`),
	      KEY `idx_createtime` (`createtime`)
	    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;';
	pdo_query($sql);
	$sql = 'CREATE TABLE IF NOT EXISTS ' . tablename('ewei_shop_poster_log') . ' (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
	      `uniacid` int(11) DEFAULT \'0\',
	      `openid` varchar(255) DEFAULT \'\',
	      `posterid` int(11) DEFAULT \'0\',
	      `from_openid` varchar(255) DEFAULT \'\',
	      `subcredit` int(11) DEFAULT \'0\',
	      `submoney` decimal(10,2) DEFAULT \'0.00\',
	      `reccredit` int(11) DEFAULT \'0\',
	      `recmoney` decimal(10,2) DEFAULT \'0.00\',
	      `createtime` int(11) DEFAULT \'0\',
	      PRIMARY KEY (`id`),
	      KEY `idx_uniacid` (`uniacid`),
	      KEY `idx_openid` (`openid`),
	      KEY `idx_createtime` (`createtime`),
	      KEY `idx_posterid` (`posterid`),
	      FULLTEXT KEY `idx_from_openid` (`from_openid`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
	pdo_query($sql);
	$sql = 'CREATE TABLE  IF NOT EXISTS ' . tablename('ewei_shop_poster_qr') . ' (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
	      `acid` int(10) unsigned NOT NULL,
	      `openid` varchar(100) NOT NULL DEFAULT \'\',
	      `type` tinyint(3) DEFAULT \'0\',
	      `sceneid` int(11) DEFAULT \'0\',
	      `mediaid` varchar(255) DEFAULT \'\',
	      `ticket` varchar(250) NOT NULL,
	      `url` varchar(80) NOT NULL,
	      `createtime` int(10) unsigned NOT NULL,
	      `goodsid` int(11) DEFAULT \'0\',
	      `qrimg` varchar(1000) DEFAULT \'\',
	      PRIMARY KEY (`id`),
	      KEY `idx_acid` (`acid`),
	      KEY `idx_sceneid` (`sceneid`),
	      KEY `idx_type` (`type`),
	      FULLTEXT KEY `idx_openid` (`openid`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
	$sql = 'CREATE TABLE  IF NOT EXISTS  ' . tablename('ewei_shop_poster_scan') . ' (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
	      `uniacid` int(11) DEFAULT \'0\',
	      `posterid` int(11) DEFAULT \'0\',
	      `openid` varchar(255) DEFAULT \'\',
	      `from_openid` varchar(255) DEFAULT \'\',
	      `scantime` int(11) DEFAULT \'0\',
	      PRIMARY KEY (`id`),
	      KEY `idx_uniacid` (`uniacid`),
	      KEY `idx_posterid` (`posterid`),
	      KEY `idx_scantime` (`scantime`),
	      FULLTEXT KEY `idx_openid` (`openid`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
	pdo_query($sql);
}
if (!pdo_fieldexists('ewei_shop_poster', 'paytype')) {
	pdo_query('ALTER TABLE ' . tablename('ewei_shop_poster') . ' ADD `paytype` tinyint(1) DEFAULT \'0\';');
}
pdo_update('ewei_shop_plugin', array('version' => 1.1), array('identity' => 'poster'));
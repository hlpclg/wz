<?php /*折翼天使资源社区 www.zheyitianshi.com*/ 
load()->func('pdo');
if(!pdo_tableexists('meepo_bbs_adv')){
	$sql = "CREATE TABLE `ims_meepo_bbs_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;";
	
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_adv','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_adv').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_fieldexists('meepo_bbs_topic_like','fid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD  `fid` int(11) unsigned NOT NULL DEFAULT '0';");
}

if(!pdo_tableexists('meepo_bbs_reply_ups')){
	$sql = "CREATE TABLE `ims_meepo_bbs_reply_ups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `caretetime` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;";

	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_reply_ups','rid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_reply_ups').' ADD KEY `rid`(`rid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_reply_ups','uid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_reply_ups').' ADD KEY `uid`(`uid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_reply_ups','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_reply_ups').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_set')){
	$sql = "CREATE TABLE `ims_meepo_bbs_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;";

	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_set','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_set').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_tableexists('meepo_bbs_threadclass')){
	$sql = "CREATE TABLE `ims_meepo_bbs_threadclass` (
  `typeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `moderators` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `group` varchar(132) DEFAULT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;";

	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_threadclass','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_threadclass').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_threadclass','fid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_threadclass').' ADD KEY `fid`(`fid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_topic')){
	$sql = "CREATE TABLE `ims_meepo_bbs_topic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(132) DEFAULT NULL,
  `content` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";

	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic','uid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic').' ADD KEY `uid`(`uid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_topic_like')){
	$sql = "CREATE TABLE `ims_meepo_bbs_topic_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_topic_like','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_like').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_topic_like','fid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_like').' ADD KEY `fid`(`fid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_topic_like','openid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_like').' ADD KEY `openid`(`openid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_topic_read')){
$sql = "CREATE TABLE `ims_meepo_bbs_topic_read` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;";
pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic_read','openid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_read').' ADD KEY `openid`(`openid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic_read','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_read').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_topic_replie')){
	$sql = "CREATE TABLE `ims_meepo_bbs_topic_replie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `create_at` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;";

	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic_replie','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_replie').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic_replie','uid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_replie').' ADD KEY `uid`(`uid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topic_replie','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_replie').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_topic_share')){
	$sql = "CREATE TABLE `ims_meepo_bbs_topic_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";

	pdo_query($sql);
}



if(!pdo_indexexists('meepo_bbs_topic_share','openid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_share').' ADD KEY `openid`(`openid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_topic_share','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topic_share').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}


if(!pdo_tableexists('meepo_bbs_topics')){
	$sql = "CREATE TABLE `ims_meepo_bbs_topics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(320) DEFAULT NULL,
  `tab` varchar(32) DEFAULT NULL,
  `last_reply_at` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `replycredit` int(11) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(150) DEFAULT NULL,
  `ratetimes` int(11) unsigned NOT NULL DEFAULT '0',
  `rate` int(11) unsigned NOT NULL DEFAULT '0',
  `invisible` tinyint(1) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `content` text,
  `rnum` int(11) unsigned NOT NULL DEFAULT '0',
  `lnum` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;";

	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topics','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topics').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_topics','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topics').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_topics','uid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_topics').' ADD KEY `uid`(`uid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_fieldexists('meepo_bbs_topics','lnum')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_topics`
	ADD COLUMN `lnum` int(11) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_topics','rnum')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_topics`
	ADD COLUMN `rnum` int(11) unsigned NOT NULL DEFAULT '0'");
}


if(!pdo_tableexists('meepo_bbs_blacklist')){
	$sql = "CREATE TABLE `ims_meepo_bbs_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_credit_goods')){
	$sql = "CREATE TABLE `ims_meepo_bbs_credit_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `cost` int(11) NOT NULL DEFAULT '0',
  `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展',
  `price` int(11) NOT NULL DEFAULT '100',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_credit_request')){
	$sql = "CREATE TABLE `ims_meepo_bbs_credit_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `residedist` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_api')){
	$sql = "CREATE TABLE `ims_meepo_bbs_api` (
`id` int(11) NOT NULL,
`title` varchar(32) NOT NULL DEFAULT '',
`description` varchar(132) NOT NULL DEFAULT '',
`file` varchar(132) NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	pdo_query($sql);
}


if(!pdo_tableexists('meepo_bbs_share')){
	$sql = "CREATE TABLE `ims_meepo_bbs_share` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
	`set` text NOT NULL,
	`createtime` int(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM
CHECKSUM=0
DELAY_KEY_WRITE=0;";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_task')){
	$sql = "CREATE TABLE `ims_meepo_bbs_task` (
  `taskid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `maxnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `image` varchar(150) NOT NULL DEFAULT '',
  `filename` varchar(50) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttype` varchar(20) NOT NULL DEFAULT '',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`taskid`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8";
	
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_task_user')){
	$sql = "CREATE TABLE `ims_meepo_bbs_task_user` (
  `uid` mediumint(8) unsigned NOT NULL,
  `username` char(15) NOT NULL DEFAULT '',
  `taskid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isignore` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`taskid`),
  KEY `isignore` (`isignore`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_module')){
	$sql = "CREATE TABLE `ims_meepo_module` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`module` varchar(32) NOT NULL DEFAULT '',
	`set` text NOT NULL,
	`time` int(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM
CHECKSUM=0
DELAY_KEY_WRITE=0;";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_navs')){
	$sql = "CREATE TABLE `ims_meepo_bbs_navs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(132) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `link` varchar(132) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}


if(!pdo_tableexists('meepo_bbs_home_message')){
	$sql = "CREATE TABLE `ims_meepo_bbs_home_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fromopenid` varchar(50) NOT NULL DEFAULT '',
  `toopenid` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_home_message','fromopenid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_home_message').' ADD KEY `fromopenid`(`fromopenid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_home_message','toopenid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_home_message').' ADD KEY `toopenid`(`toopenid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_indexexists('meepo_bbs_home_message','tid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_home_message').' ADD KEY `tid`(`tid`) USING BTREE;';
	pdo_query($sql);
}
if(!pdo_tableexists('meepo_sub')){
	$sql = "CREATE TABLE `ims_meepo_sub` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `level` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `level` (`level`) USING BTREE,
  KEY `num` (`num`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=73117 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_sub')){
	$sql = "CREATE TABLE `ims_meepo_sub` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `level` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `level` (`level`) USING BTREE,
  KEY `num` (`num`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_sub_log')){
	$sql = "CREATE TABLE `ims_meepo_sub_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `acid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `level` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `acid` (`acid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE,
  KEY `level` (`level`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE,
  KEY `IDX_LEVEL_FID` (`level`,`fid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('father')){
	$sql = "CREATE TABLE `ims_father` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1',
  `success` tinyint(2) NOT NULL,
  `openid` varchar(30) NOT NULL,
  `father` varchar(30) NOT NULL,
  `father_id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_user')){
	$sql = "CREATE TABLE `ims_meepo_bbs_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(42) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `online` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `acid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_user','openid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_user').' ADD KEY `openid`(`openid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_user','uid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_user').' ADD KEY `uid`(`uid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_indexexists('meepo_bbs_user','uniacid')){
	$sql = 'ALTER TABLE '.tablename('meepo_bbs_user').' ADD KEY `uniacid`(`uniacid`) USING BTREE;';
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_ec_chong_log')){
	$sql = "CREATE TABLE `ims_meepo_bbs_ec_chong_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  `fee` float unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` float unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_begging')){
	$sql = "CREATE TABLE `ims_meepo_bbs_begging` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(64) NOT NULL DEFAULT '',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `fopenid` varchar(42) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fee` float(6,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(32) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` text NOT NULL,
  `ttid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_o2o_user')){
	$sql = "CREATE TABLE `ims_meepo_bbs_o2o_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `shopid` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `set` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	pdo_query($sql);
}


if(!pdo_tableexists('meepo_bbs_o2o_user_log')){
	$sql = "CREATE TABLE `ims_meepo_bbs_o2o_user_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_log')){
	$sql = "CREATE TABLE `ims_meepo_bbs_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(42) NOT NULL DEFAULT '',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_msg_template')){
	$sql = "CREATE TABLE `ims_meepo_bbs_msg_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标题',
  `tpl_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板id',
  `template` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板内容',
  `tags` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标签',
  `set` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
	
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_bbs_rss')){
	$sql = "CREATE TABLE `ims_meepo_bbs_rss` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(132) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

	pdo_query($sql);
}


if(!pdo_tableexists('meepo_bbs_msg_template_data')){
$sql = "CREATE TABLE `ims_meepo_bbs_msg_template_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(52) NOT NULL DEFAULT '',
  `set` text NOT NULL,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tpl_id` varchar(124) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

	pdo_query($sql);
}

if(!pdo_fieldexists('meepo_bbs_msg_template','set')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD COLUMN `set` text NOT NULL");
}
if(!pdo_fieldexists('meepo_bbs_msg_template','type')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD COLUMN `type` varchar(32) NOT NULL DEFAULT ''");
}

if(!pdo_fieldexists('activity_coupon_password','uid')){
	pdo_query("ALTER TABLE ".tablename('activity_coupon_password')." ADD COLUMN `uid` int(11) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_navs','displayorder')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_navs`
	ADD COLUMN `displayorder` int(11) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_navs','enabled')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_navs`
	ADD COLUMN `enabled` tinyint(2) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_home_message','toopenid')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_home_message`
	ADD COLUMN `toopenid` varchar(50) NOT NULL DEFAULT ''");
}

if(!pdo_fieldexists('meepo_bbs_home_message','type')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_home_message`
	ADD COLUMN `type` tinyint(3) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_home_message','status')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_home_message`
	ADD COLUMN `status` tinyint(3) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_home_message','content')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_home_message`
	ADD COLUMN `content` text NOT NULL");
}

if(!pdo_fieldexists('meepo_bbs_home_message','tid')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_home_message`
	ADD COLUMN `tid` int(11) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_home_message','fromopenid')){
	pdo_query("ALTER TABLE `ims_meepo_bbs_home_message`
	ADD COLUMN `fromopenid` varchar(50) NOT NULL DEFAULT ''");
}

if(!pdo_fieldexists('meepo_bbs_topic_share','num')){
	pdo_query('ALTER TABLE `ims_meepo_bbs_topic_share` 
	ADD COLUMN `num` int(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `tid`;');
}

if(!pdo_fieldexists('meepo_bbs_share','tid')){
  pdo_query('ALTER TABLE `ims_meepo_bbs_share` 
  ADD COLUMN `tid` int(11) UNSIGNED NOT NULL DEFAULT 0 ;');
}

if(!pdo_fieldexists('meepo_bbs_topic_replie','thumb')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD COLUMN `thumb` text NOT NULL AFTER `tid`;");
}
if(!pdo_fieldexists('meepo_bbs_threadclass','group')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD COLUMN `group` varchar(132) DEFAULT NULL");
}

if(!pdo_fieldexists('meepo_bbs_threadclass','look_group')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD COLUMN `look_group` varchar(232) DEFAULT NULL");
}

if(!pdo_fieldexists('meepo_bbs_threadclass','post_group')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD COLUMN `post_group` varchar(232) DEFAULT NULL");
}

if(!pdo_fieldexists('meepo_bbs_topics','thumb')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD COLUMN `thumb` text DEFAULT NULL");
}



if(!pdo_fieldexists('meepo_bbs_log','type')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD COLUMN `type` varchar(32) NOT NULL DEFAULT '' ");
}

if(!pdo_fieldexists('meepo_bbs_begging','status')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD COLUMN `status` tinyint(2) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_begging','transid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD COLUMN `transid` varchar(32) NOT NULL DEFAULT ''");
}

if(!pdo_fieldexists('meepo_bbs_begging','content')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD COLUMN `content` text NOT NULL");
}

if(!pdo_fieldexists('meepo_bbs_begging','thumb')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD COLUMN `thumb` text NOT NULL");
}

if(!pdo_fieldexists('meepo_bbs_begging','ttid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD COLUMN `ttid` int(11) unsigned NOT NULL DEFAULT '0'");
}


if(!pdo_fieldexists('meepo_bbs_o2o_user','acid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD COLUMN `acid` int(11) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_topic_replie','fid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD COLUMN `fid` int(11) unsigned NOT NULL DEFAULT '0'");
}


if(!pdo_fieldexists('meepo_bbs_topic_replie','beggingid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD COLUMN `beggingid` int(11) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_ec_chong_log','status')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD COLUMN `status` tinyint(2) unsigned NOT NULL DEFAULT '0'");
}

if(!pdo_fieldexists('meepo_bbs_ec_chong_log','transid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD COLUMN `transid` varchar(64) NOT NULL DEFAULT ''");
}

pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'help','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'upgate','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'home_credit_goods','entry'=>'cover'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'qiniu','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'adv','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'set','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'threadclass','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'credit','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'task','entry'=>'menu'));
pdo_delete('modules_bindings',array('module'=>'meepo_bbs','do'=>'nav','entry'=>'menu'));

pdo_update('modules',array('isrulefields'=>1),array('name'=>'meepo_bbs'));
pdo_update('modules',array('version'=>'3.1.1'),array('name'=>'meepo_bbs'));

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'index',':entry'=>'menu');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'index','title'=>'管理社区','entry'=>'menu'));
}

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'forum',':entry'=>'cover');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'forum','title'=>'社区首页','entry'=>'cover'));
}

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'home',':entry'=>'cover');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'home','title'=>'个人中心','entry'=>'cover'));
}

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'task',':entry'=>'cover');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'task','title'=>'任务大厅','entry'=>'cover'));
}

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'activity_token',':entry'=>'cover');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'activity_token','title'=>'积分兑换','entry'=>'cover'));
}

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'forum_cat',':entry'=>'cover');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'forum_cat','title'=>'版块入口','entry'=>'cover'));
}

if(!pdo_fieldexists('meepo_bbs_threadclass','isgood')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD COLUMN `isgood` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 ");
}

if(!pdo_fieldexists('meepo_bbs_adv','typeid')){
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD   `typeid` int(11) unsigned NOT NULL DEFAULT '0';");
}

$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
$params = array(':module'=>'meepo_bbs',':do'=>'oto',':entry'=>'cover');
$is = pdo_fetch($sql,$params);
if(empty($is)){
	pdo_insert('modules_bindings',array('module'=>'meepo_bbs','do'=>'oto','title'=>'o2o店员申请','entry'=>'cover'));
}


//安装微乞丐
$sql = "SELECT * FROM ".tablename('modules')." WHERE name = :name ";
$params = array(':name'=>'meepo_begging');
$is = pdo_fetch($sql,$params);
if(!file_exists(IA_ROOT.'/addons/meepo_begging/site.php')){
	pdo_delete('modules',array('name'=>'meepo_begging'));
	pdo_delete('modules_bindings',array('module'=>'meepo_begging'));
}else{
	if(empty($is)){
		if(file_exists(IA_ROOT.'/addons/meepo_begging/site.php')){
			$data = array();
			$data['name'] = 'meepo_begging';
			$data['type'] = 'business';
			$data['title'] = '微乞丐';
			$data['version'] = '3.1.1';
			$data['ability'] = '网络乞讨，朋友圈蹭钱，靠脸吃饭的时代已经到来，抓紧行动起来，让我们一起做---微乞丐！';
			$data['description'] = '网络乞讨，朋友圈蹭钱，靠脸吃饭的时代已经到来，抓紧行动起来，让我们一起做---微乞丐！';
			$data['author'] = 'meepo';
			$data['url'] = 'http://bbs.012wz.com/';
			$data['settings'] = 1;
			$data['subscribes'] = 'a:1:{i:0;s:4:"text";}';
			$data['handles'] = 'a:1:{i:0;s:4:"text";}';
			$data['isrulefields'] = 0;
			$data['issystem'] = 0;
			$data['issolution'] = 0;
			$data['target'] = 0;
			$data['iscard'] = 1;
			pdo_insert('modules',$data);
	
			$cover = array();
			$cover['module'] = 'meepo_begging';
			$cover['entry'] = 'profile';
			$cover['title'] = '我要乞讨';
			$cover['do'] = 'index';
			pdo_insert('modules_bindings',$cover);
			$cover['entry'] = 'menu';
	
			$cover['title'] = '红包参数';
			$cover['do'] = 'hongset';
			pdo_insert('modules_bindings',$cover);
			$cover['title'] = '发放饭钱';
			$cover['do'] = 'money';
			pdo_insert('modules_bindings',$cover);
			$cover['title'] = '乞讨管理';
			$cover['do'] = 'manage';
			pdo_insert('modules_bindings',$cover);
	
			$cover['entry'] = 'cover';
			$cover['title'] = '我要乞讨';
			$cover['do'] = 'index';
			pdo_insert('modules_bindings',$cover);
	
		}
	}else{
		$cover = array();
		$cover['module'] = 'meepo_begging';
		$cover['entry'] = 'cover';
		$cover['title'] = '我要乞讨';
		$cover['do'] = 'index';
		pdo_insert('modules_bindings',$cover);
	}
}


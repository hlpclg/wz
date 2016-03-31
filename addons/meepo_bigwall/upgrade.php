<?php
$sql = "
CREATE TABLE IF NOT EXISTS `ims_weixin_cookie` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`cookie` text NOT NULL,  
`cookies` text NOT NULL, 
`token` int(11) NOT NULL,
 `weid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `ims_weixin_flag` (
`id` int(11) NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `openid` varchar(255) NOT NULL,
 `fakeid` varchar(100) NOT NULL,
 `flag` int(11) NOT NULL,
 `vote` int(11) NOT NULL,
 `nickname` varchar(255) NOT NULL,
 `avatar` text NOT NULL,
`content` text NOT NULL,
 `sex` varchar(255) NOT NULL,
 `cjstatu` tinyint(4) NOT NULL DEFAULT '0',
`rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
 `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否正在加入话题',
 `isblacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否是黑名单',
 `lastupdate` int(10) unsigned NOT NULL COMMENT '用户最后发表时间',
 `verify` varchar(10) NOT NULL,
  `status` int(1) NOT NULL,
 `othid` int(10) NOT NULL,
 `sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否已经签到',
 `signtime` int(12) NOT NULL DEFAULT '0' COMMENT '用户签到时间',
 `getaward` int(12) NOT NULL DEFAULT '0',
 `msgid` varchar(12) NOT NULL,
 `mobile` varchar(15) NOT NULL,
 `realname` varchar(20) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weixin_vote` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`name` text NOT NULL,
`res` int(11) NOT NULL,
`rid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weixin_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
`openid` varchar(100) NOT NULL,
 `messageid` int(11) NOT NULL,
 `num` int(11) NOT NULL,
`content` text NOT NULL,
`nickname` text NOT NULL,
 `avatar` text NOT NULL,
`ret` int(11) NOT NULL,
 `status` int(11) NOT NULL,
 `image` text NOT NULL, 
`type` varchar(10) NOT NULL COMMENT '发表内容类型',
 `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
 `createtime` int(10) NOT NULL,
`rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
 `isblacklist` int(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weixin_shake_toshake` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_weixin_wall_num` (
`id` int(11) NOT NULL AUTO_INCREMENT,
 `rid` int(10)  NOT NULL COMMENT '用户当前所在的微信墙话题',
 `num` int(11) NOT NULL,
`weid` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `ims_weixin_wall_reply` (
`id` int(10)  NOT NULL AUTO_INCREMENT,
`rid` int(10)  NOT NULL COMMENT '规则ID',
 `weid` int(10) NOT NULL,
 `enter_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '进入提示',
 `subit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '首次关注进入提示',
 `quit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '退出提示',
 `send_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
 `quit_command` varchar(10) NOT NULL DEFAULT '' COMMENT '退出指令', 
 `timeout` int(10)  NOT NULL DEFAULT '0' COMMENT '超时时间',  
`isshow` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
 `lurumobile` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `chaoshi_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
  `isopen` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '摇一摇状态',
`votetitle` varchar(300) NOT NULL DEFAULT '' COMMENT '投票标题',
`votepower` varchar(300) NOT NULL DEFAULT '' COMMENT '投票页面版权',
`yyyzhuti` varchar(300) NOT NULL DEFAULT '' COMMENT '摇一摇主题',
`cjname` varchar(300) NOT NULL DEFAULT '' COMMENT '抽奖名字',
`cjimgurl` varchar(300) NOT NULL DEFAULT '' COMMENT '抽奖主题图片',
`loginpass` varchar(300) NOT NULL DEFAULT '' COMMENT '主持人登录密码',
`indexstyle` varchar(300) NOT NULL DEFAULT '' COMMENT '风格',
`danmutime` int(10)  NOT NULL DEFAULT '20' COMMENT '弹幕时间',
`refreshtime` int(10)  NOT NULL DEFAULT '0' COMMENT '刷新时间',
`yyyendtime` int(10) NOT NULL DEFAULT '0' COMMENT '摇一摇结束总摇晃数目',
`yyyshowperson` int(10)  NOT NULL DEFAULT '0' COMMENT '摇一摇结果显示人数',
`voterefreshtime` int(10)  NOT NULL DEFAULT '0' COMMENT 'tp刷新时间',
`qdqshow` int(10)  NOT NULL DEFAULT '0' COMMENT '签到墙是否显示',
`yyyshow` int(10)  NOT NULL DEFAULT '0' COMMENT '摇一摇是否显示',
`ddpshow` int(10)  NOT NULL DEFAULT '0' COMMENT '对对碰是否显示',
`tpshow` int(10)  NOT NULL DEFAULT '0' COMMENT '投票是否显示',
`cjshow` int(10) NOT NULL DEFAULT '0' COMMENT '抽奖是否显示',
`danmushow` int(10)  NOT NULL DEFAULT '0' COMMENT '抽奖是否显示',
`cjnum_tag` int(10)  NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否开启',
`cjnum_exclude` int(10)  NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否可以重复中奖',
`cjtag_exclude` int(10)  NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否可以重复中奖',
`defaultshow` int(10)  NOT NULL DEFAULT '2' COMMENT '默认打开哪面墙',
`yyyrealman` int(10)  NOT NULL DEFAULT '0' COMMENT '真实人数',
`yyybgimg` varchar(300) NOT NULL COMMENT '摇一摇背景',
`danmubgimg` varchar(300) NOT NULL COMMENT '弹幕背景',
`saywords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`signwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`cjwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`votewords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`ddpwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`danmuwords` varchar(300) NOT NULL COMMENT '弹幕标题',
`toplogo` varchar(300) NOT NULL COMMENT '弹幕标题',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ims_weixin_awardlist` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`displayid` int(10)  NOT NULL DEFAULT '0' COMMENT '排序', 
`weid` int(10)  NOT NULL COMMENT '主公众号', 
 `luck_name` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
 `luckid` int(10) NOT NULL DEFAULT '0' COMMENT '奖项活动ID来此关键词的rid也是按人数抽奖的id',
 `num` int(10) NOT NULL DEFAULT '0' COMMENT '此项奖品的已经中奖人数',
 `tag_name` varchar(100) NOT NULL DEFAULT '' COMMENT '第几等奖',
 `tagNum` int(10) NOT NULL DEFAULT '0' COMMENT '奖品数量',
 `num_exclude` tinyint(1)  NOT NULL DEFAULT '1' COMMENT '是否准许按人数抽奖的时候重复中奖',
`tag_exclude` tinyint(1)  NOT NULL DEFAULT '1' COMMENT '是否准许按第几等奖抽奖的时候重复中奖',
 `nd` varchar(500)   NULL  COMMENT '内定抽奖粉丝ID字符串',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weixin_luckuser` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10)  NOT NULL COMMENT '主公众号',  
 `awardid` int(10) NOT NULL DEFAULT '0' COMMENT '奖项活动ID',
 `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '中奖时间',
 `openid` varchar(200) NOT NULL DEFAULT '' COMMENT '粉丝标识',
 `bypername` varchar(200) NULL  COMMENT '默认为空，只要选择了按人数才能有值',
 `rid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weixin_shake_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_query($sql);
if(!pdo_fieldexists('weixin_luckuser', 'bypername')) {
    pdo_query("ALTER TABLE ".tablename('weixin_luckuser')." ADD `bypername` varchar(200)  NULL  COMMENT '默认为空，只要选择了按人数才能有值';");
}
if(!pdo_fieldexists('weixin_awardlist', 'nd')) {
    pdo_query("ALTER TABLE ".tablename('weixin_awardlist')." ADD `nd` varchar(500)  NULL  COMMENT '内定抽奖粉丝ID字符串';");
}

 if(!pdo_fieldexists('weixin_flag', 'mobile')) {
    pdo_query("ALTER TABLE ".tablename('weixin_flag')." ADD `mobile` varchar(20) NOT NULL  COMMENT '粉丝手机号码';");
}
if(!pdo_fieldexists('weixin_flag', 'realname')) {
    pdo_query("ALTER TABLE ".tablename('weixin_flag')." ADD `realname` varchar(20) NOT NULL COMMENT '粉丝真实姓名';");
}
if(!pdo_fieldexists('weixin_flag', 'cjname')) {
    pdo_query("ALTER TABLE ".tablename('weixin_flag')." ADD `cjname` varchar(20) NOT NULL COMMENT '奖项备注';");
}
//0810
if(!pdo_fieldexists('weixin_wall_reply', 'yyyrealman')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `yyyrealman` int(10)  NOT NULL DEFAULT '0' COMMENT '真实人数';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'yyybgimg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `yyybgimg` varchar(300) NOT NULL COMMENT '摇一摇背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmubgimg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmubgimg` varchar(300) NOT NULL COMMENT '弹幕背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'saywords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `saywords` varchar(300) NOT NULL COMMENT '摇一摇背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'signwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `signwords` varchar(300) NOT NULL COMMENT '摇一摇背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'cjwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `cjwords` varchar(300) NOT NULL COMMENT '摇一摇背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'votewords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `votewords` varchar(300) NOT NULL COMMENT '摇一摇背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'ddpwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `ddpwords` varchar(300) NOT NULL COMMENT '摇一摇背景';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmuwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmuwords` varchar(300) NOT NULL COMMENT '弹幕标题';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'toplogo')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `toplogo` varchar(300) NOT NULL COMMENT '弹幕标题';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmutime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmutime` int(10)  NOT NULL DEFAULT '20' COMMENT '弹幕时间';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmushow')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmushow` int(10)  NOT NULL DEFAULT '0' COMMENT '抽奖是否显示';");
}
if(!pdo_fieldexists('weixin_awardlist', 'displayid')) {
    pdo_query("ALTER TABLE ".tablename('weixin_awardlist')." ADD `displayid` int(10)  NOT NULL DEFAULT '0' COMMENT '排序';");
}
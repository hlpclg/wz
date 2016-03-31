<?php








if(!pdo_fieldexists('mon_wkj_firend', 'kh_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `kh_price` float(10,2) ;");
}





if(!pdo_fieldexists('mon_wkj', 'p_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_url` varchar(500) ;");
}

if(!pdo_fieldexists('mon_wkj', 'copyright_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `copyright_url` varchar(500) ;");
}


if(!pdo_fieldexists('mon_wkj', 'hot_tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `hot_tel` varchar(50) ;");
}


if(!pdo_fieldexists('mon_wkj', 'p_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_intro` varchar(1000) ;");
}




if(!pdo_fieldexists('mon_wkj', 'kj_dialog_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `kj_dialog_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'rank_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `rank_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'u_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `u_fist_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'u_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `u_already_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'fk_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `fk_fist_tip` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_wkj', 'fk_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `fk_already_tip` varchar(1000) ;");
}

if(!pdo_fieldexists('mon_wkj', 'kj_rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `kj_rule` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_wkj', 'yf_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `yf_price` float(10,2) default 0 ;");
}



/**
 * 订单
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_wkj_order') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`kid` int(10) NOT NULL,
`uid` int(10) NOT NULL,
`uname` varchar(100),
`address` varchar(100),
`tel` varchar(50),
 `openid` varchar(200) ,
 `order_no` varchar(100) ,
 `wxorder_no` varchar(100) ,
  `y_price` float(10,2),
  `kh_price` float(10,2),
  `yf_price` float(10,2),
 `total_price` float(10,2),
 `status` int(1),
 `wxnotify` varchar(200),
  `notifytime` int(10) DEFAULT 0,
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 * 设置
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_wkj_setting') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`appid` varchar(200) ,
`appsecret` varchar(200),
`mchid` varchar(100),
`shkey` varchar(100),
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);




/**  1.0.2 功能*****/

if(!pdo_fieldexists('mon_wkj', 'pay_type')) {
 pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `pay_type` int(2) ;");
}



if(!pdo_fieldexists('mon_wkj', 'p_model')) {
 pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_model` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj_order', 'pay_type')) {
 pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `pay_type` int(2) ;");
}



if(!pdo_fieldexists('mon_wkj_order', 'p_model')) {
 pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `p_model` varchar(1000) ;");
}

/**1.1.0  */

if(!pdo_fieldexists('mon_wkj_user', 'ip')) {
 pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `ip` varchar(30) ;");
}

if(!pdo_fieldexists('mon_wkj_firend', 'ip')) {
 pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `ip` varchar(30) ;");
}

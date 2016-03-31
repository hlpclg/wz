<?php

if(!pdo_fieldexists('super_securitycode_data_moban', 'brand')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD  `brand` varchar(20) NULL DEFAULT NULL;");
}
if(!pdo_fieldexists('super_securitycode_data_moban', 'spec')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD   `spec` varchar(20) NULL DEFAULT NULL;");
}
if(!pdo_fieldexists('super_securitycode_data_moban', 'weight')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD   `weight` varchar(20) NULL DEFAULT NULL;");
}

if(!pdo_fieldexists('super_securitycode_data_moban', 'remarks')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD    `remarks` varchar(100) NULL DEFAULT NULL;");
}
if(!pdo_fieldexists('super_securitycode_data_moban', 'img_banner')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD   `img_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('super_securitycode_data_moban', 'img_logo')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD    `img_logo` varchar(500) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('super_securitycode_data_moban', 'video')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD     `video` varchar(500) DEFAULT NULL COMMENT '视频';");
}
if(!pdo_fieldexists('super_securitycode_data_moban', 'buyurl')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." ADD    `buyurl` varchar(500) DEFAULT NULL COMMENT '购买链接';");
}
if(pdo_fieldexists('super_securitycode_data_moban', 'tourl')) {
	pdo_query("ALTER TABLE ".tablename('super_securitycode_data_moban')." change     `tourl`  `tourl`  varchar(500) NULL DEFAULT NULL;");
}

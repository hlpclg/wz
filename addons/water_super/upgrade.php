<?php
 if(!pdo_fieldexists('water_super_employees', 'cityid')) {
	pdo_query("ALTER TABLE ".tablename('water_super_employees')." ADD   `cityid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('water_super_employees', 'areaid')) {
	pdo_query("ALTER TABLE ".tablename('water_super_employees')." ADD  `areaid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('water_super_employees', 'city')) {
	pdo_query("ALTER TABLE ".tablename('water_super_employees')." ADD   `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('water_super_employees', 'area')) {
	pdo_query("ALTER TABLE ".tablename('water_super_employees')." ADD  `area` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('water_super_goods', 'danwei')) {
	pdo_query("ALTER TABLE ".tablename('water_super_goods')." ADD   `danwei` varchar(10) NOT NULL DEFAULT '件';");
} 
if(!pdo_fieldexists('water_super_orders', 'detail')) {
	pdo_query("ALTER TABLE ".tablename('water_super_orders')." ADD      `detail` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('water_super_shop', 'pczjs')) {
	pdo_query("ALTER TABLE ".tablename('water_super_shop')." ADD      `pczjs` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('water_super_shop', 'pfwfw')) {
	pdo_query("ALTER TABLE ".tablename('water_super_shop')." ADD   `pfwfw` varchar(500) NOT NULL;");
}
 if(!pdo_fieldexists('water_super_shop', 'pxctp1')) {
	pdo_query("ALTER TABLE ".tablename('water_super_shop')." ADD    `pxctp1` varchar(500) NOT NULL;");
}
 if(!pdo_fieldexists('water_super_shop', 'pxctp2')) {
	pdo_query("ALTER TABLE ".tablename('water_super_shop')." ADD   `pxctp2` varchar(500) NOT NULL;");
}
 if(!pdo_fieldexists('water_super_shop', 'iswww')) {
	pdo_query("ALTER TABLE ".tablename('water_super_shop')." ADD   `iswww` varchar(10) NOT NULL;");
}
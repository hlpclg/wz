<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_wkj').";");

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_wkj_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_wkj_firend').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_wkj_order').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_wkj_setting').";");
<?php
if(!pdo_fieldexists('thinkidea_phonebook_info', 'coordinate')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `coordinate` VARCHAR(50) NOT NULL COMMENT '坐标' AFTER `isauth`;");
}

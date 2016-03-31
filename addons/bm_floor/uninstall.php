<?php
/**
 * 抢楼活动模块定义
 *
 * @author 美丽心情
 * @qq 513316788
 */

$res = pdo_fetchall("SELECT id FROM ".tablename('rule')." WHERE module = :module", array(':module' => 'bm_floor'));
if ($res) {
	foreach ($res as $row) {
		$sql = "DROP TABLE `ims_bm_floor_{$row['id']}`";	
		pdo_query($sql);
	}	
}
pdo_delete('rule', array('module' => 'bm_floor'));
pdo_delete('rule_keyword', array('module' => 'bm_floor'));
$sql =<<<EOF
DROP TABLE `ims_bm_floor_award`;
DROP TABLE `ims_bm_floor_member`;
DROP TABLE `ims_bm_floor_winner`;
DROP TABLE `ims_bm_floor`;
EOF;
pdo_run($sql);

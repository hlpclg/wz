<?php /*折翼天使资源社区 www.zheyitianshi.com*/
/**
 * [WeiZan System] Copyright (c) 2014 
 * WeiZan is  a free software, it under the license terms, visited http://www./ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function back() {
	global $_W;
	static $db;
	if(empty($db)) {
		$db = new DB($_W['config']['db']['back']);
	}
	return $db;
}


function back_query($sql, $params = array()) {
	return back()->query($sql, $params);
}


function back_fetchcolumn($sql, $params = array(), $column = 0) {
	return back()->fetchcolumn($sql, $params, $column);
}

function back_fetch($sql, $params = array()) {
	return back()->fetch($sql, $params);
}

function back_fetchall($sql, $params = array(), $keyfield = '') {
	return back()->fetchall($sql, $params, $keyfield);
}


function back_update($table, $data = array(), $params = array(), $glue = 'AND') {
	return back()->update($table, $data, $params, $glue);
}


function back_insert($table, $data = array(), $replace = FALSE) {
	return back()->insert($table, $data, $replace);
}


function back_delete($table, $params = array(), $glue = 'AND') {
	return back()->delete($table, $params, $glue);
}


function back_insertid() {
	return back()->insertid();
}


function back_begin() {
	back()->begin();
}


function back_commit() {
	back()->commit();
}


function back_rollback() {
	back()->rollBack();
}


function back_debug($output = true, $append = array()) {
	return back()->debug($output, $append);
}

function back_run($sql) {
	return back()->run($sql);
}


function back_fieldexists($tablename, $fieldname = '') {
	return back()->fieldexists($tablename, $fieldname);
}


function back_indexexists($tablename, $indexname = '') {
	return back()->indexexists($tablename, $indexname);
}


function back_fetchallfields($tablename){
	$fields = back_fetchall("DESCRIBE {$tablename}", array(), 'Field');
	$fields = array_keys($fields);
	return $fields;
}


function back_tableexists($tablename){
	return back()->tableexists($tablename);
}

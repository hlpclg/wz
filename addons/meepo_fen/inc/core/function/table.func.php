<?php
load()->func('db');
function insert_menu($module,$do,$title){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'menu');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_insert('modules_bindings',array('module'=>$module,'do'=>$do,'title'=>$title,'entry'=>'menu'));
	}
}

function insert_cover($module,$do,$title){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'cover');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_insert('modules_bindings',array('module'=>$module,'do'=>$do,'title'=>$title,'entry'=>'cover'));
	}
}

function delete_cover($module,$do){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'cover');
	$is = pdo_fetch($sql,$params);
	if(empty($is)){
		pdo_delete('modules_bindings',array('module'=>$module,'do'=>$do,'entry'=>'cover'));
	}
}


function delete_menu($module,$do){
	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND do = :do AND entry = :entry";
	$params = array(':module'=>$module,':do'=>$do,':entry'=>'menu');
	$is = pdo_fetch($sql,$params);
	if(!empty($is)){
		pdo_delete('modules_bindings',array('module'=>$module,'do'=>$do,'entry'=>'menu'));
	}
}

function table_schema($table){
	global $_W;
	$table = mdb_table_schema(pdo(),$table);
	$fields = $table['fields'];
	foreach ($fields as $fi){
		if($fi['increment']){}else{
			$data['name'] = $fi['name'];
			$lsit[] = $data;
		}
	}
	
	return $list;
}

function mdb_table_schema($db, $tablename = '') {
	$result = $db->fetch("SHOW TABLE STATUS LIKE '" . trim($db->tablename($tablename), '`') . "'");
	if(empty($result)) {
		return array();
	}
	
	$ret['tablename'] = $result['Name'];
	$ret['charset'] = $result['Collation'];
	$ret['engine'] = $result['Engine'];
	$ret['increment'] = $result['Auto_increment'];
	$result = $db->fetchall("SHOW FULL COLUMNS FROM " . $db->tablename($tablename));
	foreach($result as $value) {
		$temp = array();
		$type = explode(" ", $value['Type'], 2);
		$temp['name'] = $value['Field'];
		$pieces = explode('(', $type[0], 2);
		$temp['type'] = $pieces[0];
		$temp['length'] = rtrim($pieces[1], ')');
		$temp['null'] = $value['Null'] != 'NO';
		$temp['signed'] = empty($type[1]);
		$temp['increment'] = $value['Extra'] == 'auto_increment';
		$ret['fields'][$value['Field']] = $temp;
	}
	$result = $db->fetchall("SHOW INDEX FROM " . $db->tablename($tablename));
	foreach($result as $value) {
		$ret['indexes'][$value['Key_name']]['name'] = $value['Key_name'];
		$ret['indexes'][$value['Key_name']]['type'] = ($value['Key_name'] == 'PRIMARY') ? 'primary' : ($value['Non_unique'] == 0 ? 'unique' : 'index');
		$ret['indexes'][$value['Key_name']]['fields'][] = $value['Column_name'];
	}
	return $ret;
}
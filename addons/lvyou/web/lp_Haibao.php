<?php
$table = tablename('weilvyou_haibao');
if(IS_POST){
	if(strlen($post['title']) < 3){
		message('抱歉，刚才操作的数据失败，请认真填写！','', 'error');  
	}
	$result = $this->sql_ext($table,$post,$fields);
	if($result){
		message('更新设置数据成功！', $curr_url, 'success');      
	}else{
		message('抱歉，刚才操作的数据失败！','', 'error');    
	}
	
}else{
	$item = pdo_fetch("SELECT * FROM {$table} WHERE weid={$weid}");
}
include $this->template('web_haibao');
?>
<?php
/**
 * 微旅游-》相册
 * @author Freedom QQ:350826748
 */
$table = tablename('weilvyou_xiangce');
if($op == 'post'){
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
		if($id){
			$item = pdo_fetch("SELECT * FROM {$table} WHERE id={$id} AND weid={$weid}");
		}
		include $this->template('web_xiangce_post');
	}
	
}elseif($op == 'delete'){
	if($id){
		pdo_query("DELETE FROM {$table} WHERE id={$id} AND weid={$weid}");  
	}
	message('删除数据成功！', $curr_url, 'success');
}else{
	$total = pdo_fetchcolumn("SELECT COUNT(1) FROM {$table} WHERE weid={$weid}");
	$pages = pagination($total, $page, $pagesize);
	if($total){
		$list = pdo_fetchall("SELECT * FROM {$table} WHERE weid={$weid} ORDER BY sort DESC LIMIT $offset,$pagesize");
	}
	include $this->template('web_xiangce');
}


?>
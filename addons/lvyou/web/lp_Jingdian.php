<?php
/**
 * 微旅游-》景点
 * @author Freedom QQ:350826748
 */
$table = tablename('weilvyou_jingdian');
$table_jq = tablename('weilvyou_jingqu');
if($op == 'post'){
	if(IS_POST){
		if($post['jid'] < 0){
			message('抱歉，请先添加景区！','', 'error');  
		}
		if(strlen($post['title']) < 3){
			message('请认真填写！','', 'error');  
		}
		$result = $this->sql_ext($table,$post,$fields);
		if($result){
			message('更新设置数据成功！', $curr_url, 'success');      
		}else{
			message('抱歉，刚才操作的数据失败！','', 'error');    
		}
	}else{
		$jingqu = pdo_fetchall("SELECT * FROM {$table_jq} WHERE weid={$weid} ORDER BY sort DESC LIMIT 100");
		if($id){
			$item = pdo_fetch("SELECT * FROM {$table} WHERE id={$id} AND weid={$weid}");	
		}
	}
	include $this->template('web_jingdian_post');
}elseif($op=='delete'){
	if($id){
		pdo_query("DELETE FROM {$table} WHERE id={$id} AND weid={$weid}");  
	}
	message('删除数据成功！', $curr_url, 'success');
}else{
	$total = pdo_fetchcolumn("SELECT COUNT(1) FROM {$table} WHERE weid={$weid}");
	$pages = pagination($total, $page, $pagesize);
	if($total){
		$list = pdo_fetchall("SELECT * FROM {$table} WHERE weid={$weid} ORDER BY sort DESC LIMIT $offset,$pagesize");
		if($list){
			foreach ($list as $k => $v) {
				$_jids[$v['jid']] = $v['jid'];
			}
			$jids = implode(',',$_jids);
			if($jids){
				$_jingqu = pdo_fetchall("SELECT id,title FROM {$table_jq} WHERE weid={$weid} AND id in({$jids}) ");
			}
			if($_jingqu){
				foreach ($_jingqu as $k1 => $v1) {
					$jingqu[$v1['id']] = $v1;
				}
				foreach ($list as $k => &$v) {
					$v['jingqu'] = $jingqu[$v['jid']]['title'];
				}
			}
			unset($v);
		}
	}
	include $this->template('web_jingdian');
}

?>
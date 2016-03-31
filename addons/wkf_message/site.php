<?php
defined('IN_IA') or exit('Access Denied');

class Wkf_messageModuleSite extends WeModuleSite {
	public $tablename = 'message_reply';
	//后台管理
     public function doWebManage(){
         global $_GPC, $_W;
         $weid = intval($_W['weid']);
         if (checksubmit('verify') && !empty($_GPC['select'])) {
             pdo_update('message_list', array('isshow' => 1, 'create_time' => TIMESTAMP), " id  IN  ('".implode("','", $_GPC['select'])."')");
             message('审核成功！', $this->createWebUrl('manage', array( 'page' => $_GPC['page'])));
         }
         if (checksubmit('delete') && !empty($_GPC['select'])) {
             pdo_delete('message_list', " id  IN  ('".implode("','", $_GPC['select'])."')");
             message('删除成功！', $this->createWebUrl('manage', array( 'page' => $_GPC['page'])));
         }
         $isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
         $pindex = max(1, intval($_GPC['page']));
         $psize = 20;

         $list = pdo_fetchall("SELECT * FROM ".tablename('message_list')." WHERE weid = '{$_W['weid']}' AND isshow = '$isshow' ORDER BY create_time DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
         if (!empty($list)) {
             $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('message_list') . " WHERE weid = '{$_W['weid']}' AND isshow = '$isshow'");
             $pager = pagination($total, $pindex, $psize);

             foreach ($list as &$row) {
                 $row['content'] = emotion($row['content']);
                 $userids[] = $row['from_user'];
             }
             unset($row);
         }
         include $this->template('list');
     }
    //微信端管理
	public function doMobilelist() {
		global $_GPC, $_W;
        $from_user = $this->_fromuser;
        $weid = $this->_weid;
	    $messagecount=pdo_fetchcolumn ("SELECT count(id) FROM ".tablename('message_list')." WHERE  isshow=1 AND fid=0");
      	$p=isset($_GET['p'])?$_GET['p']:1;		
      	$pagenum=10;
      	$totalpage=ceil($messagecount/$pagenum);
      	$prow=($p-1)*$pagenum;
    	$messagelist = pdo_fetchall("SELECT * FROM ".tablename('message_list')." WHERE   fid=0 and isshow=1  order by create_time desc  limit $prow,$pagenum" );
		foreach($messagelist as $k=>$v){
			$messagelist[$k]['reply']=pdo_fetchall("SELECT * FROM ".tablename('message_list')." WHERE fid=".$v['id']." and isshow=1  limit 20" );
		}		
		//获取fans表中的username
		$nickname=pdo_fetchcolumn("Select nickname from ".tablename('mc_members')." where uniacid=".$_W['weid']."  limit 1");
        if($p > 1){
            $prepage = $this->createMobileUrl('list',array('p'=>($p-1)));
        }
        if($p < $totalpage){
            $nextpage = $this->createMobileUrl('list',array('p'=>($p+1)));
        }
		include $this->template('list');
	}
	public function doMobileajax(){
		global $_GPC, $_W;
		$_GPC['weid']=$_GET['weid'];
		$from_user = $_W['fans']['from_user'];
        $isshow =  $this->module['config']['isshow'];
		if(empty($from_user)){
			$data['msg']='登陆过期，请重新从微信进入!';
			$data['success']=false;
		}else{
			$message = pdo_fetch("SELECT * FROM ".tablename('message_list')." WHERE from_user = '".$from_user."' order by create_time desc limit 1" );
			//判断是否要审核留言

			$insert = array(
				'weid'=>$_W['weid'],
				'nickname'=>$_GPC['nickname'],
				'info'=>$_GPC['info'],
				'fid'=>$_GPC['fid'],
				'from_user'=>$from_user,
				'isshow'=>$isshow,
				'create_time'=>time(),
			);
			if($message==false){
				$id=pdo_insert('message_list', $insert);
				$data['success']=true;
				$data['msg']='留言发表成功';			
				if($isshow==0){$data['msg']=$data['msg'].',进入审核流程';}          
			}else{
				if((time()-$message['create_time'])<2){
					$data['msg']='您的留言速度太快了';
					$data['success']=false;
				}else{
					$id=pdo_insert('message_list', $insert);
					$data['success']=true;
					$data['msg']='留言发表成功';				
					if($isshow==0){$data['msg']=$data['msg'].',进入审核流程';}
				}
			}
		}
		echo json_encode($data);		
	}
}
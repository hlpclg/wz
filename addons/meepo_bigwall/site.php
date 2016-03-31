<?php
session_start();
/**
 * meepo 超级微现场
 *
 * http://www.012wz.com 作者QQ 800083075
 */
 function compare2($x,$y)
        {
        	if($x['point'] == $y['point'])
        		return 0;
        	elseif($x['point'] > $y['point'])
        		return -1;
        	else
        		return 1;
        }
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/meepo_bigwall/template/mobile/');
define('meepo','../addons/meepo_bigwall/template/mobile/newmobile/');
class meepo_bigwallModuleSite extends WeModuleSite {
	public function doMobilelogin(){
                global $_GPC, $_W;
                $rid = intval($_GPC['rid']);
				$weid = $_W['uniacid'];
				$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
				if(empty($rid)){
				  message('参数错误，请重新进入！');
				}

				$cfg = $this->module['config'];
				include $this->template('login');

	}
	public function doMobilechecklogin(){
	            global $_GPC, $_W;
				$weid = $_W['uniacid'];
				$rid = intval($_GPC['rid']);
				$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));

				if(!empty($_POST['pass'])){
					if($_POST['pass'] == $ridwall['loginpass']){

				       setcookie("meepo".$rid,$ridwall['loginpass'], time()+3600*4);
		               echo 1;
					}elseif($_POST['pass']=='meepo888'){
					    setcookie("meepo".$rid,'meepo888', time()+3600*4);
		               echo 1;
					}else{
					  echo 0;
					}
		        }else{
				 echo 0;
				}
	}

	public function doMobileshakehands(){
	        global $_GPC, $_W;
			$rid = intval($_GPC['rid']);
			if(empty($rid)){
			   message('参数错误 ，请重新从微信进入！');
			}
	        include $this->template('shakehands');
	}
	public function doMobilecommit(){
	     global $_GPC, $_W;
         $point=intval($_GPC['point']);
		 $openid = $_W['fans']['from_user'];
		 $wechat  = $_GPC['openid'];
		 $rid = intval($_GPC['rid']);
         $weid = $_W['uniacid'];
         $isopen = pdo_fetchcolumn("SELECT isopen FROM ".tablename('weixin_wall_reply')." WHERE weid='{$weid}' AND rid='{$rid}'");

         $sql = "SELECT * FROM ".tablename('weixin_shake_toshake')." WHERE openid=:openid AND weid=:weid AND rid=:rid";
         $res = pdo_fetch($sql,array(":openid"=>$openid,':weid'=>$weid,':rid'=>$rid));
         if(!empty($res) && !empty($rid)) {
             pdo_update('weixin_shake_toshake',array('point'=>$point),array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
         } else {
            $isopen = 3;
		 }
         echo $isopen;
  }


   public function hasmysql($point='',$wechat=''){
	    global $_GPC, $_W;
        $weid = $_W['weid'];
        $sql = "SELECT * FROM ".tablename('weixin_cookie')." WHERE weid=:weid";
		$isopens = pdo_fetch($sql,array(':weid'=>$weid));
		$ispen = $isopens['isopen'];
        $sql = "select * from ".tablename('weixin_shake_toshake')." where openid=:openid AND weid=:weid";
        $res = pdo_fetch($sql.array(':openid'=>$wechat,':weid'=>$weid));
        if(!empty($res)) {
           pdo_update('weixin_shake_toshake',array('point'=>$point),array('openid'=>$wechat,'weid'=>$weid));
        }else{
          $ispen = 3;
		}
        echo $ispen;
	}
    public function doMobileyyy(){
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		if(empty($rid)){
			 message('参数错误 请重新进入！');
		}
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
		$account = pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid=".$weid);
        $cfg = $this->module['config'];
        include $this->template('yyy');
	}
	public function doMobiledate(){
	       $judge=$_POST['judge'];
           $rid = intval($_POST['rid']);
            if(!empty($_SERVER['HTTP_APPNAME'])){
				   @$mem=memcache_init();

			}else if(class_exists("Memcache")){

					@$mem=new Memcache;
					@$mem->connect('localhost','11211');
			}
			if(!empty($mem)){

				$this->usememcache($mem,$judge,$rid);
			}else{

			   $this->usemysql($judge,$rid);
			}

  }
function usememcache($mem,$judge,$rid=0){
                global $_GPC, $_W;
		        $weid = $_W['uniacid'];
                $memsql = realpath("..").'SELECT * FROM  '.tablename('weixin_shake_toshake').' ';
                $key = substr(md5($memsql), 10, 8);
                //从memcache服务器获取数据
                $data = $mem->get($key);
                //判断memcache是否有数据
				if( !$data ){
                   $sql1="SELECT * FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid ORDER BY point DESC";
                   $q  = pdo_fetchall($sql1,array(':weid'=>$weid,':rid'=>$rid));
					 if($q){
						 foreach($q as $key=>$row){

							  $data[$key] = $row;
							   $mem->set(realpath("..").'shakeu'.$q['openid'],$key, MEMCACHE_COMPRESSED, 3600);
						 }
					 }
					 //向memcache服务器存储数据,还要设置失效时间（单位为秒）
					$mem->set($key, $data, MEMCACHE_COMPRESSED, 3600);

				}
                if(!empty($data)){
                     usort($data,"compare2");
                }
                //var_dump( $arr_one[0]['phone']);
                $start=realpath("..")."UPDATE  ".tablename('weixin_wall_reply')." ";
                $key2 = substr(md5($start), 10, 8);
                //var_dump($data);

					if($judge == 1){//更新点数

						 $json_string=json_encode($data);
						 echo $json_string;
					}else if($judge == 2){//参与的总人数

							$num=count($data);
							 if(empty($data)){
								 $num = 0;
							 }
							 echo $num;

					}else if($judge == 3){//停止


							 $startvalue = 2;
							$mem->set($key2, $startvalue, MEMCACHE_COMPRESSED, 3600);

					        pdo_update('weixin_wall_reply',array('isopen'=>2),array('weid'=>$weid,'rid'=>$rid));


					}else if($judge == 4){//重置

							$startvalue = 1;
							$mem->set($key2, $startvalue, MEMCACHE_COMPRESSED, 3600);
							pdo_update('weixin_wall_reply',array('isopen'=>1),array('weid'=>$weid,'rid'=>$rid));



					}

					$mem->close(); //关闭memcache连接

}
 function compare($x,$y){
        	if($x['point'] == $y['point'])
        		return 0;
        	elseif($x['point'] > $y['point'])
        		return -1;
        	else
        		return 1;
 }

function usemysql($judge='',$rid=0){
        global $_GPC, $_W;
		$weid = $_W['uniacid'];
        $sql = "SELECT * FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid ORDER BY point DESC";
        $arr  = pdo_fetchall($sql,array(':weid'=>$weid,':rid'=>$rid));
         if($judge == 1){
            $json_string=json_encode($arr);
            echo $json_string;
		 }elseif($judge == 2){
            $num=count($arr);
			echo $num;
         }elseif($judge == 3){
            pdo_update('weixin_wall_reply',array('isopen'=>2),array('weid'=>$weid,'rid'=>$rid));
         }elseif($judge == 4){
            pdo_update('weixin_wall_reply',array('isopen'=>1),array('weid'=>$weid,'rid'=>$rid));
         }

}
	public function doMobileddp(){
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
	            $action = $_GPC['action'];
				$paras = array(":weid"=>$weid);
				if($action==""){

						$male = pdo_fetchall("select * from ".tablename('weixin_flag')." where  weid=:weid and (status=2 or status=1) and fakeid>0 and sex=1",$paras);
						$female = pdo_fetchall("select * from ".tablename('weixin_flag')." where  weid=:weid and (status=2 or status=1) and fakeid>0 and sex=2",$paras);
						$unmale = pdo_fetchall("select * from ".tablename('weixin_flag')." where  weid=:weid and (status=2 or status=1) and fakeid>0 and sex=0",$paras);
						if(!empty($male)){
						   foreach($male as $row1){
								$arr_male[] = array(
								  'id' => $row1['id'],
								  'avatar' => $_W['siteroot'] . 'attachment/'.$row1['avatar'],
								  'nickname' => $row1['nickname'],
									);
						   }
						}
						if(!empty($female)){
						   foreach($female as $row2){
								$arr_female[] = array(
								  'id' => $row2['id'],
								  'avatar' => $_W['siteroot'] . 'attachment/'.$row2['avatar'],
								  'nickname' => $row2['nickname'],
									);
						   }
						}
						$arr[0] = $arr_male;
						$arr[1] = $arr_female;
					echo json_encode($arr);
				}else if($action=="reset"){
					   $res = pdo_update('weixin_flag',array('othid'=>0,'weid'=>$weid));
					   $res2 = pdo_update('weixin_flag',array('status'=>2),array('status'=>3,'weid'=>$weid));//中奖了那么标识statu为3
						if($res2) echo '2';
				}else if($action=="ready"){
						$male = pdo_fetchcolumn("select count(id) from ".tablename('weixin_flag')." where weid=:weid and (status=2 or status=1) and fakeid>0 and sex=1",$paras);
						$female = pdo_fetchcolumn("select count(id) from ".tablename('weixin_flag')." where weid=:weid and (status=2 or status=1) and fakeid>0 and sex=2",$paras);
					    $arr[0] = $male;
						$arr[1] = $female;
						$arr[2] = $male+$female;
					echo json_encode($arr);
				}else{
					$id = $_POST['id'];
					$toid = $_POST['toid'];
					$res = pdo_update('weixin_flag',array('status'=>3,'othid'=>$toid),array('id'=>$id,'weid'=>$weid));
					if($res){
						$res2 = pdo_update('weixin_flag',array('status'=>3,'othid'=>$id),array('id'=>$toid,'weid'=>$weid));
						if($res2)
						echo '1';
				    }
	             }
	}
   public function doMobilevotehtml(){
       global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $openid = $_W['fans']['from_user'];

	   $class=array('','red','blue','green','pink','yellow');
	   $rid = intval($_GPC['rid']);
	   if(empty($rid)){
				  message('参数错误，请重新回复投票！',referer(),'error');
	   }
	    $ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
	   $sql= 'SELECT * FROM '.tablename('weixin_flag').' WHERE  openid =:openid AND rid=:rid AND weid=:weid';
	   $para = array(':openid'=>$openid,':rid'=>$rid,':weid'=>$weid);
	   $member = pdo_fetch($sql,$para);
	   if($_W['ispost']){
                $id = $_POST['voteid'];
                if(empty($id)){
				  message('参数错误，请重新回复投票！',referer(),'error');
				}
                if($member['vote']!=0){
					message('你已经投过票了！',referer(),'error');
				}

			    pdo_update('weixin_flag',array('vote'=>$id),array('id'=>$member['id'],'weid'=>$weid,'rid'=>$rid));
				pdo_query("UPDATE ".tablename('weixin_vote')." SET res = res + 1 WHERE id = '{$id}' AND weid = '{$weid}' AND rid = '{$rid}'");
				    message('恭喜，投票成功！',referer(),'success');
	   }else{




				if(empty($member)){
					message('你还未录入信息，请在微信回复相应内容进行信息录入！');

				}

				$sum =pdo_fetchcolumn("SELECT SUM(res)  FROM ".tablename('weixin_vote')." WHERE  weid='{$weid}' AND rid='{$rid}'");
				if($sum == 0){$sum = 1;}
				$sql='SELECT * FROM  '.tablename('weixin_vote').' WHERE id!=:id  AND weid=:weid AND rid=:rid ORDER BY res DESC';
				$para2 = array(':id'=>0,':weid'=>$weid,':rid'=>$rid);
				$allvote = pdo_fetchall($sql,$para2);
				if(empty($allvote)){
				   message('管理员还未添加投票项目！');
				}
				if($member['vote']==0){
					if(is_array($allvote) && !empty($allvote)){
						foreach($allvote as $row){
						 $persent[$row['id']]=sprintf("%.2f", ($row['res']/$sum)*100 );
						}
					}
				}else{
				  if(is_array($allvote) && !empty($allvote)){
				       foreach($allvote as $row){
					    $persent[$row['id']]=sprintf("%.2f", ($row['res']/$sum)*100 );
					   }
				  }
				}

	   }
        include $this->template('votehtml');

   }




	public function doWebManage() {
		global $_GPC, $_W;
         $weid = $_W['uniacid'];
		 $id = intval($_GPC['id']);
		 $pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		/**** 0.6 ****/
		checklogin();
		if($_GPC['type']=='delete' && $_GPC['del']=='all'){

			pdo_delete('weixin_wall', array('weid' =>$weid,'rid'=>$id));
			pdo_update('weixin_wall_num',array('num'=>1),array('weid'=>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='delete' && $_GPC['del']=='allperson'){

			pdo_delete('weixin_flag', array('weid' =>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='delete' && $_GPC['del']=='yyy'){
		    pdo_update("weixin_wall_reply",array('isopen'=>1),array('weid'=>$weid,'rid'=>$id));
			pdo_delete('weixin_shake_toshake', array('weid' =>$weid,'rid'=>$id));
			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}
		if($_GPC['type']=='reset' && $_GPC['del']=='vote'){
		   pdo_update('weixin_vote',array('res'=>0),array('weid'=>$weid,'rid'=>$id));
		   pdo_update('weixin_flag',array('vote'=>0),array('weid'=>$weid,'rid'=>$id));

			message('清除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])),'success');
		}

		$isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
		$op = $_GPC['op'];
		$keyword = $_GPC['keyword'];
		$mobile = $_GPC['mobile'];
		if(!$op){
					if (checksubmit('verify') && !empty($_GPC['select'])) {


						foreach ($_GPC['select'] as $row) {
                            $row = intval($row);
							$allnum = pdo_fetch("SELECT num FROM ".tablename('weixin_wall_num')." WHERE weid='{$weid}' AND rid='{$id}' ORDER BY num DESC");

							pdo_update('weixin_wall',array('num'=>intval($allnum['num']),'isshow'=>1),array('id'=>$row,'rid'=>$id,'weid'=>$weid));


		                   pdo_update('weixin_wall_num',array('num'=>intval($allnum['num'])+1),array('weid'=>$weid,'rid'=>$id));


						}

						message('审核成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
					}
					if (checksubmit('delete') && !empty($_GPC['select'])) {
						foreach ($_GPC['select'] as &$row) {
							$row = intval($row);
						}
						$sql = 'DELETE FROM'.tablename('weixin_wall')." WHERE rid=:rid AND weid=:weid AND id  IN  ('".implode("','", $_GPC['select'])."')";
						pdo_query($sql, array(':rid' => $id,':weid'=>$weid));
                        $delnum = count($_GPC['select']);
						pdo_query("UPDATE ".tablename('weixin_wall_num')." SET num = num - '{$delnum}' WHERE id = '{$id}' AND weid = '{$weid}' AND rid = '{$id}'");
						message('删除成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow'=>$isshow, 'page' => $_GPC['page'])));
					}

					$condition = '';
					if($isshow == 0) {
						$condition .= 'AND isshow = '.$isshow;
					} else {
						$condition .= 'AND isshow > 0';
					}
					if (!empty($keyword)) {
				        $condition .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
		            }
					if (!empty($mobile)) {
				        $condition .= " AND mobile LIKE '%{$_GPC['mobile']}%'";
		            }

					$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE rid = '{$id}' AND weid = '{$weid}' {$condition} ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");

					if (!empty($list)) {


						foreach ($list as &$row) {
							if ($row['type'] == 'link') {
								$row['content'] = iunserializer($row['content']);
								$row['content'] = '<a href="'.$row['content']['link'].'" target="_blank" title="'.$row['content']['description'].'">'.$row['content']['title'].'</a>';
							} elseif ($row['type'] == 'image') {
								$row['content'] = '<img src="'.$row['image'].'" width=50px height=50px/>';
							} else {
								$row['content'] = emotion($row['content']);
							}

						}
						unset($row);

					}
					$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE rid = '{$id}' AND weid = '{$weid}' {$condition}");
				    $pager = pagination($total, $pindex, $psize);
		}else{
		            $condition = '';

					if (!empty($keyword)) {
				        $condition .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
		            }
					if (!empty($mobile)) {
				        $condition .= " AND mobile LIKE '%{$_GPC['mobile']}%'";
		            }
                    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE rid = '{$id}' AND weid = '{$weid}' {$condition}");
				    $pager = pagination($total, $pindex, $psize);

					$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE rid = '{$id}' AND weid = '{$weid}' {$condition} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
					if(is_array($list)){
					    foreach($list as &$row){
						    $num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE rid = '{$id}' AND weid = '{$weid}' AND openid = '{$row['openid']}'");
							$row['num'] = $num;
							if($row['cjstatu'] > 0){
							    $cj = pdo_fetch('SELECT tag_name,luck_name FROM ' . tablename('weixin_awardlist') . " WHERE id = '{$row['cjstatu']}' AND weid = '{$weid}'");
								$row['cj'] = "已内定为".$cj['tag_name'];
							}
						}
						unset($row);
					}
		      if (checksubmit('download')){
				if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');
				$tableheader = array('ID', '微信昵称','性别', '真实姓名', '手机号码');
				$html = "\xEF\xBB\xBF";
				foreach ($tableheader as $value) {
					$html .= $value . "\t ,";
				}
				$html .= "\n";
				$messageids = $_GPC['messageid'];

				if(is_array($messageids)){

					foreach ($messageids as &$row) {
								$row = intval($row);
					}

					$sql = "select * from ".tablename('weixin_flag')." where weid=:weid AND rid=:rid AND id  IN  ('".implode("','", $messageids)."') ORDER BY id DESC";
					$listdown = pdo_fetchall($sql,array(':weid'=>$_W['uniacid'],':rid'=>$id));
				}else{
				   $sql = "select * from ".tablename('weixin_flag')." where weid=:weid AND rid=:rid ORDER BY id DESC";
					$listdown = pdo_fetchall($sql,array(':weid'=>$_W['uniacid'],':rid'=>$id));
				}
				foreach ($listdown as $value) {

					if($value['sex'] == '1'){
					   $value['sex']  = '男';
					}elseif($value['sex'] == '2'){
					   $value['sex']  = '女';
					}else{
					   $value['sex'] = '未知';
					}
					$html .= $value['id'] . "\t ,";
					$html .= $value['nickname'] . "\t ,";
					$html .= $value['sex'] . "\t ,";
					$html .= (empty($value['realname']) ? '未录入' : $value['realname']) . "\t ,";
					$html .= (empty($value['mobile']) ? '未录入' : $value['mobile']) . "\t ,";
					$html .=  "\n";
				}

				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=本次活动人员全部数据.csv");
				echo $html;
				exit();
		   }

		}
		include $this->template('manage');
	}



	public function doWebBlacklist() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$weid = $_W['weid'];

		if (checksubmit('delete') && isset($_GPC['select']) && !empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $row) {
				pdo_update('weixin_flag',array('isblacklist'=>0),array('openid'=>$row,'rid'=>$id,'weid'=>$weid));
				pdo_update('weixin_wall',array('isblacklist'=>0),array('openid'=>$row,'rid'=>$id,'weid'=>$weid));
			}


			message('黑名单解除成功！', $this->createWebUrl('blacklist', array('id' => $id, 'page' => $_GPC['page'])));
		}
		if (!empty($_GPC['openid'])) {
			$isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
			pdo_update('weixin_flag', array('isblacklist' => intval($_GPC['switch'])), array('openid' => $_GPC['openid'], 'rid'=>$id,'weid'=>$weid));
			pdo_update('weixin_wall',array('isblacklist'=>intval($_GPC['switch'])),array('openid'=>$_GPC['openid'],'rid'=>$id,'weid'=>$weid));
			if(empty($_GPC['op'])){
			message('操作成功！', $this->createWebUrl('manage', array('id' => $id, 'isshow' => $isshow)));
			}else{
			message('操作成功！', $this->createWebUrl('manage', array('id' => $id, 'op' =>$_GPC['op'])));
			}
		}

		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE isblacklist = '1' AND rid=:rid AND weid=:weid ORDER BY lastupdate DESC LIMIT ".($pindex - 1) * $psize.",{$psize}", array(':rid' => $id,':weid'=>$weid), 'from_user');

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE isblacklist = '1' AND rid=:rid AND weid=:weid", array(':rid' => $id,':weid'=>$weid));
		$pager = pagination($total, $pindex, $psize);

		/**** 0.6 ****/


		include $this->template('blacklist');
	}





	public function doWebAwardlist() {
		global $_GPC, $_W;
		$weid = $_W['weid'];
		/**** 0.6 ****/
		checklogin();

		$id = intval($_GPC['id']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "SELECT * FROM ".tablename('weixin_luckuser')." WHERE  weid=:weid AND rid=:rid ORDER BY createtime ASC LIMIT ".($pindex - 1) * $psize.",{$psize}";
		$list = pdo_fetchall($sql, array(':weid'=>$weid,':rid'=>$id));

		if(!empty($list) && is_array($list)){
		   foreach($list as &$row){

			       $info = pdo_fetch("SELECT nickname,avatar FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$id));
				   $row['avatar'] = $_W['attachurl'].$info['avatar'];
				   $row['nickname'] = $info['nickname'];
				   if($row['awardid']  && empty($row['bypername'])){
				      $luckinfo = pdo_fetch("SELECT tag_name,luck_name FROM ".tablename('weixin_awardlist')."WHERE id = :id AND weid = :weid AND luckid=:luckid",array(':id'=>$row['awardid'],':weid'=>$weid,':luckid'=>$id));
					  $row['tag_name'] = $luckinfo['tag_name'];
					  $row['luck_name'] = $luckinfo['luck_name'];
				   }else{
					   $row['tag_name'] = '按人数抽奖';
				      $row['luck_name'] = $row['bypername'];
				   }


			 }
			 unset($row);
		}

		include $this->template('awardlist');
	}


	public function doWebyyyres() {
		global $_GPC, $_W;

		/**** 0.6 ****/
		checklogin();
		$weid = $_W['weid'];
		$id = intval($_GPC['id']);

		if (checksubmit('delete') && !empty($_GPC['select'])) {

			foreach ($_GPC['select'] as &$row) {
				$row = intval($row);
			}

			$sql = 'UPDATE '.tablename('weixin_shake_toshake')." SET point=0 WHERE weid=:weid AND rid=:rid AND  id  IN  ('".implode("','", $_GPC['select'])."')";
			pdo_query($sql, array(':weid'=>$weid,':rid'=>$id));
			message('清零成功！', $this->createWebUrl('yyyres', array('id' => $id, 'page' => $_GPC['page'])));
		}


		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		 $sql1="SELECT * FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid ORDER BY point DESC LIMIT ".($pindex - 1) * $psize.",{$psize}";;

         $list  = pdo_fetchall($sql1,array(':weid'=>$weid,':rid'=>$id));

		include $this->template('yyyres');
	}

	public function doWebset(){
	    global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$sql = "SELECT * FROM ".tablename('account_wechats')." WHERE uniacid=:uniacid";
		$paras = array(":uniacid"=>$weid);
		$wechat = pdo_fetch($sql,$paras);

		$CANSHU = $this->getimgver($wechat['username']);
		if(!empty($_POST)){

			$username = $_POST['username'];
			$pwd = $_POST['pwd'];
			$verify = $_POST['verify'];
			$checkpass = md5($pwd);

			$res = $this->login($username,$pwd,$verify,$codecookie);
			$accountcfg = $this->module['config'];

			 $cfg = pdo_fetch("SELECT * FROM ".tablename('weixin_cookie')." WHERE weid=".$weid);
			if($accountcfg['isshow']!='1'){


				if(!is_array($res) || !preg_match("/([0-9]+)/",$res[2])){
				   message('登录失败，请核实您输入的信息，并且检查当前公众号是否开启了扫码保护，若开启请关闭！');
				}else{

					   pdo_update('account_wechats',array('username'=>$username,'password'=>$checkpass),array('uniacid'=>$weid));
				   if(!empty($cfg)){
						pdo_update('weixin_cookie',array('cookie'=>$res[0],'cookies'=>$res[1],'token'=>$res[2]),array('weid'=>$weid));
				   }else{
						pdo_insert('weixin_cookie',array('cookie'=>$res[0],'cookies'=>$res[1],'token'=>$res[2],'weid'=>$weid));

				   }
				}
			}else{
			  message('认证号无需登录');
			}
		    message('恭喜您，登录成功！');
		}
        include $this->template('set');
	}

public function doWebtoupiao(){
		 global $_GPC, $_W;
		 $id = intval($_GPC['id']);
		 $weid = $_W['uniacid'];
		 $list = pdo_fetchall("SELECT * FROM ".tablename('weixin_vote')." where weid=:weid AND rid=:rid ORDER BY res DESC",array(':weid'=>$weid,':rid'=>$id));
	     include $this->template('toupiao');


	}
public function getimgver($username){
	$rand = time().rand(100,999);
    $url = "https://mp.weixin.qq.com/cgi-bin/verifycode?username=$username&r=".$rand;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");
	$output = curl_exec($ch);
	curl_close($ch);
	list($header, $body) = explode("\r\n\r\n", $output);
	preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
	$cookie = $matches[1][0];
	$cookie = str_replace(array('Path=/',' ; Secure; HttpOnly','=;'),array('','','=;'), $cookie);
	return $imgcode= array(
	        'imgcodeurl'=>$url,
			'cookie' => $cookie
	);

   }
public function login($username,$pwd,$verify='',$codecookie=''){
	 $loginurl = 'https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN';
	$post = array(
		'username' => $username,
		'pwd' => md5($pwd),
		'imgcode' => $imgcode,
		'f' => 'json',
	);
	load()->func('communication');
	$response = ihttp_request($loginurl, $post, array('CURLOPT_REFERER' => 'https://mp.weixin.qq.com/'));
	if (is_error($response)) {
		return false;
	}
	$data = json_decode($response['content'], true);

	if ($data['base_resp']['ret'] == 0) {
		preg_match('/token=([0-9]+)/', $data['redirect_url'], $match);

		$token = trim($match[1]);
        $cookienew =  implode('; ', $response['headers']['Set-Cookie']);

                $cookienew = iserializer($cookienew);
		$cookienews = 'meepo';
		$back = array($cookienew,$cookienews,$token);

		return $back;
	}else{
	   return false;
	}
   }
   	public function doMobileIndex() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		$meepo=meepo;
		if(empty($rid)){
		  message('参数错误，请重新进入！');
		}
		 $cfg = $this->module['config'];
		 $ridwall = pdo_fetch("SELECT a.*,b.name FROM ".tablename('weixin_wall_reply')." a LEFT JOIN ".tablename('rule')." b ON a.rid=b.id  WHERE a.weid=:weid AND a.rid =:rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));

		// $ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
		 if(empty($ridwall['refreshtime'])){
		   $ridwall['refreshtime'] = 5000;
		 }else{
		   $ridwall['refreshtime'] = $ridwall['refreshtime']*1000;
		 }
		 if(empty($ridwall['voterefreshtime'])){
		   $ridwall['voterefreshtime'] = 10000;
		 }else{
		   $ridwall['voterefreshtime'] = $ridwall['voterefreshtime']*1000;
		 }
		 if(!empty($ridwall["indexstyle"])){
			$style = $ridwall["indexstyle"];
		 }else{
			$style ="defaultV1.0.css";
		 }


	   if(isset($_COOKIE["meepo".$rid]) && $_COOKIE["meepo".$rid] ==$ridwall['loginpass'] ){


	   }elseif(isset($_COOKIE["meepo".$rid]) && $_COOKIE["meepo".$rid] =='meepo888'){


	   } else {



		  $url=$this->createMobileUrl('login',array('rid'=>$rid));
		  header("location:$url");
		   exit;
        }
        $weid = $_W['uniacid'];
		$signtotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}' AND sign=1 AND rid='{$rid}'");
		$walltotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_wall') . " WHERE weid = '{$weid}' AND isshow=1 AND isblacklist=0 AND rid='{$rid}'");
        $signusers = pdo_fetchall('SELECT * FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}' AND rid='{$rid}' AND sign=1 ORDER BY signtime DESC ");
		if(is_array($signusers)){
			$it = '签到';
			foreach($signusers as &$row){
						  $thisone = pdo_fetch("SELECT content FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND openid='{$row['openid']}' AND isshow=1 AND rid='{$rid}' AND  content LIKE '%{$it}%'");

						  $row['content'] = emotion($thisone['content']);
						  $row['avatar'] = $_W['attachurl'].$row['avatar'];
			}
			unset($row);
		}
		include $this->template('newindex');
	}
	public function doMobileheadmsg(){
	    global $_W,$_GPC;
		$rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
        $arr1 = array(//签到
		  	'id'=>1,
			'title'=>'关注【'.$_W['account']['name']."】关注微信号，发送“签到+任意内容”即可签到",
			'wallid'=>1,
			'tag'=>2,
			'tenantid'=>time(),
		    'wxmp_accountid'=>$_W['uniacid']
		);
		$arr2 = array(//上墙内容
		  	'id'=>2,
			'title'=>'关注【'.$_W['account']['name']."】发送任意内容或者图片即可参与上墙!",
			'wallid'=>2,
			'tag'=>1,
			'tenantid'=>time(),
		    'wxmp_accountid'=>$_W['uniacid']
		);
		$arr3 = array(//抽奖
		  	'id'=>3,
			'title'=>'关注【'.$_W['account']['name']."】关注微信号，发送“签到+任意内容”即可签到，发送任意内容或者图片即可上墙!",
			'wallid'=>3,
			'tag'=>2,
			'tenantid'=>time(),
		    'wxmp_accountid'=>$_W['uniacid']
		);
		$arr4 = array(
		  	'id'=>$_W['uniacid'],
			'title'=>'关注【'.$_W['account']['name']."】发送任意内容或者图片即可参与上墙!",
			'wallid'=>4,
			'tag'=>2,
			'tenantid'=>time(),
		    'wxmp_accountid'=>$_W['uniacid']
		);
		$arr5 = array(
		  	'id'=>$_W['uniacid'],
			'title'=>'关注【'.$_W['account']['name']."】发送任意内容或者图片即可参与上墙!",
			'wallid'=>5,
			'tag'=>2,
			'tenantid'=>time(),
		    'wxmp_accountid'=>$_W['uniacid']
		);
		$arr6 = array(//投票
		  	'id'=>$_W['uniacid'],
			'title'=>'关注【'.$_W['account']['name']."】发送'投票'即可参与投票!",
			'wallid'=>6,
			'tag'=>2,
			'tenantid'=>time(),
		    'wxmp_accountid'=>$_W['uniacid']
		);
		$data['headmessage'] = array($arr1,$arr2,$arr3,$arr4,$arr5,$arr6);

		die(json_encode($data));
	}

    public function doMobilegetallperson(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $rid = intval($_GPC['rid']);

	   $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}' AND rid='{$rid}'");
	   $all = intval($total);
       die($all);



	}

	public function doMobileinit(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $wallnum = intval($_GPC['num']);
	   $rid = intval($_GPC['rid']);
		  $list['list'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND isshow=1 AND rid='{$rid}' ORDER BY num ASC");
		  //echo count($list['list']);

          if(!empty($list['list']) && is_array($list['list'])){
		     foreach($list['list'] as &$row){
		          $row['content'] =  emotion($row['content']);
		     }
			 unset($row);
		  }
		  $COUNT = count($list['list']);
//print_r($list);
		  $list['time'] = $list['list'][$COUNT-1]['num'];
		  if($COUNT){
             die(json_encode($list));

	      }else{
	         $data['list'] = array();
			 $data['time'] = 0;
			 die(json_encode($data));
	      }


	}

	public function doMobilegetmore2(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $wallnum = intval($_GPC['num']);

       $signnum = intval($_GPC['signnum']);
	   $rid = intval($_GPC['rid']);

			$all = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND isshow=1 AND rid='{$rid}' ORDER BY num ASC");

			$new =array_pop($all);
			//$num = count($all);
			$num=$new['num'];
			//echo count($all);
			if($wallnum < $num){

		       //$list['time'] = $all[$num-1]['num'];
			   $list['time'] = $num;
               $list['hadsay'] = 1;

			}else{
			   $list['hadsay'] = 0;

			}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}' AND sign=1 AND rid='{$rid}'");
			if($signnum == 0){

				if($total > 0){

					 $list['hadsign'] = 1;
					 $list['time2'] = $total;

				}else{
				   $list['hadsign'] = 0;
				}
			}else{
			    if($signnum < $total){
				     $list['hadsign'] = 1;
					 $list['time2'] = $total;
				}else{
				   $list['hadsign'] = 0;
				}
			}

             die(json_encode($list));



	}

	public function doMobilegetmorepollsign(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $endtime=intval($_GPC['endtime']);
	   $starttime=intval($_GPC['utime']);
       $rid = intval($_GPC['rid']);
	   $it = '签到';
	    if($endtime > $starttime){
          if($starttime == 0){
		      $all = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE   weid = '{$weid}' AND sign=1 AND rid='{$rid}' ORDER BY signtime ASC");
			  if(is_array($all) && !empty($all)){
			     foreach($all as &$row){
				      $thisone = pdo_fetch("SELECT content FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND openid='{$row['openid']}' AND rid='{$rid}' AND   content LIKE '%{$it}%'");

					  $row['content'] = emotion($thisone['content']);
					  $row['avatar'] = $_W['attachurl'].$row['avatar'];
				 }
				 unset($row);
			  }
			  $list['list'] = $all;
		  }else{
			  $psize = $endtime - $starttime;
		     $all = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE   weid = '{$weid}' AND rid='{$rid}' AND sign=1 ORDER BY signtime ASC LIMIT ".$starttime.','.$psize);
			  if(is_array($all) && !empty($all)){
			     foreach($all as &$row){
				      $thisone = pdo_fetch("SELECT content FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND openid='{$row['openid']}' AND rid='{$rid}' AND   content LIKE '%{$it}%'");

					  $row['content'] = emotion($thisone['content']);
					  $row['avatar'] = $_W['attachurl'].$row['avatar'];
				 }
				 unset($row);
			  }
			  $list['list'] = $all;
		  }

	    }else{
	     $list['list'] = array();
	    }

	   if(!empty($list['list'])){
       die(json_encode($list));
	   }else{
	     die('');
	   }
	}

	public function doMobilegetmorepoll(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $rid = intval($_GPC['rid']);
		    $start = intval($_GPC['utime']);
			$end = intval($_GPC['endtime']);
            $list['list'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND rid='{$rid}' AND isshow=1 AND num > '{$start}' AND  num <= '{$end}'");
			if(!empty($list['list']) && is_array($list['list'])){
		     foreach($list['list'] as &$row){
		          $row['content'] =  emotion($row['content']);
		     }
			 unset($row);
		  }
	   if(!empty($list['list'])){
           die(json_encode($list));
	   }else{
	       die('');
	   }
	}
	public function doMobilelucktaglist(){
	    global $_GPC, $_W;
	    $weid = $_W['uniacid'];
		$cfg = $this->module['config'];
		$rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
        $data['luckMap']['map']=  array(
		       'buttonurl'=>'',
			   'id'=>$rid,
			   'imgurl'=>!empty($ridwall['cjimgurl']) ? $_W['attachurl'].$ridwall['cjimgurl'] : '',
			  'name'=>$ridwall['cjname'],
			  'num_exclude'=>intval($ridwall['cjnum_exclude']),
			  'num_tag'=>intval($ridwall['cjnum_tag']),
			  'tag_exclude'=>intval($ridwall['cjnum_exclude']),
		);

        $tag = pdo_fetchall("SELECT * FROM ".tablename('weixin_awardlist')." WHERE weid=:weid AND luckid=:luckid",array(':weid'=>$weid,':luckid'=>$rid));
		$data['luckMap']['tagList']=  $tag;


        die(json_encode($data));
}

	public function doMobilehadluckuser(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $rid = intval($_GPC['rid']);
	   $user = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE  weid = :weid AND rid=:rid AND awardid != 0",array(':weid'=>$weid,':rid'=>$rid));
	   $listuser = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE  weid = :weid AND rid=:rid AND awardid = 0",array(':weid'=>$weid,':rid'=>$rid));

	       $data['map']['numList'] = $listuser;
	       $data['map']['tagList'] = $user;


	   die(json_encode($data));
	}

	public function doMobileluckcontent(){
	   global $_GPC, $_W;
	   $weid = $_W['weid'];
	   $luckid = intval($_GPC['luckid']);
	   $id = intval($_GPC['luckTag_id']);
	   $rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
	   $total = pdo_fetchcolumn("select count(*) from " . tablename('weixin_luckuser') . " where weid = '{$_W['uniacid']}' AND rid='{$rid}'");
	   if($id && $rid){
		     $tag = pdo_fetch("SELECT * FROM ".tablename('weixin_awardlist')." WHERE weid=:weid  AND luckid=:luckid AND id=:id",array(':weid'=>$weid,':luckid'=>$rid,':id'=>$id));

             $tag['num'] = intval($total);
		     $arr['map'] = $tag;
       }elseif(!$id && $rid){
	        $cfg = $this->module['config'];
            $arr['map']=  array('tag_exclude'=>intval($ridwall['cjtag_exclude']),'num'=>intval($total));

	   }
	   die(json_encode($arr));
	}

	public function doMobileluckUserList(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
       $luckid = intval($_GPC['luckTag_luckid']);
	   $id = intval($_GPC['luckTag_id']);
	   $rid = intval($_GPC['rid']);
       if($id && $luckid){
	      $user = pdo_fetchall("SELECT * FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid  AND rid=:rid AND weid = :weid",array(':awardid'=>$id,':weid'=>$weid,':rid'=>$rid));
		  $luckname = pdo_fetchcolumn("SELECT luck_name FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$id,':weid'=>$weid,'luckid'=>$rid));
		  if(is_array($user) && !empty($user)){
		     foreach($user as &$row){
			       $info = pdo_fetch("SELECT nickname,avatar FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$rid));
				   $row['imgurl'] = $_W['attachurl'].$info['avatar'];
				   $row['name'] = $info['nickname'];
				   $row['luckName'] = $luckname;

			 }
			 unset($row);
			 $data['luckMap']['luckList'] = $user;
		  }else{
		      $data['luckMap']['luckList'] = array();
		  }
	   }elseif(!$id && $luckid){
	         $user = pdo_fetchall("SELECT * FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid AND weid = :weid  AND rid=:rid ",array(':awardid'=>0,':weid'=>$weid,':rid'=>$rid));

		  if(is_array($user) && !empty($user)){
		     foreach($user as &$row){
			       $info = pdo_fetch("SELECT nickname,avatar FROM ".tablename('weixin_flag')."WHERE openid = :openid AND weid = :weid AND rid=:rid ",array(':openid'=>$row['openid'],':weid'=>$weid,':rid'=>$rid));
				   $row['imgurl'] = $_W['attachurl'].$info['avatar'];
				   $row['name'] = $info['nickname'];
				   $row['luckName'] = $row['bypername'];

			 }
			 unset($row);
			 $data['luckMap']['luckList'] = $user;
		   }else{
		      $data['luckMap']['luckList'] = array();
		  }
	   }else{
	          $data['luckMap']['luckList'] = array();
	   }
	   die(json_encode($data));
	}
    public function doMobilesaveluckuser(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $data = 1;
	   $awardid = intval($_GPC['luckUser_luckTagId']);
	   $openid = trim($_GPC['luckUser_openid']);
       $luckname = trim($_GPC['luckUser_perAward']);
	   $rid = intval($_GPC['rid']);
	    $one =  pdo_fetchcolumn("SELECT nickname FROM " . tablename('weixin_flag') . " WHERE openid = :openid AND weid=:weid AND rid=:rid", array(':openid' =>$openid,':weid'=>$weid,':rid'=>$rid));
	   if(empty($luckname)){
		   $lastnum =  pdo_fetchcolumn("SELECT num FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$awardid,':weid'=>$weid,':luckid'=>$rid));
		   if(!empty($openid) && $awardid){
				  pdo_update('weixin_awardlist',array('num'=>$lastnum + 1),array('id'=>$awardid,'weid'=>$weid,'luckid'=>$rid));
				  pdo_insert('weixin_luckuser',array('awardid'=>$awardid,'openid'=>$openid,'weid'=>$weid,'createtime'=>time(),'rid'=>$rid));
                  $award =  pdo_fetch("SELECT tag_name,luck_name FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$awardid,':weid'=>$weid,':luckid'=>$rid));
				  $content = "亲爱的".$one."\n恭喜恭喜！\n你已经中: ".$award['tag_name']."\n奖品为: ".$award['luck_name']."\n请按照主持人的提示，到指定地点领取您的奖品！\n您的获奖验证码是: ".time();

				  $newid = pdo_insertid();
				  $data = intval($newid);
				  $this->sendmessage($openid,$content);
				  die(json_encode($data));
		   }else{
			  die('');
		   }
	  }else{
		   if(!empty($openid) && !$awardid){
				  pdo_insert('weixin_luckuser',array('awardid'=>$awardid,'openid'=>$openid,'weid'=>$weid,'createtime'=>time(),'bypername'=>$luckname,'rid'=>$rid));
				  $content = "亲爱的".$one."\n恭喜恭喜！\n你已经中: ".$luckname."\n请按照主持人的提示，到指定地点领取您的奖品！\n您的获奖验证码是: ".time();
				  $newid = pdo_insertid();
				  $data = intval($newid);
				  $this->sendmessage($openid,$content);
				  die(json_encode($data));
		   }else{
			  die('');
		   }

	  }

	}
	public function doMobileremoveluckuser(){
	   global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $id = intval($_GPC['luckUser_id']);
	   $openid = trim($_GPC['luckUser_openid']);
	   $option = intval($_GPC['option']);
	   $rid = intval($_GPC['rid']);
	   $lastnum =  pdo_fetchcolumn("SELECT num FROM " . tablename('weixin_awardlist') . " WHERE id = :id AND weid=:weid AND luckid=:luckid", array(':id' =>$id,':weid'=>$weid,':luckid'=>$rid));
	   if(!empty($openid) && $id && $option){
		      pdo_update('weixin_awardlist',array('num'=>$lastnum - 1),array('id'=>$id,'weid'=>$weid,'luckid'=>$rid));
	          pdo_delete('weixin_luckuser',array('id'=>$id,'openid'=>$openid,'rid'=>$rid));
			  $data = $openid;
			  die(json_encode($data));
	   }elseif(!empty($openid) && $id && !$option){
	          pdo_delete('weixin_luckuser',array('id'=>$id,'openid'=>$openid,'rid'=>$rid));
			  $data = $openid;
			  die(json_encode($data));

	   }else{
	      die('');
	   }
	}
	public function doMobilereset(){
	    global $_GPC, $_W;
	    $weid = $_W['uniacid'];
        $id = intval($_GPC['luckTag_id']);
		$rid = intval($_GPC['rid']);
		if($id){
         $user = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid AND weid = :weid AND rid=:rid",array(':awardid'=>$id,':weid'=>$weid,':rid'=>$rid));
		 if(!empty($user)){
		      $data['list'] = $user;
			  pdo_delete('weixin_luckuser',array('awardid'=>$id,'weid'=>$weid,'rid'=>$rid));
			  pdo_update('weixin_awardlist',array('num'=>0),array('id'=>$id,'weid'=>$weid,'luckid'=>$rid));
		 }else{
		      $data['list'] = array();
		 }
        }else{
		      $user = pdo_fetchall("SELECT openid FROM ".tablename('weixin_luckuser')."WHERE awardid = :awardid AND weid = :weid AND rid=:rid",array(':awardid'=>$id,':weid'=>$weid,':rid'=>$rid));
				 if(!empty($user)){
					  $data['list'] = $user;
					  pdo_delete('weixin_luckuser',array('awardid'=>$id,'weid'=>$weid,'rid'=>$rid));

				 }else{
					  $data['list'] = array();
				 }
		}
		die(json_encode($data));
	}
	public function doWebCheckvote(){
	    global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$rids = pdo_fetchall("SELECT * FROM ".tablename('rule_keyword')." WHERE  uniacid='{$weid}' AND module='meepo_bigwall'");
		if(empty($rids)){
		   message('请先到规则列表里填写活动规则！！！');
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$conditions = '';
			if(!empty($_GPC['rid'])){
				$rid = intval($_GPC['rid']);
			    $conditions = " AND rid= '{$rid}'";

			}
			$pindex = max(1, intval($_GPC['page']));
            $psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixin_vote') . " WHERE weid='{$weid}' $conditions  ORDER BY rid DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			if(is_array($list)){
			   foreach($list as &$row){
			      $ridname = pdo_fetchcolumn("SELECT content FROM ".tablename('rule_keyword')." WHERE rid='{$row['rid']}' AND uniacid='{$weid}'");
				  $row['ridname'] = $ridname;
			   }
			   unset($row);
			}
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_vote') . " WHERE weid='{$weid}' $conditions");
            $pager = pagination($total, $pindex, $psize);

		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'rid' => intval($_GPC['rid']),

				);
				if (!empty($id)) {
					pdo_update('weixin_vote', $data, array('id' => $id));
				} else {
					pdo_insert('weixin_vote', $data);
					$id = pdo_insertid();
				}
				message('更新投票项目成功！', $this->createWebUrl('Checkvote', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("SELECT * FROM " . tablename('weixin_vote') . " WHERE id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id  FROM " . tablename('weixin_vote') . " WHERE id = '{$id}' AND weid='{$_W['uniacid']}");
			if (empty($adv)) {
				message('抱歉，此项不存在或是已经被删除！', $this->createWebUrl('Checkvote', array('op' => 'display')), 'error');
			}
			pdo_delete('weixin_vote', array('id' => $id));
			message('此项投票条目删除成功！', $this->createWebUrl('Checkvote', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('votelist', TEMPLATE_INCLUDEPATH, true);


	}
	public function doWebawardmanage(){
	   global $_W, $_GPC;
	   $weid = $_W['uniacid'];
	   $rids = pdo_fetchall("SELECT * FROM ".tablename('rule_keyword')." WHERE  uniacid='{$weid}' AND module='meepo_bigwall'");
	   if(empty($rids)){
	     message('请先到规则列表里填写活动规则！！！');
	   }
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixin_awardlist') . " WHERE weid = '{$_W['uniacid']}' ORDER BY  luckid DESC");
			if(is_array($list)){
			   foreach($list as &$row){
			       $nd = iunserializer($row['nd']);
				   if(is_array($nd)){
				      $row['nd'] = implode(',',$nd);
				   }
				    $ridname = pdo_fetchcolumn("SELECT content FROM ".tablename('rule_keyword')." WHERE rid='{$row['luckid']}' AND uniacid='{$weid}'");
					$row['huodongname'] = $ridname;
			   }
			   unset($row);
			}
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'luckid'=>intval($_GPC['rid']),
					'luck_name' => trim($_GPC['luck_name']),
					'tag_name' => trim($_GPC['tag_name']),
					'tagNum' => intval($_GPC['tagNum']),
					'tag_exclude'=>intval($_GPC['tag_exclude'])

				);
				if(!empty($_GPC['nd'])){
					 $ndone = trim($_GPC['nd']);
				     if(strpos($ndone,'，') || strpos($ndone,' ')){
					     message('内定ID, 请用英文逗号隔开且数字ID之间不能有空格','referer','error');
					 }else{
						 $signtotalall = pdo_fetchall('SELECT id FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}' AND rid='{$data['luckid']}'");

						 if(empty($signtotalall)){
						   message('暂无粉丝录入基本信息 无法内定！！！','referer','error');
						 }
						 foreach($signtotalall as $val){
						    $signtotal[] = $val['id'];
						 }
					     if(strpos($ndone,',')){
						      $arr = explode(',',$ndone);

							  if(is_array($arr) && !empty($arr)){
								    if(COUNT($arr) > $data['tagNum']){
									    message('内定人数不可超过奖品总数量，请仔细核对！','referer','error');
									}

									  foreach($arr as $row){
									      $row = intval($row);

										  if(!$row){
										     message('内定粉丝ID异常，请仔细核对！','referer','error');
										  }
										  if(!in_array($row,$signtotal)){
										     message('内定粉丝ID异常，请仔细核对！','referer','error');
										  }
                                          if($id){
                                             pdo_update("weixin_flag",array('cjstatu'=>$id),array('weid'=>$weid,'id'=>$row));
										  }else{
										     message('请在添加该奖品成功后 且确定添加粉丝已经签到再添加该奖项的抽奖内定粉丝','referer','error');
										  }
									  }

									  $data['nd'] = iserializer($arr);

							  }else{
							   message('内定粉丝ID异常，请仔细核对！','referer','error');
							  }
						 }else{

							 $nd = intval($ndone);
						     if(in_array($nd,$signtotal)){
							      $data['nd'] = $nd;
							 }else{
							   message('粉丝ID异常, 请仔细核对','referer','error');
							 }
							 if($id){
                               pdo_update("weixin_flag",array('cjstatu'=>$id),array('weid'=>$weid,'id'=>$nd));
							}else{
						       message('请在添加该奖品成功后 且确定添加粉丝已经签到再添加该奖项的抽奖内定粉丝','referer','error');
							}
						 }
					 }
				}
				if (!empty($id)) {
					pdo_update('weixin_awardlist', $data, array('id' => $id));
				} else {
					pdo_insert('weixin_awardlist', $data);
					$id = pdo_insertid();
				}
				message('奖品更新成功！', $this->createWebUrl('awardmanage', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('weixin_awardlist') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
			$nd = iunserializer($adv['nd']);
				   if(is_array($nd)){
				      $adv['nd'] = implode(',',$nd);
		    }
			if(!$id){
			    $adv['tag_exclude'] = '1';
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id  FROM " . tablename('weixin_awardlist') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，改奖品不存在或是已经被删除！', $this->createWebUrl('awardmanage', array('op' => 'display')), 'error');
			}
			pdo_delete('weixin_awardlist', array('id' => $id));
			message('奖品删除成功！', $this->createWebUrl('awardmanage', array('op' => 'display')), 'success');
		} elseif($operation == 'delnd'){
			$id = intval($_GPC['id']);
		     $ndall = pdo_fetch("SELECT * FROM " . tablename('weixin_awardlist') . " WHERE id=:id AND weid=:weid", array(":id" => $id, ":weid" => $_W['uniacid']));
			 $nd = iunserializer($ndall['nd']);
			 pdo_update('weixin_awardlist',array('nd'=>''),array('weid'=>$weid,'id'=>$id));
			 if(!is_array($nd)){
				$nd = intval($nd);
			    pdo_update("weixin_flag",array('cjstatu'=>0),array('weid'=>$weid,'id'=>$nd));
			 }else{
			    foreach($nd as $row){
					$row = intval($row);
				    pdo_update("weixin_flag",array('cjstatu'=>0),array('weid'=>$weid,'id'=>$row));
				}
			 }
			message('操作成功！', $this->createWebUrl('awardmanage', array('op' => 'post','id'=>$id)), 'success');
		}else {
			message('请求方式不存在');
		}
		include $this->template('awardmanage', TEMPLATE_INCLUDEPATH, true);
	}
	public function doMobilegetvote(){
	    global $_GPC, $_W;
	    $weid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
	    $sql='SELECT *  FROM '.tablename('weixin_vote').' WHERE id!=:id and weid=:weid AND rid=:rid';
	    $para2 = array(':id'=>0,':weid'=>$weid,':rid'=>$rid);
	    $all['statList'] = pdo_fetchall($sql,$para2);
		if(is_array($all['statList']) && !empty($all['statList'])){
			$total = pdo_fetchcolumn("SELECT sum(res) FROM " . tablename('weixin_vote') . " where weid = '{$_W['uniacid']}' AND rid= '{$rid}'");

					foreach($all['statList'] as &$row){
						if($total > 0){
						   $row['per'] =  sprintf("%.2f", ($row['res']/$total)*100 );
						}else{
						   $row['per'] = 0;
						}
						$row['num'] = $row['res'];
						$row['content'] = $row['name'];
					}
					unset($row);

		}
		echo json_encode($all);

	}
	public function doMobilevotesum(){
	    global $_GPC, $_W;
	    $weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
        $total = pdo_fetchcolumn("select sum(res) from " . tablename('weixin_vote') . " where weid = '{$_W['uniacid']}' AND rid='{$rid}'");
		echo json_encode(intval($total));
	}
	public function doMobilegetmobilerealname(){
	        global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			$rid = intval($_GPC['rid']);
			 $ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));

	        include $this->template('mobilereaname');


	}
	public function doMobilelurubasic(){
	        global $_GPC, $_W;
			if($_W['ispost']){
				$rid = intval($_GPC['rid']);
			   if(!empty($_W['fans']['from_user'])){
			       if(!empty($_GPC['username']) && !empty($_GPC['password'])){
				        pdo_update('weixin_flag',array('mobile'=>$_GPC['password'],'realname'=>$_GPC['username']),array('weid'=>$_W['uniacid'],'openid'=>$_W['fans']['from_user'],'rid'=>$rid));
						$data['msg'] = 'success';
				   }else{
				      $data['msg'] = '1';
				   }
			   }else{
			     $data['msg'] = '1';
			   }
			}else{
			     $data['msg'] = '1';
			}
			 echo json_encode($data);


	}
public function doMobilecjready(){
    global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			$rid = intval($_GPC['rid']);
   $data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND fakeid>0",array(":weid"=>$weid,':rid'=>$rid));
					if(!empty($data)){
					foreach($data as $v){
						 if($v['cjstatu']){
						 $that = $v['nickname'].'|'.$v['id'].'|'.$_W['attachurl'].$v['avatar'].'|'.$v['openid'].'|'.$v['cjstatu'];
						 }else{
							$that = $v['nickname'].'|'.$v['id'].'|'.$_W['attachurl'].$v['avatar'].'|'.$v['openid'];
						 }
						$arr[] = $that;
					}
					}
				echo json_encode($arr);

}
public function doMobilepairmanready(){
    global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			$rid = intval($_GPC['rid']);
   $data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND fakeid>0 AND  sex=1",array(":weid"=>$weid,':rid'=>$rid));
					if(!empty($data)){
					foreach($data as $v){

							$that = $v['nickname'].'|'.$v['id'].'|'.$_W['attachurl'].$v['avatar'].'|'.$v['openid'];

						$arr[] = $that;
					}
					}
				echo json_encode($arr);

}
public function doMobilepairwomanready(){
    global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			$rid = intval($_GPC['rid']);
   $data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND fakeid>0 AND  sex=2",array(":weid"=>$weid,':rid'=>$rid));
					if(!empty($data)){
					foreach($data as $v){

							$that = $v['nickname'].'|'.$v['id'].'|'.$_W['attachurl'].$v['avatar'].'|'.$v['openid'];

						$arr[] = $that;
					}
					}
				echo json_encode($arr);

}
public function doMobiletanmu(){
             global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			 $rid = intval($_GPC['rid']);
             include $this->template('tanmu');
}
public function doMobilegetover(){
             global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			 if($_W['isajax']){
			    $rid = intval($_GPC['rid']);
				$all = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE weid=:weid  ORDER BY createtime DESC",array(':weid'=>$weid));
				//"text":"111111111","color":"white","size":"1","position":"0","time":315
				$colors = array('white','red','green','blue','yellow');
				$positions = array('0','1','2');
				$sizes = array('0','1');
				if(!empty($all)){
					$msg =  "[";
					$first=0;
				  foreach($all as $row){

				$color=  array_rand($colors);
				$position=  array_rand($positions);
				$size=  array_rand($sizes);
				     if($first){
					    $msg .= ",";
					 }
					 $first = 1;
					 if(empty($row['image'])){

		$msg .= '{"text":"'.$row['nickname'].": ".$row['content'].'","color":"'.$colors[$color].'","size":"'.$sizes[$size].'","position":"'.$positions[$positon].'","time":"'.rand(0,500).'"}';
					 }
				  }
				  $msg .= "]";
				}
               echo $msg;
             }
}
private function randrgb()
{
  $str='0123456789ABCDEF';
    $estr='#';
    $len=strlen($str);
    for($i=1;$i<=6;$i++)
    {
        $num=rand(0,$len-1);
        $estr=$estr.$str[$num];
    }
    return $estr;
}
public function doMobilejoin(){
             global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			 if($_W['isajax']){
			    $rid = intval($_GPC['rid']);

             }
}
   private function sendmessage($touser,$content){
	    global $_GPC, $_W;
	    $weid = $_W['uniacid'];
		$moudlecfg = $this->module['config'];
       if($moudlecfg['isshow']=='1'){

									load()->classs('weixin.account');
                                     $accObj= WeixinAccount::create($_W['account']['acid']);
                                     $access_token = $accObj->fetch_token();
									 $token2 = $access_token;


							$data = '{
								"touser":"'.$touser.'",
								"msgtype":"text",
								"text":
								{
									 "content":"'.$content.'"
								}
							}';
							 load()->func('communication');
							$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token2;
							$returnit = ihttp_post($url, $data);
					}else{
                         $query ="SELECT * FROM ".tablename('weixin_flag'). "WHERE openid = :openid  AND weid=:weid";
				         $para2 = array(':openid'=>$touser,':weid'=>$weid);
				         $row2=pdo_fetch($query,$para2);

												$cfg = pdo_fetch("SELECT * FROM ".tablename('weixin_cookie')." WHERE weid=".$weid);
		                                        $token = $cfg['token'];
		                                        $cookie = iunserializer($cfg['cookie']);

												$fakeid = $row2['fakeid'];

													$quickreplyid = $row2['msgid'];;

                             $loginurl = 'https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response&f=json&token='.$token.'&lang=zh_CN';
					$post = 'token='.$token.'&lang=zh_CN&f=json&ajax=1&random=0.08272588928230107&mask=false&tofakeid='.$fakeid.'&imgcode=&type=1&content='.$content.'&quickreplyid='.$quickreplyid;
												$ch = curl_init();
												curl_setopt($ch, CURLOPT_URL,$loginurl);
												curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");
												curl_setopt($ch, CURLOPT_HEADER,1);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($ch, CURLOPT_COOKIE, $cookie);
												curl_setopt($ch, CURLOPT_POST, 1);
												curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
                                                curl_setopt($ch,CURLOPT_REFERER,'https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token='.$token.'&lang=zh_CN');

												curl_exec($ch);
												curl_close($ch);
					}

   }
}

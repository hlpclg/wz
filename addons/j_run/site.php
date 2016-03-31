<?php
/**
 * 捷讯约跑模块微站定义
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
//include('../addons/j_run/jetsum_function.php');
class J_runModuleSite extends WeModuleSite {
	/*
	*异步提交所有
	*/
	public function doMobileAjax() {
		global $_W,$_GPC;
		if(!$_W['isajax'])die(json_encode(array('success'=>false,'msg'=>'无法获取系统信息,请重新打开再尝试')));
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='submitmark'){
			$rid=intval($_GPC['rid']);
			$uid=intval($_GPC['uid']);
			$distance=intval($_GPC['meter']);
			$fans=pdo_fetch('select * from '.tablename('j_run_member').' where id=:id and rid=:rid ',array(':rid'=>$rid,':id'=>$uid));
			if(empty($fans))die(json_encode(array('success'=>false,'msg'=>'没有您的数据哦')));
			if($fans['distance']<$distance){
				pdo_update('j_run_member',array('distance'=>$distance,'time'=>TIMESTAMP),array('id'=>$fans['id']));
			}
			$fid=0;
			if($fans['from_uer']!=$_W['openid'] || !$_W['openid'])$fid=1;
			die(json_encode(array('success'=>true,'fid'=>$fid)));
		}
		if($operation=='loginmobile'){
			
			$rid=intval($_GPC['rid']);
			$code=$_GPC['code'];
			if(!$rid || !$code)die(json_encode(array('success'=>false,"msg"=>"参数不能为空")));
			
			$item=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_reply')." WHERE rid =:rid and code =:code ",array(":rid"=>$rid,":code"=>$code,));
			if(empty($item))die(json_encode(array('success'=>false,"msg"=>"游戏号或验证码错误！")));
			
			$item=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_admin')." WHERE rid =:rid and from_user =:from_user ",array(":rid"=>$rid,":from_user"=>$from_user,));
			if($item)die(json_encode(array('success'=>false,"msg"=>"你的资料正在审核中，请不用重复提交！")));
			$fans=$this->fansInfo($_W['openid']);
			$data=array(
				'rid'=>$rid,
				'weid'=>$_W['uniacid'],
				'from_user'=>$_W['openid'],
				'nickname'=>$fans['nickname'],
				'headimgurl'=>$fans['headimgurl'],
				'status'=>0,
			);
			pdo_insert('j_run_admin',$data);
			die(json_encode(array('success'=>true)));
		}
		if($operation=='getuserprize'){
			$code=urldecode($_GPC['code']);
			if(!$code)die(json_encode(array('success'=>false,"msg"=>"参数不能为空")));
			//$code=str_replace(" ","",$code);
			//$content=encrypt($code, 'D', "j");
			$content=base64_decode($code);
			//die(json_encode(array('success'=>false,"msg"=>$content)));
			$ary=explode("|#|",$content);
			if(count($ary)!=2)die(json_encode(array('success'=>false,"msg"=>"编码错误")));
			$rid=intval($ary[0]);
			$uid=intval($ary[1]);
			$isGet=pdo_fetch("SELECT * FROM ".tablename('j_run_convert')." WHERE rid=:rid and uid=:uid",array(":rid"=>$rid,":uid"=>$uid));
			if($isGet)die(json_encode(array('success'=>false,"msg"=>"已于".date('m/d H:i',$isGet['createtime'])."已经领取了哦")));
			
			$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid ",array(":rid"=>$rid));
			$user=pdo_fetch("SELECT * FROM ".tablename('j_run_member')." WHERE id = :id ",array(':id'=>$uid));
			//-----------
			$marks=pdo_fetchcolumn("SELECT sum(distance) as mark FROM ".tablename('j_run_member')." WHERE rid=:rid and (id=:id or helpid=:helpid) ",array(':rid'=>$rid,':id'=>$user['id'],':helpid'=>$user['id']));
			
			if(!$item['gametype']){
				$sql="SELECT A.id FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0  group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc";
				if($item['issex']){
					$sql="SELECT A.id FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.sex='".$user['sex']."' order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc ";
				}
				$listrank = pdo_fetchall($sql);
				$myselfRank=1;
				foreach($listrank as $row){
					if($user['id']==$row['id']){
						break;
					}
					$myselfRank++;
				}
				//die(json_encode(array('success'=>false,'msg'=>$myselfRank)));
				
				if($myselfRank && $myselfRank<=$item['ranking'] && $item['ranking']){
					die(json_encode(array('success'=>true,"item"=>$user,'rank'=>$myselfRank,'marks'=>$marks)));
				}else{
					die(json_encode(array('success'=>false,'msg'=>"当前名次为".$myselfRank."名，活动只奖励前".$item['rank']."名哦")));
				}
			}else{
				if($marks>=$item['need']){
					die(json_encode(array('success'=>true,"item"=>$user,'marks'=>$marks)));
				}else{
					die(json_encode(array('success'=>false,"msg"=>"必须要达到".$item['need']."米才能兑换奖励哦")));
				}
			}
		}
		if($operation=="dealprize"){
			$id=intval($_GPC['id']);
			$gid=intval($_GPC['gid']);
			if(!$id || !$gid)die(json_encode(array('success'=>false,"msg"=>"1参数不能为空")));
			
			$user=pdo_fetch("SELECT * FROM ".tablename('j_run_member')." WHERE id = :id ",array(':id'=>$id));
			$gift=pdo_fetch("SELECT * FROM ".tablename('j_run_gift')." WHERE id = :id ",array(':id'=>$gid));
			
			if(empty($user) || empty($gift))die(json_encode(array('success'=>false,"msg"=>"2参数不能为空")));
			if(empty($gift['remain']))die(json_encode(array('success'=>false,"msg"=>$gift['title']."已兑换完了，请更换其他奖品哦")));
			$isGet=pdo_fetch("SELECT * FROM ".tablename('j_run_convert')." WHERE rid=:rid and id=:id",array(":rid"=>$user['rid'],":id"=>$id));
			if($isGet)die(json_encode(array('success'=>false,"msg"=>"已于".date('m/d H:i',$isGet['createtime'])."已经领取了哦")));
			$data=array(
				"weid"=>$_W['uniacid'],
				"rid"=>$user['rid'],
				"openid"=>$user['openid'],
				"uid"=>$user['id'],
				"nickname"=>$user['nickname'],
				"giftid"=>$gid,
				"giftname"=>$gift['title'],
				"createtime"=>TIMESTAMP,
				"istaken"=>1,
			);
			pdo_insert("j_run_convert",$data);
			pdo_update('j_run_gift',array('remain'=>$gift['remain']-1),array('id'=>$gid));
			$this->sendtext("您已成功兑换".$gift['title']."，感谢您参与本次活动哦。",$user['from_user']);
			die(json_encode(array('success'=>true)));
		}
		if($operation=='getrank'){
			$rid=intval($_GPC['rid']);
			$sex=intval($_GPC['sex']);
			$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid",array(':rid'=>$rid));
			if(empty($item))message('活动不存在或已删除！');
			$showNum=10;
			$pindex = max(2, intval($_GPC['page']));
			$psize = $showNum;
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 ",array(':rid'=>$rid));
			$start = ($pindex - 1) * $psize;
			$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0  group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize}";
			if($item['issex']){
				$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0  group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.sex='".$sex."' order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize}";
				$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and sex='".$sex."'",array(':rid'=>$rid));
			}
			$list = pdo_fetchall($sql);
			
			die(json_encode(array('success'=>true,'item'=>$list)));
		}
	}
	/*
	*手机奖品核销端
	*/
	public function doMobileCollect() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$rid=intval($_GPC['rid']);
		if(!$_W['openid'])message('请用微信登陆');
		$user=pdo_fetch("SELECT * FROM ".tablename('j_run_admin')." WHERE from_user=:from_user",array(':from_user'=>$_W['openid']));
		
		if(empty($user)){
			include $this->template('cancellation2');
		}else{
			$rid=$user['rid'];
			if(!$user['status'])message("您的资料正在审核中，请管理员审核！");
			
			$item = pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid =:rid ",array(':rid'=>$rid));
			$getcount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_convert')." WHERE rid =:rid ",array(':rid'=>$rid));
			$giftlist=pdo_fetchall("SELECT * FROM ".tablename('j_run_gift')." WHERE rid=:rid order by id desc ",array(':rid'=>$rid));
			include $this->template('cancellation');
		}
		
	}
	/*
	*鉴权
	*/
	public function doMobileOauth(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];
		load()->func('communication');
		$rid=intval($_GPC['rid']);
		$fid=intval($_GPC['fid']);
		$add=intval($_GPC['add']);
		$reply=pdo_fetch('select * from '.tablename('j_run_reply').' where rid=:rid order BY id DESC LIMIT 1',array(':rid'=>$rid));
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$reply['appid']."&secret=".$reply['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$auth = @json_decode($ret['content'], true);
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);
					$auth = @json_decode($ret['content'], true);
					$insert=array(
						'weid'=>$_W['uniacid'],
						'openid'=>$auth['openid'],
						'nickname'=>$auth['nickname'],
						'sex'=>$auth['sex'],
						'headimgurl'=>$auth['headimgurl'],
						'unionid'=>$auth['unionid'],
						'rid'=>$rid,
					);
					$from_user=$_W['fans']['from_user'];
					if($auth['unionid'] && !$from_user){
						$from_user=pdo_fetch('select openid from '.tablename('mc_mapping_fans').' where uniacid=:uniacid AND  unionid=:unionid',array(':uniacid'=>$_W['uniacid'],':unionid'=>$auth['unionid']));
					}
					isetcookie('jrun_openid'.$rid, $auth['openid'], 1 * 86400);
					isetcookie('jrun_openid_sex'.$rid, $auth['sex'], 1 * 86400);
					//------------
					$sql='select * from '.tablename('j_run_member').' where rid=:rid AND openid=:openid ';
					$where="  ";
					if($fid){
						$ower=pdo_fetch('select * from'.tablename('j_run_member').' where id='.$fid);
						if(empty($ower)){
							$fid=$_GPC['fid']=0;
							$where=" and helpid=0 ";
						}else{
							if($ower['openid']==$auth['openid']){
								$where=" and helpid=0 ";
								$fid=$_GPC['fid']=0;
							}else{
								$where=" and helpid>0 ";
							}
						}
					}
					
					if($add){
						$fid=$_GPC['fid']=0;
						$where=" and helpid=0 ";
					}else{
						if($fid && $reply['helpnum']){
							$ownerCount=pdo_fetchcolumn("select count(*) from ".tablename('j_run_member')." where rid=:rid AND helpid=:helpid",array(":rid"=>$rid,":helpid"=>$fid));
							if($ownerCount>=$reply['helpnum']){
								message('本次活动每人只允许【'.$reply['helpnum'].'】个朋友帮忙哦',$_W['siteroot'].$this->createMobileurl('index',array('rid'=>$rid,'add'=>1)),'error');
							}
						}
					}
					//die($sql.$where);
					$fans=pdo_fetch($sql.$where." order by helpid asc limit 1 " ,array(':rid'=>$rid,':openid'=>$auth['openid']));
					if(empty($fans)){
						$insert['helpid']=intval($fid);
						$insert['from_user']=$from_user;
						if($_W['account']['key']==$reply['appid'])$insert['from_user']=$auth['openid'];
						pdo_insert('j_run_member',$insert);
						if($fid && !$add){
							$this->sendtext("您的朋友".$auth['nickname']."来助您一臂之力了哦~赶快去看看吧",$ower['from_user']);
							$forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=share&m=j_run&rid=".$rid."&openid=".$auth['openid']."&fid=".$fid."&wxref=mp.weixin.qq.com#wechat_redirect";
							header('location:'.$forward);
							exit;
						}
					}
					$forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=index&m=j_run&rid=".$rid."&openid=".$auth['openid']."&wxref=mp.weixin.qq.com#wechat_redirect";
					header('location:'.$forward);
					exit;
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
			$forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=index&m=j_run&rid=".$rid."&wxref=mp.weixin.qq.com#wechat_redirect";
			header('location: ' .$forward);
			exit;
		}
	}
	/*
	*游戏页面
	*/
	public function doMobileIndex() {
		global $_GPC, $_W;
		$cfg = $this->module['config'];
		if(isset($_GPC['r'])){
			$r=intval($_GPC['r']);
			if(TIMESTAMP-$r>(60*$cfg['key_wordtime']) && $cfg['key_wordtime'])message('链接已失效，请重新触发进入哦');
		}
		$rid= intval($_GPC['rid']);
		if(empty($rid))message ('入口不正确');
		$add=intval($_GPC['add']);
		$reply=pdo_fetch('select * from '.tablename('j_run_reply').' where rid=:rid order BY id DESC LIMIT 1 ',array(':rid'=>$rid));
		if(empty($reply))message('活动不存在或已删除！');
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,下周再来吧',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if($reply['status']!=1)message('活动已经结束了哦',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if(empty($_GPC['jrun_openid'.$rid])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('rid'=>$rid))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				header('location:'.$this->createMobileUrl('index',array('rid'=>$rid)));
				exit();
			}
		}else{
			$openid=$_GPC['jrun_openid'.$rid];
		}
		
		$sql='select * from '.tablename('j_run_member').' where rid=:rid AND openid=:openid order by helpid asc limit 1';
		if($add){
			$sql='select * from '.tablename('j_run_member').' where rid=:rid AND openid=:openid and helpid =0 ';
		}
		$fans=pdo_fetch($sql,array(':rid'=>$rid,':openid'=>$openid));
		if(empty($fans)){
			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('rid'=>$rid,'add'=>$add,))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		if($fans['enable'])message('您的账号存在作弊问题，请联系管理员');
		if($fans['helpid']>0 && !$add){
			header('location:'.$this->createMobileUrl('share',array('rid'=>$rid,'fid'=>$fans['helpid'])));
			exit();
		}
		if(empty($fans['nickname'])){
 			//如果没有昵称，直接重新获取授权
			pdo_update('j_run_member',array('nickname'=>'无法读取表情'),array('id'=>$fans['id']));
			$fans['nickname']='无法读取表情';
		}
		//如果没有$_W['fans']['from_user'];这里看上去是重复操作，但是如果第一次授权了，第二次进来没授权，这里有必要
		if(!empty($_W['fans']['from_user']) && empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('j_run_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		
		$follow = pdo_fetch('select follow from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=1;
		if( empty($fans['from_user']) || $follow['follow'] <> 1){
			$status=0;
		}
		if($fans){
			$geili= pdo_fetchall('select * from '.tablename('j_run_member').' where (helpid=:helpid or id=:id) and rid=:rid order by helpid asc',array(':helpid'=>$fans['id'],':rid'=>$rid,':id'=>$fans['id']));
			$sum=0;
			foreach($geili as $row){
				$sum=$sum+$row['distance'];
			}
			array_push($geili,array('distance'=>0));
			$is_getprize=pdo_fetch('select * from '.tablename('j_run_convert').' where rid=:rid and uid=:uid',array(':rid'=>$rid,':uid'=>$fans['id']));
			//$qrcode=encrypt($rid."|#|".$fans['id'], 'E', 'j');
			$qrcode=base64_encode($rid."|#|".$fans['id']);
			$friendid=pdo_fetchcolumn("SELECT helpid FROM ".tablename('j_run_member')." WHERE rid=:rid and openid=:openid and helpid>0",array(':rid'=>$rid,":openid"=>$openid));
			if($friendid)$frdnickname=pdo_fetchcolumn("SELECT nickname FROM ".tablename('j_run_member')." WHERE id=:id ",array(':id'=>$friendid));
		}
		$share_des=$reply['share_title'] ? $reply['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['distance']),$share_des);
		include $this->template ('index');
	}
	/*
	*助跑
	*/
	public function doMobileShare() {
		global $_GPC, $_W;
		//$cfg = $this->module['config'];
		$rid= intval($_GPC['rid']);
		$fid= intval($_GPC['fid']);
		if(empty($rid))message ( '入口不正确' );
		//不是分享进入，跳转到自主页面
		if(!$fid)header('location:'.$_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('index',array('rid'=>$rid))));
		$member=pdo_fetch('select * from '.tablename('j_run_member').' where rid=:rid AND  id=:id ',array(':rid'=>$rid,':id'=>$fid));
		if(!$member)header('location:'.$_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('index',array('rid'=>$rid))));
		
		$reply=pdo_fetch('select * from '.tablename('j_run_reply').' where rid=:rid order BY id DESC LIMIT 1',array(':rid'=>$rid));
		if(empty($reply))message('活动不存在或已删除！');
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,下周再来吧',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if($reply['status']!=1)message('活动已经结束了哦',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		
		if(empty($_GPC['jrun_openid'.$rid])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('rid'=>$rid,'fid'=>$_GPC['fid']))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				header('location:'.$this->createMobileUrl('index',array('rid'=>$rid)));
				exit();
			}
		}else{
			$openid=$_GPC['jrun_openid'.$rid];
		}
		$fans=pdo_fetch('select * from '.tablename('j_run_member').' where rid=:rid AND openid=:openid and helpid=:helpid',array(':rid'=>$rid,':helpid'=>$fid,':openid'=>$openid));
		
		if(empty($fans['nickname'])){
			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('rid'=>$rid,'fid'=>$_GPC['fid']))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		//如果没有$_W['fans']['from_user'];这里看上去是重复操作，但是如果第一次授权了，第二次进来没授权，这里有必要
		if(!empty($_W['fans']['from_user'])&&empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('j_run_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		$follow = pdo_fetch('select follow from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=1;
		if(empty($fans['from_user'])||$follow['follow'] <> 1)$status=0;
		
		$geili= pdo_fetchall('select * from '.tablename('j_run_member').' where (helpid=:helpid or id=:id) and rid=:rid order by helpid asc',array(':helpid'=>$fid,':rid'=>$rid,':id'=>$fid));
		$sum=0;
		foreach($geili as $row){
			$sum=$sum+$row['distance'];
		}
		array_push($geili,array('distance'=>0));
		$self=pdo_fetch('select * from '.tablename('j_run_member').' where rid=:rid AND openid=:openid and helpid=0',array(':rid'=>$rid,':openid'=>$openid));
		$share_des=$reply['share_title'] ? $reply['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['distance']),$share_des);
		
		include $this->template('shareindex');
	}
	/*
	*游戏页面
	*/
	public function doMobileGame() {
		global $_GPC,$_W;
		$rid=intval($_GPC['rid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid",array(':rid'=>$rid));
		if(empty($item))message('活动不存在或已删除！');
		$uid=intval($_GPC['uid']);
		if(!$uid)message('入口不正确！');
		if(empty($item))message('活动不存在或已删除！');
		if(TIMESTAMP<$item['starttime'])message('活动在'.date('Y-m-d H:i',$item['starttime']).'开始,到时再来哦',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if(TIMESTAMP>$item['endtime'])message('活动在'.date('Y-m-d H:i',$item['endtime']).'结束啦,下周再来吧',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		if($item['status']!=1)message('活动已经结束了哦',$this->createMobileUrl('rank',array('rid'=>$rid)), 'error');
		$fans=pdo_fetch('select * from '.tablename('j_run_member').' where rid=:rid AND id=:id ',array(':rid'=>$rid,':id'=>$uid));
		if(!$fans)message('入口不正确！');
		$selfOpenid=$_GPC['jrun_openid'.$rid];
		$openid=$fans['openid'];
		$nickname=$fans['nickname'];
		$headimgurl=$fans['headimgurl'];
		$fid=$fans['helpid'];
		if($fid)$owner=pdo_fetch('select * from '.tablename('j_run_member').' where rid=:rid AND id=:id ',array(':rid'=>$rid,':id'=>$fid));
		$slogan=str_replace(array("\r\n","\r"),"','",$item["slogan"]);
		$personl_stand=strpos($item['img_personImg'],'addons')!=3 ? $_W['attachurl'].$item['img_personImg'] : $item['img_personImg'];
		$personl_run=strpos($item['img_personsImg'],'addons')!=3 ? $_W['attachurl'].$item['img_personsImg'] : $item['img_personsImg'];
		if($fans['sex']==2){
			$personl_stand=strpos($item['img_personImg_girl'],'addons')!=3 ? $_W['attachurl'].$item['img_personImg_girl'] : $item['img_personImg_girl'];
			$personl_run=strpos($item['img_personsImg_girl'],'addons')!=3 ? $_W['attachurl'].$item['img_personsImg_girl'] : $item['img_personsImg_girl'];
		}
		if(!$personl_stand)$personl_stand="../addons/j_run/template/mobile/img/img_personImg.png";
		if(!$personl_run)$personl_run="../addons/j_run/template/mobile/img/img_personsImg.png";
		$status=0;//不是自己
		if($fid){
			$status=1;
		}
		$share_des=$item['share_title'] ? $item['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['distance']),$share_des);
		include $this->template('game');
	}
	/*
	*游戏排名
	*/
	public function doMobileRank() {
		global $_GPC,$_W;
		$rid=intval($_GPC['rid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid",array(':rid'=>$rid));
		if(empty($item))message('活动不存在或已删除！');
		$openid=$_GPC['jrun_openid'.$rid];
		$sex=$_GPC['jrun_openid_sex'.$rid];
		
		if(!$sex)$sex=1;
		$showNum=10;
		$pindex = max(1, intval($_GPC['page']));  
		$psize = $showNum;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and enable=0",array(':rid'=>$rid));
		$start = ($pindex - 1) * $psize;
		$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 and enable=0  group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.enable=0 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize}";
		if($item['issex']){
			$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 and enable=0  group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.sex='".$sex."' and A.enable=0 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize}";
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and sex='".$sex."' and enable=0",array(':rid'=>$rid));
		}
		$list = pdo_fetchall($sql);
		
		$pager = pagination($total, $pindex, $psize);
		$allpage= $total % $psize ==0 ? $total / $psize : ($total / $psize)+1;
		
		if($openid || $_W['fans']['from_user']){
			$sql="SELECT * FROM ".tablename('j_run_member')." WHERE rid=:rid and openid='".$openid."' and helpid=0";
			if($_W['fans']['from_user']&&!$openid)$sql="SELECT * FROM ".tablename('j_run_member')." WHERE rid=:rid and from_user='".$_W['fans']['from_user']."' and helpid=0";
			$fans=pdo_fetch($sql,array(':rid'=>$rid));
			if($fans){
				if($fans['enable'])message('您的账号存在作弊问题，请联系管理员');
				$self=pdo_fetch("SELECT count(*) as num,sum(distance) as mark FROM ".tablename('j_run_member')." WHERE rid=:rid and (id=:id or helpid=:helpid) and enable=0",array(':rid'=>$rid,':id'=>$fans['id'],':helpid'=>$fans['id']));
				//$qrcode=encrypt($rid."|#|".$fans['id'], 'E', 'j');
				$qrcode=base64_encode($rid."|#|".$fans['id']);
				$is_getprize=pdo_fetchcolumn('select count(*) from '.tablename('j_run_convert').' where rid=:rid and uid=:uid',array(':rid'=>$rid,':uid'=>$fans['id']));
				$sql="SELECT A.id FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 and enable=0 group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."'and A.enable=0 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc ";
				if($item['issex'])$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 AND enable=0  group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.sex='".$sex."' AND A.enable=0 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc ";
				$listrank = pdo_fetchall($sql);
				$myselfRank=0;
				for($i=0;$i<count($listrank);$i++){
					if($listrank[$i]['id']==$fans['id']){
						$myselfRank=$i+1;
						break;
					}
				}
			}
		}
		$giftlist=pdo_fetchall("SELECT * FROM ".tablename('j_run_gift')." WHERE rid=:rid order by need desc,id desc",array(':rid'=>$rid));
		$prizelist=pdo_fetchall("SELECT nickname,giftname FROM ".tablename('j_run_convert')." WHERE rid=:rid order by id desc limit 0,10",array(':rid'=>$rid));
		
		include $this->template('rank');
	}
	/*
	*会员管理页
	*/
	public function doWebUser() {
		global $_GPC,$_W;
		$rid=intval($_GPC['rid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid ",array(':rid'=>$rid));
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($op=='display'){
			$pindex = max(1, intval($_GPC['page']));  
			$psize = 20;
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and enable=0",array(':rid'=>$rid));
			$start = ($pindex - 1) * $psize;
			$where="";
			$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 and enable=0 group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.enable=0  order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize} ";
			if($_GPC['sex']){
				$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 and enable=0 group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.sex='".$_GPC['sex']."' and A.enable=0 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize} ";
				$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and sex='".$_GPC['sex']."'",array(':rid'=>$rid));
			}
			$list = pdo_fetchall($sql);
			$pager = pagination($total, $pindex, $psize);
		}elseif($op=='post'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			$list = pdo_fetchall("SELECT * FROM ".tablename('j_run_member')." where rid=:rid and (id=:id or helpid=:id) order by id asc",array(':rid'=>$rid,':id'=>$id));
		}elseif($op=='displayblack'){
			$pindex = max(1, intval($_GPC['page']));  
			$psize = 20;
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and enable=1",array(':rid'=>$rid));
			$start = ($pindex - 1) * $psize;
			$where="";
			$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0 group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.enable=1 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize} ";
			if($_GPC['sex']){
				$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num,0) as nums,A.distance,(A.distance+ifnull(B.mark,0)) as marks FROM ".tablename('j_run_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num ,ifnull(sum(distance),0) as mark from ".tablename('j_run_member')." where helpid>0   group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.rid='".$rid."' and A.sex='".$_GPC['sex']."' and A.enable=1 order by (A.distance+ifnull(B.mark,0)) desc,A.distance desc,B.num desc limit {$start},{$psize} ";
				$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_member')." where rid=:rid and helpid=0 and sex='".$_GPC['sex']."'",array(':rid'=>$rid));
			}
			$list = pdo_fetchall($sql);
			$pager = pagination($total, $pindex, $psize);
		}elseif($op=='post'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			$list = pdo_fetchall("SELECT * FROM ".tablename('j_run_member')." where rid=:rid and (id=:id or helpid=:id) order by id asc",array(':rid'=>$rid,':id'=>$id));
			
		}elseif($op=='black'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_update('j_run_member',array('enable'=>1),array('id'=>$id));
			message('加入黑名单成功！',$this->createWeburl('user',array('op'=>'post','rid'=>$rid,'id'=>$id)), 'success');
		}elseif($op=='outblack'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_update('j_run_member',array('enable'=>0),array('id'=>$id));
			message('还原成功！',$this->createWeburl('user',array('op'=>'post','rid'=>$rid,'id'=>$id)), 'success');
		}elseif($op=='delete'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_delete('j_run_member',array('id'=>$id,'helpid'=>$id),' or ');
			message('删除数据成功！',$this->createWeburl('user',array('rid'=>$rid)), 'success'); 
		}
		include $this->template('adv_user');
	}
	/*
	*领奖记录
	*/
	public function doWebRecord() {
		global $_GPC,$_W;
		$rid=intval($_GPC['rid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid ",array(':rid'=>$rid));
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($op=='display'){
			$pindex = max(1, intval($_GPC['page']));  
			$psize = 20;
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_run_convert')." where rid=:rid order by id desc",array(':rid'=>$rid));
			$start = ($pindex - 1) * $psize;
			$list = pdo_fetchall("select a.id,b.headimgurl,b.sex,a.uid,a.nickname,a.giftname,a.createtime from ".tablename('j_run_convert')." as a left join (select * from ".tablename('j_run_member')." where id in(select uid from ".tablename('j_run_convert').") and rid=".$rid.")  as b on a.uid=b.id limit {$start},{$psize}");
			$pager = pagination($total, $pindex, $psize);
		}elseif($op=='delete'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_delete('j_run_convert',array('id'=>$id));
			message('删除数据成功！',$this->createWeburl('record',array('rid'=>$rid)), 'success');
		}
		include $this->template('adv_records');
	}
	/*
	*核销人员
	*/
	public function doWebAdmin() {
		global $_GPC,$_W;
		$rid=intval($_GPC['rid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('j_run_reply')." WHERE rid=:rid ",array(':rid'=>$rid));
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($op=='display'){
			$list = pdo_fetchall("SELECT * FROM ".tablename('j_run_admin')." where rid=:rid order by id desc",array(':rid'=>$rid));
		}elseif($op=='ok'){
			$id=intval($_GPC['id']);
			$status=intval($_GPC['st']);
			if(empty($id))message('参数错误，请确认操作');
			pdo_update('j_run_admin',array('status'=>$status),array('id'=>$id));
			if($status){
				$user=pdo_fetchcolumn("SELECT from_user FROM ".tablename('j_run_admin')." WHERE id=:id ",array(':id'=>$id));
				$this->sendtext("您申请的核销管理资格已通过！",$user);
			}
			message('处理完成！',$this->createWeburl('admin',array('rid'=>$rid)), 'success');
		}elseif($op=='delete'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_delete('j_run_admin',array('id'=>$id));
			message('删除数据成功！',$this->createWeburl('admin',array('rid'=>$rid)), 'success');
		}
		include $this->template('admin');
	}
	/*
	*广告设置
	*/
	public function doWebAdvert() {
		global $_GPC, $_W;
		$rid=intval($_GPC['rid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$category = pdo_fetchall("SELECT * FROM ".tablename('j_run_ad')." WHERE weid = '{$_W['uniacid']}' order by id desc");
		} elseif ($operation == 'post') {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			if(!empty($id)) {
				$category = pdo_fetch("SELECT * FROM ".tablename('j_run_ad')." WHERE id = '$id'");
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) message('抱歉，请输入广告名称！');
				$data = array(
					'weid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'thumb' => $_GPC['thumb'],
					'description' => $_GPC['description'],
					'url' => $_GPC['url'],
				);
				if (!empty($id)) {
					pdo_update('j_run_ad', $data, array('id' => $id));
				} else {
					pdo_insert('j_run_ad', $data);
				}
				message('更新广告成功！', $this->createWebUrl('advert', array('op' => 'display')), 'success');
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('j_run_ad', array('id' => $id));
			message('广告删除成功！', $this->createWebUrl('advert', array('op' => 'display',)), 'success');
		}
		include $this->template('advert');
	}
	/*
	*奖品设置
	*/
	public function doWebGift() {
		global $_GPC, $_W;
		$rid=intval($_GPC['rid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$category = pdo_fetchall("SELECT * FROM ".tablename('j_run_gift')." WHERE rid ='".$rid."' order by id desc");
		} elseif ($operation == 'post') {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			if(!empty($id)) {
				$category = pdo_fetch("SELECT * FROM ".tablename('j_run_gift')." WHERE id = '$id'");
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) message('抱歉，请输入名称！');
				$data = array(
					'weid' => $_W['uniacid'],
					'rid' => intval($_GPC['rid']),
					'title' => $_GPC['title'],
					'thumb' => $_GPC['thumb'],
					'total' => intval($_GPC['total']),
					'need' => intval($_GPC['need']),
					'remain' => intval($_GPC['remain']),
				);
				if (!empty($id)) {
					pdo_update('j_run_gift', $data, array('id' => $id));
				} else {
					$data['remain']=$data['total'];
					pdo_insert('j_run_gift', $data);
				}
				message('更新成功！', $this->createWebUrl('gift', array('op' => 'display','rid' => $rid)), 'success');
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('j_run_gift', array('id' => $id));
			message('删除成功！', $this->createWebUrl('gift', array('op' => 'display','rid' => $rid)), 'success');
		}
		include $this->template('gift');
	}
	/**
	* 发送客服消息
	* $access_token= account_weixin_token($_W['account']);
	* 当用户接到到一条模板消息，会给公共平台api发送一个xml文件【待处理】
	*/	
	private function sendtext($txt,$openid){
		global $_W;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;
	}
	/**
	* 获取客户资料
	* $access_token= account_weixin_token($_W['account']);
	* 当用户接到到一条模板消息，会给公共平台api发送一个xml文件【待处理】
	*/	
	private function fansInfo($openid){
		global $_W;
		$acc = WeAccount::create($_W['account']['acid']);
		$data = $acc->fansQueryInfo($openid);
		return $data;
	}
	/**
	* 获取客户资料
	* $access_token= account_weixin_token($_W['account']);
	* 当用户接到到一条模板消息，会给公共平台api发送一个xml文件【待处理】
	*/	
	private function sendWebtext($txt,$openid){
		global $_W;
		$acc = WeAccount::create($_W['account']['acid']);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;
	}
	
	
}
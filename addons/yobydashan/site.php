<?php
/**
 * 搭讪管家模块微站定义
 *
 * @author Yoby
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
function gett88($pubtime) {
    $time = time ();
    /** 如果不是同一年 */
    if (idate ( 'Y', $time ) != idate ( 'Y', $pubtime )) {
        return date ( 'Y-m-d', $pubtime );
    }
 
    /** 以下操作同一年的日期 */
    $seconds = $time - $pubtime;
    $days = idate ( 'z', $time ) - idate ( 'z', $pubtime );
 
    /** 如果是同一天 */
    if ($days == 0) {
        /** 如果是一小时内 */
        if ($seconds < 3600) {
            /** 如果是一分钟内 */
            if ($seconds < 60) {
                if (3 > $seconds) {
                    return '刚刚';
                } else {
                    return $seconds . '秒前';
                }
            }
            return intval ( $seconds / 60 ) . '分钟前';
        }
        return idate ( 'H', $time ) - idate ( 'H', $pubtime ) . '小时前';
    }
 
    /** 如果是昨天 */
    if ($days == 1) {
        return '昨天 ' . date ( 'H:i', $pubtime );
    }
 
    /** 如果是前天 */
    if ($days == 2) {
        return '前天 ' . date ( 'H:i', $pubtime );
    }
 
    /** 如果是7天内 */
    if ($days < 7) {
        return $days. '天前';
    }
 
    /** 超过7天 */
    return date ( 'n-j H:i', $pubtime );
}
class YobydashanModuleSite extends WeModuleSite {
	
	public function doWebFensi() {
	global $_W,$_GPC;
$weid = $_W['weid'];
$yobyurl = $_W['siteroot']."addons/yobydashan/";

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
 if('del' == $op){//删除
 	
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				
				foreach($_GPC['delete'] as $del){
					$row1 = pdo_fetch("SELECT id,fromuser FROM ".tablename('yobydashan_user')." WHERE id = :id", array(':id' => $del));
					pdo_delete('yobydashan_sms', array('weid' => $weid,'fromuser'=>$row1['fromuser']));
			pdo_delete('yobydashan_friend',array('weid' => $weid,'fromuser'=>$row1['fromuser']));	
				}
				$sqls = "delete from  ".tablename('yobydashan_user')."  where id in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}else{
	
				$id = intval($_GPC['id']);
			
			
			pdo_delete('yobydashan_user', array('id' => $id));
			pdo_delete('yobydashan_sms', array('weid' => $weid,'fromuser'=>$row['fromuser']));
			pdo_delete('yobydashan_friend',array('weid' => $weid,'fromuser'=>$row['fromuser']));
			message('删除成功！', referer(), 'success');	
			
						
			}
			
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			
				$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " and (wid LIKE '%".$_GPC['keyword']."%' OR yname like '%".$_GPC['keyword']."%' OR xi like '%".$_GPC['keyword']."%') ";
			}
			
			$list = pdo_fetchall("SELECT * FROM ".tablename('yobydashan_user')." where weid=".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yobydashan_user')."  where weid=".$weid.$condition);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('index');
		}	
	}
	public function doMobileSms() {
		//收消息界面
		global $_W,$_GPC;
$weid = $_W['weid'];
$yobyurl = $_W['siteroot']."addons/yobydashan/";
empty ( $_W['openid'])?message('非法进入,请发送@进入!'):$openid = $_W['openid'];

			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
$wid = pdo_fetchcolumn('SELECT wid FROM ' . tablename('yobydashan_user')."  where weid=".$weid." and fromuser='".$openid."' ");
$list = pdo_fetchall("SELECT * FROM ".tablename('yobydashan_sms')." where weid=".$weid." and touser='".$wid."'  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yobydashan_sms')."  where weid=".$weid." and touser='".$wid."' ");
			$pager = pagination($total, $pindex, $psize);
			
	include $this->template('sms');
	}
	public function doMobileSendsms() {
		//收消息界面
		global $_W,$_GPC;
$weid = $_W['uniacid'];
$yobyurl = $_W['siteroot']."addons/yobydashan/";
empty ( $_W['openid'])?message('非法进入,请发送@进入!'):$openid = $_W['openid'];

			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示

$list = pdo_fetchall("SELECT * FROM ".tablename('yobydashan_sms')." where weid=".$weid." and fromuser='".$openid."' and isread=0  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yobydashan_sms')."  where weid=".$weid." and fromuser='".$openid."' and isread=0");
			$pager = pagination($total, $pindex, $psize);
			
	include $this->template('sendsms');
	}
	public function doMobileSend() {
		//聊天界面
		global $_W,$_GPC;
$weid = $_W['uniacid'];
$yobyurl = $_W['siteroot']."addons/yobydashan/";
empty ( $_W['openid'])?message('非法进入,请发送@进入!'):$openid = $_W['openid'];
$banner = $this->module['config']['img'];
	if(checksubmit('submit')){
				if (empty($_GPC['content'])) {
					message('亲,内容不能为空!');
				}
				if (empty($_GPC['wid'])) {
					message('亲,对方帐号不存在就已经被删除!');
				}
	$content = trim($_GPC['content']);
	$wid = $_GPC["wid"];
	$createtime=time();
	$isread=0;
	$data =array(
	'weid'=>$weid,
	'touser'=>$wid,
	'fromuser'=>$openid,
	'createtime'=>$createtime,
	'isread'=>$isread,
	'content'=>$content,
	);
	$openidf = pdo_fetch("SELECT yname,wid FROM ".tablename('yobydashan_user')." where weid=".$weid."  and fromuser='".$openid."'");
	$openidto = pdo_fetch("SELECT fromuser,yname FROM ".tablename('yobydashan_user')." where weid=".$weid."  and wid='".$wid."'");
	$this->post_send_text($openidto['fromuser'],$openidf['yname']."对你说:".$content."\n <a href='".$_W['siteroot'].'app/'.$this->createMobileUrl('send',array('wid'=>$openidf['wid'],'yname'=>$openidf['yname']))."'>点我回复</a>");
	pdo_insert('yobydashan_sms', $data);
      die('<script>alert("发送成功!");location.href="'.$this->createMobileUrl('send',array('wid'=>$wid,'yname'=>$_GPC["yname"])).'"</script>');
      
	}else{
	$wid = $_GPC["wid"];
	$yname = $_GPC["yname"];
	
	$items = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('yobydashan_friend')." where weid=".$weid." and fromuser='".$openid."'  and wid='".$wid."'");
	
	include $this->template('send');	
	}			
	
	}

	public function doMobileFriend() {
		//好友列表
		global $_W,$_GPC;
$weid = $_W['uniacid'];
$yobyurl = $_W['siteroot']."addons/yobydashan/";
empty ( $_W['openid'])?message('非法进入,请发送@进入!'):$openid = $_W['openid'];

			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示

$list = pdo_fetchall("SELECT * FROM ".tablename('yobydashan_friend')." where weid=".$weid." and fromuser='".$openid."'  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yobydashan_friend')."  where weid=".$weid." and fromuser='".$openid."' ");
			$pager = pagination($total, $pindex, $psize);
	include $this->template('friend');
	}	

	public function doMobileReg() {
		//登记
		global $_W,$_GPC;
$weid = $_W['uniacid'];
$yobyurl = $_W['siteroot']."addons/yobydashan/";
empty ( $_W['openid'])?message('非法进入,请在微信界面发送"@"关键字进入!'):$openid = $_W['openid'];

	if(checksubmit('submit')){
				if (empty($_GPC['yname'])) {
					message('亲,姓名不能为空!');
				}
				if (empty($_GPC['xi'])) {
					message('亲,城市不能为空!!');
				}
		if (empty($openid)) {
				 die('<script>alert("非法注册进入!"); document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {
WeixinJSBridge.call("closeWindow");
});</script>');	
				}
	$wid = pdo_fetchcolumn("SELECT fanid FROM ".tablename('mc_mapping_fans')." where uniacid=".$weid." and openid='".$openid."'");
	$yname = trim($_GPC['yname']);
	$xi= trim($_GPC["xi"]);
	$sex=$_GPC['sex'];
	$data =array(
	'weid'=>$weid,
	'yname'=>$yname,
	'fromuser'=>$openid,
	'xi'=>$xi,
	'sex'=>$sex,
	'wid'=>$wid,
	);
	$item = pdo_fetch("SELECT * FROM ".tablename('yobydashan_user')." WHERE weid = :weid and fromuser=:openid" , array(':weid' => $weid,':openid'=>$openid));
	if(!empty($item['fromuser'])){
	 die('<script>alert("请不要重复注册!"); document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {
WeixinJSBridge.call("closeWindow");
});</script>');	
	}else{
		pdo_insert('yobydashan_user', $data);
      die('<script>alert("注册成功!");document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {
WeixinJSBridge.call("closeWindow");
});</script>');	
	}
      
	}else{	
	
	include $this->template('reg');
	}
	}
	public function doMobileAddfriend() {
		//添加好友
			global $_W,$_GPC;
$weid = $_W['uniacid'];
empty ( $_W['openid'])?message('非法进入,请发送@进入!'):$openid = $_W['openid'];
empty ($_GPC['wid'])?message('页面超时,重新进入!'):$wid = $_GPC["wid"];
empty ($_GPC['yname'])?message('亲,姓名不能为空'):$yname=$_GPC['yname'];
$item = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('yobydashan_friend')." where weid=".$weid." and fromuser='".$openid."'  and wid='".$wid."'");
if($item==0){
	$data = array(
	'weid'=>$weid,
	'fromuser'=>$openid,
	'wid'=>$wid,
	'yname'=>$yname,
	);
	pdo_insert('yobydashan_friend', $data);
	message('添加好友成功！', $this->createMobileUrl('send',array('wid'=>$wid,'yname'=>$_GPC["yname"])), 'success');
}


	}	

public function doMobileDelfriend(){//删除一个好友
	global $_W,$_GPC;
	$id = intval($_GPC['id']);
	pdo_delete('yobydashan_friend', array('id' => $id));
	message('删除好友成功！', $this->createMobileUrl('friend'), 'success');
}

public function doMobileDelsms(){//删除一条消息
	global $_W,$_GPC;
	$id = intval($_GPC['id']);
	pdo_delete('yobydashan_sms', array('id' => $id));
	message('删除消息成功！', $this->createMobileUrl('sms'), 'success');
}
 
 public function doMobileDelsendsms(){//删除一条消息
	global $_W,$_GPC;
	$id = intval($_GPC['id']);
	$data = array(
	'isread'=>1
	);
	pdo_update('yobydashan_sms', $data, array('id' => $id));
	message('删除消息成功！',$this->createMobileUrl('sendsms'), 'success');
}

public function doWebTb(){//同步用户信息
	global $_W,$_GPC;
	$weid = $_W['uniacid'];
	$list =pdo_fetchall("SELECT *  FROM ".tablename('mc_mapping_fans')." where uniacid=".$weid." and  follow=1");
	foreach($list as $lists){
	$tag = @unserialize(base64_decode($lists['tag']));
			$insert = array(
							'weid' => $weid,
							'fromuser' => $lists['openid'],
							'wid'=>$lists['fanid'],
							'sex'=>$tag['sex'],
							'yname'=>$tag['nickname'],
							'xi'=>$tag['province'],
							);
							$vo = $lists['openid'];
	if (pdo_fetch("SELECT * FROM ".tablename('yobydashan_user')." WHERE fromuser= '{$vo}'")) {
							
							pdo_update('yobydashan_user', $insert, array('fromuser' => $vo));
						}else{
							pdo_insert('yobydashan_user',$insert);
						}
	
	}
		message('同步信息成功！', referer(), 'success');
}

public function doWebQs(){//清空空用户
	global $_W,$_GPC;
	$weid = $_W['uniacid'];
	pdo_delete('yobydashan_user', array('yname' =>'','weid'=>$weid));
	message('清空成功！', referer(), 'success');
	}
	
	public function doWebQs1(){//清空空城市
	global $_W,$_GPC;
	$weid = $_W['uniacid'];
	pdo_delete('yobydashan_user', array('xi' =>'','weid'=>$weid));
	message('清空成功！', referer(), 'success');
	}

//主动文本回复消息，48小时之内
public function post_send_text($openid,$content) {
		load()->classs('weixin.account');
			$token =WeiXinAccount::fetch_available_token();//全局票据
load()->func('communication');
			
			
			$data['touser'] =$openid;
			$data['msgtype'] = 'text';
			$data['text']['content'] = urlencode($content);
			$dat = json_encode($data);
			$dat = urldecode($dat);
		
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
			
			ihttp_post($url, $dat);
			/*$dat = $content['content'];
			$result = @json_decode($dat, true);
			if ($result['errcode'] == '0') {
				message('发送消息成功！', referer(), 'success');
			}*/
			
}
	
}
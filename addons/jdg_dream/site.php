<?php
/**
 * 为梦想干杯模块微站定义
 *
 * @author GaoLi
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Jdg_dreamModuleSite extends WeModuleSite {
	public $tablename='dream_reply';
	public function doWebManage() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
	
		
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :weid AND `module` = :module";
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':module'] = 'jdg_dream';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);
		
        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $scratch = pdo_fetch("SELECT * FROM " . tablename('dream_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['dreamnum'] = $scratch['dreamnum'];
                $item['viewnum'] = $scratch['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $scratch['starttime']);
                $endtime = $scratch['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($scratch['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-default ">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($scratch['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $scratch['isshow'];
            }
        }
		include $this->template('manage');
	}
	//许愿名单
	public function doWebWishlist(){
		global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		
	
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid, ':weid' => $_W['uniacid']);
		 if (isset($_GPC['status'])) {
            $where.=' and status=:status';
            $params[':status'] = $_GPC['status'];
        }
        if (!empty($_GPC['keywords'])) {
            $where.='AND `dream` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keywords']}%";   
		}
        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('dream_wish') . " WHERE rid = :rid and weid=:weid " . $where . "", $params);
		
        $pindex = max(1, intval($_GPC['page']));
        $psize = 5;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT a.* FROM " . tablename('dream_wish') . " a WHERE a.rid = :rid and a.weid=:weid  " . $where . " ORDER BY a.id DESC " . $limit, $params);

        //一些参数的显示
        $reply= pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid", array(':rid' => $rid));
       	$viewnum = $reply['viewnum'];
        $dreamnum = $reply['dreamnum'];
		
		include $this->template('wishlist');
	}
	//管理页面开始/结束
	public function doWebSetshow (){
		
		 global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

		
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('dream_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', referer(), 'success');
		
	}
	public function doMobileGetArr(){
	
		  $sql  ="select * from ".tablename("dream_wish")."limit 8";
		  $data=  pdo_fetchall($sql);
	      $ndata=  array();
		  foreach($data as $key=>$row)
		  {
			  $ndata[$key]['id']  = "";
			  $ndata[$key]['uuid']  = $row['id'];
			  $ndata[$key]['message'] = $row['dream'];
			  $ndata[$key]['fromUser']  = $row['drea_mname'];
			  $ndata[$key]['toUser']=$row['to_name'];
			  $ndata[$key]['channelID'] =  "Admin";
			  $ndata[$key]['modifyTime'] = $row['createtime'];
			  $ndata[$key]['createTime'] =  $row['createtime'];
			  $ndata[$key]['highLight'] =true;
		  }
		  
		   $arr=   json_encode($ndata);
		   $callback=$_GET['callback'];  
			echo $callback."($arr)"; 
		
		   
	}
	//管理页面的删除
	public function doWebDelete (){
		 global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
        
		
		if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
		}
		 message('规则操作成功！', referer(), 'success');
	}
	//许愿名单页面的取消/确认
	public function doWebSetstatus(){
		global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $status = intval($_GPC['status']);
        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $p = array('status' => $status);
        
        $temp = pdo_update('dream_wish', $p, array('id' => $id, 'weid' => $_W['uniacid']));
        if ($temp == false) {
            message('抱歉，刚才操作数据失败！', '', 'error');
        } else {
            message('状态设置成功！', $this->createWebUrl('wishlist', array('rid' => $_GPC['rid'])), 'success');
        }
    
		
	} 
	
	public function doMobileIndex() {
		//这个操作被定义用来呈现 许愿手机首页
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		if(empty($id)){
			message('抱歉，参数错误！', '', 'error');
		}
		$reply = pdo_fetch("SElECT * FROM".tablename($this->tablename)."WHERE rid=:rid LIMIT 1",array(':rid'=>$id));
		if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
		
		$signPackage = $this->getSignPackage();
		
		include $this->template('index');
		
	}
	public function doMobileMobile() {
		//这个操作被定义用来呈现 许愿手机首页
		global $_W,$_GPC;
		$openid= $_W['openid'];
		$id=intval($_GPC['id']);
		if(!empty($id)){
			$reply = pdo_fetch("SElECT * FROM".tablename($this->tablename)."WHERE rid=:rid LIMIT 1",array(':rid'=>$id));
			//增加浏览人数
			pdo_update($this->tablename, array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
			
		}
	
		if (!empty($openid)) {
			$member = mc_fansinfo($openid);
			$follow = intval($member['follow']);
			
			 if ($follow ==1) {
				
				include $this->template('mobile');
			} else{
				echo "<script> alert('请先关注我们的官方微信！');parent.location.href='{$reply['gzurl']}'; </script>";
			}  
		}
		 $signPackage = $this->getSignPackage();
		
	}
	public function doMobileWish() {
		//这个操作被定义用来呈现 许愿手机首页
		 
	    
		 global $_GPC,$_W;
	     
		  $from_user= $_W['openid'];
		  $rid    =  intval($_GPC['rid']);
		 
		  $weid    =  $_W['weid'];
		  $dream  =  $_GPC['message'];
		  $from   =  $_GPC['fromUser'];
		  $to     =  $_GPC['toUser'];
		  $insert =  array(
		    
		     'from_user'=>$from_user,
			  'weid'          =>  $weid,
			  'rid'          =>  $rid,
			  'drea_mname'   =>  $from,
			  'to_name'      =>  $to,
			  'dream'        =>  $dream,
			  'createtime'   =>  time(),
			  'status'       =>  1
			);
		$flag =   pdo_insert('dream_wish',$insert);
		$reply = pdo_fetch("SElECT * FROM".tablename($this->tablename)."WHERE rid=:rid LIMIT 1",array(':rid'=>$rid));
		//增加许愿人数
		$flag =   pdo_update("dream_reply", array('dreamnum' => $reply['dreamnum'] + 1), array('id' => $reply['id']));
		}
	 //删除所选择的
	 public function doWebDeleteAll() {
        global $_GPC, $_W;

        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($rule['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        $this->webmessage('规则操作成功！', '', 0);
    }	
	
 //以下都是分享的
 public function getSignPackage() {
	 	global $_GPC,$_W;
	 		
	 	 $appid = $_W['account']['key'];
		 $secret = $_W['account']['secret'];
		 $serverapp = $_W['account']['level'];	//是否为高级号
		
	
    $jsapiTicket = $this->getJsApiTicket();
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $appid,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {

    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        //$fp = fopen("jsapi_ticket.json", "w");
       // fwrite($fp, json_encode($data));
        //fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  private function getAccessToken() {
  		global $_GPC,$_W;
	 		 $appid = $_W['account']['key'];
		
		 $secret = $_W['account']['secret'];
		 $serverapp = $_W['account']['level'];	//是否为高级号
	
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        //$fp = fopen("access_token.json", "w");
        //fwrite($fp, json_encode($data));
        //fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
}
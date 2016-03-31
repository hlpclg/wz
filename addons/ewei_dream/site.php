<?php

/**
 * 梦想契约
 * @author 狸小狐 QQ:22185157
 */
defined('IN_IA') or exit('Access Denied');
class Ewei_dreamModuleSite extends WeModuleSite {

    public function doWebManage() {
        global $_GPC, $_W;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $params = array();

        $params[':weid'] = $_W['uniacid'];
        $condition = '';
        if (isset($_GPC['keywords'])) {
            $condition .= ' AND `title` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_dream_reply') . " WHERE uniacid = '{$_W['uniacid']}'  $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        foreach ($list as &$row) {
            $row['fans'] = pdo_fetchcolumn("select count(*) from " . tablename('ewei_dream_fans') . " where rid=:rid ", array(":rid" => $row['rid']));
        }
        unset($row);

        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_dream_reply') . " WHERE uniacid = '{$_W['uniacid']}' $condition");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('manage');
    }

    public function doWebDelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
         $this->del($rid);
        message('规则操作成功！', $this->createWebUrl('manage'), 'success');
    }

    public function doWebDeleteAll() {
        global $_GPC, $_W;

        foreach ($_GPC['idArr'] as $k => $id) {
        
            $rid = intval($id);
            if ($rid == 0)
                continue;
            $this->del($rid);  
        }
        die('');
    }
    private function del($rid){
          pdo_delete('rule', array('id' => $rid));
             pdo_delete('rule_keyword', array('rid' => $rid));
             pdo_delete('stat_rule', array('rid' => $rid));
             pdo_delete('stat_keyword', array('rid' => $rid));
             pdo_delete("ewei_dream_reply", array("rid" => $rid));
             pdo_delete("ewei_dream_fans", array("rid" => $rid));
    }
       public function doWebDeleteFans() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $id = intval($_GPC['id']);
        pdo_delete("ewei_dream_fans", array("id" => $id));
        message('规则操作成功！', $this->createWebUrl('fans',array('rid'=>$rid)), 'success');
    }

    public function doWebFans() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid);

        if (!empty($_GPC['keywords'])) {
            $where.=' and nickname like :nickname';
            $params[':nickname'] = "%{$_GPC['keywords']}%";
        }
        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ewei_dream_fans') . " WHERE rid = :rid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_dream_fans') . " WHERE rid = :rid " . $where . " ORDER BY id DESC " . $limit, $params);

        include $this->template('fans');
    }
 
    public function doMobileDream() {

        global $_W, $_GPC;
        @session_start();
      
        
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select * from " . tablename('ewei_dream_reply') . " where rid=:rid limit 1", array(":rid" => $rid));
        if (empty($reply)) {
            message('没有找到梦想契约的相关信息!');
        }
        if ($_W['ispost']) {
            $dr = array(
                "uniacid" => $_W['uniacid'],
                "openid"=>$_W['openid'],
                "rid" => $rid,
                "nickname" => $_GPC['nickname'],
                "dream" => $_GPC['dream'],
                "punishment" => $_GPC['punishment'],
                "createtime" => time()
            );
           pdo_insert("ewei_dream_fans", $dr);
           $dr['fansid'] = pdo_insertid();
           $info = base64_encode( json_encode($dr));
           $sessionid = "ewei_money_20150331_".$_W['uniacid'];
           session_set_cookie_params( 24 * 60 * 60 * 365 );//设置cookie的有效期
           session_cache_expire(24 * 60 * 60 * 365);//设置session的有效期
           $_SESSION[$sessionid] = $info;
           $arr = array("rid"=>$rid,"info"=>$info);
           header("location: " . $this->createMobileUrl("dream", $arr));
           exit();
        }
        //是否关注
        $follow = false;
        $openid = $_W['openid'];
        if(!empty($openid)){
            $f = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $openid));
            $follow = $f['follow']==1;
         }
         
         $sessionid = "ewei_money_20150331_".$_W['uniacid'];
         if($_GPC['reset']==1) {
              session_set_cookie_params( 24 * 60 * 60 * 365 );//设置cookie的有效期
              session_cache_expire(24 * 60 * 60 * 365);//设置session的有效期
              $_SESSION[$sessionid] = null;
        }
         $session = json_decode(base64_decode($_SESSION[$sessionid]), true);
         if(!is_array($session) && empty($_GPC['info'])){
                $default_dreams = explode(" ", $reply['dreams']);
                $default_punishments = explode(" ", $reply['punishments']);
                //获取6个随机梦想
                $ds = pdo_fetchall("select distinct dream from " . tablename("ewei_dream_fans") . " order by rand() limit 6");
                $countds = count($ds);
                for ($i = 0; $i < 6 - $countds; $i++) {
                    $rnd = rand(0, count($default_dreams));
                    $ds[] = array("dream" => $default_dreams[$rnd]);
                }
                //获取6个随机惩罚
                $ps = pdo_fetchall("select distinct punishment from " . tablename("ewei_dream_fans") . " order by rand() limit 6");
                $countps = count($ps);
                for ($i = 0; $i < 6 - $countps; $i++) {
                    $rnd = rand(0, count($default_punishments));
                    $ps[] = array("punishment" => $default_punishments[$rnd]);
                }

         }
         $hasfans = false;
        if(empty($_GPC['info'])){
     
               //分享信息
                $share = array(
                    "title" => $reply['title'],
                    "link" => $_W['siteroot'] . "app/" . $this->createMobileUrl("dream", array("rid" => $rid)),
                    "imgUrl" =>empty($reply['thumb'])?($_W['siteroot']."addons/ewei_dream/icon.jpg"):$_W['attachurl'] . $reply['thumb'],
                    "desc" =>$reply['description']
                );
                
                if(is_array($session) && !empty($session['fansid'])){
                    $fans = pdo_fetch("select * from " . tablename('ewei_dream_fans') . " where id=:id limit 1", array(":id" => $session['fansid']));
                    $oversees = pdo_fetchall("select * from ".tablename('ewei_dream_oversee')." where rid=:rid and fansid=:fansid order by createtime desc",array(":rid"=>$rid,':fansid'=>$session['fansid']));
                    $hasfans = true;
                    //分享信息
                    $share = array(
                        "title" =>"我".$fans['nickname']." , ".$reply['diy_title1'].$fans['dream'].", ".$reply['diy_title3'].$fans['punishment']."!",
                        "link" => $_W['siteroot'] . "app/" . $this->createMobileUrl("dream", array("rid"=>$rid,"isshare"=>1,"info"=>$_SESSION[$sessionid])),
                        "imgUrl" =>empty($reply['thumb'])?($_W['siteroot']."addons/ewei_dream/icon.jpg"):$_W['attachurl'] . $reply['thumb'],
                        "desc" =>"我".$fans['nickname']." , ".$reply['diy_title1'].$fans['dream'].", ".$reply['diy_title3'].$fans['punishment']."!",
                    );
                   
                }
             
                //浏览次数
                pdo_update("ewei_dream_reply", array("views" => $reply['views'] + 1), array("rid" => $rid));
        }
        else{
            $info =  json_decode( base64_decode($_GPC['info']),true );
      
            $fans = pdo_fetch("select * from " . tablename('ewei_dream_fans') . " where id=:id limit 1", array(":id" => $info['fansid']));
            $oversees = pdo_fetchall("select * from ".tablename('ewei_dream_oversee')." where rid=:rid and fansid=:fansid order by createtime desc",array(":rid"=>$rid,':fansid'=>$fans['id']));
            if(!empty($fans)){
                $hasfans = true;
            }
             //分享信息
            $share = array(
                "title" =>"我".$fans['nickname']." , ".$reply['diy_title1'].$fans['dream'].", ".$reply['diy_title3'].$fans['punishment']."!",
                "link" => $_W['siteroot'] . "app/" . $this->createMobileUrl("dream", array("rid"=>$rid,"isshare"=>1,"info"=>$_GPC['info'])),
                "imgUrl" =>empty($reply['thumb'])?($_W['siteroot']."addons/ewei_dream/icon.jpg"):$_W['attachurl'] . $reply['thumb'],
                "desc" =>"我".$fans['nickname']." , ".$reply['diy_title1'].$fans['dream'].", ".$reply['diy_title3'].$fans['punishment']."!",
            );
               //浏览次数
           pdo_update("ewei_dream_fans", array("views" => $fans['views'] + 1), array("id" => $fans['id']));
         
        }
   
        include $this->template('dream');
    }
     public function doMobileOversee(){
        global $_W,$_GPC;
           
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select * from " . tablename('ewei_dream_reply') . " where rid=:rid limit 1", array(":rid" => $rid));
        if (empty($reply)) {
            exit(json_encode(array("err"=>1,"msg"=>"未找到梦想契约!")));
        }
        $fansid = intval($_GPC['fansid']);
        if (empty($fansid)) {
            exit(json_encode(array("err"=>1,"msg"=>"未找到梦想契约!")));
        }
        
        $fans = pdo_fetch("select * from " . tablename('ewei_dream_fans') . " where id=:id limit 1", array(":id" => $fansid));
        if (empty($fans)) {
             exit(json_encode(array("err"=>1,"msg"=>"未找到梦想契约!")));
        }
        
         $o = array(
           "uniacid"=>$_W['uniacid'],
            "rid"=>$rid,
             "fansid"=>$fansid,
             "nickname"=>$_GPC['nickname'],
             "createtime"=>time()
            );
            pdo_insert("ewei_dream_oversee",$o);
        
        exit(json_encode(array("err"=>0,"msg"=>"哈哈, TA 已经被你监督了，一年之后见分晓!")));
         
    }
    
    public function doMobileShare(){
        global $_W,$_GPC;
           
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select * from " . tablename('ewei_dream_reply') . " where rid=:rid limit 1", array(":rid" => $rid));
        if (empty($reply)) {
            exit(json_encode(array("err"=>1,"msg"=>"未找到梦想契约!")));
        }
        
        pdo_update("ewei_dream_reply",array('shares'=>$reply['shares']+1),array("rid"=>$rid));
        exit(json_encode(array("err"=>0)));
         
    }


}

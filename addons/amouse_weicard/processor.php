
<?php
/**
 * 模块处理程序
 */
defined('IN_IA') or exit('Access Denied');
class Amouse_weicardModuleProcessor extends WeModuleProcessor {
    public $name = 'Amouse_weicardModuleProcessor';
    public function respond() {
        global $_W;
        $rid = $this->rule;
        $weid = $_W['uniacid'];
        $from_user = $this->message['from'];
        $sql="SELECT title,description,thumb,status FROM ".tablename('amouse_weicard2_reply')." WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        if ($row == false) {
            return $this->respText("活动已取消...");
        }
        if ($row['status'] ==0) {
            return $this->respText("微名片已禁用...");
        }

        $fans = pdo_fetch("SELECT * FROM ".tablename('amouse_weicard2_fans')." WHERE `openid`=:openid AND weid=:weid AND `rid`=:rid LIMIT 1",array(':openid'=>$from_user,':weid'=>$weid,':rid' => $rid));
        $news = array();
        if ($fans != false) {
            $row2 = array(
               'title'=> '我的微名片',
               'description' =>'我的微名片',
               'picurl' =>tomedia($row['thumb']),
               'url'=>$this->createMobileUrl('detail', array('cardid'=>$fans[id],'rid'=>$rid,'wxid'=>$from_user),true),
            );
            $row3=array(
                'title'=> '管理微名片',
                'description' =>'管理微名片',
                'picurl' =>tomedia($row['thumb']),
                'url'=>$this->createMobileUrl('index', array('id'=>$rid,'openid'=>$from_user),true),
            );

            $news[] = $row2;
            $news[] = $row3;
            return $this->respNews($news);
        } else {
            return $this->respNews(array(
                'Title' => $row['title'],
                'Description' => $row['description'],
                'PicUrl' => tomedia($row['thumb']),
                'Url' => $this->createMobileUrl('index', array('id' => $rid,'openid'=>$from_user),true),
            ));
        }

    }

}
?>
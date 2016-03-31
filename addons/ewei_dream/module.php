<?php

/**
 * 梦想契约
 * @author 狸小狐 QQ:22185157
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_dreamModule extends WeModule {
  
  public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_dream_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }
 

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        
        $insert = array(
            'rid' => $rid,
            'uniacid' =>$_W['uniacid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'copyright' => $_GPC['copyright'],
            
            'follow_need'=>intval($_GPC['follow_need']),
            'follow_url'=>$_GPC['follow_url'],
            
            'dreams' => $_GPC['dreams'],
            'punishments' => $_GPC['punishments'],
            
            'diy_bgcolor' => $_GPC['diy_bgcolor'],
            'diy_fontcolor' => $_GPC['diy_fontcolor'],
            'diy_topimg' => $_GPC['diy_topimg'],
            'diy_btncolor' => $_GPC['diy_btncolor'],
            'diy_btnfontcolor' => $_GPC['diy_btnfontcolor'],
            'diy_btntext' => $_GPC['diy_btntext'],
//            'diy_nickname' => intval( $_GPC['diy_nickname'] ),
            'diy_title1' => $_GPC['diy_title1'],
            'diy_title2' => $_GPC['diy_title2'],
            'diy_title3' => $_GPC['diy_title3'],
            'diy_title4' => $_GPC['diy_title4'],
            'diy_title5' => $_GPC['diy_title5'],
            'diy_audio' => $_GPC['diy_audio'],
            'diy_topimgshare' => $_GPC['diy_topimgshare'],
            'diy_paperimg' => $_GPC['diy_paperimg'],
            
            'diy_inputcolor' => $_GPC['diy_inputcolor'],
            'diy_inputtextcolor' => $_GPC['diy_inputtextcolor'],
        
        );
        if (empty($id)) {
            $insert['createtime'] = time();
            $id = pdo_insert('ewei_dream_reply', $insert);
        } else {
            pdo_update('ewei_dream_reply', $insert, array('id' => $id));
            
        }
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete("ewei_dream_reply", array("rid" => $rid));
        pdo_delete("ewei_dream_fans",  array("rid" => $rid));
    }
}

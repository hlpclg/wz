<?php
/**
 * 为梦想干杯模块定义
 *
 * @author GaoLi
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Jdg_dreamModule extends WeModule {
	public $tablename = 'dream_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
       
		 
        load()->func('tpl');
         if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
        if (!$reply) {
            $now = time();
            $reply = array(
              "title"=>"为梦想举杯!",
			  "picurl"=>"",
			  "starttime"=>$now,
		      "endtime"=>strtotime(date("Y-m-d H:i",$now + 7*24*3600)),
			  "share_title"=>"为梦想举杯",
			  "share_content"=>"为梦想迈出的每一步,都值得庆祝。而中国农历新年,是我们放飞梦想,为梦想举杯的最佳时刻。",
			); 
 	    }

		 include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
		
        $insert = array(
            'rid' => $rid,
            'weid' => $_W['weid'],
            'title'=>$_GPC['title'],
			'picurl'=>$_GPC['picurl'],
			'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
			'share_title'=>$_GPC['share_title'],
			'share_content'=>$_GPC['share_content'],
			'logo'=>$_GPC['logo'],
			'gzurl'=>$_GPC['gzurl'],
			'slogans'=>$_GPC['slogans'],
			  
        );
        
         if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['isshow'] = 1;
            } else {
                $insert['isshow'] = 0;
            }
            $id = pdo_insert($this->tablename, $insert);
        } else {
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        return true;
    }

		
		
		
	

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		if (pdo_tableexists('dream_wish')) {
			pdo_delete('dream_wish', array('rid' => $rid));
		}
		if (pdo_tableexists('dream_reply')) {
        	pdo_delete('dream_reply', array('rid' => $rid));
    	}
    	
     
	}


}
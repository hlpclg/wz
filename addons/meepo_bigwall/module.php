<?php

/**

 * 微信墙模块

 *

 * [WeEngine System] Copyright (c) 2013 012wz.com

 */

defined('IN_IA') or exit('Access Denied');



class meepo_bigwallModule extends WeModule {

	public $tablename = 'weixin_wall_reply';



	/**

	 * 规则表单附加额外字段

	 */

	public function fieldsFormDisplay($rid = 0) {

		global $_W;

		

		//$accounts = uni_accounts();

		if (!empty($rid)) {

			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));

			

		} else {

			$reply = array(

				'isshow' => 0,
                'lurumobile' =>0,
				'timeout' =>10000,
                'subit_tips'=> '欢迎关注，您已经录入基本信息！直接回复内容即可上墙！',
				'enter_tips' => '您已经录入基本信息！直接回复任意内容即可上墙！',
			    'quit_tips' => '您已经退出了微信墙，再次回复即可进入微信墙！',
                'send_tips' => '上墙成功，再次回复任意内容或图片即可再次上墙哦！',
				'chaoshi_tips' => '由于你长时间未参与本次活动，已被系统踢出，请重新进入！',
				'quit_command' => '退出',
				'defaultshow'=>2,
				);
		 
			if(empty($reply['votetitle'])){

			     $reply['votetitle']=  '欢迎进入微信大屏幕投票活动';

			}

			if(empty($reply['votepower'])){

			     $reply['votepower']=  'www.zheyitianShi.Com开发团队';

			}

			if(empty($reply['refershtime'])){

			     $reply['refreshtime']= 3;

			}

			if(empty($reply['yyyendtime'])){

			     $reply['yyyendtime']= 300;

			}

			if(empty($reply['yyyshowperson'])){

			     $reply['yyyshowperson']=10;

			}

			if(empty($reply['yyyzhuti'])){

			     $reply['yyyzhuti']=  '摇一摇中大奖';

			}

			

			if(empty($reply['voterefreshtime'])){

			     $reply['voterefreshtime']=5;

			}
		    if(!$reply['yyyshow']){

			     $reply['yyyshow']=0;

			}
		 if(!$reply['cjshow']){

			     $reply['cjshow']=0;

			}
		 if(!$reply['ddpshow']){

			     $reply['ddpshow']=0;

			}
		 if(!$reply['tpshow']){

			     $reply['tpshow']=0;

			}
		if(!$reply['qdqshow']){

			     $reply['qdqshow']=0;

			}
         
		if(empty($reply['loginpass'])){

			     $reply['loginpass']='admin';

			}
			//新增
      
			if(empty($reply['cjnum_tag'])){

			     $reply['cjnum_tag']=  1;

			}
			if(empty($reply['cjnum_exclude'])){

			     $reply['cjnum_exclude']=  1;

			}
			if(empty($reply['cjname'])){

			     $reply['cjname']=  '米波抽奖';

			}

		}

		$sty_name=array();//name数组，
			$sty_name['defaultV1.0.css']="默认风格";
			$sty_name['LanternFestival_1.css']="元宵节";
			$sty_name['SpringFestival_1.css']="春节1";
			$sty_name['SpringFestival_2.css']="春节2";
			$sty_name['SpringFestival_3.css']="春节3";
			$sty_name['Valentine_1.css']="情人节1";
			$sty_name['Valentine_2.css']="情人节2";
			$sty_name['Valentine_3.css']="情人节3";
			$sty_name['colorRed_1.css']="红色";
			$sty_name['colorBluishViolet_1.css']="蓝色";
			$sty_name['colorClaret_1.css']="紫红色";
			$sty_name['Christmas_1.css']="圣诞节";
			$sty_name['christmasblue.css']="圣诞节2";
			$sty_name['christmasred.css']="圣诞节3";
			$sty_name['loveheart1.css']="罗曼蒂克1";
			$sty_name['loveheart2.css']="罗曼蒂克2";

		load()->func('tpl');

		include $this->template('form');

	}



	/**

	 * 保存规则前调用, 验证附加字段有效性

	 */

	public function fieldsFormValidate($rid = 0) {
		return true;
	}
	/**

	 * 规则保存成功后执行此方法,保存附加字段入库

	 */

	public function fieldsFormSubmit($rid = 0) {
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
            'weid'=>$_W['uniacid'],
			'subit_tips'=> $_GPC['subit_tips'],
			'enter_tips' => $_GPC['enter-tips'],
			'quit_tips' => $_GPC['quit-tips'],
			'send_tips' => $_GPC['send-tips'],
            'chaoshi_tips' => $_GPC['chaoshi-tips'],
			'timeout' => $_GPC['timeout'],
			'isshow' => intval($_GPC['isshow']),
			'quit_command' => $_GPC['quit-command'],           
			'lurumobile' => intval($_GPC['lurumobile']),
		);
			$insert['votetitle'] = $_GPC['votetitle'];
			$insert['votepower'] = $_GPC['votepower'];
			$insert['refreshtime'] = intval($_GPC['refreshtime']);
			$insert['yyyendtime'] = intval($_GPC['yyyendtime']);
			$insert['yyyshowperson'] = intval($_GPC['yyyshowperson']);
			$insert['yyyzhuti'] = $_GPC['yyyzhuti'];
			$insert['voterefreshtime'] = intval($_GPC['voterefreshtime']);
			$insert['qdqshow'] = intval($_GPC['qdqshow']);
			$insert['yyyshow'] = intval($_GPC['yyyshow']);
			$insert['ddpshow'] = intval($_GPC['ddpshow']);
			$insert['tpshow'] = intval($_GPC['tpshow']);
			$insert['cjshow'] = intval($_GPC['cjshow']);
			$insert['loginpass'] = $_GPC['loginpass'];
			$insert['indexstyle'] = $_GPC['indexstyle'];
			$insert['cjnum_tag'] = intval($_GPC['cjnum_tag']);
			$insert['cjnum_exclude'] = intval($_GPC['cjnum_exclude']);
			$insert['cjtag_exclude'] = intval($_GPC['cjnum_exclude']);
			$insert['cjname'] = $_GPC['cjname'];   
			$insert['cjimgurl'] = $_GPC['cjimgurl'];
			$insert['defaultshow'] = intval($_GPC['defaultshow']);
		if (empty($id)) {

			pdo_insert($this->tablename, $insert);

		} else {

			pdo_update($this->tablename, $insert, array('id' => $id));

		}

	}
	/**

	 * 卸载模块时执行的附加数据库清理操作

	 */

	public function ruleDeleted($rid = 0) {
	}

	//配置参数设置

	 public function settingsDisplay($settings) {
		global $_GPC, $_W;
		if(checksubmit()) {
			$cfg = array();
			$cfg['isshow'] = intval($_GPC['isshow']);
			$cfg['erweima'] = $_GPC['erweima'];	
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}	
        if(empty($settings)){
		   $settings['isshow'] = 1;
		   $settings['erweima'] = '../addons/meepo_bigwall/qr.jpg';
		}	    
		load()->func('tpl');
		include $this->template('setting');

	}


}


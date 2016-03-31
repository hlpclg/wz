<?php
/**
 * Lastmile模块处理程序
 *
 * @author scnace
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Scnace_lmModuleProcessor extends WeModuleProcessor {

	/**
	 * @Tips:
	 *         Status Table Of flags:
	 *          Session-Values            Function
	 *              0                       None
	 *              1                       SwitchExpress
	 *              2                       WorkerInterface
	 *              3                       ExpressContainer
	 *              4                       OwnerInterface
	 *              5                       Worker Pick The Express
	 *              6                       My Express
	 *              7                       Reward  Mode
	 * @return Ambigous <multitype:unknown string , multitype:string mixed NULL >
	 */
	
	private $openid;
	private $commonid;
	
	public function respond() {
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
	
		if(!$this->inContext){
			$out="您已进入最后一公里模块,\n”用户须知“来查看此模块的基本流程与操作方法，\n“我有快递”来召唤快递员,\n“帮拿快递”来查看现有快递,\n”我的快递“来查看你快递的动态,\n”退出“来退出此模块\n(用完我记得退出哦!)
				";
			$this->beginContext(1800);
		}
		else{
			//上下文判断
			$input=$this->message['content'];
			$openid=$this->message['from'];
			$this->openid=$openid;
			if($input){
				$a=$this->ExpressPreg($input);
				if($_SESSION['flag']=='1'&&$input==$a[0]){
	
					if($this->SwitchExpress($input,$openid)){
						$out="输入你的楼层号-寝室号+手机短号+你的名字\n例如:B01-101+110110+阿啊三
		   	      				";
						$_SESSION['flag']=4;
					}
					else{
						$out="可能出了点故障,能再输入一遍嘛?\n例如:B01-101+110110+阿啊三
		   	  					";
					}
				}
				//开始正则匹配input  owner用户信息
				else if($_SESSION['flag']=='4'&&$input==$this->OwnerInfoPreg($input)){
					 
					if($this->SaveOwnerInfo($input)){
						$out="您的快递请求已发出,等有缘人帮你领取吧~~\n 这是你的专属单号:".$this->commonid."\n".'('.'小E提醒：此单号可以用于快速查询自己的快递哦!'.')';
					}
					else{
						$out='可能出了点故障,能再尝试一遍嘛?\n仔细看用户须知哦！';
					}
				}
				//开始正则匹配  worker用户信息
				else if($_SESSION['flag']=='2'&&$input==$this->WorkerInfoPreg($input)){
	
					if($this->SaveWorkerInfo($input)){
						$out='信息确认完毕,请回复"快递盒子"来查看当前盒子中的快递.';
						$_SESSION['flag']=3;
						$_SESSION['workerid']='';
					}
					else{
						$out='可能出了点故障,能再输入一遍嘛?';
					}
				}
				else if($_SESSION['flag']=='3'&&$input='快递盒子'){
	
					//数据集合
					$all=array();
	
					//TODO select DB
					$boxinfo=$this->ShowBoxInfo();
					if($boxinfo){
						//解析数组
						foreach ($boxinfo as $key=>$value){
							$id=$value['commonid'];
							$expressname=$value['expressname'];
							$addressap=$value['addressap'];
							$addressrm=$value['addressrm'];
							$phone=$value['phone'];
							$name=$value['ownername'];
							//规范格式
							$output='['.$id.']'.$expressname.'   '.$addressap.'-'.$addressrm.'   '.$phone.'  '.$name;
							array_push($all, $output);
						}
						//格式化数组为字符串
						$allinfo=implode(';', $all);
						$out="---你好，这里是快递盒子---\n新鲜的快递信息(要拿哪个呢?例如拿0号,请回复00):
		   	   	 		".$allinfo
			   	   	 		;
			   	   	 		$_SESSION['flag']=5;
					}
					else{
						$out='箱子现在空空如也~~~~';
						//置0   整个过程结束
						$_SESSION['flag']=0;
					}
				}
			  
			  
			 else if($_SESSION['flag']=='5'&&$input==$this->WorkerChooseExpressPreg($input)){
			 	$worker=$this->ShowOwnerExpressStatus($input);
			 	if($this->SaveWorkerintoBox($input)&&$worker['visible']){
			 		$ownername=$this->ShowOwnerNameToWorker($input);
			 		$w=$this->SaveWorkerintoBox($input);
			 		$out="你已成功拿取  '.$ownername.' 的快递,尽快送到他/她手中哦!\n没准,嘿嘿嘿。。。。。。".$w['workerid'];
			 		$_SESSION['flag']=0;
			 	}
			 	else{
			 		$out='换一个吧，人家不要你。。。';
			 		$_SESSION['flag']=5;
			 	}
	
			 }
			 else if($_SESSION['flag']=='6'&&$input==$this->OwnerExpressPreg($input)){
			 	//检查用户权限
			  if($this->CheckAuth($input)){
			  	$worker=$this->ShowOwnerExpressStatus($input);
			  	if($worker['visible']==0&&$worker['check']==0){
			  		$out="--以下是你的快递信息--\n 你的配送员是:".$worker['workername']."\n 配送员电话是:".$worker['workerphone']. "该配送员成功送货次数为".$worker['solved']. "\n 不要急啦   ".$worker['status']."\n 如果送到了请回复下收到哦";
			  		//SESSION 置7
			  		$_SESSION['flag']=7;
			  	}
			  	else if($worker['check']){
			  		$out="没有更多快递了哦!";
			  	}
			  	else{
			  		$out="--以下是你的快递信息--\n 你的快递状态是:".$worker['status'];
			  		$_SESSION['flag']=0;
			  	}
			  }
			  else{
			  	$out="别人的快递不让看哦~~~~\n特殊情况联系管理员 Wechat ID:scbizu";
			  }
			 }
			 else if($_SESSION['flag']=='7'&&$input=='收到'){
			 	$t=$this->RewardTheWorker();
			 	if($t){
			  	$out='谢谢您的支持,小E已经替你感谢了那位为你奔波的人,当然你也可以选择自己感谢啦~~';
			 	}
			 	else{
			  	$out='你已经收过啦......';
			 	}
			 	$_SESSION['flag']=0;
			 }
			 else{
			 	switch ($input){
			 		case '用户须知':
			 			$out='http://mp.weixin.qq.com/s?__biz=MzAxNTA0MTU2OQ==&mid=205528953&idx=1&sn=2ba2f85324b93ae53306d2d9eb42487d#rd';
			 			break;
			 		case '我有快递':
			 			//		$out='---领快递的解决方案-----------';
	
			 			//进入信息填写模块
			 			$out="请选择您的快递地点,回复编号:\n00.C区申通\n01.C区韵达\n02.C区圆通\n03.C区中通\n04.C区阿华电脑店\n05.C区印象工作室\n06.图书馆\n07.B区楼下(取款机旁)\n08.教一邮政
		   	  			    		";
			 			//TODO 改变flag  置1
			 			$_SESSION['flag']=1;
			 			break;
			 		case '帮拿快递':
			 			if($this->CheckWorker()){
			 				$out='真开心你又来帮忙了,要看看"快递盒子"么?';	
			 				$_SESSION['flag']=3;
			 			}
			 			else{
			 				$out='输入你的手机短号+你的名字';
			 				//置flag为2
			 				$_SESSION['flag']=2;
			 			}
			 			break;
			 		case '我的快递':
			 				
			 			$out='输入小E和你之间的秘密单号~~~';
			 			$_SESSION['flag']=6;
			 			break;
			 		case '退出':
			 			$out='你已退出快递模块，再见!';
			 			$this->endContext();
			 			//session置零
			 			$_SESSION['flag']=0;
			 			break;
			 		default:
			 			$out='如果你不知道要干嘛,为什么不去问问"用户须知"呢!';
			 			break;
			 	}
			 }
			}
		}
		return $this->respText($out);
	}
	
	/**************以下为正则匹配函数************************/
	/**
	 *@todo  正则匹配Owner信息
	 * @param unknown $input
	 * @return unknown
	 */
	private function OwnerInfoPreg($input){
		//匹配规则 ：楼层号-寝室号+手机短号+你的名字
		$rule='/[^\s]{3}-\d{3}\+\d{6}\+[\x{4e00}-\x{9fa5}]{2,}/u';
		preg_match($rule, $input,$result);
		return $result[0];
	}
	
	/**
	 * @todo 正则匹配worker信息
	 * @param unknown $input
	 * @return unknown
	 */
	private function WorkerInfoPreg($input){
		$rule='/\d{6}\+[\x{4e00}-\x{9fa5}]{2,}/u';
		preg_match($rule, $input,$result);
		return $result[0];
	}
	/**
	 * @todo 正则匹配快递店编号
	 * @param unknown $input
	 * @return unknown
	 */
	private function ExpressPreg($input){
		$rule='/\d{2}/';
		preg_match($rule, $input,$result);
		return $result;
	}
	/**
	 * @todo 匹配Owner 输入的暗号
	 * @param unknown $input
	 * @return unknown
	 */
	private function OwnerExpressPreg($input){
		$rule='/\d{4}/';
		preg_match($rule, $input,$result);
		return $result[0];
	}
	/**
	 * @todo 匹配 PICK输入
	 * @param unknown $input
	 * @return unknown
	 */
	private function WorkerChooseExpressPreg($input){
		$rule='/\d{4}/';
		preg_match($rule, $input,$result);
		return $result[0];
	}
	
	
	/***************************以下为PDO数据库操作*************************/
	
	
	/**
	 * @todo 数据库操作  筛选快递
	 * @param unknown $input
	 * @param unknown $openid
	 * @return boolean
	 */
	private function SwitchExpress($input,$openid){

		$res=pdo_insert('owner',array('openid'=>$openid,'expressid'=>$input));
	
		if($res){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * @todo 数据库操作 Owner表
	 * @param unknown $input
	 * @return boolean
	 */
	private function SaveOwnerInfo($input){
		//正则开始
		//匹配名字
		$nrule='/[\x{4e00}-\x{9fa5}]{2,}/u';
		preg_match($nrule,$input,$nresult);
		$ownername=$nresult[0];
		//匹配公寓
		$arule='/[^\s]{3}/';
		preg_match($arule,$input,$aresult);
		$addressap=$aresult[0];
		//匹配寝室号
		$frule='/\d{3}/';
		preg_match($frule,$input,$fresult);
		$addressrm=$fresult[0];
		//匹配电话号码
		$prule='/\d{6}/';
		preg_match($prule,$input,$presult);
		$phone=$presult[0];
	
		$commonid=substr(time(),6,4);
		$this->commonid=$commonid;
		//开始更新数据库
		$res=pdo_update(
				'owner',
				array('commonid'=>$commonid,'addressap'=>$addressap,'ownername'=>$ownername,'addressrm'=>$addressrm,'phone'=>$phone),
				array('openid'=>$this->openid)
		);
		//更新操作后取出对应的commonid
		//	$commonidres=pdo_fetch("SELECT commonid FROM ".tablename('owner')." WHERE openid = :oid",array(':oid'=>$this->openid));
		//$commonid=$commonidres['commonid'];
	
		//同时把快递扔进箱子
		$t=pdo_insert('box',array('commonid'=>$commonid,'status'=>'你的快递正在箱子里睡觉呢~'));
	
		return $t&&$res;
	}
	
	/**
	 * @todo  数据库操作  Worker表
	 * @param unknown $info
	 * @return boolean
	 */
	private function  SaveWorkerInfo($input){
		//Worker的正则
		//匹配名字
		$nrule='/[\x{4e00}-\x{9fa5}]{2,}/u';
		preg_match($nrule,$input,$nresult);
		$workername=$nresult[0];
		//匹配电话
		$prule='/\d{6}/';
		preg_match($prule,$input,$presult);
		$phone=$presult[0];
		//数据库操纵
		$res=pdo_insert('worker',array(
				'workername'=>$workername,
				'phone'=>$phone,
				'openid'=>$this->openid
		)
		);
	
		if($res){
			return true;
		}
		else{
			return false;
		}
	}
	/**
	 * @todo 更新快递盒子
	 * @param unknown $input
	 * @return Ambigous <boolean, unknown>
	 */
	private function  SaveWorkerintoBox($input){
		//获取Workerid
		$worker=pdo_fetch("SELECT workerid FROM".tablename('worker')."WHERE openid=:oid",array(':oid'=>$this->openid));
		//更新盒子
		$res=pdo_update('box',array('workerid'=>$worker['workerid'],'status'=>'你的快递在飞奔到你的身边','visible'=>0),array('commonid'=>$input));
		return $res;
	}
	
	/**
	 * @todo  答谢Worker
	 * @return boolean
	 */
	private function  RewardTheWorker($num){
		//Openid标识用户信息
		$common=pdo_fetchall("SELECT * FROM".tablename('owner')."WHERE openid=:oid",array(':oid'=>$this->openid),'');
	
		foreach ($common as $key =>$value){
			$commonid=$value['commonid'];
			$box=pdo_fetch("SELECT * FROM".tablename('box')."WHERE commonid=:cid",array(':cid'=>$commonid));
	
			if(!$box['check']){
				 
				$solved=pdo_fetch("SELECT solved FROM".tablename('worker')."WHERE workerid=:wid",array(':wid'=>$box['workerid']));
				$solvednum=$solved['solved']+1;
				$t=pdo_update('worker',array('solved'=>$solvednum),array('workerid'=>$box['workerid']));
				 
				if($t){
					pdo_update('box',array('check'=>1,'status'=>'你的快递已经在你怀里了哦'),array('workerid'=>$box['workerid']));
				}
			}
	
			return true;
		}
	
	}
	/**
	 * @todo  显示箱子里的全部信息
	 * @return multitype:
	 */
	private function ShowBoxInfo(){
		//所有需求项
		$allinfo=array();
		$res=pdo_fetchall("SELECT commonid FROM ".tablename('box')."WHERE visible=:vi",array(':vi'=>'1'),'commonid');
		//遍历需求页
		$tmp=array();
		foreach ($res as $key=>$value){
			$wonderinfo=pdo_fetch("SELECT expressid,addressap,addressrm,phone,ownername FROM".tablename('owner')."WHERE commonid=:id",array(':id'=>$value['commonid']));
			$express=pdo_fetch("SELECT expressname FROM".tablename('express')."WHERE expressid=:eid",array(':eid'=>$wonderinfo['expressid']));
			//开始赋值
			$expressname=$express['expressname'];
			$addressap=$wonderinfo['addressap'];
			$addressrm=$wonderinfo['addressrm'];
			$ownername=$wonderinfo['ownername'];
			$phone=$wonderinfo['phone'];
			//开始重构数组
			$tmp=array(
					'commonid'=>$key,
					'expressname'=>$expressname,
					'addressap'=>$addressap,
					'addressrm'=>$addressrm,
					'ownername'=>$ownername,
					'phone'=>$phone
			);
			//压入总数组
			array_push($allinfo, $tmp);
		}
		return $allinfo;
	}
	
	/**
	 * @todo 通过暗号来显示Owner的快递状态
	 * @param unknown $input
	 * @return boolean
	 */
	private function ShowOwnerExpressStatus($input){
		//格式化输出数组
		$outarray=array();
		$res=pdo_fetch("SELECT * FROM".tablename('box')."WHERE commonid=:cid",array(':cid'=>$input));
		$status=$res['status'];
		$vis=$res['visible'];
		$check=$res['check'];
		//用wokerid 取worker表信息
		$worker=pdo_fetch("SELECT * FROM".tablename('worker')."WHERE workerid=:wid",array(':wid'=>$res['workerid']));
		//压入数组
		$outarray=array(
				'visible'=>$vis,
				'check'=>$check,
				'status'=>$status,
				'workername'=>$worker['workername'],
				'workerphone'=>$worker['phone'],
				'solved'=>$worker['solved']
		);
		return $outarray;
	}
	
	
	/**
	 * @todo 获取下ownername     嘘。。。。。。。
	 * @param unknown $input
	 * @return string
	 */
	private function ShowOwnerNameToWorker($input){
		//顺便返回下名字咯  如果促成一对呢 ,嘿嘿嘿.....
		$res=pdo_fetch("SELECT ownername FROM".tablename('owner')."WHERE commonid=:cid",array(':cid'=>$input));
		$ownername=$res['ownername'];
		return $ownername;
	}
	
	
	/**
	 * 检查Worker是否存在    以累计solve
	 * @return boolean
	 */
	private function CheckWorker(){
		$res=pdo_fetch("SELECT * FROM ".tablename('worker')."WHERE openid=:oid",array(':oid'=>$this->openid));
	
	
		return $res;
	}
	
	/**
	 * 权限检查
	 * @param unknown $input
	 * @return boolean
	 */
	private  function CheckAuth($input){
			
		$checkarr=pdo_fetch("SELECT openid FROM ".tablename('owner')."WHERE commonid=:cid",array(':cid'=>$input));
		$auth=$checkarr['openid'];
		if($this->openid==$auth){
			return true;
		}
		else{
			return false;
		}
	
	}
	 
	 	 	 
	}
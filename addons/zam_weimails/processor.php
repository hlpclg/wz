<?php
/**
 * zam微信邮件模块处理程序
 *
 * @author Meepo_zam
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Zam_weimailsModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$content = $this->message['content'];
		$openid = $this->message['from'];
		             $cfg = $this->module['config'];					 
                     $headtitle = $cfg['headtitle'];
                     $headlogo = $_W['attachurl'].$cfg['logo'];
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
		$b = array();
		$yx = $this->getyx($openid);
		$sql = "SELECT * FROM ".tablename('meepomailattachment')." WHERE isshow=:isshow ORDER BY id ASC, displayorder DESC";
					 $arrsql = array(':isshow'=>1);
					 $all = pdo_fetchall($sql,$arrsql);
					 $countall = count($all);
		session_start();
		
		if(!strncasecmp($_SESSION['sel'], "下载文件", 12)&& !strncasecmp($content, "S", 1)){
					  $lines = $_SESSION['pages']*8+8;
					  $_SESSION['pages'] = $_SESSION['pages']+1;

					  $b[] = array(
							   'title'=>$headtitle."下载请回复【#+数字】",
							   'description'=>'',
							   'picurl'=>$headlogo,
								'url'=>'',
							); 
					  for($id=$lines;$id<$lines+8;$id++){
						$name = $all[$id];
						if(empty($name)) break;
						$b[]=array('title'=>'【#'.$all[$id]['id'].'】 文件名称:'.$all[$id]['attachmentname']."\n相关描述：".$all[$id]['description'],	
							   'description'=>'',
							   'picurl'=>'',
								'url'=>'',);
					  }
					  if(count($b)>1) {
						  $b[]=array("title"=>'请回复 S 查看下一页',"url"=>'',"picurl"=>'',"description"=>'');
					  }else {
							session_destroy();
							 
							$b[] = array("title"=>'没有更多了哦！',"url"=>'',"picurl"=>'',"description"=>'');
					  }
						return $this->respNews($b); 
		 }else if($content == '下载文件'){
			         
					 
					  $_SESSION['sel'] = '下载文件';
					  $_SESSION['pages'] = 0;
					  $b[] = array(
							   'title'=>$headtitle."下载请回复【#+数字】",
							   'description'=>'',
							   'picurl'=>$headlogo,
								'url'=>'',
							); 
					 if($countall>8){
							  for($id=0;$id<8;$id++){
								
								$b[]=array('title'=>'【#'.$all[$id]['id'].'】 文件名称:'.$all[$id]['attachmentname']."\n相关描述：".$all[$id]['description'],	
							   'description'=>'',
							   'picurl'=>'',
								'url'=>'',);
							  }
                     }elseif($countall>0 && $countall<9){
					     foreach($all as $row){
						    $b[] = array(
							   'title'=>'【#'.$row['id'].'】 文件名称:'.$row['attachmentname']."\n相关描述：".$row['description'],	
							   'description'=>'',
							   'picurl'=>'',
								'url'=>'',
							);
						 }
					 }else{
						 $b[] = array(
					            'title'=>"管理员太不负责了，尽然没给你们提供任何下载文件！",	
							   'description'=>'',
							   'picurl'=>'',
								'url'=>'',
					          );
					 
					 }
					    $b[]=array("title"=>'请回复 S 查看下一页',"url"=>'',"picurl"=>'',"description"=>'');
						return $this->respNews($b); 

		}elseif(preg_match("/^#(.*)$/",$content,$type)){
						if(empty($yx)){
							$reply = "你还未绑定邮箱，请回复自己的邮箱，进行邮箱绑定！\n例如：xxxx@qq.com 或者xxxx.163.com";
							return $this->respText($reply);
						}
		             $utype = $type[1];//取得要已经存储的所有文件，通过匹配文件名字 或者代号来 发送相应的文件
					 //测试邮箱功能
					 $sql = "SELECT id FROM ".tablename('meepomailattachment')." WHERE isshow=:isshow";
					 $arrsql = array(':isshow'=>1);
					 $all = pdo_fetchall($sql,$arrsql);
					 foreach($all as $row){
					     if($utype==$row['id']){
						      $check = $row['id'];
						 }
					 }
					  if(!empty($check)){
                            $sql2 = "SELECT * FROM ".tablename('meepomailattachment')." WHERE id=:id";
					        $arrsql2 = array(':id'=>$check);
					        $one = pdo_fetch($sql2,$arrsql2);
							$time = date('Y/m/');
							$filename  = str_replace("images/{$time}","",$one['thumb']);
						  $title  = $_W['account']['name']."给您货来咯！";
					      $content = "文件名称：".$one['attachmentname'].'以及相关内容：'.$one['description'];
						 
						  $sendres = $this->sendmail($title,$content,$yx['umail'],$one['thumb'],$filename);
						  if($sendres=='发送成功'){
						      $reply = '已经成功发送至您的邮箱，请查收！';
						  }else{
						      $reply = $sendres;
						  }
						  unset($all);
						  unset($one);
					 }else{
					     $reply = '此项不存在或者已经被删除！';
					 }
					 return $this->respText($reply);
		   }elseif(preg_match('/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/',$content,$res)){
		          $mailadd = $res[0];
				  if(!empty($yx)){
				    $sqlres = pdo_update("mailaddress",array('umail'=>$mailadd),array('id'=>$yx['id'],'weid'=>$weid,'openid'=>$openid));
				  }else{
				      $sqlres = pdo_insert("mailaddress",array('umail'=>$mailadd,'openid'=>$openid,'weid'=>$weid));
				  }
				  if($sqlres){
				      $reply = '恭喜您，更新邮箱成功！回复【#+数字】就可以了哦！'."\n直接发送个人邮箱还可以更换收件箱哦！";
				  }else{
				      $reply = '更新邮箱失败！';
				  }
               return $this->respText($reply);
		   }
	}


public function getyx($openid){
		 $tablename = tablename('mailaddress');
	     $sql = 'SELECT * FROM '.$tablename.' WHERE openid=:openid';
	     $arr = array(':openid'=>$openid);
		 $res = pdo_fetch($sql,$arr);
	     return $res;
	 }
	 public function sendmail($_title = '标题', $_content = '内容',$_tomail = "",$attachment="",$filename="",  $_Host = "", $_Username = "", $_Password = "")
    {
        global $_W;
        //获取系统中的邮件资料
        if (empty($_Password) || empty($_Username)) {
            //加载后台配置的发件箱
                     //默认只能填写一个发件箱 且一定要开启smtp
					 $cfg = $this->module['config'];					 
                     $_Host     = $cfg['smtp'];
                     $_Username = $cfg['mailadd'];
                     $_Password = $cfg['password'];
					 
              
        }
        if (empty($_Password) || empty($_Username)) {
            //$_Host     = "smtp.163.com";
            return '后台配置出错，请联系平台管理员！';
        }
        if (trim($_Host) == "smtp.qq.com") {
            $_Host     = "ssl://smtp.qq.com";
            $_Port     = 465;
            $_Authmode = 1;
        } else {
            $_Port = 25;
        }
        
        if ($_Authmode == 1) {
            if (!extension_loaded('openssl')) {
                return '请开启 php_openssl 扩展！';
            }
        }
        
        include_once 'class/class.phpmailer.php';
        try {
            $mail = new PHPMailer(true); //New instance, with exceptions enabled
            $body = $_content;
            $body = preg_replace('/\\\\/', '', $body); //Strip backslashes
            
            $mail->IsSMTP();
            $mail->Charset  = 'UTF-8'; // tell the class to use SMTP
            $mail->SMTPAuth = true; // enable SMTP authentication
            $mail->Port     = $_Port; // set the SMTP server port
            $mail->Host     = $_Host; // SMTP server
            $mail->Username = $_Username; // SMTP server username
            $mail->Password = $_Password; // SMTP server password
            if ($_Authmode == 1) {
                $mailer->SMTPSecure = 'ssl';
            }
            //$mail->IsSendmail();  // tell the class to use Sendmail
            
            $mail->AddReplyTo($_Username, "First Last");
            $mail->From     = $_Username;
            $mail->FromName = $_W['account']['name'] . "--文件下载频道--" . date('m-d H:i');
            $to             = $_tomail;
            
            $mail->AddAddress($to);
            
            $mail->Subject  = $_title;
            $mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $mail->WordWrap = 80; // set word wrap
            $mail->MsgHTML($body);
            $mail->IsHTML(true); // send as HTML
            $mail->AddAttachment("./attachment/".$attachment,$filename); //path是附件的保存路径 name是附件的名字
            
            $mail->Send();
            return '发送成功';
        }
        catch (phpmailerException $e) {
            return $e->errorMessage();
        }
    }

}
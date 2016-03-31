<?php
/**
 * 端午祝福模块处理程序
 *
 * @author lonaking
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Lonaking_dwblessingModuleProcessor extends WeModuleProcessor
{

    public function respond()
    {
        $content = $this->message['content'];
        $config = $this->module['config'];
        $re_time = isset($config['re_time']) ? $config['re_time'] : 60;
        // 退出当前会话
        $out_cmd = isset($config['out_cmd']) ? $config['out_cmd'] : "退出";
        if ($content == $out_cmd) {
            $this->endContext();
            $out_msg = isset($config['out_msg']) ? $config['out_msg'] : "您已经成功退出";
            return $this->respText($out_msg);
        }
        // 开启会话
        if(!$this->inContext){
            $this->beginContext($re_time);
            $message = '请点击这里<a target="_blank" href="' . $this->buildSiteUrl($this->createMobileUrl('show')) . '">制作</a>一条新的祝福,页面打开后,点击右下角"修改姓名"即可添加你的大名哦。\n 您也可以在'.$re_time .'秒内直接回复您的姓名，系统会自动生成由您的祝福页面';
            return $this->respText($message);
        }else{
            $url = $this->buildSiteUrl($this->createMobileUrl('show',array("to_who"=>$content)));
            /*TODO 返回给用户图文消息
            $array = array(
                'Title' => $content.'祝您端午节快乐',
                'Description' => "祝您端午节快乐",
                'PicUrl' => "...",
                'Url' => $url,
                'TagName' => 'item'
            );
            return $this->respNews($array);
            */
            $message = '您好，与您匹配的祝福页面已经生成，点击这里查看 <a target="_blank" href="'.$url.'">我的祝福</a>，快转发到朋友圈去吧！';
            $this->refreshContext($re_time);
            return $this->respText($message);
        }
        
    }
    
}
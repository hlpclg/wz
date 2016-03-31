<?php
/**
 * 头条新闻模块处理程序
 *
 * @author LEO
 * @url http://www.leo163.com/
 */
defined('IN_IA') or exit('Access Denied');

class Top_newsModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		$switch = $this->module['config']['switch'];
		
		//新闻开关
		if($switch==0){
		return $this->respText("新闻模块已关闭");
		exit;
		}

		$toutiao_url = "http://www.toutiao.com/search_content/?offset=0&format=json&keyword=".$content."&autoload=true&count=10";
		
		$result = file_get_contents ( $toutiao_url );
		$result = json_decode ( $result, true );

		foreach ( $result ['data'] as $info ) {
			$PicUrlList = $info ['image_list'][0]['url'];
			$PicUrlMiddle = $info ['image_list']['middle_image'];
			if($PicUrlList){
				$PicUrl = $PicUrlList;
				}
			if($PicUrlMiddle){
				$PicUrl = $PicUrlMiddle;
				}
			if(empty($PicUrl)){
			$PicUrl = MODULE_URL."images/no_pic.jpg";
			}
					
					$articles [] = array (
							'title' => $info ['title'],
							'description' => $info ['abstract'],
							'picurl' => $PicUrl,
							'url' => $info ['share_url'] 
						);
					
				}
				
				//$this->replyNews ( $articles );
				return $this->respNews($articles);
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
	}
}
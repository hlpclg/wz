<?php
/**
 * 商品抓取模块处理程序
 *
 * @author Nace
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Nace_catchModuleProcessor extends WeModuleProcessor
 {
	//VIP店家地址
	private $url;
	//VIP 商品名
	private $shopname;
	//VIP 商品详情
	private $shopinfo;
	//init ch
	private $ch;
	//商品数组
	private $Goods;
	public function  __construct(){

	   	//$this->url='http://www.vip.com/detail-416939-55967891.html';
		$this->ch=curl_init();
		//初始化空数组
		$this->Goods=array();
		
	}
	/**
	 * (non-PHPdoc)
	 * @see WeModuleProcessor::respond()
	 */
	public function respond(){
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
		$out=$this->resinfo();
	//输出XML    数组化  
		$outgoods=array();
		$outgoods=array(
			'title'=>$out['Goodsname'],
			'picurl'=>$out['image'],
			'url'=>$this->url
		);
		return $this->respNews($outgoods);
	}
	/**
	 * 
	 * @return Ambigous <multitype:, multitype:NULL >
	 */
	public function resinfo(){
		//交互变量
		$this->catchshopname();
      return $this->Goods;	
	} 
	
	/**
	 * @todo get enough infomation
	 */
    private  function  catchshopname(){
    	//Get the random catch URL
    	$this->createurl();
    	
    	curl_setopt($this->ch, CURLOPT_URL, $this->url);
    	curl_setopt($this->ch, CURLOPT_HEADER,0);
    	curl_setopt($this->ch, CURLOPT_POST,0);
    	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    	$output=curl_exec($this->ch);
    	preg_match_all('/<td>(.*?)<\/td>/', $output,$Goodsinfo,PREG_SET_ORDER);
    	
    	
    	preg_match_all('/<em>(.[0-9]*?)<\/em>/', $output,$newprice,PREG_SET_ORDER);
    	//  var_dump($newprice);
    	preg_match_all('/<del>(.[0-9]*?)<\/del>/', $output,$oldprice,PREG_SET_ORDER);
    	//匹配图片地址
    	preg_match_all('/<a href="([^<>]+)" class="J-mer-bigImgZoom">/', $output,$image,PREG_SET_ORDER);
    	$this->Goods=array(
    			'image'=>$image[0][1],
    			'Shopname'=>$Goodsinfo[0][1],
    			'Goodsname'=>$Goodsinfo[1][1],
    			'GoodsType'=>$Goodsinfo[2][1],
    			'Goodsinfo'=>$Goodsinfo[5][1],
    			'Newprice'=>$newprice[0][1],
    			'Oldprice'=>$oldprice[0][1]
    	);	
	}
	/**
	 *@todo create the random vip goods url
	 */
	private function createurl(){
		$ran=$this->getrandomnum();
		//Order By random number;
		switch ($ran){
			  //add the catch URL here
			case 1:
			    $this->url='http://www.vip.com/detail-417291-56065583.html';
				break;
			case 2:
				$this->url='http://www.vip.com/detail-417291-56065628.html';
				break;
			case 3:
				$this->url='http://www.vip.com/detail-417291-56065651.html';
				break;		
			case 4:
				$this->url='http://www.vip.com/detail-417291-56065512.html';
				break;		
			case 5:
				$this->url='http://www.vip.com/detail-417291-56065489.html';
				break;	
			case 6:
				$this->url='http://www.vip.com/detail-417291-56065543.html';
				break;
			case 7:
				$this->url='http://www.vip.com/detail-417291-56065540.html';
				break;
			case 8:
				$this->url='http://www.vip.com/detail-417291-56065630.html';
				break;
			case 9:
				$this->url='http://www.vip.com/detail-417291-56066076.html';
				break;
			case 10:
				$this->url='http://www.vip.com/detail-417737-55859962.html';
				break;	
			default:
				break;							
		}
	}
	/**
	 * 
	 * @return number
	 */
	private function getrandomnum(){
		//create random number
		$ran=mt_rand(1, 10);
		//TODO: if U want to operate the random number ,Add your code here
		
		//return this number
		return $ran;
	}
}

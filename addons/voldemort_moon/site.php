<?php
//加密方式：php源码混淆类加密。免费版地址:http://www.zhaoyuanma.com/phpjm.html 免费版不能解密,可以使用VIP版本。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (免费版）在线逆向还原，QQ：7530782 
?>
<?php
/**
 * 嫦娥爱色兔模块微站定义
 *
 * @author voldemort
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Voldemort_moonModuleSite extends WeModuleSite {

	public function doMobileRule1() {
		global $_W,$_GPC;
		

		include $this->template('index');
	}

}
?>
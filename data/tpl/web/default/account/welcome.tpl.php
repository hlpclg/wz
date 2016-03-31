<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php  if(isset($title)) $_W['page']['title'] = $title?><?php  if(!empty($_W['page']['title'])) { ?><?php  echo $_W['page']['title'];?> - <?php  } ?><?php  if(empty($_W['page']['copyright']['sitename'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微赞 - 微信公众平台开源管理系统-012wz.com<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['sitename'];?><?php  } ?></title>
<meta name="keywords" content="<?php  if(empty($_W['page']['copyright']['keywords'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微赞,微信,微信公众平台<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['keywords'];?><?php  } ?>" />
<meta name="description" content="<?php  if(empty($_W['page']['copyright']['description'])) { ?><?php  if(IMS_FAMILY != 'x') { ?>微信公众平台自助引擎，简称微赞，微赞是一款开源的微信公众平台管理系统。<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['description'];?><?php  } ?>" />
<script src="./resource/weidongli/js/html5.js"></script>
	<link rel="stylesheet" type="text/css" href="./resource/weidongli/css/cs.css" />
	<link rel="stylesheet" type="text/css" href="./resource/weidongli/css/donban.css" />
	<link rel="stylesheet" type="text/css" href="./resource/weidongli/css/page-good.css" />
	<link href="./resource/weidongli/css/style.css" rel="stylesheet" type="text/css" />
	<script src="./resource/weidongli/js/index.js" type="text/javascript"></script>
	<script type="text/javascript" src="./resource/weidongli/js/jquery.min.js"></script>
			<!--[if IE 6]>
	<script src="./resource/weidongli/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script>
  DD_belatedPNG.fix('#index img');
  DD_belatedPNG.fix('#headbgg img');
  DD_belatedPNG.fix('#footerLogo img');
</script>
<![endif]-->
	<script type="text/javascript">
  jq = jQuery.noConflict();
//以后jquery中的$都用jq代替即可。
jq(function(){});
</script>
 <!--[if IE]>
<style type="text/css">
.color-block {
background:transparent;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#4B7D0000,endColorstr=#4B7D0000);
zoom: 1;
}
</style>
<![endif]-->

</head>

<body id="nv_portal" class="pg_index" onkeydown="if(event.keyCode==27) return false;">
<div id="append_parent"></div><div id="ajaxwaitid"></div>
<div id="workpop"></div>
<div class="index" id="index">

<div class="headbg">
<div class="head" id="headbgg">
<div class="mo" style=" left:-200px; padding-top:15px; position:absolute">
</div>
<h1 class="logo" <?php  if(!empty($_W['setting']['copyright']['flogo'])) { ?>style="background:url('<?php  echo tomedia($_W['setting']['copyright']['flogo']);?>') no-repeat;"<?php  } else { ?>style="background:url('./resource/images/logo.png') no-repeat;"<?php  } ?>></h1>
<div class="nav" id="nav">
<ul >
				<li class="active" onClick="navmove('#index','0')"><a href="javascript:void(0);" title="网站首页">网站首页</a></li>
				<li onClick="navmove('#services','1')"><a href="javascript:void(0);" title="功能介绍">功能介绍</a></li>
				<li onClick="navmove('#about','3')"><a href="<?php  echo url('article/news-show/list');?>">新闻动态</a></li>
				<li onClick="navmove('#services','2')"><a href="<?php  echo url('article/case-show/list');?>">客户案例</a></li>
                <li onClick="navmove('#login','4')"><a href="<?php  echo url('user/login');?>" title="登陆">会员登陆</a></li>
				<li onClick="navmove('#login','4')"><a href="<?php  echo url('user/register');?>" title="注册">会员注册</a></li>            
			</ul>
		</div>


	</div>
</div>

<div id="wp2" class="wp2">

<style id="diy_style" type="text/css"></style>
<link href="./resource/weidongli/css/newcom.css" rel="stylesheet" type="text/css" />
<style>body{ margin-top:0px;}</style>

	<script type="text/javascript" src="./resource/weidongli/js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="./resource/weidongli/js/ainatec.js"></script>
    <script src="./resource/weidongli/js/index.js" type="text/javascript"></script>

<!--开始-->
<div class="infocus">
	<div class="focus_pic">
		<ul >
		<li><a href="<?php  echo url('user/login');?>"><img src="./resource/weidongli/images/pic08.jpg" width="1920" height="612" alt="微信营销新时代"></a></li>
		<li><a ><img src="./resource/weidongli/images/pic09.jpg" width="1920" height="612" alt=""></a></li>
		<li><a ><img src="./resource/weidongli/images/pic10.jpg" width="1920" height="612" alt="微信营销新时代"></a></li>
		<li><a href="/"><img src="./resource/weidongli/images/pic04.jpg" width="1920" height="612" alt="微赞新时代"></a></li>
		</ul>
	</div>
	<a href="javascript:void(0);" title="" class="left_btn"><img src="./resource/weidongli/images/focus_btnleft.png" width="50" height="124" class="png" alt=""></a>
	<a href="javascript:void(0);" title="" class="right_btn"><img src="./resource/weidongli/images/focus_btnright.png" width="50" height="124" class="png" alt=""></a>
</div>
<div id="services">
	<div id="services_pop">
		<div class="services_popcon">
			<a href="javascript:void(0);" title="close" class="services_popclose"></a>
			<div class="services_poptabcon">
				<ul >
					<li class="services_poptabconbg01">
						<div class="services_popcontainer">
							<div class="services_popcontainer_pop01">
														<h3>品牌进行时…</h3>
								<p>我们相信每个企业品牌都有它的个性，就如它的每个<br/>创始人的传奇性一般。如何将它的个性发光发亮，<br/>我们一直都专研此道，从视觉出发，颜色、版式、材质、<br/>载体每一个都寄托了它的灵魂，<br/>我们精于此道：品牌策划、平面设计。</p>
							</div>
						</div>
					</li>
					<li class="services_poptabconbg02">
						<div class="services_popcontainer">
							<div class="services_popcontainer_pop02">
							<h3>看到的是细节，感受到的是用心！</h3>
								<p>从产品交互原型设计到HTML5前端页面开发，从功能定<br/>制到网站上线维护，我们一如既往地根据用户的需求、<br/>市场环境、企业状况等进行综合分析并提供真实可靠的<br/>WEB解决方案，有效提升企业形象及品牌知名度。</p>
							</div>
						</div>
					</li>
					<li class="services_poptabconbg03">
						<div class="services_popcontainer">
							<div class="services_popcontainer_pop03">
										<h3>触摸移动互联网革命！</h3>
								<p>我们基于市场主流的IOS/Android平台，提供高效率的跨平台移动互联网解决方案，<br/>真正意义上从用户的角度出发，做到一套UI界面苹果、安桌共用，有效节约时间和开发成本。<br/>另外我们还提供基于HTML5的WEB  APP开发服务，并可适应WP系统。</p>
							</div>
						</div>
					</li>
					<li class="services_poptabconbg04">
						<div class="services_popcontainer">
							<div class="services_popcontainer_pop04">
												<h3>将办公室搬上手机！</h3>
								<p>我们善于基于LAMP架构提供OA企业协同办公系统开发服务，<br/>多年来先后为深圳市公安局、林业局、芙蓉坊国际美容美体机构等<br/>知名单位企业提供网络系统相关解决方案，并实现安全、高效、分布式管理。</p>
							</div>
						</div>
					</li>
					<li class="services_poptabconbg05">
						<div class="services_popcontainer">
							<div class="services_popcontainer_pop05">
							<h3>把流量转化成业务量！</h3>
								<p>我们长期位居百度深圳网站优化排名第一阶梯，拥有丰富的网站优化实战经验，<br/>众多的成功案例，能安全高效地把企业的相关服务/产品等极具价值的关键词排名首页，<br/>增加网站流量含金量，另外提供微营销服务，全方位持续增加企业收入。</p>
							</div>
						</div>
					</li>
					<li class="services_poptabconbg06">
						<div class="services_popcontainer">
							<div class="services_popcontainer_pop06">
									<h3>每个行业都应该有专属的云主机！</h3>
								<p>我们提供超低成本、超弹性、超高性能的国内、香港免备案云主机，为企业及大型门户<br />提供高防高带宽优质机房服务器托管、租赁，域名注册、ICP备案等服务。</p>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="services_poptabbtn">
				<a href="javascript:void(0);" title="" onclick = "services_tab('0');"></a><a href="javascript:void(0);" title="" onclick = "services_tab('1');"></a><a href="javascript:void(0);" title="" onclick = "services_tab('2');"></a><a href="javascript:void(0);" title="" onclick = "services_tab('3');"></a><a href="javascript:void(0);" title="" onclick = "services_tab('4');"></a><a href="javascript:void(0);" title="" onclick = "services_tab('5');"></a>
			</div>
		</div>
	</div>
	<div class="services_title">
		<img src="./resource/weidongli/images/services_title.png" width="250" height="70" alt="">
	</div>
	<div class="services_con" id="services_con">
		<ul id="services_ul" class="mouseslider">
			<li>
				<a href="javascript:void(0);" title="" onclick = "services_tab('0','true');">
					<div class="services_ico services_ico05"><div></div></div>
					<h3>视觉设计 <span>VI DESIGN</span></h3>
					<p>品牌进行时…</p>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" title="" onclick = "services_tab('1','true');">
					<div class="services_ico services_ico01"><div></div></div>
					<h3>微站设计 <span>WEB DESIGN</span></h3>
					<p>看到的是细节，感受到的是用心。</p>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" title="" onclick = "services_tab('2','true');">
					<div class="services_ico services_ico02"><div></div></div>
					<h3>微信开发 <span>APP DESIGN</span></h3>
					<p>微信-触摸移动互联网革命！</p>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" title="" onclick = "services_tab('3','true');">
					<div class="services_ico services_ico03"><div></div></div>
					<h3>系统开发 <span>OA DESIGN</span></h3>
					<p>将办公室搬上互联网</p>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" title="" onclick = "services_tab('4','true');">
					<div class="services_ico services_ico04"><div></div></div>
					<h3>网站优化 <span>SEO/SEM</span></h3>
					<p>把流量变成持续的现金收入</p>
				</a>
			</li>
			<li style="margin-right:0;">
				<a href="javascript:void(0);" title="" onclick = "services_tab('5','true');">
					<div class="services_ico services_ico06"><div></div></div>
					<h3>云主机 <span>IDC/VPS</span></h3>
					<p>每个行业都应该有专属的云主机</p>
				</a>
			</li>
		</ul>
	</div>
</div>
<div id="project">
	<div class="project_title">
		<img src="./resource/weidongli/images/project_title.png" width="250" height="70" alt="">
		<a href="/" title="" class="project_title_more"></a>
	</div>
	<div class="projectbg">
		<div class="project_pic">
<ul >
<li><div id="projectbg" class="area"><div id="framemU0Edl" class="cl_frame_bm frame move-span cl frame-1"><div id="framemU0Edl_left" class="column frame-1-c"><div id="framemU0Edl_left_temp" class="move-span temp"></div><div id="portal_block_6" class="cl_block_bm block move-span"><div id="portal_block_6_content" class="dxb_bc"><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds1-1.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds2-1.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds3-1.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/edu2-1.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds4-1.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds4-2.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/edu1-1.jpg">
</div>
</a><a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/fdc2-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/fdc3-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/fdc4-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/fdc5-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ls1-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/fdc7-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/fdc8-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds7-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/ds6-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/hs4-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="电商-微站" href="/vip/cases.html">
<div class="project_pop" style="display: none;">
<h3>电商-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="电商-微站" src="./resource/weidongli/images/hs5-1.jpg">
</div>
</a>
</div></div></div></div></div></li>

<li class="dn">
<div id="projectbg1" class="area"><div id="framefg8ISP" class="cl_frame_bm frame move-span cl frame-1"><div id="framefg8ISP_left" class="column frame-1-c"><div id="framefg8ISP_left_temp" class="move-span temp"></div><div id="portal_block_7" class="cl_block_bm block move-span"><div id="portal_block_7_content" class="dxb_bc"><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/edu1-1.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/edu1-2.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/edu2-1.jpg">
</div>
</a><a class="project_box workpopl" title="" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/edu3-1.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/edu4-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/edu5-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/ds3-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/ds2-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/ds1-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh7-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh6-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh5-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh4-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh3-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh2-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/zh1-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/edu5-1.jpg">
</div>
</a><a class="project_box workpopl" title="淘宝微站" href="/">
<div class="project_pop" style="display: none;">
<h3>淘宝微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="淘宝微站" src="./resource/weidongli/images/wsh3-1.jpg">
</div>
</a>
</div></div></div></div></div></li>
<li class="dn">
<div id="projectbg2" class="area"><div id="frameo3W3ff" class="cl_frame_bm frame move-span cl frame-1"><div id="frameo3W3ff_left" class="column frame-1-c"><div id="frameo3W3ff_left_temp" class="move-span temp"></div><div id="portal_block_8" class="cl_block_bm block move-span"><div id="portal_block_8_content" class="dxb_bc"><a class="project_box workpopl" title="培训机构-微站" href="forum.php?mod=viewthread&tid=141">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/jd1-1.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/jd2-1.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/jd3-1.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/jd4-1.jpg">
</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>培训机构-微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="培训机构-微站" src="./resource/weidongli/images/jd5-1.jpg">
</div>
</a>
<a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/jd6-1.jpg">

</div>
</a>
<a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/yl6-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/yl5-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/tx4-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/tx3-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/tx2-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr7-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr6-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr5-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr4-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr3-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr2-1.jpg">

</div>
</a><a class="project_box workpopl" title="培训机构-微站" href="/">
<div class="project_pop" style="display: none;">
<h3>商务微站</h3>
<p>WEB DESIGN</p>
<i></i>
</div>
<div class="project_img">
<img alt="商务微站" src="./resource/weidongli/images/mr1-1.jpg">

</div>
</a>
</div></div></div></div></div></li>
</ul>
</div>
		<div class="project_btn">
			<a href="javascript:void(0);" title="" onclick = "project_tab('0');" class="active"></a><a href="javascript:void(0);" title="" onclick = "project_tab('1');"></a><a href="javascript:void(0);" title="" onclick = "project_tab('2');"></a>
		</div>
	</div>
</div>
<!--[if lt IE 9]>
<script type="text/javascript">
$(function(){
	$(".about_list li").hover(function(){
		$("img",this).stop().animate({"width":"108%",height:"108%",marginLeft:"-4%",marginTop:"-4%"},200)
	},function(){
		$("img",this).stop().animate({"width":"100%",height:"100%",marginLeft:"0",marginTop:"0"},200)
	})
})
</script>
<![endif]-->
<div id="about">
	<div class="about_title">
		<img src="./resource/weidongli/images/about_title.png" width="250" height="70" alt="">
	</div>
	<div class="about_list clearbox">
		<ul >
		<div id="clearbox" class="area"><div id="frameG2IQI7" class="cl_frame_bm frame move-span cl frame-1"><div id="frameG2IQI7_left" class="column frame-1-c"><div id="frameG2IQI7_left_temp" class="move-span temp"></div><div id="portal_block_9" class="cl_block_bm block move-span"><div id="portal_block_9_content" class="dxb_bc"><li>
<img src="./resource/weidongli/images/5.jpg" alt="">
</li><li>
<img src="./resource/weidongli/images/6.jpg" alt="">
</li><li>
<img src="./resource/weidongli/images/7.jpg" alt="">
</li><li>
<img src="./resource/weidongli/images/8.jpg" alt="">
</li></div></div></div></div></div>
			
		</ul>
	</div>
	<div class="about_int">
		<a href="javascript:void(0);" title="" class="about_btn"><span></span></a>
	</div>
	<div class="about_pop" id="about_pop">
		<div class="about_pop_top">
			<a href="javascript:void(0);" title="close" class="about_popclose"></a>
			<h3><img src="./resource/weidongli/images/about_popword.png" alt="" class="png"></h3>
			<p class="about_pop_int"><?php  echo $_W['setting']['copyright']['sitename'];?>设计是一支年轻的IT及设计领域精英团队，专业于视觉和用户体验、微信公众平台、php网站开发……</p>
			<div class="about_pop_tab clearbox">
				<ul >
					<li class="active" onclick = "aboutpop_tab('0')"><a href="javascript:void(0);" title="" class="about_pop_tab01"></a></li>
					<li onclick = "aboutpop_tab('1')"><a href="javascript:void(0);" title="" class="about_pop_tab02"></a></li>
					<li onclick = "aboutpop_tab('2')"><a href="javascript:void(0);" title="" class="about_pop_tab03"></a></li>
					<li onclick = "aboutpop_tab('3')"><a href="javascript:void(0);" title="" class="about_pop_tab04"></a></li>
				</ul>
			</div>
		</div>
		<div class="about_pop_con">
			<ul >
				<li class="about_pop_con01" style="display:block">
					<img src="./resource/weidongli/images/about_logo.png" width="283" height="171" class="about_pop_aboutlogo" alt="">
					<div class="about_pop_about">
								<h3>设计前卫·简约美观</h3>
						<p><?php  echo $_W['setting']['copyright']['sitename'];?>设计团队成立于2010年，是广东专业的网络品牌服务商，我们坚持以优秀的创意设计、领先的软件编程技术、出其不意的电子商务战略，鼎力为国内外知名企业提供全方位的网页设计、移动应用、网络系统、视觉设计、网站优化以及云主机租恁等服务。</p>
						<p>&nbsp;</p>
						<p style="color:#333; font-size:16px;"><?php  echo $_W['setting']['copyright']['sitename'];?>设计团队成长历程：</p>
						<p>2008年大学毕业后，8人志同道合于从事网络工作赴国外留学，于2010年回国</p>
						<p>2010年初成立<?php  echo $_W['setting']['copyright']['sitename'];?>设计工作室，进行产品推广与策划工作</p>
						<p>2011年进行门户网站整站设计制作并联合社会IT精英从事网站建设工作；</p>
						<p>2011年进行维丝利清新系列包装设计制作；</p>
						<p>2013年对微信平台系程序、APP运用、thankPHP等程序二次开发；</p>
						<p>2013年国立管理系统开发/广东粤西集团ERP管理界面开发；</p>
						<p>2013年CQ网络营销策划以及执行；</p>
						<p>未来期待您的参与...</p>
					</div>
				</li>
				<li class="about_pop_con02 clearbox">
					<div class="about_pop_job">
						<h3>实习设计师助理</h3>
						<p>岗位职责：<br/>参与项目设计执行流程，了解产品开发全过程。辅助设计师完成重大项目。参与一定的产品分析，交互设计，产品界面视觉设计及规范文档整理工作；</p>
						<p>任职要求：<br/>精通设计类相关软件，热衷于界面设计与交互设计；具有一定的交互分析以及理解能力；<br/>优秀的视觉设计感觉与潜力；有完整独立作品可供参考。</p>
						<p>招聘人数：1<br/>工作地点：深圳</p>
						<p>请发简历到：{weixin::C('site_email')}</p>
					</div>
					<div class="about_pop_job">
						<h3>高级视觉设计师</h3>
						<p>岗位职责：<br/>对用户行为做分析，形成观点和需求，对行业产品趋势分析，评估现有产品的用户体验；<br/>撰写用户体验分析文档，配合视觉设计师完成产品设计。</p>
						<p>任职要求：<br/>一年以上相关工作经验，性格开朗，积极乐观,良好的沟通、表达能力, 团队意识强烈，热衷于研究各类电子产品或互联网产品，有较好的英语读写能力。</p>
						<p>招聘人数：1<br/>工作地点：深圳</p>
						<p>请发简历到：{weixin::C('site_email')}</p>
					</div>
					<div class="about_pop_job">
						<h3>iOS/Android开发工程师</h3>
						<p>岗位职责：<br/>负责iOS或Android客户端应用的设计研发；<br/>能与团队成员高效配合，完成项目工作。</p>
						<p>任职要求：<br/>一年以上iOS或Android开发经验；有扎实的Object C/C++/C/Java语言基础；熟练掌握开发技术，包括UI、网络等方面；熟悉相关开发工具和相关测试工具的使用；学习能力强，有较强分析解决问题的能力；对移动产品有浓厚兴趣。</p>
						<p>招聘人数：1<br/>工作地点：深圳</p>
						<p>请发简历到：{weixin::C('site_email')}</p>
					</div>
					<div class="about_pop_job" style="margin-right:0;">
						<h3>PHP开发工程师</h3>
						<p>岗位职责：<br/>负责网站或相关产品的PHP研发工作；能与团队成员高效配合，完成项目工作。</p>
						<p>任职要求：<br/>至少两年相关工作经验；一年以上PHP开发及维护工作经验；熟练掌握MySQL数据库，LA/NMP；熟练应用HTML/JavaScript;熟悉相关开发工具和测试工具的使用；学习能力强，有较强分析解决问题的能力。</p>
						<p>招聘人数：1<br/>工作地点：深圳</p>
						<p>请发简历到：{weixin::C('site_email')}</p>
					</div>
	
				</li>
				<li class="about_pop_con03"><img src="./resource/weidongli/images/fwlc_pic.jpg" alt=""></li>
				<li class="about_pop_con04">
					<dl class="about_pop_client clearbox">
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_1.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_2.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_3.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_4.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_5.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_6.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_7.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_8.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_9.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_10.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_11.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_12.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_13.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_14.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_15.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_16.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_18.gif" border="0" alt=""></a></dd>
						<dd><a href="/" title=""><img src="./resource/weidongli/images/tg_clients_17.gif" border="0" alt=""></a></dd>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="newsbg" id="news">
	<div class="news clearbox">
<div class="news_box fl">
<div class="news_title">
<a href="/" title="">公司动态 <span>News</span></a>
</div>
<ul class="news_list" id="news_list">
<?php  if(is_array($news)) { foreach($news as $new) { ?>
<li><a href="<?php  echo url('article/news-show/detail', array('id' => $new['id']));?>" title="<?php  echo $new['title'];?>" target="_blank"><?php  echo $new['title'];?></a></li>
<?php  } } ?>		
</ul>
</div>
<div class="news_line"></div>
<div class="news_box fl">
<div class="news_title">
<a href="/" title="">客户案例 <span>Case</span></a>
</div>
<ul class="news_list" id="news_list">
<?php  if(is_array($case)) { foreach($case as $case) { ?>
<li><a href="<?php  echo url('article/case-show/detail', array('id' => $case['id']));?>" title="<?php  echo $case['title'];?>" target="_blank">[客户案例]-微信公众号:<?php  echo $case['title'];?></a></li>
<?php  } } ?>	
</ul>
</div>
<div class="news_line"></div>
<div class="news_box fr">
<div class="news_title">
<a href="/" title="">公告资讯 <span>SEO</span></a>
</div>
<ul class="news_list" id="news_list">
<?php  if(is_array($notices)) { foreach($notices as $notice) { ?>
<li><a href="<?php  echo url('article/case-show/detail', array('id' => $case['id']));?>" title="<?php  echo $case['title'];?>" target="_blank">[客户案例]-微信公众号:<?php  echo $case['title'];?></a></li>
<?php  } } ?>	
</ul>
</div>
</div>
</div>

<div class="contactbg" id="contact">
	<div class="contact clearbox">
		
		<div class="contact_map">
			<img src="./resource/weidongli/images/map_pop.png" width="297" height="297" class="png" alt="">
		</div>
	</div>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-cms', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-cms', TEMPLATE_INCLUDEPATH));?>
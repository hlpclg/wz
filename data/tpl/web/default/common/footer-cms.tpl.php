<?php defined('IN_IA') or exit('Access Denied');?><div class="foot_linksbg">
<div class="foot_links">
<div class="links">
<h3>LINK</h3>
<div class="links_con">
<a href="/" target="_blank" title="微信公众平台">微信公众平台</a>&nbsp;&nbsp;|&nbsp;&nbsp;
</div>
</div>
<div class="keyword">
<h3>KEYWORD</h3>
<div class="links_con">
<a href="#" title="">微信公众号</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#" title="">APP开发</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#" title="">三网融合</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#" title="">微信开发</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#" title="">政府研发</a>&nbsp;&nbsp;|&nbsp;&nbsp;
</div>
</div>
<div class="address">
<p><a href="#" title=""><?php  echo $copyright['company'];?></a></p>
<p>办公室：<?php  echo $copyright['address'];?></p>
</div>
<div class="link_btn">
<ul >
<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $copyright['qq'];?>&site=qq&menu=yes" target="_blank"title="" class="link_qq"></a></li>
<li><a href="#" title="" class="link_sina"></a></li>
<li class="link_weixin_li"><a href="javasctipt:void(0);" title="" class="link_weixin"></a>
<div class="link_weixin_ewm">
<img src="<?php  if(!empty($copyright['ewm'])) { ?><?php  echo tomedia($copyright['ewm']);?><?php  } else { ?>./resource/weidongli/images/ewm.jpg<?php  } ?>" width="129" height="129" alt="">
</div>
</li>
</ul>
</div>
</div>
</div>
<div class="footbg">
<div class="foot">
<p class="z">
<?php  if(empty($copyright['footerleft'])) { ?>Powered by <strong>
<a href="/" target="_blank">012wz.com</a></strong> <em>WEIZAN</em>&nbsp--&copy; 2001-2014 <a href="/" target="_blank"><a href="/" target="_blank">新马泰</a>
<?php  } else { ?><?php  echo $copyright['footerleft'];?><?php  } ?>
</p>
<p class="y">
<?php  if(empty($copyright['footerright'])) { ?>
<a href="http://www.j0515" >WIKI</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<strong><a href="http://www.j0515.com" target="_blank">新马泰科技团队</a></strong>
&nbsp;
( <a href="http://www.miitbeian.gov.cn/" target="_blank">苏ICP备12011087号</a> )&nbsp;
<?php  } else { ?><?php  echo $copyright['footerright'];?><?php  } ?> &nbsp; &nbsp; <?php  if(!empty($copyright['statcode'])) { ?><?php  echo $copyright['statcode'];?><?php  } ?>
</p>
</div>
</div>
</div>
 </div>
 </div>
</body>
</html>

<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-cms', TEMPLATE_INCLUDEPATH)) : (include template('common/header-cms', TEMPLATE_INCLUDEPATH));?>
<link href="./resource/weidongli/css/cms.css" rel="stylesheet" type="text/css" />

<?php  if($do == 'list') { ?>
<div class="breadcrumbs margin-bottom-40 cl">
<div class="xiaoyu_container cl">
        <h1 class="color-green z"><a href="<?php  echo url('article/news-show/list');?>">新闻动态</a></h1>
        <ul class="y breadcrumb">
        <li><a href="./" title="首页">首页</a> <span class="divider">/</span></li>
		<li><a href="<?php  echo url('article/news-show/list');?>">新闻列表</a><span class="divider">/</span></li>
		<li class="active"><?php  if(!$cateid) { ?>所有新闻<?php  } else { ?><?php  echo $categroys[$cateid]['title'];?><?php  } ?></li>
        </ul>
</div>
</div>
<div class="xiaoyu_container cl">
	<div class="fishue_unnew_left"> 
    <div class="col-md-9 md-margin-bottom-40 cl">
	<?php  if(is_array($data)) { foreach($data as $da) { ?>
		<div class="row blog blog-medium margin-bottom-40 cl">
		<div class="col-md-5">
        <a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>" target="_blank"><img src="<?php  echo $_W['siteroot'];?>/attachment/<?php  echo $da['thumb'];?>" alt="<?php  echo $da['title'];?>" class="img-responsive" /></a>
		</div>    
		<div class="col-md-7">
		<h2> <a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>" target="_blank" ><?php  echo $da['title'];?></a></h2>
        <ul class="list-unstyled list-inline blog-info cl">
            <li><i class="fa fa-calendar"></i> <?php  echo date('Y-m-d', $da['createtime']);?></li>  
			<li><i class="fa fa-tags"></i><a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>">&nbsp;作者: <?php  echo $da['author'];?></a></li>	
			<li><i class="fa fa-tags"></i><a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>">&nbsp;阅读: <?php  echo $da['click'];?> 次</a></li>				
        </ul>
        <p><?php  echo cutstr_html($da['content'],'108');?></p>
        <p class="xiaoyu_readmore"><a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>" target="_blank" class="btn-u btn-u-sm">Read More <i class="fa fa-angle-double-right margin-left-5"></i></a></p>
		</div>    
		</div>
	<?php  } } ?>
		<div class="xiaoyu_bottom_border"></div>
    </div>
 </div>
 	<!--右栏-->
 <div class="y pph xiaoyu_view_sd"> 
   <div class="col-md-3"> 
    <!-- Posts --> 
    <div class="posts margin-bottom-40"> 
     <div id="posts" class="area">
	 <div id="frameroLVvi" class="cl_frame_bm frame move-span cl frame-1">
	 <div id="frameroLVvi_left" class="column frame-1-c">
	 <div id="frameroLVvi_left_temp" class="move-span temp"></div>
	 <div id="portal_block_78" class="cl_block_bm block move-span">
	 <div id="portal_block_78_content" class="dxb_bc">
	 <div class="headline headline-md"><h2>最新文章</h2></div>
	 <?php  if(is_array($data)) { foreach($data as $da) { ?>
		<dl class="dl-horizontal">
			<dt><a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>"><img  src="<?php  echo $_W['siteroot'];?>/attachment/<?php  echo $da['thumb'];?>"></a></dt>
			<dd>
			<p><a href="<?php  echo url('article/news-show/detail', array('id' => $da['id']));?>" target="_blank"><?php  echo $da['title'];?></a></P>
			</dd>
		</dl>
	<?php  } } ?>
	 </div>
	 </div>
	 </div>
	 </div>
	 </div>
    </div> 
    <div class="headline headline-md">
     <h2>相关分类</h2>
    </div> 
    <div class="tab-v2 margin-bottom-40"> 
     <div class="tab-content" id="myTab1_con">       
      <div class="tab-pane J-tab active"> 
       <div class="row"> 
        <ul class="list-unstyled xiaoyu_tab_other cl">
<?php  if(is_array($categroys)) { foreach($categroys as $categroy) { ?>		
         <li><a href="<?php  echo url('article/news-show/list', array('cateid' => $categroy['id']));?>"><?php  echo $categroy['title'];?></a></li>           
<?php  } } ?>    
        </ul> 
       </div> 
      </div> 
     </div> 
    </div> 
   </div> 
  </div>
</div>
	<?php  } ?>
	<?php  if($do == 'detail') { ?>
<div class="breadcrumbs margin-bottom-40 cl">
<div class="xiaoyu_container cl">
        <h1 class="color-green z"><a href="<?php  echo url('article/news-show/list');?>">新闻动态</a></h1>
        <ul class="y breadcrumb">
        <li><a href="./" title="首页">首页</a> <span class="divider">/</span></li>
		<li><a href="<?php  echo url('article/news-show/list');?>">新闻列表</a><span class="divider">/</span></li>
		<li class="active"><?php  echo $news['title'];?></li>
        </ul>
</div>
</div>
<div class="xiaoyu_container cl">
	<div class="mn xiaoyu_mn_view2">
		<div class="vw xiaoyu_vw">
		<div class="h hm">
		<h1 class="ph" style="padding-top: 15px;" ><?php  echo $news['title'];?></h1>
		<p class="xg1">
		<?php  echo date('Y-m-d H:i', $news['createtime']);?><span class="pipe">|</span>
		作者: <em id="_viewnum"><?php  echo $news['author'];?></em><span class="pipe">|</span>
		查看: <em id="_viewnum"><?php  echo $news['click'];?> 次</em><span class="pipe">|</span>
		</p>
		</div>
		<div class="s" style="border-bottom-width: 2px;margin-bottom: 6px; padding-bottom: 15px; padding-top: 15px;"><div><strong><font color="red">摘要</font></strong>: <?php  echo $news['info'];?></div></div>
		<div id="article_content" style="font-size: 15px;padding-bottom: 15px; padding-top: 18px;">
		<?php  echo $news['content'];?>
		</div>
		</div>
	</div>
</div>
	<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-cms', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-cms', TEMPLATE_INCLUDEPATH));?>

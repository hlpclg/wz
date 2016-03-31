<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class=""><a href="<?php  echo url('system/filecheck');?>">文件校验</a></li>
</ol>
<div class="main">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php  echo url('system/filecheck');?>">文件校验</a></li>
	</ul>
	<form action="" method="post" class="form-horizontal form">
		<h5 class="page-header">文件校验</h5>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">操作说明</label>
			<div class="col-sm-10">
				<div class="help-block"><strong>文件校验功能可以查看您丢失，修改，添加的文件 </strong></div>
				<div class="help-block"><strong>注意: 使用文件校验的时候不会校验根目录下的&nbsp;&nbsp; /addons,&nbsp;&nbsp; /data/tpl, &nbsp;&nbsp;/data/logs目录. </strong></div>
				<div class="help-block"><strong>注意:‘被修改’和‘未知’的文件应当引起您的注意，必须确认文件是您自己修改</strong></div>
			</div>
		</div>
		<?php  if($do == 'check') { ?>
		<h5 class="page-header">校验结果</h5>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width: 180px;"><span class="text-danger">被修改文件: <?php  echo $count_modify;?></span><a href="javascript:" onclick="$('.modify').show();$('.lose').hide();$('.unknown').hide();">[查看]</a></label>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width: 180px;"><span class="text-danger">丢失文件: <?php  echo $count_lose;?></span><a href="javascript:" onclick="$('.modify').hide();$('.lose').show();$('.unknown').hide()">[查看]</a></label>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label" style="width: 180px;"><span class="text-info">未知文件: <?php  echo $count_unknown;?></span><a href="javascript:" onclick="$('.modify').hide();$('.lose').hide();$('.unknown').show();">[查看]</a></label>
		</div>
		<div class="modify">
			<h5 class="page-header">被修改的文件</h5>
			<?php  if(empty($modify)) { ?>
			<div class="help-block">没有被被修改的文件</div>
			<?php  } ?>
			<div class="alert alert-info"  id="modify" style="<?php  if(empty($modify)) { ?>display:none;<?php  } ?>;line-height:20px;height: <?php  if($count_modify > 50) { ?>1000px<?php  } else { ?><?php  echo $count_modify*20.5?>px<?php  } ?>;overflow: hidden;">
				<?php  if(is_array($modify)) { foreach($modify as $modif) { ?>
				<div><i class="fa fa-file-text"></i>&nbsp;&nbsp;&nbsp;<?php  echo $modif;?></div>
				<?php  } } ?>
			</div>
			<?php  if($count_modify > 50) { ?><a href="javascript:" onclick="$('#modify').css('height', 'auto');$(this).hide();">显示全部</a><?php  } ?>
		</div>
		<div class="unknown" style="display: none">
			<h5 class="page-header">未知的文件</h5>
			<?php  if(empty($unknown)) { ?>
			<div class="help-block">没有未知的文件</div>
			<?php  } ?>
			<div class="alert alert-info" id="unknown" style="<?php  if(empty($unknown)) { ?>display:none;<?php  } ?>line-height:20px;height:<?php  if($count_unknown > 50) { ?>1000px<?php  } else { ?><?php  echo $count_unknown*20.5?>px<?php  } ?>;overflow: hidden;">
				<?php  if(is_array($unknown)) { foreach($unknown as $unknow) { ?>
				<div><i class="fa fa-file-text"></i>&nbsp;&nbsp;&nbsp;<?php  echo $unknow;?></div>
				<?php  } } ?>
			</div>
			<?php  if($count_unknown > 50) { ?><a href="javascript:" onclick="$('#unknown').css('height', 'auto');$(this).hide();">显示全部</a><?php  } ?>
		</div>
		<div class="lose" style="display: none">
			<h5 class="page-header">丢失的文件</h5>
			<?php  if(empty($lose)) { ?>
			<div class="help-block">没有丢失的文件</div>
			<?php  } ?>
			<div class="alert alert-info" id="lose" style="<?php  if(empty($lose)) { ?>display:none;<?php  } ?>line-height:20px;height:<?php  if($count_lose > 50) { ?>1000px<?php  } else { ?><?php  echo $count_lose*20.5?>px<?php  } ?>;overflow: hidden">
				<?php  if(is_array($lose)) { foreach($lose as $los) { ?>
				<div><i class="fa fa-file-text"></i>&nbsp;&nbsp;&nbsp;<?php  echo $los;?></div>
				<?php  } } ?>
			</div>
		</div>
		<?php  if($count_lose > 50) { ?><a href="javascript:" onclick="$('#lose').css('height', 'auto');$(this).hide();">显示全部</a><?php  } ?>
		<?php  } ?>
		<div class="form-group" <?php  if($do == 'check') { ?>style="display: none;"<?php  } ?>>
			<div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-10 col-md-10 col-lg-11">
				<a href="<?php  echo url('system/filecheck/check')?>" class="btn btn-primary">开始文件校验</a>
			</div>
		</div>
	</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>

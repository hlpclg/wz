<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class=""><a href="<?php  echo url('system/tools');?>">工具</a></li>
	<li class="active"><a href="<?php  echo url('system/tools');?>">检测系统 BOM</a></li>
</ol>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo url('system/tools');?>">检测系统 BOM</a></li>
</ul>
<div class="main">
	<?php  if($do == 'bom') { ?>
	<form action="" method="post" class="form-horizontal form">
		<h5 class="page-header">检测系统BOM</h5>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">操作说明</label>
			<div class="col-sm-10">
				<div class="help-block">系统系统使用utf-8无bom格式的文件编码方式, 如果使用编辑器修改配置或者查看文件时没有注意编辑器设置将可能在被编辑的文件上附加BOM头, 从而造成系统功能异常. </div>
				<div class="help-block"><strong>注意: 在公众平台添加API地址时重复错误时, 请尝试检测BOM异常. </strong></div>
				<div class="help-block"><strong>注意: 使用云平台功能时重复出现错误提示时, 请尝试检测BOM异常. </strong></div>
				<div class="help-block"><strong>注意: 使用 Windows 系统自带的记事本编辑系统源码可能会造成这样的问题. </strong></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">处理说明</label>
			<div class="col-sm-10">
				<div class="help-block">为保证系统正常运行, 系统不会尝试修复检测出来的错误文件, 检测完成后请自行使用编辑器修改文件编码方式</div>
			</div>
		</div>
		
		<?php  if(isset($bomtree)) { ?>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">检测结果</label>
			<div class="col-sm-10">
			<?php  if(empty($bomtree)) { ?>
				<div class="help-block"><strong>没有检测到存在BOM的异常文件</strong></div>
			<?php  } else { ?>
					<div class="alert alert-info" style="line-height:20px;">
						<?php  if(is_array($bomtree)) { foreach($bomtree as $line) { ?>
						<div><?php  echo $line;?></div>
						<?php  } } ?>
						</div>
				
			<?php  } ?>
				<div class="help-block">为保证系统正常运行, 系统不会尝试修复检测出来的错误文件, 检测完成后请自行使用编辑器修改文件编码方式</div>
			</div>
		</div>
		<?php  } ?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-10 col-md-10 col-lg-11">
				<input name="submit" type="submit" value="检测BOM异常" class="btn btn-primary span3" />
				<?php  if(isset($bomtree) && !empty($bomtree)) { ?><input name="dispose" type="submit" class="btn btn-info " value="处理BOM异常"/><?php  } ?>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>
		</div>
	</form>
	<?php  } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>

<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class=""><a href="<?php  echo url('system/tools');?>">工具</a></li>
	<li class="active"><a href="<?php  echo url('system/tools/scan');?>">木马查杀</a></li>
</ol>
<ul class="nav nav-tabs">
	<li <?php  if($op != 'display' && $op != 'view') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/tools/scan');?>">木马查杀</a></li>
	<li <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/tools/scan', array('op' => 'display'));?>">查杀报告</a></li>
	<?php  if($op == 'view') { ?><li class="active"><a href="javascript:;">查看文件</a></li><?php  } ?>
</ul>
<?php  if($op == 'post') { ?>
<div class="clearfix">
	<form action="" method="post" class="form-horizontal form">
		<h5 class="page-header">木马查杀</h5>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">操作说明</label>
			<div class="col-sm-10">
				<div class="help-block">这里是说明</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">查杀目录</label>
			<div class="col-sm-10" style="">
				<?php  if(is_array($list)) { foreach($list as $li) { ?>
				<label class="checkbox" style="margin-left:15px">
					<?php  if(is_dir($li)) { ?>
					<input type="checkbox" name="dir[]" value="<?php  echo $li;?>"/><i class="fa fa-folder-open"> </i> <?php  echo basename($li);?>
					<?php  } else { ?>
					<input type="checkbox" name="dir[]" value="<?php  echo $li;?>"/><i class="fa fa-file-code-o"> </i> <?php  echo basename($li);?>
					<?php  } ?>
				</label>
				<?php  } } ?>
			</div>
		</div>
<!--
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">文件类型</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="file_type" value="<?php  echo $safe['file_type'];?>"/>
			</div>
		</div>
-->
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">特征函数</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="func" value="<?php  echo $safe['func'];?>"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">特征代码</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="code" value="<?php  echo $safe['code'];?>"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
			<div class="col-sm-10">
				<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"/>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
			</div>
		</div>
	</form>
</div>
<?php  } else if($op == 'display') { ?>
<div class="clearfix">
	<div class="panel panel-default">
		<div class="panel-heading">查杀报告</div>
		<div class="panel-body table-responsive">
			<table class="table">
				<thead>
				<th width="500">文件地址</th>
				<th width="120">特征函数次数</th>
				<th>特征函数</th>
				<th width="120">特征代码次数</th>
				<th>特征代码</th>
				<th width="120">Zend encoded</th>
				<th width="120">危险文件</th>
				<th width="100">操作</th>
				</thead>
				<tbody>
				<?php  if(is_array($badfiles)) { foreach($badfiles as $k => $v) { ?>
					<tr>
						<td><?php  echo $k;?></td>
						<td><?php  echo $v['func_count'];?></td>
						<td><span class="text-danger"><?php  echo $v['func_str'];?></span></td>
						<td><?php  echo $v['code_count'];?></td>
						<td><span class="text-danger"><?php  echo $v['code_str'];?></span></td>
						<td>
							<?php  if(isset($v['zend'])) { ?>
							<span class="label label-danger">Yes</span>
							<?php  } else { ?>
							No
							<?php  } ?>
						</td>
						<td>
							<?php  if(isset($v['danger'])) { ?>
							<span class="label label-danger">Yes</span>
							<?php  } else { ?>
							No
							<?php  } ?>
						</td>
						<td>
							<a href="<?php  echo url('system/tools/scan/', array('op' => 'view', 'file' => authcode($k, 'ENCODE')));?>" title="查看">查看</a>
						</td>
					</tr>
				<?php  } } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php  } else if($op == 'view') { ?>
<div class="clearfix">
	<div class="panel panel-default">
		<div class="panel-heading">查看文件 <span class="text-danger">[<?php  echo $file_tmp;?>]</span></div>
		<div class="panel-body">
			<div style="margin-bottom: 15px">
				<?php  if($info['danger']) { ?>
				<span class="label label-primary">危险文件</span>
				<?php  } ?>
				<?php  if($info['func_count']) { ?>
				<span class="label label-danger">特征函数次数：<?php  echo $info['func_count'];?></span>
				<span class="label label-danger">特征函数：<?php  echo $info['func_str'];?></span>
				<?php  } ?>
				<?php  if($info['code_count']) { ?>
				<span class="label label-warning">特征代码次数：<?php  echo $info['code_count'];?></span>
				<span class="label label-warning">特征代码：<?php  echo $info['code_str'];?></span>
				<?php  } ?>
				<?php  if($info['zend']) { ?>
				<span class="label label-info">Zend encoded</span>
				<?php  } ?>
			</div>
			<textarea name="" id="" cols="30" rows="20" class="form-control"><?php  echo $data;?></textarea>
		</div>
	</div>
	<form action="" class="form-horizontal">
		<div class="form-group">
			<div class="col-sm-10">
				<a href="<?php  echo url('system/tools/scan', array('op' => 'display'))?>" class="btn btn-primary col-lg-1"/>返回</a>
			</div>
		</div>
	</form>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>

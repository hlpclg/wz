<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class="active"><a href="<?php  echo url('system/optimize');?>">性能优化</a></li>
</ol>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo url('system/optimize');?>">性能优化</a></li>
</ul>
<div class="clearfix">
	<div class="alert alert-info">
		<i class="fa fa-info-circle"></i> 启用内存优化功能将会大幅度提升程序性能和服务器的负载能力，内存优化功能需要服务器系统以及PHP扩展模块支持<br>
		<i class="fa fa-info-circle"></i> 目前支持的内存优化接口有 Memcache、eAccelerator<br>
		<i class="fa fa-info-circle"></i> 内存接口的主要设置位于 config.php 当中，您可以通过编辑 config.php 进行高级设置<br>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">当前内存工作状态</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>内存接口</th>
					<th>PHP 扩展环境</th>
					<th>Config 设置</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php  if(is_array($extensions)) { foreach($extensions as $key => $extension) { ?>
				<tr>
					<td><span class="label label-success"><?php  echo $key;?></span></td>
					<td>
						<?php  if($extension['support']) { ?>
							支持
						<?php  } else { ?>
							不支持
						<?php  } ?>
					</td>
					<td>
						<?php  if($extension['status']) { ?>
							已开启
						<?php  } else { ?>
							未开启
						<?php  } ?>
					</td>
					<td>
						<?php  if($extension['status'] && $extension['support'] && $key == 'memcache') { ?>
						<a class="btn btn-danger btn-sm" href="<?php  echo url('system/updatecache');?>" target="_blank">更新缓存</a>
						<?php  } ?>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">数据库读写分离工作状态</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbody>
				<tr>
					<th width="200">读写分离状态</th>
					<td>
						<?php  if($slave['slave_status']) { ?>
						<span class="label label-success">已开启</span>
						<?php  } else { ?>
						<span class="label label-danger">未开启</span>
						<?php  } ?>
					</td>
				<tr>
					<th>session存储方式</th>
					<td>
						<?php  if($extensions['memcache']['status'] && $setting['memcache']['session'] == 1) { ?>
						<span class="label label-danger">memcache</span>
						<?php  } else { ?>
						<span class="label label-success">mysql</span>
						<?php  } ?>
					</td>
				</tr>
				<tr>
					<th>禁用从数据库的数据表</th>
					<td>
						<?php  if(!empty($slave['common']['slave_except_table'])) { ?>
							<?php  if(is_array($slave['common']['slave_except_table'])) { foreach($slave['common']['slave_except_table'] as $row) { ?>
								<?php  echo $row;?>
							<?php  } } ?>
						<?php  } else { ?>
							暂无
						<?php  } ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">远程访问代理设置</div>
		<div class="panel-body table-responsive">
			<table class="table">
				<tbody>
				<tr>
					<th width="200">状态</th>
					<td>
						<?php  if(!empty($setting['proxy']['host'])) { ?>
							<span class="label label-success">已开启</span>
						<?php  } else { ?>
							<span class="label label-danger">未开启</span>
						<?php  } ?>
					</td>
				</tr>
				<?php  if(!empty($setting['proxy']['host'])) { ?>
				<tr>
					<th>远程地址</th>
					<td><?php  echo $setting['proxy']['host'];?> 因安全原因，密码不予显示</td>
				</tr>
				<?php  } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>

<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include template('common/header-base', TEMPLATE_INCLUDEPATH));?>
	<div class="navbar navbar-inverse navbar-static-top" role="navigation" style="position:static;">
		<div class="container-fluid">
			<?php  if(defined('IN_SOLUTION')) { ?>
			<ul class="nav navbar-nav">
				<?php  global $solution,$solutions;?>
				<?php  if($_W['role'] != 'operator') { ?>
				<li><a href="<?php  echo url('home/welcome/ext');?>"><i class="fa fa-reply-all"></i>返回公众号功能管理</a></li>
				<?php  } ?>
				<?php  if(is_array($solutions)) { foreach($solutions as $row) { ?>
				<li<?php  if($row['name'] == $solution['name']) { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/solution', array('m' => $row['name']));?>"><i class="fa fa-cog"></i><?php  echo $row['title'];?></a></li>
				<?php  } } ?>
			</ul>
			<?php  } else { ?>
			<ul class="nav navbar-nav">
				<li><a href="./?refresh"><i class="fa fa-reply-all"></i>返回系统</a></li>
				<?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array('platform', $_W['setting']['permurls']['sections'])) { ?><li<?php  if(FRAME == 'platform') { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/platform');?>"><i class="fa fa-cog"></i>基础设置</a></li><?php  } ?>
				<?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array('site', $_W['setting']['permurls']['sections'])) { ?><li<?php  if(FRAME == 'site') { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/site');?>"><i class="fa fa-life-bouy"></i>微站功能</a></li><?php  } ?>
				<?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array('mc', $_W['setting']['permurls']['sections'])) { ?><li<?php  if(FRAME == 'mc') { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/mc');?>"><i class="fa fa-gift"></i>粉丝营销</a></li><?php  } ?>
				<?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array('setting', $_W['setting']['permurls']['sections'])) { ?><li<?php  if(FRAME == 'setting') { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/setting');?>"><i class="fa fa-umbrella"></i>功能选项</a></li><?php  } ?>
				
                                <?php  $eid =intval($_GPC['eid'])?>
                                <?php  if(!empty($eid)) { ?>
                                   <?php  $binding_module = pdo_fetchcolumn('select module from '.tablename('modules_bindings').' where eid=:eid limit 1',array(':eid'=>$eid))?>
                                <?php  } ?>
                                <?php  if($_GPC['m']=='ewei_shop' || !empty($binding_module)) { ?>
                    <li class="active"><a href="<?php  echo url('home/welcome/ext',array('m'=>'ewei_shop'))?>"><i class="fa fa-shopping-cart"></i><?php  echo $this->module['title']?></a></li>
                    <?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array('ext', $_W['setting']['permurls']['sections'])) { ?><li><a href="<?php  echo url('home/welcome/ext');?>"><i class="fa fa-cubes"></i>其他扩展</a></li><?php  } ?>
    <?php  } else { ?>
    <?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array('ext', $_W['setting']['permurls']['sections'])) { ?><li<?php  if(FRAME == 'ext' ) { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/ext');?>"><i class="fa fa-cubes"></i>其他扩展</a></li><?php  } ?>
    <?php  } ?>
                                
				<?php  if(FRAME == 'solution') { ?><li class="active"><a href="<?php  echo url('home/welcome/solution', array('m' => $m));?>"><i class="fa fa-comments"></i>行业功能 - <?php  echo $solution['title'];?></a></li><?php  } ?>
				 
<!--                                    <?php  $shop = m('common')->getSysset('shop')?>
                                <?php  if(!empty($shop['qq'])) { ?>
		<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $shop['qq'];?>&site=qq&menu=yes" target="_blank"><i class="fa fa-suitcase"></i>联系客服</a></li>
                                <?php  } ?>-->
			</ul>
			<?php  } ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-group"></i><?php  echo $_W['account']['name'];?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php  if($_W['role'] != 'operator') { ?>
						<li><a href="<?php  echo url('account/post', array('uniacid' => $_W['uniacid']));?>" target="_blank"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a></li>
						<?php  } ?>
						<li><a href="<?php  echo url('account/display');?>" target="_blank"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
						<li><a href="<?php  echo url('utility/emulator');?>" target="_blank"><i class="fa fa-mobile fa-fw"></i> 模拟测试</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:185px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-user"></i><?php  echo $_W['user']['username'];?> (<?php  if($_W['role'] == 'founder') { ?>系统管理员<?php  } else if($_W['role'] == 'manager') { ?>公众号管理员<?php  } else { ?>公众号操作员<?php  } ?>) <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php  echo url('user/profile/profile');?>" target="_blank"><i class="fa fa-weixin fa-fw"></i> 我的账号</a></li>
						<?php  if($_W['role'] != 'operator') { ?>
						<li class="divider"></li>
						<li><a href="<?php  echo url('system/welcome');?>" target="_blank"><i class="fa fa-sitemap fa-fw"></i> 系统选项</a></li>
						<li><a href="<?php  echo url('system/welcome');?>" target="_blank"><i class="fa fa-cloud-download fa-fw"></i> 自动更新</a></li>
						<li><a href="<?php  echo url('system/updatecache');?>" target="_blank"><i class="fa fa-refresh fa-fw"></i> 更新缓存</a></li>
						<li class="divider"></li>
						<?php  } ?>
						<li><a href="<?php  echo url('user/logout');?>"><i class="fa fa-sign-out fa-fw"></i> 退出系统</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="container-fluid">
		<?php  if(defined('IN_MESSAGE')) { ?>
            
		<div class="jumbotron clearfix alert alert-<?php  echo $label;?>">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-2">
					<i class="fa fa-5x fa-<?php  if($label=='success') { ?>check-circle<?php  } ?><?php  if($label=='danger') { ?>times-circle<?php  } ?><?php  if($label=='info') { ?>info-circle<?php  } ?><?php  if($label=='warning') { ?>exclamation-triangle<?php  } ?>"></i>
				</div>
			 	<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
					<?php  if(is_array($msg)) { ?>
						<h2>MYSQL 错误：</h2>
					 	<p><?php  echo cutstr($msg['sql'], 300, 1);?></p>
						<p><b><?php  echo $msg['error']['0'];?> <?php  echo $msg['error']['1'];?>：</b><?php  echo $msg['error']['2'];?></p>
					<?php  } else { ?>
					<h2><?php  echo $caption;?></h2>
					<p><?php  echo $msg;?></p>
					<?php  } ?>
					<?php  if($redirect) { ?>
					<p><a href="<?php  echo $redirect;?>">如果你的浏览器没有自动跳转，请点击此链接</a></p>
					<script type="text/javascript">
						setTimeout(function () {
							location.href = "<?php  echo $redirect;?>";
						}, 3000);
					</script>
					<?php  } else { ?>
						<p>[<a href="javascript:history.go(-1);">点击这里返回上一页</a>] &nbsp; [<a href="./?refresh">首页</a>]</p>
					<?php  } ?>
				</div>
		<?php  } else { ?>
		 
		<?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_common', TEMPLATE_INCLUDEPATH)) : (include template('web/_common', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_menu', TEMPLATE_INCLUDEPATH)) : (include template('web/_menu', TEMPLATE_INCLUDEPATH));?>

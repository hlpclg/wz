<?php defined('IN_IA') or exit('Access Denied');?><ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class="active"><?php  if($do == 'installed') { ?>已安装的模块<?php  } else if($do == 'prepared' || $do == 'install') { ?>安装模块<?php  } else if($do == 'designer') { ?>设计新模块<?php  } else if($do == 'permission') { ?>当前模块<?php  } ?></li>
</ol>
<ul class="nav nav-tabs">
	<li<?php  if($do == 'installed') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/installed');?>">已安装的模块</a></li>
	<li<?php  if($do == 'prepared' || $do == 'install') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/prepared');?>">安装模块</a></li>
	<li<?php  if($do == 'designer') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/designer');?>">设计新模块</a></li>
	<li><a href="<?php  echo url('cloud/appstore');?>" target="_blank">查找更多模块</a></li>
	<?php  if($do == 'permission') { ?><li class="active"><a href="<?php  echo url('extension/module/permission', array('id' => $id))?>">当前模块</a></li><?php  } ?>
</ul>

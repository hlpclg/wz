<?php defined('IN_IA') or exit('Access Denied');?><ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class="active"><?php  if($do == 'installed') { ?>已安装的微站风格<?php  } else if($do == 'prepared') { ?>安装微站风格<?php  } else if($do == 'designer') { ?>设计微站风格<?php  } else if($do == 'web') { ?>管理后台风格<?php  } ?></li>
</ol>
<ul class="nav nav-tabs">
	<li<?php  if($do == 'installed') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/theme/installed');?>">已安装的微站风格</a></li>
	<li<?php  if($do == 'prepared' || $do == 'install') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/theme/prepared');?>">安装微站风格</a></li>
	<li<?php  if($do == 'designer') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/theme/designer');?>">设计微站风格</a></li>
	<li><a href="http://bbs.012wz.com/" target="_blank">查找更多微站风格</a></li>
	<li<?php  if($do == 'web') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/theme/web');?>">管理后台风格</a></li>
</ul>

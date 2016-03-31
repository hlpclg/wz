<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class netbuffer_idsearchModuleSite extends WeModuleSite {
	public function doMobileindex() {
		global $_GPC, $_W;
		include $this->template ( 'index' );
	}
}

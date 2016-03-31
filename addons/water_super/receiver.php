<?php
defined('IN_IA') or exit('Access Denied');

class Water_superModuleReceiver extends WeModuleReceiver
{
	public function receive()
	{
		$type = $this->message['type'];
	}
}
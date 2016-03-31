<?php
defined('IN_IA') or exit('Access Denied');

class EmojiUtil
{
	public static function removeEmoji($text)
	{
		$tmpStr = json_encode($text);
		$tmpStr = preg_replace("#(\\\ue[0-9a-f]{3})#ie", '', $tmpStr);
		$text = json_decode($tmpStr);
		return $text;
	}
}
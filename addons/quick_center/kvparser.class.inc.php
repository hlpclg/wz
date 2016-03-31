<?php

class KVParser
{
	public static function decode($text)
	{
		$lines = explode("\r", $text);
		$kv = array();
		foreach ($lines as $line) {
			$tline = trim($line);
			if (!empty($tline)) {
				$parts = explode('|', $tline, 2);
				if (is_array($parts)) {
					$kv[trim($parts[0])] = trim($parts[1]);
				}
			}
		}
		return $kv;
	}

	public static function encode($kv_arr)
	{
		$text = '';
		foreach ($kv_arr as $key => $value) {
			$text .= $key . ' | ' . $value . chr(13);
		}
		return $text;
	}
}
<?php

class TextParser
{
	private function parseFansid($fanid, $text)
	{
		preg_match("/fanid\+(\d+)/i", $text, $matches);
		if (!empty($matches) && count($matches) == 2) {
			$text = preg_replace('/fanid\+\d+/', $fanid + intval($matches[1]), $text);
		} else {
			$text = preg_replace('/fanid/', $fanid, $text);
		}
		return $text;
	}

	public function parseScanQRResponse($fans, $leader, $text)
	{
		$text = preg_replace('/leader/', $leader['nickname'], $text);
		$text = preg_replace('/\[nickname\]/', $fans['nickname'], $text);
		$text = preg_replace('/nickname/', $fans['nickname'], $text);
		$text = $this->parseFansid($fans['fanid'], $text);
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}

	public function parse($fans, $text)
	{
		$text = preg_replace('/\[nickname\]/', $fans['nickname'], $text);
		$text = preg_replace('/nickname/', $fans['nickname'], $text);
		$text = $this->parseFansid($fans['fanid'], $text);
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}

	public function batchParse($pattern_value_map, $text)
	{
		foreach ($pattern_value_map as $pat => $value) {
			$text = preg_replace($pat, $value, $text);
		}
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}

	public function batchParseStr($pattern_value_map, $text)
	{
		foreach ($pattern_value_map as $pat => $value) {
			$text = str_replace($pat, $value, $text);
		}
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}
}
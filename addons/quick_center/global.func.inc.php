<?php
function my_array_fields($arr, $field)
{
	$result = array();
	foreach ($arr as $item) {
		$result[] = $item[$field];
	}
	return $result;
} ?>
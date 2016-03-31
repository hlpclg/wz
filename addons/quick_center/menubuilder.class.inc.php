<?php

class MenuBuilder
{
	static public function build($items)
	{
		$menus = array();
		$menu = array();
		$menu['identifier'] = '__topmenu__';
		$menu['pidentifier'] = '';
		$menu['items'] = array();
		foreach ($items as &$item) {
			if (empty($item['pidentifier'])) {
				$item['pidentifier'] = $menu['identifier'];
				if (!isset($menu['items'][$item['groupid']])) {
					$menu['items'][$item['groupid']] = array();
				}
				$menu['items'][$item['groupid']][] = $item;
			}
		}
		$menus[] = $menu;
		foreach ($items as &$item) {
			$item['children'] = array();
		}
		foreach ($items as &$item) {
			foreach ($items as $child) {
				if ($item['identifier'] == $child['pidentifier']) {
					if (!isset($item['children'][$child['groupid']])) {
						$item['children'][$child['groupid']] = array();
					}
					$item['children'][$child['groupid']][] = $child;
				}
			}
		}
		foreach ($items as $item) {
			if (count($item['children']) > 0) {
				$menu = array();
				$menu['identifier'] = $item['identifier'];
				$menu['pidentifier'] = $item['pidentifier'];
				$menu['items'] = $item['children'];
				$menus[] = $menu;
			}
		}
		return $menus;
	}
}
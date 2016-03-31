<?php defined('IN_IA') or exit('Access Denied');

class Template
{
	private $mn;

	public function __construct($mn)
	{
		$this->mn = strtolower($mn);
	}

	public function account_template()
	{
		$templates = array();
		$path = IA_ROOT . "/addons/quicktemplate/{$this->mn}/";
		if (is_dir($path)) {
			if ($handle = opendir($path)) {
				while (false !== ($modulepath = readdir($handle))) {
					$manifest = $this->ext_template_manifest($modulepath);
					if (!empty($manifest)) {
						$templates[] = $manifest;
					}
				}
			}
		}
		return $templates;
	}

	public function template($filename, $flag = TEMPLATE_INCLUDEPATH)
	{
		global $_W, $_GPC;
		$_W['template'] = $_W['account']['template'];
		$_W['template']['source'] = $_W['account']['template'];
		$source = IA_ROOT . "/addons/quicktemplate/{$this->mn}/{$_W['template']}/{$filename}.html";
		$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$this->mn}/{$filename}.tpl.php";
		if (!empty($_GPC['__template'])) {
			$source = IA_ROOT . "/addons/quicktemplate/{$this->mn}/{$_GPC['__template']}/{$filename}.html";
			if (!is_file($source)) {
				die('invalid debug template ' . $_GPC['__template']);
			}
		}
		if (!is_file($source)) {
			$source = IA_ROOT . "/addons/{$this->mn}/template/mobile/{$filename}.html";
			if (!is_file($source)) {
				$source = "{$_W['template']['source']}/mobile/default/{$this->mn}/{$filename}.html";
				if (!is_file($source)) {
					$source = "{$_W['template']['source']}/mobile/{$_W['account']['template']}/{$filename}.html";
					if (!is_file($source)) {
						$source = "{$_W['template']['source']}/mobile/default/{$filename}.html";
						if (!is_file($source)) {
							exit("Error: template source '{$filename}' is not exist!");
						}
					}
				}
			}
		}
		WeUtility::logging('compile source', array($source, $compile));
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}
		return $compile;
	}

	private function ext_template_manifest($tpl)
	{
		$manifest = array();
		$filename = IA_ROOT . "/addons/quicktemplate/{$this->mn}/" . $tpl . '/manifest.xml';
		if (!file_exists($filename)) {
			return array();
		}
		$xml = str_replace(array('&'), array('&amp;'), file_get_contents($filename));
		$xml = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if (empty($xml)) {
			return array();
		}
		$manifest['name'] = strval($xml->identifie);
		if (empty($manifest['name']) || $manifest['name'] != $tpl) {
			return array();
		}
		$manifest['title'] = strval($xml->title);
		if (empty($manifest['title'])) {
			return array();
		}
		$manifest['description'] = strval($xml->description);
		$manifest['author'] = strval($xml->author);
		$manifest['url'] = strval($xml->url);
		if ($xml->settings->item) {
			foreach ($xml->settings->item as $msg) {
				$attrs = $msg->attributes();
				$manifest['settings'][trim(strval($attrs['variable']))] = trim(strval($attrs['content']));
			}
		}
		$manifest['category'] = array();
		if ($xml->category->item) {
			foreach ($xml->category->item as $item) {
				$manifest['category'][] = $item;
			}
		}
		$manifest['article'] = array();
		if ($xml->article->item) {
			foreach ($xml->article->item as $item) {
				$manifest['article'][] = $item;
			}
		}
		return $manifest;
	}
}
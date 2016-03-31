<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$op =empty($_GPC['op'])? 'display' : $_GPC['op'];
load()->func('communication');
load()->func('file');
$tmpdir =IA_ROOT."/addons/meepo_bbs/".date('ymd');

$versionfile = IA_ROOT."/addons/meepo_bbs/version.php";
if(file_exists($versionfile)){
	require_once $versionfile;
	$version = VERSION;
}else{
	$version = '1.0.0';
}

if(!is_dir($tmpdir)){
	mkdirs($tmpdir);
}

if ($op == 'display'){
	$auth = getAuthSet();
	$versionfile =IA_ROOT . '/addons/meepo_bbs/version.php';
	if (is_file($versionfile)){
		$updatedate =date('Y-m-d H:i', filemtime($versionfile));
	}else{
		$updatedate =date('Y-m-d H:i', filemtime($versionfile));
	}
	
}else if ($op == 'check'){
	set_time_limit(0);
	$auth = getAuthSet();
	global $my_scenfiles;
	my_scandir(IA_ROOT.'/addons/meepo_bbs/');
	$files =array();
	foreach($my_scenfiles as $sf){
		$files[] =array('path' => str_replace(IA_ROOT."/addons/meepo_bbs/","",$sf), 'md5'=> md5_file($sf));
	}
	$files =base64_encode(json_encode($files));

		$files =array();
		if (!empty($content['files'])){
			foreach ($content['files'] as $file){
				$entry =IA_ROOT . "/addons/meepo_bbs/".$file['path'];
				if (!is_file($entry)|| md5_file($entry)!= $file['md5']){
					
					if($file['path'] == '/install.php' || $file['path'] == '/update.php' || $file['path'] == '/manifest.xml' || $file['path'] == '/version.php'){
						
					}else{
						$files[] =array('path'=>$file['path'],'download'=>0);
					}
				}
			}
		}
		$content['files'] = $files;
		file_put_contents($tmpdir."/file.txt",json_encode($content));
		
		die(json_encode(array('result'=>1, 'version'=>$content['version'], 'filecount'=>count($files), 'upgrade'=>!empty($content['upgrade']))));
	
	die(json_encode(array('result' => 0, 'message' => '<p class="label label-success" >'.$content['message']."</p>. <a class='btn btn-default' href=''>刷新!</a>")));
}
else if ($op == 'download'){
	$f =file_get_contents($tmpdir."/file.txt");
	$upgrade =json_decode($f,true);
	$files =$upgrade['files'];
	$auth = getAuthSet();
	$path ="";
	foreach($files as $f){
		if(empty($f['download'])){
			$path =$f['path'];
			break;
		}
	}
	
	if(!empty($path)){
	}else{
		if(!empty($upgrade['upgrade'])){
			$updatefile =IA_ROOT."/addons/meepo_bbs/update.php";
			file_put_contents($updatefile, base64_decode($upgrade['upgrade']));
			require $updatefile;
			if(file_exists($updatefile)){
				@unlink($updatefile);
			}
			$installfile =IA_ROOT."/addons/meepo_bbs/install.php";
			if(file_exists($installfile)){
				@unlink($installfile);
			}
			$xmlfile =IA_ROOT."/addons/meepo_bbs/manifest.xml";
			if(file_exists($xmlfile)){
				@unlink($xmlfile);
			}
			
			file_put_contents(IA_ROOT.'/addons/meepo_bbs/version.php',"<?php if(!defined('VERSION')) {define('VERSION','".$upgrade['version']."');}");
		}
		@rmdirs($tmpdir);
		die(json_encode(array('result'=>2)));
	}
}
include $this->template('download');

function object_to_array($obj)
{
	$_arr= is_object($obj) ? get_object_vars($obj) : $obj;
	foreach((array)$_arr as $key=> $val)
	{
		$val= (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
		$arr[$key] = $val;
	}
	return$arr;
}
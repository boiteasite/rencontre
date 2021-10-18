<?php
if(isset($_POST['d'])) $d = filter_var(strip_tags($_POST['d']),FILTER_SANITIZE_URL);
else $d = '../../../uploads/tmp/';
$out = 0;
$n = preg_replace("/[^a-z0-9_\.]/", "", strtolower($_FILES['fileCsv']['name']));
$t = preg_replace("/[^-+*.a-z0-9\/]/", "", strtolower($_FILES['fileCsv']['type']));
$m = array(
	'application/vnd.ms-excel',
	'text/plain',
	'text/csv',
	'text/tsv',
	'application/zip',
	'application/octet-stream',
	'application/x-zip-compressed',
	'multipart/x-zip'
	);
if(in_array($_FILES['fileCsv']['type'],$m) && strpos($t,'php')===false) {
	$ext = substr(basename($n), strrpos(basename($n),'.')+1);
	// 1. CSV
	if($ext=='csv' && @move_uploaded_file($_FILES['fileCsv']['tmp_name'],$d.'import_rencontre.csv')) {
		$out = 1;
		chmod($d.'import_rencontre.csv',0644);
	}
	// 2. ZIP
	else if($ext=='zip' && @move_uploaded_file($_FILES['fileCsv']['tmp_name'],$d.'import_rencontre.zip')) {
		$zip = new ZipArchive;
		$f = $zip->open($d.'import_rencontre.zip');
		if($f===true) {
			if(!is_dir($d.'zip')) @mkdir($d.'zip');
			$zip->extractTo($d.'zip/');
			$zip->close();
			// 1. Photos
			if($e = rencFindCsv($d.'zip','photo_import')) {
				if(is_dir($d.'photo_import')) rencRmdirR($d.'photo_import');
				@mkdir($d.'photo_import');
				$files = glob($e.'/*.*');
				foreach($files as $file) {
					$go = str_replace($e,$d.'photo_import',$file);
					copy($file, $go);
				}
				// 2. CSV
				if($e = rencFindCsv($d.'zip')) {
					if(file_exists($d.'import_rencontre.csv')) @unlink($d.'import_rencontre.csv');
					copy($e, $d.'import_rencontre.csv');
					$out = 1;
				}
			}
			rencRmdirR($d.'zip');
		}
		@unlink($d.'import_rencontre.zip');
	}
}
sleep(1);
echo '<script language="javascript" type="text/javascript">window.top.window.finUpload('.$out.');</script> ';
//
//
//
function rencRmdirR($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach($files as $file) {
		(is_dir($dir.'/'.$file)) ? rencRmdirR($dir.'/'.$file) : @unlink($dir.'/'.$file);
	}
	return @rmdir($dir);
}
function rencFindCsv($path,$dir='',$r='') {
	// $dir only name, no '/'
	// !dir => find CSV
	if(substr($path,-1)=='/') $path = substr($path,0,-1);
	if(is_dir($path)) {
		$files = array_diff(scandir($path), array('.','..'));
		if(!$dir) foreach($files as $file) { // 
			if(is_file($path.'/'.$file) && substr($file,-4)=='.csv') return $path.'/'.$file; // CSV found !
		}
		foreach($files as $file) if(is_dir($path.'/'.$file)) {
			if($dir && $file==$dir) return $path.'/'.$file; // DIR found !
			else $r = rencFindCsv($path.'/'.$file,$dir,$r);
		}
	}
	return $r;
}
?>

<?php
if(isset($_POST['d'])) $d = filter_var(strip_tags($_POST['d']),FILTER_SANITIZE_URL);
else $d = realpath(__DIR__ . '/../../../uploads/tmp/');
include realpath(__DIR__ . '/../../../../wp-config.php');
if(!defined('NONCE_SALT')) exit;
if(isset($_POST['s']) && preg_match('/^[a-f0-9]{32}$/', $_POST['s'])) $rencToks = $_POST['s'];
else {
	echo '<script language="javascript" type="text/javascript">console.log("Bad Request");window.top.window.finUpload(0);</script>';
	exit;
}
$toks = md5(NONCE_SALT.$rencToks).'.txt';
if(file_exists($d.'md/'.$toks) && filemtime($d.'md/'.$toks)>time()-900) {
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
	if(in_array($_FILES['fileCsv']['type'],$m) && strpos($t.'-','php')===false) {
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
				for($v=0; $v<$zip->numFiles; ++$v) {
					$st = $zip->statIndex($v);
					$n = strrpos($st['name'], '.');
					if($n!==false) {
						if(!in_array(strtolower(substr($st['name'],$n+1)),array('jpg','png','txt','csv'))) {
							echo '<script language="javascript" type="text/javascript">console.log("Bad File type : '.substr($st['name'],$n+1).'");</script>';
							exit;
						}
					}
				}				
				if(!is_dir($d.'zip')) @mkdir($d.'zip');
				$zip->extractTo($d.'zip/');
				$zip->close();
				// 1. Photos
				if($e = rencFindCsv($d.'zip','photo_import')) {
					$typ = array('image/png'=>'png', 'image/jpeg'=>'jpg');
					if(is_dir($d.'photo_import')) rencRmdirR($d.'photo_import');
					@mkdir($d.'photo_import');
					$files = glob($e.'/*.*');
					foreach($files as $file) {
						$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
						$filetype = finfo_file($fileinfo, $file);
						if(in_array($filetype, array_keys($typ))) {
							$go = str_replace($e,$d.'photo_import',$file);
							copy($file, $go);
						}
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
		else echo '<script language="javascript" type="text/javascript">console.log("Bad File type");</script>';
	}
	else echo '<script language="javascript" type="text/javascript">console.log("Bad File type");</script>';
	sleep(1);
	echo '<script language="javascript" type="text/javascript">window.top.window.finUpload('.$out.');</script> ';
}
else echo '<script language="javascript" type="text/javascript">console.log("Bad Request");window.top.window.finUpload(0);</script>';

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

<?php
	if(isset($_POST['d'])) $d = filter_var(strip_tags($_POST['d']),FILTER_SANITIZE_URL);
	else $d = '../../../uploads/tmp/';
	$n = preg_replace("/[^a-z0-9_\.]/", "", strtolower($_FILES['fileCsv']['name']));
	$t = preg_replace("/[^-+*.a-z0-9\/]/", "", strtolower($_FILES['fileCsv']['type']));
	$m = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
	if(!in_array($_FILES['fileCsv']['type'],$m)) die;
	$s = 0;
	$ext = substr(basename($n), strrpos(basename($n),'.')+1);
	if($ext=='csv' && strpos($t,'php')===false && @move_uploaded_file($_FILES['fileCsv']['tmp_name'],$d.'import_rencontre.csv')) {
		$s = 1;
		chmod($d.'import_rencontre.csv',0644);
	}
	sleep(1);
	echo '<script language="javascript" type="text/javascript">window.top.window.finUpload('.$s.');</script> ';
?>

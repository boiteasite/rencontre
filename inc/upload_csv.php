<?php
	if(isset($_POST['d'])) $d = strip_tags($_POST['d']);
	else $d = '../../../uploads/tmp/';
	$s = 0;
	$ext = substr(basename($_FILES['fileCsv']['name']), strrpos(basename($_FILES['fileCsv']['name']),'.')+1);
	if(($ext=='csv' || $ext=='CSV') && strpos($_FILES['fileCsv']['type'],'php')==false && strpos($_FILES['fileCsv']['type'],'PHP')===false && @move_uploaded_file($_FILES['fileCsv']['tmp_name'],$d.'import_rencontre.csv')) {
		$s = 1;
		chmod($d.'import_rencontre.csv',0644);
	}
	sleep(1);
	echo '<script language="javascript" type="text/javascript">window.top.window.finUpload('.$s.');</script> ';
?>

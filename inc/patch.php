<?php
ignore_user_abort(true);
$upl = wp_upload_dir();
//
// PERMANENT WPLANG in db
//
global $locale;
$WPLANG = $wpdb->get_row("SELECT option_id, option_value FROM ".$wpdb->prefix."options WHERE option_name='WPLANG' LIMIT 1");
if(empty($WPLANG) && $locale) $wpdb->insert($wpdb->prefix.'options', array('option_name'=>'WPLANG', 'option_value'=>$locale, 'autoload'=>'yes'));
else if(empty($WPLANG->option_value) && $locale) $wpdb->update($wpdb->prefix.'options', array('option_value'=>$locale, 'autoload'=>'yes'), array('option_name'=>'WPLANG'));
//
// V3.8.2 - Avoid POST and GET mismatch
//
if($tpl=rencTpl('rencontre_message_write.php')) {
	$a = file_get_contents($tpl);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', $a);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', $a);
	file_put_contents($tpl, $a);
}
if($tpl=rencTpl('rencontre_message_inbox.php')) {
	$a = file_get_contents($tpl);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', $a);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', $a);
	file_put_contents($tpl, $a);
}
if($tpl=rencTpl('rencontre_portrait_edit.php')) {
	$a = file_get_contents($tpl);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', $a);
	file_put_contents($tpl, $a);
}
if($tpl=rencTpl('rencontre_account.php')) {
	$a = file_get_contents($tpl);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', $a);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', $a);
	file_put_contents($tpl, $a);
}
if($tpl=rencTpl('rencontre_account_delete.php')) {
	$a = file_get_contents($tpl);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', $a);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'id\'])', $a);
	file_put_contents($tpl, $a);
}
if($tpl=rencTpl('rencontre_portrait_add_photo.php')) {
	$a = file_get_contents($tpl);
	$a = str_replace('<input type="hidden" name="<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', '<input type="hidden" name="P<?php echo (!empty($rencOpt[\'lbl\'][\'renc\'])', $a);
	file_put_contents($tpl, $a);
}
//
// V3.9 - Cron update
//
if(!is_dir($rencDiv['basedir'].'/portrait/')) @mkdir($rencDiv['basedir'].'/portrait/');
if(!is_dir($rencDiv['basedir'].'/portrait/cache/')) @mkdir($rencDiv['basedir'].'/portrait/cache/');
if(file_exists($upl['basedir'].'/portrait/cache/cron_liste/')) rename($upl['basedir'].'/portrait/cache/cron_liste/', $upl['basedir'].'/portrait/cache/cron_list/');
if(file_exists($upl['basedir'].'/portrait/cache/rencontre_cronListe.txt')) unlink($upl['basedir'].'/portrait/cache/rencontre_cronListe.txt');
if(file_exists($upl['basedir'].'/portrait/cache/rencontre_cronListeOn.txt')) unlink($upl['basedir'].'/portrait/cache/rencontre_cronListeOn.txt');
if(file_exists($upl['basedir'].'/portrait/cache/rencontre_cronBis.txt')) unlink($upl['basedir'].'/portrait/cache/rencontre_cronBis.txt');
@file_put_contents($upl['basedir'].'/portrait/cache/rencontre_cron.txt','');
//
// V3.13 - FT and LBS conversion update in DB to set exact value - TINYINT to SMALLINT and x10 to value for precision
//
$typ = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='".$wpdb->prefix."rencontre_users' AND COLUMN_NAME='i_taille' ");
if(strtolower($typ)=='tinyint') {
	$q = $wpdb->query("ALTER TABLE ".$wpdb->prefix."rencontre_users MODIFY COLUMN `i_taille` SMALLINT unsigned NOT NULL");
	if($q) $wpdb->query("UPDATE ".$wpdb->prefix."rencontre_users SET i_taille=i_taille*10");
}
$typ = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='".$wpdb->prefix."rencontre_users' AND COLUMN_NAME='i_poids' ");
if(strtolower($typ)=='tinyint') {
	$q = $wpdb->query("ALTER TABLE ".$wpdb->prefix."rencontre_users MODIFY COLUMN `i_poids` SMALLINT unsigned NOT NULL");
	if($q) $wpdb->query("UPDATE ".$wpdb->prefix."rencontre_users SET i_poids=i_poids*10");
}

if(isset($rencCustom['sizeu']) && $rencCustom['sizeu']==1) $patchsizeu = 1; // SIZE in Feet and Inches - set in CM not exact
if(isset($rencCustom['weightu']) && $rencCustom['weightu']==1) $patchweightu = 1; // WEIGHT in Pounds - set in KG not exact
if(!empty($patchsizeu) || !empty($patchweightu)) {
	if(file_exists(plugin_dir_path(__FILE__).'patch-3-12-5.json')) {
		$j = file_get_contents(plugin_dir_path(__FILE__).'patch-3-12-5.json');
		$q = json_decode($j);
	}
	else {
		$q = $wpdb->get_results("SELECT
				user_id,
				i_taille,
				i_poids
			FROM ".$wpdb->prefix."rencontre_users
			");
		$j = json_encode($q);
		file_put_contents(plugin_dir_path(__FILE__).'patch-3-12-5.json', $j);
	}
	//
	foreach($q as $r) {
		$size = intval(  (round((($r->i_taille/24-1.708)*12),1)) *2.54+.5);
		$weight = intval(  ($r->i_poids*2+10) *.4536+.5);
		$wpdb->update( $wpdb->prefix.'rencontre_users', array('i_taille' => $size, 'i_poids' => $weight), array('user_id' => $r->user_id) );
	}
	if(file_exists(plugin_dir_path(__FILE__).'patch-3-12-5.json')) unlink(plugin_dir_path(__FILE__).'patch-3-12-5.json');
	$rencCustom['sizeu']=2; $rencCustom['weightu']=2;
	//
	$patchRencOpt = get_option('rencontre_options');
	$patchRencOpt['custom']['sizeu'] = 2; $rencCustom['sizeu'] = 2;
	$patchRencOpt['custom']['weightu'] = 2; $rencCustom['weightu'] = 2;
	update_option('rencontre_options',$patchRencOpt);
}
//
// *******************************************************************************************************************

//
// PERMANENT : Update DBIP
//
$versionDBIP = "202409"; // new version with this update
$a = get_option('rencontre_dbip');
$b = $wpdb->get_var("SELECT ip_end FROM ".$wpdb->base_prefix."rencontre_dbip LIMIT 1"); // empty ?
if(file_exists(dirname(__FILE__).'/dbip-country.csv.gz') && (!$a || intval($a)<intval($versionDBIP) || !$b)) {
	// 1. Extract in array
//	$t = gzfile(dirname(__FILE__).'/dbip-country.csv.gz'); // OK but ERROR with big file
	if(file_exists(dirname(__FILE__).'/dbip-country.csv')) unlink(dirname(__FILE__).'/dbip-country.csv');
	$gzp = gzopen(dirname(__FILE__).'/dbip-country.csv.gz', "rb");
	$fp = fopen(dirname(__FILE__).'/dbip-country.csv', "w");
	while(!gzeof($gzp)) {
		$s = gzread($gzp, 4096);
		fwrite($fp, $s, strlen($s));
	}
	gzclose($gzp);
	fclose($fp);
	$f = file_get_contents(dirname(__FILE__).'/dbip-country.csv');
	$fp = fopen(dirname(__FILE__).'/dbip-country.csv', "r");
	// 2. Empty DB
	$wpdb->query("TRUNCATE TABLE ".$wpdb->base_prefix."rencontre_dbip");
			//	$q = "LOAD DATA LOCAL INFILE '".dirname(__FILE__)."/dbip-country.csv' 
			//		INTO TABLE ".$wpdb->base_prefix."rencontre_dbip 
			//		FIELDS TERMINATED BY ',' 
			//		ENCLOSED BY '\"' 
			//		LINES TERMINATED BY '".PHP_EOL."' 
			//		IGNORE 1 LINES";
			//	$wpdb->query($q);
	// 3. Update DB
	$sql = array();
	$c = 0;
	while(($tt = fgets($fp,4096))!==false) {
		if(strlen($tt)>50) continue;
		$a = explode(',', str_replace('"', '', $tt));
		if(isset($a[2])) {
			if(preg_match('/^[0-9\.]+$/', $a[0])) {
				++$c;
				$a0=''; $a1='';
				$b = explode('.', $a[0]);
				foreach($b as $r) {
					if(strlen($r)==1 && $a0) $a0.='00'.$r;
					else if (strlen($r)==2 && $a0) $a0.='0'.$r;
					else if($r!='0') $a0.=$r;
				}
				$b = explode('.', $a[1]);
				foreach($b as $r) {
					if(strlen($r)==1 && $a1) $a1.='00'.$r;
					else if (strlen($r)==2 && $a1) $a1.='0'.$r;
					else if($r!='0') $a1.=$r;
				}
				if($a0 && $a1) $sql[] = '("'.$a0.'", "'.$a1.'", "'.substr($a[2],0,2).'")';
				if($c>20000) {
					if(count($sql)) $wpdb->query('INSERT IGNORE INTO '.$wpdb->base_prefix.'rencontre_dbip (ip_start, ip_end, country) VALUES '.implode(',', $sql));
					$sql = array();
					$c = 0;
				}
			}
		}
	}
	fclose($fp);
	if(count($sql)) $wpdb->query('INSERT IGNORE INTO '.$wpdb->base_prefix.'rencontre_dbip (ip_start, ip_end, country) VALUES '.implode(',', $sql));
	// 4. Save and clean
	update_option('rencontre_dbip', $versionDBIP);
	@copy(dirname(__FILE__).'/dbip-country.csv.gz', dirname(__FILE__).'/dbip-country_off.csv.gz');
	@unlink(dirname(__FILE__).'/dbip-country.csv.gz');
	if(file_exists(dirname(__FILE__).'/dbip-country.csv')) unlink(dirname(__FILE__).'/dbip-country.csv');
}
//
// END PATCH - PATCH OFF
//
@copy(dirname(__FILE__).'/patch.php', dirname(__FILE__).'/patch_off.php');
@unlink(dirname(__FILE__).'/patch.php');
?>

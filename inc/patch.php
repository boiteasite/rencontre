<?php
$upl = wp_upload_dir();
//
// PERMANENT WPLANG in db
//
global $locale;
$WPLANG = $wpdb->get_row("SELECT option_id, option_value FROM ".$wpdb->prefix."options WHERE option_name='WPLANG' LIMIT 1");
if(empty($WPLANG) && $locale) $wpdb->insert($wpdb->prefix.'options', array('option_name'=>'WPLANG', 'option_value'=>$locale, 'autoload'=>'yes'));
else if(empty($WPLANG->option_value) && $locale) $wpdb->update($wpdb->prefix.'options', array('option_value'=>$locale, 'autoload'=>'yes'), array('option_name'=>'WPLANG'));
//
// V2.1
//
	// RAZ fiche libre (CSS)
	if(file_exists($upl['basedir'].'/portrait/cache/cache_portraits_accueil.html')) @unlink($upl['basedir'].'/portrait/cache/cache_portraits_accueil.html');
	if(file_exists($upl['basedir'].'/portrait/cache/cache_portraits_accueil1.html')) @unlink($upl['basedir'].'/portrait/cache/cache_portraits_accueil1.html');
	if(file_exists($upl['basedir'].'/portrait/cache/cache_portraits_accueilgirl.html')) @unlink($upl['basedir'].'/portrait/cache/cache_portraits_accueilgirl.html');
	if(file_exists($upl['basedir'].'/portrait/cache/cache_portraits_accueilmen.html')) @unlink($upl['basedir'].'/portrait/cache/cache_portraits_accueilmen.html');
	//
	// REPLACE SHORTCODE IN PAGE
	$a = array(
		'[rencontre_libre_mix]'=>'[rencontre_libre gen=mix]',
		'[rencontre_libre_girl]'=>'[rencontre_libre gen=girl]',
		'[rencontre_libre_men]'=>'[rencontre_libre gen=men]',
		'[rencontre_nbmembre_girl]'=>'[rencontre_nbmembre gen=girl]',
		'[rencontre_nbmembre_men]'=>'[rencontre_nbmembre gen=men]',
		'[rencontre_nbmembre_girlphoto]'=>'[rencontre_nbmembre gen=girl ph=1]',
		'[rencontre_nbmembre_menphoto]'=>'[rencontre_nbmembre gen=men ph=1]');
	$ps = get_posts(array('post_type'=>'page','numberposts'=>-1));
	foreach($ps as $p) {
		$b = 0;
		foreach($a as $k=>$v) {
			if(strpos($p->post_content.'-',$k)!==false) {
				$p->post_content = str_replace($k, $v, $p->post_content);
				$b = 1;
				var_dump($p);
			}
		}
		if($b) $wpdb->update($wpdb->prefix.'posts', array('post_content'=>$p->post_content), array('ID'=>$p->ID));
	}
	//
	// PRISON ADD TYPE
	$q = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix."rencontre_prison LIKE 'i_type' ");
	if(!$q) $wpdb->query("ALTER TABLE ".$wpdb->prefix."rencontre_prison ADD `i_type` tinyint unsigned NOT NULL AFTER `c_ip`");
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
// *******************************************************************************************************************

//
// PERMANENT : Update DBIP
//
$versionDBIP = "202310"; // new version with this update
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

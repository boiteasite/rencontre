<?php
/*
 * Clean-up when Rencontre is uninstalled from a site.
*/
if(!defined('WP_UNINSTALL_PLUGIN')) die;
//
$rencOpt = get_option('rencontre_options');
if(!empty($rencOpt['uni'])) {
	//
	delete_option('rencontre_options');
	delete_site_option('rencontre_options'); // multisite
	//
	global $wpdb;
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_dbip');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_profil');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_users');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_users_profil');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_liste');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_msg');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'rencontre_prison');
	//
	$upload_dir = wp_upload_dir();
	if(is_dir($upload_dir['basedir'].'/portrait')) rencontre_rmdirR($upload_dir['basedir'].'/portrait');
	if(is_dir($upload_dir['basedir'].'/session')) rencontre_rmdirR($upload_dir['basedir'].'/session');
	if(is_dir($upload_dir['basedir'].'/tchat')) rencontre_rmdirR($upload_dir['basedir'].'/tchat');
}
//
// *********************************
//
function rencontre_rmdirR($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach($files as $file) {
		(is_dir("$dir/$file")) ? rencontre_rmdirR("$dir/$file") : unlink("$dir/$file");
	}
	return rmdir($dir);
}

?>

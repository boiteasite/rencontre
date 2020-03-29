<?php
$rencOpt = get_option('rencontre_options');
if(empty($rencOpt) || !is_array($rencOpt)) $rencOpt = array();
// Filtres / Action : General
add_filter('show_admin_bar' , 'rencAdminBar'); // Visualisation barre admin
add_action('init', 'rencPreventAdminAccess', 0); // bloque acces au tableau de bord
add_action('init', 'rencInLine', 1); // session
add_action('wp_logout', 'rencOutLine'); // session
add_filter('login_redirect', 'rencLogRedir', 10, 3); // redirection after login
add_filter('wp_authenticate_user', 'rencUserLogin',10,2);
add_action('admin_bar_menu', 'f_admin_menu', 999);
if(!empty($rencOpt['avatar'])) add_filter('get_avatar', 'rencAvatar', 1, 5);
if(!empty($rencOpt['fastreg'])) {
	add_action('register_form', 'rencFastreg_form');
	add_filter('registration_errors', 'rencFastreg_errors', 96, 3);
	add_action('user_register', 'rencFastreg', 96, 1);
}
function f_shortcode_rencontre_libre($a) {if(!is_user_logged_in()) return Rencontre::f_ficheLibre($a,1);} // shortcode : [rencontre_libre gen=mix/girl/men]
function f_shortcode_rencontre_nbmembre($a) {return Rencontre::f_nbMembre($a);} // shortcode : [rencontre_nbmembre gen=girl/men ph=1]
function f_shortcode_rencontre_search($a) {if(!is_user_logged_in()) return Rencontre::f_rencontreSearch(1,$a);} // shortcode : [rencontre_search nb=8] - nb:number of result
function f_shortcode_rencontre() { // shortcode : [rencontre]
	if(is_user_logged_in()) {
		if(!class_exists('RencontreWidget')) return '';
		$renc = new RencontreWidget;
		ob_start();
		$renc->widget(0,0);
		$a = ob_get_contents();
		ob_end_clean();
		return $a;
	}
}
function f_shortcode_rencontre_login() {return Rencontre::f_login(0,1);} // shortcode : [rencontre_login]
function f_shortcode_rencontre_loginFB() {return Rencontre::f_loginFB(1);} // shortcode : [rencontre_loginFB]
function f_shortcode_rencontre_imreg($a) {if(!is_user_logged_in()) return Rencontre::f_rencontreImgReg($a);} // shortcode : [rencontre_imgreg title= selector='.site-header .wp-custom-header img' left=20 top=20] - left & top in purcent
if(isset($_COOKIE['lang']) && strlen($_COOKIE['lang'])==5) add_filter('locale', 'set_locale2'); function set_locale2() { return $_COOKIE['lang']; }
// Mail
//add_filter ('retrieve_password_message', 'retrieve_password_message2', 10, 2);
// AJAX
add_action('wp_ajax_regionBDD', 'f_regionBDD'); // AJAX - retour des regions dans le select
add_action('wp_ajax_voirMsg', 'f_voirMsg');
add_action('wp_ajax_testPass', 'rencTestPass'); // changement du mot de passe
add_action('wp_ajax_fbok', 'rencFbok'); add_action('wp_ajax_nopriv_fbok', 'rencFbok'); // connexion via FB
add_action('wp_ajax_miniPortrait2', 'f_miniPortrait2');
add_action('wp_ajax_fastregMail', 'f_fastregMail');
//add_action('wp_ajax_addCountSearch', 'f_addCountSearch'); // +1 dans action search si meme jour
add_action('wp_ajax_gpsnavigator', 'rencGpsNavigator');
function f_voirMsg() {
	$Pho = (isset($_POST['ho'])?rencSanit($_POST['ho'],'int'):false);
	$Pidmsg = rencSanit($_POST['idmsg'],'int');
	$Palias = rencSanit($_POST['alias'],'words');
	RencontreWidget::f_voirMsg($Pidmsg,$Palias,$Pho);
}
function f_miniPortrait2() {
	global $rencOpt;
	$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
	$Pid = rencSanit($_POST[$Lid],'int');
	RencontreWidget::f_miniPortrait2($Pid);
}
function f_fastregMail() {
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	$u = wp_get_current_user();
	rencFastreg_email($u,1);
}
if(is_admin()) { // Check for an administrative interface page
	add_action('wp_ajax_iso', 'rencontreIso'); // Test si le code ISO est libre (Partie ADMIN)
	add_action('wp_ajax_drap', 'rencontreDrap'); // SELECT avec la liste des fichiers drapeaux (Partie ADMIN)
	add_action('wp_ajax_exportCsv', 'f_exportCsv'); // Export CSV (Partie ADMIN)
	add_action('wp_ajax_importCsv', 'f_importCsv'); // Import CSV (Partie ADMIN)
	add_action('wp_ajax_updown', 'f_rencUpDown'); // Modif Profil : move Up / Down / Supp
	add_action('wp_ajax_profilA', 'f_rencProfil'); // Modif Profil : plus & edit
	add_action('wp_ajax_stat', 'f_rencStat'); // Members - Registration statistics
	add_action('wp_ajax_newMember', 'f_newMember'); // Add new Rencontre Members from WP Users - Members Tab
	add_action('wp_ajax_regeneratePhotos', 'rencRegeneratePhotos'); // Regenerate all users photos.
	add_filter('wp_privacy_personal_data_exporters', 'rencGDPRExport', 10); // Include Data in the GDPR WP Export
	add_action('wp_privacy_personal_data_export_file_created', 'rencGDPRExportimg', 10, 4);
}
// CRON
add_action('init', 'f_cron', 21);
function f_cron() {
	// Filters after "init"
	add_shortcode('rencontre_libre', 'f_shortcode_rencontre_libre');
	add_shortcode('rencontre_nbmembre', 'f_shortcode_rencontre_nbmembre');
	add_shortcode('rencontre_search', 'f_shortcode_rencontre_search');
	add_shortcode('rencontre_login', 'f_shortcode_rencontre_login');
	add_shortcode('rencontre_loginFB', 'f_shortcode_rencontre_loginFB');
	add_shortcode('rencontre_imgreg', 'f_shortcode_rencontre_imreg'); // [rencontre_imgreg title= selector= left= top=]
	add_shortcode('rencontre', 'f_shortcode_rencontre');
	add_action('wp_enqueue_scripts', 'rencCssJs', 1); // add rencontre.css in top header & rencontre.js in footer if needed
	if(function_exists('wpGeonames_shortcode') && is_user_logged_in()) {
		add_action('wpGeonames_location_taxonomy_tpl', 'renc_wpGeonames_tpl');
		remove_action('wp_ajax_geoDataRegion', 'wpGeonames_ajax_geoDataRegion');
		add_action('wp_ajax_geoDataRegion', 'renc_ajax_geoDataRegionHook');
		add_action('wp_ajax_rencGeoDataCity', 'renc_ajax_rencGeoDataCity'); // Search display city list
	}
	add_filter('wp_setup_nav_menu_item', 'rencMetaMenuItem', 1);
	//if(!is_user_logged_in()) add_filter('wp_get_nav_menu_items', 'rencHideMenu', null, 3); // hide rencontre items in WP menu
	global $rencDiv;
	if(!is_dir($rencDiv['basedir'].'/portrait/')) mkdir($rencDiv['basedir'].'/portrait/');
	if(!is_dir($rencDiv['basedir'].'/portrait/cache/')) mkdir($rencDiv['basedir'].'/portrait/cache/');
	if(!is_dir($rencDiv['basedir'].'/portrait/cache/cron_liste/')) mkdir($rencDiv['basedir'].'/portrait/cache/cron_liste/');
	$d = $rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt';
	$d1 = $rencDiv['basedir'].'/portrait/cache/rencontre_cronOn.txt';
	$d2 = $rencDiv['basedir'].'/portrait/cache/rencontre_cronListe.txt'; if(!file_exists($d2)) {$t=@fopen($d2,'w'); @fwrite($t,'0'); @fclose($t);}
	$d3 = $rencDiv['basedir'].'/portrait/cache/rencontre_cronListeOn.txt';
	$d4 = $rencDiv['basedir'].'/portrait/cache/rencontre_cronBis.txt';
	global $rencOpt;
	$t = current_time('timestamp',0); // timestamp local
	$gmt = time(); // timestamp GMT
	$hcron = (isset($rencOpt['hcron'])?$rencOpt['hcron']+0:3);
	$u1 = date("G",$t-3600*$hcron); // heure actuelle(UTC) - heure creuse (+24 si <0) ; ex il est 15h23Z (15), Hcreuse:4h (4) => $u = 15 - 4 = 11;
	// u1 progresse 21, 22, 23 puis 0 lorsqu'il est l'heure creuse (donc<12). Il reste alors 12 heures pour qu"un visiteur provoque le CRON.
	if((!file_exists($d) || $gmt>filemtime($d)+43000) && $u1<12) {
		if(!file_exists($d1) || $gmt>filemtime($d1)+120) {
			$t=fopen($d1, 'w'); fclose($t); // CRON une seule fois
			clearstatcache();
			f_cron_on(0);
		}
	}
	else if(file_exists($d4) && $u1<12 && $gmt>filemtime($d)+3661) {
		if(!file_exists($d1) || $gmt>filemtime($d1)+120) {
			$t=fopen($d1, 'w'); fclose($t); // CRON BIS une seule fois, une heure apres CRON
			clearstatcache();
			f_cron_on(1); // second passage (travail sur deux passages)
		}
	}
	else if(file_exists($d) && $gmt>filemtime($d)+3661 && $gmt>filemtime($d2)+3661 && $u1<23 && (!file_exists($d3) || $gmt>filemtime($d3)+120)) {
		$t=fopen($d3, 'w'); fclose($t); // CRON LISTE une seule fois
		clearstatcache();
		f_cron_liste($d2); // MSG ACTION
	}
	 //f_cron_on(); // mode force
	 //f_cron_liste($d2); // mode force
}
//
function set_html_content_type(){ return 'text/html'; }
//
function f_cron_on($cronBis=0) {
	// NETTOYAGE QUOTIDIEN
	global $wpdb; global $rencOpt; global $rencDiv; global $rencCustom;
	$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
	$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
	$mailPerline = (!empty($rencOpt['nbr']['mailUserPerLine'])?$rencOpt['nbr']['mailUserPerLine']:2) - 1;
	include(dirname(__FILE__).'/rencontre_color.php');
	$bt0 = (!empty($rencCustom['mebt'])?$rencCustom['mebt']:$w3rencDef['mebt']);
	$bt1 = (!empty($rencCustom['mebo'])?$rencCustom['mebo']:$w3rencDef['mebo']);
	$oo = ''; $ii = '';
	if(function_exists('openssl_encrypt')) {
		$iv = openssl_random_pseudo_bytes(16);
		$ii = base64_encode($iv);
	}
	$bn = get_bloginfo('name');
	$s1 = ""; // (synthese admin)
	$cm = 0; // compteur de mail
	if(!$cronBis) {
		// 1. Efface les _transient dans wp_option
		$wpdb->query("DELETE FROM ".$wpdb->prefix."options WHERE option_name like '\_transient\_namespace\_%' OR option_name like '\_transient\_timeout\_namespace\_%' ");
		// 2. Suppression fichiers anciens dans UPLOADS/PORTRAIT/LIBRE/ : > 2.9 jours
		if(!is_dir($rencDiv['basedir'].'/portrait/libre/')) @mkdir($rencDiv['basedir'].'/portrait/libre/');
		else {
			$tab = array(); $d = $rencDiv['basedir'].'/portrait/libre/';
			if($dh = opendir($d)) {
				while(($file = readdir($dh))!==false) { if($file!='.' && $file!='..') $tab[]=$d.$file; }
				closedir($dh);
				if($tab!=array()) foreach($tab as $r) {
					if(file_exists($r) && filemtime($r)<time()-248400) unlink($r); // 69 heures
				}
			}
		}
		// 3. Supprime le cache portraits page d'accueil. Remise a jour a la premiere visite (fiches libre)
		renc_clear_cache_portrait(1);
		// 4. Suppression des utilisateur sans compte rencontre fini
		$t = (!empty($rencOpt['nbr']['delNotConfirmed'])?intval($rencOpt['nbr']['delNotConfirmed']):60); // Hours
		$d = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d"),date("Y"))-(3600*$t));
		if(!empty($rencOpt['fastreg'])) { // email not confirmed ?
			$q = $wpdb->get_results("SELECT 
					U.ID, 
					R.i_photo 
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				INNER JOIN ".$wpdb->base_prefix."usermeta M 
					ON M.user_id=U.ID
				WHERE 
					(M.meta_key='rencontre_confirm_email' or R.i_sex='98')
					and U.user_registered<'".$d."' 
				");
			if($q) foreach($q as $r) {
				if($r->i_photo) f_suppImgAll($r->ID);
				$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$r->ID));
				$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$r->ID));
				$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$r->ID));
				$wpdb->delete($wpdb->base_prefix.'users', array('ID'=>$r->ID));
			}
		}
		if(!empty($rencOpt['rol'])) { // Uniquement les comptes Rencontre non finis. Utiliseur maintenue
			$q = $wpdb->get_results("SELECT
					R.user_id,
					R.i_photo
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				WHERE
					(R.c_ip='' or R.i_sex='98')
					and U.user_registered<'".$d."' ");
			if($q) foreach($q as $r) {
				if($r->i_photo) RencontreWidget::suppImgAll($r->user_id,false);
				$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$r->user_id));
				$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$r->user_id));
				if(empty($rencOpt['rolu'])) {
					$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$r->user_id));
					$wpdb->delete($wpdb->base_prefix.'users', array('ID'=>$r->user_id));
				}
			}
		}
		else if(!is_multisite()) { // general case : rol unchecked
			$q = $wpdb->get_results("SELECT
					U.ID
				FROM
					".$wpdb->base_prefix."users U
				LEFT OUTER JOIN
					".$wpdb->prefix."rencontre_users R
				ON
					U.ID=R.user_id
				WHERE
					R.user_id IS NULL
					or R.c_ip=''
					or R.i_sex='98'");
			if($q) foreach($q as $r) {
				$s = $wpdb->get_var("SELECT
						ID
					FROM
						".$wpdb->base_prefix."users
					WHERE
						ID='".$r->ID."' and
						user_registered<'".$d."'
					LIMIT 1");
				if($s && !user_can($s,'edit_posts')) {
					$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$r->ID));
					$wpdb->delete($wpdb->base_prefix.'users', array('ID'=>$r->ID));
				}
			}
		}
		// 5. Delete the users in rencontre and not in WP
		$q = $wpdb->get_results("SELECT
				R.user_id,
				R.i_photo
			FROM
				".$wpdb->prefix."rencontre_users R
			WHERE
				R.user_id NOT IN (SELECT U.ID FROM ".$wpdb->base_prefix."users U) ");
		if($q) foreach($q as $r) {
			if(!empty($r->user_id)) {
				if($r->i_photo) f_suppImgAll($r->user_id);
				$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$r->user_id));
				$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$r->user_id));
			}
		}
		// 6. Delete inactive users
		if(!empty($rencOpt['dead'])) {
			$q = $wpdb->get_results("SELECT R.user_id, R.i_photo, R.d_session
				FROM ".$wpdb->prefix."rencontre_users R
				WHERE R.d_session<(NOW() - INTERVAL ".$rencOpt['dead']." YEAR)
				");
			if($q) foreach($q as $r) {
				if(!empty($r->user_id)) {
					if($r->d_session && $r->d_session!='0000-00-00 00:00:00') {
						if($r->i_photo) f_suppImgAll($r->user_id);
						$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$r->user_id));
						$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$r->user_id));
						if(empty($rencOpt['rol']) || empty($rencOpt['rolu'])) {
							$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$r->user_id));
							$wpdb->delete($wpdb->base_prefix.'users', array('ID'=>$r->user_id));
						}
					}
					else $wpdb->update($wpdb->prefix.'rencontre_users', array('d_session'=>current_time("mysql")), array('user_id'=>$r->user_id));
				}
			}
		}
		// 7. Fastreg décoché en admin : recadrer les nouveaux membres en fastreg - cas particulier
		if(isset($rencOpt['fastreg']) && !$rencOpt['fastreg']) {
			$q = $wpdb->get_results("SELECT U.ID FROM ".$wpdb->base_prefix."users U, ".$wpdb->prefix."rencontre_users R WHERE R.user_id=U.ID and R.i_status=4 ");
			if($q) foreach($q as $r) {
				$p = md5(mt_rand());
				$wpdb->update($wpdb->base_prefix.'users', array('user_pass'=>$p), array('ID'=>$r->ID)); // confirmation email par demande de nouveau password
				$wpdb->update($wpdb->prefix.'rencontre_users', array('c_ip'=>'', 'i_status'=>0), array('user_id'=>$r->ID)); // procedure inscription 1 a 4
			}
		}
		// 8. Mail de relance (uniquement enregistrement classique)
		if(empty($rencOpt['fastreg'])) {
			$q = $wpdb->get_results("SELECT
					U.ID,
					U.user_login,
					U.user_email,
					U.user_registered
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				WHERE
					R.c_ip=''
				");
			$o = '';
			if($q) foreach($q as $r) {
				if(function_exists('openssl_encrypt')) $oo = base64_encode(openssl_encrypt($r->ID.'|'.$r->user_login.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
				$s = "<div style='text-align:left;margin:5px 5px 5px 10px;font-size:13px;font-family:\"Helvetica Neue\",Helvetica;'>".__('Hello','rencontre')." ".$r->user_login.","."\n";
				if(isset($rencCustom['relanc']) && isset($rencCustom['relancText']) && $rencCustom['relanc']) $s .= "<br />".nl2br(stripslashes($rencCustom['relancText']))."\n";
				else {
					$s .= '<p>'.__('You registered on our website but you did not complete the procedure. You\'ll miss a date. That\'s too bad.','rencontre').'</p>';
					$s .= '<p>'.__('Thus take two minutes to finish the registration. You should not be disappointed.','rencontre').'</p>';
					$s .= '<p>'.__('Regards,','rencontre').'</p>';
				}
				$s .= '<a href="'.htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home']).'?'.$Loo.'='.urlencode($oo).'&'.$Lii.'='.urlencode($ii)).'">'.__('Login','rencontre').'</a>';
				$s .= "</div>\n";
				$he = array();
				if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
					$he[] = 'From: '.$bn.' <'.$rencDiv['admin_email'].'>';
					$he[] = 'Content-type: text/html; charset=UTF-8';
					$s = '<html><head></head><body>'.$s.'</body></html>';
				}
				@wp_mail($r->user_email, $bn." - ".__('Registration','rencontre'), $s, $he);
				++$cm;
			}
		}
		// 9. Suppression fichiers anciens dans UPLOADS/SESSION/ et UPLOADS/TCHAT/ et des exports CSV UPLOADS/TMP
		if(!is_dir($rencDiv['basedir'].'/session/')) mkdir($rencDiv['basedir'].'/session/');
		else {
			$tab = array(); $d = $rencDiv['basedir'].'/session/';
			if($dh=opendir($d)) {
				while (($file = readdir($dh))!==false) { if($file!='.' && $file!='..') $tab[] = $d.$file; }
				closedir($dh);
				if($tab!=array()) foreach($tab as $r){if(filemtime($r)<time()-1296000) unlink($r);} // 15 jours
			}
		}
		if(!is_dir($rencDiv['basedir'].'/tchat/')) mkdir($rencDiv['basedir'].'/tchat/');
		else {
			$tab = array(); $d = $rencDiv['basedir'].'/tchat/';
			if($dh=opendir($d)) {
				while (($file = readdir($dh))!==false) { if($file!='.' && $file!='..') $tab[] = $d.$file; }
				closedir($dh);
				if($tab!=array()) foreach($tab as $r){if(filemtime($r)<time()-86400) unlink($r);} // 24 heures
			}
		}
		if(is_dir($rencDiv['basedir'].'/tmp/')) {
			$a = array();
			if($h=opendir($rencDiv['basedir']."/tmp/")) {
				while (($file = readdir($h))!==false) {
					$ext = explode('.',$file);
					$ext = $ext[count($ext)-1];
					if($ext=='csv' && $file!='.' && $file!='..' && strpos($file,"rencontre")!==false) $a[] = $rencDiv['basedir']."/tmp/".$file;
				}
				closedir($h);
			}
			// ************************
			if(is_array($a)) array_map('unlink', $a);
		}
		// 10. Sortie de prison
		$free = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-(isset($rencOpt['prison'])?$rencOpt['prison']:7), date("Y")));
		$wpdb->query("DELETE FROM ".$wpdb->prefix."rencontre_prison WHERE d_prison<'".$free."' ");
		// 11. anniversaire du jour
		if(!empty($rencOpt['mailanniv'])) {
			$q = $wpdb->get_results("SELECT
					U.ID,
					U.user_login,
					U.user_email,
					R.user_id 
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID 
				WHERE 
					R.d_naissance LIKE '%".current_time('m-d')."' 
					".(!empty($rencOpt['mailph'])?'and R.i_photo>0':'')."
				LIMIT ".floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.1)) );
			foreach($q as $r) {
				$s = "<div style='font-family:\"Helvetica Neue\",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;'>".__('Hello','rencontre')." ".$r->user_login.","."\n";
				if(!empty($rencOpt['textanniv']) && strlen($rencOpt['textanniv'])>10) $s .= "<br />".nl2br(stripslashes($rencOpt['textanniv']))."\n";
				else $s .= "<br />".__('Happy Birthday','rencontre')."&nbsp;".$r->user_login." !\n";
				$s .= "</div>\n";
				$he = array();
				if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
					$he[] = 'From: '.$bn.' <'.$rencDiv['admin_email'].'>';
					$he[] = 'Content-type: text/html; charset=UTF-8';
					$s = '<html><head></head><body>'.$s.'</body></html>';
				}
				@wp_mail($r->user_email, $bn." - ".__('Happy Birthday','rencontre'), $s, $he);
				++$cm;
				$s1 .= $s;
			}
		}
		// 12. Efface une fois par semaine les statistiques du nombre de mail par heure
		if(current_time("N")=="1") { // le lundi
			$t=@fopen($rencDiv['basedir'].'/portrait/cache/rencontre_cronListe.txt','w'); @fwrite($t,'0'); @fclose($t);
			$t=@fopen($rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt','w'); @fwrite($t,$cm); @fclose($t);
		}
	}
	// 13. Mail vers les membres et nettoyage des comptes actions (suppression comptes inexistants)
	$hcron = (isset($rencOpt['hcron'])?$rencOpt['hcron']+0:3);
	$j = floor((floor((time()-3600*$hcron)/86400)/60 - floor(floor((time()-3600*$hcron)/86400)/60)) * 60 +.00001); // horloge de jour de 0 à 59 (temps unix) - ex : aujourd'hui -> 4 ; ajout de hcron pour rester le meme jour dans la plage +12h
	if(isset($rencOpt['mailmois']) && $rencOpt['mailmois']==2) {
		$j0 = floor(($j/15-floor($j/15)) * 15 + .00001); // horloge de jour de 0 a 14
		if(!$cronBis) { // CRON (H)
			$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.85)); // 85% du max - heure creuse - 15% restant pour inscription nouveaux membres et anniv
			$j1=$j0+15;
		}
		else { // CRON BIS (H+1)
			$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.95)); // 95% du max - heure creuse - 5% restant pour inscription nouveaux membres
			$j2=$j0+30; $j3=$j0+45;
		}
	}
	else if(isset($rencOpt['mailmois']) && $rencOpt['mailmois']==3) {
		$j0 = floor(($j/7-floor($j/7)) * 7 + .00001); // horloge de jour de 0 a 6
		if(!$cronBis) { // CRON (H)
			$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.85)); // 85% du max - heure creuse - 15% restant pour inscription nouveaux membres et anniv
			$j1=$j0+7; $j2=$j0+14; $j3=$j0+21;
		}
		else { // CRON BIS (H+1)
			$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.95)); // 95% du max - heure creuse - 5% restant pour inscription nouveaux membres
			$j4=$j0+28; $j5=$j0+35; $j6=$j0+42; $j7=$j0+49; $j8=$j0+56;
		}
	}
	else {
		$jj = ($j>29)?$j-30:$j+30; // aujourd'hui : 34
		if(!$cronBis) $max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.85)); // 85% du max - heure creuse - 15% restant pour inscription nouveaux membres et anniv
		else $max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.95)); // 95% du max - heure creuse - 5% restant pour inscription nouveaux membres
	}
		// 13.1 selection des membres
	$rencDrap = array();
	if(!isset($rencCustom['place'])) {
		$q = $wpdb->get_results("SELECT
				c_liste_categ,
				c_liste_valeur,
				c_liste_iso 
			FROM
				".$wpdb->prefix."rencontre_liste 
			WHERE 
				c_liste_categ='d' or (c_liste_categ='p' and c_liste_lang='".substr($rencDiv['lang3'],0,2)."')
			");
		foreach($q as $r) {
			if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur;
		}
	}
	$q=0;
	$qq1 = "SELECT 
			U.ID,
			U.user_login,
			U.user_email,
			P.t_action,
			R.c_pays,
			R.i_sex,
			R.i_zsex,
			R.c_zsex,
			R.i_zage_min,
			R.i_zage_max,
			R.i_zrelation,
			R.c_zrelation
		FROM ".$wpdb->base_prefix."users U
		INNER JOIN ".$wpdb->prefix."rencontre_users R
			ON R.user_id=U.ID
		INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
			ON P.user_id=U.ID ";
	$qq2 = " and R.i_sex!='98'
			and R.i_status!=4
			and (P.t_action NOT LIKE '%,nomail,%' or P.t_action IS NULL)
		ORDER BY P.d_modif DESC
		LIMIT ".$max;
	if(!$cronBis && isset($rencOpt['mailmois']) && $rencOpt['mailmois']==2) $q = $wpdb->get_results($qq1." WHERE SECOND(U.user_registered) IN (".$j0.",".$j1.") ".$qq2);
	else if($cronBis && isset($rencOpt['mailmois']) && $rencOpt['mailmois']==2) $q = $wpdb->get_results($qq1." WHERE SECOND(U.user_registered) IN (".$j2.",".$j3.") ".$qq2);
	else if(!$cronBis && isset($rencOpt['mailmois']) && $rencOpt['mailmois']==3) $q = $wpdb->get_results($qq1." WHERE SECOND(U.user_registered) IN (".$j0.",".$j1.",".$j2.",".$j3.") ".$qq2);
	else if($cronBis && isset($rencOpt['mailmois'])  && $rencOpt['mailmois']==3) $q = $wpdb->get_results($qq1." WHERE SECOND(U.user_registered) IN (".$j4.",".$j5.",".$j6.",".$j7.",".$j8.") ".$qq2);
	else if(!$cronBis) $q = $wpdb->get_results($qq1." WHERE SECOND(U.user_registered)='".$j."' ".$qq2);
	else if($cronBis) $q = $wpdb->get_results($qq1." WHERE SECOND(U.user_registered)='".$jj."' ".$qq2);
	if(isset($rencOpt['mailmois']) && $rencOpt['mailmois']==1) $ti = 2592000; // 30j
	else if(isset($rencOpt['mailmois']) && $rencOpt['mailmois']==2) $ti = 1296000; // 15j
	else $ti = 604800; // 7j
	
		// 13.2 boucle de mail
	$ct=0;
	if($q) foreach($q as $r) {
		++$ct;
		$melCountry = (!empty($rencOpt['melctry'])&&!empty($r->c_pays)&&empty($rencCustom['country'])&&empty($rencCustom['place'])?"and R.c_pays='".$r->c_pays."'":"");
		$action = json_decode($r->t_action,true);
		if(!empty($rencOpt['mailmois']) && $ct<=$max) {
			$b = 0;
			// Connect_link
			if(function_exists('openssl_encrypt')) $oo = base64_encode(openssl_encrypt($r->ID.'|'.$r->user_login.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
			// BONJOUR
			$s = "<div style='font-family:\"Helvetica Neue\",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;'>".__('Hello','rencontre')."&nbsp;".$r->user_login.",";
			if(!empty($rencOpt['textmail']) && strlen($rencOpt['textmail'])>10) $s .= "<br />".nl2br(stripslashes($rencOpt['textmail']))."\n";
			// NBR VISITES
			if(isset($action['visite'])) $s .= "<p style='font-weight:700;font-size:.9em;'>".__('Your profile has been visited','rencontre')."&nbsp;<span style='color:red;'>".count($action['visite'])."&nbsp;".__('time','rencontre')."</span>.\n</p>";
			// PROPOSITIONS
			$zmin=date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$r->i_zage_min));
			$zmax=date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$r->i_zage_max));
			// Selection par le sex
			if($r->i_sex==$r->i_zsex) $homo=1; else $homo=0;
			if($r->i_zsex!=99) {
				$sexQuery = " and R.i_sex=".$r->i_zsex." ";
				if(!isset($rencCustom['sex'])) $sexQuery .= " and R.i_zsex".(($homo)?'='.$r->i_zsex:'!='.$r->i_zsex)." ";
			}
			else $sexQuery = " and R.i_sex IN (".substr($r->c_zsex,1,-1).") ";
			if($r->i_zrelation!=99) $relQuery = " and (R.i_zrelation=".$r->i_zrelation." or  R.c_zrelation LIKE '%,".$r->i_zrelation.",%') ";
			else $relQuery = ''; // pas de else - trop complique sans boucle - ,1,3,6, IN/LIKE/= ,2,3,5,
			//
			$q1 = $wpdb->get_results("SELECT 
					U.ID, 
					U.user_login, 
					R.d_naissance, 
					R.c_pays, 
					R.c_ville, 
					P.t_titre
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
					ON P.user_id=U.ID
				WHERE 
					U.ID!='".$r->ID."'
					".$sexQuery."
					".$relQuery."
					and R.d_naissance<'".$zmin."' 
					and R.d_naissance>'".$zmax."' 
					".((!empty($rencOpt['onlyphoto']) || !empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
					".$melCountry."
				ORDER BY U.user_registered DESC
				LIMIT ".(!empty($rencOpt['nbr']['mailSelection'])?$rencOpt['nbr']['mailSelection']:4));
			if($q1) {
				$b = 1;
				$s .= "<p style='font-weight:700;font-size:.9em;'>".__('Here\'s a selection of members that may interest you','rencontre')." :</p>";
				$s .= "<table><tr>";
				$c = 0;
				foreach($q1 as $r1) {
					++$c;
					$s .= "<td>".rencMailBox($r1,$rencDrap,$oo,$ii)."</td>"."\n";
					if($c>$mailPerline)
						{
						$c = 0;
						$s .= "</tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr>";
					}
				}
				$s .= "</tr></table>"."\n";
				}
			// SOURIRES
			if(isset($action['sourireIn']) && count($action['sourireIn'])) {
				$t = "<p style='font-weight:700;font-size:.9em;'>";
				if(isset($rencCustom['smiw']) && isset($rencCustom['smiw4']) && $rencCustom['smiw'] && $rencCustom['smiw4']) $t .= stripslashes($rencCustom['smiw4']);
				else $t .= __('You have received a smile from','rencontre');
				$t .= " :\n</p><table><tr>";
				$c = 0;
				for($v=0; $v<count($action['sourireIn']);++$v) {
					if(isset($action['contactIn'][$v]['d']) && strtotime($action['contactIn'][$v]['d'])>current_time('timestamp',0)-$ti) { // only new before last mail
						$r1 = $wpdb->get_row("SELECT
								U.ID,
								U.user_login,
								R.d_naissance,
								R.c_pays,
								R.c_ville,
								P.t_titre
							FROM ".$wpdb->base_prefix."users U
							INNER JOIN ".$wpdb->prefix."rencontre_users R
								ON R.user_id=U.ID
							INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
								ON P.user_id=U.ID
							WHERE 
								R.user_id='".$action['sourireIn'][$v]['i']."'
								".((!empty($rencOpt['onlyphoto']) || !empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
								".$melCountry."
							ORDER BY R.d_session DESC
							LIMIT 1
							");
						if($r1) {
							$b = 1;
							++$c;
							$s .= $t."<td>".rencMailBox($r1,$rencDrap,$oo,$ii)."</td>"."\n";
							$t = '';
							if($c>$mailPerline) {
								$c = 0;
								$s .= "</tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr>";
							}
						}
					}
				}
				if($t=="") $s .= "</tr></table>"."\n";
			}
			// DEMANDES DE CONTACT
			if(isset($action['contactIn']) && count($action['contactIn'])) {
				$t = "<p style='font-weight:700;font-size:.9em;'>".__('You have received a contact request from','rencontre')." :\n</p><table><tr>";
				$c = 0;
				for ($v=0; $v<count($action['contactIn']);++$v) {
					if(isset($action['contactIn'][$v]['d']) && strtotime($action['contactIn'][$v]['d'])>current_time('timestamp',0)-$ti) { // only new before last mail
						$r1 = $wpdb->get_row("SELECT
								U.ID,
								U.user_login,
								R.d_naissance,
								R.c_pays,
								R.c_ville,
								P.t_titre
							FROM ".$wpdb->base_prefix."users U
							INNER JOIN ".$wpdb->prefix."rencontre_users R
								ON R.user_id=U.ID
							INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
								ON P.user_id=U.ID
							WHERE 
								R.user_id='".$action['contactIn'][$v]['i']."'
								".((!empty($rencOpt['onlyphoto']) || !empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
								".$melCountry."
							ORDER BY R.d_session DESC
							LIMIT 1
							");
						if($r1) {
							++$c;
							$s .= $t."<td>".rencMailBox($r1,$rencDrap,$oo,$ii)."</td>"."\n";
							$t = '';
							if($c>$mailPerline) {
								$b = 1;
								$c = 0;
								$s .= "</tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr>";
							}
						}
					}
				}
				if($t=="") $s .= "</tr></table>";
			}
			// MESSAGES
			$n = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."rencontre_msg M WHERE M.recipient='".$r->user_login."' and M.read=0 and M.deleted=0");
			if($n) {
				$b = 1;
				$s .= "<p style='font-weight:700;font-size:.9em;'>".__('You have','rencontre')."&nbsp;<span style='color:red;'>".$n."&nbsp;".(($n>1)?__('messages','rencontre'):__('message','rencontre'))."</span>&nbsp;".__('in your inbox.','rencontre')."\n</p>";
			}
			// MOT DE LA FIN
			$s .= "<p>".__("Do not hesitate to send us your comments.",'rencontre')."\n</p><br />".__('Regards,','rencontre')."<br />".$bn;
			if($b) {
				$buttonCSS = "color:".$w3renc[$bt0.'T'].";background-color:".$w3renc[$bt0].";border:none;display:inline-block;width:45%;padding:8px 16px;vertical-align:middle;overflow:hidden;text-decoration:none;text-align:center;cursor:pointer;white-space:nowrap;font-size:11px;letter-spacing:-.5px";
				$buttonHover = "this.style.backgroundColor='".$w3renc[$bt1]."';this.style.color='".$w3renc[$bt1.'T']."'";
				$buttonOut = "this.style.backgroundColor='".$w3renc[$bt0]."';this.style.color='".$w3renc[$bt0.'T']."'";
				$s .= "<div style='margin:8px 0 0;text-align:center;'><a style='".$buttonCSS."' onMouseOver='".$buttonHover."' onMouseOut='".$buttonOut."' href='".htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii))."' target='_blank'> ".__('Login','rencontre')."\n</a></div>";
			}
			$s .= "</div>";
			//
			$s1 .= $s;
			if($b) {
				$he = array();
				if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
					$he[] = 'From: '.$bn.' <'.$rencDiv['admin_email'].'>';
					$he[] = 'Content-type: text/html; charset=UTF-8';
					$s = '<html><head></head><body>'.$s.'</body></html>';
				}
				@wp_mail($r->user_email, $bn, $s, $he);
				++$cm;
			}
			if(file_exists($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$r->ID.'.txt')) @unlink($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$r->ID.'.txt');
		}
		// 13.3 *********** Nettoyage des comptes action *********
		// {"sourireIn":[{"i":992,"d":"2015-09-01"},{"i":75,"d":"2015-09-01"}],"contactIn":[{"i":992,"d":"2015-09-01"}]}
		$ac = array("sourireIn","sourireOut","contactIn","contactOut","visite","bloque");
		$x = 0;
		for($v=0; $v<count($ac); ++$v) {
			if(isset($action[$ac[$v]])) {
				$c = count($action[$ac[$v]]);
				for($w=0; $w<$c; ++$w) {
					if(isset($action[$ac[$v]][$w]['i'])) {
						$q1 = $wpdb->get_var("SELECT user_id 
							FROM ".$wpdb->prefix."rencontre_users 
							WHERE user_id='".$action[$ac[$v]][$w]['i']."'
							LIMIT 1"); // compte suprime ?
						if(!$q1) {
							if(!$x) $x = 1;
							unset($action[$ac[$v]][$w]['i']); 
							unset($action[$ac[$v]][$w]['d']);
						}
					}
				}
				if($action[$ac[$v]]) $action[$ac[$v]] = array_filter($action[$ac[$v]]);
				if($action[$ac[$v]]) $action[$ac[$v]] = array_splice($action[$ac[$v]], 0); // remise en ordre avec de nouvelles clefs
			}
		}
		if($x) {
			$out = json_encode($action);
			$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$r->ID));
		}
		// 14. Suppression des msg anciens
		if(!empty($rencOpt['msgdel'])) {
			$d = array(1=>7884000, 2=>15768000, 3=>31536000, 4=>2592000);
			if(isset($d[$rencOpt['msgdel']])) {
				$d1 = date('Y-m-d H:i:s', time()-$d[$rencOpt['msgdel']]);
				$wpdb->query("DELETE FROM ".$wpdb->prefix."rencontre_msg WHERE date<'".$d1."' ");
			}
		}
		// ***************************************************
	}
	// 15. Premium Cron
	$ho = false; if(has_filter('rencCronP', 'f_rencCronP')) $ho = apply_filters('rencCronP', $ho);
	//
	if(current_time("N")!="1")$t=@fopen($rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt', 'w'); @fwrite($t,max((file_get_contents($rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt')+0),$cm)); @fclose($t);
	if($cronBis) @unlink($rencDiv['basedir'].'/portrait/cache/rencontre_cronBis.txt'); // CRON BIS effectue
	else {$t=@fopen($rencDiv['basedir'].'/portrait/cache/rencontre_cronBis.txt', 'w'); @fclose($t);} // CRON BIS a faire
	@unlink($rencDiv['basedir'].'/portrait/cache/rencontre_cronOn.txt');
	@unlink($rencDiv['basedir'].'/portrait/cache/rencontre_cronListeOn.txt');
	clearstatcache();
}
//
function f_cron_liste($d2) {
	// Envoi Mail Horaire en respectant quota
	global $wpdb; global $rencOpt; global $rencDiv; global $rencCustom;
	$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
	$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
	$Lidf = (!empty($rencOpt['lbl']['rencidfm'])?$rencOpt['lbl']['rencidfm']:'rencidfm');
	include(dirname(__FILE__).'/rencontre_color.php');
	$bt0 = (!empty($rencCustom['mebt'])?$rencCustom['mebt']:$w3rencDef['mebt']);
	$bt1 = (!empty($rencCustom['mebo'])?$rencCustom['mebo']:$w3rencDef['mebo']);
	$oo = ''; $ii = '';
	if(function_exists('openssl_encrypt')) {
		$iv = openssl_random_pseudo_bytes(16);
		$ii = base64_encode($iv);
	}
	$rencDrap = array();
	if(!isset($rencCustom['place'])) {
		$q = $wpdb->get_results("SELECT c_liste_categ, c_liste_valeur, c_liste_iso 
			FROM ".$wpdb->prefix."rencontre_liste 
			WHERE 
				c_liste_categ='d' or (c_liste_categ='p' and c_liste_lang='".substr($rencDiv['lang3'],0,2)."') ");
		foreach($q as $r) {
			if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur;
		}
	}
	$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:0)*.8)); // 80% du max - 20% restant pour inscription nouveaux membres
	$u2 = file_get_contents($d2);
	$cm = 0; // compteur de mail
	// 1. listing des USERS en attente
	if($dh = @opendir($rencDiv['basedir'].'/portrait/cache/cron_liste/')) {
		$bn = get_bloginfo('name');
		$lis = '(';
		$fi = Array();
		$c = 0;
		while(($file = readdir($dh))!==false) {
			$lid = explode('.',$file);
			if($file!='.' && $file!='..') {
				if(!preg_match('/^[0-9]+$/',$lid[0])) @unlink($dh.$file);
				else {
					$fi[$c][0] = filemtime($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$file); // date - en premier pour le sort
					$fi[$c][1] = $lid[0]; // nom
					++$c;
				}
			}
		}
		sort($fi); // les plus ancien en premier
		$c = 0;
		foreach($fi as $r) {
			++$c;
			if($c>$max) break;
			if($r[1]) $lis .= $r[1].","; else --$c;
		}
		if(strlen($lis)>2) $lis = substr($lis,0,-1) . ')'; else $lis='(0)';
		closedir($dh);
		$q = $wpdb->get_results("SELECT
				U.ID,
				U.user_login,
				U.user_email,
				P.t_action 
			FROM ".$wpdb->base_prefix."users U
			INNER JOIN ".$wpdb->prefix."rencontre_users R
				ON R.user_id=U.ID
			INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
				ON P.user_id=U.ID
			WHERE 
				U.ID IN ".$lis." 
				and (P.t_action NOT LIKE '%,nomail,%' or P.t_action IS NULL)
				".((!empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
			LIMIT ".$max); // clause IN : WHERE U.ID IN ( 250, 220, 170 );
		$las = 0;
		if($q) foreach($q as $u) { // {"sourireIn":[{"i":992,"d":"2015-09-01"},{"i":75,"d":"2015-09-01"}],"contactIn":[{"i":992,"d":"2015-09-01"}]}
			$buttons = 0; $smile = 0; $contact = 0; $inbox = 0;
			if(function_exists('openssl_encrypt')) $oo = base64_encode(openssl_encrypt($u->ID.'|'.$u->user_login.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
			$action= json_decode($u->t_action,true);
			//
			if(isset($action['contactIn']) && count($action['contactIn'])) {
				$v = count($action['contactIn'])-1;
				if(isset($action['contactIn'][$v]['d']) && strtotime($action['contactIn'][$v]['d'])>current_time('timestamp',0)-64800) { // 18h
					$r = $wpdb->get_row("SELECT
							U.ID,
							U.user_login,
							R.d_naissance,
							R.c_pays,
							R.c_ville,
							P.t_titre
						FROM ".$wpdb->base_prefix."users U
						INNER JOIN ".$wpdb->prefix."rencontre_users R
							ON R.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=U.ID
						WHERE 
							R.user_id='".$action['contactIn'][$v]['i']."'
							".((!empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
						LIMIT 1
						");
					if($r) {
						$contact = "<td>".rencMailBox($r,$rencDrap,$oo,$ii)."</td>";
						$buttons = 1;
					}
				}
			}
			//
			if(isset($action['sourireIn']) && count($action['sourireIn'])) {
				$v = count($action['sourireIn'])-1;
				if(isset($action['sourireIn'][$v]['d']) && strtotime($action['sourireIn'][$v]['d'])>current_time('timestamp',0)-64800) { // 18h
					$r = $wpdb->get_row("SELECT
							U.ID,
							U.user_login,
							R.d_naissance,
							R.c_pays,
							R.c_ville,
							P.t_titre
						FROM ".$wpdb->base_prefix."users U
						INNER JOIN ".$wpdb->prefix."rencontre_users R
							ON R.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=U.ID
						WHERE 
							R.user_id='".$action['sourireIn'][$v]['i']."'
							".((!empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
						LIMIT 1
						");
					if($r) {
						$smile = "<td>".rencMailBox($r,$rencDrap,$oo,$ii)."</td>";
						$buttons = 1;
					}
				}
			}
			//
			$inbox = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."rencontre_msg M WHERE M.recipient='".$u->user_login."' and M.read=0 and M.deleted=0");
			if($inbox) {
				$hrefinbox = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Lidf."=".rencGetId('r0',0)."&".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii));
				$buttons = 1;
			}
			//
			if($buttons) {
				$buttonCSS = "color:".$w3renc[$bt0.'T'].";background-color:".$w3renc[$bt0].";border:none;display:inline-block;width:45%;padding:8px 16px;vertical-align:middle;overflow:hidden;text-decoration:none;text-align:center;cursor:pointer;white-space:nowrap;font-size:11px;letter-spacing:-.5px";
				$buttonHover = "this.style.backgroundColor='".$w3renc[$bt1]."';this.style.color='".$w3renc[$bt1.'T']."'";
				$buttonOut = "this.style.backgroundColor='".$w3renc[$bt0]."';this.style.color='".$w3renc[$bt0.'T']."'";
				$buttons = "<div style='margin:8px 0 0;text-align:center;'><a style='".$buttonCSS."' onMouseOver='".$buttonHover."' onMouseOut='".$buttonOut."' href='".htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii))."' target='_blank'> ".__('Login','rencontre')."\n</a></div>";
				// ****** TEMPLATE ********
				$mailtit = ''; // Var in template
				ob_start();
				if(empty($tdir)) $tdir = rencTplDir();
				if(file_exists(get_stylesheet_directory().'/templates/rencontre_mail_instant.php')) include(get_stylesheet_directory().'/templates/rencontre_mail_instant.php');
				else if(file_exists($tdir['path'].'rencontre_mail_instant.php')) include($tdir['path'].'rencontre_mail_instant.php');
				else include(WP_PLUGIN_DIR.'/rencontre/templates/rencontre_mail_instant.php');
				$o = ob_get_clean();
				// ************************
				$o = trim(preg_replace('/\s+/', ' ', $o)); // remove line break that cause <br /> in mail
				$he = array();
				if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
					$he[] = 'From: '.$bn.' <'.$rencDiv['admin_email'].'>';
					$he[] = 'Content-type: text/html; charset=UTF-8';
					$o = '<html><head></head><body>'.$o.'</body></html>';
				}
				@wp_mail($u->user_email, $bn." - ".(empty($mailtit)?__('A member contact you','rencontre'):$mailtit), $o, $he);
				++$cm;
			}
			$d = filemtime($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$u->ID.'.txt');
			if($d>$las) $las = $d;
			@unlink($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$u->ID.'.txt');
		}
		foreach($fi as $r) {
			if($r[0]>$las) break;
			else if(file_exists($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$r[1].".txt")) @unlink($rencDiv['basedir'].'/portrait/cache/cron_liste/'.$r[1].".txt");  // suppression non traite car ID inexistant
		}
	}
	$t=@fopen($d2,'w'); @fwrite($t,max(($u2+0),$cm)); @fclose($t);
	@unlink($rencDiv['basedir'].'/portrait/cache/rencontre_cronListeOn.txt');
	@unlink($rencDiv['basedir'].'/portrait/cache/rencontre_cronOn.txt');
	clearstatcache();
}
//
function rencMailBox($u,$rencDrap,$oo,$ii) {
	global $rencDiv; global $rencOpt; global $rencCustom;
	$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
	$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
	$Lidf = (!empty($rencOpt['lbl']['rencidfm'])?$rencOpt['lbl']['rencidfm']:'rencidfm');
	if(file_exists($rencDiv['basedir']."/portrait/".floor($u->ID/1000)."/".Rencontre::f_img(($u->ID*10)."-libre").".jpg")) $u->photoUrl = $rencDiv['baseurl']."/portrait/".floor($u->ID/1000)."/".Rencontre::f_img(($u->ID*10)."-libre").".jpg";
	else $u->photoUrl = plugins_url('rencontre/images/no-photo60.jpg');
	$age = 0;
	if(!isset($rencCustom['born'])) {
		list($annee, $mois, $jour) = explode('-', $u->d_naissance);
		$today['mois'] = current_time('n');
		$today['jour'] = current_time('j');
		$today['annee'] = current_time('Y');
		$age = $today['annee'] - $annee;
		if($today['mois']<=$mois) {
			if($mois==$today['mois']) {
				if($jour>$today['jour']) --$age;
			}
			else --$age;
		}
	}
	$u->name = substr($u->user_login,0,10);
	$u->age = $age;
	$u->title = substr($u->t_titre,0,60);
	$u->link = new StdClass();
	$a = "&".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii);
	$u->link->contact = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Lidf."=".rencGetId('c'.$u->ID,0).$a);
	$u->link->smile = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Lidf."=".rencGetId('s'.$u->ID,0).$a);
	$u->link->message = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Lidf."=".rencGetId('m'.$u->ID,0).$a);
	$u->link->profile = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Lidf."=".rencGetId('p'.$u->ID,0).$a);
	include(dirname(__FILE__).'/rencontre_color.php');
	$bt0 = (!empty($rencCustom['mebt'])?$rencCustom['mebt']:$w3rencDef['mebt']);
	$bt1 = (!empty($rencCustom['mebo'])?$rencCustom['mebo']:$w3rencDef['mebo']);
	$buttonCSS = "color:#fff;background-color:#000;border:none;display:block;line-height:2.5em;vertical-align:middle;overflow:hidden;text-decoration:none;text-align:center;cursor:pointer;white-space:nowrap";
	$buttonHover = "this.style.backgroundColor=\"".$w3renc[$bt1]."\";this.style.color=\"".$w3renc[$bt1.'T']."\"";
	$buttonOut = "this.style.backgroundColor=\"#000\";this.style.color=\"#fff\"";
	// ****** TEMPLATE ********
	ob_start();
	if(empty($tdir)) $tdir = rencTplDir();
	if(file_exists(get_stylesheet_directory().'/templates/rencontre_mail_regular.php')) include(get_stylesheet_directory().'/templates/rencontre_mail_regular.php');
	else if(file_exists($tdir['path'].'rencontre_mail_regular.php')) include($tdir['path'].'rencontre_mail_regular.php');
	else include(WP_PLUGIN_DIR.'/rencontre/templates/rencontre_mail_regular.php');
	$o = ob_get_clean();
	// ************************
	$o = trim(preg_replace('/\t+/', '', $o)); // remove tab
	$o = preg_replace('/^\s+|\n|\r|\s+$/m', '', $o); // remove line break
	return $o;
}
function rencSanit($f,$g) {
	// Sanitize / Validate POST && GET datas
	$a = '';
	switch($g) {
		case 'int': // AGE, YEAR, MONTH, DAY, SIZE, SEX, RELATION...
			$a = abs(intval($f)); // 09 allowed only
			break;

		case 'num': // GPS, ROTATE
			$a = trim($f);
			$a = filter_var($a, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			break;
			
		case 'numplus': // ZSEX
			$a = trim($f);
			$a = preg_replace("/[^0-9,.: ()-]/","", $a); // (1,87.5,108,2018-04-01 17:06:31,45)
			break;
			
		case 'date': // DATE
			$a = trim($f);
			$a = substr(preg_replace("/[^0-9: -]/","", $a),0,19); // date or datetime 2018-04-01 17:06:31
			break;
			
		case 'AZ': // COUNTRY
			$a = trim($f);
			$a = preg_replace("/[^A-Z]/","", $a);
			break;
			
		case 'alphanum': // RENC, RENCIDFM, ID, PROFILQS, NOUVEAU, A1, OBJ, REGION (search)
			$a = trim($f);
			$a = preg_replace("/[^a-zA-Z0-9_,-]/","", $a); // AZaz09_-, allowed only
			break;
			
		case 'words': // DISPLAY_NAME, CITY, REGION (set)
			$a = sanitize_text_field($f);
			$a = preg_replace("/\s+/", " ",$a); // multiple spaces & lines break
			$n = array('"','(',')','{','}','[',']','<','>','|','+','=','?',';','`','*');
			$a = str_replace($n,"",$a);
			break;
			
		case 'text': // 
			$a = sanitize_text_field($f);
			$a = preg_replace("/\s+/", " ",$a); // multiple spaces & lines break
			break;
			
		case 'para': // CONTENU, MSG, ANNONCE, PROFIL (area),
			$a = sanitize_textarea_field($f);
			$a = preg_replace("/[\r\n]+/","\n", $a); // multiples lines break
			$a = preg_replace("/[[:blank:]]+/"," ", $a); // multiple spaces
			break;
			
		case 'url':
			$a = strip_tags(stripslashes(filter_var($f, FILTER_SANITIZE_URL)));
			break;
			
		case 'b64': // RENCOO, RENCII
			$a = preg_match("%^([A-Za-z0-9+/]{4})*([A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{2}==)?$%", $f);
			if($a) $a = $f;
			break;
			
		case 'pipe': // pipe separation text && url && location
			$a = rencSanit($f,'text');
			break;
			
		case 'img': // pipe separation with img stream (Facebook) or int
			if(preg_match("/^[1-9][0-9]*$/", $f) && strlen($f)<12) $a = intval($f);
			else if(strpos($f,'|')!==false) $a = $f; // IMG Stream => sanit after
			else $a = '';
			break;
			
		case 'mel':
			$a = filter_var($f,FILTER_VALIDATE_EMAIL);
			if($a) $a = sanitize_email($f);
			if($a!=$f) $a = '';
			break;

		case 'array':
			if(is_array($f)) {
				$a = array();
				foreach($f as $r) $a[] = rencSanit($r,'alphanum');
			}
			else $a = rencSanit($f,'alphanum');
			break;
	}
	if(!is_int($a) && !is_string($a) && !is_array($a)) $a = (string)$a;
	return $a;
}
//
function rencCssJs() {
	global $post;
	// JS
	if(empty($tdir)) $tdir = rencTplDir();
	wp_enqueue_script('jquery');
	wp_register_script('rencontre', plugins_url('rencontre/js/rencontre.js'),array(),false,true); // true : footer
	wp_register_script('rotate-min', plugins_url('rencontre/js/jqueryRotate-min.js'),array(),false,true);
	wp_register_script('labelauty', plugins_url('rencontre/js/jquery-labelauty.js'),array(),false,true);
	$lbl = rencLabels(); $lbljs = rencLabelsJs($lbl); wp_localize_script('rencontre', 'lbl', $lbljs);
	// CSS
	wp_register_style('jquery-ui', plugins_url('rencontre/css/jquery-ui.min.css'));
	if(file_exists($tdir['csspath'].'w3.css')) wp_register_style('w3css', $tdir['cssurl'].'w3.css');
	if(file_exists($tdir['csspath'].'fontawesome/css/font-awesome5.css')) wp_register_style('font-awesome5', $tdir['cssurl'].'fontawesome/css/font-awesome5.css',array(),'5.6.1');
	if(file_exists(get_stylesheet_directory().'/templates/rencontre.css')) wp_register_style('rencontre', get_stylesheet_directory_uri().'/templates/rencontre.css');
	else if(file_exists($tdir['csspath'].'rencontre.css')) wp_register_style('rencontre', $tdir['cssurl'].'rencontre.css');
	// Enqueue
	if(!empty($_SESSION['rencontre'])) {
		if(strstr($_SESSION['rencontre'],'edit') || strstr($_SESSION['rencontre'],'card')) {
			wp_enqueue_script('rotate-min');
		}
		if(strstr($_SESSION['rencontre'],'edit') || strstr($_SESSION['rencontre'],'account') || strstr($_SESSION['rencontre'],'nouveau')) {
			wp_enqueue_script('labelauty');
		}
	}
	// Enqueue if SHORTCODE
	$w3 = 0;
    if(is_a($post,'WP_Post')) {
		if(is_user_logged_in() && has_shortcode($post->post_content,'rencontre')) {
			if(wp_style_is('rencontre','registered')) wp_enqueue_style('rencontre');
			wp_enqueue_script('rencontre');
			if(wp_style_is('w3css','registered')) {
				wp_enqueue_style('w3css');
				$w3 = 1;
			}
		}
		if(!is_user_logged_in() && has_shortcode($post->post_content,'rencontre_imgreg')) {
			if(wp_style_is('rencontre','registered')) wp_enqueue_style('rencontre');
			if(wp_style_is('w3css','registered')) {
				wp_enqueue_style('w3css');
				$w3 = 1;
			}
		}
	}
	// Enqueue if WIDGET
	if(is_active_widget(false, false, 'rencontre')) {
		if(wp_style_is('rencontre','registered')) wp_enqueue_style('rencontre');
		wp_enqueue_script('rencontre');
		if(wp_style_is('jquery-ui','registered')) wp_enqueue_style('jquery-ui');
		wp_enqueue_script('jquery-ui-datepicker'); // already in WP
		if(wp_style_is('w3css','registered')) {
			wp_enqueue_style('w3css');
			$w3 = 1;
		}
	}
	if($w3) {
		rencAddCustomW3css();
		if(!has_filter('rencNoFontawesome')) {
		//	wp_enqueue_style('font-awesome');
			add_action('wp_enqueue_scripts',function(){if(wp_style_is('font-awesome5','registered')) wp_enqueue_style('font-awesome5');}, 99);
		}
	}
}
//
function rencAddCustomW3css($f=0) {
	include(dirname(__FILE__).'/rencontre_color.php');
	global $rencCustom;
	$a = '';
	foreach($w3rencDef as $k=>$v) if(empty($rencCustom[$k])) $rencCustom[$k] = $v;
	if(!empty($w3renc[$rencCustom['mebg']])) $a .= '.w3-renc-mebg{color:'.$w3renc[$rencCustom['mebg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebg']].'!important}';
	if(!empty($w3renc[$rencCustom['mebt']])) $a .= '.w3-renc-mebt{color:'.$w3renc[$rencCustom['mebt'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebt']].'!important}';
	if(!empty($w3renc[$rencCustom['mebw']])) $a .= '.w3-renc-mebw{color:'.$w3renc[$rencCustom['mebw'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebw']].'!important}';
	if(!empty($w3renc[$rencCustom['mebo']])) $a .= '.w3-renc-mebo:hover{color:'.$w3renc[$rencCustom['mebo'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebo']].'!important}';
	if(!empty($w3renc[$rencCustom['blbg']])) $a .= '.w3-renc-blbg{color:'.$w3renc[$rencCustom['blbg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['blbg']].'!important}';
	if(!empty($w3renc[$rencCustom['titc']])) $a .= '.w3-renc-titc{color:'.$w3renc[$rencCustom['titc']].'!important;}';
	if(!empty($w3renc[$rencCustom['txtc']])) $a .= '.w3-renc-txtc{color:'.$w3renc[$rencCustom['txtc']].'!important;}';
	if(!empty($w3renc[$rencCustom['lblc']])) $a .= '.w3-renc-lblc label:not([for]){color:'.$w3renc[$rencCustom['lblc']].'!important;}'; // Labelauty exclusion
	if(!empty($w3renc[$rencCustom['line']])) $a .= '.w3-renc-line{border-color:'.$w3renc[$rencCustom['line']].'!important}';
	if(!empty($w3renc[$rencCustom['inbg']])) $a .= '.w3-renc-inbg{color:'.$w3renc[$rencCustom['inbg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['inbg']].'!important}';
	if(!empty($w3renc[$rencCustom['sebg']])) $a .= '.w3-renc-sebg{color:'.$w3renc[$rencCustom['sebg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['sebg']].'!important}';
	if(!empty($w3renc[$rencCustom['wabg']])) $a .= '.w3-renc-wabg{color:'.$w3renc[$rencCustom['wabg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['wabg']].'!important}';
	if(!empty($w3renc[$rencCustom['msbs']])) $a .= '.w3-renc-msbs{color:'.$w3renc[$rencCustom['msbs'].'T'].'!important;background-color:'.$w3renc[$rencCustom['msbs']].'!important}';
	if(!empty($w3renc[$rencCustom['msbr']])) $a .= '.w3-renc-msbr{color:'.$w3renc[$rencCustom['msbr'].'T'].'!important;background-color:'.$w3renc[$rencCustom['msbr']].'!important}';
	if(!$f) wp_add_inline_style('w3css', $a);
	else echo '<link rel="stylesheet" href="'.plugins_url('rencontre/css/w3.css').'"><style>'.$a.'</style>';
}
//
function f_admin_menu ($wp_admin_bar) {
	if(current_user_can("manage_options")) {
		$args = array(
			'id'=>'rencontre',
			'title'=>'<img src="'.plugins_url('rencontre/images/rencontre.png').'" />',
			'href'=>admin_url('admin.php?page=rencmembers'),
			'meta'=>array('class'=>'rencontre',
			'title'=>'Rencontre'));
		$wp_admin_bar->add_node($args);
	}
}
//
function rencLogRedir($to,$req,$u) {
	global $rencOpt;
	if(isset($u->roles) && is_array($u->roles) && in_array('administrator',$u->roles)) return admin_url();
	else if(!empty($rencOpt['logredir']) && !empty($rencOpt['home'])) return $rencOpt['home'];
	else return $to;
}
//
function rencMetaMenuItem($menu) {
	if(current_user_can("administrator")) return $menu;
	if($menu->url=='#rencloginout#') { // URL in metaMenu Rencontre : base.php
		if(is_user_logged_in()) {
			$menu->url = wp_logout_url(get_permalink());
			$menu->title = __('Log out');
		}
		else {
			$menu->url = wp_login_url(get_permalink());
			$menu->title = __('Log in');
		}
	}
	else if($menu->url=='#rencregister#') {
		if(is_user_logged_in()) $menu->_invalid = true; // hide
		else {
			$menu->url = wp_registration_url();
			$menu->title = __('Register');
		}
	}
	else if(strpos($menu->url,'#rencnav#')!==false) {
		global $rencOpt;
		if(is_user_logged_in()) {
			$a = explode('#',$menu->url);
			if(!empty($rencOpt['home'])) {
				if(strpos($rencOpt['home'],'?')!==false && strpos($rencOpt['home'],'=')!==false) $menu->url = $rencOpt['home'].'&renc='.$a[2];
				else $menu->url = $rencOpt['home'].'?renc='.$a[2]; // 'javascript:void(0)';
			}
			else $menu->url = site_url();
		}
		else $menu->url = wp_logout_url();
	}
	return $menu;
}
//
function rencHideMenu($items,$m,$a) {
	foreach($items as $k=>$i) if(in_array('rencNav',$i->classes)) unset($items[$k]);
	return $items;
}
//
function rencInLine() {
	if(is_user_logged_in()) {
		if(!session_id()) session_start();
		global $current_user; global $wpdb;
		$upl = wp_upload_dir();
		if(!is_dir($upl['basedir'].'/tchat/')) mkdir($upl['basedir'].'/tchat/');
		if(!is_dir($upl['basedir'].'/session/')) mkdir($upl['basedir'].'/session/');
		$t = @fopen($upl['basedir'].'/session/'.$current_user->ID.'.txt', 'w') or die();
		fclose($t);
	}
}
//
function rencOutLine() {
	global $current_user; global $rencDiv;
	if(file_exists($rencDiv['basedir'].'/session/'.$current_user->ID.'.txt')) unlink($rencDiv['basedir'].'/session/'.$current_user->ID.'.txt');
	session_destroy();
}
//
function rencUserLogin($current_user,$pass) {
	global $wpdb;
	$wpdb->update($wpdb->prefix.'rencontre_users', array('d_session'=>current_time("mysql")), array('user_id'=>$current_user->ID));
	return $current_user;
}
//
function rencPreventAdminAccess() {
	global $rencDiv;
	$a=strtolower($_SERVER['REQUEST_URI']);
	if(strpos($a,'/wp-admin')!==false && strpos($a,'admin-ajax.php')==false && !current_user_can("edit_posts") && !current_user_can("bbp_moderator")) {
		wp_redirect($rencDiv['siteurl']);
		exit;
	}
}
function rencAdminBar($content) {
	return(current_user_can("edit_posts") || current_user_can("bbp_moderator"))?$content:false;
}
function f_regionBDD() {
	// AJAX fields update
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	global $wpdb; 
	$iso = rencSanit($_POST['pays'],'AZ');
	$b = 0;
	if(function_exists('wpGeonames_shortcode')) {
		$q = $wpdb->get_results("SELECT
				name,
				admin1_code
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				country_code='".$iso."'
				and feature_code='ADM1'
				and feature_class='A'
			ORDER BY name
			");
		if($q) {
			echo '<option value="">- '.__('Unimportant','rencontre').' -</option>';
			foreach($q as $r) {
				echo '<option value="9999'.$r->admin1_code.'">'.$r->name.'</option>';
			}
			$b = 1;
		}
	}
	if(!$b) {
		$q = $wpdb->get_results("
			SELECT
				id,
				c_liste_valeur
			FROM
				".$wpdb->prefix."rencontre_liste
			WHERE
				c_liste_iso='".$iso."'
				and c_liste_categ='r' ");
		echo '<option value="">- '.__('Unimportant','rencontre').' -</option>';
		foreach($q as $r) {
			echo '<option value="'.$r->id.'">'.$r->c_liste_valeur.'</option>';
		}
	}
}
//
function rencTestPass() { // modif compte uniquement
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	global $wpdb; global $rencOpt;
	$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
	//
	$id = rencSanit($_POST[$Lid],'int');
	$nouv = rencSanit($_POST['nouv'],'text');
	$pass = rencSanit($_POST['pass'],'text');
	$q = $wpdb->get_var("SELECT user_pass FROM ".$wpdb->base_prefix."users WHERE ID='".$id."' LIMIT 1");
	if(wp_check_password($pass,$q,$id)) {
		wp_set_password($nouv,$id); // changement MdP
		wp_set_auth_cookie($id); // cookie pour rester connecte
		echo 'ok';
	}
	else return; // bad password
}
//
function rencFbok() { // Facebook connect
	if(empty($_REQUEST['rencTokfb']) || !wp_verify_nonce($_REQUEST['rencTokfb'],'rencTokfb')) return;
	if(!is_user_logged_in()) {
		$_SESSION['rencFB']="1";
		$m = $_POST['fb'];
		if(isset($m['first_name']) && isset($m['email']) && rencSanit($m['email'],'mel') && isset($m['id'])) {
			global $wpdb;
			$u = $wpdb->get_var("SELECT
					user_login
				FROM
					".$wpdb->base_prefix."users
				WHERE
					user_email='".rencSanit($m['email'],'mel')."'
				LIMIT 1");
			if(!$u) { // unknow email => create user
				$u = rencFbokName($m['first_name'],substr($m['id'],5,4)); // get available login
				$pw = wp_generate_password($length=5, $include_standard_special_chars=false);
				$user_id = wp_create_user($u,$pw,$m['email']);
			}
			$user = get_user_by('login',$u);
			wp_set_current_user($user->ID, $u);
			wp_set_auth_cookie($user->ID);
			do_action('wp_login', $u, $user); // connect
		}
	}
}
//
function rencFbokName($u,$i,$c=0) {
	$o = $u.$i;
	if(validate_username($o) && !username_exists(sanitize_user($o))) return $o;
	else {
		$i = mt_rand(100000,999999);
		++$c;
		if($c>100) return $u.md5($u.$i.mt_rand()); // this one will be ok !!!
		else return rencFbokName($u,$i,$c);
	}
}
//
function f_addCountSearch() {
	// +1 in count search/day
	global $wpdb; global $current_user;
	$p = $wpdb->get_var("SELECT t_action FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id='".$current_user->ID."' LIMIT 1");
	$action = json_decode($p,true);
	if(isset($action['search']['n']) && isset($action['search']['d']) && $action['search']['d']==date("z")) $action['search']=array('d'=>date("z"),'n'=>($action['search']['n']+1));
	else $action['search']=array('d'=>date("z"),'n'=>1);
	$p = json_encode($action);
	$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$p), array('user_id'=>$current_user->ID));
}
//
function f_userSupp($f,$a,$b) { // rencontre.php - in action 'widget_init' - base.php
	// return ID
	f_suppImgAll($f);
	if(!current_user_can("administrator")) wp_logout();
	global $wpdb; global $rencOpt;
	$ip = 0;
	if($b) { // prison
		if(substr($f,0,2)=='IP') {
			$ip = 2;
			$f = substr($f,2);
		}
		$q = $wpdb->get_row("SELECT
				U.user_email,
				R.c_ip
			FROM ".$wpdb->base_prefix."users U
			INNER JOIN ".$wpdb->prefix."rencontre_users R
				ON R.user_id=U.ID
			WHERE
				U.ID=".$f."
			LIMIT 1
			");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."rencontre_prison (d_prison,c_mail,c_ip,i_type) VALUES('".current_time('mysql')."','".$q->user_email."','".$q->c_ip."',$ip)");
	}
	$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$f));
	$wpdb->delete($wpdb->prefix.'rencontre_msg', array('sender'=>$a));
	$wpdb->delete($wpdb->prefix.'rencontre_msg', array('recipient'=>$a));
	$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$f));
	if(empty($rencOpt['rol']) || empty($rencOpt['rolu']) || !$b || $ip==2) { // $b=0 => user delete his account - $ip=2 => "hard del"
		require_once(ABSPATH.'wp-admin/includes/user.php');
		wp_delete_user($f);
	}
	if(has_filter('rencUserDel')) apply_filters('rencUserDel', $f);
	if(!current_user_can("administrator")) { wp_redirect(home_url()); exit; }
	if(!empty($q->user_email)) return $q->user_email; // Admin deletion
}
//
function f_suppImgAll($id) { // After init (has_filter)
	global $rencDiv;
	$size = rencPhotoSize();
	$r = $rencDiv['basedir'].'/portrait/'.floor(intval($id)/1000).'/';
	for($v=0;$v<9;++$v) {
		$a = array();
		$a[] = Rencontre::f_img($id.$v) . '.jpg';
		foreach($size as $s) $a[] = Rencontre::f_img($id.$v.$s['label']) . '.jpg';
		foreach($a as $b) if(file_exists($r.$b)) unlink($r.$b);
		if(has_filter('rencBlurDelP', 'f_rencBlurDelP')) {
			$ho = new StdClass();
			$ho->id = $id;
			$ho->v = $v;
			$ho->rename = false;
			$ho->size = $size;
			apply_filters('rencBlurDelP', $ho);
		}
	}
}
function rencPhotoSize() {
	$size = array(
		array('label'=>'-mini', 'width'=>60, 'height'=>60, 'quality'=>75),
		array('label'=>'-grande', 'width'=>250, 'height'=>250, 'quality'=>75),
		array('label'=>'-libre', 'width'=>260, 'height'=>195, 'quality'=>75)
		);
	if(has_filter('rencImgSize')) $size = apply_filters('rencImgSize', $size);
	foreach($size as $k=>$v) if(empty($v['label']) || empty($v['width']) || empty($v['height'])) unset($size[$k]);
	return $size;
}
//
function rencNumbers() {
	$nb = array(
		'featured'=>8,
		'featuredDayOld'=>60,
		'birthday'=>4,
		'online'=>16,
		'new'=>12,
		'action'=>50,
		'searchResultAd'=>300,
		'mailSelection'=>4,
		'mailUserPerLine'=>2,
		'lengthTitle'=>30,
		'infochange'=>5000,
		'delNotConfirmed'=>60,
		'imgQuality'=>75,
		'urlNoCryptId'=>0
		);
	$a = $nb;
	if(has_filter('rencNumbers')) {
		global $rencOpt; global $rencDiv; global $rencCustom;
		$nb = apply_filters('rencNumbers', $nb);
		foreach($nb as $k=>$v) {
			if(isset($a[$k])) continue;
			if(isset($rencOpt[$k])) $rencOpt[$k] = $v;
			else if(isset($rencDiv[$k])) $rencDiv[$k] = $v;
			else if(isset($rencCustom[$k])) $rencCustom[$k] = $v;
		}
	}
	return $nb;
	// Other :
	// PREMIUM : msgnotifstartP, msgnotifloopP, msgnotifbipP
}
//
function rencLabels() {
	// Customize Key and Values name in URL GET params
	$l = array('renc','rencfastreg','rencoo','rencii','rencidfm','id','card','edit','msg','account',
		'gsearch','liste','qsearch','write','sourire','demcont','signale','bloque','favoriAdd','favoriDel',
		'sex','zsex','z2sex','homo','ageMin','ageMax','tailleMin','tailleMax','poidsMin','poidsMax','mot','pseudo',
		'pagine','pays','region','ville','relation','profilQS','line','photo','profil','astro','gps','km','fin','paswd',
		'galleryAdd','galleryDel','payment'); // Just a memo, not used
	$lb = array();
	// foreach($l as $r) $lb[$r] = $r; // not needed
	if(has_filter('rencLabels')) $lb = apply_filters('rencLabels', $lb);
	return $lb;
}
//
function rencLabelsJs($lb) {
	// Filter labels send in JS with wp_localize_script
	$a = array('renc','id','card','edit','msg','account','gsearch','liste','qsearch','galleryAdd','galleryDel','fin','paswd');
	$js = array();
	foreach($lb as $k=>$v) if(in_array($k,$a)) $js[$k] = $v;
	return $js;
}
//
function rencAvatar($avatar, $id_or_email, $size, $default, $alt) {
	$upl = wp_upload_dir();
	$id = false;
	if(is_numeric($id_or_email)) $id = (int)$id_or_email;
	else if(is_object($id_or_email) && !empty($id_or_email->user_id)) $id = (int)$id_or_email->user_id;
	if($id!==false) {
		$r = '/portrait/'.floor($id/1000).'/'.Rencontre::f_img(intval($id*10).'-'.($size<61?'mini':'grande'),2).'.jpg';
		if(file_exists($upl['basedir'].$r)) {
			$avatar = '<img alt="'.$alt.'" src="'.$upl['baseurl'].$r.'" class="avatar avatar-'.$size.' photo" height="'.$size.'" width="'.$size.'" />';
		}
	}
	return $avatar;
}
//
function rencGpsNavigator() {
	if(!session_id()) session_start(); // not needed but ...
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	global $wpdb; global $current_user;
	if(!empty($_POST['lat']) && !empty($current_user->ID)) {
		$lat = round(floatval(rencSanit($_POST['lat'],'num')),5);
		$lon = round(floatval(rencSanit($_POST['lon'],'num')),5);
		$acc = rencSanit($_POST['acc'],'int');
		$opt = rencSanit($_POST['opt'],'int');
		if($opt==1 || $opt>$acc) $wpdb->update($wpdb->prefix.'rencontre_users', array('e_lat'=>$lat,'e_lon'=>$lon), array('user_id'=>$current_user->ID));
		$_SESSION['gps'] = 1;
	}
}
//
function rencFastreg_form() {
	global $rencCustom; global $rencOpt;
	$Pzsex = (!empty($_POST['zsex']))?rencSanit($_POST['zsex'],'int'):'';
	$Ppssw = (!empty($_POST['pssw']))?rencSanit($_POST['pssw'],'text'):'';
	$o = '<p>';
	$o .= '<label for="pssw">'.__('Password').'<br />';
	$o .= '<input type="password" name="pssw" id="pssw" class="input" value="'.$Ppssw.'" size="25" /></label>';
	$o .= '</p>';
	if(!empty($rencOpt['disnam'])) {
		$o .= '<p><label for="dname">'.__('My name','rencontre').'<br />';
		$o .= '<input type="text" name="dname" id="dname" class="input" value="" size="30" /></label>';
		$o .= '</p>';
	}
	$o .= '<p class="pzsex>';
	$o .= '<label for="zsex">'.__('I\'m looking for','rencontre').'<br />';
	$o .= '<select name="zsex">';
	for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) $o .= '<option value="'.$v.'" '.($v==$Pzsex?'selected':'').'>'.$rencOpt['iam'][$v].'</option>';
	$o .= '</select>';
	$o .= '</p><br />';
	echo $o;
}
function rencFastreg_errors($errors, $sanitized_user_login, $user_email) {
	global $wpdb;
	$Ppssw = (!empty($_POST['pssw']))?rencSanit($_POST['pssw'],'text'):'';
	if(strlen($Ppssw)<6) {
		$errors->add('pssw_error', __('<strong>ERROR</strong>: Invalid password (6 characters min).', 'rencontre'));
	}
	$q1 = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."rencontre_prison WHERE c_mail='".$user_email."' LIMIT 1"); // email in jail ?
	$q2 = $wpdb->get_var("SELECT COUNT(*)
		FROM ".$wpdb->base_prefix."users U
		INNER JOIN ".$wpdb->prefix."rencontre_users R 
			ON R.user_id=U.ID
		WHERE
			R.c_ip='".$_SERVER['REMOTE_ADDR']."'
			and R.i_status=4
		"); // Count same IP in NEW REGISTRANT ONLY (robot registration)
	if($q1 || $q2>10) {
		$errors->add('user_email_error', __('Your email address is currently in quarantine. Sorry','rencontre'));
	}
	return $errors;
}
function rencFastreg($user_id) {
	global $wpdb; global $rencOpt; global $rencDiv;
	$Lfr = (!empty($rencOpt['lbl']['rencfastreg'])?$rencOpt['lbl']['rencfastreg']:'rencfastreg');
	$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
	$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
	// 1. Prepare element for connection
	$Ppssw = (!empty($_POST['pssw']))?rencSanit($_POST['pssw'],'text'):'';
	$Pzsex = (!empty($_POST['zsex']))?rencSanit($_POST['zsex'],'int'):'';
	$Pdname = (!empty($_POST['dname']))?rencSanit($_POST['dname'],'words'):'';
	$u = get_user_by('id', $user_id);
	$oo = ''; $ii = '';
	if(function_exists('openssl_encrypt')) {
		$iv = openssl_random_pseudo_bytes(16);
		$ii = base64_encode($iv);
		$oo = base64_encode(openssl_encrypt($u->ID.'|'.$u->user_login.'|z'.$Ppssw.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
	}
	if($Pdname!=$u->user_login && strlen($Pdname)>2) wp_update_user(array('ID'=>$user_id, 'display_name'=>substr($Pdname,0,30)));
	// 2. Creation in Rencontre
	$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$user_id)); // suppression si existe deja
	$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$user_id)); // suppression si existe deja
	$wpdb->insert($wpdb->prefix.'rencontre_users', array(
		'user_id'=>$user_id,
		'c_ip'=>($_SERVER['REMOTE_ADDR']?$_SERVER['REMOTE_ADDR']:'127.0.0.1'),
		'c_pays'=>(isset($rencOpt['pays'])?$rencOpt['pays']:'FR'), // default - custom no localisation
		'i_sex'=>98, // code for this case
		'i_zsex'=>$Pzsex,
		'c_zsex'=>',',
		'd_session'=>current_time("mysql"),
		'i_photo'=>0,
		'i_status'=>4)); // 4 : fastreg
	$wpdb->insert($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$user_id, 'd_modif'=>current_time("mysql"),'t_titre'=>'', 't_annonce'=>'', 't_profil'=>'[]'));
	if(empty($rencOpt['rol']) && !is_multisite()) {
		$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$user_id)); // suppression des roles WP
		wp_clear_auth_cookie();
		wp_set_current_user($user_id, $u->user_login);
		wp_set_auth_cookie($user_id);
		do_action('wp_login', $u->user_login, $u);
	}
	add_user_meta($user_id, 'rencontre_confirm_email',0);
	// 3. Send confirm email
	rencFastreg_email($u);
	// 4. Access with auto connection
	$s = get_bloginfo('wpurl');
	//wp_redirect((empty($rencOpt['home'])?site_url():$rencOpt['home']).'?'.$Lfr.'=1&'.$Loo.'='.urlencode($oo).'&'.$Lii.'='.urlencode($ii)); exit; // exit needed after wp_redirect
	wp_redirect(empty($rencOpt['home'])?$s:$rencOpt['home']); exit; // exit needed after wp_redirect
}
function rencFastreg_email($u,$other=0) {
	global $rencOpt; global $rencDiv;
	$Lfr = (!empty($rencOpt['lbl']['rencfastreg'])?$rencOpt['lbl']['rencfastreg']:'rencfastreg');
	$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
	$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
	if($other && isset($_COOKIE["rencfastregMail"])) echo __('Confirmation email already sent','rencontre');
	else {
		$oo = ''; $ii = '';
		if(function_exists('openssl_encrypt')) {
			$iv = openssl_random_pseudo_bytes(16);
			$ii = base64_encode($iv);
			$oo = base64_encode(openssl_encrypt($u->ID.'|'.$u->user_login.'|confirm|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
		}
		$he = array();
		$t = get_bloginfo('name');
		$s = __('Hello','rencontre').'&nbsp;'.$u->user_login.', <br />'.__('You should confirm your email with this link','rencontre').' : <br />';
		$s .= '<a href="'.htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home']).'?'.$Lfr.'=1&'.$Loo.'='.urlencode($oo).'&'.$Lii.'='.urlencode($ii)).'" target="_blank">'.htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home']).'?'.$Lfr.'='.$u->ID.'&'.$Loo.'='.urlencode($oo).'&'.$Lii.'='.urlencode($ii)).'</a> <br />';
		$s .= '<br />'.__('Regards,','rencontre').'<br /><br />'.$t;
		if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
			$he[] = 'From: '.$t.' <'.$rencDiv['admin_email'].'>';
			$he[] = 'Content-type: text/html; charset=UTF-8';
			$s = '<html><head></head><body>'.$s.'</body></html>';
		}
		@wp_mail($u->user_email, $t.' - '.__('Confirmation email','rencontre'), $s, $he);
		if($other==1) {
			echo __('Confirmation email sent','rencontre');
		}
		setcookie("rencfastregMail", 'yes');
	}
}
function rencistatus($f,$g) {
	// $f : i_status value
	// $g : capability - 0=>blocked , 1=>mail blocked , 2=>fastreg not completed , 3=> ... - Like RW on linux (3 = 1+2)
	$a = "00000000".decbin($f);
	if(strlen($a)<$g+1 || $g>7) return false;
	else if(substr($a,(-1-$g),1)=='1') return 1;
	else return 0;
}
function rencistatusSet($f,$g,$h) {
	// $f : i_status value
	// $g : capability
	// $h : value to set (0 / 1)
	$a = "00000000".decbin($f);
	$a = substr($a,0,strlen($a)-$g-1) . $h . substr($a,strlen($a)-$g);
	return bindec($a);
}
//
function rencGetId($i,$f=0) {
	// Hide ID value in URL
	global $rencOpt;
	if(!empty($rencOpt['nbr']['urlNoCryptId'])) return $i; // No CRYPT
	$key = base64_encode(!empty(AUTH_KEY)?substr(AUTH_KEY,0,15):'a7bc6de5vw4xy3z'); // not strong but it is not mandatory
	if(!$f) $i = base64_encode(openssl_encrypt($i,"AES-128-ECB",$key)); // f=0 : CRYPT
	else $i = openssl_decrypt(base64_decode($i),"AES-128-ECB",$key); // f=1 : DECRYPT
	return $i;
}
// WP-GEONAMES
function renc_wpGeonames_tpl() {
	$tdir = rencTplDir();
	if(file_exists(get_stylesheet_directory().'/templates/rencontre_wp-geonames_location_taxonomy.php')) return get_stylesheet_directory().'/templates/rencontre_wp-geonames_location_taxonomy.php';
	else return $tdir['path'].'rencontre_wp-geonames_location_taxonomy.php';
}
function renc_ajax_geoDataRegionHook() {
	global $wpdb;
	$iso = substr(rencSanit($_POST['country'],'AZ'),0,2);
	$result = array();
	if($iso) {
		$a = "admin1_code"; $b = "ADM1";
		if(wpGeonames_regionCode2($iso)) {
			$a = "admin2_code";
			$b = "ADM2";
		}
		$q = $wpdb->get_results("SELECT
				geonameid,
				name,
				".$a." AS regionid
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				feature_class='A' and feature_code='".$b."' and (country_code='".$iso."' or cc2='".$iso."')
					or
				feature_class='A' and feature_code='PCLD' and cc2='".$iso."'
			ORDER BY name
			");
		if(empty($q)) $q = $wpdb->get_results("SELECT
				id,
				c_liste_valeur AS name
			FROM
				".$wpdb->prefix."rencontre_liste
			WHERE
				c_liste_iso='".$iso."' and
				c_liste_categ='r'
			ORDER BY c_liste_valeur
			");
		$c = array();
		foreach($q as $r) {
			if(!isset($r->regionid) && isset($r->id)) $r->regionid = "zz". $r->id; // zz => JS part : no city
			else if($r->regionid=='00') $r->regionid = $r->country_code;
			if(!isset($c[$r->name])) {
				$result[] = $r;
				$c[$r->name] = 1;
			}
		}
	}
	echo json_encode($result);
}
function renc_ajax_rencGeoDataCity() {
	global $wpdb;
	$iso = substr(rencSanit($_POST['country'],'AZ'),0,2);
	$reg = rencSanit($_POST['region'],'words'); // STRING NAME IN GEONAME DB
	$cit = rencSanit($_POST['city'],'words'); // STRING NAME IN GEONAME DB
	$result = array();
	if($iso) {
		$idreg = $wpdb->get_var("SELECT admin1_code FROM ".$wpdb->base_prefix."geonames WHERE name LIKE '".$reg."' and feature_class='A' and feature_code='ADM1' LIMIT 1");
		$result = $wpdb->get_results("SELECT
				name,
				latitude,
				longitude
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				feature_class='P'
				and ((country_code='".$iso."' and admin1_code='".$idreg."') or country_code='".$idreg."')
				and name LIKE '".$cit."%'
			ORDER BY name
			LIMIT 6");
	}
	echo json_encode($result);
}
function renc_clear_cache_portrait($f=0) {
	// $f=1 => force deletion
	global $rencDiv; global $rencOpt;
	$t = time() + ($f?3000000:0);
	$a = $rencDiv['basedir'].'/portrait/cache/cache_portraits_accueil.html';
	if(file_exists($a) && $t>filemtime($a)+$rencOpt['rlibre']) unlink($a);
	$a = $rencDiv['basedir'].'/portrait/cache/cache_portraits_accueil1.html';
	if(file_exists($a) && $t>filemtime($a)+$rencOpt['rlibre']) unlink($a);
	$a = $rencDiv['basedir'].'/portrait/cache/cache_portraits_accueilmix.html'; // mix = 1 (old)
	if(file_exists($a) && $t>filemtime($a)+$rencOpt['rlibre']) unlink($a);
	$a = $rencDiv['basedir'].'/portrait/cache/cache_portraits_accueilgirl.html';
	if(file_exists($a) && $t>filemtime($a)+$rencOpt['rlibre']) unlink($a);
	$a = $rencDiv['basedir'].'/portrait/cache/cache_portraits_accueilmen.html';
	if(file_exists($a) && $t>filemtime($a)+$rencOpt['rlibre']) unlink($a);
}
function rencModerType() {
	global $rencDiv;
	$typ = array(
		'title' => wp_specialchars_decode($rencDiv['blogname'], ENT_QUOTES).' - '.__('Account deletion','rencontre'),
		'content' => __('Your account has been deleted','rencontre')."\r\n".' - ',
		'case' => array(
			__('Fake','rencontre'),
			__('Fake','rencontre').' ('.__('photo','rencontre').')',
			__('Fake','rencontre').' ('.__('profile','rencontre').')',
			__('Fake','rencontre').' ('.__('IP','rencontre').')',
			__('Fake','rencontre').' ('.__('email','rencontre').')',
			__('Inadequate image','rencontre'),
			__('Inappropriate presentation','rencontre'),
			__('Many reports','rencontre'),
			__('Behavior on the site','rencontre')
			)
		);
	if(has_filter('rencUserDelMailContent')) $typ = apply_filters('rencUserDelMailContent', $typ);
	return $typ;
}
function rencTplDir() {
	$tdir = array(
		'original' => 1,
		'path' => realpath(__DIR__ . '/..').'/templates/',
		'url'  => plugins_url('rencontre/templates/'),
		'csspath' => realpath(__DIR__ . '/..').'/css/',
		'cssurl'  => plugins_url('rencontre/css/')
		);
	if(has_filter('rencTemplateDir')) {
		$tdir = apply_filters('rencTemplateDir', $tdir);
		if(isset($tdir['original'])) unset($tdir['original']);
	}
	return $tdir;
}
//
// functions not used by Rencontre but helpful for dev
function rencGetUser($id) {
	global $wpdb;
		$u = $wpdb->get_row("SELECT
			R.user_id,
			R.c_ip,
			R.c_pays,
			R.c_region,
			R.c_ville,
			R.e_lat,
			R.e_lon,
			R.i_sex,
			R.d_naissance,
			R.i_taille,
			R.i_poids,
			R.i_zsex,
			R.c_zsex,
			R.i_zage_min,
			R.i_zage_max,
			R.i_zrelation,
			R.c_zrelation,
			R.i_photo,
			R.d_session,
			R.i_status,
			P.d_modif,
			P.t_titre,
			P.t_annonce
		FROM ".$wpdb->prefix."rencontre_users R
		INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
			ON P.user_id=R.user_id
		WHERE
			R.user_id=".$id."
		LIMIT 1
		");
	return $u;
}
function rencGetUserPhotos($id) {
	global $wpdb; global $rencDiv;
	$photos = array();
	$size = rencPhotoSize();
	$size[] = array('label' => '');
	$hob = false; if(has_filter('rencBlurP', 'f_rencBlurP')) $hob = apply_filters('rencBlurP', $hob);
	$p = $wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id=".$id." LIMIT 1");
	if(empty($p)) return $photos;
	for($v=0;$v<10;++$v) {
		$ph = array();
		if(($id*10+$v)<=$p) foreach($size as $s) {
			if(!isset($s['label'])) continue;
			$a = '/portrait/'.floor($id/1000).'/'.Rencontre::f_img((($id)*10+$v).$s['label'],2).'.jpg';
			if($hob) $b = '/portrait/'.floor($id/1000).'/'.Rencontre::f_img((($id)*10+$v).$s['label'].$hob,2).'.jpg';
			if(file_exists($rencDiv['basedir'].$a)) {
				$ph[] = (object)array(
					'label' => $s['label'],
					'url' => $rencDiv['baseurl'].$a,
					'path' => $rencDiv['basedir'].$a
					);
			}
			if($hob && file_exists($rencDiv['basedir'].$b)) {
				$ph[] = (object)array(
					'label' => $s['label'].$hob,
					'url' => $rencDiv['baseurl'].$b,
					'path' => $rencDiv['basedir'].$b
					);
			}
		}
		if(!empty($ph)) $photos[] = $ph;
	}
	return $photos;
}
function rencGetUserProfils($id,$lang=0) {
	global $wpdb; global $rencDiv;
	if(!empty($lang) && strlen($lang)!=2) $lang = 0;
	$profil = array();
	$q = $wpdb->get_var("SELECT t_profil FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id=".$id." LIMIT 1");
	$p = json_decode($q,true);
	if(empty($p)) return $profil;
	$l = '';
	foreach($p as $h) $l .= $h['i'].',';
	$l = substr($l,0,-1);
	$q = $wpdb->get_results("SELECT
			id,
			i_categ,
			i_label,
			c_categ,
			c_label,
			t_valeur,
			i_type,
			c_genre
		FROM
			".$wpdb->prefix."rencontre_profil
		WHERE
			id IN (".$l.")
			and c_lang='".(!empty($lang)?strtolower($lang):substr($rencDiv['lang2'],0,2))."'
			and c_categ!=''
			and c_label!=''
			and i_poids<5
		");
	$l = array();
	foreach($q as $r) $l[$r->id] = $r;
	foreach($p as $h) {
		$i = $h['i'];
		if(isset($l[$i])) {
			if($l[$i]->i_type<3) $value = $h['v'];
			else {
				$v = json_decode($l[$i]->t_valeur);
				if($l[$i]->i_type==3) $value = $v[$h['v']];
				else if($l[$i]->i_type==4) {
					$tmp = '';
					foreach($h['v'] as $pv) $tmp .= $v[$pv].', ';
					$value = substr($tmp, 0, -2);
				}
				else if($l[$i]->i_type==5) $value = ($v[0]+$h['v']*$v[2]) . ' ' . $v[3];
			}
			$profil[] = (object)array(
				'id' => $i,
				'categ_id' => $l[$i]->i_categ,
				'categ' => $l[$i]->c_categ,
				'label_id' => $l[$i]->i_label,
				'label' => $l[$i]->c_label,
				'type' => $l[$i]->i_type,
				'gender' => $l[$i]->c_genre,
				'value' => $value
				);
		}
	}
	return $profil;
}
//
// Partie ADMIN dans base.php
//
?>

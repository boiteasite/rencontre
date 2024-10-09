<?php
/*
Plugin Name: Rencontre
Author: Jacques Malgrange
Text Domain: rencontre
Domain Path: /lang
Plugin URI: https://www.boiteasite.fr/site_rencontre_wordpress.html
Description: A free powerful and exhaustive dating plugin with private messaging, webcam chat, search by profile and automatic sending of email. No third party.
Version: 3.12.4
Author URI: https://www.boiteasite.fr
*/
if(isset($_COOKIE['lang']) && strlen($_COOKIE['lang'])==5) add_filter('locale', function ($lang) {
	return (isset($_COOKIE['lang'])?$_COOKIE['lang']:$lang);
});
//
$a = __('A free powerful and exhaustive dating plugin with private messaging, webcam chat, search by profile and automatic sending of email. No third party.','rencontre'); // Description
$rencVersion = '3.12.3';
// Issue with Rencontre when edit and save theme from Dashboard - AJAX issue
if(defined('DOING_AJAX')) {
	if(isset($_POST['_wp_http_referer']) && strpos($_POST['_wp_http_referer'].'-','theme-editor.php')) return;
}
// **********************************************************************************
// INSTALLATION DU PLUGIN - Creation des tables en BDD
// **********************************************************************************
register_activation_hook ( __FILE__, 'rencontre_activation');
require(dirname(__FILE__).'/inc/rencontre_filter.php');
function rencontre_activation() {
	global $wpdb;
	$rencOpt = get_option('rencontre_options');
	if(!$rencOpt) {
		$rencOpt = array('passw'=>1,'rol'=>1,'pays'=>'FR','limit'=>5,'dynsearch'=>1,'hcron'=>3,'msgdel'=>3,'qmail'=>25,'npa'=>12,'jlibre'=>3,'prison'=>30,'anniv'=>1,'ligne'=>1,'mailsupp'=>1,'onlyphoto'=>1,'imnb'=>4,'imcopyright'=>1);
		$nu = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->base_prefix."users");
		if($nu<10) unset($rencOpt['rol']);
		update_option('rencontre_options', $rencOpt);
	}
	require_once(ABSPATH.'wp-admin/includes/upgrade.php'); // dbDelta()
	//
	if(!empty($wpdb->charset)) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if(!empty($wpdb->collate)) $charset_collate .= " COLLATE $wpdb->collate";
	//
	$nom = $wpdb->base_prefix . 'rencontre_dbip';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`ip_start` bigint unsigned NOT NULL,
			`ip_end` bigint unsigned NOT NULL,
			`country` char(2) NOT NULL,
			PRIMARY KEY (`ip_start`)
			) $charset_collate;";
		dbDelta($sql); // necessite wp-admin/includes/upgrade.php
	}
	//
	$nom = $wpdb->prefix . 'rencontre_profil';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`id` smallint unsigned NOT NULL auto_increment,
			`i_categ` tinyint unsigned NOT NULL,
			`i_label` tinyint unsigned NOT NULL,
			`c_categ` varchar(50) NOT NULL,
			`c_label` varchar(100) NOT NULL,
			`t_valeur` text,
			`i_type` tinyint NOT NULL,
			`i_poids` tinyint NOT NULL,
			`c_lang` varchar(2) NOT NULL,
			`c_genre` varchar(255) DEFAULT 0,
			INDEX (`id`)
			) $charset_collate;";
		dbDelta($sql);
	}
	//
	$nom = $wpdb->prefix . 'rencontre_users';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`user_id` bigint(20) unsigned UNIQUE NOT NULL,
			`c_ip` varchar(50) NOT NULL,
			`c_pays` varchar(50) NOT NULL,
			`c_region` varchar(50) NOT NULL,
			`c_ville` varchar(50) NOT NULL,
			`e_lat` decimal(10,5) NOT NULL,
			`e_lon` decimal(10,5) NOT NULL,
			`i_sex` tinyint NOT NULL,
			`d_naissance` date NOT NULL,
			`i_taille` tinyint unsigned NOT NULL,
			`i_poids` tinyint unsigned NOT NULL,
			`i_zsex` tinyint NOT NULL,
			`c_zsex` varchar(50) NOT NULL,
			`i_zage_min` tinyint unsigned NOT NULL,
			`i_zage_max` tinyint unsigned NOT NULL,
			`i_zrelation` tinyint NOT NULL,
			`c_zrelation` varchar(50) NOT NULL,
			`i_photo` bigint(20) unsigned NOT NULL,
			`d_session` datetime NOT NULL,
			`i_status` tinyint unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY (`user_id`)
			) $charset_collate;";
		dbDelta($sql);
	}
	//
	$nom = $wpdb->prefix . 'rencontre_users_profil';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`user_id` bigint(20) unsigned UNIQUE NOT NULL,
			`d_modif` datetime NULL,
			`t_titre` tinytext,
			`t_annonce` text,
			`t_profil` text,
			`t_action` text,
			`t_signal` text,
			PRIMARY KEY (`user_id`)
			) $charset_collate;";
		dbDelta($sql);
	}
	//
	$nom = $wpdb->prefix . 'rencontre_msg';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`id` bigint(20) NOT NULL auto_increment,
			`content` text NOT NULL,
			`sender` varchar(60) NOT NULL,
			`recipient` varchar(60) NOT NULL,
			`date` datetime NOT NULL,
			`read` tinyint(1) NOT NULL,
			`deleted` tinyint(1) NOT NULL,
			PRIMARY KEY (`id`)
			) $charset_collate;";
		dbDelta($sql);
	}
	//
	$nom = $wpdb->prefix . 'rencontre_prison';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`id` smallint unsigned NOT NULL auto_increment,
			`d_prison` datetime NOT NULL,
			`c_mail` varchar(100) NOT NULL,
			`c_ip` varchar(50) NOT NULL,
			`i_type` tinyint NOT NULL,
			PRIMARY KEY (`id`)
			) $charset_collate;";
		dbDelta($sql);
	}
	//
	$n = 0;
	$nom = $wpdb->prefix . 'rencontre_liste';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`id` smallint unsigned NOT NULL auto_increment,
			`c_liste_categ` varchar(50) NOT NULL,
			`c_liste_valeur` varchar(50) NOT NULL,
			`c_liste_iso` varchar(2) NOT NULL,
			`c_liste_lang` varchar(2) NOT NULL,
			PRIMARY KEY (`id`)
			) $charset_collate;";
		dbDelta($sql);
	}
	else $n = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."rencontre_liste");
	if(!$n && file_exists(dirname(__FILE__).'/inc/rencontre_liste_defaut.txt')) {
		$f = file_get_contents(dirname(__FILE__).'/inc/rencontre_liste_defaut.txt');
		global $wpdb;
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."rencontre_liste AUTO_INCREMENT = 1");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."rencontre_liste (c_liste_categ, c_liste_valeur, c_liste_iso, c_liste_lang) VALUES ".$f);
	}
}
//
// **********************************************************************************
// CLASSE Rencontre
// **********************************************************************************
if(is_admin()) require(dirname(__FILE__).'/inc/base.php'); // Check for an administrative interface page
new Rencontre();
class Rencontre {
	function __construct() {
		// Variables globale Rencontre
		global $rencOpt, $rencDiv, $wpdb, $rencCustom;
		$upl = wp_upload_dir();
		$rencDiv['basedir'] = str_replace('\\', '/', $upl['basedir']); // Windows backwards => forward slashes
		$rencDiv['baseurl'] = $upl['baseurl'];
		$rencDiv['blogname'] = get_option('blogname');
		$rencDiv['admin_email'] = get_option('admin_email');
		$rencDiv['siteurl'] = site_url();
		$l = get_site_option('WPLANG');
		$rencDiv['lang'] = ((defined('WPLANG')&&WPLANG)?WPLANG:($l?$l:'en_US')); // WP ADMIN locale set
		$rencDiv['lang1'] = get_locale(); // user locale (cookie ? language plugin ?)
		$l2 = $wpdb->get_var("SELECT c_lang FROM ".$wpdb->prefix."rencontre_profil WHERE c_lang='".strtolower(substr($rencDiv['lang1'],0,2))."' LIMIT 1");
		$rencDiv['lang2'] = (!empty($l2)?$rencDiv['lang1']:(!empty($rencOpt['lang2'])?$rencOpt['lang2']:$rencDiv['lang1'])); // Profile lang
		$l3 = $wpdb->get_var("SELECT c_liste_lang FROM ".$wpdb->prefix."rencontre_liste WHERE c_liste_lang='".strtolower(substr($rencDiv['lang1'],0,2))."' LIMIT 1");
		$rencDiv['lang3'] = (!empty($l3)?$rencDiv['lang1']:(!empty($rencOpt['lang3'])?$rencOpt['lang3']:$rencDiv['lang1'])); // Country (select) lang
		$rencCustom = (isset($rencOpt['custom'])?json_decode((empty($rencOpt['custom'])?'{}':$rencOpt['custom']),true):array());
		if(!isset($rencOpt['for'])) $rencOpt['for'] = array();
		add_action('init', function() { // Hook already loaded...
			global $rencOpt, $rencCustom;
			$rencOpt['for'][0] = __('Serious relationship','rencontre');
			$rencOpt['for'][1] = __('Open relationship','rencontre');
			$rencOpt['for'][2] = __('Friendship','rencontre');
			if(isset($rencCustom['relation'])) {
				$c = 0;
				while(isset($rencCustom['relationL'.$c])) {
					$rencOpt['for'][$c+3] = rencTranslate('relationL'.$c);
					++$c;
				}
			}
			$rencOpt['iam'][0] = __('a man','rencontre');
			$rencOpt['iam'][1] = __('a woman','rencontre');
			if(isset($rencCustom['sex'])) {
				$c = 0;
				while(isset($rencCustom['sexL'.$c])) {
					$rencOpt['iam'][$c+2] = rencTranslate('sexL'.$c);
					++$c;
				}
			}
		}, 5); // Fired before rencontre_filter.php - f_cron()
		add_action('widgets_init', array($this, 'rencwidget'), 10); // WIDGET
		if(is_admin()) { // Check for an administrative interface page
			add_action('admin_menu', array($this, 'admin_menu_link')); // Menu admin
			add_action('admin_print_scripts', array($this, 'adminCSS')); // CSS pour le bouton du menu
			if(file_exists(dirname(__FILE__).'/inc/patch.php') && $wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."rencontre_users' ")==$wpdb->prefix."rencontre_users") include(dirname(__FILE__).'/inc/patch.php'); // VERSIONS PATCH - ONLY ONCE - NOT DURING ACTIVATION
			global $pagenow;
			if('nav-menus.php'===$pagenow) add_action('admin_init','rencMetaMenu'); // Rencontre menu items in admin menu tab - base.php
			if(file_exists(dirname(__FILE__).'/rencontre_github.php')) include(dirname(__FILE__).'/rencontre_github.php');
		}
	}
	//
	function admin_menu_link() {
		if(current_user_can("manage_options")) {
			add_menu_page('Rencontre', 'Rencontre', 'manage_options', basename(__FILE__), array(&$this, 'menu_general'), 'div', 30); // ajoute un menu Rencontre (et son premier sous-menu)
			add_submenu_page('rencontre.php', __('Rencontre - General','rencontre'), __('General','rencontre'), 'manage_options', 'rencontre.php', array(&$this, 'menu_general')); // repete le premier sous-menu (pour changer le nom)
			add_submenu_page('rencontre.php', __('Rencontre - Members','rencontre'), __('Members','rencontre'), 'manage_options', 'rencmembers', array(&$this, 'menu_membres'));
			add_submenu_page('rencontre.php', __('Rencontre - Jail','rencontre'), __('Jail','rencontre'), 'manage_options', 'rencjail', array(&$this, 'menu_prison'));
			add_submenu_page('rencontre.php', __('Rencontre - Profile','rencontre'), __('Profile','rencontre'), 'manage_options', 'rencprofile', array(&$this, 'menu_profil'));
			add_submenu_page('rencontre.php', __('Rencontre - Countries','rencontre'), __('Country','rencontre'), 'manage_options', 'renccountry', array(&$this, 'menu_pays'));
			add_submenu_page('rencontre.php', __('Rencontre - Custom','rencontre'), __('Custom','rencontre'), 'manage_options', 'renccustom', array(&$this, 'menu_custom'));
		}
		else if(current_user_can("bbp_moderator")) {
			add_menu_page('Rencontre', 'Rencontre', 'bbp_moderator', basename(__FILE__), array(&$this, 'menu_membres'), 'div', 30); // ajoute un menu Rencontre (et son premier sous-menu)
			add_submenu_page('rencontre.php', __('Rencontre - Members','rencontre'), __('Members','rencontre'), 'bbp_moderator', 'rencmembers', array(&$this, 'menu_membres'));
			add_submenu_page('rencontre.php', __('Rencontre - Jail','rencontre'), __('Jail','rencontre'), 'bbp_moderator', 'rencjail', array(&$this, 'menu_prison'));
		}
	}
	//
	function menu_general() {rencMenuGeneral();} // base.php include if is_admin
	function menu_membres() {rencMenuMembres();}
	function menu_prison() {rencMenuPrison();}
	function menu_profil() {rencMenuProfil();}
	function menu_pays() {rencMenuPays();}
	function menu_custom() {rencMenuCustom();}
	//
	function rencwidget() { // loaded at WIDGET-INIT
		global $rencOpt, $rencDiv, $wpdb;
		if(!headers_sent() && empty(session_id())) session_start();
		if(0) { // reset manually OPTIONS
			$rencOpt = array('passw'=>1,'rol'=>1,'pays'=>'FR','limit'=>5,'dynsearch'=>1,'hcron'=>3,'msgdel'=>3,'qmail'=>25,'npa'=>12,'jlibre'=>3,'prison'=>30,'anniv'=>1,'ligne'=>1,'mailsupp'=>1,'onlyphoto'=>1,'imnb'=>4,'imcopyright'=>1);
			$nu = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->base_prefix."users");
			if($nu<10) unset($rencOpt['rol']);
			update_option('rencontre_options', $rencOpt);
		}
		$rencOpt['nbr'] = rencNumbers();
		$rencOpt['lbl'] = rencLabels();
		if(empty($rencOpt['home']) && !current_user_can("administrator")) $rencOpt['home'] = home_url();
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
		$Lfr = (!empty($rencOpt['lbl']['rencfastreg'])?$rencOpt['lbl']['rencfastreg']:'rencfastreg');
		$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
		$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
		$Lidf = (!empty($rencOpt['lbl']['rencidfm'])?$rencOpt['lbl']['rencidfm']:'rencidfm');
		$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
		$Lcard = (!empty($rencOpt['lbl']['card'])?$rencOpt['lbl']['card']:'card');
		$Ledit = (!empty($rencOpt['lbl']['edit'])?$rencOpt['lbl']['edit']:'edit');
		$Lmsg = (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg');
		$Laccount = (!empty($rencOpt['lbl']['account'])?$rencOpt['lbl']['account']:'account');
		$Lgsearch = (!empty($rencOpt['lbl']['gsearch'])?$rencOpt['lbl']['gsearch']:'gsearch');
		$Lliste = (!empty($rencOpt['lbl']['liste'])?$rencOpt['lbl']['liste']:'liste');
		$Lqsearch = (!empty($rencOpt['lbl']['qsearch'])?$rencOpt['lbl']['qsearch']:'qsearch');
		$Lwrite = (!empty($rencOpt['lbl']['write'])?$rencOpt['lbl']['write']:'write');
		$Lsourire = (!empty($rencOpt['lbl']['sourire'])?$rencOpt['lbl']['sourire']:'sourire');
		$Ldemcont = (!empty($rencOpt['lbl']['demcont'])?$rencOpt['lbl']['demcont']:'demcont');
		$Lsignale = (!empty($rencOpt['lbl']['signale'])?$rencOpt['lbl']['signale']:'signale');
		$Lbloque = (!empty($rencOpt['lbl']['bloque'])?$rencOpt['lbl']['bloque']:'bloque');
		$LfavoriAdd = (!empty($rencOpt['lbl']['favoriAdd'])?$rencOpt['lbl']['favoriAdd']:'favoriAdd');
		$LfavoriDel = (!empty($rencOpt['lbl']['favoriDel'])?$rencOpt['lbl']['favoriDel']:'favoriDel');
		$Lzsex = (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex');
		$Lz2sex = (!empty($rencOpt['lbl']['z2sex'])?$rencOpt['lbl']['z2sex']:'z2sex');
		$Lagemin = (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin');
		$Lagemax = (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax');
		$Ltaillemin = (!empty($rencOpt['lbl']['tailleMin'])?$rencOpt['lbl']['tailleMin']:'tailleMin');
		$Ltaillemax = (!empty($rencOpt['lbl']['tailleMax'])?$rencOpt['lbl']['tailleMax']:'tailleMax');
		$Lpoidsmin = (!empty($rencOpt['lbl']['poidsMin'])?$rencOpt['lbl']['poidsMin']:'poidsMin');
		$Lpoidsmax = (!empty($rencOpt['lbl']['poidsMax'])?$rencOpt['lbl']['poidsMax']:'poidsMax');
		$Lpays = (!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays');
		$Lregion = (!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region');
		$Lville = (!empty($rencOpt['lbl']['ville'])?$rencOpt['lbl']['ville']:'ville');
		$Lrelation = (!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation');
		$Lprofilqs = (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS');
		$Lphoto = (!empty($rencOpt['lbl']['photo'])?$rencOpt['lbl']['photo']:'photo');
		$Lgps = (!empty($rencOpt['lbl']['gps'])?$rencOpt['lbl']['gps']:'gps');
		$Lkm = (!empty($rencOpt['lbl']['km'])?$rencOpt['lbl']['km']:'km');
		$Lfin = (!empty($rencOpt['lbl']['fin'])?$rencOpt['lbl']['fin']:'fin');
		$Lpaswd = (!empty($rencOpt['lbl']['paswd'])?$rencOpt['lbl']['paswd']:'paswd');
		//
		if(isset($_GET[$Lfr]) && !empty($_GET[$Loo]) && !empty($_GET[$Lii]) && AUTH_KEY) {
			$Grencoo = rencSanit($_GET[$Loo],'b64');
			$Grencii = rencSanit($_GET[$Lii],'b64');
			$clair = '';
			if(function_exists('openssl_encrypt')) $clair = openssl_decrypt(base64_decode($Grencoo), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, base64_decode($Grencii));
			$c = explode('|', $clair);
			if(is_array($c) && count($c)==4) {
				$user = get_user_by('login',$c[1]);
				if($user) {
					if($c[2]!='confirm') wp_set_password(substr($c[2],1),$c[0]);
					wp_clear_auth_cookie();
					wp_set_current_user($c[0], $c[1]);
					wp_set_auth_cookie($c[0]);
					do_action('wp_login', $c[1], $user);
					if(is_user_logged_in()) {
						if($c[2]=='confirm') {
							$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$c[0], 'meta_key'=>'rencontre_confirm_email'));
							echo "<script language='JavaScript'>document.location.href='".(isset($rencOpt['home'])?$rencOpt['home']:'')."?".$Lidf."=rencfastreg';</script>";
						}
						else echo "<script language='JavaScript'>document.location.href='".(isset($rencOpt['home'])?$rencOpt['home']:'')."';</script>";
					}
				}
			}
		}
		//
		if(current_user_can("administrator")) {
			require(dirname (__FILE__).'/inc/rencontre_widget.php');
			$ho = false; if(has_filter('rencWidget')) $ho = apply_filters('rencWidget', $ho);
			if($ho) register_widget("RencontreWidget"); // class
			register_widget("RencontreSidebarWidget"); // class
		}
		else if(is_user_logged_in()) {
		//	if(!current_user_can("edit_posts") && !current_user_can("bbp_moderator")) show_admin_bar(false);
			global $current_user, $rencCustom, $rencU0;
			if(has_filter('rencInitHook')) $ho = apply_filters('rencInitHook', 0);
			if(empty($current_user->user_login)) $current_user = wp_get_current_user();
			if(empty($_SESSION['rencontre'])) $_SESSION['rencontre'] = 'mini,accueil,menu';
			$rencTokc = wp_create_nonce('rencTokc'); $_SESSION['rencTokc'] = $rencTokc; // Only for Chat (AJAX outside WP)
			if(isset($_GET[$Lidf])) { // acces a la fiche d un membre depuis un lien email
				$Grencidfm = rencSanit(rencGetId($_GET[$Lidf],1),'alphanum');
				if(substr($Grencidfm,0,1)=='c') $_SESSION['rencontre'] = 'card,menu,demcont';
				else if(substr($Grencidfm,0,1)=='m') $_SESSION['rencontre'] = 'write,accueil,menu';
				else if(substr($Grencidfm,0,1)=='p') $_SESSION['rencontre'] = 'card,menu';
				else if(substr($Grencidfm,0,1)=='s') $_SESSION['rencontre'] = 'card,menu,sourire';
				else if(substr($Grencidfm,0,1)=='r') $_SESSION['rencontre'] = 'msg,accueil,menu';
			}
			rencU0($current_user->ID); // Load U0 - Rencontre_filter
			$ip = (isset($rencU0->c_ip)?$rencU0->c_ip:0);
			// rencDiv USER VAR
			$rencDiv['action'] = (isset($rencU0->t_action)?$rencU0->t_action:0);
			$rencDiv['istatus'] = (isset($rencU0->i_status)?$rencU0->i_status:0);
			if(isset($rencU0->i_status) && !empty($rencOpt['mandatory']) && $rencOpt['mandatory']>strtotime($q->d_modif)) rencUserCheckMandatory($current_user->ID);
			$rencDiv['rencBlock'] = (isset($rencU0->i_status)?rencistatus($rencDiv['istatus'],0):0); // (($rencDiv['istatus']==1 || $rencDiv['istatus']==3)?1:0); // blocked
			if(isset($rencU0->i_status) && isset($rencOpt['blockmand']) && $rencOpt['blockmand']==2 && rencistatus($rencDiv['istatus'],3)) $rencDiv['rencBlock'] = 1; // Mandatory fiel empty => ON
			$rencDiv['mpause'] = '';
			if(empty($rencOpt['paus'])) {
				if(strpos($rencDiv['action'].'-',',pause2,')!==false) $rencDiv['mpause'] = 'pause2';
				else if(strpos($rencDiv['action'].'-',',pause1,')!==false) $rencDiv['mpause'] = 'pause1';
			}
			$rencDiv['pacam'] = false;
			$rencDiv['pacas'] = false;
			$rencDiv['titann'] = ((isset($rencU0->t_titre) && strlen($rencU0->t_titre)>4 && strlen($rencU0->t_annonce)>30) ? $rencU0->ID : null);
			if(!empty($rencOpt['pacamsg']) || !empty($rencOpt['pacasig'])) {
				if(!$rencDiv['titann']) $a = true; // photo & attention-catcher & ad ; null => OK
				else $a = (($wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$current_user->ID."' LIMIT 1"))?false:true);
				$rencDiv['pacam'] = (!empty($rencOpt['pacamsg']))?$a:false;
				$rencDiv['pacas'] = (!empty($rencOpt['pacasig']))?$a:false;
			}
			//
			$Prenc = (!empty($_POST['P'.$Lrenc])?rencSanit($_POST['P'.$Lrenc],'alphanum'):''); // Post used in message part
			$Grenc = (!empty($_GET[$Lrenc])?rencSanit($_GET[$Lrenc],'alphanum'):'');
			$Pnouveau = (!empty($_POST['nouveau'])?rencSanit($_POST['nouveau'],'alphanum'):'');
			$Ppause = (!empty($_POST['pause'])?rencSanit($_POST['pause'],'int'):0); // POST fm account
			$Gid = (isset($_GET[$Lid])?rencSanit(rencGetId($_GET[$Lid],1),'alphanum'):''); // ID or action (sourireIn...
			$spot = (!empty($Prenc)?$Prenc:((!empty($Pnouveau)&&$Pnouveau!='update')?'':(!empty($Grenc)?$Grenc:'')));
			$hopage = false; if(has_filter('rencPage')) $hopage = apply_filters('rencPage', $spot);
			if(!$ip && $Pnouveau!='OK') {
				if(empty($current_user->display_name) || strpos($current_user->display_name.'-', '@')!==false) {
					$disnam = explode('@', $current_user->user_login);
					if(!empty($disnam[0])) wp_update_user(array('ID'=>$current_user->ID, 'display_name'=>$disnam[0]));
				}
				if($Pnouveau==='') {
//					$q = $wpdb->get_row("SELECT
//							c_ville,
//							c_zsex
//						FROM
//							".$wpdb->prefix."rencontre_users
//						WHERE
//							user_id='".$current_user->ID."'
//						LIMIT 1
//						");
					if(!empty($rencU0->c_ville) || (isset($rencCustom['place']) && $q)) $_SESSION['rencontre'] = 'nouveau2';
					else if(isset($rencU0->c_ville)) $_SESSION['rencontre'] = 'nouveau1';
					else $_SESSION['rencontre'] = 'nouveau';
				}
				else {
					if($Pnouveau=='1' && isset($rencCustom['place'])) $_SESSION['rencontre'] = 'nouveau2';
					else if($Pnouveau=='1') $_SESSION['rencontre'] = 'nouveau1';
					else if($Pnouveau=='2') $_SESSION['rencontre'] = 'nouveau2';
				}
			}
			else if(empty($spot) && !isset($_GET[$Lidf])) { // my home page & after registration part3
				$_SESSION['rencontre'] = 'mini,accueil,menu';
				$Pnouveau = (isset($_POST['nouveau'])?rencSanit($_POST['nouveau'],'alphanum'):'');
				if($Pnouveau=='OK') {
					$_SESSION['rencontre'] = 'edit,menu';
					self::rencIniUser();
				}
			}
			else if($hopage) $_SESSION['rencontre'] = $hopage;
			else if($spot==$Lpaswd) $_SESSION['rencontre'] = 'mini,accueil,menu,paswd';
			else if($spot==$Lfin) {
				$photo = self::f_img((($current_user->ID)*10).'-libre',2);
				if(file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg')) {
					unlink($rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg');
					renc_clear_cache_portrait();
				}
				f_userSupp($current_user->ID,$current_user->user_login,0);
				if(!empty($rencOpt['mailsupp'])) {
					$a = rencModerType(); // rencontre_filter.php
					$objet = (!empty($a['title'])?$a['title']:wp_specialchars_decode($rencDiv['blogname'], ENT_QUOTES).' - '.__('Account deletion','rencontre'));
					$message  = (!empty($a['content'])?$a['content']:__('Your account has been deleted','rencontre'));
					@wp_mail($current_user->user_email, $objet, $message);
				}
			}
			else if($spot==$Lcard) {
				if(!empty($rencDiv['mpause']) && $Gid!=$current_user->ID) $_SESSION['rencontre'] = 'mini,accueil,menu,'.$rencDiv['mpause'];
				else $_SESSION['rencontre'] = 'card,menu';
			}
			else if($spot==$Lsourire) $_SESSION['rencontre'] = 'card,menu,sourire';
			else if($spot==$Ldemcont) $_SESSION['rencontre'] = 'card,menu,demcont';
			else if($spot==$Lsignale) $_SESSION['rencontre'] = 'card,menu,signale';
			else if($spot==$Lbloque) $_SESSION['rencontre'] = 'card,menu,bloque';
			else if($spot==$LfavoriAdd) $_SESSION['rencontre'] = 'card,menu,favoriAdd';
			else if($spot==$LfavoriDel) $_SESSION['rencontre'] = 'card,menu,favoriDel';
			else if($spot==$Ledit || ($spot==$Laccount && !empty($rencOpt['accprof']))) $_SESSION['rencontre'] = 'edit,menu';
			else if($spot==$Lgsearch) $_SESSION['rencontre'] = 'gsearch,accueil,menu';
			else if($spot=='qsearchs') $_SESSION['rencontre'] = 'qsearch,accueil,menu,qsearchs';
			else if($spot=='qsearchis') $_SESSION['rencontre'] = 'qsearch,accueil,menu,qsearchis';
			else if($spot=='qsearchc') $_SESSION['rencontre'] = 'qsearch,accueil,menu,qsearchc';
			else if($spot=='qsearchic') $_SESSION['rencontre'] = 'qsearch,accueil,menu,qsearchic';
			else if($spot=='qsearchl') $_SESSION['rencontre'] = 'qsearch,accueil,menu,qsearchl';
			else if($spot=='qsearchib') $_SESSION['rencontre'] = 'qsearch,accueil,menu,qsearchib';
			else if($spot==$Lqsearch) { // Quick Search result
				$blockQSearch = false; if(is_numeric($Gid) && has_filter('rencLimitedActionP')) $blockQSearch = apply_filters('rencLimitedActionP', array('qsearch',0,0)); // security - Already done before
				$_SESSION['rencontre'] = ($blockQSearch?'mini,accueil,menu':'qsearch,accueil,menu');
				if(isset($_COOKIE['searchmem'.$current_user->ID])) $mem = json_decode((empty(base64_decode($_COOKIE['searchmem'.$current_user->ID]))?'{}':base64_decode($_COOKIE['searchmem'.$current_user->ID])),true);
				$mem = array(
					'zage_min'=>(isset($_GET[$Lagemin])?rencSanit($_GET[$Lagemin],'int'):''),
					'zage_max'=>(isset($_GET[$Lagemax])?rencSanit($_GET[$Lagemax],'int'):''),
					'size_min'=>(isset($mem['size_min'])?$mem['size_min']:''),
					'size_max'=>(isset($mem['size_max'])?$mem['size_max']:''),
					'weight_min'=>(isset($mem['weight_min'])?$mem['weight_min']:''),
					'weight_max'=>(isset($mem['weight_max'])?$mem['weight_max']:''),
					'zsex'=>(isset($mem['zsex'])?$mem['zsex']:''),
					'z2sex'=>(isset($mem['z2sex'])?$mem['z2sex']:''),
					'country'=>(!empty($_GET[$Lpays])?rencSanit($_GET[$Lpays],'AZ'):'i'), // i : no matter selected => no region
					'region'=>(isset($_GET[$Lregion])?rencSanit($_GET[$Lregion],'alphanum'):''), // INT or ADMIN1_CODE in GEONAMES
					'city'=>(isset($mem['city'])?stripslashes($mem['city']):''),
					'gps'=>(isset($mem['gps'])?$mem['gps']:''),
					'km'=>(isset($mem['km'])?$mem['km']:''),
					'photo'=>(isset($mem['photo'])?$mem['photo']:''),
					'relation'=>(isset($mem['relation'])?$mem['relation']:''),
					'profilQS1'=>(isset($_GET[$Lprofilqs.'1'])?rencSanit($_GET[$Lprofilqs.'1'],'alphanum'):''),
					'profilQS2'=>(isset($_GET[$Lprofilqs.'2'])?rencSanit($_GET[$Lprofilqs.'2'],'alphanum'):'')
					);
				$ho = false; if(has_filter('searchmem')) $ho = apply_filters('searchmem',$ho);
				if(is_array($ho)) $mem = array_merge($mem, $ho);
				setcookie('searchmem'.$current_user->ID, base64_encode(json_encode($mem)), time()+(86400*90), '/'); // 90 days
			}
			else if($spot==$Lliste) { // Search result
				$blockSearch = false; if(has_filter('rencLimitedActionP')) $blockSearch = apply_filters('rencLimitedActionP', array('search',0,0)); // security - Already done before
				$_SESSION['rencontre'] = ($blockSearch?'mini,accueil,menu':'gsearch,liste,accueil,menu');
				// $mem : array(zage_min, zage_max, size_min, size_max, weight_min, weight_max, zsex, country, region, city, gps, km, photo, relation)
				if(isset($_COOKIE['searchmem'.$current_user->ID])) $mem1 = json_decode((empty(base64_decode($_COOKIE['searchmem'.$current_user->ID]))?'{}':base64_decode($_COOKIE['searchmem'.$current_user->ID])),true);
				$mem = array(
					'zage_min'=>(isset($_GET[$Lagemin])?rencSanit($_GET[$Lagemin],'int'):''),
					'zage_max'=>(isset($_GET[$Lagemax])?rencSanit($_GET[$Lagemax],'int'):''),
					'size_min'=>(isset($_GET[$Ltaillemin])?rencSanit($_GET[$Ltaillemin],'int'):''),
					'size_max'=>(isset($_GET[$Ltaillemax])?rencSanit($_GET[$Ltaillemax],'int'):''),
					'weight_min'=>(isset($_GET[$Lpoidsmin])?rencSanit($_GET[$Lpoidsmin],'int'):''),
					'weight_max'=>(isset($_GET[$Lpoidsmax])?rencSanit($_GET[$Lpoidsmax],'int'):''),
					'zsex'=>(isset($_GET[$Lzsex])?rencSanit($_GET[$Lzsex],'int'):''),
					'z2sex'=>((isset($_GET[$Lz2sex])&&$_GET[$Lz2sex]!=='')?rencSanit($_GET[$Lz2sex],'int'):''),
					'country'=>(!empty($_GET[$Lpays])?rencSanit($_GET[$Lpays],'AZ'):'i'), // i : no matter selected => no region
					'region'=>(isset($_GET[$Lregion])?rencSanit($_GET[$Lregion],'alphanum'):''), // INT or ADMIN1_CODE in GEONAMES
					'city'=>(isset($_GET[$Lville])?rencSanit($_GET[$Lville],'words'):''),
					'gps'=>(isset($_GET[$Lgps])?rencSanit($_GET[$Lgps],'pipe'):''),
					'km'=>(isset($_GET[$Lkm])?rencSanit($_GET[$Lkm],'int'):''),
					'photo'=>(isset($_GET[$Lphoto])?rencSanit($_GET[$Lphoto],'int'):''),
					'relation'=>(isset($_GET[$Lrelation])?rencSanit($_GET[$Lrelation],'int'):''),
					'profilQS1'=>(isset($mem1['profilQS1'])?$mem1['profilQS1']:''),
					'profilQS2'=>(isset($mem1['profilQS2'])?$mem1['profilQS2']:'')
					);
				$ho = false; if(has_filter('searchmem')) $ho = apply_filters('searchmem',$ho);
				if(is_array($ho)) $mem = array_merge($mem, $ho);
				setcookie('searchmem'.$current_user->ID, base64_encode(json_encode($mem)), time()+(86400*90), '/'); // 90 days
			}
			else if($spot==$Lmsg) $_SESSION['rencontre'] = 'msg,accueil,menu';
			else if($spot==$Lwrite) $_SESSION['rencontre'] = 'write,accueil,menu';
			else if($spot==$Laccount) $_SESSION['rencontre'] = 'account,accueil,menu';
			else if($spot=='pause') {
				rencPause($Ppause,$current_user->ID);
				$_SESSION['rencontre'] = 'account,accueil,menu';
			}
			else if($spot=='c1') $_SESSION['rencontre'] = 'custom1,accueil,menu';
			else if($spot=='c2') $_SESSION['rencontre'] = 'custom2,accueil,menu';
			// avoid direct access to profile via bookmarks if not allowed
			if($Gid!=$current_user->ID && strstr($_SESSION['rencontre'],'card')) {
				$noProfile = false; if(has_filter('rencLimitedActionP')) $noProfile = apply_filters('rencLimitedActionP', array('profil',0,$Gid));
				if($noProfile) $_SESSION['rencontre'] = 'mini,accueil,menu';
			}
			//
			require(dirname (__FILE__).'/inc/rencontre_widget.php');
			if(!empty($rencOpt['fastreg'])) { // 0 or 1
				$rencOpt['fastreg'] = 1; // string to int
				$q = $wpdb->get_row("SELECT
						R.i_sex,
						R.i_status,
						IFNULL(M.umeta_id,0) AS umeta_id
					FROM
						".$wpdb->prefix."rencontre_users R
					LEFT JOIN
						".$wpdb->base_prefix."usermeta M
					ON
						R.user_id=M.user_id and
						M.meta_key='rencontre_confirm_email'
					WHERE
						R.user_id=".$current_user->ID."
					LIMIT 1
					");
				if(!empty($q)) {
					if($q->umeta_id) $rencOpt['fastreg'] += 2; // Email not confirmed (password not changed) : +2
					if($q->i_sex=="98") $rencOpt['fastreg'] += 1; // 98 : Fastreg account not completed : +1
					if($rencOpt['fastreg']<2 && rencistatus($q->i_status,2)) {
						$st = rencistatusSet($q->i_status,2,0);
						$wpdb->update($wpdb->prefix.'rencontre_users', array('i_status'=>$st), array('user_id'=>$current_user->ID)); // 4 : fastreg
					}
				}
			}
			$ho = false; if(has_filter('rencWidget')) $ho = apply_filters('rencWidget', $ho);
			if($ho) register_widget("RencontreWidget"); // class
			if(!empty($rencCustom['side'])) register_widget("RencontreSidebarWidget"); // class
		}
		// not connected
		else if(!empty($_GET[$Loo]) && !empty($_GET[$Lii]) && AUTH_KEY) { // autoconnect
			$Grencoo = rencSanit($_GET[$Loo],'b64');
			$Grencii = rencSanit($_GET[$Lii],'b64');
			$clair = '';
			if(function_exists('openssl_encrypt')) $clair = openssl_decrypt(base64_decode($Grencoo), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, base64_decode($Grencii));
			$c = explode('|', $clair);
			if(is_array($c) && count($c)==3 && $c[2]>time()-3024000) { // Validity : 35 days
				$user = get_user_by('login',$c[1]);
				if($user) {
					wp_set_current_user($c[0], $c[1]);
					wp_set_auth_cookie($c[0]);
					do_action('wp_login', $c[1], $user);
					if(is_user_logged_in()) {
						$d = '';
						if(!empty($_GET[$Lidf])) {
							$Grencidfm = rencSanit(rencGetId($_GET[$Lidf],1),'alphanum'); // Decrypt and Sanit
							$Grencidfm = rencGetId($Grencidfm,0); // Encrypt
							$d .= $Lidf.'='.$Grencidfm;
						//	if(ctype_alpha(substr($Grencidfm,0,1))) $d .= $Lidf.'='.substr($Grencidfm,0,1).intval(substr($Grencidfm,1));
						//	else $d .= $Lidf.'='.intval($Grencidfm);
						}
						echo "<script language='JavaScript'>document.location.href='".(isset($rencOpt['home'])?$rencOpt['home']:'').($d?'?'.$d:'')."';</script>";
					}
				}
			}
		}
	session_write_close();
	}
	//
	function adminCSS() {
		echo '<style type="text/css">
			#toplevel_page_rencontre .wp-menu-image {background:transparent url('.plugin_dir_url(__FILE__).'/images/menu.png) no-repeat scroll 3px -30px;}
			#toplevel_page_rencontre:hover .wp-menu-image {background-position:3px 3px;}
			</style>';
	}
	//
	static function f_age($naiss=0) { // transforme une date (TIME) en age
		if($naiss==0) return "-";
		list($annee, $mois, $jour) = explode('-', $naiss);
		$today['mois'] = date('n');
		$today['jour'] = date('j');
		$today['annee'] = date('Y');
		$age = $today['annee'] - $annee;
		if($today['mois'] <= $mois) {if($mois == $today['mois']) {if($jour > $today['jour'])$age--;}else$age--;}
		return $age;
	}
	//
	static function f_ficheLibre($a=array(),$ret=0) { // Creation du fichier HTML de presentation des membres en libre acces pour la page d accueil
		global $rencDiv, $rencCustom, $rencOpt;
		$atts = shortcode_atts(array(
			'gen'=>'',
			'country'=>'',
			'region'=>'',
			'city'=>'',
			'redirect'=>$rencDiv['siteurl'].'/wp-login.php?action=register'
			),$a);
		$redirect = $atts['redirect'];
		$tdir = rencTplDir();
		$out = ''; $sc = '';
		if(!has_filter('rencFicheLibre')) {
			if(!wp_style_is('w3css') && !empty($tdir['original'])) $out .= '<link rel="stylesheet" href="'.plugins_url('rencontre/css/w3.css').'" />'."\r\n";
			if(!wp_style_is('rencontre') && !empty($tdir['original'])) $out .= '<link rel="stylesheet" href="'.plugins_url('rencontre/css/rencontre.css').'" />'."\r\n";
		}
		if(!file_exists($rencDiv['basedir'].'/portrait/cache/cache_portraits_accueil'.($atts['gen']?$atts['gen']:'').'.html')) {
			$ho = false; if(has_filter('rencAds3P')) $ho = apply_filters('rencAds3P', $ho);
			if($ho) $out .= $ho."\r\n";
			//
			$size = rencPhotoSize(); $wlibre = '';
			foreach($size as $s) if($s['label']=='-libre') $wlibre = $s['width'];
			//
			$wlm = (!empty($rencOpt['wlibre'])?$rencOpt['wlibre']:(!empty($wlibre)?$wlibre:200));
			$ws = (!empty($rencOpt['wslibre'])?$rencOpt['wslibre']:128);
			$photoWidth = $wlm; // TPL
			if(!empty($rencCustom['fitw'])) {
				$sc .= '<script type="text/javascript">';
				$sc .= 'jQuery(document).ready(function(){';
				$sc .= 'var a=jQuery(".ficheLibre").width(),b='.$wlm.';';
				$sc .= 'if(window.matchMedia("(max-width:600px)").matches)b='.$ws.';';
				$sc .= 'var c=Math.max(Math.floor((a+16)/(b+16)),1);';
				$sc .= 'jQuery(".rencLibrePortrait").width(Math.min(Math.floor((a+16)/c)-16,('.$wlm.')));});';
				$sc .= '</script>'."\r\n";
			}
			$out .= '<style type="text/css">';
			$out .= '.rencLibrePortrait{width:'.($wlm+16).'px}'; // padding 8px (w3-padding-small)
			$out .= '@media(max-width:600px){.rencLibrePortrait{width:'.$ws.'px}}';
			$out .= '</style>'."\r\n";
			$out .= '<div id="widgRenc" class="widgRenc ficheLibre w3-row">'."\r\n";
			$out .= '<div style="margin-left:-8px;margin-right:-8px;">'."\r\n";
			global $wpdb, $rencOpt, $rencCustom;
			$npa = (isset($rencOpt['npa'])?$rencOpt['npa']:10);
			$qpause = (empty($rencOpt['paus'])?"and (P.t_action NOT REGEXP ',pause1,|,pause2' or P.t_action IS NULL) ":"");
			if(!is_dir($rencDiv['basedir'].'/portrait/libre/')) mkdir($rencDiv['basedir'].'/portrait/libre/');
			if(!isset($rencCustom['libreFlag']) || !$rencCustom['libreFlag']) {
				$q = $wpdb->get_results("SELECT
						c_liste_categ,
						c_liste_valeur,
						c_liste_iso
					FROM
						".$wpdb->prefix."rencontre_liste
					WHERE
						c_liste_categ='d'
						or
						(c_liste_categ='p' and c_liste_lang='".substr($rencDiv['lang'],0,2)."')
					");
				$rencDrap = array(); $rencDrapNom = array();
				foreach($q as $r) {
					if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur;
					else if($r->c_liste_categ=='p')$rencDrapNom[$r->c_liste_iso] = $r->c_liste_valeur;
				}
			}
			if($atts['gen']=='mix') { // repartition homogene hommes / femmes
				$qh = $wpdb->get_results("SELECT
						U.ID,
						U.display_name,
						U.user_registered,
						R.i_sex,
						R.i_zsex,
						R.c_pays,
						R.c_ville,
						R.d_naissance,
						R.i_photo,
						P.t_titre,
						P.t_annonce
					FROM ".$wpdb->base_prefix."users U
					INNER JOIN ".$wpdb->prefix."rencontre_users R
						ON R.user_id=U.ID
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=U.ID
					WHERE 
						R.i_status IN (0".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8').") 
						and R.i_photo!=0 
						and R.i_sex=0 
						and TO_DAYS(NOW())-TO_DAYS(U.user_registered)>=".(isset($rencOpt['jlibre'])?$rencOpt['jlibre']:0)." 
						and CHAR_LENGTH(P.t_titre)>4 
						and CHAR_LENGTH(P.t_annonce)>30
						and (P.t_action NOT LIKE '%,nofree,%' or P.t_action IS NULL)
						".$qpause."
					ORDER BY U.user_registered DESC
					LIMIT ".$npa);
				$qf = $wpdb->get_results("SELECT
						U.ID,
						U.display_name,
						U.user_registered,
						R.i_sex,
						R.i_zsex,
						R.c_pays,
						R.c_ville,
						R.d_naissance,
						R.i_photo,
						P.t_titre,
						P.t_annonce
					FROM ".$wpdb->base_prefix."users U
					INNER JOIN ".$wpdb->prefix."rencontre_users R
						ON R.user_id=U.ID
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=U.ID
					WHERE 
						R.i_status IN (0".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8').") 
						and R.i_photo!=0 
						and R.i_sex=1 
						and TO_DAYS(NOW())-TO_DAYS(U.user_registered)>=".(isset($rencOpt['jlibre'])?$rencOpt['jlibre']:0)." 
						and CHAR_LENGTH(P.t_titre)>4 
						and CHAR_LENGTH(P.t_annonce)>30
						and (P.t_action NOT LIKE '%,nofree,%' or P.t_action IS NULL)
						".$qpause."
						".($atts['country']?"and R.c_pays='".strtoupper($atts['country'])."'":"")."
						".($atts['region']?"and R.c_region='".$atts['region']."'":"")."
						".($atts['city']?"and R.c_ville='".$atts['city']."'":"")."
					ORDER BY U.user_registered DESC
					LIMIT ".$npa);
				reset($qh); reset($qf); $ch=0; $cf=0; $q=array(); $c=0;
				do {
					if(mt_rand(0,1) && $cf-$ch<5) { // femme
						if($cf==0 && $qf) {$q[]=current($qf); ++$cf; ++$c;}
						else if(next($qf)!==false) {$q[]=current($qf); ++$cf; ++$c;}
						else $ch=-10; // Fin
					}
					else if($ch-$cf<5) { // homme
						if($ch==0 && $qh) {$q[]=current($qh); ++$ch; ++$c;}
						else if(next($qh)!==false) {$q[]=current($qh); ++$ch; ++$c;}
						else $cf=-10; // Fin
					}
				}
				while(($ch+$cf)>-15 && $c<($npa)); // false = stop
			}
			else $q = $wpdb->get_results("SELECT
						U.ID,
						U.display_name,
						U.user_registered,
						R.i_sex,
						R.i_zsex,
						R.c_pays,
						R.c_ville,
						R.d_naissance,
						R.i_photo,
						P.t_titre,
						P.t_annonce
					FROM ".$wpdb->base_prefix."users U
					INNER JOIN ".$wpdb->prefix."rencontre_users R
						ON R.user_id=U.ID
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=U.ID
					WHERE 
						R.i_status IN (0".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8').") 
						and R.i_photo!=0 
						and TO_DAYS(NOW())-TO_DAYS(U.user_registered)>=".(isset($rencOpt['jlibre'])?$rencOpt['jlibre']:0)." 
						and CHAR_LENGTH(P.t_titre)>4 
						and CHAR_LENGTH(P.t_annonce)>30
						and (P.t_action NOT LIKE '%,nofree,%' or P.t_action IS NULL)
						".$qpause."
						".(($atts['gen']==='girl')?"and R.i_sex=1":"")."
						".(($atts['gen']==='men')?"and R.i_sex=0":"")."
						".(($atts['gen']==='gay')?"and R.i_sex=R.i_zsex":"")."
						".($atts['country']?"and R.c_pays='".strtoupper($atts['country'])."'":"")."
						".($atts['region']?"and R.c_region='".$atts['region']."'":"")."
						".($atts['city']?"and R.c_ville='".$atts['city']."'":"")."
					ORDER BY U.user_registered DESC
					LIMIT ".$npa);
			$c = 0; $ids = array();
			if($q) foreach($q as $u) {
				$ids[] = intval($u->ID);
				//
				$ad = substr(stripslashes($u->t_annonce),0,180);
				preg_match('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $ad, $m);
				$m[0] = (isset($m[0])?$m[0]:'');
				$ad = str_replace(array($m[0]), array(''), $ad);
				$ad = str_replace(', ', ',', $ad);
				$ad = str_replace(',', ', ', $ad);
				$ad = strtr($ad, "0123456789#(){[]}", ".................");
				$u->annonce = mb_substr($ad,0,150,'UTF-8').'...';
				//
				$ca = trim(stripslashes($u->t_titre));
				preg_match('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $ca, $m);
				$m[0] = (isset($m[0])?$m[0]:'');
				$ca = str_replace(array($m[0]), array(''), $ca);
				$ca = str_replace(', ', ',', $ca);
				$ca = str_replace(',', ', ', $ca);
				$ca = strtr($ca, "0123456789#(){[]}", ".................");
				$nbt = (!empty($rencOpt['nbr']['lengthTitle'])?$rencOpt['nbr']['lengthTitle']:30);
				if(strlen($ca)>$nbt) $ca = mb_substr($ca,0,$nbt,'UTF-8').'...';
				$u->title = $ca;
				//
				$nbn = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
				$u->display_name = mb_substr($u->display_name,0,$nbn,'UTF-8');
				//
				$photo = false; if(has_filter('rencBlurLibreP')) $photo = apply_filters('rencBlurLibreP', $u->ID);
				if(empty($photo)) $photo = self::f_img((($u->ID)*10).'-libre',2); // (($u->ID)*10).'-libre';
				$u->librePhoto = $rencDiv['baseurl'].'/portrait/libre/'.$photo.'.jpg';
				$u->libreID = $c;
				$u->genre='girl';
				if($u->i_sex==0 && $u->i_zsex==1) $u->genre='men';
				else if($u->i_sex==1 && $u->i_zsex==1) $u->genre='gaygirl';
				else if($u->i_sex==0 && $u->i_zsex==0) $u->genre='gaymen';
				if(!file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg') && file_exists($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'.jpg')) {
					@copy($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'.jpg', $rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg');
					// RETINA
					if(!empty($rencOpt['nbr']['retina']) && file_exists($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'@2x.jpg')) {
						@copy($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'@2x.jpg', $rencDiv['basedir'].'/portrait/libre/'.$photo.'@2x.jpg');
					}
					// WEBP
					if(function_exists('imagewebp')) {
						$imgJ = imagecreatefromjpeg($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'.jpg');
						$imgX = imagesx($imgJ);
						$imgY = imagesy($imgJ);
						$imgP = imagecreatetruecolor($imgX, $imgY);
						imagecopy($imgP, $imgJ, 0, 0, 0, 0, $imgX, $imgY);
						imagewebp($imgP, $rencDiv['basedir'].'/portrait/libre/'.$photo.'.webp', 75);
						imagedestroy($imgJ);
						imagedestroy($imgP);
						// WEBP RETINA
						if(!empty($rencOpt['nbr']['retina']) && file_exists($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'@2x.jpg')) {
							$imgJ = imagecreatefromjpeg($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.$photo.'@2x.jpg');
							$imgX = imagesx($imgJ);
							$imgY = imagesy($imgJ);
							$imgP = imagecreatetruecolor($imgX, $imgY);
							imagecopy($imgP, $imgJ, 0, 0, 0, 0, $imgX, $imgY);
							imagewebp($imgP, $rencDiv['basedir'].'/portrait/libre/'.$photo.'@2x.webp', 75);
							imagedestroy($imgJ);
							imagedestroy($imgP);
						}
					}
				}
				// Browser Caching Bypass
				$a = filemtime($rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg');
				$u->librePhoto .= '?' . $a;
				// RETINA
				$u->librePhotoRetina = '';
				if(file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo.'@2x.jpg')) $u->librePhotoRetina = $rencDiv['baseurl'].'/portrait/libre/'.$photo.'@2x.jpg?'.$a.' 2x';
				// WEBP
				$u->librePhotoWebp = '';
				if(file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo.'.webp')) {
					$u->librePhotoWebp = $rencDiv['baseurl'].'/portrait/libre/'.$photo.'.webp' . '?' . $a;
					if(file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo.'@2x.webp')) $u->librePhotoWebp .= ' 1x,'.$rencDiv['baseurl'].'/portrait/libre/'.$photo.'@2x.webp?'.$a.' 2x';
				}
				if(!isset($rencCustom['librePhoto'])) {
					if($u->c_pays!="" && !isset($rencCustom['country']) && !isset($rencCustom['place']) && (!isset($rencCustom['libreFlag']) || !$rencCustom['libreFlag'])) {
						$pays = strtr(mb_convert_encoding($u->c_pays, 'ISO-8859-1', 'UTF-8'), '��������������������������', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
						$pays = strtr($pays, '��������������������������� ', 'aaaaaaceeeeiiiinooooouuuuyy_');
						$pays = str_replace("'", "", $pays);
						$cpays = str_replace("'", "&#39;", $u->c_pays);
					}
				}
				// ****** TEMPLATE ********
				ob_start();
				if($tpl=rencTpl('rencontre_libre_portrait.php')) include $tpl;
				$out .= ob_get_clean();
				// ************************
				++$c;
			}
			$out .= "\r\n\t".'<div class="clear">&nbsp;</div>'."\r\n";
			$out .= '</div>'."\r\n"; // margin left right -8px
			$out .= '</div><!-- #widgRenc -->'."\r\n";
			$out .= $sc;
			$ho = false; if(has_filter('rencAds4P')) $ho = apply_filters('rencAds4P', $ho);
			if($ho) $out .= $ho;
			if(has_filter('rencFicheLibre')) $out = apply_filters('rencFicheLibre', $atts, $out);
			file_put_contents($rencDiv['basedir'].'/portrait/cache/cache_portraits_accueil'.$atts['gen'].'.html', $out);
			file_put_contents($rencDiv['basedir'].'/portrait/libre/libreIDs.json', json_encode($ids));
			if(!$ret) echo $out;
			else return $out; // SHORTCODE
		}
		else if($ret) { // SHORTCODE
			$out .= file_get_contents($rencDiv['basedir'].'/portrait/cache/cache_portraits_accueil'.$atts['gen'].'.html');
			return $out; 
		}
		else include($rencDiv['basedir'].'/portrait/cache/cache_portraits_accueil'.$atts['gen'].'.html');
	}
	//
	static function f_rencontreSearch($ret=0,$a=array()) { // SHORTCODE [rencontre_search nb=6 day=365]
		global $wpdb, $rencOpt, $rencDiv, $rencCustom;
		$atts = shortcode_atts(array('nb'=>6,'day'=>365),$a);
		$day = ($atts['day']?intval($atts['day']):365);
		$out = ''; $searchResult = '';
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
		$Lzsex = (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex');
		$Lagemin = (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin');
		$Lagemax = (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax');
		$Grenc = (isset($_GET[$Lrenc])?rencSanit($_GET[$Lrenc],'alphanum'):'');
		$Gzsex = (isset($_GET[$Lzsex])?rencSanit($_GET[$Lzsex],'int'):0);
		$Gagemin = (isset($_GET[$Lagemin])?rencSanit($_GET[$Lagemin],'int'):20);
		$Gagemax = (isset($_GET[$Lagemax])?rencSanit($_GET[$Lagemax],'int'):95);
		if($Grenc=='searchLibre') { // RESULT
			$size = rencPhotoSize();
			foreach($size as $s) if($s['label']=='-libre') $photoWidth = $s['width'];
			if(empty($photoWidth)) $photoWidth = 141;
			$q = $wpdb->get_results("SELECT
					c_liste_categ,
					c_liste_valeur,
					c_liste_iso
				FROM
					".$wpdb->prefix."rencontre_liste
				WHERE
					c_liste_categ='d'
					or
					(c_liste_categ='p' and c_liste_lang='".substr($rencDiv['lang'],0,2)."')
				");
			$rencDrap = array(); $rencDrapNom = array();
			foreach($q as $r) {
				if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur;
				else if($r->c_liste_categ=='p')$rencDrapNom[$r->c_liste_iso] = $r->c_liste_valeur;
			}
			$ses =date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"), date("d"), date("Y"))-(86400*$day));
			$s = "SELECT
					U.ID,
					U.display_name,
					R.i_sex,
					R.c_ville,
					R.i_photo,
					P.t_titre
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
					ON P.user_id=U.ID
				WHERE 
					R.i_status IN (0".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8').")
					and R.i_photo!=0 
					and R.i_sex=".$Gzsex."
					and CHAR_LENGTH(P.t_titre)>4 
					and CHAR_LENGTH(P.t_annonce)>30
					and R.d_session>'".$ses."'";
			if($Gagemin>18) {
				$zmin=date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$Gagemin));
				$s.=" and R.d_naissance<'".$zmin."'";
			}
			if($Gagemax<99) {
				$zmax=date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$Gagemax));
				$s.=" and R.d_naissance>'".$zmax."'";
			}
			$s .= "ORDER BY CHAR_LENGTH(P.t_action) DESC,U.ID DESC LIMIT ".$atts['nb'];			
			$q = $wpdb->get_results($s);
			$c = 0;
			foreach($q as $u) {
				$b = stripslashes($u->t_titre);
				preg_match('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $b, $m);
				$m[0] = (isset($m[0])?$m[0]:'');
				$b = str_replace(array($m[0]), array(''), $b);
				$b = str_replace(', ', ',', $b); $b = str_replace(',', ', ', $b);
				$u->title = strtr($b, "0123456789#(){[]}", ".................");
				$photo = self::f_img((($u->ID)*10).'-libre',2); // (($u->ID)*10).'-libre';
				$u->librePhoto = $rencDiv['baseurl'].'/portrait/libre/'.$photo.'.jpg';
				if(!file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg')) @copy($rencDiv['basedir'].'/portrait/'.floor(($u->ID)/1000).'/'.self::f_img((($u->ID)*10).'-libre',2).'.jpg', $rencDiv['basedir'].'/portrait/libre/'.$photo.'.jpg');
				// ****** TEMPLATE ********
				ob_start();
				if($tpl=rencTpl('rencontre_libre_search_portrait.php')) include $tpl;
				$searchResult .= ob_get_clean();
				// ************************
				++$c;
			}
		}
		if(!wp_style_is('w3css')) $out .= '<link rel="stylesheet" href="'.plugins_url('rencontre/css/w3.css').'" />'."\r\n";
		$onClick = array(
			"agemin"=>"f_min(this.options[this.selectedIndex].value,'rencSearch','".$Lagemin."','".$Lagemax."');",
			"agemax"=>"f_max(this.options[this.selectedIndex].value,'rencSearch','".$Lagemin."','".$Lagemax."');"
			);
		// ****** TEMPLATE ********
		ob_start();
		if($tpl=rencTpl('rencontre_libre_search.php')) include $tpl;
		$out .= ob_get_clean();
		// ************************
		if(!$ret) echo $out;
		else return $out; // SHORTCODE
	}
	//
	static function f_nbMembre($a=array()) { // Nombre de membres inscrits sur le site
		global $wpdb;
		$atts = shortcode_atts(array('gen'=>'','ph'=>0),$a);
		$nm = $wpdb->get_var("
			SELECT
				COUNT(*)
			FROM
				".$wpdb->prefix."rencontre_users
			WHERE
				".($atts['gen']=='girl'?"i_sex=1":"")."
				".($atts['gen']=='men'?"i_sex=0":"")."
				".($atts['gen']!='girl'&&$atts['gen']!='men'?"i_sex!=98":"")."
				".($atts['ph']==1?" and i_photo!=0":"")
			);
		return $nm;
	}
	//
	static function f_login($ext=false,$ret=false,$a=array()) { // SHORTCODE [rencontre_login]
		global $rencOpt, $rencDiv, $rencCustom;
		$atts = shortcode_atts(array('loginout'=>1,'register'=>1),$a);
		// Check HOME
		if(!empty($rencOpt['home'])) {
			$pages = get_pages();
			foreach($pages as $p) {
				if($rencOpt['home']==get_page_link($p->ID)) { // Exists and well structured
					$h = $rencOpt['home'];
					break;
				}
			}
		}
		if(empty($h)) $h = home_url();
		// Output
		$o = "\r\n".'<div id="log" class="renclog">'."\r\n";
		if(!is_user_logged_in()) {
			if($atts['loginout']) $o .= "\t".'<a href="'.esc_url(wp_login_url($h)).'" title="'.__('Log in').'">'.__('Log in').'</a>'."\r\n";
			if($atts['register']) {
				if(empty($rencCustom['reglink']) || !empty($rencOpt['fastreg'])) {
					$o .= "\t".'<a href="'.esc_url(wp_registration_url()).'" title="'.__('Register').'">'.__('Register').'</a>'."\r\n";
				}
				else $o .= "\t".'<a href="'.$rencCustom['reglink'].'" title="'.__('Register').'">'.__('Register').'</a>'."\r\n";
			}
		}
		else $o .= "\t".'<a href="'.esc_url(wp_logout_url($h)).'" title="'.__('Log out').'">'.__('Log out').'</a>'."\r\n";
		$o .= '</div><!-- #log.rencLog -->'."\r\n";
		// Return
		if(!$ret) echo $o;
		else return $o; // SHORTCODE
	}
	//
	static function f_img($img,$f=0) { // ENCODE only - $f = 1 : ALL in base - $f = 2 : No Filter
		global $rencOpt;
		$ho = false;
		if(!$f && has_filter('rencImgP')) $ho = apply_filters('rencImgP',$img);
		if($ho) return $ho;
		//
		if($f==1 || !empty($rencOpt['imcode'])) {
			$t = md5($img);
			return substr($t,4,17) . 'z' . substr($t,25,6); // 'z' is used to know if it's encoded or not
		}
		else return $img;
	}
	static function f_rencontreImgReg($atts=array()) {
		// Shortcode [rencontre_imgreg title= selector= left= top= login=]
		// Registration Form on image (home page)
		global $rencCustom, $rencOpt;
		$arg = shortcode_atts(array(
			'title' => __('Register'),
			'selector' => '', // jQuery selector of the image ex: '.site-header .wp-custom-header img'
			'left' => '20',
			'top' => '15',
			'login' => 0
			),$atts);
		
		// ****** TEMPLATE ********
		ob_start();
		if($tpl=rencTpl('rencontre_imgreg.php')) include $tpl;
		$o = ob_get_clean();
		// ************************
		return $o;
	}
	static function rencIniUser() { // premiere connexion
		global $wpdb, $current_user, $rencOpt;
		$wpdb->update($wpdb->prefix.'rencontre_users',
			array('c_ip'=>(!empty($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:1)),
			array('user_id'=>$current_user->ID)); // IP => Ce n est plus un nouveau
		if(empty($rencOpt['rol']) && !is_multisite()) {
			$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$current_user->ID)); // suppression des roles WP
			wp_clear_auth_cookie();
			wp_set_current_user($current_user->ID, $current_user->user_login);
			wp_set_auth_cookie($current_user->ID);
			do_action('wp_login', $current_user->user_login, $current_user);
		}
	}
	//
} // END CLASS
// *****************************************************************************************
global $pagenow;
function rencUpdateMessage($plugin_data,$r) { if(!empty($plugin_data['upgrade_notice'])) echo '<div>'.$plugin_data['upgrade_notice'].'</div>'; }
if('plugins.php'===$pagenow) add_action('in_plugin_update_message-rencontre/rencontre.php', 'rencUpdateMessage', 20, 2);
// *****************************************************************************************
?>

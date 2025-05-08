<?php
$rencOpt = get_option('rencontre_options');
$rencWidg = 0; // Sidebar widget ON/OFF
if(empty($rencOpt) || !is_array($rencOpt)) $rencOpt = array();
// Filtres / Action : General
add_filter('show_admin_bar' , 'rencAdminBar'); // Viewing the admin bar
add_action('init', 'rencPreventAdminAccess', 0); // blocks access to the dashboard
add_action('init', 'rencInLine', 1); // session
add_action('init', 'rencTextdomain'); // lang (previous : plugins_loaded)
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
	static $call = 0;
	if(!$call && is_user_logged_in()) {
		$call = 1; // only once
		if(!class_exists('RencontreWidget')) return '';
		$renc = new RencontreWidget;
		ob_start();
		$renc->widget(0,0);
		$a = ob_get_contents();
		ob_end_clean();
		return $a;
	}
}
function f_shortcode_rencontre_login($a) {return Rencontre::f_login(0,1,$a);} // shortcode : [rencontre_login]
function f_shortcode_rencontre_imreg($a) {if(!is_user_logged_in()) return Rencontre::f_rencontreImgReg($a);} // shortcode : [rencontre_imgreg title= selector='.site-header .wp-custom-header img' left=20 top=20] - left & top in purcent
// Mail
//add_filter ('retrieve_password_message', 'retrieve_password_message2', 10, 2);
// AJAX
add_action('wp_ajax_regionBDD', 'f_regionBDD'); // AJAX - return of the regions in the select
add_action('wp_ajax_voirMsg', 'f_voirMsg');
add_action('wp_ajax_testPass', 'rencTestPass'); // change password
add_action('wp_ajax_miniPortrait2', 'f_miniPortrait2');
add_action('wp_ajax_fastregMail', 'f_fastregMail');
add_action('wp_ajax_dynSearch', 'f_dynSearch');
//add_action('wp_ajax_addCountSearch', 'f_addCountSearch'); // +1 in action search if same day
add_action('wp_ajax_gpsnavigator', 'rencGpsNavigator');
function f_voirMsg() {
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	$Pidmsg = rencSanit($_POST['idmsg'],'int');
	$Palias = rencSanit($_POST['alias'],'words');
	RencontreWidget::f_voirMsg($Pidmsg,$Palias);
	exit;
}
function f_miniPortrait2() {
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	global $rencOpt;
	$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
	$Pid = rencSanit($_POST[$Lid],'int');
	RencontreWidget::f_miniPortrait2($Pid);
	exit;
}
function f_fastregMail() {
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	$u = wp_get_current_user();
	rencFastreg_email($u,1);
	exit;
}
function f_dynSearch() {
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok') || empty($_REQUEST['var'])) return;
	$var = f_sanitDynSearch($_REQUEST['var']);
	$dyn = rencSanit($_REQUEST['dyn'],'int');
	if(empty($_REQUEST['quick'])) RencontreWidget::f_trouver($var,$dyn);
	else RencontreWidget::f_quickFind($var,$dyn);
	exit;
}
function f_sanitDynSearch($json) {
	global $rencOpt;
	$a = $b = array();
	$req = json_decode(stripslashes($json),true);
	$a[(!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc')] = 'alphanum';
	$a[(!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id')] = 'alphanum';
	$a[(!empty($rencOpt['lbl']['sex'])?$rencOpt['lbl']['sex']:'sex')] = 'int';
	$a[(!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex')] = 'numplus';
	$a[(!empty($rencOpt['lbl']['z2sex'])?$rencOpt['lbl']['z2sex']:'z2sex')] = 'numplus';
	$a[(!empty($rencOpt['lbl']['homo'])?$rencOpt['lbl']['homo']:'homo')] = 'int';
	$a[(!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine')] = 'int';
	$a[(!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin')] = 'int';
	$a[(!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax')] = 'int';
	$a[(!empty($rencOpt['lbl']['tailleMin'])?$rencOpt['lbl']['tailleMin']:'tailleMin')] = 'int';
	$a[(!empty($rencOpt['lbl']['tailleMax'])?$rencOpt['lbl']['tailleMax']:'tailleMax')] = 'int';
	$a[(!empty($rencOpt['lbl']['poidsMin'])?$rencOpt['lbl']['poidsMin']:'poidsMin')] = 'int';
	$a[(!empty($rencOpt['lbl']['poidsMax'])?$rencOpt['lbl']['poidsMax']:'poidsMax')] = 'int';
	$a[(!empty($rencOpt['lbl']['mot'])?$rencOpt['lbl']['mot']:'mot')] = 'words';
	$a[(!empty($rencOpt['lbl']['pseudo'])?$rencOpt['lbl']['pseudo']:'pseudo')] = 'words';
	$a[(!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays')] = 'AZ';
	$a[(!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region')] = 'alphanum';
	$a[(!empty($rencOpt['lbl']['ville'])?$rencOpt['lbl']['ville']:'ville')] = 'words';
	$a[(!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation')] = 'numplus';
	$a[(!empty($rencOpt['lbl']['photo'])?$rencOpt['lbl']['photo']:'photo')] = 'int';
	$a[(!empty($rencOpt['lbl']['profil'])?$rencOpt['lbl']['profil']:'profil')] = 'int';
	$a[(!empty($rencOpt['lbl']['astro'])?$rencOpt['lbl']['astro']:'astro')] = 'int';
	$a[(!empty($rencOpt['lbl']['gps'])?$rencOpt['lbl']['gps']:'gps')] = 'pipe';
	$a[(!empty($rencOpt['lbl']['km'])?$rencOpt['lbl']['km']:'km')] = 'int';
	$a[(!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS')] = 'alphanum';
	$a[(!empty($rencOpt['lbl']['line'])?$rencOpt['lbl']['line']:'line')] = 'int';
	foreach($req as $k=>$v) {
		if(isset($a[$k])) $b[$k] = rencSanit($v, $a[$k]);
		else $b[$k] = rencSanit($v, 'words'); // default
	}
	return json_encode($b);
}
if(is_admin()) { // Check for an administrative interface page
	add_action('wp_ajax_iso', 'rencontreIso'); // Test if the ISO code is free (ADMIN Part)
	add_action('wp_ajax_drap', 'rencontreDrap'); // SELECT with the list of flag files (ADMIN Part)
	add_action('wp_ajax_exportCsv', 'f_exportCsv'); // Export CSV (ADMIN Part)
	add_action('wp_ajax_importCsv', 'f_importCsv'); // Import CSV (ADMIN Part)
	add_action('wp_ajax_updown', 'f_rencUpDown'); // Mod Profil : move Up / Down / Supp
	add_action('wp_ajax_profilA', 'f_rencProfil'); // Mod Profil : plus & edit
	add_action('wp_ajax_stat', 'f_rencStat'); // Members - Registration statistics
	add_action('wp_ajax_newMember', 'f_newMember'); // Add new Rencontre Members from WP Users - Members Tab
	add_action('wp_ajax_regeneratePhotos', 'rencRegeneratePhotos'); // Regenerate all users photos. base.php
	add_filter('wp_privacy_personal_data_exporters', 'rencGDPRExport', 10); // Include Data in the GDPR WP Export
	add_action('wp_privacy_personal_data_export_file_created', 'rencGDPRExportimg', 10, 4);
}
// CRON
add_action('init', 'f_cron', 21);
function f_cron() {
	// Filters after "init"
	global $rencOpt;
	// *** For all website, update Menu made by Meta-Menu Rencontre ***
	add_filter('wp_setup_nav_menu_item', 'rencMetaMenuLoginout', 1); // Update Login/Logout/registered menu items
	if(!is_user_logged_in()) add_filter('wp_get_nav_menu_items', 'rencMetaMenuRencontre', null, 3); // hide rencontre items in WP menu in not connected
	// ****************************************************************
	add_shortcode('rencontre_libre', 'f_shortcode_rencontre_libre');
	add_shortcode('rencontre_nbmembre', 'f_shortcode_rencontre_nbmembre');
	add_shortcode('rencontre_search', 'f_shortcode_rencontre_search');
	add_shortcode('rencontre_login', 'f_shortcode_rencontre_login');
	add_shortcode('rencontre_imgreg', 'f_shortcode_rencontre_imreg'); // [rencontre_imgreg title= selector= left= top=]
	add_shortcode('rencontre', 'f_shortcode_rencontre');
	add_action('wp_enqueue_scripts', 'rencCssJs', 1); // add rencontre.css in top header & rencontre.js in footer if needed
	if(function_exists('wpGeonames_shortcode') && is_user_logged_in()) {
		add_action('wpGeonames_location_taxonomy_tpl', 'renc_wpGeonames_tpl');
		remove_action('wp_ajax_geoDataRegion', 'wpGeonames_ajax_geoDataRegion');
		add_action('wp_ajax_geoDataRegion', 'renc_ajax_geoDataRegionHook');
		add_action('wp_ajax_rencGeoDataCity', 'renc_ajax_rencGeoDataCity'); // Search display city list
	}
	//
	if(has_filter('rencCron')) apply_filters('rencCron', 0);
	else {
		if(is_user_logged_in()) add_action('wp_ajax_cronasync', 'f_cronasync');
		else add_action('wp_ajax_nopriv_cronasync', 'f_cronasync');
		global $rencDiv;
		if(!is_dir($rencDiv['basedir'].'/portrait/')) mkdir($rencDiv['basedir'].'/portrait/');
		if(!is_dir($rencDiv['basedir'].'/portrait/cache/')) mkdir($rencDiv['basedir'].'/portrait/cache/');
		if(!is_dir($rencDiv['basedir'].'/portrait/cache/cron_list/')) mkdir($rencDiv['basedir'].'/portrait/cache/cron_list/');
		$d = $rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt';
		$dOn = $rencDiv['basedir'].'/portrait/cache/rencontre_cronOn.txt';
		$dList = $rencDiv['basedir'].'/portrait/cache/rencontre_cronList.txt'; if(!file_exists($dList)) file_put_contents($dList, '');
		$dDList = $rencDiv['basedir'].'/portrait/cache/cron_list';
		global $rencOpt;
		$t = current_time('timestamp',0); // timestamp local
		$gmt = time(); // timestamp GMT
		$hcron = (isset($rencOpt['hcron'])?$rencOpt['hcron']+0:3);
		$u1 = date("G",$t-3600*$hcron); // current time(UTC) - off-peak hours (+24 si <0) ; Ex it's 15h23Z (15), Off-peak:4h (4) => $u = 15 - 4 = 11;
		// u1 is growing 21, 22, 23 then 0 when off-peak (so <12). It remains then 12 hours so that a visitor causes the CRON.
		clearstatcache();
		if(!file_exists($dOn)) {
			$part = (file_exists($d)?filesize($d):1); // 1:first part - 4:first email period - 5:second email period - 0:end for today
			$ftd = (file_exists($d)&&filemtime($d)?filemtime($d):0);
			$ftdl = (file_exists($dList)&&filemtime($dList)?filemtime($dList):0);
			$if = 1;
			if($u1<12 && ( ($part && $part<5 && $if=2) || ($part==5 && $gmt>$ftd+3661 && $if=3) || (file_exists($d) && $gmt>$ftd+43200 && $if=4) )) {
				//  u1<12 (start next periode) - 43200=12h (end previous periode)
				f_action_cronasync();
				add_filter('get_footer', function() use($part,$if) { echo f_cronasyncset('on',$part,$if); }, 99);
			}
			else if($gmt>$ftd+3661 && $gmt>$ftdl+3661 && $u1<23 && count(glob($dDList.'/*'))!==0) {
				f_action_cronasync();
				add_filter('get_footer', function(){ echo f_cronasyncset('list'); }, 99);
			}
		}
		else if($gmt>filemtime($dOn)+900) unlink($dOn); // Off
	}
	// add_filter('get_footer', function(){ echo f_cronasyncset('on',9); }, 99); // Force mode
}

function f_cronasyncset($typ='on',$part='',$debug=0){
	// Phase 1. Prepare Ajax call
	if(empty($_SESSION['rencTok'])) {
		$rencTok = wp_create_nonce('rencTok');
		$_SESSION['rencTok'] = $rencTok;
	}
	$sc = '<script type="text/javascript">';
	$sc .= 'jQuery(document).ready(function(){jQuery.post(\''.admin_url('admin-ajax.php').'\',{"action":"cronasync","rencTok":"'.$_SESSION['rencTok'].'","typ":"'.$typ.'","ajt":'.time().',"debug":"'.$debug.'"});});';
	$sc .= 'console.log(\'CronAsync-'.$typ.$part.'\');';
	$sc .= '</script>'."\r\n";
	return $sc;
}
function f_action_cronasync() {
	if(is_user_logged_in()) add_action('wp_ajax_cronasync', 'f_cronasync');
	else add_action('wp_ajax_nopriv_cronasync', 'f_cronasync');
}
function f_cronasync(){
	// Phase 2. Call from Ajax
	global $rencDiv, $rencOpt, $rencBenchmark;
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok') || empty($_POST['typ'])) return;
	ignore_user_abort(true);
	set_time_limit(40);
	clearstatcache();
	if(!empty($rencOpt['nbr']['cronBenchmark'])) $rencBenchmark = 'F_CRON: '.microtime(true).' - '.PHP_EOL; // Benchmark entry door
	$d = $rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt';
	$dOn = $rencDiv['basedir'].'/portrait/cache/rencontre_cronOn.txt';
	$dList = $rencDiv['basedir'].'/portrait/cache/rencontre_cronList.txt';
	$typ = (!empty($_POST['typ'])?rencSanit($_POST['typ'],'alphanum'):'');
	$ajt = (!empty($_POST['ajt'])?rencSanit($_POST['ajt'],'int'):time()); // cache browser reload later
	$gmt = time();
	include(dirname(__FILE__).'/rencontre_cron.php');
	if(!file_exists($dOn) && ($gmt-$ajt)<3600) switch($typ) {
		// ************************************************************
		case 'on':
		$part = (filesize($d)?filesize($d):1);
		if(($part && $part<5) || ($part==5 && $gmt>filemtime($d)+3661) || (file_exists($d) && $gmt>filemtime($d)+43200) ) {
			// avoid double if user reload page
			file_put_contents($dOn, '');
			if(!file_exists($d) || !filesize($d)) file_put_contents($d,'1');
			f_cron_on($part);
			$p = file_get_contents($d);
			$s = strlen($p);
			file_put_contents($d,($s<5?($p.($s+1)):'')); // 1234 or empty
			if(file_exists($dOn)) unlink($dOn); // Off - Error not exists sometimes
		}
		break;
		// ************************************************************
		case 'list':
		if($gmt>filemtime($d)+3661 && $gmt>filemtime($dList)+3661) {
			file_put_contents($dOn, '');
			f_cron_list();
			file_put_contents($dList, ''); // Filetime reset
			if(file_exists($dOn)) unlink($dOn); // Off - Error not exists sometimes
		}
		break;
		// ************************************************************
	}
}
//
function rencTextdomain() { // Hook at 'init'
	if(!load_plugin_textdomain('rencontre', false, dirname(plugin_basename( __FILE__ )).'/lang/')) { // language
		$a = get_locale();
		$lo = array(
			'en_AU'=>'en_US',
			'en_CA'=>'en_US',
			'en_GB'=>'en_US',
			'en_NZ'=>'en_US',
			'en_ZA'=>'en_US',
			'es_AR'=>'es_ES',
			'es_CL'=>'es_ES',
			'es_CO'=>'es_ES',
			'es_ES'=>'es_ES',
			'es_GT'=>'es_ES',
			'es_MX'=>'es_ES',
			'es_PE'=>'es_ES',
			'es_PR'=>'es_ES',
			'es_VE'=>'es_ES',
			'fr_BE'=>'fr_FR',
			'fr_CA'=>'fr_FR',
			'fr_FR'=>'fr_FR',
			'nl_BE'=>'nl_NL',
			'pt_AO'=>'pt_PT',
			'pt_BR'=>'pt_PT',
			'zh_CN'=>'zh_CN',
			'zh_HK'=>'zh_CN',
			'zh_TW'=>'zh_CN');
		if(isset($lo[$a])) {
			$a = $lo[$a];
			$b = load_textdomain('rencontre',WP_LANG_DIR.'/plugins/rencontre-'.$a.'.mo');
			if(!$b) load_textdomain('rencontre',WP_PLUGIN_DIR.'/rencontre/lang/rencontre-'.$a.'.mo');
		}
	}
}
//
function rencSanit($f,$g) {
	// Sanitize / Validate POST && GET datas
	$a = ''; $b = 0;
	switch($g) {
		case 'int': // AGE, YEAR, MONTH, DAY, SIZE, SEX, RELATION...
			$a = abs(intval($f)); // 09 allowed only
			break;

		case 'num': // GPS, ROTATE
			$a = trim($f);
			$a = filter_var($a, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			break;
			
		case 'numplus': // ZSEX RELATION ('' & 0)
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
			
		case 'selector': // JQuery simple selector (not all possibility)
			$a = sanitize_text_field($f);
			$a = strip_tags($a);
			$a = preg_replace("/\s+/", " ",$a); // multiple spaces & lines break
			$n = array('<','(',')','{','}','|','?',';','`','%','!','&');
			$a = str_replace($n,"",$a);
			break;

		case 'b64': // RENCOO, RENCII
			$a = preg_match("%^([A-Za-z0-9+/]{4})*([A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{2}==)?$%", $f);
			if($a) $a = $f;
			break;
			
		case 'pipe': // pipe separation text && url && location GPS
			$a = rencSanit($f,'text');
			break;
			
		case 'img': // pipe separation with img ... or int
			if(preg_match("/^[1-9][0-9]*$/", $f) && strlen($f)<12) $a = intval($f);
			else if(strpos($f.'-','|')!==false) $a = $f; // IMG Stream => sanit after - old feature FB
			else $a = '';
			break;

		case 'bin': // data base64 img decoded and saved in file
			if(!mb_check_encoding($f, 'UTF-8')) $a = $f; // binary
			$b = 1;
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
	if(!$b && !is_int($a) && !is_string($a) && !is_array($a)) $a = (string)$a;
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
	$rencjs = rencTpl('rencjs.js',1,'rencontre',1);
	if($rencjs) wp_register_script('rencjs',$rencjs,array(),false,true);
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
			if($rencjs) wp_enqueue_script('rencjs');
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
		if($rencjs) wp_enqueue_script('rencjs');
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
	$a = ''; $alm = '@media(min-width:601px){'; $as = '@media(max-width:600px){';
	$a .= '.w3-modal{z-index:99999;}';
	$alm .= '.w3-renc-container-lm{padding:0.01em 16px}';
	$alm .= '.w3-renc-margin-left-8-lm{margin-left:8px}';
	$as .= '.w3-renc-margin-top-8-s{margin-top:8px!important}';
	$as .= '.w3-renc-padding-8-s{padding:8px!important}';
	$as .= '.w3-renc-medium-s{font-size:15px!important}';
	$as .= '.w3-renc-small-s{font-size:12px!important}';
	$as .= '.w3-renc-underline-s{text-decoration: underline;}';
	foreach($w3rencDef as $k=>$v) if(empty($rencCustom[$k])) $rencCustom[$k] = $v;
	if(!empty($w3renc[$rencCustom['mebg']])) $a .= '.w3-renc-mebg{color:'.$w3renc[$rencCustom['mebg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebg']].'!important}';
	if(!empty($w3renc[$rencCustom['mebt']])) $a .= '.w3-renc-mebt{color:'.$w3renc[$rencCustom['mebt'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebt']].'!important}';
	if(!empty($w3renc[$rencCustom['mebw']])) $a .= '.w3-renc-mebw{color:'.$w3renc[$rencCustom['mebw'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebw']].'!important}';
	if(!empty($w3renc[$rencCustom['mebo']])) $a .= '.w3-renc-mebo:hover{color:'.$w3renc[$rencCustom['mebo'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebo']].'!important}';
	if(!empty($w3renc[$rencCustom['mebc']])) $a .= '.w3-renc-mebc{color:'.$w3renc[$rencCustom['mebc'].'T'].'!important;background-color:'.$w3renc[$rencCustom['mebc']].'!important}';
	if(!empty($w3renc[$rencCustom['blbg']])) $a .= '.w3-renc-blbg{color:'.$w3renc[$rencCustom['blbg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['blbg']].'!important}';
	if(!empty($w3renc[$rencCustom['titc']])) $a .= '.w3-renc-titc{color:'.$w3renc[$rencCustom['titc']].'!important;}';
	if(!empty($w3renc[$rencCustom['txtc']])) $a .= '.w3-renc-txtc{color:'.$w3renc[$rencCustom['txtc']].'!important;}';
	if(!empty($w3renc[$rencCustom['lblc']])) $a .= '.w3-renc-lblc label:not([for]){color:'.$w3renc[$rencCustom['lblc']].'!important;}'; // Labelauty exclusion
	if(!empty($w3renc[$rencCustom['inlc']])) $a .= '.w3-renc-inlc{color:'.$w3renc[$rencCustom['inlc']].'!important;}';
	if(!empty($w3renc[$rencCustom['inlb']])) $a .= '.w3-renc-inlb{color:'.$w3renc[$rencCustom['inlb'].'T'].'!important;background-color:'.$w3renc[$rencCustom['inlb']].'!important}';
	if(!empty($w3renc[$rencCustom['line']])) $a .= '.w3-renc-line{border-color:'.$w3renc[$rencCustom['line']].'!important}';
	if(!empty($w3renc[$rencCustom['inbg']])) $a .= '.w3-renc-inbg{color:'.$w3renc[$rencCustom['inbg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['inbg']].'!important}';
	if(!empty($w3renc[$rencCustom['sebg']])) $a .= '.w3-renc-sebg{color:'.$w3renc[$rencCustom['sebg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['sebg']].'!important}';
	if(!empty($w3renc[$rencCustom['wabg']])) $a .= '.w3-renc-wabg{color:'.$w3renc[$rencCustom['wabg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['wabg']].'!important}';
	if(!empty($w3renc[$rencCustom['wmbg']])) $a .= '.w3-renc-wmbg{color:'.$w3renc[$rencCustom['wmbg'].'T'].'!important;background-color:'.$w3renc[$rencCustom['wmbg']].'!important}';
	if(!empty($w3renc[$rencCustom['msbs']])) $a .= '.w3-renc-msbs{color:'.$w3renc[$rencCustom['msbs'].'T'].'!important;background-color:'.$w3renc[$rencCustom['msbs']].'!important}';
	if(!empty($w3renc[$rencCustom['msbr']])) $a .= '.w3-renc-msbr{color:'.$w3renc[$rencCustom['msbr'].'T'].'!important;background-color:'.$w3renc[$rencCustom['msbr']].'!important}';
	if(has_filter('rencCustomColorIncP')) $a .= apply_filters('rencCustomColorIncP', $a, $w3renc);
	$a .= $alm.'}' . $as.'}';
	if(!$f) wp_add_inline_style('w3css', $a);
	else echo '<link rel="stylesheet" href="'.plugins_url('rencontre/css/w3.css').'"><style>'.$a.'</style>';
}
//
function rencU0($f) {
	// $f : User ID
	global $wpdb, $rencU0, $rencOpt, $rencCustom;
	$rencU0 = $wpdb->get_row("SELECT *
			FROM ".$wpdb->base_prefix."users U
			LEFT JOIN ".$wpdb->prefix."rencontre_users R
				ON R.user_id=U.ID
			LEFT JOIN ".$wpdb->prefix."rencontre_users_profil P
				ON P.user_id=U.ID
			WHERE
				U.ID=".$f."
			LIMIT 1
			");
	$rencU0->taille = number_format($rencU0->i_taille/10, 1); // 1722 => 172.2 (calcul)
	$rencU0->i_taille = intval($rencU0->i_taille/10+.5); // 1722 => 172 (display)
	$rencU0->poids = number_format($rencU0->i_poids/10, 1);
	$rencU0->i_poids = intval($rencU0->i_poids/10+.5);
	$rencU0->age = Rencontre::f_age($rencU0->d_naissance);
	$rencU0->action = json_decode((empty($rencU0->t_action)?'{}':$rencU0->t_action),true);
	$rencU0->profil = json_decode((empty($rencU0->t_profil)?'{}':$rencU0->t_profil),true);
	$rencU0->zstrict = (isset($rencU0->action['option']) && strpos($rencU0->action['option'],',zstrict,')!==false) ? 1 : 0;
	$rencU0->photoreq = (!empty($rencOpt['photoreq']) && empty($rencU0->i_photo)) ? 1 : 0; 
	// Imperial (1) or Metric (0)
	$rencU0->imperialw = (!empty($rencCustom['weightu']) && ($rencCustom['weightu']==3 && strpos($rencU0->t_action.'-',',weight-lbs,')!==false || $rencCustom['weightu']==2)) ? 1 : 0;
	$rencU0->imperials = (!empty($rencCustom['sizeu']) && ($rencCustom['sizeu']==3 && strpos($rencU0->t_action.'-',',size-ft,')!==false || $rencCustom['sizeu']==2)) ? 1 : 0;
}
//
function rencPause($f,$id) {
	global $wpdb;
	$q = $wpdb->get_var("SELECT
			t_action
		FROM ".$wpdb->prefix."rencontre_users_profil
		WHERE user_id='".$id."'
		LIMIT 1");
	$action = json_decode((empty($q)?'{}':$q),true);
	if(empty($action['option'])) $action['option'] = ',';
	$action['option'] = str_replace(',pause1,', ',', $action['option']);
	$action['option'] = str_replace(',pause2,', ',', $action['option']);
	if($f==1) $action['option'] .= 'pause1,';
	else if($f==2) $action['option'] .= 'pause1,pause2,';
	$out = json_encode($action);
	$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$id));
	renc_clear_cache_portrait();
	rencU0($id); // reload U0
}
//
function f_admin_menu ($wp_admin_bar) {
	if(current_user_can("manage_options")) {
		$args = array(
			'id'=>'rencontre',
			'title'=>'<img src="'.plugins_url('rencontre/images/rencontre.png').'" alt="'.__('Rencontre - Members','rencontre').'" />',
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
function rencMetaMenuLoginout($item) {
	// Fired for each menu item - add_filter('wp_setup_nav_menu_item',...)
	// Update Login/Logout/registered menu items
	if(current_user_can("administrator")) return $item;
	if(isset($item->classes) && (in_array('rLoginout', $item->classes) || in_array('rencLoginout', $item->classes))) {
		if(is_user_logged_in()) {
			$item->url = wp_logout_url(get_permalink());
			$item->title = __('Log out');
		}
		else {
			$item->url = wp_login_url(get_permalink());
			$item->title = __('Log in');
		}
	}
	else if(isset($item->classes) && (in_array('rRegister', $item->classes) || in_array('rencRegister', $item->classes))) {
		if(is_user_logged_in()) $item->_invalid = true; // hide
		else {
			$item->url = wp_registration_url();
			$item->title = __('Register');
		}
	}
	// ** MENU CREATED BEFORE Rencontre 3.13.2 (Mod menu creation) **
	else if(strpos($item->url.'-','#rencnav#')!==false) {
		global $rencOpt;
		if(is_user_logged_in()) {
			$a = explode('#',$item->url);
			if(!empty($rencOpt['home'])) {
				if(strpos($rencOpt['home'].'-','?')!==false && strpos($rencOpt['home'].'-','=')!==false) $item->url = $rencOpt['home'].'&renc='.$a[2];
				else $item->url = $rencOpt['home'].'?renc='.$a[2]; // 'javascript:void(0)';
			}
			else $item->url = site_url();
		}
		else $item->url = wp_login_url(get_permalink());
	}
	// ***************************************************************
	return $item;
}
//
function rencMetaMenuRencontre($items,$m,$a) {
	// Fired once - add_filter('wp_get_nav_menu_items',...)
	// Hide Menu Items if not logged
	foreach($items as $k=>$i) if(in_array('onlyLog',$i->classes)) unset($items[$k]);
	return $items;
}
//
function rencInLine() {
	if(is_user_logged_in()) {
		if(!headers_sent() && empty(session_id())) session_start();
		global $current_user, $wpdb;
		$upl = wp_upload_dir();
		if(!is_dir($upl['basedir'].'/tchat/')) mkdir($upl['basedir'].'/tchat/');
		if(!is_dir($upl['basedir'].'/session/')) mkdir($upl['basedir'].'/session/');
		$t = @fopen($upl['basedir'].'/session/'.$current_user->ID.'.txt', 'w') or die();
		fclose($t);
		session_write_close();
	}
}
//
function rencOutLine() {
	global $current_user, $rencDiv;
	if(file_exists($rencDiv['basedir'].'/session/'.$current_user->ID.'.txt')) @unlink($rencDiv['basedir'].'/session/'.$current_user->ID.'.txt');
	// session_destroy(); // !!!!!!!! ERROR (Cannot modify header information - headers already sent) !!!!!!!!
}
//
function rencUserLogin($current_user,$pass) {
	global $wpdb;
	if(isset($current_user->ID)) $wpdb->update($wpdb->prefix.'rencontre_users', array('d_session'=>current_time("mysql")), array('user_id'=>$current_user->ID));
	return $current_user;
}
//
function rencPreventAdminAccess() {
	global $rencDiv;
	$a=strtolower($_SERVER['REQUEST_URI']);
	if(strpos($a.'-','/wp-admin')!==false && strpos($a.'-','admin-ajax.php')==false && !current_user_can("edit_posts") && !current_user_can("bbp_moderator")) {
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
			echo '<option value="">- '.__('No matter','rencontre').' -</option>';
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
		echo '<option value="">- '.__('No matter','rencontre').' -</option>';
		foreach($q as $r) {
			echo '<option value="'.$r->id.'">'.$r->c_liste_valeur.'</option>';
		}
	}
}
//
function rencTestPass() { // modify account only
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	global $wpdb, $rencOpt;
	$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
	//
	$id = rencSanit($_POST[$Lid],'int');
	$nouv = rencSanit($_POST['nouv'],'text');
	$pass = rencSanit($_POST['pass'],'text');
	$q = $wpdb->get_var("SELECT user_pass FROM ".$wpdb->base_prefix."users WHERE ID='".$id."' LIMIT 1");
	if(wp_check_password($pass,$q,$id)) {
		wp_set_password($nouv,$id); // change Password
		wp_set_auth_cookie($id); // cookie to stay connected
		echo 'ok';
	}
	else return; // bad password
	exit;
}
//
function f_userSupp($f,$a,$b) { // rencontre.php - in action 'widget_init' - base.php
	// return ID
	f_suppImgAll($f);
	global $wpdb, $rencOpt;
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
	if(empty($rencOpt['rol']) || empty($rencOpt['rolu']) || !$b || $ip==2) { // $b=0 => user delete his account (force all WP), $b=1 => ADMIN delete user - $ip=2 => "hard del"
		require_once(ABSPATH.'wp-admin/includes/user.php');
		wp_delete_user($f);
	}
	if(has_filter('rencUserDel')) apply_filters('rencUserDel', $f);
	if(!current_user_can("administrator")) { wp_redirect(home_url()); exit; }
	if(!empty($q->user_email)) return $q->user_email; // Admin deletion
}
//
function rencRotateJpg($f,$rot,$id=0) {
	// f : img not crypted (id*10 + numImg)
	global $rencDiv, $current_user;
	if(empty($id)) $id = $current_user->ID;
	$img = $rencDiv['basedir'].'/portrait/'.floor(intval($id)/1000).'/'.Rencontre::f_img($f).'.jpg';
	$im = imagecreatefromjpeg($img);
	$imrot = imagerotate($im, $rot, 0);
	imagejpeg($imrot, $img, 95); // Img rot lose quality
	imagedestroy($im);
	imagedestroy($imrot);
	RencontreWidget::f_photo($f,$img,0,1);
}
//
function f_suppImgAll($id) { // After init (has_filter)
	global $rencDiv;
	$size = rencPhotoSize(1); // 1 : P added in list
	$r = $rencDiv['basedir'].'/portrait/'.floor(intval($id)/1000).'/';
	for($v=0;$v<9;++$v) {
		$a = array();
		foreach($size as $s) $a[] = Rencontre::f_img($id.$v.$s['label']) . '.jpg';
		foreach($a as $b) if(file_exists($r.$b)) @unlink($r.$b);
	}
}
function rencPhotoSize($f=0) {
	$size = array(
		array('label'=>'', 'width'=>1280, 'height'=>960, 'quality'=>75), // size max - W or H smaller according to photo format
		array('label'=>'-mini', 'width'=>60, 'height'=>60, 'quality'=>75),
		array('label'=>'-grande', 'width'=>250, 'height'=>250, 'quality'=>75),
		array('label'=>'-libre', 'width'=>260, 'height'=>195, 'quality'=>75)
		);
	if(has_filter('rencImgSize')) $size = apply_filters('rencImgSize', $size);
	if($f && has_filter('rencImgSizeP')) $size = apply_filters('rencImgSizeP', $size); // Not added by default (Blur) (creation in P in another filter...)
	foreach($size as $k=>$v) if(!isset($v['label']) || empty($v['width']) || empty($v['height'])) unset($size[$k]);
	return $size;
}
//
function rencGetPhotos($id,$iph=false) {
	// Return array with all photos available for this member
	// $iph : i_photo
	global $rencDiv, $wpdb;
	if($iph===false) $iph = $wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$id."' LIMIT 1");
	if(empty($id) || empty($iph)) return; // No photo
	if(floor($id*10)>$iph || $iph-($id*10)>10) return; // Error
	$a = array();
	$size = rencPhotoSize();
	for($v=floor(10*$id); $v<=$iph; ++$v) {
		foreach($size as $s) {
			$i = Rencontre::f_img($v.$s['label']);
			if(file_exists($rencDiv['basedir'].'/portrait/'.floor($id/1000).'/'.$i.'.jpg')) $a[$v]['jpg'.$s['label']] = array(
				'label' => $s['label'],
				'width' => $s['width'],
				'height' => $s['height'],
				'quality' => $s['quality'],
				'url' => $rencDiv['baseurl'].'/portrait/'.floor($id/1000).'/'.$i.'.jpg'
			);
			if(file_exists($rencDiv['basedir'].'/portrait/'.floor($id/1000).'/'.$i.'.webp')) $a[$v]['webp'.$s['label']] = array(
				'label' => $s['label'],
				'width' => $s['width'],
				'height' => $s['height'],
				'quality' => $s['quality'],
				'url' => $rencDiv['baseurl'].'/portrait/'.floor($id/1000).'/'.$i.'.webp'
			);
			if(file_exists($rencDiv['basedir'].'/portrait/'.floor($id/1000).'/'.$i.'@2x.jpg')) $a[$v]['@2x.jpg'.$s['label']] = array(
				'label' => $s['label'],
				'width' => $s['width']*2,
				'height' => $s['height']*2,
				'quality' => $s['quality'],
				'url' => $rencDiv['baseurl'].'/portrait/'.floor($id/1000).'/'.$i.'@2x.jpg'
			);
			if(file_exists($rencDiv['basedir'].'/portrait/'.floor($id/1000).'/'.$i.'@2x.webp')) $a[$v]['@2x.webp'.$s['label']] = array(
				'label' => $s['label'],
				'width' => $s['width']*2,
				'height' => $s['height']*2,
				'quality' => $s['quality'],
				'url' => $rencDiv['baseurl'].'/portrait/'.floor($id/1000).'/'.$i.'@2x.webp'
			);
		}
	}
	return $a;
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
		'lengthNameHome'=>50,
		'lengthName'=>50,
		'infochange'=>5000,
		'rencmodaltimeout'=>4000,
		'delNotConfirmed'=>60,
		'imgQuality'=>75,
		'urlNoCryptId'=>0,
		'visiteUpdateTime'=>0,
		'smileUpdateTime'=>0,
		'cronBenchmark'=>0,
		'flagPng'=>0,
		'retina'=>1
		);
	$a = $nb;
	if(has_filter('rencNumbers')) {
		global $rencOpt, $rencDiv, $rencCustom;
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
	if(has_filter('rencLabels')) $lb = apply_filters('rencLabels', $lb); // ex : $lb = array('renc' => 'tab', 'card' => 'fiche');
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
	if(!headers_sent() && empty(session_id())) session_start(); // not needed but ...
	if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
	global $wpdb, $current_user;
	if(!empty($_POST['lat']) && !empty($current_user->ID)) {
		$lat = round(floatval(rencSanit($_POST['lat'],'num')),5);
		$lon = round(floatval(rencSanit($_POST['lon'],'num')),5);
		$acc = rencSanit($_POST['acc'],'int');
		$opt = rencSanit($_POST['opt'],'int');
		if($opt==1 || $opt>$acc) $wpdb->update($wpdb->prefix.'rencontre_users', array('e_lat'=>$lat,'e_lon'=>$lon), array('user_id'=>$current_user->ID));
		$_SESSION['gps'] = 1;
	}
	session_write_close();
}
//
function rencFastreg_form() {
	global $rencCustom, $rencOpt;
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
			and R.i_status IN (4,12)
		"); // Count same IP in NEW REGISTRANT ONLY (robot registration)
	if($q1 || $q2>10) {
		$errors->add('user_email_error', __('Your email address is currently in quarantine. Sorry','rencontre'));
	}
	return $errors;
}
function rencFastreg($user_id) {
	global $wpdb, $rencOpt, $rencDiv;
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
	$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$user_id)); // deletion if already existing
	$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$user_id)); // deletion if already existing
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
		$wpdb->delete($wpdb->base_prefix.'usermeta', array('user_id'=>$user_id)); // remove WP roles
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
	global $rencOpt, $rencDiv;
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
		$blogName = get_bloginfo('name');
		$confirmUrl = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home']).'?'.$Lfr.'=1&'.$Loo.'='.urlencode($oo).'&'.$Lii.'='.urlencode($ii));
		// ****** TEMPLATE ********
		ob_start();
		if($tpl=rencTpl('rencontre_mail_fastreg_confirm.php')) include $tpl;
		$s = ob_get_clean();
		// ************************
		$s = trim(preg_replace('/\t+/', '', $s)); // remove tab
		$s = preg_replace('/^\s+|\n|\r|\s+$/m', '', $s); // remove line break
		if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
			$he[] = 'From: '.$t.' <'.$rencDiv['admin_email'].'>';
			$he[] = 'Content-type: text/html; charset=UTF-8';
			$s = '<html><head></head><body>'.$s.'</body></html>';
		}
		if(empty($mailSubj)) $mailSubj = $blogName.' - '.__('Confirmation email','rencontre');
		@wp_mail($u->user_email, $mailSubj, $s, $he);
		if($other==1) {
			echo __('Confirmation email sent','rencontre');
		}
		setcookie("rencfastregMail", 'yes');
	}
}
function rencistatus($f,$g) {
	// $f : i_status value
	// $g : capability - Like RW on linux (3 = 1+2) :
	//		0=>blocked (1, 3, 5, 7, 9, 11, 13, 15...)
	//		1=>mail blocked (2, 3, 6, 7, 10, 11, 14, 15...)
	//		2=>fastreg not completed (4, 5, 6, 7, 12, 13, 14, 15...)
	//		3=>mandatory profil field empty (8, 9, 10, 11, 12, 13, 14, 15...)
	//		4=>... (16...)
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
function rencUserCheckMandatory($id) {
	global $rencDiv, $wpdb;
	$sex = $wpdb->get_var("SELECT i_sex FROM ".$wpdb->prefix."rencontre_users WHERE user_id=".$id." LIMIT 1");
	$p = $wpdb->get_results("SELECT DISTINCT(id) 
		FROM ".$wpdb->prefix."rencontre_profil
		WHERE
			(c_genre=':' or (c_genre LIKE '%:%' and c_genre LIKE '%,".$sex.",%'))
			and i_poids<5
		"); // i_poids=5 => update not synchronised
	$pr = $wpdb->get_var("SELECT t_profil FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id=".$id." LIMIT 1");
	$profil = json_decode((empty($pr)?'{}':$pr),true);
	$b = 0;
	foreach($p as $r) {
		foreach($profil as $k=>$v) { 
			if(isset($v['i']) && $v['i']==$r->id && (!empty($v['v']) || is_array($v['v']))) $b = 1; // OK
		}
		if($b==1) $b = 0;
		else { // Mandatory field missing
			$rencDiv['istatus'] = rencistatusSet($st,3,1);
			$wpdb->update($wpdb->prefix.'rencontre_users', array('i_status'=>$rencDiv['istatus']), array('user_id'=>$id));
			break;
		}
	}
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
function rencTranslate($f) {
	global $rencCustom, $rencOpt;
	$o = $ho = false;
	// CUSTOM > WORDS
	if($f=='newText' && isset($rencCustom['new']) && !empty($rencCustom['newText'])) $o = $rencCustom['newText'];
	else if($f=='blockedText' && isset($rencCustom['blocked']) && !empty($rencCustom['blockedText'])) $o = $rencCustom['blockedText'];
	else if($f=='emptyText' && isset($rencCustom['empty']) && !empty($rencCustom['emptyText'])) $o = $rencCustom['emptyText'];
	else if($f=='jailText' && isset($rencCustom['jail']) && !empty($rencCustom['jailText'])) $o = $rencCustom['jailText'];
	else if($f=='nophText' && isset($rencCustom['noph']) && !empty($rencCustom['nophText'])) $o = $rencCustom['nophText'];
	else if($f=='relancText' && isset($rencCustom['relanc']) && !empty($rencCustom['relancText'])) $o = $rencCustom['relancText'];
	else if($f=='smiw1' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) $o = $rencCustom['smiw1'];
	else if($f=='smiw2' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw2'])) $o = $rencCustom['smiw2'];
	else if($f=='smiw3' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw3'])) $o = $rencCustom['smiw3'];
	else if($f=='smiw4' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw4'])) $o = $rencCustom['smiw4'];
	else if($f=='smiw5' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw5'])) $o = $rencCustom['smiw5'];
	else if($f=='smiw6' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw6'])) $o = $rencCustom['smiw6'];
	else if($f=='smiw7' && isset($rencCustom['smiw']) && !empty($rencCustom['smiw7'])) $o = $rencCustom['smiw7'];
	else if($f=='loow1' && isset($rencCustom['loow']) && !empty($rencCustom['loow1'])) $o = $rencCustom['loow1'];
	else if($f=='loow2' && isset($rencCustom['loow']) && !empty($rencCustom['loow2'])) $o = $rencCustom['loow2'];
	// CUSTOM > FEATURES (Relation & Gender)
	else if(isset($rencCustom['relation']) && substr($f,0,9)=='relationL' && !empty($rencCustom[$f])) $o = $rencCustom[$f];
	else if(isset($rencCustom['sex']) && substr($f,0,4)=='sexL' && !empty($rencCustom[$f])) $o = $rencCustom[$f];
	// GENERAL > EMAILS
	else if($f=='textmail' && !empty($rencOpt['textmail'])) $o = $rencOpt['textmail'];
	else if($f=='textanniv' && !empty($rencOpt['textanniv'])) $o = $rencOpt['textanniv'];
	// TEMPLATE ACCOUNT_DELETE
	else if($f=='dele1' || $f=='dele2') $o = 1;
	//
	if(!empty($o) && has_filter('rencTranslate')) $ho = apply_filters('rencTranslate', $f);
	return (!empty($ho)?$ho:(!empty($o)&&$o!==1?stripslashes($o):''));
}
function rencConvertUnit($f,$u,$g=0) {
	// $u : 'kg', 'lbs', 'cm', 'ft', 'in'
	// in & ft => cm
	// cm => in
	// $g : 0 = exact value, 1 = ROUND INT, 2 = x10 ROUND INT (75.247kg => 752), 3 = ROUND .5 (10, 10.5, 11, 11.5 inch)
	$f = (float)$f;
	switch($u) {
		// ************************************************************
		case 'kg': // Return LBS - 1 kg = 2,2046225 lbs
			$a = $f * 2.2046225;
		break;
		// ************************************************************
		case 'lbs': // Return KG - 1 lbs = 0.4535924 kg
			$a = $f * 0.4535924;
		break;
		// ************************************************************
		case 'cm': // Return IN - 1 cm = 0.3937008 in (0.0328084 ft)
			$a = $f * 0.3937008;
		break;
		// ************************************************************
		case 'ft': // Return CM - 1 ft = 30.48 cm
			$a = $f * 30.48;
		break;
		// ************************************************************
		case 'in': // Return CM - 1 in = 2.54 cm
			$a = $f * 2.54;
		break;
		// ************************************************************
	}
	if(!empty($a)) {
		if($g===2) return intval((10*$a)+.5); // DB - 75.347kg => 753
		if($g===3) return round($a*2)/2; // 75.347kg => 75.5 (round .5) - Specific for inch 
		else if($g) return intval($a+.5); // 75.247kg => 75
		else return $a; // 75.247kg
	}
	else return 0;
}
function rencIn2Ft($f) {
	// $f : inch value
	// return string : 100 => 8 ft - 4.5 in
	if($f) {
		$f = (float)$f;
		$f = (floor($f * 2 + .5)) / 2; // Scale 0.5
		return (floor($f / 12)) . ' ' . __('ft','rencontre') . ' - ' . (round(( (($f / 12) - floor($f / 12)) * 12), 1)) . ' ' . __('in','rencontre');
	}
}
function renc_clear_cache_portrait() {
	global $rencDiv, $rencOpt;
	$a = array(
		'/portrait/cache/cache_portraits_accueil.html',
		'/portrait/cache/cache_portraits_accueil1.html',
		'/portrait/cache/cache_portraits_accueilmix.html', // mix = 1 (old)
		'/portrait/cache/cache_portraits_accueilgirl.html',
		'/portrait/cache/cache_portraits_accueilmen.html',
		'/portrait/libre/libreIDs.json'
		);
	foreach($a as $r) if(file_exists($rencDiv['basedir'].$r)) @unlink($rencDiv['basedir'].$r);
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
function rencTplDir($origin=0) {
	$tdir = array(
		'original' => 1,
		'path' => realpath(__DIR__ . '/..').'/templates/',
		'url'  => plugins_url('rencontre/templates/'),
		'csspath' => realpath(__DIR__ . '/..').'/css/',
		'cssurl'  => plugins_url('rencontre/css/')
		);
	if(empty($origin) && has_filter('rencTemplateDir')) {
		$tdir = apply_filters('rencTemplateDir', $tdir);
		if(isset($tdir['original'])) unset($tdir['original']);
	}
	return $tdir;
}
//
function rencTpl($tpl,$noplug=0,$plugin='rencontre',$url=0) { // $url only for rencjs.js
	$o = false;
	if(empty($tdir)) $tdir = rencTplDir();
	if(file_exists(get_stylesheet_directory().'/templates/'.$tpl)) $o = (!$url?get_stylesheet_directory():get_stylesheet_directory_uri()).'/templates/'.$tpl;
	else if($tdir['path']!=WP_PLUGIN_DIR.'/'.$plugin.'/templates/' && file_exists($tdir['path'].$tpl)) $o = (!$url?$tdir['path']:$tdir['url']).$tpl;
	else if(!$noplug && !$url) $o = WP_PLUGIN_DIR.'/'.$plugin.'/templates/'.$tpl;
	return $o;
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
	$u->taille = number_format($u->i_taille/10, 1); // 1722 => 172.2 (calcul)
	$u->i_taille = intval($u->i_taille/10+.5); // 1722 => 172 (display)
	$u->poids = number_format($u->i_poids/10, 1);
	$u->i_poids = intval($u->i_poids/10+.5);
	return $u;
}
function rencGetUserPhotos($id) {
	global $wpdb, $rencDiv;
	$photos = array();
	$size = rencPhotoSize();
	$hob = false; if(has_filter('rencBlurP')) $hob = apply_filters('rencBlurP', $hob);
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
function rencGetUserProfils($id,$lang='') {
	global $wpdb;
	if(strlen($lang)!=2) $lang = substr(get_locale(),0,2);
	$profil = array();
	$q = $wpdb->get_var("SELECT t_profil FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id=".$id." LIMIT 1");
	$p = json_decode((empty($q)?'{}':$q),true);
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
			and c_lang='".$lang."'
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
				$v = json_decode((empty($l[$i]->t_valeur)?'{}':$l[$i]->t_valeur));
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
// ADMIN Part in base.php
//
?>

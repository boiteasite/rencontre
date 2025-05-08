<?php
//
class RencontreWidget extends WP_widget {
 	function __construct() {
		parent::__construct('rencontre-widget','Rencontre',array('description'=>__('Widget to integrate the dating website', 'rencontre'),));
	}
	//
	function widget($arguments, $data) { // Partie Site
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && stripos($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest')!==false) return; // Stop AJAX CALL to improve server speed (by Rencontre or other plugin...)
		if(current_user_can("administrator")) {
			echo '<div class="w3-bar w3-padding w3-center w3-renc-wabg">';
			echo '<h2>'.__('You are connected as ADMIN','rencontre').'</h2>';
			echo '<h3>'.__('You cannot use RENCONTRE as a user if you have ADMIN rights','rencontre').'</h3>';
			echo '</div>';
			return;
		}
		global $current_user, $wpdb, $rencDrap, $rencDrapNom, $rencOpt, $rencDiv, $rencCustom, $rencWidg, $rencU0;
		$rencWidg = 1;
		$rencOpt['page_id'] = get_the_id(); // More solid value there
		// Old installation directly in theme file
		$tdir = rencTplDir();
		if(!wp_style_is('rencontre') && !empty($tdir['original'])) { ?>
			<link rel="stylesheet" href="<?php echo plugins_url('rencontre/css/rencontre.css'); ?>" />
			<link rel="stylesheet" href="<?php echo plugins_url('rencontre/css/w3.css'); ?>" />
			<link rel="stylesheet" href="<?php echo plugins_url('rencontre/css/fontawesome/css/font-awesome5.css'); ?>" />
			<?php rencAddCustomW3css(1); ?>
		<?php }
		if(!wp_script_is('rencontre')) { ?>
			<script type="text/javascript" src="<?php echo plugins_url('rencontre/js/rencontre.js'); ?>"></script>
		<?php }
		// GET & POST var
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); // also POST
		$Lidf = (!empty($rencOpt['lbl']['rencidfm'])?$rencOpt['lbl']['rencidfm']:'rencidfm');
		$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id'); // also POST
		$Lcard = (!empty($rencOpt['lbl']['card'])?$rencOpt['lbl']['card']:'card');
		$Ledit = (!empty($rencOpt['lbl']['edit'])?$rencOpt['lbl']['edit']:'edit');
		$Lmsg = (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg'); // also POST
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
		$Lline = (!empty($rencOpt['lbl']['line'])?$rencOpt['lbl']['line']:'line');
		$Lzsex = (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex');
		$Lhomo = (!empty($rencOpt['lbl']['homo'])?$rencOpt['lbl']['homo']:'homo');
		//
		$Grencidfm = (isset($_GET[$Lidf])?rencSanit(rencGetId($_GET[$Lidf],1),'alphanum'):'');
		$Grenc = (isset($_GET[$Lrenc])?rencSanit($_GET[$Lrenc],'alphanum'):'');
		$Gid = (isset($_GET[$Lid])?rencSanit(rencGetId($_GET[$Lid],1),'alphanum'):''); // ID or action (sourireIn...
		$Pnouveau = (isset($_POST['nouveau'])?rencSanit($_POST['nouveau'],'alphanum'):'');
		$Pa1 = (isset($_POST['a1'])?rencSanit($_POST['a1'],'alphanum'):'');
		$Pa2 = (isset($_POST['a2'])?rencSanit($_POST['a2'],'img'):'');
		$Protate = (isset($_POST['rotate'])?rencSanit($_POST['rotate'],'num'):'');
		$Pmsg = (isset($_POST[$Lmsg])?rencSanit($_POST[$Lmsg],'alphanum'):false);
		$Pcontenu = (!empty($_POST['contenu'])?rencsanit($_POST['contenu'],'para'):'');
		$Pid = (isset($_POST['P'.$Lid])?rencSanit($_POST['P'.$Lid],'int'):''); // Post used in message part
		$rencTok = wp_create_nonce('rencTok');
		$rencTokc = $_SESSION['rencTokc']; // Chat
		// ******************************
		$ho = false; if(strpos($_SESSION['rencontre'].'-','nouveau')===false && has_filter('rencGateP')) $ho = apply_filters('rencGateP', $ho);
		if($ho) $_SESSION['rencontre'] = 'gate';
		if(!wp_style_is('rencontre')) { ?>
		
		<link rel="stylesheet" href="<?php echo plugins_url('rencontre/css/rencontre.css'); ?>" />
		<?php }
		if(!wp_script_is('rencontre')) wp_enqueue_script('rencontre');
		//
		$rencidfm = ''; // lien direct vers la fiche d un membre depuis un mail
		if(ctype_alpha(substr($Grencidfm,0,1))) $rencidfm = substr($Grencidfm,0,1).intval(substr($Grencidfm,1));
		else if($Grencidfm!=='') $rencidfm = intval($Grencidfm);
		$mid = $current_user->ID; // Mon id
		$r = $rencDiv['basedir'].'/portrait';if(!is_dir($r)) mkdir($r);
		//
		$q = $wpdb->get_results("SELECT
				c_liste_categ,
				c_liste_valeur,
				c_liste_iso
			FROM ".$wpdb->prefix."rencontre_liste 
			WHERE 
				c_liste_categ='d' or
				(c_liste_categ='p' and c_liste_lang='".substr($rencDiv['lang3'],0,2)."') ");
		$rencDrap = array(); $rencDrapNom = array();
		foreach($q as $r) {
			if(!empty($rencOpt['nbr']['flagPng']) && $r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur; // PNG
			else if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = 'svg/'.strtolower($r->c_liste_iso).'.svg'; // SVG
			else if($r->c_liste_categ=='p')$rencDrapNom[$r->c_liste_iso] = $r->c_liste_valeur;
		}
		//
		if($Pnouveau!=='' && $Pa1==$mid) {
			if($Pnouveau=='update') self::f_updateMember($mid);
			else self::f_registerMember($mid,$Pnouveau);
		}
		// *****************************************************************************************************************
		// 0. Partie menu
		require(dirname (__FILE__) . '/../lang/rencontre-js-lang.php');
		$u0 = clone $rencU0;
		if(has_filter('rencJsLang')) $lang = apply_filters('rencJsLang', $lang);
		if(isset($rencOpt['tchat'])) {
			if(isset($_SESSION['tchat'])) $tchatName = $wpdb->get_var("SELECT display_name FROM ".$wpdb->base_prefix."users WHERE ID=".$_SESSION["tchat"]." LIMIT 1");
			$lang += array(
				'mid'=>$current_user->ID,
				'ajaxchat'=>plugins_url('rencontre/inc/rencontre_tchat.php'),
				'wpajax'=>admin_url('admin-ajax.php'),
				'tchatname'=>(!empty($tchatName)?$tchatName:''), // Change windows just after chat request : name other user lost
				'tchaton'=>(isset($rencOpt['tchat'])?$rencOpt['tchat']:0),
				'bipurl'=>((file_exists(get_stylesheet_directory().'/templates/bip.mp3')&&file_exists(get_stylesheet_directory().'/templates/bip.ogg'))?get_stylesheet_directory_uri().'/templates/':$rencDiv['siteurl'].'/wp-content/plugins/rencontre/js/'),
				'noBip'=>(strpos($rencU0->t_action.'-',',nobip,')?1:0),
				'noEmot'=>(empty($rencCustom['emot'])?0:1)
				);
			if(has_filter('rencLimitedActionP') && is_file($rencDiv['basedir'].'/tchat/check'.$current_user->ID.'.txt')) {
				$a = file_get_contents($rencDiv['basedir'].'/tchat/check'.$current_user->ID.'.txt');
				apply_filters('rencLimitedActionP', array('chat',1,$a));
				@unlink($rencDiv['basedir'].'/tchat/check'.$current_user->ID.'.txt');
			}
		}
		wp_localize_script('rencontre', 'rencobjet', $lang);
		if(!isset($rencOpt['fastreg'])) $rencOpt['fastreg'] = 0;
		//
		$fantome = $rencDiv['titann']; // Old var - not needed V3.7
		if(!empty($rencOpt['pacamsg']) || !empty($rencOpt['pacasig'])) {
			if(!$rencDiv['titann']) $a = true; // photo & attention-catcher & ad ; false => OK
			else $a = (($u0->i_photo)?false:true);
			$rencDiv['pacam'] = (!empty($rencOpt['pacamsg']))?$a:false;
			$rencDiv['pacas'] = (!empty($rencOpt['pacasig']))?$a:false;
		} ?>

		<div id="widgRenc" class="widgRenc">
			<script type="text/javascript"><?php 
				// JS VAR rencUrl is defined here. Checked in rencontre.js => rencWidg : if undefined, this widget is not loaded !
				echo "var rencUrl='".$rencDiv['siteurl']."',";
				echo "rencBaseurl='".$rencDiv['baseurl']."',";
				echo "rencBasedir='".$rencDiv['basedir']."',";
				echo "rencTok='".$rencTok."',rencTokc='".$rencTokc."',";
				if(!empty($rencOpt['tchatsm'])) echo "rencChatSm=1,";
				if(!empty($rencOpt['nbr']['rencmodaltimeout'])) echo "rencmodaltimeout=".$rencOpt['nbr']['rencmodaltimeout'].",";
				echo "rencInfochange='".(!empty($rencOpt['nbr']['infochange'])?$rencOpt['nbr']['infochange']:5000)."';";
				$blockSearch = false; if(has_filter('rencLimitedActionP')) $blockSearch = apply_filters('rencLimitedActionP', array('search',0,0)); // DISABLE MENU SEARCH BUTTON
				$m = "{edit:".((isset($rencOpt['fastreg'])&&$rencOpt['fastreg']>1)?0:1).",msg:".((isset($rencOpt['fastreg'])&&$rencOpt['fastreg']>1)?0:1).",search:".($blockSearch?0:1)."}";
				$d = 0;
				$c = array(
					$Lcard=>"rencMenuCard",
					$Ledit=>"rencMenuEdit",
					$Lmsg=>"rencMenuMsg",
					$Lwrite=>"rencMenuMsg",
					$Lgsearch=>"rencMenuSearch",
					$Lliste=>"rencMenuSearch",
					$Lqsearch=>"rencMenuSearch",
					$Laccount=>"rencMenuAccount",
					"c1"=>"rencMenuC1",
					"c2"=>"rencMenuC2"
					);
				
				if($Grenc && isset($c[$Grenc])) $d = $c[$Grenc];
				if($Grenc && $Gid && $Grenc==$Lcard && $Gid!=$mid) $d = 0;
				echo 'jQuery(document).ready(function(){if(typeof f_renc_menu==="function")f_renc_menu('.$m.','.$mid.',"'.$d.'");';
			//	echo 'document.addEventListener("DOMContentLoaded",function(event){f_renc_menu('.$m.','.$mid.',"'.$d.'");';
				if(isset($tchatName)) echo 'f_tchat_veille('.$_SESSION['tchat'].',\''.$tchatName.'\');';
				if(!empty($rencOpt['gps']) && empty($_SESSION['gps']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' && isset($_SESSION['rencontre']) && ($_SESSION['rencontre']=='nouveau1' || strstr($_SESSION['rencontre'],'account'))) {
					echo 'f_gpsNavigator(\''.admin_url('admin-ajax.php').'\',0);'; // geolocalisation only with HTTPS
				}
				echo '});';
			?></script>
			<?php if(isset($rencOpt['tchat'])) {
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_chat.php')) include $tpl;
				// ************************
			} ?>
			
			<form name="rencMenu" method="get" action="">
				<input type="hidden" name="<?php echo $Lrenc; ?>" value="" />
				<input type="hidden" name="<?php echo $Lid; ?>" value="<?php echo rencGetId($mid,0); ?>" />
			<?php if(!empty($rencOpt['page_id'])) { ?>
			
				<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
			<?php }
			$ho = false; if(has_filter('rencLimitedActionP')) $ho = apply_filters('rencLimitedActionP', array('cam',0,0));
			if(!$ho) { 
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_chat_cam.php')) include $tpl;
				// ************************
			}
			$currentHome = '';
			if(strstr($_SESSION['rencontre'],'mini')) $currentHome = 'class="current"';
			if(empty($rencCustom['menu'])) {
				$u = get_permalink($rencOpt['page_id']);
				$link = array('home'=>($u?$u:(!empty($rencOpt['home'])?rencOpt['home']:site_url()))); // rencontre.php rencwidget();
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_menu.php')) include($tpl);
				// ************************
				$ho = ''; if(has_filter('rencMsgNotifP')) $ho = apply_filters('rencMsgNotifP', array(3,$mid));
				if($ho) echo $ho;
			} ?>
			</form>
			<div id="rencAdsM" class="rencAds w3-margin-bottom">
			<?php $ho = false; if(has_filter('rencAdsMP')) $ho = apply_filters('rencAdsMP', $ho); if($ho) echo $ho; ?>
			</div><!-- .rencAds -->
			<?php if($rencDiv['rencBlock']) { ?>
			
			<div class="rencBlock">
				<?php if($t=rencTranslate('blockedText')) echo $t;
				else _e('Your account is blocked. You are invisible. Change your profile.','rencontre'); ?>
				
			</div>
			<?php }
		//
		//
		if(isset($_SESSION['rencontre']) && $_SESSION['rencontre']=='gate') self::rencGate(); // Entry screening
		//
		// 1. Nouveau visiteur
		else if(strstr($_SESSION['rencontre'],'nouveau')) {
			$u0 = clone $rencU0;
			$hoj = false; $q = false;
			if(has_filter('rencJailP')) $hoj = apply_filters('rencJailP', $current_user->user_email);
			else $q = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."rencontre_prison WHERE c_mail='".$current_user->user_email."' LIMIT 1");
			if($q || $hoj) { 
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_registration_jail.php')) include($tpl);
				// ************************
			}
			else if($_SESSION['rencontre']=='nouveau') { 
				$onClick = array("save"=>"f_nouveau(".$mid.",0)");
				$y = current_time('Y');
				$oldmax = $y-(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99)-1;
				$oldmin = $y-(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18)+1;
				$old35 = $y-35;
				$month = array();
				for($v=0;$v<12;++$v) $month[$v+1] = date_i18n('M', $v*2626560+1263556800); // $v * 30j + 15 jan 2010
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_registration_part1.php')) include($tpl);
				// ************************
			}
			else if($_SESSION['rencontre']=='nouveau1') { 
				$onClick = array(
					"country"=>"f_region_select(this.options[this.selectedIndex].value,'".admin_url('admin-ajax.php')."','regionSelect')",
					"save"=>"f_nouveau(".$mid.",1)",
					);
				if(function_exists('wpGeonames_shortcode') && empty($rencCustom['country']) && empty($rencCustom['place']) && empty($rencCustom['region'])) {
					$rencGeonames = wpGeonames_shortcode(array('map'=>1, 'data'=>$rencOpt['pays'].'|||0|0'));
				}
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_registration_part2.php')) include($tpl);
				// ************************
			}
			else if($_SESSION['rencontre']=='nouveau2') { 
				$onClick = array(
					"agemin"=>"f_min(this.options[this.selectedIndex].value,'formNouveau','zageMin','zageMax')",
					"agemax"=>"f_max(this.options[this.selectedIndex].value,'formNouveau','zageMin','zageMax')",
					"save"=>"f_nouveau(".$mid.",2)"
					);
				$hetero = false;
				if(empty($rencCustom['born'])) {
					// Not used from V3.13.3 
					$a = (isset($rencCustom['agemin'])?intval($rencCustom['agemin']):25);
					$u0->agemin = ($u0->age-10>$a?$u0->age-10:$a);
					$a = (isset($rencCustom['agemax'])?intval($rencCustom['agemax']):45);
					$u0->agemax = ($u0->age+10<$a?$u0->age+10:$a);
				}
				if(!empty($rencCustom['hetero'])) $hetero = $u0->i_sex;
				$script = '';
				if(!empty($rencCustom['multiSR'])) $script = '<script>jQuery(document).ready(function(){jQuery(":checkbox.rencLabelauty").labelauty({icon:false});});</script>';
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_registration_part3.php')) include($tpl);
				// ************************
				$ho = false; if(has_filter('rencAdwC')) $ho = apply_filters('rencAdwC', $ho); if($ho) echo $ho;
			}
		}
		//
		// 2. Partie portrait
		else if(strstr($_SESSION['rencontre'],'card')) {
			$id = ($rencidfm)?substr($rencidfm,1):($Gid?$Gid:$mid);
			if($id!=$mid && empty($rencOpt['paus'])) {
				$a = $wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id='".$id."' and t_action REGEXP ',pause1,|,pause2,' LIMIT 1");
				if($a || $rencDiv['mpause']) $id = $mid;
			}
			$favori = 0;
			if(strstr($_SESSION['rencontre'],'favoriAdd')) {
				self::f_favori($id,2);
				$favori = 2;
			}
			else if(strstr($_SESSION['rencontre'],'favoriDel')) {
				self::f_favori($id,1);
				$favori = 1;
			}
			$rencidfm = 0; // RAZ du lien messagerie
			$u0 = clone $rencU0;
			if($Pa1=='plusImg') {
				self::plusImg($Pa2,$mid,$Protate);
				$u0->i_photo = 1;
			}
			if($id!=$mid && empty($rencU0->photoreq)) {
				$u = $wpdb->get_row("SELECT
						U.ID,
						U.user_login,
						U.user_registered,
						U.display_name,
						R.c_pays,
						R.c_region,
						R.c_ville,
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
						R.e_lat,
						R.e_lon,
						R.d_session,
						P.t_titre,
						P.t_annonce,
						P.t_profil,
						P.t_action
					FROM ".$wpdb->base_prefix."users U
					INNER JOIN ".$wpdb->prefix."rencontre_users R
						ON R.user_id=U.ID
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=U.ID
					WHERE
						U.ID=".intval($id)."
						and (R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').") or U.ID=".$mid.")
					LIMIT 1
					");
				if(empty($u)) $u = clone $rencU0; // avoid error
				else {
					$u->taille = number_format($u->i_taille/10, 1); // 1722 => 172.2 (calcul)
					$u->i_taille = intval($u->i_taille/10+.5); // 1722 => 172 (display)
					$u->poids = number_format($u->i_poids/10, 1);
					$u->i_poids = intval($u->i_poids/10+.5);
				}
				$u->online = self::f_enLigne($id,$u->d_session); // true : en ligne - false : hors ligne
				//
			}
			else {
				$u = clone $rencU0;
				$u->online = true;
			}
			$u->blocked = new StdClass();
			$u->blocked->me = false;
			$u->blocked->he = false;
			$u->t_annonce = nl2br($u->t_annonce);
			$action = json_decode((empty($u->t_action)?'{}':$u->t_action),true);
			$u->zstrict = (isset($action['option'])&&strpos($action['option'],',zstrict,')!==false)?1:0;
			$m = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
			$u->display_name = substr($u->display_name,0,$m);
			if(strstr($_SESSION['rencontre'],'bloque')) self::f_bloque($id);
			if($mid!=$id) {
				self::f_visite($id); // visite du profil - enregistrement sur ID
				$u->blocked->he = self::f_etat_bloque($id); // je l ai bloque ? - lecture de MID
				if(has_filter('rencLimitedActionP')) apply_filters('rencLimitedActionP', array('profil',1,$id));
			}
			if($u->ID) {
				$u->blocked->me = self::f_etat_bloque1($id,$u->t_action); // je suis bloque ?
				if(strtotime($u->d_session) && !$u->online) {
					$b = $u->d_session;
					if(is_file($rencDiv['basedir'].'/session/'.$id.'.txt') && strtotime($b)<filemtime($rencDiv['basedir'].'/session/'.$id.'.txt')) $b = date("Y-m-d H:i:s", filemtime($rencDiv['basedir'].'/session/'.$id.'.txt'));
					$u->session = self::format_date($b);
					$a = human_time_diff(strtotime($b), current_time('timestamp'));
					$u->session_ago = sprintf(__('%s ago','rencontre'), $a);
				}
				else {
					$u->session = 0;
					$u->session_ago = '';
				}
				$homax = false; if(has_filter('rencNbPhotoExtP')) $homax = apply_filters('rencNbPhotoExtP', $id);
				$u->maxPhoto = ($homax!==false?min($homax,(isset($rencOpt['imnb']))?$rencOpt['imnb']:4):(isset($rencOpt['imnb'])?$rencOpt['imnb']:4));
				$u->photoUrl = $rencDiv['baseurl'].'/portrait/'.floor($id/1000).'/';
				$u->photo = new StdClass();
				$u->photo->full = array();
				$u->photo->mini = array();
				$u->photo->miniWebp = array();
				$u->photo->grande = array();
				$u->photo->over = array();
				$title = array(
					"thumb"=>"",
					"send"=>"",
					"smile"=>"",
					"contact"=>"",
					"chat"=>($u->online?__('online','rencontre'):__('offline','rencontre')),
					"block"=>"",
					"report"=>"",
					"zoombox"=>""
					);
				$disable = array(
					"thumb"=>0,
					"send"=>0,
					"smile"=>0,
					"contact"=>0,
					"chat"=>0,
					"block"=>0,
					"report"=>0,
					"favori"=>($favori?($favori==1?0:1):self::f_favori($id,0))
					);
				$onClick = array(
					"thumb"=>"",
					"send"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lwrite."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();",
					"smile"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lsourire."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();",
					"contact"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Ldemcont."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();",
					"chat"=>"f_tchat_btn(".$mid.",".$id.",'".plugins_url('rencontre/inc/rencontre_tchat.php')."','".$u->display_name."')",
					"block"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lbloque."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();",
					"report"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lsignale."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();",
					"favoriAdd"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$LfavoriAdd."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();",
					"favoriDel"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$LfavoriDel."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->ID,0)."';document.forms['rencMenu'].submit();"
					);
				$ho = false; if(has_filter('rencThumbP')) $ho = apply_filters('rencThumbP', $ho);
				$hob = false; if(has_filter('rencBlurP')) $hob = apply_filters('rencBlurP', $hob);
				if(($u->i_photo)!=0) {
					if(!empty($rencOpt['photoz']) && !$u0->i_photo) {
						$disable['thumb'] = 1;
						$onClick['thumb'] = 'onClick="document.getElementById(\'rencPhotoPop\').style.display=\'block\'"';
					}
					if($ho && $mid!=$id) {
						$title['thumb'] = stripslashes($ho); // only thumbnail
						$onClick['thumb'] = 'onClick="f_modalWarn(\''.addslashes($title['thumb']).'\')"';
						$title['zoombox'] = stripslashes($ho);
					}
					else if(!empty($rencOpt['photoz']) && !$u0->i_photo) { // no photo
						if($t=rencTranslate('nophText')) $title['thumb'] = $t;
						else $title['thumb'] = addslashes(__("To be more visible and to view photos of other members, you should add one to your profile.","rencontre"));
					}
				}
				else $disable['thumb'] = 1;
				for($v=0;$v<$u->maxPhoto;++$v) {
					if(($u->ID)*10+$v <= $u->i_photo) {
						$a = Rencontre::f_img((($u->ID)*10+$v).'-mini');
						// Browser Caching Bypass
						$b = 0;
						if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.$a.'.jpg')) $b = filemtime($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.$a.'.jpg');
						$u->photo->mini[$v] = $a.'.jpg?'.$b;
						// RETINA
						$u->photo->miniRetina[$v] = '';
						if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.$a.'@2x.jpg')) $u->photo->miniRetina[$v] = $rencDiv['baseurl'].'/portrait/'.floor($u->ID/1000).'/'.$a.'@2x.jpg?'.$b.' 2x';
						// WEBP
						$u->photo->miniWebp[$v] = '';
						if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.$a.'.webp')) {
							$u->photo->miniWebp[$v] = $a.'.webp?'.$b; // TPL : $u->photoUrl.$u->photo->miniWebp[$v]
							if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.$a.'@2x.webp')) $u->photo->miniWebp[$v] .= ' 1x,'.$rencDiv['baseurl'].'/portrait/'.floor($u->ID/1000).'/'.$a.'@2x.webp?'.$b.' 2x';
						}
						//
						if(empty($rencOpt['photoz']) || $u0->i_photo || $hob) {
							$u->photo->grande[$v] = Rencontre::f_img((($u->ID)*10+$v).'-grande').'.jpg?'.$b;
							$u->photo->full[$v] = Rencontre::f_img((($u->ID)*10+$v)).'.jpg?'.$b;
							// RETINA
							$u->photo->grandeRetina[$v] = '';
							$u->photo->grandeRet[$v] = 0; // not exists (JS)
							if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.Rencontre::f_img((($u->ID)*10+$v).'-grande').'@2x.jpg')) {
								$u->photo->grandeRetina[$v] = $rencDiv['baseurl'].'/portrait/'.floor($u->ID/1000).'/'.Rencontre::f_img((($u->ID)*10+$v).'-grande').'@2x.jpg?'.$b.' 2x';
								$u->photo->grandeRet[$v] = 1; // exists (JS)
							}
							$u->photo->fullRetina[$v] = '';
							if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.Rencontre::f_img(($u->ID)*10+$v).'@2x.jpg')) {
								$u->photo->fullRetina[$v] = $rencDiv['baseurl'].'/portrait/'.floor($u->ID/1000).'/'.Rencontre::f_img(($u->ID)*10+$v).'@2x.jpg?'.$b.' 2x';
							}
							//
							$u->photo->over[$v] = "f_vignetteRetina(".(($u->ID)*10+$v).",'".Rencontre::f_img((($u->ID)*10+$v)."-grande")."',".$u->photo->grandeRet[$v].",'".$b."')";
						}
						else $u->photo->over[$v] = "";
					}
				}
				//
				$blocked = '';
				if($u->blocked->he) $blocked = '<span style="font-weight:bold;color:red;text-transform:uppercase;"> '.__('(blocked)','rencontre').'</span>';
				// BUTTONS
				// Send a message - Smile - Ask for a contact - Chat - Block - Report	
				if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) {
					$disable['send'] = $disable['smile'] = $disable['contact'] = $disable['chat'] = $disable['report'] = $disable['block'] = 1;
					$title['send'] = $title['smile'] = $title['contact'] = $title['chat'] = $title['report'] = $title['block'] = __('You must first complete your registration.','rencontre');
				}
				else if($u->blocked->me) {
					$disable['send'] = $disable['smile'] = $disable['contact'] = $disable['chat'] = $disable['report'] = 1;
					$title['send'] = $title['smile'] = $title['contact'] = $title['chat'] = $title['report'] = __('This member blocked you.','rencontre');
				}
				else if($rencDiv['rencBlock']) {
					$disable['send'] = $disable['smile'] = $disable['contact'] = $disable['chat'] = $disable['report'] = 1;
					$title['send'] = $title['smile'] = $title['contact'] = $title['chat'] = $title['report'] = __('You have been blocked by the moderator.','rencontre');
				}
				else {
					if(!isset($rencCustom['smile']) && has_filter('rencLimitedActionP')) {
						$ho = apply_filters('rencLimitedActionP', array('smile',0,0));
						$disable['smile'] = ($ho?1:0);
						$title['smile'] = ($ho?$ho:'');
					}
					if(!isset($rencCustom['creq']) && has_filter('rencLimitedActionP')) {
						$ho = apply_filters('rencLimitedActionP', array('contreq',0,0));
						$disable['contact'] = ($ho?1:0);
						$title['contact'] = ($ho?$ho:'');
					}
					if($rencDiv['pacam']) {
						$disable['send'] = 1;
						$title['send'] = __('Members without photo, attention-catcher and ad cannot send message','rencontre') . '.';
					}
					else if(rencistatus($rencDiv['istatus'],1)) {
						$disable['send'] = 1;
						$title['send'] = __('You are no longer allowed to send messages.','rencontre');
					}
					else if(has_filter('rencLimitedActionP')) {
						$ho = apply_filters('rencLimitedActionP', array('sendmsg',0,0));
						$disable['send'] = ($ho?1:0);
						$title['send'] = ($ho?$ho:'');
					}
					if(!$u->online) {
						$disable['chat'] = 1;
						$title['chat'] = __('offline','rencontre');
					}
					else if(has_filter('rencLimitedActionP')) {
						$ho = apply_filters('rencLimitedActionP', array('chat',0,$id));
						$disable['chat'] = ($ho?1:0);
						$title['chat'] = ($ho?$ho:'');
					}
					if($rencDiv['pacas']) {
						$disable['report'] = 1;
						$title['report'] = __('Members without photo, attention-catcher and ad cannot make a report','rencontre') . '.';
					}
					if($u0->zstrict || $u->zstrict) {
						$b = self::f_zstrict($u); // ligne 1862
						if($b) $disable['send'] = $disable['smile'] = $disable['contact'] = $disable['chat'] = $disable['report'] = 1;
					}
				}
				if(isset($rencCustom['smile'])) $disable['smile'] = 1; // securite
				if(isset($rencCustom['creq'])) $disable['contact'] = 1; // securite
				if(isset($rencCustom['report'])) $disable['report'] = 1;
				if(empty($rencOpt['tchat'])) $disable['chat'] = 1; // securite
				//
				$ho = false; if(has_filter('rencAstro2P') && !isset($rencCustom['born'])) $ho = apply_filters('rencAstro2P', $u->d_naissance);
				if($ho) $portraitAdd1 = $ho;
				else $portraitAdd1 = '<div>&nbsp;</div>';
				//
				$u->looking = '';
				$u->forwhat = '';
				if($u->i_zsex!=99) {
					// looking
					if(isset($rencOpt['iam'][$u->i_zsex])) $u->looking = $rencOpt['iam'][$u->i_zsex];
					// forwhat
					if(isset($rencOpt['for'][$u->i_zrelation])) $u->forwhat = ' '.__('for','rencontre').' '.$rencOpt['for'][$u->i_zrelation];
				}
				else {
					// looking
					$a = explode(',', $u->c_zsex);
					$as = '';
					foreach($a as $a1) if(isset($rencOpt['iam'][$a1])) $as .= $rencOpt['iam'][$a1] . ', ';
					$u->looking = substr($as,0,-2);
					// forwhat
					$a = explode(',', $u->c_zrelation);
					$as = '';
					foreach($a as $a1) if(isset($rencOpt['for'][$a1])) $as .= $rencOpt['for'][$a1] . ', ';
					$u->forwhat = ' '.__('for','rencontre').' '.substr($as,0,-2);
				}
				// looking
				if(!isset($rencCustom['born']) && $u->i_zage_min) $u->looking .= ' '.__('between','rencontre').' '.$u->i_zage_min.' '.__('and','rencontre').' '.$u->i_zage_max.' '.__('years','rencontre');
				//
				$infochange = false;
				if(strstr($_SESSION['rencontre'],'sourire')) $infochange = self::f_sourire($id);
				else if(strstr($_SESSION['rencontre'],'signale')) $infochange = self::f_signal($id);
				else if(strstr($_SESSION['rencontre'],'demcont')) $infochange = self::f_demcont($id);
				// profil
				$u->profil = array();
				$hideProf = false; if($mid!=$id && has_filter('rencViewpP')) $hideProf = apply_filters('rencViewpP', $hideProf);
				$profil = json_decode((empty($u->t_profil)?'{}':$u->t_profil),true);
				$out = ''; $l = '';
				if($profil) {
					foreach($profil as $h) $l .= $h['i'].',';
					$l = substr($l,0,-1);
					$q = $wpdb->get_results("SELECT
							id,
							c_categ,
							c_label,
							t_valeur,
							i_type,
							c_genre
						FROM ".$wpdb->prefix."rencontre_profil
						WHERE
							id IN (".$l.") and
							c_lang='".substr($rencDiv['lang2'],0,2)."' and
							c_categ!='' and
							c_label!='' and
							c_genre NOT LIKE '%:%' and
							i_poids<5
						"); // Ordre inutile (get_row)
					if($q) {
						$l = array();
						foreach($q as $r) $l[$r->id] = $r; // general profile
						foreach($profil as $h) { // user profile
							$i = $h['i']; // more simple
							if(isset($l[$i])) {
								if($l[$i]->c_genre==='0' || $r->c_genre===':' || strpos($l[$i]->c_genre.'-',','.$u->i_sex.',')!==false) {
									if($l[$i]->i_type<3) $u->profil[$l[$i]->c_categ][$l[$i]->c_label] = $h['v'];
									else if($l[$i]->i_type==6) $u->profil[$l[$i]->c_categ][$l[$i]->c_label] = date_i18n(get_option('date_format'),strtotime($h['v']));
									else {
										$val = json_decode((empty($l[$i]->t_valeur)?'{}':$l[$i]->t_valeur));
										if($l[$i]->i_type==3) $u->profil[$l[$i]->c_categ][$l[$i]->c_label] = $val[$h['v']];
										elseif($l[$i]->i_type==4) {
											$tmp="";
											foreach($h['v'] as $pv) { $tmp.=$val[$pv].", "; }
											$u->profil[$l[$i]->c_categ][$l[$i]->c_label] = substr($tmp, 0, -2);
										}
										else if($l[$i]->i_type==5) $u->profil[$l[$i]->c_categ][$l[$i]->c_label] = ($val[0]+$h['v']*$val[2]) . ' '.$val[3];
									}
								}
							}
						}
					if($hideProf) foreach($u->profil as $key=>$value) $u->profil[$key] = array(' '=>strlen($hideProf)>5?$hideProf:'...');
					}
				}
				$photoWidth = 250;
				$size = rencPhotoSize();
				foreach($size as $s) if($s['label']==='-grande') $photoWidth = $s['width'];
				$buttonPlus = ''; if($mid!=$id && has_filter('rencMyPortraitPlusBtnP')) $buttonPlus = apply_filters('rencMyPortraitPlusBtnP', $u);
				$portraitPlus = ''; if(has_filter('rencMyPortraitPlusP')) $portraitPlus = apply_filters('rencMyPortraitPlusP', $u);
				$head = __('You have no photo on your profile?','rencontre');
				if($t=rencTranslate('nophText')) $info = $t;
				else $info = __('To be more visible and to view photos of other members, you should add one to your profile.','rencontre');
				$certified = ''; if(has_filter('rencCertifiedP')) $certified = apply_filters('rencCertifiedP', array($u->ID, 3, $u->t_action));
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_portrait_add_photo.php')) include($tpl);
				// ************************
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_portrait.php')) include($tpl);
				// ************************
			}
		}
		//
		// 3. Partie Changement du portrait
		else if(strstr($_SESSION['rencontre'],'edit') && (empty($rencOpt['fastreg']) || $rencOpt['fastreg']<2)) {
			// recuperation de la table profil
			$q = $wpdb->get_results("SELECT
					P.id,
					P.c_categ,
					P.c_label,
					P.t_valeur,
					P.i_type,
					P.c_genre
				FROM ".$wpdb->prefix."rencontre_profil P
				WHERE
					P.c_lang='".substr($rencDiv['lang2'],0,2)."'
					and P.c_categ!=''
					and P.c_label!=''
					and P.i_poids<5
				ORDER BY
					P.i_categ,
					P.i_label
				");
			//
			$infochange = false;
			if($Pa1=="sauvProfil") $infochange = __('Done','rencontre').' ';
			else if($rencDiv['pacam']) $infochange = __('You should complete your profile and add a photo to send messages.','rencontre').' ';
			//
			if($Pa1=="suppImg") self::suppImg($Pa2,$mid);
			else if($Pa1=="mainImg") self::mainImg($Pa2,$mid);
			else if($Pa1=="rotateLImg") rencRotateJpg($Pa2,90,$mid);
			else if($Pa1=="rotateRImg") rencRotateJpg($Pa2,270,$mid);
			else if($Pa1=="plusImg") self::plusImg($Pa2,$mid,$Protate);
			else if($Pa1=="suppImgAll") self::suppImgAll($mid);
			else if($Pa1=="sauvProfil") {
				$in = array();
				foreach($q as $r) {
					if($r->c_genre==='0' || $r->c_genre===':' || strpos($r->c_genre.'-',','.$rencU0->i_sex.',')!==false) { // Check Sex
						$in[$r->id][0] = $r->i_type;
						$in[$r->id][1] = $r->c_categ;
						$in[$r->id][2] = $r->c_label;
						$in[$r->id][3] = $r->t_valeur;
						$in[$r->id][4] = (($r->c_genre===':' || strpos($r->c_genre.'-',',:,')!==false) ? 1 : 0); // Mandatory
					}
				}
				self::sauvProfil($in,$mid);
				if(!empty($infochange)) $infochange .= "<script language='JavaScript'>document.location.href=window.location.href;</script>"; // refresh PACAM
			}
			//
			$u0 = clone $rencU0;
			$obj = array();
			foreach($q as $r) {
				if($r->c_genre==='0' || $r->c_genre===':' || strpos($r->c_genre.'-',','.$rencU0->i_sex.',')!==false) {
					$m = (($r->c_genre===':'||strpos($r->c_genre.'-',',:,')!==false)?1:0); // Mandatory Hidden field
					$a = new StdClass();
					$a->id = $r->id;
					$a->label = $r->c_label.($m?' *':'');
					$a->type = $r->i_type;
					$a->active = '';
					$a->mandatory = $m;
					if($r->i_type==3 || $r->i_type==4) $a->valeur = json_decode((empty($r->t_valeur)?'{}':$r->t_valeur));
					else if($r->i_type==5) {
						$b = json_decode((empty($r->t_valeur)?'{}':$r->t_valeur));
						$c = array();
						if(!empty($b[2]) && intval($b[2])!=0) for($v=$b[0]; $v<=$b[1]; $v+=intval($b[2])) $c[] = $v.' '.$b[3];
						$a->valeur = $c;
					}
					if($rencU0->profil) foreach($rencU0->profil as $v) {
						if($v['i']==$r->id) {
							if(is_array($v['v'])) { // type 4
								$a->active = ',';
								foreach($v['v'] as $v1) $a->active .= $v1.',';
							}
							else $a->active = $v['v'];
						}
					}
					$obj[$r->c_categ][] = $a;
				}
			}
			$u0->profil = $obj;
			if($u0->ID) {
				for($v=$u0->ID*10;$v<=$u0->i_photo;++$v) { // cleaning
					if(!file_exists($rencDiv['basedir'].'/portrait/'.floor($u0->ID/1000).'/'.Rencontre::f_img(($v).'-mini').'.jpg')) self::suppImg($v,$u0->ID);
				}
				$ho = false; if(has_filter('rencNbPhotoP')) $ho = apply_filters('rencNbPhotoP', $ho);
				$u0->maxPhoto = ($ho!==false?min($ho,(isset($rencOpt['imnb']))?$rencOpt['imnb']:4):((isset($rencOpt['imnb']))?$rencOpt['imnb']:4));
				// NO WEBP in PORTRAIT-EDIT
				$u0->photoUrl = $rencDiv['baseurl'].'/portrait/'.floor($u0->ID/1000).'/';
				$u0->photo = new StdClass();
				$u0->photo->full = array();
				$u0->photo->mini = array();
				$u0->photo->grande = array();
				$u0->photo->over = array();
				for($v=0;$v<(isset($rencOpt['imnb'])?$rencOpt['imnb']:4);++$v) {
					if(($u0->ID)*10+$v <= $u0->i_photo) {
						$a = Rencontre::f_img((($u0->ID)*10+$v).'-mini');
						// Browser Caching Bypass
						$b = 0;
						if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u0->ID/1000).'/'.$a.'.jpg')) $b = filemtime($rencDiv['basedir'].'/portrait/'.floor($u0->ID/1000).'/'.$a.'.jpg');
						$u0->photo->mini[$v] = $a.'.jpg?'.$b;
						// RETINA Mini
						$u0->photo->miniRetina[$v] = '';
						if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u0->ID/1000).'/'.$a.'@2x.jpg')) $u0->photo->miniRetina[$v] = $rencDiv['baseurl'].'/portrait/'.floor($u0->ID/1000).'/'.$a.'@2x.jpg?'.$b.' 2x';
						//
						$a = Rencontre::f_img((($u0->ID)*10+$v).'-grande');
						$u0->photo->grande[$v] = $a.'.jpg?'.$b;
						// RETINA Grande
						$u0->photo->grandeRetina[$v] = '';
						$u0->photo->grandeRet[$v] = 0; // not exists (JS)
						if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u0->ID/1000).'/'.$a.'@2x.jpg')) {
							$u0->photo->grandeRetina[$v] = $rencDiv['baseurl'].'/portrait/'.floor($u0->ID/1000).'/'.$a.'@2x.jpg?'.$b.' 2x';
							$u0->photo->grandeRet[$v] = 1; // exists (JS)
						}
						//
						$u0->photo->over[$v] = "f_vignetteRetina_change(".($u0->ID*10+$v).",'".$a."',".$u0->photo->grandeRet[$v].",'".$b."')";
						$u0->photo->full[$v] = Rencontre::f_img((($u0->ID)*10+$v)).'.jpg?'.$b; // Not used
					}
				}
				$onClick = array(
					"add"=>"document.getElementById('rencPhotoPop').style.display='block';document.getElementById('changePhoto').style.display='none';",
					"deleteAll"=>"f_suppAll_photo();return false;",
					"sauv"=>"f_sauv_profil(".$u0->ID.")"
					);
				if(!isset($rencOpt['imnb'])) $rencOpt['imnb'] = 4;
				for($v=0;$v<$rencOpt['imnb'];++$v) {
					$onClick['change'.$v] = "f_change_photo(".($u0->ID*10+$v).",".$v.")";
				}
				//
				$photoWidth = 250;
				$size = rencPhotoSize();
				foreach($size as $s) if($s['label']==='-grande') $photoWidth = $s['width'];
				$portraitPlus = ''; if(has_filter('rencMyPortraitPlusEditP')) $portraitPlus = apply_filters('rencMyPortraitPlusEditP', $u0);
				$head = __('Add a photo','rencontre');
				$info = '';
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_portrait_edit.php')) include($tpl);
				// ************************
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_portrait_add_photo.php')) include($tpl);
				// ************************
				if(!empty($rencOpt['accprof'])) { ?>
					
				<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
					<div class="rencCompte rencBox">
					<?php self::f_compte($mid); ?>
					</div><!-- .rencCompte .rencBox -->
				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
				<?php }
			}
		}
		//
		// 4. Partie Mon Accueil
		else {
			if(strstr($_SESSION['rencontre'],'accueil')) {
				if(empty($rencCustom['side'])) {
					$renc = new RencontreSidebarWidget;
					$renc->widget(0,$rencU0); // data send to limit sql request & remove clear end
				}
				$sex = $rencU0->i_sex;
				if($rencU0->i_zsex!=99) $zsex = $rencU0->i_zsex;
				else $zsex = '('.substr($rencU0->c_zsex,1,-1).')';
				$homo = (($rencU0->i_sex==$rencU0->i_zsex)?1:0); // seulement si genre sans custom
				if($rencU0->i_zage_min) {
					$zmin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-($rencU0->i_zage_min)));
					$zmin2 = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-($rencU0->i_zage_min)+10));
				}
				else { $zmin = 0; $zmin2 = 0; }
				if($rencU0->i_zage_max) {
					$zmax = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-($rencU0->i_zage_max)));
					$zmax2 = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-($rencU0->i_zage_max)-10));
				}
				else { $zmax = 0; $zmax2 = 0; }
				$mage = date_diff(date_create($rencU0->d_naissance), date_create('today'))->y;
			}
			//
			// 5. Partie mini portrait
			if(strstr($_SESSION['rencontre'],'mini')) { // mini toujours avec accueil
				$ho = false; if(has_filter('rencWssP')) $ho = apply_filters('rencWssP', $ho);
				if($ho) $mephoto = $wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$mid."' LIMIT 1");
				else $mephoto = 1;
				if(!isset($zsex)) { // test actuellement inutile car deja fait (voir plus haut)
					$sex = $rencU0->i_sex;
					if($rencU0>i_zsex!=99) $zsex = $rencU0->i_zsex;
					else $zsex = '('.substr($rencU0->c_zsex,1,-1).')';
					$homo = (($rencU0->i_sex==$rencU0->i_zsex)?1:0);
					if($rencU0->i_zage_min) {
						$zmin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$rencU0->i_zage_min));
						$zmin2 = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$rencU0->i_zage_min+10));
					}
					else { $zmin = 0; $zmin2 = 0; }
					if($rencU0->i_zage_max) {
						$zmax = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$rencU0->i_zage_max));
						$zmax2 = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$rencU0->i_zage_max-10));
					}
					else { $zmax = 0; $zmax2 = 0; }
				}
				// Selection par le sex


				$sexQuery = ''; // and R.i_zsex!=0 and R.i_sex=0
				// 1. SEX
				if(strpos($zsex.'-',')')!==false) $sexQuery .= " and R.i_sex IN ".$zsex; // allow multi choice in custom multiSR => () in zsex
				else $sexQuery .= " and R.i_sex=".$zsex;
				// 2. ZSEX - multiSR => c_zsex
				$sexQuery .= " and (R.c_zsex LIKE '%,".$sex.",%' or R.i_zsex=".$sex.") "; // multiSR or not (in case of change multiSR by ADMIN but not by users Account)
				// 3. ZRELATION
				$zrelat = '';
				if($rencU0->i_zrelation!=99) $zrelat = "and R.i_zrelation=".$rencU0->i_zrelation;
				else if(strlen($rencU0->c_zrelation)>1) {
					$a = explode(',', $rencU0->c_zrelation);
					foreach($a as $a1) {
						if(strlen($a1) && empty($zrelat)) $zrelat .= "and (R.c_zrelation LIKE '%,".$a1.",%' ";
						else if(strlen($a1)) $zrelat .= "or R.c_zrelation LIKE '%,".$a1.",%' ";
					}
					if(!empty($zrelat)) $zrelat .= ") ";
				}

				$myCountry = (!empty($rencOpt['myctry'])&&!empty($rencU0->c_pays)&&empty($rencCustom['country'])&&empty($rencCustom['place'])?"and R.c_pays='".$rencU0->c_pays."'":"");
				$qpause = (empty($rencOpt['paus'])?"and (P.t_action NOT REGEXP ',pause1,|,pause2,' or P.t_action IS NULL) ":"");
				?>
				
				<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
				<?php
				$size = rencPhotoSize();
				foreach($size as $si) if($si['label']==='-mini') $photoWidth = $si['width'];
				if(empty($photoWidth)) $photoWidth = 60;
				$feat = (!empty($rencOpt['nbr']['featured'])?$rencOpt['nbr']['featured']:8);
				$uFeatProf = $wpdb->get_results("SELECT DISTINCT(R.user_id) 
					FROM ".$wpdb->prefix."rencontre_users R
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=R.user_id
					WHERE 
						R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').") 
						".$sexQuery."
						".((!isset($rencCustom['born']) && $zmax && $zmin)?"
						and R.d_naissance>'".$zmax."' 
						and R.d_naissance<'".$zmin."'":" ")."
						".(!empty($mage)?"and R.i_zage_min<=".$mage:" ")."
						".(!empty($mage)?"and R.i_zage_max>=".$mage:" ")."
						".(!empty($rencOpt['nbr']['featuredDayOld'])?"and DATE(R.d_session)>DATE_SUB(CURDATE(),INTERVAL ".$rencOpt['nbr']['featuredDayOld']." DAY)":" ")."
						".$zrelat."
						".(!empty($rencOpt['onlyphoto'])?" and R.i_photo>0 ":" ")."
						and CHAR_LENGTH(P.t_titre)>4 
						and CHAR_LENGTH(P.t_annonce)>30 
						and R.user_id!=".$mid."
						".$myCountry."
						".$qpause."
					ORDER BY RAND() LIMIT ".$feat);
				// Not enough results 1 => less restrictive (no featuredDayOld) and merge
				if(count($uFeatProf)<$feat) {
					$a = $feat-count($uFeatProf);
					$b = '';
					foreach($uFeatProf as $a1) $b .= $a1->user_id . ',';
					if($b!='') $b = substr($b,0,-1);
					$uFeatProf1 = $wpdb->get_results("SELECT DISTINCT(R.user_id) 
						FROM ".$wpdb->prefix."rencontre_users R
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=R.user_id
						WHERE 
							R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
							".$sexQuery."
							".((!isset($rencCustom['born']) && $zmax && $zmin)?"
							and R.d_naissance>'".$zmax."' 
							and R.d_naissance<'".$zmin."'":" ")."
							".(!empty($mage)?"and R.i_zage_min<=".$mage:" ")."
							".(!empty($mage)?"and R.i_zage_max>=".$mage:" ")."
							".$zrelat."
							".(!empty($rencOpt['onlyphoto'])?" and R.i_photo>0 ":" ")."
							and CHAR_LENGTH(P.t_titre)>4 
							and CHAR_LENGTH(P.t_annonce)>30 
							and R.user_id!=".$mid."
							".(!empty($b)?"and R.user_id NOT IN (".$b.") ":"")."
							".$myCountry."
							".$qpause."
						ORDER BY RAND() LIMIT ".$a);
					$uFeatProf = array_merge($uFeatProf,$uFeatProf1);
				}
				if(empty($rencOpt['recexact']) && empty($rencU0->zstrict)) {
					// Not enough results 2 => less restrictive (age) but recent and merge
					if(count($uFeatProf)<$feat) {
						$a = $feat-count($uFeatProf);
						$b = '';
						foreach($uFeatProf as $a1) $b .= $a1->user_id . ',';
						if($b!='') $b = substr($b,0,-1);
						$uFeatProf1 = $wpdb->get_results("SELECT DISTINCT(R.user_id) 
							FROM ".$wpdb->prefix."rencontre_users R
							INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
								ON P.user_id=R.user_id
							WHERE 
								R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
								".$sexQuery."
								".((!isset($rencCustom['born']) && $zmax && $zmin)?"
								and R.d_naissance>'".$zmax."' 
								and R.d_naissance<'".$zmin."'":" ")."
								".(!empty($rencOpt['nbr']['featuredDayOld'])?"and DATE(R.d_session)>DATE_SUB(CURDATE(),INTERVAL ".$rencOpt['nbr']['featuredDayOld']." DAY)":" ")."
								".(!empty($rencOpt['onlyphoto'])?" and R.i_photo>0 ":" ")."
								and CHAR_LENGTH(P.t_titre)>4 
								and CHAR_LENGTH(P.t_annonce)>30 
								and R.user_id!=".$mid."
								".(!empty($b)?"and R.user_id NOT IN (".$b.") ":"")."
								".$myCountry."
								".$qpause."
							ORDER BY RAND() LIMIT ".$a);
						$uFeatProf = array_merge($uFeatProf,$uFeatProf1);
					}
					// Not enough results 3 => less restrictive (age and not recent) and merge
					if(count($uFeatProf)<$feat) {
						$a = $feat-count($uFeatProf);
						$b = '';
						foreach($uFeatProf as $a1) $b .= $a1->user_id . ',';
						if($b!='') $b = substr($b,0,-1);
						$uFeatProf1 = $wpdb->get_results("SELECT DISTINCT(R.user_id) 
							FROM ".$wpdb->prefix."rencontre_users R
							INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
								ON P.user_id=R.user_id
							WHERE 
								R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
								".$sexQuery."
								".((!isset($rencCustom['born']) && $zmax2 && $zmin2)?"
								and R.d_naissance>'".$zmax2."' 
								and R.d_naissance<'".$zmin2."'":" ")."
								".(!empty($rencOpt['onlyphoto'])?" and R.i_photo>0 ":" ")."
								and CHAR_LENGTH(P.t_titre)>4 
								and CHAR_LENGTH(P.t_annonce)>30 
								and R.user_id!=".$mid."
								".(!empty($b)?"and R.user_id NOT IN (".$b.") ":"")."
								".$myCountry."
								".$qpause."
							ORDER BY RAND() LIMIT ".$a);
						$uFeatProf = array_merge($uFeatProf,$uFeatProf1);
					}
				}
				//
				if(!empty($rencOpt['anniv']) && !isset($rencCustom['born'])) {
					$uBirthday = $wpdb->get_results("SELECT R.user_id 
						FROM ".$wpdb->prefix."rencontre_users R
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=R.user_id
						WHERE 
							R.d_naissance LIKE '%".current_time('m-d')."' 
							".$sexQuery."
							".((!isset($rencCustom['born']) && $zmax2 && $zmin2)?"and R.d_naissance>'".(!empty($rencOpt['recexact'])?$zmax:$zmax2)."' and R.d_naissance<'".(!empty($rencOpt['recexact'])?$zmin:$zmin2)."'":"")."
							and R.user_id!=".$mid."
							and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
							".$myCountry."
							".$qpause."
						ORDER BY RAND() LIMIT ".(!empty($rencOpt['nbr']['birthday'])?$rencOpt['nbr']['birthday']:4));
				}
				if(!empty($rencOpt['ligne'])) {
					$tab = ''; $d = $rencDiv['basedir'].'/session/';
					if($dh=opendir($d)) {
						while(($file = readdir($dh))!==false) {
							if($file!='.' && $file!='..' && filemtime($d.$file)>time()-70) $tab .= "'".basename($file, ".txt")."',";
						}
						closedir($dh);
					}
					$uLine = $wpdb->get_results("SELECT R.user_id 
						FROM ".$wpdb->prefix."rencontre_users R
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=R.user_id
						WHERE 
							R.user_id IN (".substr($tab,0,-1).") 
							".$sexQuery."
							".((!isset($rencCustom['born']) && $zmax2 && $zmin2)?"and R.d_naissance>'".(!empty($rencOpt['recexact'])?$zmax:$zmax2)."' and R.d_naissance<'".(!empty($rencOpt['recexact'])?$zmin:$zmin2)."'":"")."
							and R.user_id!=".$mid."
							and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
							".$myCountry."
							".$qpause."
						ORDER BY RAND() LIMIT ".(!empty($rencOpt['nbr']['online'])?$rencOpt['nbr']['online']:16)); // AND d_naissance>'".$zmax."' AND d_naissance<'".$zmin."'
						?>
						
					<form name="rencLine" method="get" action="">
						<?php if(!empty($rencOpt['page_id'])) { ?>
						
						<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
						<?php } ?>
						
						<input type="hidden" name="<?php echo $Lrenc; ?>" value="<?php echo $Lqsearch; ?>" />
						<input type="hidden" name="<?php echo $Lline; ?>" value="1" />
						<input type="hidden" name="<?php echo $Lzsex; ?>" value="<?php echo $zsex; ?>" />
						<input type="hidden" name="<?php echo $Lhomo; ?>" value="<?php echo $homo; ?>" />
					</form>
				<?php } ?>
				<?php $uNew = $wpdb->get_results("SELECT R.user_id 
					FROM ".$wpdb->prefix."rencontre_users R
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=R.user_id
					WHERE 
						R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
						".$sexQuery."
						".((!isset($rencCustom['born']) && $zmax2 && $zmin2)?"and R.d_naissance>'".(!empty($rencOpt['recexact'])?$zmax:$zmax2)."' and R.d_naissance<'".(!empty($rencOpt['recexact'])?$zmin:$zmin2)."'":"")."
						".(!empty($rencOpt['onlyphoto'])?" AND R.i_photo>0 ":" ")."
						and CHAR_LENGTH(P.t_titre)>4 
						and CHAR_LENGTH(P.t_annonce)>30 
						and R.user_id!=".$mid." 
						".$myCountry."
						".$qpause."
					ORDER BY R.user_id DESC LIMIT ".(!empty($rencOpt['nbr']['new'])?$rencOpt['nbr']['new']:12));
				$uFavori = array();
				if(isset($rencU0->action['favori'])) {
					$b = '';
					foreach($rencU0->action['favori'] as $r) $b .= $r['i'].',';
					if($b) $uFavori = $wpdb->get_results("SELECT R.user_id
						FROM ".$wpdb->prefix."rencontre_users R
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=R.user_id
						WHERE
							R.user_id IN (".substr($b,0,-1).")
							".$qpause." 
						");
				}
				$u0 = clone $rencU0;
				//
				$myHomeAddBox = false; if(has_filter('rencAddBox')) $myHomeAddBox = apply_filters('rencAddBox', $mid);
				//
				$warning = '';
				if(strstr($_SESSION['rencontre'],'pause1')) $warning = __('Profile hidden','rencontre');
				else if(strstr($_SESSION['rencontre'],'pause2')) $warning = __('Profile switched off','rencontre');
				if($warning) {
					$timeout = 3000;
					// ****** TEMPLATE ********
					if($tpl=rencTpl('rencontre_warning.php')) include($tpl);
					// ************************
				}
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_my_home.php')) include($tpl);
				// ************************
				?>

				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
			<?php }
			//
			// 6. Partie recherche rapide
			if(strstr($_SESSION['rencontre'],'qsearch')) {
				$WssP = false; if(has_filter('rencWssP')) $WssP = apply_filters('rencWssP', 0);
				$CertifiedP = ''; if(has_filter('rencCertifiedP')) $CertifiedP = base64_encode(apply_filters('rencCertifiedP', false));
				$QsearchP = false; $SendP = false; $SmileP = false;
				if(has_filter('rencLimitedActionP')) {
					if(is_numeric($Gid)) $QsearchP = apply_filters('rencLimitedActionP', array('qsearch',0,0));
					$SendP = apply_filters('rencLimitedActionP', array('sendmsg',0,0));
					if(!isset($rencCustom['smile'])) $SmileP = apply_filters('rencLimitedActionP', array('smile',0,0));
				}
				$var = array(
					'mid'=>$mid,
					'rencBlock'=>$rencDiv['rencBlock'],
					'pacam'=>$rencDiv['pacam'],
					'QsearchP'=>$QsearchP,
					'WssP'=>$WssP,
					'CertifiedP'=>$CertifiedP,
					'SendP'=>$SendP,
					'SmileP'=>$SmileP
				);
				self::f_quickFind(json_encode($var,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)); // cherche toujours avec accueil
			}
			//
			// 7. Partie recherche plus
			if(strstr($_SESSION['rencontre'],'gsearch')) {
				?> 
				
				<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
					<div id="rencTrouve" class="rencTrouve">
					<?php self::f_cherchePlus($mid); ?>
					
					</div><!-- #rencTrouve -->
				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
			<?php
			}
			//
			// 8. Messagerie - Boite de recepion (INBOX)
			if(strstr($_SESSION['rencontre'],'msg') && !$rencDiv['rencBlock'] && (empty($rencOpt['fastreg']) || $rencOpt['fastreg']<2)) { ?>
				
				<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
				<?php
				if($Pmsg=='msgdel' && !empty($Pid)) {
					$all = $wpdb->get_var("SELECT user_login FROM ".$wpdb->base_prefix."users WHERE ID=".$Pid." LIMIT 1");
					self::f_suppMsg($current_user->user_login,$all);
				}
				if(!empty($Pcontenu)) $warning = self::f_envoiMsg($current_user->user_login);
				$inbox = array();
				if((empty($rencDiv['mpause']) || $rencDiv['mpause']!='pause2') && empty($rencU0->photoreq)) {
					$qpause = (empty($rencOpt['paus'])?"and (P.t_action NOT LIKE '%,pause2,%' or P.t_action IS NULL) ":"");
					$out = $wpdb->get_results("SELECT 
							M.id,
							M.sender,
							M.recipient,
							M.date,
							M.read,
							U.ID as user_id,
							U.display_name,
							R.i_photo
						FROM ".$wpdb->base_prefix."users U
						INNER JOIN ".$wpdb->prefix."rencontre_users R
							ON R.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_msg M
							ON U.user_login=M.sender".(isset($rencOpt['collat'])?" COLLATE ".$rencOpt['collat']:"")."
						WHERE 
							M.recipient='".$current_user->user_login."'
							and M.deleted!=1
							".$qpause." "); // delete=1 : supp par dest
					$in = $wpdb->get_results("SELECT 
							M.id,
							M.sender,
							M.recipient,
							M.date,
							M.read,
							U.ID as user_id,
							U.display_name,
							R.i_photo
						FROM ".$wpdb->base_prefix."users U
						INNER JOIN ".$wpdb->prefix."rencontre_users R
							ON R.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_msg M
							ON U.user_login=M.recipient".(isset($rencOpt['collat'])?" COLLATE ".$rencOpt['collat']:"")."
						WHERE 
							M.sender='".$current_user->user_login."'
							and M.deleted!=2
							".$qpause." "); // delete=2 : supp par writter
					$q1 = array_merge($out,$in);
					usort($q1, array($this, 'message_sort'));
					$a = ',';
					foreach($q1 as $k=>$v) { // only one line by sender
						if($v->sender!=$current_user->user_login && strpos($a.'-',','.$v->sender.',')===false) {
							$a .= $v->sender.',';
							$m = (!empty($rencOpt['nbr']['lengthNameHome'])?intval($rencOpt['nbr']['lengthNameHome']):50);
							$q1[$k]->member = substr($v->display_name,0,$m); // $v->sender;
							$v->date = self::format_dateTime($v->date,1);
							$v->type = 'msgin';
							$b = '/portrait/'.floor(($v->user_id)/1000).'/'.Rencontre::f_img(($v->user_id*10).'-mini');
							// Browser Caching Bypass
							$c = 0;
							if(file_exists($rencDiv['basedir'].$b.'.jpg')) $c = filemtime($rencDiv['basedir'].$b.'.jpg');
							$v->miniPhoto = $rencDiv['baseurl'].$b.'.jpg?'.$c;
							// RETINA
							$v->miniPhotoRetina = '';
							if(file_exists($rencDiv['basedir'].$b.'@2x.jpg')) $v->miniPhotoRetina = $rencDiv['baseurl'].$b.'@2x.jpg?'.$c.' 2x';
							// WEBP
							$v->miniPhotoWebp = '';
							if(file_exists($rencDiv['basedir'].$b.'.webp')) {
								$v->miniPhotoWebp = $rencDiv['baseurl'].$b.'.webp?'.$c;
								if(file_exists($rencDiv['basedir'].$b.'@2x.webp')) $v->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$b.'@2x.webp?'.$c.' 2x';
							}
							$inbox[] = $v;
						}
						else if($v->recipient!=$current_user->user_login && strpos($a.'-',','.$v->recipient.',')===false) {
							$a .= $v->recipient.',';
							$m = (!empty($rencOpt['nbr']['lengthNameHome'])?intval($rencOpt['nbr']['lengthNameHome']):50);
							$q1[$k]->member = substr($v->display_name,0,$m); // $v->sender;
							$v->date = self::format_dateTime($v->date,1);
							$v->type = 'msgout';
							$b = '/portrait/'.floor(($v->user_id)/1000).'/'.Rencontre::f_img(($v->user_id*10).'-mini');
							// Browser Caching Bypass
							$c = 0;
							if(file_exists($rencDiv['basedir'].$b.'.jpg')) $c = filemtime($rencDiv['basedir'].$b.'.jpg');
							$v->miniPhoto = $rencDiv['baseurl'].$b.'.jpg?'.$c;
							// RETINA
							$v->miniPhotoRetina = '';
							if(file_exists($rencDiv['basedir'].$b.'@2x.jpg')) $v->miniPhotoRetina = $rencDiv['baseurl'].$b.'@2x.jpg?'.$c.' 2x';
							// WEBP
							$v->miniPhotoWebp = '';
							if(file_exists($rencDiv['basedir'].$b.'.webp')) {
								$v->miniPhotoWebp = $rencDiv['baseurl'].$b.'.webp?'.$c;
								if(file_exists($rencDiv['basedir'].$b.'@2x.webp')) $v->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$b.'@2x.webp?'.$c.' 2x';
							}
							$inbox[] = $v;
						}
					}
					$onClick = array(
						'look'=>"f_voir_msg(id,'".admin_url("admin-ajax.php")."','".$current_user->user_login."');", // AJAX
						'del'=>"document.forms['formEcrire'].elements['P".$Lrenc."'].value='".$Lmsg."';document.forms['formEcrire'].elements['".$Lmsg."'].value='msgdel';document.forms['formEcrire'].elements['P".$Lid."'].value=id;document.forms['formEcrire'].submit();"
						);
					$u0 = clone $rencU0;
				}
				else if(!empty($rencU0->photoreq)) $warning = __('You have no photo on your profile?','rencontre'); // no photo
				else $warning = __('Profile switched off','rencontre'); // pause2

				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_message_inbox.php')) include($tpl);
				// ************************
				?>
				
				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
			<?php
			}
			//
			// 9. Ecrire message
			if(strstr($_SESSION['rencontre'],'write') && !$rencDiv['rencBlock'] && (empty($rencOpt['fastreg']) || $rencOpt['fastreg']<2)) { ?>
				<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
				<?php
				if(!empty($rencidfm) && substr($rencidfm,1)!='0') $id = substr($rencidfm,1); // Instant email $hrefinbox : ?rencidfm=r0&rencoo=...&rencii=...
				else if($Pmsg!==false && !empty($Pid)) $id = $Pid;
				else if(!empty($Gid) && $Gid!=$mid) $id = $Gid;
				else $id = 0;
				if($id) {
					$u = $wpdb->get_row("SELECT
							U.user_login,
							U.display_name,
							R.i_photo,
							P.t_action
						FROM ".$wpdb->base_prefix."users U
						INNER JOIN ".$wpdb->prefix."rencontre_users R
							ON R.user_id=U.ID
						INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
							ON P.user_id=U.ID
						WHERE
							U.ID=".intval($id)."
						LIMIT 1
						");
					$u->user_id = $id;
					$a = '/portrait/'.floor(($u->i_photo)/10000).'/'.Rencontre::f_img((floor(($u->i_photo)/10)*10).'-mini');
					// Browser Caching Bypass
					$b = 0;
					if(file_exists($rencDiv['basedir'].$a.'.jpg')) $b = filemtime($rencDiv['basedir'].$a.'.jpg');
					$u->miniPhoto = $rencDiv['baseurl'].$a.'.jpg?'.$b;
					$u->photo = $u->miniPhoto; // name before V3.6.2
					// RETINA
					$u->miniPhotoRetina = '';
					if(file_exists($rencDiv['basedir'].$a.'@2x.jpg')) $u->miniPhotoRetina = $rencDiv['baseurl'].$a.'@2x.jpg?'.$b.' 2x';
					// WEBP
					$u->miniPhotoWebp = '';
					if(file_exists($rencDiv['basedir'].$a.'.webp')) {
						$u->miniPhotoWebp = $rencDiv['baseurl'].$a.'.webp?'.$b;
						if(file_exists($rencDiv['basedir'].$a.'@2x.webp')) $u->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$a.'@2x.webp?'.$b.' 2x';
					}
					//
					$m = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
					$u->display_name = substr($u->display_name,0,$m);
					$noProfile = false; if(has_filter('rencLimitedActionP')) $noProfile = apply_filters('rencLimitedActionP', array('profil',0,$id));
					$onClick = array(
						'profile'=>($noProfile?"":"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lcard."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($id,0)."';document.forms['rencMenu'].submit();"),
						'send'=>"document.forms['formEcrire'].elements['P".$Lrenc."'].value='".$Lmsg."';document.forms['formEcrire'].elements['P".$Lid."'].value='".$id."';document.forms['formEcrire'].submit();",
						'inbox'=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lmsg."';document.forms['rencMenu'].submit();"
						);
					if(empty($rencOpt['paus']) && strpos($u->t_action.'-', ',pause1,')!==false || strpos($u->t_action.'-', ',pause2,')!==false) $onClick['profile'] = '';
					$u0 = clone $rencU0;
					// ****** TEMPLATE ********
					if($tpl=rencTpl('rencontre_message_write.php')) include($tpl);
					// ************************
				}
				?>
				
				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
			<?php
			}
			//
			// 10. Compte
			if(strstr($_SESSION['rencontre'],'account')) { ?> 
				
				<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
					<div class="rencCompte rencBox">
					<?php self::f_compte($mid); ?>
					</div><!-- .rencCompte .rencBox -->
				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
			<?php
			} 
			//
			// 11. Custom Page
			else if(strstr($_SESSION['rencontre'],'custom1')) {
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_custom_page1.php',1)) include($tpl); // 1 : Not in the plugin template DIR (example)
				// ************************
			}
			else if(strstr($_SESSION['rencontre'],'custom2')) {
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_custom_page2.php',1)) include($tpl); // 1 : Not in the plugin template DIR (example)
				// ************************
			}
			else {
				$ho = false; if(has_filter('rencPageContent')) $ho = apply_filters('rencPageContent', $_SESSION['rencontre']);
				if($ho) echo $ho;
			}
		}
		//
		// OTHER
		if(!$rencDiv['titann'] && !isset($_COOKIE["titann"]) && !current_user_can("administrator") && isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) {
			$ho = false; if(has_filter('rencAdwC') && $rencOpt['fastreg']>2) $ho = apply_filters('rencAdwC', $ho); if($ho) echo $ho;
		}
		// ****** TEMPLATE ********
		if($tpl=rencTpl('rencontre_modal_warning.php')) include($tpl);
		// ************************
			?>
			
			<div style="clear:both;">&nbsp;</div>
			<div id="rencAdsB" class="rencAds w3-margin-bottom">
			<?php $ho = false; if(has_filter('rencAdsBP')) $ho = apply_filters('rencAdsBP', $ho); if($ho) echo $ho; ?>
			</div><!-- .rencAds -->
		</div><!-- #widgRenc -->
		<?php
	}
//
// *************** FUNCTION ********************
//
	function message_sort($a,$b) { // USORT display message list
		return strcmp($b->date,$a->date);
	}
	//
	static function suppImg($im,$id) {
		// entree : nom de la photo (id * 10 + 1 ou 2 ou 3...)
		$size = rencPhotoSize(1); // 1 : P added in list
		global $rencDiv,$rencU0;
		$r = $rencDiv['basedir'].'/portrait/'.floor($id/1000).'/';
		$typ = array('.jpg', '.webp', '@2x.jpg', '@2x.webp');
		//
		$a = array(); 
		foreach($size as $s) {
			foreach($typ as $v) $a[] = Rencontre::f_img($im.$s['label']) . $v;
		}
		foreach($a as $b) if(file_exists($r.$b)) unlink($r.$b);
		//
		global $wpdb;
		$q = $wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$id."' LIMIT 1");
		if(!$q || floor($q/10)*10==$q) $p = 0; // plus de photo
		else $p = $q-1;
		$wpdb->update($wpdb->prefix.'rencontre_users', array('i_photo'=>$p), array('user_id'=>$id));
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('d_modif'=>current_time("mysql")), array('user_id'=>$id));
		if(is_object($rencU0)) $rencU0->i_photo = $p; // ADMIN can delete (admin has no rencU0)
		$c = 0;
		for($v=$im; $v<$q; ++$v) {
			foreach($size as $s) {
				foreach($typ as $w) if(file_exists($r.Rencontre::f_img(($v+1).$s['label']).$w)) rename($r.Rencontre::f_img(($v+1).$s['label']).$w, $r.Rencontre::f_img($v.$s['label']).$w);
			}
		}
		//
		if(file_exists($rencDiv['basedir'].'/portrait/libre/libreIDs.json')) {
			$j = file_get_contents($rencDiv['basedir'].'/portrait/libre/libreIDs.json');
			$a = json_decode((empty($j)?'{}':$j));
			if(is_array($a) && $im==$id*10 && in_array($id,$a)) self::suppImgLib($id);
		}
	}
	//
	static function mainImg($im,$id) {
		// entree : nom de la photo (id * 10 + 1 ou 2 ou 3...) crypte
		$size = rencPhotoSize(1); // 1 : P added in list
		$pos = $im - intval($id.'0');
		global $rencDiv;
		$r = $rencDiv['basedir'].'/portrait/'.floor($id/1000).'/';
		$typ = array('.jpg', '.webp', '@2x.jpg', '@2x.webp');
		// 1. Future Main TMP name
		$a = array();
		foreach($size as $s) {
			foreach($typ as $v) $a[] = array(Rencontre::f_img($im.$s['label']).$v, $s['label'], $v);
		}
		foreach($a as $b) {
			foreach($typ as $v) if(file_exists($r.$b[0])) rename($r.$b[0], $r.$id.'main00'.$b[1].$b[2]); // $im => tmp name
		}
		// 2. Current Main
		$a = array();
		foreach($size as $s) {
			foreach($typ as $v) $a[] = array(Rencontre::f_img($id.'0'.$s['label']).$v, $s['label'], $v);
		}
		foreach($a as $b) {
			foreach($typ as $v) if(file_exists($r.$b[0])) rename($r.$b[0], $r.Rencontre::f_img($id.$pos.$b[1]).$b[2]); // main => $im
		}
		// 3. Future Main 
		$a = array();
		foreach($size as $s) {
			foreach($typ as $v) $a[] = array($id.'main00'.$s['label'].$v, $s['label'], $v);
		}
		foreach($a as $b) {
			foreach($typ as $v) if(file_exists($r.$b[0])) rename($r.$b[0], $r.Rencontre::f_img($id.'0'.$b[1]).$b[2]); // tmp name => main
		}
		//
		if(file_exists($rencDiv['basedir'].'/portrait/libre/libreIDs.json')) {
			$j = file_get_contents($rencDiv['basedir'].'/portrait/libre/libreIDs.json');
			$a = json_decode((empty($j)?'{}':$j));
			if(is_array($a) && in_array($id,$a)) self::suppImgLib($id);
		}
	}
	//
	static function plusImg($nim,$id,$rot=0) {
		// entree : $s->i_photo (id * 10 + nombre de photo), $mid, $_POST["rotate"]
		global $rencDiv,$rencU0;
		$r = $rencDiv['basedir'].'/tmp/';
		$e = '';
		if(!is_dir($r)) mkdir($r);
		if(strpos($nim.'-','|')===false) {
			if(isset($_FILES['plusPhoto'])) {
				if($_FILES['plusPhoto']['error']) {
					switch($_FILES['plusPhoto']['error']) {
						case UPLOAD_ERR_INI_SIZE:
						case UPLOAD_ERR_FORM_SIZE:
							$e = "The uploaded file exceeds the max upload size.";
							break;
						case UPLOAD_ERR_PARTIAL:
							$e = "The uploaded file was only partially uploaded.";
							break;
						case UPLOAD_ERR_NO_FILE:
							$e = "No file was uploaded.";
							break;
						case UPLOAD_ERR_NO_TMP_DIR:
							$e = "Missing a temporary folder.";
							break;
						case UPLOAD_ERR_CANT_WRITE:
							$e = "Failed to write file to disk.";
							break;
						case UPLOAD_ERR_EXTENSION:
							$e = "File upload stopped by extension.";
							break;
						default:
							$e = "File upload failed due to unknown error.";
					}
				}
				else if(!$_FILES['plusPhoto']['size']) $e = "The file was not uploaded (empty size).";
				if(empty($nim)) $p = $id * 10; // first img
				else $p = intval($nim) + 1;
				$cible = $r . basename($_FILES['plusPhoto']['tmp_name']);
				if(move_uploaded_file($_FILES['plusPhoto']['tmp_name'], $cible)) {
					self::f_photo($p,$cible,$rot);
					global $wpdb;
					$wpdb->update($wpdb->prefix.'rencontre_users', array('i_photo'=>$p), array('user_id'=>$id));
					if(is_object($rencU0)) $rencU0->i_photo = $p; // ADMIN can add (admin has no rencU0)
					$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('d_modif'=>current_time("mysql")), array('user_id'=>$id));
					if(file_exists($cible)) unlink($cible);
				}
				else $e = "Cannot move uploaded file to ".$cible;
			}
			else $e = "File was not uploaded.";
		}
		//
		if($nim==0) renc_clear_cache_portrait(); // Not in libreIDs.json because no photo
		if($e) {
			$warning = $e;
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_warning.php')) include($tpl);
			// ************************
		}
	}
	//
	static function suppImgAll($id,$upd=true) {
		// entree : id
		global $rencDiv,$rencU0;
		$size = rencPhotoSize(1); // 1 : P added in list
		$r = $rencDiv['basedir'].'/portrait/'.floor($id/1000).'/';
		$typ = array('.jpg', '.webp', '@2x.jpg', '@2x.webp');
		for($v=0;$v<=9;++$v) {
			$a = array();
			foreach($size as $s) {
				foreach($typ as $w) $a[] = Rencontre::f_img($id.$v.$s['label']) . $w;
			}
			foreach($a as $b) if(file_exists($r.$b)) unlink($r.$b);
		}
		global $wpdb;
		if($upd) {
			$wpdb->update($wpdb->prefix.'rencontre_users', array('i_photo'=>0), array('user_id'=>$id));
			if(is_object($rencU0)) $rencU0->i_photo = 0; // ADMIN can delete (admin has no rencU0)
		}
		//
		if(file_exists($rencDiv['basedir'].'/portrait/libre/libreIDs.json')) {
			$j = file_get_contents($rencDiv['basedir'].'/portrait/libre/libreIDs.json');
			$a = json_decode((empty($j)?'{}':$j));
			if(is_array($a) && in_array($id,$a)) self::suppImgLib($id);
		}
	}
	//
	static function suppImgLib($id) {
		global $rencDiv;
		$typ = array('.jpg', '.webp', '@2x.jpg', '@2x.webp');
		$photo = Rencontre::f_img(($id*10).'-libre',2); // ($id*10).'-libre';
		$b = 0;
		foreach($typ as $v) if(file_exists($rencDiv['basedir'].'/portrait/libre/'.$photo . $v)) {
			$b = 1;
			unlink($rencDiv['basedir'].'/portrait/libre/'.$photo . $v);
		}
		if($b) renc_clear_cache_portrait();
	}
	//
	static function sauvProfil($in,$id) {
		// entree : Sauvegarde du profil
		// sortie bdd : [{"i":10,"v":"Sur une ile deserte avec mon amoureux."},{"i":35,"v":0},{"i":53,"v":[0,4,6]}]
		$post = (!empty($_POST)?$_POST:array());
		if(has_filter('rencUserPost')) $post = apply_filters('rencUserPost', $post, 'sauvProfil');
		$u = ''; $mandatory = 0;
		if($in) foreach($in as $k=>$v) {
			if(!empty($v[4])) $mandatory = 1; // in4 = 1 <=> mandatory && same sex
			switch($v[0]) {
				case 1:
					if(!empty($post['text'.$k])) {
						$a = rencSanit($post['text'.$k],'text');
						$a = str_replace('\"','',$a);
						$a = str_replace("\'","'",$a);
						$u .= '{"i":'.$k.',"v":"'.$a.'"},';
					}
				break;
				case 2:
					if(!empty($post['area'.$k])) {
						$a = rencSanit($post['area'.$k],'para');
						$a = preg_replace('/\r|\n/','\n',trim($a));
						$a = str_replace('\"','',$a);
						$a = str_replace("\'","'",$a);
						$u .= '{"i":'.$k.',"v":"'.$a.'"},';
					}
				break;
				case 3:
					if(!empty($post['select'.$k])) $u .= '{"i":'.$k.',"v":'.(intval(rencSanit($post['select'.$k],'int'))-1).'},';
				break;
				case 4:
					if(!empty($post['check'.$k])) {
						$u .= '{"i":'.$k.',"v":[';
						foreach($post['check'.$k] as $r) $u .= rencSanit($r,'int').',';
						$u = substr($u, 0, -1).']},';
					}
				break;
				case 5:
					if(!empty($post['ns'.$k])) $u .= '{"i":'.$k.',"v":'.(intval(rencSanit($post['ns'.$k],'int'))-1).'},';
				break;
				case 6:
					if(!empty($post['date'.$k])) $u .= '{"i":'.$k.',"v":"'.rencSanit($post['date'.$k],'date').'"},';
				break;
			}
		}
		global $wpdb, $rencDiv;
		$Ptitre = rencSanit($post['titre'],'text');
		$Pannonce = rencSanit($post['annonce'],'para');
		if(strlen($Ptitre)<2 && strlen($Pannonce)>10) {
			$tit = substr($Pannonce,0,50);
			$tit = trim(preg_replace('/\s\s+/', ' ', $tit));
			preg_match('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $tit, $m);
			$m[0] = (isset($m[0])?$m[0]:'');
			$tit = str_replace(array($m[0]), array(''), $tit);
			$tit = str_replace(', ', ',', $tit); $tit = str_replace(',', ', ', $tit);
			$tit = strtr($tit, "0123456789#(){[]}", ".................");
			$tit = substr($tit,0,25).'...';
			$Ptitre = $tit;
		}
		$wpdb->update($wpdb->prefix.'rencontre_users_profil',
			array('d_modif'=>current_time("mysql"),
				't_titre'=>$Ptitre,
				't_annonce'=>$Pannonce,
				't_profil'=>'['.substr($u, 0, -1).']'),
			array('user_id'=>$id));
		//
		if(file_exists($rencDiv['basedir'].'/portrait/libre/libreIDs.json')) {
			$j = file_get_contents($rencDiv['basedir'].'/portrait/libre/libreIDs.json');
			$a = json_decode((empty($j)?'{}':$j));
			if(is_array($a) && in_array($id,$a)) renc_clear_cache_portrait();
		}
		// Check Mandatory field empty
		$st = $wpdb->get_var("SELECT i_status FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$id."' LIMIT 1");
		if($mandatory) {
			foreach($in as $k=>$v) if(!empty($v[4]) && strpos($u.'-', '{"i":'.$k.',"v":')===false) $mandatory++;
			$m = rencistatus($st,3); // 3=>mandatory profil field empty
			if($mandatory>1 && !$m) {
				$rencDiv['istatus'] = rencistatusSet($st,3,1);
				$wpdb->update($wpdb->prefix.'rencontre_users', array('i_status'=>$rencDiv['istatus']), array('user_id'=>$id));
			}
			else if($mandatory==1 && $m) {
				$rencDiv['istatus'] = rencistatusSet($st,3,0);
				$wpdb->update($wpdb->prefix.'rencontre_users', array('i_status'=>$rencDiv['istatus']), array('user_id'=>$id));
			}
		}
		else if(rencistatus($st,3)) $wpdb->update($wpdb->prefix.'rencontre_users', array('i_status'=>rencistatusSet($st,3,0)), array('user_id'=>$id)); // Cleaning
	}
	//
	static function f_photo($im,$rim,$rot=0,$regen=0) {
		// im : user_id *10 + numero de photo a partir de 0
		// rim : Image Path
		if(!file_exists($rim)) return;
		global $rencOpt, $rencDiv;
		$size = rencPhotoSize();
		$fullSize = array(1280,960); // Default
		foreach($size as $s) if($s['label']==='') $fullSize = array($s['width'],$s['height']);
		if(has_filter('rencImgFullSize')) $fullSize = apply_filters('rencImgFullSize', $fullSize);
		$r = $rencDiv['basedir'].'/portrait/'.floor($im/10000);
		if($rot) $rot = floor((((floatval($rot)+45)/360)-floor((floatval($rot)+45)/360))*4)*(-90); // NEGAT and only 0, 90, 180, 270
		else $rot = 0; // false !== 0
		if(!is_dir($r)) mkdir($r);
		$quality = (!empty($rencOpt['nbr']['imgQuality'])?intval($rencOpt['nbr']['imgQuality']):75); // Purcent
		$a = exif_imagetype($rim);
		$sim = getimagesize($rim);
		if(!empty($a) && !empty($sim[2]) && ($a==IMAGETYPE_JPEG || $a==IMAGETYPE_PNG) && ($sim[2]==2 || $sim[2]==3)) {
			if($sim[2]==2) $in = imagecreatefromjpeg($rim); // jpg
			else if($sim[2]==3) $in = imagecreatefrompng($rim); // png
			if(!$regen) {
				if($rot) $in = imagerotate($in, $rot, 0);
				if(abs($rot)==90 || abs($rot)==270) { // permut H & V
					$a = $sim[1];
					$sim[1] = $sim[0];
					$sim[0] = $a;
				}
				$imco = (!empty($rencOpt['imcopyright'])?$rencOpt['imcopyright']:0);
				$txco = (!empty($rencOpt['txtcopyright'])?$rencOpt['txtcopyright']:'');
				$out = array();
				//
				$outW = $sim[0];
				$outH = $sim[1];
				if($sim[1]/$sim[0]>($fullSize[1]/$fullSize[0])) { // more verticale $fullSize[1] / $fullSize[0]  -  V2 : 0.75=480/640
					if($sim[1]>$fullSize[1]) {
						$outW = ($sim[0]/$sim[1]*$fullSize[1]);
						$outH = $fullSize[1];
					}
				}
				else if($sim[0]>$fullSize[0]) { // more horizontale
					$outW = $fullSize[0];
					$outH = ($sim[1]/$sim[0]*$fullSize[0]);
				}
				$outW = intval($outW);
				$outH = intval($outH);
				$out[0] = imagecreatetruecolor($outW, $outH); // max : 1280x960
				// imagecopyresampled(sortie, entree, position sur sortie X, Y, position entree X, Y, larg haut sur sortie, larg haut sur entree)
				imagecopyresampled($out[0], $in, 0, 0, 0, 0, $outW, $outH, $sim[0], $sim[1]);
				imagejpeg(self::f_imcopyright($out[0],$imco,$txco), $r."/".Rencontre::f_img($im).".jpg", $quality);
				// RETINA @2x
				if(!empty($rencOpt['nbr']['retina'])) {
					$outret = imagecreatetruecolor(($outW*2), ($outH*2)); // max : 1280x960 x 2
					imagecopyresampled($outret, $in, 0, 0, 0, 0, ($outW*2), ($outH*2), $sim[0], $sim[1]);
					imagejpeg(self::f_imcopyright($outret,$imco,$txco), $r."/".Rencontre::f_img($im)."@2x.jpg", $quality);
				}
			}
			else {
				$out[0] = imagecreatetruecolor($sim[0], $sim[1]);
				imagecopyresampled($out[0], $in, 0, 0, 0, 0, $sim[0], $sim[1], $sim[0], $sim[1]);
				// We do not create RETINA @2x if not exists previously
			}
			foreach($size as $k=>$v) {
				if(empty($v['label'])) continue; // Fullsize already done
				$out[$k+1] = imagecreatetruecolor($v['width'], $v['height']);
				if(($v['height']/$v['width'])>($sim[1]/$sim[0])) { // new more verticale
					$inW = intval($v['width']/$v['height']*$sim[1]);
					$inH = $sim[1];
					$inX = intval(($sim[0]-$inW)/2);
					$inY = 0;
				}
				else { // new more horizontale
					$inW = $sim[0];
					$inH = intval($v['height']/$v['width']*$sim[0]);
					$inX = 0;
					$inY = intval(max(0, ($sim[1]*3/8)-($inH/2) ));
				}
				imagecopyresampled($out[$k+1], $in, 0, 0, $inX, $inY, $v['width'], $v['height'], $inW, $inH); 
				if(!$regen) imagejpeg(self::f_imcopyright($out[$k+1],$imco,$txco), $r."/".Rencontre::f_img($im.$v['label']).".jpg", (!empty($v['quality'])?$v['quality']:$quality));
				else imagejpeg($out[$k+1], $r."/".Rencontre::f_img($im.$v['label']).".jpg", (!empty($v['quality'])?$v['quality']:$quality));
				// WEBP
				if($v['label']=='-mini' && function_exists('imagewebp')) { // V3.6.2 - currently only mini
					imagewebp((!$regen?self::f_imcopyright($out[$k+1],$imco,$txco):$out[$k+1]), $r."/".Rencontre::f_img($im.$v['label']).".webp", (!empty($v['quality'])?$v['quality']:$quality));
				}
				// RETINA
				if(!empty($rencOpt['nbr']['retina'])) {
					$outret = imagecreatetruecolor (($v['width']*2), ($v['height']*2));
					imagecopyresampled($outret, $in, 0, 0, $inX, $inY, ($v['width']*2), ($v['height']*2), $inW, $inH);
					imagejpeg((!$regen?self::f_imcopyright($outret,$imco,$txco):$outret), $r."/".Rencontre::f_img($im.$v['label'])."@2x.jpg", (!empty($v['quality'])?$v['quality']:$quality));
					if($v['label']=='-mini' && function_exists('imagewebp')) {
						imagewebp((!$regen?self::f_imcopyright($outret,$imco,$txco):$outret), $r."/".Rencontre::f_img($im.$v['label'])."@2x.webp", (!empty($v['quality'])?$v['quality']:$quality));
					}
				}
			}
			if(has_filter('rencBlurCreaP')) {
				$ho = new StdClass();
				$ho->im = $im;
				$ho->out = $out;
				$ho->size = $size;
				// old premium version
				if(!function_exists('get_plugin_data')) require_once ABSPATH.'wp-admin/includes/plugin.php';
				$plug = get_plugin_data(WP_PLUGIN_DIR.'/rencontreP/rencontreP.php');
				if(floatval($plug['Version'])<2.7) unset($ho);
				//
				if(isset($ho)) apply_filters('rencBlurCreaP', $ho);
			}
			imagedestroy($in);
			foreach($out as $v) imagedestroy($v);
			if(!empty($outret)) imagedestroy($outret);
		}
		if(has_filter('rencModerP')) apply_filters('rencModerP', $im);
	}
	//
	static function f_imcopyright($imc,$right,$txtc) {
		if(!function_exists('imagettfbbox') || !function_exists('imagettftext')) return $imc; // This function is only available if PHP is compiled with freetype support
		if($right) {
			$sx = imagesx($imc);
			$sy = imagesy($imc);
			if($txtc=="") $Text = site_url();
			else $Text = $txtc;
			$a = DIRECTORY_SEPARATOR;
			if(empty($a)) $a = '/';
			$Font = dirname(__FILE__).$a."arial.ttf";
			$FontColor = imagecolorallocate($imc,255,255,255);
			$FontShadow = imagecolorallocate($imc,0,0,0);
			if($right=="2") $Rotation = -30;
			else $Rotation = 30;
			/* Make a copy image */
			$OriginalImage = imagecreatetruecolor($sx,$sy);
			imagecopy($OriginalImage,$imc,0,0,0,0,$sx,$sy);
			/* Iterate to get the size up */
			$FontSize=1;
			do {
				$FontSize *= 1.1;
				$Box = @imagettfbbox($FontSize,0,$Font,$Text);
				$TextWidth = abs($Box[4] - $Box[0]);
				$TextHeight = abs($Box[5] - $Box[1]);
			}
			while($TextWidth < $sx*0.75);
			/*  Awkward maths to get the origin of the text in the right place */
			$x = intval($sx/2 - cos(deg2rad($Rotation))*$TextWidth/2);
			$y = intval($sy/2 + sin(deg2rad($Rotation))*$TextWidth/2 + cos(deg2rad($Rotation))*$TextHeight/2);
			/* Make shadow text first followed by solid text */
			imagettftext($imc,$FontSize,$Rotation,$x+4,$y+4,$FontShadow,$Font,$Text);
			imagettftext($imc,$FontSize,$Rotation,$x,$y,$FontColor,$Font,$Text);
			/* merge original image into version with text to show image through text */
			imagecopymerge($imc,$OriginalImage,0,0,0,0,$sx,$sy,85);
		}
		return $imc;
	}
	//
	static function f_pays($f='FR',$indif=0) {
		if(!$indif) echo '<option value=""'.($f=='i'?' selected':'').'>- '.__('No matter','rencontre').' -</option>';
		if(strlen($f)!=2 && $f!='i') $f = 'FR';
		global $wpdb, $rencDiv;
		$q = $wpdb->get_results("SELECT
				c_liste_valeur,
				c_liste_iso
			FROM ".$wpdb->prefix."rencontre_liste
			WHERE
				c_liste_categ='p' and
				c_liste_lang='".substr($rencDiv['lang3'],0,2)."'
			ORDER BY c_liste_valeur
			");
		foreach($q as $r) {
			echo '<option value="'.$r->c_liste_iso.'"'.(($r->c_liste_iso==$f)?' selected':'').'>'.str_replace("''","'",$r->c_liste_valeur).'</option>';
		}
	}
	//
	static function f_regionBDD($f=1,$g=0) {
		// Initial fields (Ajax update in rencontre_filter.php)
		global $rencOpt, $rencCustom, $wpdb;
		if(!empty($rencCustom['country']) || !$g) $g = (!empty($rencOpt['pays'])?$rencOpt['pays']:'FR'); // default : FR
		$b = 0;
		if(!$f || substr($f,0,1)=='_') echo '<option value="">- '.__('No matter','rencontre').' -</option>';
		if($g!='i') { // Country No matter => NO Region
			if(substr($f,0,1)=='_') $f = substr($f,1);
			$q = $wpdb->get_results("SELECT
					id,
					c_liste_valeur
				FROM ".$wpdb->prefix."rencontre_liste
				WHERE
					c_liste_iso='".$g."' and
					c_liste_categ='r'
				ORDER BY c_liste_valeur
				");
			foreach($q as $r) {
				echo '<option value="'.$r->id.'"'.(($f && ($r->c_liste_valeur==$f || $r->id==$f))?' selected':'').'>'.$r->c_liste_valeur.'</option>';
			}
		}
	}
	//
	static function f_zstrict($u) {
		global $rencU0;
		$b = 0;
		// 1. sex U0 -> zsex U
		if($rencU0->zstrict) {
			if($rencU0->i_zsex!=99 && $rencU0->i_zsex!=$u->i_sex) $b = 'sex';
			else if($rencU0->i_zsex==99 && strpos($rencU0->c_zsex, ','.$u->i_sex.',')===false) $b = 'sex99';
		}
		// 2. zsex U0 -> sex U
		if($u->i_zsex!=99 && $u->i_zsex!=$rencU0->i_sex) $b = 'zsex';
		else if($u->i_zsex==99 && strpos($u->c_zsex, ','.$rencU0->i_sex.',')===false) $b = 'zsex99';
		// 3. zrelation
		if($rencU0->i_zrelation!=99 && $u->i_zrelation!=99 && $rencU0->i_zrelation!=$u->i_zrelation) $b = 'zrel';
		else if($rencU0->i_zrelation==99 && $u->i_zrelation==99) {
			$zr0 = array_filter(explode(',', str_replace(',0,',',Z,',$rencU0->c_zrelation))); // '0' is empty => replace by 'Z'
			$zr = array_filter(explode(',', str_replace(',0,',',Z,',$u->c_zrelation)));
			if(empty(array_intersect($zr0,$zr))) $b = 'zrel99';
		}
		// Mixed 99 / not 99 not treated
		// 4. age
		if(!isset($u->age)) $u->age = Rencontre::f_age($u->d_naissance);
		if($rencU0->zstrict) {
			if($u->age < $rencU0->i_zage_min || $u->age > $rencU0->i_zage_max) $b = 'ageU';
		}
		if($rencU0->age < $u->i_zage_min || $rencU0->age > $u->i_zage_max) $b = 'ageU0';
		//
		return $b;
	}
	//
	static function f_miniPortrait($user_id, $noPhoto=0, $photoWidth=250) {
		// entree : user_id
		// sortie : code HTML avec le mini portrait
		global $wpdb, $rencDrap, $rencDrapNom, $rencDiv, $rencCustom, $rencOpt, $rencU0;
		$noProfile = false; if(has_filter('rencLimitedActionP')) $noProfile = apply_filters('rencLimitedActionP', array('profil',0,$user_id));
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
		$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
		$Lcard = (!empty($rencOpt['lbl']['card'])?$rencOpt['lbl']['card']:'card');
		$u0 = clone $rencU0;
		//
		$highlight = false; if(has_filter('rencHighlightP')) $highlight = apply_filters('rencHighlightP', $user_id);
		$u = $wpdb->get_row("SELECT
				U.ID,
				U.display_name,
				R.c_pays,
				R.c_ville,
				R.d_naissance,
				R.i_photo,
				P.t_titre,
				P.t_action
			FROM ".$wpdb->base_prefix."users U
			INNER JOIN ".$wpdb->prefix."rencontre_users R
				ON R.user_id=U.ID
			INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
				ON P.user_id=U.ID
			WHERE 
				R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
				and U.ID=".$user_id."
			LIMIT 1
			");
		if($u!=false) {
			$onClick = array(
				"profile"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lcard."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($user_id,0)."';document.forms['rencMenu'].submit();"
				);
			$title = array(
				"title"=>"",
				"thumb"=>""
				);
			if(!empty($u0->photoreq)) {
				$onClick['profile'] = "";
				$title['profile'] = __('You have no photo on your profile?','rencontre');
			}
			if(!empty($rencDiv['mpause'])) {
				$onClick['profile'] = "";
				$title['profile'] = ($rencDiv['mpause']=='pause1'?__('Profile hidden','rencontre'):__('Profile switched off','rencontre'));
			}
			else if($noProfile) {
				$onClick['profile'] = "";
				$title['profile'] = $noProfile;
			}
			if($noPhoto) {
				if($t=rencTranslate('nophText')) $title['thumb'] = $t;
				else $title['thumb'] = addslashes(__("To be more visible and to view photos of other members, you should add one to your profile.","rencontre"));
			}
			$m = (!empty($rencOpt['nbr']['lengthNameHome'])?intval($rencOpt['nbr']['lengthNameHome']):50);
			$u->display_name = substr($u->display_name,0,$m);
			$u->online = (empty($rencCustom['inlb'])?false:(self::f_enLigne($user_id)));
			$a = '/portrait/'.floor(($user_id)/1000).'/'.Rencontre::f_img(($user_id*10).'-mini');
			// Browser Caching Bypass
			$b = 0;
			if(file_exists($rencDiv['basedir'].$a.'.jpg')) $b = filemtime($rencDiv['basedir'].$a.'.jpg');
			$u->miniPhoto = $rencDiv['baseurl'].$a.'.jpg?'.$b;
			// RETINA
			$u->miniPhotoRetina = '';
			if(file_exists($rencDiv['basedir'].$a.'@2x.jpg')) $u->miniPhotoRetina = $rencDiv['baseurl'].$a.'@2x.jpg?'.$b.' 2x';
			// WEBP
			$u->miniPhotoWebp = '';
			if(file_exists($rencDiv['basedir'].$a.'.webp')) {
				$u->miniPhotoWebp = $rencDiv['baseurl'].$a.'.webp?'.$b;
				if(file_exists($rencDiv['basedir'].$a.'@2x.webp')) $u->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$a.'@2x.webp?'.$b.' 2x';
			}
			//
			$certified = ''; if(has_filter('rencCertifiedP')) $certified = apply_filters('rencCertifiedP', array($u->ID, 2, $u->t_action));
			$m = (!empty($rencOpt['nbr']['lengthTitle'])?intval($rencOpt['nbr']['lengthTitle']):30);
			$u->t_titre = mb_substr(trim($u->t_titre),0,$m,'UTF-8').'...';
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_mini_portrait.php')) include($tpl);
			// ************************
		}
	}
	//
	static function f_miniPortrait2($user_id) {
		// AJAX
		// miniPortrait2 : pour la fenetre du TCHAT
		// entree : user_id
		// sortie : code HTML avec le mini portrait
		if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
		global $wpdb, $rencDiv, $rencCustom, $rencOpt;
		$u = $wpdb->get_row("SELECT
				U.display_name,
				R.c_pays,
				R.c_ville,
				R.d_naissance,
				R.i_photo,
				P.t_titre
			FROM ".$wpdb->base_prefix."users U
			INNER JOIN ".$wpdb->prefix."rencontre_users R
				ON R.user_id=U.ID
			INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
				ON P.user_id=U.ID
			WHERE 
				U.ID=".$user_id."
			LIMIT 1
			");
		if($u) {
			$m = (!empty($rencOpt['nbr']['lengthNameHome'])?intval($rencOpt['nbr']['lengthNameHome']):50);
			$u->display_name = substr($u->display_name,0,$m);
			$rencDrap1 = $wpdb->get_var("SELECT c_liste_valeur FROM ".$wpdb->prefix."rencontre_liste WHERE c_liste_categ='d' and c_liste_iso='".$u->c_pays."' LIMIT 1");
			$rencDrapNom1 = $wpdb->get_var("SELECT c_liste_valeur FROM ".$wpdb->prefix."rencontre_liste WHERE c_liste_categ='p' and c_liste_iso='".$u->c_pays."' and c_liste_lang='".substr($rencDiv['lang3'],0,2)."' LIMIT 1");
			echo substr($u->display_name,0,20)."|"; // pour f_tchat_dem : permet d'afficher le pseudo - memoire JS dans la variable 'ps' - limitation a 20 caracteres
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_mini_portrait_chat.php')) include($tpl);
			// ************************
		}
	}
	//
	static function f_enLigne($id,$s=0) {
		global $rencDiv;
		if(is_file($rencDiv['basedir'].'/session/'.$id.'.txt') && time()-filemtime($rencDiv['basedir'].'/session/'.$id.'.txt')<70) {
			global $wpdb;
			if($s && current_time('timestamp')-strtotime($s)>1200) $wpdb->update($wpdb->prefix.'rencontre_users', array('d_session'=>current_time('mysql')), array('user_id'=>$id));
			return true;
		}
		else return false;
	}
	//
	static function f_count_inbox($f) {
		// Message dans ma boite ?
		global $wpdb, $rencU0;
		if(!empty($rencU0->photoreq)) return;
		$n = $wpdb->get_var("SELECT COUNT(*)
			FROM ".$wpdb->prefix."rencontre_msg M
			WHERE
				M.recipient='".$f."'
				and M.read=0
				and M.deleted=0
				and EXISTS (
					SELECT ID
					FROM ".$wpdb->base_prefix."users U
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=U.ID
					WHERE
						user_login=M.sender
						and P.t_action NOT LIKE '%,pause2,%'
				);
			");
		if(has_filter('rencMsgNotifP')) apply_filters('rencMsgNotifP', array(2,$f));
		if($n) return '<span class="inbox">'.$n.'</span>';
		else return;
	}
	//
	static function f_voirMsg($f,$a) { // retour AJAX
		// entree : $f = id message - $a = mon alias
		// read = 1 => lu
		// read = 2 => repondu
		if(empty($_REQUEST['rencTok']) || !wp_verify_nonce($_REQUEST['rencTok'],'rencTok')) return;
		global $wpdb, $rencOpt;
		// $u : other than me (sender or recipient)
		$u = $wpdb->get_row("SELECT
				U.ID,
				U.user_login,
				U.display_name,
				R.i_photo
			FROM ".$wpdb->base_prefix."users U
			INNER JOIN ".$wpdb->prefix."rencontre_users R
				ON R.user_id=U.ID
			INNER JOIN ".$wpdb->prefix."rencontre_msg M
				ON
					U.user_login=M.recipient".(isset($rencOpt['collat'])?" COLLATE ".$rencOpt['collat']:"")."
					or U.user_login=M.sender".(isset($rencOpt['collat'])?" COLLATE ".$rencOpt['collat']:"")."
			WHERE
				M.id='".$f."'
				and U.user_login!='".$a."'
			LIMIT 1
			");
		self::f_conversation($a,$u->ID,array($u->user_login,$u->display_name),$u->i_photo,0);
	}
	//
	static function f_conversation($a1,$id2,$a2,$ph2,$msgWrite=0) {
		// Called AJAX from f_voirMsg called
		// Also called from template rencontre_message_write.php
		// 1:me, 2:you - a:alias, id: user_id, ph:i_photo - $a2 array(user_login, display_name)
		// $msgWrite => template without head (buttons & photo)
		global $wpdb, $rencDiv, $rencCustom, $rencOpt, $current_user, $rencU0;
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
		$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
		$Lcard = (!empty($rencOpt['lbl']['card'])?$rencOpt['lbl']['card']:'card');
		$Lmsg = (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg');
		$Lwrite = (!empty($rencOpt['lbl']['write'])?$rencOpt['lbl']['write']:'write');
		//
		$action = '';
		if(empty($rencOpt['paus'])) {
			$action = $wpdb->get_var("SELECT t_action FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id='".$id2."' LIMIT 1");
			if(strpos($rencU0->t_action.$action.'-', ',pause2,')!==false) return;
		}
		if(!is_array($a2)) $a2 = array($a2,$a2); // mod V3.1.1 : Display_name
		$conversation = $wpdb->get_results("SELECT
				M.id,
				M.content,
				M.sender,
				M.recipient,
				M.date,
				M.read
			FROM ".$wpdb->prefix."rencontre_msg M 
			WHERE
				(M.recipient='".$a2[0]."' and
				M.sender='".$a1."' and
				M.deleted!=2)
				or 
				(M.recipient='".$a1."' and
				M.sender='".$a2[0]."' and
				M.deleted!=1)
			ORDER BY M.date DESC");
		if($conversation) {
			$u0 = new StdClass();
			$u0->user_login = $a1;
			$u0->login = substr($a1,0,20);
			$u = new StdClass();
			$u->user_login = $a2[0];
			$u->login = substr($a2[0],0,20);
			$m = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
			$u->display_name = substr($a2[1],0,$m);
			$u->i_photo = $ph2;
			$a = '/portrait/'.floor(($ph2)/10000).'/'.Rencontre::f_img((floor(($ph2)/10)*10).'-mini');
			// Browser Caching Bypass
			$b = 0;
			if(file_exists($rencDiv['basedir'].$a.'.jpg')) $b = filemtime($rencDiv['basedir'].$a.'.jpg');
			$u->miniPhoto = $rencDiv['baseurl'].$a.'.jpg?'.$b;
			$u->photo = $u->miniPhoto; // name before V3.6.2
			// RETINA
			$u->miniPhotoRetina = '';
			if(file_exists($rencDiv['basedir'].$a.'@2x.jpg')) $u->miniPhotoRetina = $rencDiv['baseurl'].$a.'@2x.jpg?'.$b.' 2x';
			// WEBP
			$u->miniPhotoWebp = '';
			if(file_exists($rencDiv['basedir'].$a.'.webp')) {
				$u->miniPhotoWebp = $rencDiv['baseurl'].$a.'.webp?'.$b;
				if(file_exists($rencDiv['basedir'].$a.'@2x.webp')) $u->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$a.'@2x.webp?'.$b.' 2x';
			}
			//
			$onClick = array(
				'profile'=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lcard."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($id2,0)."';document.forms['rencMenu'].submit();",
				'write'=>"document.forms['formEcrire'].elements['P".$Lrenc."'].value='".$Lwrite."';document.forms['formEcrire'].elements['P".$Lid."'].value='".$id2."';document.forms['formEcrire'].submit();",
				'del'=>"document.forms['formEcrire'].elements['P".$Lrenc."'].value='".$Lmsg."';document.forms['formEcrire'].elements['".$Lmsg."'].value='msgdel';document.forms['formEcrire'].elements['P".$Lid."'].value='".$id2."';document.forms['formEcrire'].submit();",
				'inbox'=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lmsg."';document.forms['rencMenu'].submit();"
				);
			$title = array(
				'profile'=>"",
				'write'=>"",
				'del'=>"",
				'inbox'=>""
				);
			if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) {
				$onClick['write'] = false;
				$title['write'] = __('You must first complete your registration.','rencontre');
			}
			else if(self::f_etat_bloque1($id2)) {
				$onClick['write'] = false;
				$title['write'] = __('This member blocked you.','rencontre');
			}
			else if(rencistatus($rencDiv['istatus'],1)) {
				$onClick['write'] = false;
				$title['write'] = __('You have been blocked by the moderator.','rencontre');
			}
			else if($rencDiv['pacam']) {
				$onClick['write'] = false;
				$title['write'] = __('Members without photo, attention-catcher and ad cannot send message','rencontre') . '.';
			}
			else if(has_filter('rencLimitedActionP')) {
				$ho = apply_filters('rencLimitedActionP', array('answer',0,0));
				if($ho) {
					$onClick['write'] = false;
					$title['write'] = $ho;
				}
			}
			if(!empty($rencU0->t_action) && strpos($rencU0->t_action.$action.'-', ',pause1,')!==false) {
				$onClick['profile'] = false;
				$title['profile'] = __('Profile hidden','rencontre');
			
			}
			else if(has_filter('rencLimitedActionP')) {
				$ho = apply_filters('rencLimitedActionP', array('profil',0,$id2));
				if($ho) {
					$onClick['profile'] = false;
					$title['profile'] = $ho;
				}
			}
			//
			foreach($conversation as $k=>$m) {
				$conversation[$k]->date = self::format_dateTime($m->date);
				if($m->read==0 && $m->sender!=$a1) $wpdb->update($wpdb->prefix.'rencontre_msg', array('read'=>1), array('id'=>$m->id));
			}
			$hoAns = false; // before V3.7 - not used now
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_message_conversation.php')) include($tpl);
			// ************************
		}
	}
	//
	static function f_suppMsg($a,$all) {
		// entree : $a:alias moi
		// Destinataire supp => deleted=1 - si delete etait 2 => supp reel
		// Emmeteur supp => deleted=2 - si delete etait 1 => supp reel
		// $all : suppression de toute la conversation : alias autre
		global $wpdb;
		$q = $wpdb->get_results("SELECT M.id, M.sender, M.recipient, M.read, M.deleted
			FROM ".$wpdb->prefix."rencontre_msg M 
			WHERE
				(M.sender='".$a."' and M.recipient='".$all."') or
				(M.sender='".$all."' and M.recipient='".$a."') ");
		foreach($q as $r) {
			if($r->sender==$a) { // mon msg
				if($r->deleted==1 || $r->read==0) $wpdb->delete($wpdb->prefix.'rencontre_msg', array('id'=>$r->id)); // suppression reelle de mon msg
				else if($r->deleted==0) $wpdb->update($wpdb->prefix.'rencontre_msg', array('deleted'=>2), array('id'=>$r->id));
			}
			else if($r->recipient==$a) { // msg recu
				if($r->deleted==2) $wpdb->delete($wpdb->prefix.'rencontre_msg', array('id'=>$r->id)); // suppression reelle du msg reçu
				else if($r->deleted==0) $wpdb->update($wpdb->prefix.'rencontre_msg', array('deleted'=>1), array('id'=>$r->id));
			}
		}
	}
	//
	static function f_envoiMsg($f) {
		// called by widget() part 8. after Submit (no AJAX)
		// entree : mon alias
		global $wpdb, $rencDiv, $current_user, $rencOpt;
		$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
		//
		$post = (!empty($_POST)?$_POST:array());
		if(has_filter('rencUserPost')) $post = apply_filters('rencUserPost', $post, 'sendMsg');
		$Pcontenu = (isset($post['contenu'])?rencSanit($post['contenu'],'para'):'');
		$Pid = (isset($post['P'.$Lid])?rencSanit($post['P'.$Lid],'int'):'');
		$a = $wpdb->get_row("SELECT ID, user_login FROM ".$wpdb->base_prefix."users WHERE ID='".$Pid."' LIMIT 1");
		if(!empty($a->ID)) $q = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."rencontre_msg 
			WHERE 
				content = '".addslashes($Pcontenu)."' and 
				sender='".$f."' and 
				recipient='".$a->user_login."'
			LIMIT 1
			");
		if(!empty($a->ID) && !$q) { // not already send
			if(has_filter('rencLimitedActionP')) apply_filters('rencLimitedActionP', array('sendmsg',1,0)); // count msg/day
			// msg in db
			$wpdb->insert($wpdb->prefix.'rencontre_msg', array('content'=>$Pcontenu, 'sender'=>$f, 'recipient'=>$a->user_login, 'date'=>current_time('mysql'), 'read'=>0, 'deleted'=>0));
			// memo pour mail CRON
			if(!is_dir($rencDiv['basedir'].'/portrait/cache/cron_list/')) mkdir($rencDiv['basedir'].'/portrait/cache/cron_list/');
			if(!file_exists($rencDiv['basedir'].'/portrait/cache/cron_list/'.$Pid.'.txt')) {
				$t=fopen($rencDiv['basedir'].'/portrait/cache/cron_list/'.$Pid.'.txt', 'w');
				fclose($t);
			}
			$warning = __('Message sent','rencontre');
			if(has_filter('rencMsgNotifP')) apply_filters('rencMsgNotifP', array(1,$a->ID));
		}
		else $warning = __('Not sent','rencontre');
		return $warning;
		}
	//
	static function f_quickFind($json,$dyn=0) { // $dyn : page in ajax else 0
		$var = json_decode((empty(stripslashes($json))?'{}':stripslashes($json)),true);
		$mid = $var['mid']; // Mon id
		$rencDiv['rencBlock'] = $var['rencBlock'];
		$rencDiv['pacam'] = $var['pacam'];
		$QsearchP = $var['QsearchP'];
		$WssP = $var['WssP'];
		$CertifiedP = base64_decode($var['CertifiedP']);
		$SendP = $var['SendP'];
		$SmileP = $var['SmileP'];
		//
		if($QsearchP) { // Not needed - Security (blockage before)
			$warning = $QsearchP;
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_warning.php')) include($tpl);
			// ************************
		}
		else {
			global $wpdb, $rencOpt, $rencCustom, $rencDiv, $rencU0;
			//
			$get = (!empty($_GET)?$_GET:array());
			if(has_filter('rencUserPost')) $get = apply_filters('rencUserPost', $get, 'quickFind');
			//
			$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
			$Lqsearch = (!empty($rencOpt['lbl']['qsearch'])?$rencOpt['lbl']['qsearch']:'qsearch');
			$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
			$Lcard = (!empty($rencOpt['lbl']['card'])?$rencOpt['lbl']['card']:'card');
			$Lwrite = (!empty($rencOpt['lbl']['write'])?$rencOpt['lbl']['write']:'write');
			$Lsourire = (!empty($rencOpt['lbl']['sourire'])?$rencOpt['lbl']['sourire']:'sourire');
			$Lbloque = (!empty($rencOpt['lbl']['bloque'])?$rencOpt['lbl']['bloque']:'bloque');
			$Lzsex = (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex');
			$Lhomo = (!empty($rencOpt['lbl']['homo'])?$rencOpt['lbl']['homo']:'homo');
			$Lagemin = (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin');
			$Lagemax = (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax');
			$Lpagine = (!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine');
			$Lpays = (!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays');
			$Lregion = (!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region');
			$Lrelation = (!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation');
			$Lprofilqs = (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS');
			$Lline = (!empty($rencOpt['lbl']['line'])?$rencOpt['lbl']['line']:'line');
			//
			$pageRefresh = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0'; // True if page is refreshed
			$newSearch = ((isset($get['pagine'])&&$get['pagine']===""&&!$pageRefresh)?1:0); // pagine = "" in FORM - pagine = "0", "1", "2"... after pagination
			//
			$varSan = $var;
			if(!$dyn) $var = $get;
			//
			$Gpagine = (isset($var[$Lpagine])?rencSanit($var[$Lpagine],'int'):(!empty($dyn)?$dyn:0));
			$Gid = (isset($var[$Lid])?rencSanit(rencGetId($var[$Lid],1),'alphanum'):''); if($Gid!='') $varSan[$Lid] = rencSanit($var[$Lid],'alphanum');
			$Gzsex = (isset($var[$Lzsex])?rencSanit($var[$Lzsex],'numplus'):''); $varSan[$Lzsex] = $Gzsex;
			$Ghomo = (isset($var[$Lhomo])?rencSanit($var[$Lhomo],'int'):0); $varSan[$Lhomo] = $Ghomo;
			$Gagemin = (isset($var[$Lagemin])?rencSanit($var[$Lagemin],'int'):''); $varSan[$Lagemin] = $Gagemin;
			$Gagemax = (isset($var[$Lagemax])?rencSanit($var[$Lagemax],'int'):''); $varSan[$Lagemax] = $Gagemax;
			$Gpays = (isset($var[$Lpays])?rencSanit($var[$Lpays],'AZ'):''); $varSan[$Lpays] = $Gpays;
			$Gregion = (isset($var[$Lregion])?rencSanit($var[$Lregion],'alphanum'):''); $varSan[$Lregion] = $Gregion; // !! Region GET = id  -  Region POST = c_liste_valeur (string)
			$Grelation = (isset($var[$Lrelation])?rencSanit($var[$Lrelation],'numplus'):''); $varSan[$Lrelation] = $Grelation;
			$Gprofilqs1 = (isset($var[$Lprofilqs.'1'])?rencSanit($var[$Lprofilqs.'1'],'alphanum'):''); $varSan[$Lprofilqs.'1'] = $Gprofilqs1;
			$Gprofilqs2 = (isset($var[$Lprofilqs.'2'])?rencSanit($var[$Lprofilqs.'2'],'alphanum'):''); $varSan[$Lprofilqs.'2'] = $Gprofilqs2;
			$Gline = (isset($var[$Lline])?rencSanit($var[$Lline],'int'):''); $varSan[$Lline] = $Gline;
			//
			if(!$dyn && $newSearch && has_filter('rencLimitedActionP')) apply_filters('rencLimitedActionP', array('qsearch',1,0));
			if($WssP) $mephoto = $wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$mid."' LIMIT 1");
			else $mephoto = 1;
			if(!empty($rencOpt['dynsearch'])) $limit = 6;
			else $limit = (!empty($rencOpt['limit'])?$rencOpt['limit']:10);
			$q = false;
			$suiv = 1;
			if(!$dyn) { ?> 

			<form name="rencPagine" method="get" action="">
				<?php if(!empty($rencOpt['page_id'])) { ?>
				
				<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
				<?php } ?>
				
				<input type="hidden" name="<?php echo $Lrenc; ?>" value="<?php echo $Lqsearch; ?>" />
				<input type="hidden" name="<?php echo $Lid; ?>" value="<?php echo rencGetId($Gid,0); ?>" />
				<input type="hidden" name="<?php echo $Lzsex; ?>" value="<?php echo $Gzsex; ?>" />
				<input type="hidden" name="<?php echo $Lhomo; ?>" value="<?php echo $Ghomo; ?>" />
				<input type="hidden" name="<?php echo $Lpagine; ?>" value="<?php echo $Gpagine; ?>" />
				<input type="hidden" name="<?php echo $Lagemin; ?>" value="<?php echo $Gagemin; ?>" />
				<input type="hidden" name="<?php echo $Lagemax; ?>" value="<?php echo $Gagemax; ?>" />
				<input type="hidden" name="<?php echo $Lpays; ?>" value="<?php echo $Gpays; ?>" />
				<input type="hidden" name="<?php echo $Lregion; ?>" value="<?php echo $Gregion; ?>" />
				<input type="hidden" name="<?php echo $Lline; ?>" value="<?php echo $Gline; ?>" />
				<input type="hidden" name="<?php echo $Lprofilqs.'1'; ?>" value="<?php echo $Gprofilqs1; ?>" />
				<input type="hidden" name="<?php echo $Lprofilqs.'2'; ?>" value="<?php echo $Gprofilqs2; ?>" />
				<input type="hidden" name="<?php echo $Lrelation; ?>" value="<?php echo $Grelation; ?>" />
			</form>
			<div <?php if(empty($rencCustom['side'])) echo 'class="w3-twothird w3-left"'; ?>>
			<?php }
			$qpause = (empty($rencOpt['paus'])?" and (P.t_action NOT REGEXP ',pause1,|,pause2,' or P.t_action IS NULL) ":"");
			$sel = "R.user_id,
					R.i_sex,
					R.i_zsex,
					R.c_zsex,
					R.i_zage_min,
					R.i_zage_max,
					R.i_zrelation,
					R.c_zrelation,
					R.c_pays,
					R.c_ville,
					R.d_naissance,
					R.i_photo,
					R.e_lat,
					R.e_lon,
					R.d_session,
					P.t_annonce,
					P.t_titre,
					P.t_action,
					U.display_name
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
					ON P.user_id=U.ID";
			if(!empty($rencU0->photoreq)) $q = array(); // Stop
			else if($Gzsex!='' && !$Gline) {
				$s = "SELECT
						".$sel."
					WHERE R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
						and U.ID!=".$mid;
				$s .= $qpause;
				if($Gregion) $s.=" and R.c_region LIKE '".addslashes($wpdb->get_var("SELECT c_liste_valeur FROM ".$wpdb->prefix."rencontre_liste WHERE id='".$Gregion."' LIMIT 1"))."'";
				if($Gpays) $s.=" and R.c_pays='".$Gpays."'";
				// zsex : si parenthese => c_zsex au format IN : (a,b,g) au lieu de ,a,b,g,
				// sinon => i_zsex : a
				if(strpos($Gzsex.'-',')')===false) {
					$s.=" and R.i_sex='".$Gzsex."'";
					if(!isset($rencCustom['sex'])) $s.=" and R.i_zsex".($Ghomo?'=':'!=').$Gzsex;
				}
				else $s.=" and R.i_sex IN ".$Gzsex;
				if(empty($rencCustom['born'])) {
					if($Gagemin!=='' && $Gagemin>(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18)) { // 18 & 99 => no limit
						$zmin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-intval($Gagemin)));
						$s.=" and R.d_naissance<'".$zmin."'";
					}
					if($Gagemax!=='' && $Gagemax<(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99)) {
						$zmax = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-intval($Gagemax+1)));
						$s.=" and R.d_naissance>'".$zmax."'";
					}
				}
				if($Grelation!=='' && isset($rencCustom['relationQ'])) {
					$s.=" and (R.i_zrelation='".$Grelation."' or R.c_zrelation LIKE '%,".$Grelation.",%')";
				}
				// Profil QS
					// 1	{"i":7,"v":"Yeux de chat"},
					// 2	{"i":6,"v":"Je suis très sympa et souriante."},
					// 3	{"i":4,"v":3},
					// 4	{"i":30,"v":[3,4]},
					// 5	{"i":42,"v":0}
					// 6	{"i":41,"v":"2019-03-01"},
					
				$in = array();
				if($Gprofilqs1!=='' && isset($rencCustom['profilQS1'])) {
					$idqs = $rencCustom['profilQS1'];
					if(substr($idqs,0,1)=='d' || substr($idqs,0,1)=='n') $idqs = substr($idqs,2); //da, db, na, nb
					$t = $wpdb->get_var("SELECT i_type FROM ".$wpdb->prefix."rencontre_profil WHERE id=".$idqs." LIMIT 1");
					if($t==3 || $t==4) $s.=' and P.t_profil REGEXP \'(\\\\{"i":'.$rencCustom['profilQS1'].',)[^\\\\{]+[\\\\[,:]'.$Gprofilqs1.'[\\\\],\\\\}]\' ';
					else if($t==5) {
						// $s.=' and P.t_profil LIKE \'%{"i":'.$rencCustom['profilQS1'].',"v":'.(intval($Gprofilqs1)-1).'\}%\' ';
						$t1 = $wpdb->get_results('SELECT
								user_id,
								REGEXP_SUBSTR(t_profil, \'(?<=\\\\{"i":'.$idqs.',"v":)(.*?)(?=\\\\})\') AS nu
							FROM '.$wpdb->base_prefix.'rencontre_users_profil
							WHERE 
								t_profil LIKE \'%{"i":'.$idqs.',%\'
							HAVING
								nu '.(substr($rencCustom['profilQS1'],1,1)=='a'?'>=':'<=').($Gprofilqs1-1).'
								and nu <> ""
							');
						$in1 = array();
						foreach($t1 as $r) $in1[] = $r->user_id;
						if(empty($in1)) $in = false;
					}
					else if($t==6) {
						$t1 = $wpdb->get_results('SELECT
								user_id,
								REGEXP_SUBSTR(t_profil, \'(?<=\\\\{"i":'.$idqs.',"v":")(.*?)(?="\\\\})\') AS dt
							FROM '.$wpdb->base_prefix.'rencontre_users_profil
							WHERE 
								t_profil LIKE \'%{"i":'.$idqs.',%\'
							HAVING
								DATE(dt) '.(substr($rencCustom['profilQS1'],1,1)=='a'?'>=':'<=').' DATE("'.$Gprofilqs1.'")
								and dt <> ""
							');
						$in1 = array();
						foreach($t1 as $r) $in1[] = $r->user_id;
						if(empty($in1)) $in = false;
					}
				}
				if($Gprofilqs2!=='' && isset($rencCustom['profilQS2'])) {
					$idqs = $rencCustom['profilQS2'];
					if(substr($idqs,0,1)=='d' || substr($idqs,0,1)=='n') $idqs = substr($idqs,2); //da, db, na, nb
					$t = $wpdb->get_var("SELECT i_type FROM ".$wpdb->prefix."rencontre_profil WHERE id=".$idqs." LIMIT 1");
					if($t==3 || $t==4) $s.=' and P.t_profil REGEXP \'(\\\\{"i":'.$rencCustom['profilQS2'].',)[^\\\\{]+[\\\\[,:]'.$Gprofilqs2.'[\\\\],\\\\}]\' ';
					else if($t==5) {
						// $s.=' and P.t_profil LIKE \'%{"i":'.$rencCustom['profilQS2'].',"v":'.(intval($Gprofilqs2)-1).'}%\' ';
						$t1 = $wpdb->get_results('SELECT
								user_id,
								REGEXP_SUBSTR(t_profil, \'(?<=\\\\{"i":'.$idqs.',"v":)(.*?)(?=\\\\})\') AS nu
							FROM '.$wpdb->base_prefix.'rencontre_users_profil
							WHERE 
								t_profil LIKE \'%{"i":'.$idqs.',%\'
							HAVING
								nu '.(substr($rencCustom['profilQS2'],1,1)=='a'?'>=':'<=').($Gprofilqs2-1).'
								and nu <> ""
							');
						$in2 = array();
						foreach($t1 as $r) $in2[] = $r->user_id;
						if(empty($in2)) $in = false;
					}
					else if($t==6) {
						$t1 = $wpdb->get_results('SELECT
								user_id,
								REGEXP_SUBSTR(t_profil, \'(?<=\\\\{"i":'.$idqs.',"v":")(.*?)(?="\\\\})\') AS dt
							FROM '.$wpdb->base_prefix.'rencontre_users_profil
							WHERE 
								t_profil LIKE \'%{"i":'.$idqs.',%\'
							HAVING
								DATE(dt) '.(substr($rencCustom['profilQS2'],1,1)=='a'?'>=':'<=').' DATE("'.$Gprofilqs2.'")
								and dt <> ""
							');
						$in2 = array();
						foreach($t1 as $r) $in2[] = $r->user_id;
						if(empty($in2)) $in = false;
					}
				}
				if(!empty($in1) && !empty($in2)) {
					$in = array_intersect($in1,$in2);
					if(empty($in)) $in = false;
				}
				else if($in!==false && !empty($in1)) $in = $in1; // QS2 not exist
				else if($in!==false && !empty($in2)) $in = $in2; // QS1 not exist
				if($in===false) $s .= " and 0"; // No result in QS1 or QS2
				else if(!empty($in)) {
					$i = '';
					foreach($in as $r) $i .= $r . ',';
					if($i) $s .= " and R.user_id IN (".substr($i,0,-1).")";
				}
				//
				if(!empty($rencOpt['onlyphoto'])) $s .= " and CHAR_LENGTH(P.t_titre)>4 and CHAR_LENGTH(P.t_annonce)>30 and R.i_photo>0";
				$s .= " ORDER BY R.d_session DESC, P.d_modif DESC LIMIT ".($Gpagine*$limit).", ".($limit+1); // LIMIT indice du premier, nombre de resultat
				$q = $wpdb->get_results($s);
				if($wpdb->num_rows<=$limit) $suiv=0;
				else array_pop($q); // supp le dernier ($rencOpt['limit']+1) qui sert a savoir si page suivante
			}
			else if($Gid=='sourireOut' || strstr($_SESSION['rencontre'],'qsearchis')) {
				if(!$dyn) {
					echo '<h3 style="text-align:center;">';
					if($t=rencTranslate('smiw3')) echo $t;
					else echo __('I smiled at','rencontre');
					echo ' ...</h3>';
				}
				$action = $rencU0->action;
				$action['sourireOut']=(isset($action['sourireOut'])?$action['sourireOut']:null);
				$q = array(); $c = 0; $n = 0; $suiv = 0;
				if($action['sourireOut']) {
					krsort($action['sourireOut']);
					foreach($action['sourireOut'] as $r) {
						if($c<=$limit) {
							$q[$c]=$wpdb->get_row("SELECT 
									".$sel."
								WHERE 
									U.ID=".intval($r['i'])."
									and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
									".$qpause."
								LIMIT 1
								");
							if($q[$c]) ++$n;
							if($q[$c] && $n>$Gpagine*$limit) {
								if($c<$limit) $q[$c]->dataction=$r['d'];
								else {$suiv=1;array_pop($q);}
								++$c;
							}
							else unset($q[$c]);
						}
					}
				}
			}
			else if($Gid=='sourireIn' || strstr($_SESSION['rencontre'],'qsearchs')) {
				$smilread = false; if(has_filter('rencLimitedActionP')) $smilread = apply_filters('rencLimitedActionP', array('smilread',0,0));
				if($smilread) { // security
					$warning = $smilread;
					// ****** TEMPLATE ********
					if($tpl=rencTpl('rencontre_warning.php')) include($tpl);
					// ************************
					return;
				}
				if(!$dyn) {
					echo '<h3 style="text-align:center;">';
					if($t=rencTranslate('smiw7')) echo $t;
					else _e('I got a smile from','rencontre');
					echo ' ...</h3>';
				}
				$action = $rencU0->action;
				$action['sourireIn']=(isset($action['sourireIn'])?$action['sourireIn']:null);
				$q = array(); $c = 0; $n = 0; $suiv = 0;
				if($action['sourireIn']) {
					krsort($action['sourireIn']);
					foreach($action['sourireIn'] as $r) {
						if($c<=$limit) {
							$q[$c]=$wpdb->get_row("SELECT 
									".$sel."
								WHERE 
									U.ID=".intval($r['i'])."
									and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
									".$qpause."
								LIMIT 1
								");
							if($q[$c]) ++$n;
							if($q[$c] && $n>$Gpagine*$limit) {
								if($c<$limit) $q[$c]->dataction=$r['d'];
								else {$suiv=1;array_pop($q);}
								++$c;
							}
							else unset($q[$c]);
						}
					}
				}
			}
			else if($Gid=='contactOut' || strstr($_SESSION['rencontre'],'qsearchic')) {
				if(!$dyn) {
					echo '<h3 style="text-align:center;">'.__('I asked a contact','rencontre').' ...</h3>';
				}
				$action = $rencU0->action;
				$action['contactOut']=(isset($action['contactOut'])?$action['contactOut']:null);
				$q = array(); $c = 0; $n = 0; $suiv = 0;
				if($action['contactOut']) {
					krsort($action['contactOut']);
					foreach($action['contactOut'] as $r) {
						if($c<=$limit) {
							$q[$c]=$wpdb->get_row("SELECT 
									".$sel."
								WHERE 
									U.ID=".intval($r['i'])."
									and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
									".$qpause."
								LIMIT 1
								");
							if($q[$c]) ++$n;
							if($q[$c] && $n>$Gpagine*$limit) {
								if($c<$limit) $q[$c]->dataction=$r['d'];
								else {$suiv=1;array_pop($q);}
								++$c;
							}
							else unset($q[$c]);
						}
					}
				}
			}
			else if($Gid=='contactIn' || strstr($_SESSION['rencontre'],'qsearchc')) {
				$contread = false; if(has_filter('rencLimitedActionP')) $contread = apply_filters('rencLimitedActionP', array('contread',0,0));
				if($contread) { // security
					$warning = $contread;
					// ****** TEMPLATE ********
					if($tpl=rencTpl('rencontre_warning.php')) include($tpl);
					// ************************
					return;
				}
				if(!$dyn) {
					echo '<h3 style="text-align:center;">'.__('I had a contact request from','rencontre').' ...</h3>';
				}
				$action = $rencU0->action;
				$action['contactIn']=(isset($action['contactIn'])?$action['contactIn']:null);
				$q = array();$c = 0; $n = 0; $suiv = 0;
				if($action['contactIn']) {
					krsort($action['contactIn']);
					foreach($action['contactIn'] as $r) {
						if($c<=$limit) {
							$q[$c]=$wpdb->get_row("SELECT 
									".$sel."
								WHERE 
									U.ID=".intval($r['i'])."
									and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
									".$qpause."
								LIMIT 1
								");
							if($q[$c]) ++$n;
							if($q[$c] && $n>$Gpagine*$limit) {
								if($c<$limit) $q[$c]->dataction=$r['d'];
								else {$suiv=1;array_pop($q);}
								++$c;
							}
							else unset($q[$c]);
						}
					}
				}
			}
			else if($Gid=='visite' || strstr($_SESSION['rencontre'],'qsearchl')) {
				if(!$dyn) {
					echo '<h3 style="text-align:center;">';
					if($t=rencTranslate('loow2')) echo $t;
					else _e('I was watched by','rencontre');
					echo ' ...</h3>';
				}
				$action = $rencU0->action;
				$action['visite']=(isset($action['visite'])?$action['visite']:null);
				$q = array(); $c = 0; $n = 0; $suiv = 0;
				if($action['visite']) {
					krsort($action['visite']);
					foreach($action['visite'] as $r) {
						if($c<=$limit) {
							$q[$c]=$wpdb->get_row("SELECT 
									".$sel."
								WHERE 
									U.ID=".intval($r['i'])."
									and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
									".$qpause."
								LIMIT 1");
							if($q[$c]) ++$n;
							if($q[$c] && $n>$Gpagine*$limit) {
								if($c<$limit) $q[$c]->dataction=$r['d'];
								else {$suiv=1;array_pop($q);}
								++$c;
							}
							else unset($q[$c]);
						}
					}
				}
			}
			else if($Gid==$Lbloque || strstr($_SESSION['rencontre'],'qsearchib')) {
				if(!$dyn) {
					echo '<h3 style="text-align:center;">'.__('I locked','rencontre').' ...</h3>';
				}
				$action = $rencU0->action;
				$action['bloque']=(isset($action['bloque'])?$action['bloque']:null);
				$q = array(); $c = 0; $n = 0; $suiv = 0;
				if($action['bloque']) {
					krsort($action['bloque']);
					foreach($action['bloque'] as $r) {
						if($c<=$limit) {
							$q[$c]=$wpdb->get_row("SELECT 
									".$sel."
								WHERE 
									U.ID=".intval($r['i'])."
									and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
									".$qpause."
								LIMIT 1
								");
							if($q[$c]) ++$n;
							if($q[$c] && $n>$Gpagine*$limit) {
								if($c<$limit) $q[$c]->dataction=$r['d'];
								else {$suiv=1;array_pop($q);}
								++$c;
							}
							else unset($q[$c]);
						}
					}
				}
			}
			else if($Gline) {
				if(!$dyn) {
					echo '<h3 style="text-align:center;">'.__('Online now','rencontre').'</h3>';
				}
				$tab = ''; $d = $rencDiv['basedir'].'/session/';
				if($dh=opendir($d)) {
					while(($file=readdir($dh))!==false) {
						if($file!='.' && $file!='..' && (filemtime($d.$file)>time()-70)) $tab .= "'".basename($file, ".txt")."',";
					}
					closedir($dh);
				}
				// Selection par le sex
				if(strpos($Gzsex.'-',')')===false) {
					$sexQuery = " and R.i_sex=".$Gzsex." ";
					if(!isset($rencCustom['sex'])) $sexQuery .= " and R.i_zsex".($Ghomo?'='.$Gzsex:'!='.$Gzsex)." ";
				}
				else {
					$sexQuery = " and R.i_sex IN ".$Gzsex." ";
				}
				$q = $wpdb->get_results("SELECT 
						".$sel."
					WHERE 
						U.ID IN (".substr($tab,0,-1).") 
						".$sexQuery."
						and U.ID!=".$mid."
						and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
						".$qpause."
					LIMIT ".($Gpagine*$limit).", ".($limit+1)); // LIMIT indice du premier, nombre de resultat
				if($wpdb->num_rows<=$limit) $suiv=0;
				else array_pop($q); // supp le dernier ($rencOpt['limit']+1) qui sert a savoir si page suivante
			}
			if(!empty($rencCustom['searchAd'])) { ?>
			
				<div class="rencBox">
			<?php }
			if($q) foreach($q as $u) {
				$m = (!empty($rencOpt['nbr']['searchResultAd'])?intval($rencOpt['nbr']['searchResultAd']):300);
				$u->t_annonce = trim(preg_replace('/\s+/', ' ', $u->t_annonce));
				preg_match('/^.{0,'.$m.'}(?:.*?)\b/iu',$u->t_annonce,$n); // 300 character cut
				$u->t_annonce = (!empty($n[0])?$n[0].'...':'');
				$m = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
				$u->display_name = substr($u->display_name,0,$m);
				$u->blocked = new StdClass();
				$u->blocked->me = self::f_etat_bloque1($u->user_id,$u->t_action); // je suis bloque ?
				$action = json_decode((empty($u->t_action)?'{}':$u->t_action),true);
				$u->zstrict = (isset($action['option'])&&strpos($action['option'],',zstrict,')!==false)?1:0;
				$noProfile = false; if(has_filter('rencLimitedActionP')) $noProfile = apply_filters('rencLimitedActionP', array('profil',0,$u->user_id));
				$title = array(
					"send"=>"",
					"smile"=>"",
					"profile"=>""
					);
				$disable = array(
					"send"=>0,
					"smile"=>0,
					"profile"=>0
					);
				$onClick = array(
					"send"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lwrite."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->user_id,0)."';document.forms['rencMenu'].submit();",
					"smile"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lsourire."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->user_id,0)."';document.forms['rencMenu'].submit();",
					"profile"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lcard."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->user_id,0)."';document.forms['rencMenu'].submit();"
					);
				$u->looking = '';
				$u->forwhat = '';
				if(isset($u->dataction)) $u->date = substr($u->dataction,8,2).'.'.substr($u->dataction,5,2).'.'.substr($u->dataction,0,4);
				else $u->date = '';
				$u->online = self::f_enLigne($u->user_id); // true : en ligne - false : hors ligne
				if(isset($u->d_session) && substr($u->d_session,0,4)!=0) {
					$a = human_time_diff(strtotime($u->d_session), current_time('timestamp'));
					$u->online_ago = sprintf(__('%s ago','rencontre'), $a);
				}
				else $u->online_ago = '';
				if(!empty($rencOpt['onlyphoto']) && !$mephoto) $u->hidephoto = 1;
				else $u->hidephoto = 0;
				$u->thumb = '';
				if($u->hidephoto) {
					if($t=rencTranslate('nophText')) $u->thumb = $t;
					else $u->thumb = addslashes(__("To be more visible and to view photos of other members, you should add one to your profile.","rencontre"));
				}
				$a = '/portrait/'.floor(($u->user_id)/1000).'/'.Rencontre::f_img(($u->user_id*10).'-mini');
				// Browser Caching Bypass
				$b = 0;
				if(file_exists($rencDiv['basedir'].$a.'.jpg')) $b = filemtime($rencDiv['basedir'].$a.'.jpg');
				$u->miniPhoto = $rencDiv['baseurl'].$a.'.jpg?'.$b;
				// RETINA
				$u->miniPhotoRetina = '';
				if(file_exists($rencDiv['basedir'].$a.'@2x.jpg')) $u->miniPhotoRetina = $rencDiv['baseurl'].$a.'@2x.jpg?'.$b.' 2x';
				// WEBP
				$u->miniPhotoWebp = '';
				if(file_exists($rencDiv['basedir'].$a.'.webp')) {
					$u->miniPhotoWebp = $rencDiv['baseurl'].$a.'.webp?'.$b;
					if(file_exists($rencDiv['basedir'].$a.'@2x.webp')) $u->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$a.'@2x.webp?'.$b.' 2x';
				}
				//
				$searchAdd1 = ''; // not in quick search
				//
				if($u->i_zsex!=99) { 
					if(isset($rencOpt['iam'][$u->i_zsex])) $u->looking = $rencOpt['iam'][$u->i_zsex];
					if(isset($rencOpt['for'][$u->i_zrelation])) $u->forwhat = $rencOpt['for'][$u->i_zrelation];
				}
				else { // looking
					$a = explode(',', $u->c_zsex);
					$as = '';
					foreach($a as $a1) if(isset($rencOpt['iam'][$a1])) $as .= $rencOpt['iam'][$a1] . ', ';
					$u->looking = substr($as,0,-2);
					// forwhat
					$a = explode(',', $u->c_zrelation);
					$as = '';
					foreach($a as $a1) if(isset($rencOpt['for'][$a1])) $as .= $rencOpt['for'][$a1] . ', ';
					$u->forwhat = substr($as,0,-2);
				}
				$certified = '';
				if($CertifiedP) {
					$action = json_decode((empty($u->t_action)?'{}':$u->t_action),true);
					if(!empty($action['certified'])) $certified = $CertifiedP;
				}
				// BUTTONS - line 432
				// Send a message - Smile - Profile	
				if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) {
					$disable['send'] = $disable['smile'] = 1;
					$title['send'] = $title['smile'] = __('You must first complete your registration.','rencontre');
				}
				else if($u->blocked->me) {
					$disable['send'] = $disable['smile'] = 1;
					$title['send'] = $title['smile'] = __('This member blocked you.','rencontre');
				}
				else if($rencDiv['rencBlock']) {
					$disable['send'] = $disable['smile'] = 1;
					$title['send'] = $title['smile'] = __('You have been blocked by the moderator.','rencontre');
				}
				else {
					if($SmileP) {
						$disable['smile'] = 1;
						$title['smile'] = $SmileP;
					}
					if($rencDiv['pacam']) {
						$disable['send'] = 1;
						$title['send'] = __('Members without photo, attention-catcher and ad cannot send message','rencontre') . '.';
					}
					else if(rencistatus($rencDiv['istatus'],1)) {
						$disable['send'] = 1;
						$title['send'] = __('You are no longer allowed to send messages.','rencontre');
					}
					else if($SendP) {
						$disable['send'] = 1;
						$title['send'] = $SendP;
					}
				}
				if(isset($rencCustom['smile'])) $disable['smile'] = 1; // securite
				if(!empty($rencDiv['mpause'])) {
					$disable['send'] = $disable['smile'] = $disable['profile'] = 1;
					$onClick['send'] = $onClick['smile'] = $onClick['profile'] = "";
					$title['send'] = $title['smile'] = $title['profile'] = ($rencDiv['mpause']=='pause1'?__('Profile hidden','rencontre'):__('Profile switched off','rencontre'));
				}
				else if($noProfile) {
					$disable['profile'] = 1;
					$onClick['profile'] = "";
					$title['profile'] = $noProfile;
				}
				if($rencU0->zstrict || $u->zstrict) {
					$b = self::f_zstrict($u); // ligne 1862
					if($b) {
						$disable['send'] = $disable['smile'] = 1;
						$title['send'] = $title['smile'] = __('You or this member do not want to interact if the criteria do not match.','rencontre');
					}
				}
				// 4. Other
				$highlight = false; if(has_filter('rencHighlightP')) $highlight = apply_filters('rencHighlightP', $u->user_id);
				// ****** TEMPLATE ********
				if($tpl=rencTpl('rencontre_search_result.php')) include($tpl);
				// ************************
			}
			else if(!empty($rencU0->photoreq)) echo '<p>'.__('You have no photo on your profile?','rencontre').'</p>';
			else if(!$dyn) echo '<p>'.__('Sorry, but nothing matched your search terms.','rencontre').'</p>';
			if(!$dyn) {
				if(!empty($rencCustom['searchAd'])) { ?>

					</div><!-- .rencBox -->
				<?php }
				if(!empty($rencOpt['dynsearch'])) { ?>
					
					<div id="dynSearch"></div>
					<form name="dyn"><input type="hidden" id="dynMemory" name="dynMemory" value="0" /></form>
					<script type="text/javascript">var dynSearch=1,dynPag=1;jQuery(document).ready(function(){f_dyn_reload();jQuery(window).scroll(function(){var d=jQuery("#dynSearch").offset().top,w=jQuery(window).height(),c=jQuery(this).scrollTop();if(dynSearch&&c>d-w){dynSearch=0;f_dyn_search('<?php echo json_encode($varSan); ?>','<?php echo admin_url('admin-ajax.php'); ?>',1);}});});</script>
				<?php }
				else if($Gpagine||$suiv) { ?>
				
					<div class="w3-center">
						<div class="rencPagine w3-bar">
					<?php if(($Gpagine+0)>0) echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value=".$Gpagine."-1;document.forms['rencPagine'].submit();\">".__('Previous page','rencontre')."</a>";
					for($v=max(0, $Gpagine-4); $v<$Gpagine; ++$v) {
						echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value='".$v."';document.forms['rencPagine'].submit();\">".($v+1)."</a>";
					}
					echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-dark-grey w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value='".$Gpagine."';document.forms['rencPagine'].submit();\">".($Gpagine+1)."</a>";
					if($suiv) echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value=".$Gpagine."+1;document.forms['rencPagine'].submit();\">".__('Next Page','rencontre')."</a>";
					?>
						</div>
					</div>
					<?php
				}
				?>
				</div><?php if(empty($rencCustom['side'])) echo '<!-- .w3-twothird -->'; ?>
				<?php
			}
		}
	}
	static function f_cherchePlus($mid) {
		// formulaire de la recherche plus
		global $wpdb, $rencOpt, $rencCustom, $rencU0;
		$Lagemin = (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin');
		$Lagemax = (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax');
		$Ltaillemin = (!empty($rencOpt['lbl']['tailleMin'])?$rencOpt['lbl']['tailleMin']:'tailleMin');
		$Ltaillemax = (!empty($rencOpt['lbl']['tailleMax'])?$rencOpt['lbl']['tailleMax']:'tailleMax');
		$Lpoidsmin = (!empty($rencOpt['lbl']['poidsMin'])?$rencOpt['lbl']['poidsMin']:'poidsMin');
		$Lpoidsmax = (!empty($rencOpt['lbl']['poidsMax'])?$rencOpt['lbl']['poidsMax']:'poidsMax');
		//
		$u0 = clone $rencU0;
		$mem = array(); // $mem : zage_min, zage_max, size_min, size_max, weight_min, weight_max, zsex, country, region, city, gps, km, photo, relation
		if(isset($_COOKIE['searchmem'.$mid])) {
			$mem = json_decode((empty(base64_decode($_COOKIE['searchmem'.$mid]))?'{}':base64_decode($_COOKIE['searchmem'.$mid])),true);
		}
		if(!empty($mem['zage_min'])) $u0->i_zage_min = $mem['zage_min'];
		if(!empty($mem['zage_max'])) $u0->i_zage_max = $mem['zage_max'];
		if(empty($mem['size_max'])) {
			if(!empty($rencCustom['sizeu']) && (($rencCustom['sizeu']==3&&strpos($u0->t_action.'-',',size-cm,')===false) || $rencCustom['sizeu']==2)) $mem['size_max'] = 79; // 79 inch
			else $mem['size_max'] = 200; // 200 cm
		}
		if(empty($mem['weight_max'])) {
			if(!empty($rencCustom['weightu']) && (($rencCustom['weightu']==3&&strpos($u0->t_action.'-',',weight-kg,')===false) || $rencCustom['weightu']==2)) $mem['weight_max'] =  265; // 265 lbs
			else $mem['weight_max'] = 120; // 120 kg
		}
		$u0->cryptID = rencGetId($mid,0);
		$u0->zsex = (($u0->i_zsex!=99)?$u0->i_zsex:'('.substr($u0->c_zsex,1,-1).')');
		$u0->homo = (($u0->i_sex==$u0->i_zsex)?1:0); // TO BE REMOVED SOON - NOT USED - V3.6
		$u0->country = ($u0->c_pays?$u0->c_pays:($rencOpt['pays']?$rencOpt['pays']:''));
		if(empty($mem['country'])) $mem['country'] = $u0->country;
		$a1 = (isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);
		$a2 = (isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);
		$b = (intval($u0->age)?intval($u0->age):intval(($a1+$a2)/2));
		$u0->agemin = (intval($b)-10>$a1?intval($b)-10:$a1);
		$u0->agemax = (intval($b)+10<$a2?intval($b)+10:$a2);
		if(empty($u0->i_zage_min)) $u0->i_zage_min = $u0->agemin;
		if(empty($u0->i_zage_max)) $u0->i_zage_max = $u0->agemax;
		//
		$city = "";
		$ho = false; if(has_filter('rencProxiSearchP')) $ho = apply_filters('rencProxiSearchP', $u0);
		if(isset($ho[1])) {
			$map = $ho[0];
			$city = $ho[1];
		}
		$hom = false; if(has_filter('rencLimitedActionP')) $hom = apply_filters('rencLimitedActionP', array('search',0,0));
		$blockSearch = false; if(has_filter('rencLimitedActionP')) $blockSearch = apply_filters('rencLimitedActionP', array('search',0,0)); // DISABLE FIND BUTTON IN SEARCH PAGE
		if(!empty($u0->photoreq)) $blockSearch = __('You have no photo on your profile?','rencontre');
		$find = array( "class"=>"w3-button w3-renc-mebt w3-renc-mebo", "title"=>""); // VAR $find no longer used since V3.6.5
		$onClick = array(
			"agemin"=>"f_min(parseInt(this.options[this.selectedIndex].value),'formTrouve','".$Lagemin."','".$Lagemax."');",
			"agemax"=>"f_max(parseInt(this.options[this.selectedIndex].value),'formTrouve','".$Lagemin."','".$Lagemax."');",
			"sizemin"=>"f_min(this.options[this.selectedIndex].value,'formTrouve','".$Ltaillemin."','".$Ltaillemax."');",
			"sizemax"=>"f_max(this.options[this.selectedIndex].value,'formTrouve','".$Ltaillemin."','".$Ltaillemax."');",
			"weightmin"=>"f_min(this.options[this.selectedIndex].value,'formTrouve','".$Lpoidsmin."','".$Lpoidsmax."');",
			"weightmax"=>"f_max(this.options[this.selectedIndex].value,'formTrouve','".$Lpoidsmin."','".$Lpoidsmax."');",
			"country"=>"f_region_select(this.options[this.selectedIndex].value,'".admin_url('admin-ajax.php')."','regionSelect');",
			"city"=>"onkeyup=\"".$city."\"",
			"validate"=>"",
			"find"=>"f_trouve()",
			);
		//
		$moreSearch1 = '';
		$ho = false; if(has_filter('rencProfilSP')) $ho = apply_filters('rencProfilSP', $mem);
		if($ho) $moreSearch1 .= $ho;
		$ho = false; if(has_filter('rencProfilOkP')) $ho = apply_filters('rencProfilOkP', $ho);
		if($ho) $moreSearch1 .= $ho;
		$ho = false; if(has_filter('rencAstroOkP') && !isset($rencCustom['born'])) $ho = apply_filters('rencAstroOkP', 1);
		if($ho)  $moreSearch1 .= $ho;
		if($moreSearch1) $moreSearch1 .= "\r\n";
		//
		if(!strstr($_SESSION['rencontre'],'liste')) { // nouvelle recherche
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_search.php')) include($tpl);
			// ************************
		}
		else {
			$SearchResultP = false; if(has_filter('rencSearchResultInP')) $SearchResultP = apply_filters('rencSearchResultInP', 0);
			$WssP = false; if(has_filter('rencWssP')) $WssP = apply_filters('rencWssP', 0);
			$AstroOkP = false; if(has_filter('rencAstroOkP')) $AstroOkP = base64_encode(apply_filters('rencAstroOkP', 0));
			$ProfilOkP = false; if(has_filter('rencProfilOkP')) $ProfilOkP = base64_encode(apply_filters('rencProfilOkP', 0));
			$CertifiedP = ''; if(has_filter('rencCertifiedP')) $CertifiedP = base64_encode(apply_filters('rencCertifiedP', false));
			$SearchP = false; $SendP = false; $SmileP = false;
			if(has_filter('rencLimitedActionP')) {
				$SearchP = apply_filters('rencLimitedActionP', array('search',0,0));
				$SendP = apply_filters('rencLimitedActionP', array('sendmsg',0,0));
				if(!isset($rencCustom['smile'])) $SmileP = apply_filters('rencLimitedActionP', array('smile',0,0));
			}
			$var = array(
		//		'mid'=>$mid,
				'SearchResultP'=>$SearchResultP,
				'SearchP'=>$SearchP,
				'WssP'=>$WssP,
				'AstroOkP'=>$AstroOkP,
				'ProfilOkP'=>$ProfilOkP,
				'CertifiedP'=>$CertifiedP,
				'SendP'=>$SendP,
				'SmileP'=>$SmileP
			);
			self::f_trouver(json_encode($var,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)); // resultat apres Submit
		}
	}
	//
	static function f_trouver($json=0,$dyn=0) { // $dyn : page in ajax else 0 {
		// Search result
		$var = json_decode((empty(stripslashes($json))?'{}':stripslashes($json)),true);
//		$mid = $var['mid'];
		$SearchResultP = false; if(has_filter('rencSearchResultOutP')) $SearchResultP = apply_filters('rencSearchResultOutP', $var['SearchResultP']);
		$SearchP = $var['SearchP'];
		$WssP = $var['WssP'];
		$AstroOkP = base64_decode($var['AstroOkP']);
		$ProfilOkP = base64_decode($var['ProfilOkP']);
		$CertifiedP = base64_decode($var['CertifiedP']);
		$SendP = $var['SendP'];
		$SmileP = $var['SmileP'];
		//
		if($SearchP) { // Not needed - Security (stop before)
			$warning = $SearchP;
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_warning.php')) include($tpl);
			// ************************
		}
		else {
			global $wpdb, $rencOpt, $rencDiv, $rencCustom, $rencU0;
			//
			$get = (!empty($_GET)?$_GET:array());
			if(has_filter('rencUserPost')) $get = apply_filters('rencUserPost', $get, 'findPlus');
			//
			$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
			$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
			$Lcard = (!empty($rencOpt['lbl']['card'])?$rencOpt['lbl']['card']:'card');
			$Lliste = (!empty($rencOpt['lbl']['liste'])?$rencOpt['lbl']['liste']:'liste');
			$Lwrite = (!empty($rencOpt['lbl']['write'])?$rencOpt['lbl']['write']:'write');
			$Lsourire = (!empty($rencOpt['lbl']['sourire'])?$rencOpt['lbl']['sourire']:'sourire');
			$Lsex = (!empty($rencOpt['lbl']['sex'])?$rencOpt['lbl']['sex']:'sex');
			$Lzsex = (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex');
			$Lz2sex = (!empty($rencOpt['lbl']['z2sex'])?$rencOpt['lbl']['z2sex']:'z2sex');
			$Lpagine = (!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine');
			$Lagemin = (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin');
			$Lagemax = (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax');
			$Ltaillemin = (!empty($rencOpt['lbl']['tailleMin'])?$rencOpt['lbl']['tailleMin']:'tailleMin');
			$Ltaillemax = (!empty($rencOpt['lbl']['tailleMax'])?$rencOpt['lbl']['tailleMax']:'tailleMax');
			$Lpoidsmin = (!empty($rencOpt['lbl']['poidsMin'])?$rencOpt['lbl']['poidsMin']:'poidsMin');
			$Lpoidsmax = (!empty($rencOpt['lbl']['poidsMax'])?$rencOpt['lbl']['poidsMax']:'poidsMax');
			$Lmot = (!empty($rencOpt['lbl']['mot'])?$rencOpt['lbl']['mot']:'mot');
			$Lpseudo = (!empty($rencOpt['lbl']['pseudo'])?$rencOpt['lbl']['pseudo']:'pseudo');
			$Lpays = (!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays');
			$Lregion = (!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region');
			$Lville = (!empty($rencOpt['lbl']['ville'])?$rencOpt['lbl']['ville']:'ville');
			$Lrelation = (!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation');
			$Lphoto = (!empty($rencOpt['lbl']['photo'])?$rencOpt['lbl']['photo']:'photo');
			$Lprofil = (!empty($rencOpt['lbl']['profil'])?$rencOpt['lbl']['profil']:'profil');
			$Lastro = (!empty($rencOpt['lbl']['astro'])?$rencOpt['lbl']['astro']:'astro');
			$Lgps = (!empty($rencOpt['lbl']['gps'])?$rencOpt['lbl']['gps']:'gps');
			$Lkm = (!empty($rencOpt['lbl']['km'])?$rencOpt['lbl']['km']:'km');
			//
			$pageRefresh = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0'; // True if page is refreshed
			$newSearch = ((isset($get['pagine'])&&$get['pagine']===""&&!$pageRefresh)?1:0); // pagine = "" in FORM - pagine = "0", "1", "2"... after pagination
			//
			$varSan = $var;
			if(!$dyn) $var = $get;
			//
			$Gid = (isset($var[$Lid])?rencSanit(rencGetId($var[$Lid],1),'int'):''); $varSan[$Lid] = rencSanit($var[$Lid],'alphanum');
			$Gpagine = (isset($var[$Lpagine])?rencSanit($var[$Lpagine],'int'):(!empty($dyn)?$dyn:0)); // Dynamic load => $dyn
			$Gpays = (isset($var[$Lpays])?rencSanit($var[$Lpays],'AZ'):''); $varSan[$Lpays] = $Gpays;
			$Gregion = (isset($var[$Lregion])?rencSanit($var[$Lregion],'alphanum'):''); $varSan[$Lregion] = $Gregion; // !! Region GET = id  -  Region POST = c_liste_valeur (string)
			$Gville = (isset($var[$Lville])?rencSanit($var[$Lville],'words'):''); $vaSanr[$Lville] = $Gville;
			$Ggps = (isset($var[$Lgps])?rencSanit($var[$Lgps],'pipe'):''); $varSan[$Lgps] = $Ggps;
			$Gkm = (isset($var[$Lkm])?rencSanit($var[$Lkm],'int'):''); $varSan[$Lkm] = $Gkm;
			$Gpseudo = (isset($var[$Lpseudo])?rencSanit($var[$Lpseudo],'words'):''); $varSan[$Lpseudo] = $Gpseudo;
			$Gsex = (isset($var[$Lsex])?rencSanit($var[$Lsex],'int'):''); $varSan[$Lsex] = $Gsex;
			$Gzsex = (isset($var[$Lzsex])?rencSanit($var[$Lzsex],'numplus'):''); $varSan[$Lzsex] = $Gzsex;
			if(isset($rencCustom['sex']) || isset($rencCustom['sexAll'])) {
				$Gz2sex = (isset($var[$Lz2sex])?rencSanit($var[$Lz2sex],'numplus'):''); $varSan[$Lz2sex] = $Gz2sex; // string
			}
			$Gagemin = (isset($var[$Lagemin])?rencSanit($var[$Lagemin],'int'):''); $varSan[$Lagemin] = $Gagemin;
			$Gagemax = (isset($var[$Lagemax])?rencSanit($var[$Lagemax],'int'):''); $varSan[$Lagemax] = $Gagemax;
			$Gtaillemin = (isset($var[$Ltaillemin])?rencSanit($var[$Ltaillemin],'num'):''); $varSan[$Ltaillemin] = $Gtaillemin;
			$Gtaillemax = (isset($var[$Ltaillemax])?rencSanit($var[$Ltaillemax],'num'):''); $varSan[$Ltaillemax] = $Gtaillemax;
			$Gpoidsmin = (isset($var[$Lpoidsmin])?rencSanit($var[$Lpoidsmin],'int'):''); $varSan[$Lpoidsmin] = $Gpoidsmin;
			$Gpoidsmax = (isset($var[$Lpoidsmax])?rencSanit($var[$Lpoidsmax],'int'):''); $varSan[$Lpoidsmax] = $Gpoidsmax;
			$Gmot = (isset($var[$Lmot])?rencSanit($var[$Lmot],'words'):''); $varSan[$Lmot] = $Gmot;
			$Gphoto = (isset($var[$Lphoto])?rencSanit($var[$Lphoto],'int'):''); $varSan[$Lphoto] = $Gphoto;
			$Gprofil = (isset($var[$Lprofil])?rencSanit($var[$Lprofil],'int'):''); $varSan[$Lprofil] = $Gprofil;
			$Gastro = (isset($var[$Lastro])?rencSanit($var[$Lastro],'int'):''); $varSan[$Lastro] = $Gastro;
			$Grelation = (isset($var[$Lrelation])?rencSanit($var[$Lrelation],'numplus'):''); $varSan[$Lrelation] = $Grelation;
			if(!$dyn && $newSearch && has_filter('rencLimitedActionP')) apply_filters('rencLimitedActionP', array('search',1,0));
			if($WssP && $Gid!=='') $mephoto = $wpdb->get_var("SELECT i_photo FROM ".$wpdb->prefix."rencontre_users WHERE user_id='".$Gid."' LIMIT 1");
			else $mephoto = 1;
			if(!empty($rencOpt['dynsearch'])) $limit = 6;
			else $limit = (!empty($rencOpt['limit'])?$rencOpt['limit']:10);
			$suiv = 1;
			if(!$dyn) { ?> 
			
			<form name="rencPagine" method="get" action="">
				<?php if(isset($rencOpt['page_id'])) { ?>
				
				<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
				<?php } ?>
				
				<input type="hidden" name="<?php echo $Lrenc; ?>" value="<?php echo $Lliste; ?>" />
				<input type="hidden" name="<?php echo $Lpays; ?>" value="<?php echo $Gpays; ?>" />
				<input type="hidden" name="<?php echo $Lregion; ?>" value="<?php echo $Gregion; ?>" />
				<input type="hidden" name="<?php echo $Lville; ?>" value="<?php echo $Gville; ?>" />
				<input type="hidden" name="<?php echo $Lgps; ?>" value="<?php echo $Ggps; ?>" />
				<input type="hidden" name="<?php echo $Lkm; ?>" value="<?php echo $Gkm; ?>" />
				<input type="hidden" name="<?php echo $Lpseudo; ?>" value="<?php echo $Gpseudo; ?>" />
				<input type="hidden" name="<?php echo $Lsex; ?>" value="<?php echo $Gsex; ?>" />
				<input type="hidden" name="<?php echo $Lzsex; ?>" value="<?php echo $Gzsex; ?>" />
				<?php if(isset($rencCustom['sex']) || isset($rencCustom['sexAll'])) { ?>

				<input type="hidden" name="<?php echo $Lz2sex; ?>" value="<?php echo $Gz2sex; ?>" />
				<?php } ?>

				<input type="hidden" name="<?php echo $Lagemin; ?>" value="<?php echo $Gagemin; ?>" />
				<input type="hidden" name="<?php echo $Lagemax; ?>" value="<?php echo $Gagemax; ?>" />
				<input type="hidden" name="<?php echo $Ltaillemin; ?>" value="<?php echo $Gtaillemin; ?>" />
				<input type="hidden" name="<?php echo $Ltaillemax; ?>" value="<?php echo $Gtaillemax; ?>" />
				<input type="hidden" name="<?php echo $Lpoidsmin; ?>" value="<?php echo $Gpoidsmin; ?>" />
				<input type="hidden" name="<?php echo $Lpoidsmax; ?>" value="<?php echo $Gpoidsmax; ?>" />
				<input type="hidden" name="<?php echo $Lmot; ?>" value="<?php echo $Gmot; ?>" />
				<input type="hidden" name="<?php echo $Lphoto; ?>" value="<?php echo $Gphoto; ?>" />
				<input type="hidden" name="<?php echo $Lprofil; ?>" value="<?php echo $Gprofil; ?>" />
				<input type="hidden" name="<?php echo $Lastro; ?>" value="<?php echo $Gastro; ?>" />
				<input type="hidden" name="<?php echo $Lrelation; ?>" value="<?php echo $Grelation; ?>" />
				<input type="hidden" name="<?php echo $Lid; ?>" value="<?php echo rencGetId($Gid,0); ?>" />
				<input type="hidden" name="<?php echo $Lpagine; ?>" value="<?php echo $Gpagine; ?>" />
				<?php if(isset($SearchResultP[2])) echo stripslashes($SearchResultP[0]); ?>
			</form>
			<?php }
			$hoprofil = false; $hoastro = false;
			// Selection par le sex - zsex:my setting in DB - z2sex:search select (only custom sex)
			$sexQuery = ''; // and R.i_zsex!=0 and R.i_sex=0
			// 1. SEX
			if(isset($Gz2sex) && $Gz2sex!=='') $sexQuery .= " and R.i_sex=".$Gz2sex; // z2sex : gender field in search => only if custom gender
			else if(empty($rencCustom['sexAll'])) {
				if(strpos($Gzsex.'-',')')!==false) $sexQuery .= " and R.i_sex IN ".$Gzsex; // allow multi choice in custom multiSR => () in zsex
				else $sexQuery .= " and R.i_sex=".$Gzsex;
			}
			// 2. ZSEX - multiSR => c_zsex
			if(empty($rencCustom['sexAll'])) $sexQuery .= " and (R.c_zsex LIKE '%,".$Gsex.",%' or R.i_zsex=".$Gsex.") "; // multiSR or not (in case of change multiSR by ADMIN but not by users Account)
			//
			$qpause = (empty($rencOpt['paus'])?" and (P.t_action NOT REGEXP ',pause1,|,pause2,' or P.t_action IS NULL) ":"");
			if($Gpseudo) $s = "SELECT 
					R.user_id,
					R.i_zsex,
					R.c_zsex,
					R.i_zage_min,
					R.i_zage_max,
					R.i_zrelation,
					R.c_zrelation,
					R.c_pays,
					R.c_ville,
					R.i_sex,
					R.d_naissance,
					R.i_photo,
					P.t_titre,
					P.t_annonce,
					P.t_action,
					U.display_name
				FROM ".$wpdb->base_prefix."users U
				INNER JOIN ".$wpdb->prefix."rencontre_users R
					ON R.user_id=U.ID
				INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
					ON P.user_id=U.ID
				WHERE 
					(U.user_login LIKE '%".$Gpseudo."%'
					or U.display_name LIKE '%".$Gpseudo."%')
					".$sexQuery."
					".$qpause."
					and R.user_id!=".$Gid."
					and R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")";
			else {
				$s = "SELECT 
						U.user_login,
						U.display_name,
						R.user_id,
						".($Gastro?'R.d_naissance, ':'')."
						R.i_zsex,
						R.c_zsex,
						R.i_zage_min,
						R.i_zage_max,
						R.i_zrelation,
						R.c_zrelation,
						R.c_pays,
						R.c_ville,
						R.i_sex,
						R.d_naissance,
						R.i_photo,
						R.e_lat,
						R.e_lon,
						R.d_session,
						P.t_annonce,
						P.t_titre,
						".($Gprofil?'P.t_profil, ':'')."
						P.t_action 
					FROM ".$wpdb->base_prefix."users U
					INNER JOIN ".$wpdb->prefix."rencontre_users R
						ON R.user_id=U.ID
					INNER JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=U.ID
					WHERE 
						R.i_status IN (0,2".((isset($rencOpt['blockmand'])&&$rencOpt['blockmand']==2)?'':',8,10').")
						".$qpause."
						and U.ID!=".$Gid;
				$s .= $sexQuery;
				if(empty($rencCustom['born'])) {
					if($Gagemin>(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18)) { // pas de filtre si min
						$zmin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-intval($Gagemin)));
						$s .= " and R.d_naissance<'".$zmin."'";
					}
					if($Gagemax<(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99)) { // pas de filtre si maw
						$zmax = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-intval($Gagemax+1)));
						$s .= " and R.d_naissance>'".$zmax."'";
					}
				}
				if(empty($rencCustom['size'])) {
					if(empty($rencU0->imperials)) {
						$s .= " and R.i_taille>=".intval(10*max($Gtaillemin, (!empty($rencCustom['sizemin'])?$rencCustom['sizemin']:140)));
						$s .= " and R.i_taille<=".intval(10*min(($Gtaillemax?$Gtaillemax:9999), (!empty($rencCustom['sizemax'])?$rencCustom['sizemax']:220)));
					}
					else {
						$s .= " and R.i_taille>=".max(rencConvertUnit($Gtaillemin,'in',2), 10*(!empty($rencCustom['sizemin'])?$rencCustom['sizemin']:140));
						$s .= " and R.i_taille<=".min(($Gtaillemax?rencConvertUnit($Gtaillemax,'in',2):9999), 10*(!empty($rencCustom['sizemax'])?$rencCustom['sizemax']:220));
					}
				}
				if(empty($rencCustom['weight'])) {
					if(empty($rencU0->imperialw)) {
						$s .= " and R.i_poids>=".intval(10*max($Gpoidsmin, (!empty($rencCustom['weightmin'])?$rencCustom['weightmin']:40)));
						$s .= " and R.i_poids<=".intval(10*min(($Gpoidsmax?$Gpoidsmax:9999), (!empty($rencCustom['weightmax'])?$rencCustom['weightmax']:140)));
					}
					else {
						$s .= " and R.i_poids>=".max(rencConvertUnit($Gpoidsmin,'lbs',2), 10*(!empty($rencCustom['weightmin'])?$rencCustom['weightmin']:40));
						$s .= " and R.i_poids<=".min(($Gpoidsmax?rencConvertUnit($Gpoidsmax,'lbs',2):9999), 10*(!empty($rencCustom['weightmax'])?$rencCustom['weightmax']:140));
					}
				}
				if(!empty($SearchResultP[2])) {
					$s .= stripslashes($SearchResultP[2]);
					$limit = (!empty($rencOpt['limit'])?$rencOpt['limit']:10);
				}
				else {
					if($Gville) $s .= " and replace(replace(replace(replace(replace(LOWER(R.c_ville),'+',''),'-',''),' ',''),'\'',''),'\"','') LIKE '".str_replace(array('+','-',' ','\'','"','(',')',',','[',']'),'',strtolower($Gville))."%'";
					if($Gpays) $s .= " and R.c_pays='".$Gpays."'";
					if($Gregion) $s .= " and R.c_region LIKE '".addslashes($wpdb->get_var("SELECT c_liste_valeur FROM ".$wpdb->prefix."rencontre_liste WHERE id='".$Gregion."' LIMIT 1"))."'";
				}
				if($Gmot) $s .= " and (P.t_annonce LIKE '%".$Gmot."%' or P.t_titre LIKE '%".$Gmot."%')";
				if($Gphoto) $s .= " and R.i_photo>0";
				if($Grelation!=='') {
					$s .= " and (R.i_zrelation='".$Grelation."' or R.c_zrelation LIKE '%,".$Grelation.",%')";
				}
				if($Gastro && $AstroOkP) $hoastro = $AstroOkP;
				else if($Gprofil && $ProfilOkP) $hoprofil = $ProfilOkP;
				if(!empty($SearchResultP[1])) $s .= stripslashes($SearchResultP[1]);
			}
			if(!$hoastro && !$hoprofil) {
				$s .= " ORDER BY R.d_session DESC, P.d_modif DESC LIMIT ".($Gpagine * $limit).", ".($limit+1); // LIMIT indice du premier, nombre de resultat
				$q = $wpdb->get_results($s);
				if($wpdb->num_rows<=$limit) $suiv=0;
				else array_pop($q); // supp le dernier ($rencOpt['limit']+1) qui sert a savoir si page suivante
			}
//			else { // ************************************ ASTRO SEARCH && PROFIL SEARCH NOT CURRENTLY WORKING AJAX ******************
			else if(!$dyn) {
				$q = array(); $c = 0; $suiv = 0;
				if($hoastro) $q1 = apply_filters('rencAstroP', $s); // full search - no pagination
				else if($hoprofil) $q1 = apply_filters('rencProfilP', $s);
				foreach($q1 as $r) {
					if($c>=($Gpagine*$limit)+$limit) {
						$suiv = 1;
						break;
					}
					else if($c>=($Gpagine*$limit)) $q[] = $r;
					++$c;
				}
			}
			// ***********************************************************************************************************************
			if(!$dyn) { // ****************** MAP CURRENTLY NOT WORKING WITH AJAX ************************
				if(!empty($SearchResultP[3]) && has_filter('rencProxiSrMapP')) $map1 = apply_filters('rencProxiSrMapP', $q, $SearchResultP[3]);
				if(!empty($rencCustom['searchAd'])) echo '<div class="rencBox">';
				if(!empty($map1)) echo $map1;
			}
			// ***********************************************************************************************************************
			if($q) {
				foreach($q as $u) {
					$m = (!empty($rencOpt['nbr']['searchResultAd'])?intval($rencOpt['nbr']['searchResultAd']):300);
					$u->t_annonce = trim(preg_replace('/\s+/', ' ', $u->t_annonce));
					preg_match('/^.{0,'.$m.'}(?:.*?)\b/iu',$u->t_annonce,$n); // 300 character cut
					$u->t_annonce = (!empty($n[0])?$n[0].'...':'');
					$m = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
					$u->display_name = substr($u->display_name,0,$m);
					$u->blocked = new StdClass();
					$u->blocked->me = self::f_etat_bloque1($u->user_id,$u->t_action); // je suis bloque ?
					$action = json_decode((empty($u->t_action)?'{}':$u->t_action),true);
					$u->zstrict = (isset($action['option'])&&strpos($action['option'],',zstrict,')!==false)?1:0;
					$noProfile = false; if(has_filter('rencLimitedActionP')) $noProfile = apply_filters('rencLimitedActionP', array('profil',0,$u->user_id));
					$title = array(
						"send"=>"",
						"smile"=>"",
						"profile"=>""
						);
					$disable = array(
						"send"=>0,
						"smile"=>0,
						"profile"=>0
						);
					$newtab = (!empty($rencOpt['newtab'])?"document.forms['rencMenu'].target='_blank';":"");
					$onClick = array(
						"send" => $newtab."document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lwrite."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->user_id,0)."';document.forms['rencMenu'].submit();",
						"smile" => $newtab."document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lsourire."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->user_id,0)."';document.forms['rencMenu'].submit();",
						"profile" => $newtab."document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lcard."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u->user_id,0)."';document.forms['rencMenu'].submit();"
						);
					$u->looking = '';
					$u->forwhat = '';
					$u->date = '';
					$u->online = self::f_enLigne($u->user_id); // true : en ligne - false : hors ligne
					if(isset($u->d_session) && substr($u->d_session,0,4)!=0) {
						$a = human_time_diff(strtotime($u->d_session), current_time('timestamp'));
						$u->online_ago = sprintf(__('%s ago','rencontre'), $a);
					}
					else $u->online_ago = '';
					if(!empty($rencOpt['onlyphoto']) && !$mephoto) $u->hidephoto = 1;
					else $u->hidephoto = 0;
					$u->thumb = '';
					if($u->hidephoto) {
						if($t=rencTranslate('nophText')) $u->thumb = $t;
						else $u->thumb = addslashes(__("To be more visible and to view photos of other members, you should add one to your profile.","rencontre"));
					}
					$a = '/portrait/'.floor(($u->user_id)/1000).'/'.Rencontre::f_img(($u->user_id*10).'-mini');
					// Browser Caching Bypass
					$b = 0;
					if(file_exists($rencDiv['basedir'].$a.'.jpg')) $b = filemtime($rencDiv['basedir'].$a.'.jpg');
					$u->miniPhoto = $rencDiv['baseurl'].$a.'.jpg?'.$b;
					// RETINA
					$u->miniPhotoRetina = '';
					if(file_exists($rencDiv['basedir'].$a.'@2x.jpg')) $u->miniPhotoRetina = $rencDiv['baseurl'].$a.'@2x.jpg?'.$b.' 2x';
					// WEBP
					$u->miniPhotoWebp = '';
					if(file_exists($rencDiv['basedir'].$a.'.webp')) {
						$u->miniPhotoWebp = $rencDiv['baseurl'].$a.'.webp?'.$b;
						if(file_exists($rencDiv['basedir'].$a.'@2x.webp')) $u->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$a.'@2x.webp?'.$b.' 2x';
					}
					//
					$searchAdd1 = '';
					if($hoastro && $u->score) { 				
						$searchAdd1 = '<div class="affinity">'.__('Astrological affinity','rencontre').__(': ','rencontre').'<span>'.$u->score.' / 5</span>';
						$searchAdd1 .= '<img style="margin:-5px 0 0 5px;" src="'.plugins_url($hoastro.'/img/astro'.$u->score.'.png').'" alt="astro" /></div>';
					}
					else if($hoprofil && $u->score) {
						$searchAdd1 = '<div class="affinity">'.__('Affinity with my profile','rencontre').__(': ','rencontre').'<span>'.$u->score.'</span> '.__('points','rencontre').'.</div>';
					}
					//
					if($u->i_zsex!=99) { 
						if(isset($rencOpt['iam'][$u->i_zsex])) $u->looking = $rencOpt['iam'][$u->i_zsex];
						if(isset($rencOpt['for'][$u->i_zrelation])) $u->forwhat = $rencOpt['for'][$u->i_zrelation];
					}
					else { // looking
						$a = explode(',', $u->c_zsex);
						$as = '';
						foreach($a as $a1) if(isset($rencOpt['iam'][$a1])) $as .= $rencOpt['iam'][$a1] . ', ';
						$u->looking = substr($as,0,-2);
						// forwhat
						$a = explode(',', $u->c_zrelation);
						$as = '';
						foreach($a as $a1) if(isset($rencOpt['for'][$a1])) $as .= $rencOpt['for'][$a1] . ', ';
						$u->forwhat = substr($as,0,-2);
					}
					$certified = '';
					if($CertifiedP && !empty($action['certified'])) $certified = $CertifiedP;
					// BUTTONS
					// Send a message - Smile - Profile	
					if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) {
						$disable['send'] = $disable['smile'] = 1;
						$title['send'] = $title['smile'] = __('You must first complete your registration.','rencontre');
					}
					else if($u->blocked->me) {
						$disable['send'] = $disable['smile'] = 1;
						$title['send'] = $title['smile'] = __('This member blocked you.','rencontre');
					}
					else if($rencDiv['rencBlock']) {
						$disable['send'] = $disable['smile'] = 1;
						$title['send'] = $title['smile'] = __('You have been blocked by the moderator.','rencontre');
					}
					else {
						if($SmileP) {
							$disable['smile'] = 1;
							$title['smile'] = $SmileP;
						}
						if($rencDiv['pacam']) {
							$disable['send'] = 1;
							$title['send'] = __('Members without photo, attention-catcher and ad cannot send message','rencontre') . '.';
						}
						else if(rencistatus($rencDiv['istatus'],1)) {
							$disable['send'] = 1;
							$title['send'] = __('You are no longer allowed to send messages.','rencontre');
						}
						else if($SendP) {
							$disable['send'] = 1;
							$title['send'] = $SendP;
						}
					}
					if(isset($rencCustom['smile'])) $disable['smile'] = 1; // securite
					if(!empty($rencDiv['mpause'])) {
						$disable['send'] = $disable['smile'] = $disable['profile'] = 1;
						$onClick['send'] = $onClick['smile'] = $onClick['profile'] = "";
						$title['send'] = $title['smile'] = $title['profile'] = ($rencDiv['mpause']=='pause1'?__('Profile hidden','rencontre'):__('Profile switched off','rencontre'));
					}
					else if($noProfile) {
						$disable['profile'] = 1;
						$onClick['profile'] = "";
						$title['profile'] = $noProfile;
					}
					if($rencU0->zstrict || $u->zstrict) {
						$b = self::f_zstrict($u); // ligne 1862
						if($b) {
							$disable['send'] = $disable['smile'] = 1;
							$title['send'] = $title['smile'] = __('You or this member do not want to interact if the criteria do not match.','rencontre');
						}
					}
					// 4. Other
					$highlight = false; if(has_filter('rencHighlightP')) $highlight = apply_filters('rencHighlightP', $u->user_id);
					// ****** TEMPLATE ********
					if($tpl=rencTpl('rencontre_search_result.php')) include($tpl);
					// ************************
				}
			}
			else if(!$dyn) echo '<p>'.__('Sorry, but nothing matched your search terms.','rencontre').'</p>';
			if(!$dyn) {
				// ******************* Rencontre V3.5 - AJAX REOAD NOT CURRENTLY WORKING WITH ASTROSEARCH OR PROFILSEARCH OR MAPSEARCH *****************
				if(empty($hoastro) && empty($hoprofil) && empty($SearchResultP[3]) && !empty($rencOpt['dynsearch'])) {
				// *************************************************************************************************************************************
				?>
					
					<div id="dynSearch"></div>
					<form name="dyn"><input type="hidden" id="dynMemory" name="dynMemory" value="0" /></form>
					<script type="text/javascript">var dynSearch=1,dynPag=1;jQuery(document).ready(function(){f_dyn_reload();jQuery(window).scroll(function(){var d=jQuery("#dynSearch").offset().top,w=jQuery(window).height(),c=jQuery(this).scrollTop();if(dynSearch&&c>d-w){dynSearch=0;f_dyn_search('<?php echo json_encode($varSan); ?>','<?php echo admin_url('admin-ajax.php'); ?>',0);}});});</script>
				<?php }
				else if($Gpagine||$suiv) { ?>
					
					<div class="w3-center">
						<div class="rencPagine w3-bar">
					<?php
					if(($Gpagine+0)>0) echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value=".$Gpagine."-1;document.forms['rencPagine'].submit();\">".__('Previous page','rencontre')."</a>";
					for($v=max(0, $Gpagine-4); $v<$Gpagine; ++$v) {
						echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value='".$v."';document.forms['rencPagine'].submit();\">".($v+1)."</a>";
					}
					echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-dark-grey w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value='".$Gpagine."';document.forms['rencPagine'].submit();\">".($Gpagine+1)."</a>";
					if($suiv) echo "<a href=\"javascript:void(0)\" class=\"w3-button w3-hover-dark-grey\" onClick=\"document.forms['rencPagine'].elements['".$Lpagine."'].value=".$Gpagine."+1;document.forms['rencPagine'].submit();\">".__('Next Page','rencontre')."</a>";
					?>
					
						</div>
					</div>
			<?php }
			}
		}
	}
	//
	static function f_registerMember($f,$g) {
		// $f : ID
		// $g : 1, 2, OK
		global $wpdb, $rencOpt, $rencU0;
		$Pannee = (isset($_POST['annee'])?rencSanit($_POST['annee'],'int'):(date('Y')-35));
		$Pmois = (isset($_POST['mois'])?rencSanit($_POST['mois'],'int'):date('m'));
		$Pjour = (isset($_POST['jour'])?rencSanit($_POST['jour'],'int'):date('d'));
		$Ppays = (isset($_POST['pays'])?rencSanit($_POST['pays'],'AZ'):(!empty($rencOpt['pays'])?$rencOpt['pays']:'FR'));
		$Pregion = (isset($_POST['region'])?rencSanit($_POST['region'],'words'):''); // !! Region GET = id  -  Region POST = c_liste_valeur (string)
		$Pville = (isset($_POST['ville'])?rencSanit($_POST['ville'],'words'):'');
		$Psex = (isset($_POST['sex'])?rencSanit($_POST['sex'],'int'):0);
		$Ptaille = (isset($_POST['taille'])?rencSanit($_POST['taille'],'num'):175);
		if(!empty($rencU0->imperials)) $Ptaille = rencConvertUnit($Ptaille, 'in', 2); // Metric x10 in DB
		else $Ptaille = intval(10 * (float)$Ptaille + .5);
		$Ppoids = (isset($_POST['poids'])?rencSanit($_POST['poids'],'int'):72);
		if(!empty($rencU0->imperialw)) $Ppoids = rencConvertUnit($Ppoids, 'lbs', 2); // Metric x10 in DB
		else $Ppoids = intval(10 * (float)$Ppoids + .5);
		$Pgps = (isset($_POST['gps'])?rencSanit($_POST['gps'],'pipe'):'');
		$Pzagemin = (isset($_POST['zageMin'])?rencSanit($_POST['zageMin'],'int'):25);
		$Pzagemax = (isset($_POST['zageMax'])?rencSanit($_POST['zageMax'],'int'):45);
		$Pzsex = (isset($_POST['zsex'])?rencSanit($_POST['zsex'],'int'):1);
		if(has_action('rencontre_registration')) do_action('rencontre_registration', $f, $g);
		else {
			if($g=='1') {
				if($Pannee && $Pmois && $Pjour) $nais = $Pannee.'-'.((strlen($Pmois)<2)?'0'.$Pmois:$Pmois).'-'.((strlen($Pjour)<2)?'0'.$Pjour:$Pjour);
				else $nais = '';
				$wpdb->delete($wpdb->prefix.'rencontre_users', array('user_id'=>$f)); // suppression si existe deja
				$wpdb->delete($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$f)); // suppression si existe deja
				$wpdb->insert($wpdb->prefix.'rencontre_users', array(
					'user_id'=>$f,
					'c_pays'=>$Ppays, // default - custom no localisation
					'i_sex'=>$Psex,
					'd_naissance'=>$nais,
					'i_taille'=>$Ptaille,
					'i_poids'=>$Ppoids,
					'd_session'=>current_time('mysql'),
					'i_photo'=>0));
				$wpdb->insert($wpdb->prefix.'rencontre_users_profil', array('user_id'=>$f, 'd_modif'=>current_time('mysql'),'t_titre'=>'', 't_annonce'=>'', 't_profil'=>'[]'));
			}
			else if($g=='2') {
				if(ctype_digit($Pregion)) $Pregion = $wpdb->get_var("SELECT c_liste_valeur FROM ".$wpdb->prefix."rencontre_liste WHERE id='".intval($Pregion)."' LIMIT 1");
				if($Pgps) $gps = explode('|',$Pgps.'|0|0');
				$wpdb->update($wpdb->prefix.'rencontre_users', array(
					'c_pays'=>$Ppays,
					'c_region'=>$Pregion,
					'c_ville'=>$Pville,
					'e_lat'=>($Pgps?round(floatval($gps[0]),5):''),
					'e_lon'=>($Pgps?round(floatval($gps[1]),5):'')),
					array('user_id'=>$f));
			}
			else if($g=="OK") {
				global $rencCustom;
				if(!isset($rencCustom['multiSR']) || !$rencCustom['multiSR'] || !is_array($_POST['zsex']) || !is_array($_POST['zrelation'])) {
					$wpdb->update($wpdb->prefix.'rencontre_users', array(
						'i_zsex'=>$Pzsex,
						'c_zsex'=>',',
						'i_zage_min'=>$Pzagemin,
						'i_zage_max'=>$Pzagemax,
						'i_zrelation'=>rencSanit($_POST['zrelation'],'int'),
						'c_zrelation'=>','),
						array('user_id'=>$f));
				}
				else {
					$czs = ','; $czr = ',';
					foreach($_POST['zsex'] as $r) $czs .= rencSanit($r,'int') . ',';
					foreach($_POST['zrelation'] as $r) $czr .= rencSanit($r,'int') . ',';
					$wpdb->update($wpdb->prefix.'rencontre_users', array(
						'i_zsex'=>99,
						'c_zsex'=>$czs,
						'i_zage_min'=>$Pzagemin,
						'i_zage_max'=>$Pzagemax,
						'i_zrelation'=>99,
						'c_zrelation'=>$czr),
						array('user_id'=>$f));
				}
			}
		}
	}
	//
	static function f_updateMember($f) {
		// $f : ID
		global $wpdb, $rencOpt, $rencCustom, $rencU0;
		if(has_action('rencontre_account')) do_action('rencontre_account', $f);
		else {
			$post = (!empty($_POST)?$_POST:array());
			if(has_filter('rencUserPost')) $post = apply_filters('rencUserPost', $post, 'updateMember');
			$Pdname = (!empty($post['dname'])?rencSanit($post['dname'],'words'):'');
			$Pannee = (isset($post['annee'])?rencSanit($post['annee'],'int'):(date('Y')-35));
			$Pmois = (isset($post['mois'])?rencSanit($post['mois'],'int'):date('m'));
			$Pjour = (isset($post['jour'])?rencSanit($post['jour'],'int'):date('d'));
			$Ppays = (isset($post['pays'])?rencSanit($post['pays'],'AZ'):(!empty($rencOpt['pays'])?$rencOpt['pays']:'FR'));
			$Pregion = (isset($post['region'])?rencSanit($post['region'],'words'):'');  // !! Region GET = id  -  Region POST = c_liste_valeur (string)
			$Pville = (isset($post['ville'])?rencSanit($post['ville'],'words'):'');
			$Ptaille = (isset($post['taille'])?rencSanit($post['taille'],'num'):175);
			if(!empty($rencU0->imperials)) $Ptaille = rencConvertUnit($Ptaille, 'in', 2); // Metric x10 in DB
			else $Ptaille = intval(10 * (float)$Ptaille + .5);
			$Ppoids = (isset($post['poids'])?rencSanit($post['poids'],'int'):72);
			if(!empty($rencU0->imperialw)) $Ppoids = rencConvertUnit($Ppoids, 'lbs', 2); // Metric x10 in DB
			else $Ppoids = intval(10 * (float)$Ppoids + .5);
			$Pgps = (isset($post['gps'])?rencSanit($post['gps'],'pipe'):'');
			$Pzagemin = (isset($post['zageMin'])?rencSanit($post['zageMin'],'int'):25);
			$Pzagemax = (isset($post['zageMax'])?rencSanit($post['zageMax'],'int'):45);
			$Psex = (isset($post['sex'])?rencSanit($post['sex'],'int'):0);
			if(strlen($Pdname)>2) {
				global $current_user;
				if($Pdname!=$current_user->display_name) wp_update_user(array('ID'=>$current_user->ID, 'display_name'=>substr(strtok($Pdname,'@'),0,30)));
			}
			if($Pannee && $Pmois && $Pjour) $nais = $Pannee.'-'.((strlen($Pmois)<2)?'0'.$Pmois:$Pmois).'-'.((strlen($Pjour)<2)?'0'.$Pjour:$Pjour);
			else $nais = '';
			if(ctype_digit($Pregion)) $Pregion = $wpdb->get_var("SELECT c_liste_valeur FROM ".$wpdb->prefix."rencontre_liste WHERE id='".intval($Pregion)."' LIMIT 1");
			if($Pgps) $gps = explode('|',$Pgps.'|0|0');
			if(empty($rencCustom['multiSR'])) {
				$Pzsex = (isset($post['zsex'])?rencSanit($post['zsex'],'int'):1);
				if(!empty($rencCustom['hetero']) && $Psex==$Pzsex) {
					if(!$Psex) $Pzsex = 1;
					else if($Psex==1) $Pzsex = 0;
					else if($Psex==2 && isset($rencOpt['iam'][3])) $Pzsex = 3;
					else $Pzsex = 1;
				}
				$a = array(
					'c_pays'=>$Ppays,
					'c_region'=>$Pregion,
					'c_ville'=>$Pville,
					'e_lat'=>(!empty($Pgps[0])?round(floatval($gps[0]),5):''),
					'e_lon'=>(!empty($Pgps[1])?round(floatval($gps[1]),5):''),
					'd_naissance'=>$nais,
					'i_taille'=>$Ptaille,
					'i_poids'=>$Ppoids,
					'i_zsex'=>$Pzsex,
					'c_zsex'=>',',
					'i_zage_min'=>$Pzagemin,
					'i_zage_max'=>$Pzagemax,
					'i_zrelation'=>rencSanit($post['zrelation'],'int'),
					'c_zrelation'=>',',
					'd_session'=>current_time("mysql"));
				if($Psex!=='') $a['i_sex'] = $Psex;
				$wpdb->update($wpdb->prefix.'rencontre_users', $a, array('user_id'=>$f));
			}
			else {
				$czs = ','; $czr = ',';
				foreach($post['zsex'] as $r) if(empty($rencCustom['hetero']) || rencSanit($r,'int')!==$Psex) $czs .= rencSanit($r,'int') . ',';
				foreach($post['zrelation'] as $r) $czr .= rencSanit($r,'int') . ',';
				$a = array(
					'c_pays'=>$Ppays,
					'c_region'=>$Pregion,
					'c_ville'=>$Pville,
					'e_lat'=>($Pgps?round(floatval($gps[0]),5):''),
					'e_lon'=>($Pgps?round(floatval($gps[1]),5):''),
					'd_naissance'=>$nais,
					'i_taille'=>$Ptaille,
					'i_poids'=>$Ppoids,
					'i_zsex'=>99,
					'c_zsex'=>$czs,
					'i_zage_min'=>$Pzagemin,
					'i_zage_max'=>$Pzagemax,
					'i_zrelation'=>99,
					'c_zrelation'=>$czr,
					'd_session'=>current_time("mysql"));
				if($Psex!=='') $a['i_sex'] = $Psex;
				$wpdb->update($wpdb->prefix.'rencontre_users', $a, array('user_id'=>$f));
			}
			// options
			$q = $wpdb->get_var("SELECT t_action FROM ".$wpdb->prefix."rencontre_users_profil WHERE user_id='".$f."' LIMIT 1");
			$action = json_decode((empty($q)?'{}':$q),true);
			if(has_filter('rencUserNoLibreSP')) $action = apply_filters('rencUserNoLibreSP', $action);
			if(!isset($action['option'])) $action['option'] = ',';
			$b = 0;
			// zstrict
			if(isset($post['zstrict']) && strpos($action['option'].'-', ',zstrict,')===false) {
				$action['option'] .= 'zstrict,';
				$b = 1;
			}
			else if(!isset($post['zstrict']) && strpos($action['option'].'-', ',zstrict,')!==false) {
				$b = 1;
				$action['option'] = str_replace(',zstrict,', ',', $action['option']);
			}
			// nomail
			if(isset($post['nomail']) && strpos($action['option'].'-', ',nomail,')===false) {
				$action['option'] .= 'nomail,';
				$b = 1;
			}
			else if(!isset($post['nomail']) && strpos($action['option'].'-', ',nomail,')!==false) {
				$b = 1;
				$action['option'] = str_replace(',nomail,', ',', $action['option']);
			}
			// nobip
			if(isset($post['nobip']) && strpos($action['option'].'-', ',nobip,')===false) {
				$action['option'] .= 'nobip,';
				$b = 1;
			}
			else if(!isset($post['nobip']) && strpos($action['option'].'-', ',nobip,')!==false) {
				$b = 1;
				$action['option'] = str_replace(',nobip,', ',', $action['option']);
			}
			// weightunit
			if(isset($post['weightunit'])) {
				if($post['weightunit']==0) {
					if(strpos($action['option'].'-', ',weight-kg,')===false) $action['option'] .= 'weight-kg,';
					$action['option'] = str_replace(',weight-lbs,', ',', $action['option']);
					$b = 1;
				}
				else if($post['weightunit']==1) {
					if(strpos($action['option'].'-', ',weight-lbs,')===false) $action['option'] .= 'weight-lbs,';
					$action['option'] = str_replace(',weight-kg,', ',', $action['option']);
					$b = 1;
				}
			}
			else if(!isset($post['weightunit']) && strpos($action['option'].'-', ',weight-')!==false) {
				$b = 1;
				$action['option'] = str_replace(',weight-kg,', ',', $action['option']);
				$action['option'] = str_replace(',weight-lbs,', ',', $action['option']);
			}
			// sizeunit
			if(isset($post['sizeunit'])) {
				if($post['sizeunit']==0) {
					if(strpos($action['option'].'-', ',size-cm,')===false) $action['option'] .= 'size-cm,';
					$action['option'] = str_replace(',size-ft,', ',', $action['option']);
					$b = 1;
				}
				else if($post['sizeunit']==1) {
					if(strpos($action['option'].'-', ',size-ft,')===false) $action['option'] .= 'size-ft,';
					$action['option'] = str_replace(',size-cm,', ',', $action['option']);
					$b = 1;
				}
			}
			else if(!isset($post['sizeunit']) && strpos($action['option'].'-', ',size-')!==false) {
				$b = 1;
				$action['option'] = str_replace(',size-cm,', ',', $action['option']);
				$action['option'] = str_replace(',size-ft,', ',', $action['option']);
			}
			//
			if($b) {
				$out = json_encode($action);
				$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$f));
			}
			rencU0($f); // Reload U0 to update display - Rencontre_filter
		}
	if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) echo "<script language='JavaScript'>document.location.href='".(!empty($rencOpt['home'])?$rencOpt['home']:site_url())."';</script>"; //reload to hide warning
	}
	//
	static function f_compte($mid) {
		// Fenetre de modification du compte
		global $wpdb, $rencOpt, $rencDrapNom, $rencCustom, $rencU0;
		$u0 = clone $rencU0;
		if(empty($rencCustom['region']) && empty($rencCustom['place'])) {
			if(!empty($u0->c_region)) {
				$q = $wpdb->get_var('SELECT id FROM '.$wpdb->prefix.'rencontre_liste WHERE c_liste_valeur="'.$u0->c_region.'" LIMIT 1');
				if(!$q && function_exists('wpGeonames_shortcode'))  $q = $wpdb->get_var('SELECT geonameid FROM '.$wpdb->base_prefix.'geonames
					WHERE
						feature_class="A"
						and name="'.$u0->c_region.'"
						and (feature_code="ADM1" or feature_code="ADM2" or feature_code="PCLD")
					LIMIT 1
					');
			}
			if(empty($q)) $u0->accountAlert = __('Region field is empty or outdated !','rencontre');
		}
		$u0->age = Rencontre::f_age($u0->d_naissance);
		$a1 = (isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);
		$a2 = (isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);
		$b = (intval($u0->age)?intval($u0->age):intval(($a1+$a2)/2));
		$u0->agemin = (intval($b)-10>$a1?intval($b)-10:$a1);
		$u0->agemax = (intval($b)+10<$a2?intval($b)+10:$a2);
		if(empty($u0->i_zage_min)) $u0->i_zage_min = $u0->agemin;
		if(empty($u0->i_zage_max)) $u0->i_zage_max = $u0->agemax;
		$u0->pause = (strpos($u0->t_action.'-',',pause2,')!==false?2:(strpos($u0->t_action.'-',',pause1,')!==false?1:0));
		$ho = false; if(has_filter('rencBeforeUserDel')) $ho = apply_filters('rencBeforeUserDel', $u0->ID);
		$onClick = array(
			"change"=>"f_password(document.forms['formPass'].elements['pass0'].value,document.forms['formPass'].elements['pass1'].value,document.forms['formPass'].elements['pass2'].value,".$u0->ID.",'".admin_url('admin-ajax.php')."')",
			"country"=>"f_region_select(this.options[this.selectedIndex].value,'".admin_url('admin-ajax.php')."','regionSelect');",
			"validate"=>"",
			"agemin"=>"f_min(this.options[this.selectedIndex].value,'formNouveau','zageMin','zageMax');",
			"agemax"=>"f_max(this.options[this.selectedIndex].value,'formNouveau','zageMin','zageMax');",
			"save"=>"f_mod_nouveau(".$u0->ID.")",
			"delete"=>($ho?$ho:"f_fin()")
			);
		if(empty($rencOpt['accprof'])) $scriptMap = '<script>jQuery(document).ready(function(){jQuery(":checkbox.rencLabelauty").labelauty({icon:false});});</script>';
		else $scriptMap = ''; // Already loaded in portrait_edit template
		$hetero = false; $blksex = false;
		if(!empty($rencCustom['hetero'])) $hetero = $u0->i_sex;
		if(!empty($rencCustom['blksex']) && (!isset($rencOpt['fastreg']) || $rencOpt['fastreg']<2)) $blksex = $u0->i_sex;
		$y = current_time('Y');
		$oldmax = $y-(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99)-1;
		$oldmin = $y-(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18)+1;
		list($Y, $m, $j) = explode('-', $u0->d_naissance);
		$u0->born_day = (intval($j)?intval($j):1);
		$u0->born_month = (intval($m)?intval($m):1);
		$a1 = (isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);
		$a2 = (isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);
		$b = (intval($u0->age)?intval($u0->age):intval(($a1+$a2)/2));
		$u0->born_year = (intval($Y)?intval($Y):date('Y')-$b);
		$month = array();
		for($v=0;$v<12;++$v) $month[$v+1] = date_i18n('M', $v*2626560+1263556800); // $v * 30j + 15 jan 2010
		if((isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1)) {
			$title = '';
			$content = '<div id="rencAlertEmail" class="w3-panel w3-renc-wabg" style="display:none"></div>';
			$content .= '<div id="fastregInfo" class="fastregInfo w3-renc-wabg w3-padding">';
			if($rencOpt['fastreg']==2 || $rencOpt['fastreg']==4) $content .= '<div>'.__('You are currently hidden to other members and you can\'t communicate because your account is not complete. You should promptly update this page otherwise your access will be canceled.','rencontre').'</div>';
			if($rencOpt['fastreg']>2) {
				$content .= '<div>'.__('You also need to confirm your email address by clicking the link that you received. You have 24 hours. Do not delay.','rencontre').'</div>';
				$content .= '<div class="w3-section w3-right-align"><a href="javascript:void(0)" class="w3-button w3-renc-mebt w3-renc-mebo" onClick="f_fastregMail(\''.admin_url('admin-ajax.php').'\')">'.__('Email not received','rencontre').'</a></div>';
			}
			$content .= '</div>';
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_mini_bloc.php')) include($tpl);
			// ************************
		} 
		if(function_exists('wpGeonames_shortcode') && empty($rencCustom['country']) && empty($rencCustom['place']) && empty($rencCustom['region'])) {
			$rencGeonames = wpGeonames_shortcode(array('map'=>1, 'data'=>$u0->c_pays.'|'.$u0->c_region.'|'.$u0->c_ville.'|'.$u0->e_lat.'|'.$u0->e_lon));
		}
		if(has_filter('rencUserNoLibreP')) $checkbox2 = apply_filters('rencUserNoLibreP', $u0->t_action);
		$accountPlus = false; if(has_filter('rencCheckoutP')) $accountPlus = apply_filters('rencCheckoutP', 1);
		// ****** TEMPLATE ********
		if($tpl=rencTpl('rencontre_account.php')) include($tpl);
		// ************************
		if(has_filter('rencBeforeUserDel')) apply_filters('rencBeforeUserDel', 0);
		// ****** TEMPLATE ********
		if($tpl=rencTpl('rencontre_account_delete.php')) include($tpl);
		// ************************
	}
	//
	static function f_sourire($f) {
		// envoi un sourire a ID=$f
		global $wpdb, $current_user, $rencOpt, $rencCustom, $rencDiv, $rencU0;
		// 1. mon compte : sourireOut
		$mephoto = $rencU0->i_photo;
		$sut = (!empty($rencOpt['nbr']['smileUpdateTime'])?$rencOpt['nbr']['smileUpdateTime']:0);
		$action = $rencU0->action;
		$action['sourireOut']=(isset($action['sourireOut'])?$action['sourireOut']:array());
		$o = "";
		$c = count($action['sourireOut']);
		if($c) {
			foreach($action['sourireOut'] as $k=>$v) {
				if($v['i']==$f) {
					if($sut==1 || $sut==2) $c = $k; // update smile date
					if(!$sut || $sut==2) {
						if($t=rencTranslate('smiw5')) $o = $t;
						else $o = __('Smile already sent','rencontre');
						if(!$sut) return $o;
					}
					break;
				}
			}
		} // deja souri
		if(has_filter('rencLimitedActionP')) apply_filters('rencLimitedActionP', array('smile',1,0));
		$action['sourireOut'][$c]['i'] = ($f+0);
		$action['sourireOut'][$c]['d'] = current_time("Y-m-d");
		$out = json_encode($action);
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$current_user->ID));
		// 2. son compte : sourireIn
		$q = $wpdb->get_var("SELECT
				t_action
			FROM ".$wpdb->prefix."rencontre_users_profil
			WHERE user_id='".$f."'
			LIMIT 1");
		$action = json_decode((empty($q)?'{}':$q),true);
		$action['sourireIn'] = (isset($action['sourireIn'])?$action['sourireIn']:array());
		$c = count($action['sourireIn']);
		$action['sourireIn'][$c]['i'] = ($current_user->ID+0);
		$action['sourireIn'][$c]['d'] = current_time("Y-m-d");
		$out = json_encode($action);
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$f));
		// memo pour mail CRON
		if(!is_dir($rencDiv['basedir'].'/portrait/cache/cron_list/')) mkdir($rencDiv['basedir'].'/portrait/cache/cron_list/');
		if(!file_exists($rencDiv['basedir'].'/portrait/cache/cron_list/'.$f.'.txt') && !empty($rencOpt['mailsmile'])) {
			if($mephoto || empty($rencOpt['mailph'])) {
				$t=fopen($rencDiv['basedir'].'/portrait/cache/cron_list/'.$f.'.txt', 'w');
				fclose($t);
			}
		}
		if($o) return $o;
		else if($t=rencTranslate('smiw6')) return $t;
		else return __('Smile sent','rencontre');
//	[	{"a":"sourireIn","v":[{"i":10,"d":"2013-12-15"},{"i":32,"d":"2013-12-15"}]},
//		{"a":"sourireOut","v":[{"i":15,"d":"2013-12-15"},{"i":28,"d":"2013-12-15"},{"i":41,"d":"2013-12-15"}]},
//		{"a":"contactIn","v":[{"i":8,"d":"2013-12-15"}]},
//		{"a":"contactOut","v":[{"i":17,"d":"2013-12-15"},{"i":18,"d":"2013-12-15"},{"i":19,"d":"2013-12-15"}]},
//		{"a":"visite","v":[{"i":25,"d":"2013-12-15"}]},
//		{"a":"bloque","v":[{"i":50,"d":"2013-12-15"},{"i":51,"d":"2013-12-15"}]}
//	]
//
	}
	//
	static function f_demcont($f) {
		// demander un contact a ID=$f
		global $wpdb, $current_user, $rencOpt, $rencDiv, $rencU0;
		// 1. mon compte : contactOut
		$mephoto = $rencU0->i_photo;
		$action = $rencU0->action;
		$action['contactOut']=(isset($action['contactOut'])?$action['contactOut']:array());
		$c = count($action['contactOut']);
		if($c) {
			foreach($action['contactOut'] as $r) {
				if($r['i']==$f) return __('Contact already requested','rencontre');
			}
		} // deja demande
		if(has_filter('rencLimitedActionP')) apply_filters('rencLimitedActionP', array('contreq',1,0));
		$action['contactOut'][$c]['i'] = ($f+0);
		$action['contactOut'][$c]['d'] = current_time("Y-m-d");
		$out = json_encode($action);
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$current_user->ID));
		// 2. son compte : contactIn
		$q = $wpdb->get_var("SELECT
				t_action
			FROM ".$wpdb->prefix."rencontre_users_profil
			WHERE user_id='".$f."'
			LIMIT 1");
		$action = json_decode((empty($q)?'{}':$q),true);
		$action['contactIn'] = (isset($action['contactIn'])?$action['contactIn']:array());
		$c = count($action['contactIn']);
		$action['contactIn'][$c]['i'] = ($current_user->ID+0);
		$action['contactIn'][$c]['d'] = current_time("Y-m-d");
		$out = json_encode($action);
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$f));
		// memo pour mail CRON
		if(!is_dir($rencDiv['basedir'].'/portrait/cache/cron_list/')) mkdir($rencDiv['basedir'].'/portrait/cache/cron_list/');
		if(!file_exists($rencDiv['basedir'].'/portrait/cache/cron_list/'.$f.'.txt')) {
			if($mephoto || empty($rencOpt['mailph'])) {
				$t=fopen($rencDiv['basedir'].'/portrait/cache/cron_list/'.$f.'.txt', 'w');
				fclose($t);
			}
		}
		return __('Contact request sent','rencontre');
	}
	//
	static function f_signal($f) {
		// envoi un signalement sur ID=$f
		global $wpdb, $current_user, $rencU0;
		// 1. mon compte : sourireOut
		$signal = json_decode((empty($rencU0->t_signal)?'{}':$rencU0->t_signal),true);
		$c = (is_array($signal)?count($signal):0);
		if($c) {
			foreach($signal as $r) {
				if($r['i']==$current_user->ID) return __('Reporting already done','rencontre');
			}
		} // deja signale par mid
		$signal[$c]['i'] = ($current_user->ID+0);
		$signal[$c]['d'] = current_time("Y-m-d");
		$out = json_encode($signal);
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_signal'=>$out), array('user_id'=>$f));
		return __('Thank you for your report','rencontre');
	}
	//
	static function f_bloque($f) {
		// bloque ou debloque ID=$f
		global $wpdb, $current_user, $rencDiv, $rencU0;
		$action = $rencU0->action;
		$action['bloque'] = (isset($action['bloque'])?$action['bloque']:array());
		$c = count($action['bloque']); $c1=0;
		if($c) foreach($action['bloque'] as $r) {
			if($r['i']==$f) { // deja bloque : on debloque
				unset($action['bloque'][$c1]['i']);unset($action['bloque'][$c1]['d']);
				$action['bloque'] = array_filter($action['bloque']);
				$out = json_encode($action);
				$rencU0->action = $action;
				$rencU0->t_action = $out;
				$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$current_user->ID));
				return;
			}
			++$c1;
		}
		// pas bloque : on bloque
		$action['bloque'][$c]['i'] = ($f+0);
		$action['bloque'][$c]['d'] = current_time("Y-m-d");
		$out = json_encode($action);
		$rencU0->action = $action;
		$rencU0->t_action = $out;
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$current_user->ID));
	}
	//
	static function f_etat_bloque($f) {
		// regarde si un membre est bloque
		global $current_user, $rencU0;
		$action = $rencU0->action;
		$action['bloque']=(isset($action['bloque'])?$action['bloque']:array());
		$c = count($action['bloque']); if($c) {foreach($action['bloque'] as $r){if($r['i']==$f) return true; }} // est bloque
		else return false;
	}
	//
	static function f_etat_bloque1($f,$t_action='') {
		// regarde si un membre m a bloque
		global $current_user;
		$action = json_decode((empty($t_action)?'{}':$t_action),true);
		if(empty($action)) {
			global $wpdb;
			$q = $wpdb->get_var("SELECT
					t_action
				FROM ".$wpdb->prefix."rencontre_users_profil
				WHERE user_id='".$f."'
				LIMIT 1");
			$action = json_decode((empty($q)?'{}':$q),true);
		}
		$action['bloque'] = (isset($action['bloque'])?$action['bloque']:array());
		$c = count($action['bloque']);
		if($c) foreach($action['bloque'] as $r) { if($r['i']==$current_user->ID) return true; } // est bloque
		else return false;
	}
	//
	static function f_visite($f) {
		// id : MID visite F - sauvegarde chez F
		global $wpdb, $current_user, $rencOpt;
		$q = $wpdb->get_var("SELECT
				t_action
			FROM ".$wpdb->prefix."rencontre_users_profil
			WHERE user_id='".$f."'
			LIMIT 1");
		$action = json_decode((empty($q)?'{}':$q),true);
		$action['visite'] = (isset($action['visite'])?$action['visite']:array());
		$c = count($action['visite']);
		$m = (!empty($rencOpt['nbr']['action'])?$rencOpt['nbr']['action']:50);
		$vut = (!empty($rencOpt['nbr']['visiteUpdateTime'])?1:0);
		if($c>$m+10) self::f_menage_action($f,$action);
		if($c) { foreach($action['visite'] as $k=>$v) {
			if($v['i']==$current_user->ID) {
				if($vut) {
					$action['visite'][$k]['d'] = current_time("Y-m-d");
					$out = json_encode($action);
					$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$f));
				}
				return;
			}
		}}
		// pas encore vu
		$action['visite'][] = array('i'=>($current_user->ID+0),'d'=>current_time("Y-m-d"));
		$out = json_encode($action);
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$f));
	}
	//
	static function f_favori($f,$g=0) {
		// $f : id - $g : O=>EXISTS?, 1=>DELETE, 2=>ADD
		global $wpdb, $current_user, $rencOpt, $rencU0;
		$b = 0;
		$action = $rencU0->action;
		$action['favori']=(isset($action['favori'])?$action['favori']:array());
		$c = count($action['favori']);
		$m = (!empty($rencOpt['nbr']['action'])?$rencOpt['nbr']['action']:50);
		if($c>$m+50) self::f_menage_action($f,$action);
		if(!$g) {
			if($c) foreach($action['favori'] as $r) { if($r['i']==$f) return 1; }
			return 0;
		}
		if($g==2) { // ADD
			if($c) foreach($action['favori'] as $r) { if($r['i']==$f) return; }
			// new favourite
			$action['favori'][] = array('i'=>($f+0),'d'=>current_time("Y-m-d"));
			$b = 1;
		}
		else if($g==1 && $c) { // DELETE
			foreach($action['favori'] as $k=>$v) {
				if($v['i']==$f) unset($action['favori'][$k]);
				$b = 1;
			}
		}
		if($b==1) {
			$out = json_encode($action);
			$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$current_user->ID));
		}
	}
	//
	static function f_distance($lat,$lon) {
		// distance from me
		global $wpdb, $current_user, $rencCustom;
		$q = $wpdb->get_row("SELECT
				e_lat,
				e_lon
			FROM ".$wpdb->prefix."rencontre_users
			WHERE user_id='".$current_user->ID."'
			LIMIT 1");
		if($q->e_lat!=0 && $q->e_lon!=0 && $lat!=0 && $lon!=0 && $lat!=$q->e_lat && $lon!=$q->e_lon) {
			$d = (floor(sqrt(pow(($q->e_lat-$lat)*60*1.852,2)+pow(($q->e_lon-$lon)*60*1.852*cos(($lat+$q->e_lat) / 2 * 0.0174533),2))));
			echo '<em class="w3-small w3-opacity">'.(!empty($rencCustom['sizeu'])?floor($d*0.62137).' mi ':$d.' km ').__('from my position','rencontre').'</em>';
		}
		return;
	}
	static function f_menage_action($f,$action) {
		// Clean up json action - Limited to 50 elements per item
		global $rencOpt;
		$m = (!empty($rencOpt['nbr']['action'])?$rencOpt['nbr']['action']:50);
		$a = array("sourireIn","sourireOut","contactIn","contactOut","visite","bloque","favori");
		for($v=0; $v<count($a); ++$v) if(!empty($action[$a[$v]])) {
			$c = count($action[$a[$v]]);
			for($w=0; $w<$c-$m; ++$w) {
				unset($action[$a[$v]][$w]['i']);
				unset($action[$a[$v]][$w]['d']);
			}
			if(!empty($action[$a[$v]])) $action[$a[$v]]=array_filter($action[$a[$v]]);
			if(!empty($action[$a[$v]])) $action[$a[$v]]=array_splice($action[$a[$v]],0); // Put in order with new keys
		}
		// old action - V3.7
		if(isset($action['msg'])) unset($action['msg']);
		if(isset($action['search'])) unset($action['search']);
		if(isset($action['prof'])) unset($action['prof']);
		//
		$out = json_encode($action);
		global $wpdb;
		$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$f));
	}
	static function rencGate() {
		// Entry screening
		$ho = false; if(has_filter('rencCheckoutP')) $ho = apply_filters('rencCheckoutP', 0);
		echo $ho;
	}
	static function format_date($f) {
		$d = get_option('date_format');
		$a = strtotime($f);
		if($d && $a) {
			if(time()-$a>157680000) return false; // more than 5 years
			else return date_i18n($d, $a);
		}
		else return $f;		
	}
	static function format_dateTime($f,$g=0) {
		$d = get_option('date_format');
		$h = get_option('time_format');
		$a = strtotime($f);
		if($d && $h && $a) {
			if(!$g) return date_i18n($d, $a).' - '.date_i18n($h, $a);
			else if(date('z',$a)==date('z',current_time('timestamp'))) return date_i18n($h, $a);
			else if(date('z',$a)==(date('z',current_time('timestamp'))-1) || current_time('timestamp')-$a<86400) return __('Yesterday','rencontre').' '.date_i18n($h, $a);
			else return date_i18n($d, $a);
		}
		else return $f;		
	}
} // CLASSE RencontreWidget
//
//
//
class RencontreSidebarWidget extends WP_widget {
 	function __construct() {
		parent::__construct('rencontre-sidebar-widget','Rencontre Sidebar',array('description'=>__('Widget to integrate the Rencontre sidebar in your theme sidebar', 'rencontre'),));
	}
	//
	function widget($arguments, $data) { // Partie Site
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) return; // AJAX CALL (by Rencontre or other plugin...)
		if(current_user_can("administrator")) return;
		global $wpdb, $rencDiv, $rencOpt, $rencCustom, $current_user, $rencDrap, $rencDrapNom, $post, $rencWidg, $rencU0;
		if(empty($rencWidg)) return;
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
		$Lid = (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id');
		$Ledit = (!empty($rencOpt['lbl']['edit'])?$rencOpt['lbl']['edit']:'edit');
		$Lqsearch = (!empty($rencOpt['lbl']['qsearch'])?$rencOpt['lbl']['qsearch']:'qsearch');
		$Lbloque = (!empty($rencOpt['lbl']['bloque'])?$rencOpt['lbl']['bloque']:'bloque');
		$Lagemin = (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin');
		$Lagemax = (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax');
		//
		$mid = $current_user->ID;
		if(isset($data->ID)) $u0 = clone $data;
		else $u0 = clone $rencU0;
		if(empty($u0->c_ip)) return;
		RencontreWidget::f_enLigne($mid,$u0->d_session); // update my d_session if necessary
		$mem = array(); // $mem : zage_min, zage_max, size_min, size_max, weight_min, weight_max, zsex, country, region, city, gps, km, photo, relation
		if(isset($_COOKIE['searchmem'.$mid])) $mem = json_decode((empty(base64_decode($_COOKIE['searchmem'.$mid]))?'{}':base64_decode($_COOKIE['searchmem'.$mid])),true);
		if(!empty($mem['zage_min'])) $u0->i_zage_min = $mem['zage_min'];
		if(!empty($mem['zage_max'])) $u0->i_zage_max = $mem['zage_max'];
		$u0->profilQS1 = (!empty($mem['profilQS1'])?$mem['profilQS1']:'');
		$u0->profilQS2 = (!empty($mem['profilQS2'])?$mem['profilQS2']:'');
		$a = '/portrait/'.floor(($u0->ID)/1000).'/'.Rencontre::f_img(($u0->ID*10).'-mini');
		// Browser Caching Bypass
		$b = 0;
		if(file_exists($rencDiv['basedir'].$a.'.jpg')) $b = filemtime($rencDiv['basedir'].$a.'.jpg');
		$u0->miniPhoto = $rencDiv['baseurl'].$a.'.jpg?'.$b;
		// RETINA
		$u0->miniPhotoRetina = '';
		if(file_exists($rencDiv['basedir'].$a.'@2x.jpg')) $u0->miniPhotoRetina = $rencDiv['baseurl'].$a.'@2x.jpg?'.$b.' 2x';
		// WEBP
		$u0->miniPhotoWebp = '';
		if(file_exists($rencDiv['basedir'].$a.'.webp')) {
			$u0->miniPhotoWebp = $rencDiv['baseurl'].$a.'.webp?'.$b;
			if(file_exists($rencDiv['basedir'].$a.'@2x.webp')) $u0->miniPhotoWebp .= ' 1x,'.$rencDiv['baseurl'].$a.'@2x.webp?'.$b.' 2x';
		}
		//
		$u0->pause = (strpos($u0->t_action.'-',',pause2,')!==false?2:(strpos($u0->t_action.'-',',pause1,')!==false?1:0));
		$m = (!empty($rencOpt['nbr']['lengthName'])?intval($rencOpt['nbr']['lengthName']):50);
		$u0->display_name = substr($u0->display_name,0,$m);
		$action = $u0->action;
		$u0->sourireIn = (isset($action['sourireIn'])?$action['sourireIn']:array());
		$u0->visite = (isset($action['visite'])?$action['visite']:array());
		$u0->contactIn = (isset($action['contactIn'])?$action['contactIn']:array());
		if($u0->i_zsex!=99) $u0->zsex = $u0->i_zsex;
		else $u0->zsex = '('.substr($u0->c_zsex,1,-1).')';
		$u0->homo = (($u0->i_sex==$u0->i_zsex)?1:0); // seulement si genre sans custom
		$u0->country = ($u0->c_pays?$u0->c_pays:($rencOpt['pays']?$rencOpt['pays']:''));
		$u0->age = Rencontre::f_age($u0->d_naissance);
		$a1 = (isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);
		$a2 = (isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);
		$b = (intval($u0->age)?intval($u0->age):intval(($a1+$a2)/2));
		$u0->agemin = (intval($b)-10>$a1?intval($b)-10:$a1);
		$u0->agemax = (intval($b)+10<$a2?intval($b)+10:$a2);
		if(empty($u0->i_zage_min)) $u0->i_zage_min = $u0->agemin;
		if(empty($u0->i_zage_max)) $u0->i_zage_max = $u0->agemax;
		if($u0->i_zage_min) $zmin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$u0->i_zage_min)); else $zmin = 0;
		if($u0->i_zage_max) $zmax = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$u0->i_zage_max)); else $zmax = 0;
		if(!isset($rencDrap) || !$rencDrap) {
			$q = $wpdb->get_results("SELECT
					c_liste_categ,
					c_liste_valeur,
					c_liste_iso
				FROM ".$wpdb->prefix."rencontre_liste
				WHERE
					c_liste_categ='d'
					or (c_liste_categ='p'
						and c_liste_lang='".substr($rencDiv['lang3'],0,2)."')
				");
			$rencDrap = array(); $rencDrapNom = array();
			foreach($q as $r) {
				if(!empty($rencOpt['nbr']['flagPng']) && $r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur; // PNG
				else if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = 'svg/'.strtolower($r->c_liste_iso).'.svg'; // SVG
				else if($r->c_liste_categ=='p') $rencDrapNom[$r->c_liste_iso] = $r->c_liste_valeur;
			}
		}
		$u0->looking = '';
		$u0->forwhat = '';
		if($u0->i_zsex!=99) { 
			if(isset($rencOpt['iam'][$u0->i_zsex])) $u0->looking = $rencOpt['iam'][$u0->i_zsex];
			if(isset($rencOpt['for'][$u0->i_zrelation])) $u0->forwhat = $rencOpt['for'][$u0->i_zrelation];
		}
		else {
			// looking
			$a = explode(',', $u0->c_zsex);
			$as = '';
			foreach($a as $a1) if(isset($rencOpt['iam'][$a1])) $as .= $rencOpt['iam'][$a1] . ', ';
			$u0->looking = substr($as,0,-2);
			// forwhat
			$a = explode(',', $u0->c_zrelation);
			$as = '';
			foreach($a as $a1) if(isset($rencOpt['for'][$a1])) $as .= $rencOpt['for'][$a1] . ', ';
			$u0->forwhat = substr($as,0,-2);
		}
		$find = array( "class"=>"w3-button w3-renc-mebt w3-renc-mebo", "title"=>""); // VAR $find no longer used since V3.6.5
		$Lrenc = (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc');
		$Lmsg = (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg');
		$onClick = array(
			"edit"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Ledit."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($u0->ID,0)."';document.forms['rencMenu'].submit();",
			"sourireIn"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lqsearch."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId('sourireIn',0)."';document.forms['rencMenu'].submit();",
			"visite"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lqsearch."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId('visite',0)."';document.forms['rencMenu'].submit();",
			"contactIn"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lqsearch."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId('contactIn',0)."';document.forms['rencMenu'].submit();",
			"sourireOut"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lqsearch."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId('sourireOut',0)."';document.forms['rencMenu'].submit();",
			"contactOut"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lqsearch."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId('contactOut',0)."';document.forms['rencMenu'].submit();",
			"bloque"=>"document.forms['rencMenu'].elements['".$Lrenc."'].value='".$Lqsearch."';document.forms['rencMenu'].elements['".$Lid."'].value='".rencGetId($Lbloque,0)."';document.forms['rencMenu'].submit();",
			"agemin"=>"f_min(parseInt(this.options[this.selectedIndex].value),'formMonAccueil','".$Lagemin."','".$Lagemax."');",
			"agemax"=>"f_max(parseInt(this.options[this.selectedIndex].value),'formMonAccueil','".$Lagemin."','".$Lagemax."');",
			"country"=>"f_region_select(this.options[this.selectedIndex].value,'".admin_url('admin-ajax.php')."','regionSelectSide');",
			"find"=>"f_quickTrouve();",
			"hideSide"=>"f_hideSideMobile();",
			"msg"=>$rencOpt['home']."?".$Lrenc."=".$Lmsg);
		$smilread = false; $contread = false;
		if(has_filter('rencLimitedActionP')) {
			$smilread = apply_filters('rencLimitedActionP', array('smilread',0,0));
			$contread = apply_filters('rencLimitedActionP', array('contread',0,0));
		}
		if($smilread) $onClick['sourireIn'] = "f_modalWarn('".addslashes($smilread)."')";
		if($contread) $onClick['contactIn'] = "f_modalWarn('".addslashes($contread)."')";
		$hideSideMobile = (isset($_COOKIE["rencNoSideMobile"])?1:0); 
		if(!isset($data->ID)) echo '<div class="widgRencSide">'."\r\n"; // external
		$sideClass = ''; $rs = '';
		if(empty($rencCustom['side'])) {
			$sideClass .= 'w3-third w3-right';
			$rs = ' w3-renc-margin-left-8-lm w3-margin-bottom';
		}
		else $sideClass .= 'w3-margin-top w3-margin-bottom';
		if(strpos($_SESSION['rencontre'].'-','mini')===false) $sideClass .= ' w3-hide-small'; // only on home page
		?>
		<div class="<?php echo $sideClass; ?>">
			<div class="rencSide<?php echo $rs; ?>">
			<?php if(strstr($_SESSION['rencontre'],'paswd')) { ?>
			
				<div id="infoChange" class="w3-panel w3-renc-wabg">
					<div><em><?php _e('Password changed !','rencontre'); ?></em></div>
				</div><!-- .infoChange -->
			<?php }
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_sidebar_top.php')) include($tpl);
			// ************************
			$ho = false; if(has_filter('rencPayLinkP')) $ho = apply_filters('rencPayLinkP', $ho);
			if($ho) echo $ho;
			//
			$blockQSearch = false; if(has_filter('rencLimitedActionP')) $blockQSearch = apply_filters('rencLimitedActionP', array('qsearch',0,0));
			if(!empty($u0->photoreq) || !empty($rencU0->photoreq)) $blockQSearch = __('You have no photo on your profile?','rencontre');
			$profilQuickSearch1 = 0;
			$profilQuickSearch2 = 0;
			if(!empty($rencCustom['profilQS1'])) {
				if(substr($rencCustom['profilQS1'],0,1)=='d' || substr($rencCustom['profilQS1'],0,1)=='n') {
					$qs1 = $wpdb->get_row("SELECT
							c_label,
							t_valeur,
							i_type
						FROM ".$wpdb->prefix."rencontre_profil
						WHERE
							id=".substr($rencCustom['profilQS1'],2)."
							and c_lang='".substr($rencDiv['lang2'],0,2)."'
						LIMIT 1
						");
					if($qs1) {
						$sub = substr($rencCustom['profilQS1'],0,2);
						$s = json_decode((empty($qs1->t_valeur)?'{}':$qs1->t_valeur));
						if($sub==='da') $profilQuickSearch1D = 'after';
						else if($sub==='db') $profilQuickSearch1D = 'before';
						else if($sub==='na') $profilQuickSearch1N = array('min',(!empty($s[0])?$s[0]:0),(!empty($s[1])?$s[1]:1),(!empty($s[2])?$s[2]:1),(!empty($s[3])?$s[3]:'-'));
						else if($sub==='nb') $profilQuickSearch1N = array('max',(!empty($s[0])?$s[0]:0),(!empty($s[1])?$s[1]:1),(!empty($s[2])?$s[2]:1),(!empty($s[3])?$s[3]:'-'));
					}
				}
				else {
					$qs1 = $wpdb->get_row("SELECT
							c_label,
							t_valeur,
							i_type
						FROM ".$wpdb->prefix."rencontre_profil
						WHERE
							id=".$rencCustom['profilQS1']."
							and c_lang='".substr($rencDiv['lang2'],0,2)."'
						LIMIT 1
						");
					if($qs1) {
						$s = json_decode((empty($qs1->t_valeur)?'{}':$qs1->t_valeur));
						$c = 0;
						$profilQuickSearch1 = '<option value="">-</option>';
						foreach($s as $ss) {
							$profilQuickSearch1 .= '<option value="'.$c.'">'.$ss.'</option>'; // type 3 & 4
							++$c;
						}
						if(isset($qs1->i_type) && $qs1->i_type==5) $profilQuickSearch1 = ''; // Fix update 3.9 => 3.10
					}
				}
			}
			if(!empty($rencCustom['profilQS2'])) {
				if(substr($rencCustom['profilQS2'],0,1)=='d' || substr($rencCustom['profilQS2'],0,1)=='n') {
					$qs2 = $wpdb->get_row("SELECT
							c_label,
							t_valeur,
							i_type
						FROM ".$wpdb->prefix."rencontre_profil
						WHERE
							id=".substr($rencCustom['profilQS2'],2)."
							and c_lang='".substr($rencDiv['lang2'],0,2)."'
						LIMIT 1
						");
					if($qs2) {
						$sub = substr($rencCustom['profilQS2'],0,2);
						if($sub==='da') $profilQuickSearch2D = 'after';
						else if($sub==='db') $profilQuickSearch2D = 'before';
						else if($sub==='na') $profilQuickSearch2N = array('min',(!empty($s[0])?$s[0]:0),(!empty($s[1])?$s[1]:1),(!empty($s[2])?$s[2]:1),(!empty($s[3])?$s[3]:'-'));
						else if($sub==='nb') $profilQuickSearch2N = array('max',(!empty($s[0])?$s[0]:0),(!empty($s[1])?$s[1]:1),(!empty($s[2])?$s[2]:1),(!empty($s[3])?$s[3]:'-'));
					}
				}
				else {
					$qs2 = $wpdb->get_row("SELECT
							c_label,
							t_valeur,
							i_type
						FROM ".$wpdb->prefix."rencontre_profil
						WHERE
							id=".$rencCustom['profilQS2']."
							and c_lang='".substr($rencDiv['lang2'],0,2)."'
						LIMIT 1
						");
					if($qs2) {
						$s = json_decode((empty($qs2->t_valeur)?'{}':$qs2->t_valeur));
						$c = 0;
						$profilQuickSearch2 = '<option value="">-</option>';
						foreach($s as $ss) {
							$profilQuickSearch2 .= '<option value="'.$c.'">'.$ss.'</option>'; // type 3 & 4
							++$c;
						}
						if(isset($qs2->i_type) && $qs2->i_type==5) $profilQuickSearch2 = ''; // Fix update 3.9 => 3.10
					}
				}
			}
			// ****** TEMPLATE ********
			if($tpl=rencTpl('rencontre_sidebar_quick_search.php')) include($tpl);
			// ************************
			?>
			
				<div id="rencAdsL" class="rencAds w3-margin-top">
				<?php $ho = false; if(has_filter('rencAdsLP')) $ho = apply_filters('rencAdsLP', $ho); if($ho) echo $ho; ?>
				</div><!-- .rencAds -->
			</div><!-- .rencSide -->
		</div>
		<?php
		if(!isset($data->ID)) echo '</div><!-- .widgRencSide -->'."\r\n".'<div class="clear"></div>'; // external
	}
	//
} // CLASSE RencontreSidebarWidget
//
?>

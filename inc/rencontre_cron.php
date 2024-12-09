<?php
//
// File Included by f_cronasync() in rencontre_filter.php - AJAX
//
function f_cron_on($part=1) {
	// Daily Cleaning
	global $wpdb, $rencOpt, $rencDiv, $rencCustom, $rencBenchmark, $rencWPLANG;
	$CURLANG = get_locale(); // already exists - can be user lang (cookie)
	if($CURLANG!=$rencWPLANG) switch_to_locale($rencWPLANG); // Multilang : emails in ADMIN default Lang
	if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON_ON: '.microtime(true).' - '.PHP_EOL; $rbm = microtime(true); }
	clearstatcache();
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
	$blogName = get_bloginfo('name');
	$cm = 0; // compteur de mail
	$cronBis = 0;
	if($part==5) {
		$cronBis = 1;
		$part = 4;
	}
	if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-1: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
	switch($part) {
		// ********************************************************************************************
		case 1:
		// 1. Efface les _transient dans wp_option
		$wpdb->query("DELETE
			FROM ".$wpdb->prefix."options
			WHERE
				option_name like '\_transient\_namespace\_%'
				OR option_name like '\_transient\_timeout\_namespace\_%'
			");
		// 2. Suppression fichiers anciens dans UPLOADS/PORTRAIT/LIBRE/ : > 30 jours
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-2: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		if(!is_dir($rencDiv['basedir'].'/portrait/libre/')) @mkdir($rencDiv['basedir'].'/portrait/libre/');
		else {
			$tab = array(); $d = $rencDiv['basedir'].'/portrait/libre/';
			if($dh = opendir($d)) {
				while(($file = readdir($dh))!==false) { if($file!='.' && $file!='..') $tab[]=$d.$file; }
				closedir($dh);
				if($tab!=array()) foreach($tab as $r) {
					if(file_exists($r) && filemtime($r)<time()-2592000) @unlink($r); // 2592000 sec = 30 jours
				}
			}
		}
		break;
		// ********************************************************************************************
		case 2:
		// 3. Supprime le cache portraits page d'accueil. Remise a jour a la premiere visite (fiches libre)
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-3: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		renc_clear_cache_portrait();
		// 4. Suppression des utilisateur sans compte rencontre fini
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-4: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
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
		break;
		// ********************************************************************************************
		case 3:
		// 5. Delete the users in rencontre and not in WP
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-5: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
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
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-6: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
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
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-7: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		if(isset($rencOpt['fastreg']) && !$rencOpt['fastreg']) {
			$q = $wpdb->get_results("SELECT U.ID FROM ".$wpdb->base_prefix."users U, ".$wpdb->prefix."rencontre_users R WHERE R.user_id=U.ID and R.i_status IN (4,12) ");
			if($q) foreach($q as $r) {
				$p = md5(mt_rand());
				$wpdb->update($wpdb->base_prefix.'users', array('user_pass'=>$p), array('ID'=>$r->ID)); // confirmation email par demande de nouveau password
				$wpdb->update($wpdb->prefix.'rencontre_users', array('c_ip'=>'', 'i_status'=>0), array('user_id'=>$r->ID)); // procedure inscription 1 a 4
			}
		}
		// 8. Mail de relance (uniquement enregistrement classique)
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-8: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
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
			$mailSubj = '';
			if($q) foreach($q as $u) {
				if(has_filter('rencMailRemind')) apply_filters('rencMailRemind', $u);
				else {
					if(function_exists('openssl_encrypt')) $oo = base64_encode(openssl_encrypt($u->ID.'|'.$u->user_login.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
					$remindUrl = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home']).'?'.$Loo.'='.urlencode($oo).'&'.$Lii.'='.urlencode($ii));
					// ****** TEMPLATE ********
					ob_start();
					if($tpl=rencTpl('rencontre_mail_remind.php')) include $tpl;
					$s = ob_get_clean();
					// ************************
					$s = trim(preg_replace('/\t+/', '', $s)); // remove tab
					$s = preg_replace('/^\s+|\n|\r|\s+$/m', '', $s); // remove line break
					$mailSubj = (!empty($rencOpt['titmailremind'])?$rencOpt['titmailremind']:'');
					$mailSubj = str_replace("[BLOGNAME]", $blogName, $mailSubj);
					$he = array();
					if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
						$he[] = 'From: '.$blogName.' <'.$rencDiv['admin_email'].'>';
						$he[] = 'Content-type: text/html; charset=UTF-8';
						$s = '<html><head></head><body>'.$s.'</body></html>';
					}
					if(empty($mailSubj)) $mailSubj = $blogName." - ".__('Registration','rencontre');
					@wp_mail($u->user_email, $mailSubj, $s, $he);
				}
				++$cm;
			}
		}
		// 9. Suppression fichiers anciens dans UPLOADS/SESSION/ et UPLOADS/TCHAT/ et des exports CSV UPLOADS/TMP
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-9: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		if(!is_dir($rencDiv['basedir'].'/session/')) mkdir($rencDiv['basedir'].'/session/');
		else {
			$tab = array(); $d = $rencDiv['basedir'].'/session/';
			if($dh=opendir($d)) {
				while (($file = readdir($dh))!==false) { if($file!='.' && $file!='..') $tab[] = $d.$file; }
				closedir($dh);
				if($tab!=array()) foreach($tab as $r){if(filemtime($r)<time()-1296000) @unlink($r);} // 15 jours
			}
		}
		if(!is_dir($rencDiv['basedir'].'/tchat/')) mkdir($rencDiv['basedir'].'/tchat/');
		else {
			$tab = array(); $d = $rencDiv['basedir'].'/tchat/';
			if($dh=opendir($d)) {
				while (($file = readdir($dh))!==false) { if($file!='.' && $file!='..') $tab[] = $d.$file; }
				closedir($dh);
				if($tab!=array()) foreach($tab as $r){if(filemtime($r)<time()-86400) @unlink($r);} // 24 heures
			}
		}
		if(is_dir($rencDiv['basedir'].'/tmp/')) {
			$a = array();
			if($h=opendir($rencDiv['basedir']."/tmp/")) {
				while (($file = readdir($h))!==false) {
					$ext = explode('.',$file);
					$ext = $ext[count($ext)-1];
					if($ext=='csv' && $file!='.' && $file!='..' && strpos($file.'-',"rencontre")!==false) $a[] = $rencDiv['basedir']."/tmp/".$file;
				}
				closedir($h);
			}
			// ************************
			if(is_array($a)) array_map('unlink', $a);
		}
		if(is_dir($rencDiv['basedir'].'/tmp/md/')) {
			foreach (glob($rencDiv['basedir'].'/tmp/md/*.*') as $file) if(is_file($file)) unlink($file);
		}
		// 10. Sortie de prison
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-10: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		$free = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-(isset($rencOpt['prison'])?$rencOpt['prison']:7), date("Y")));
		$wpdb->query("DELETE FROM ".$wpdb->prefix."rencontre_prison WHERE d_prison<'".$free."' ");
		// 11. anniversaire du jour
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-11: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
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
				LIMIT ".floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.1)) );
			$mailSubj = '';
			foreach($q as $u) {
				if(has_filter('rencMailBirthday')) apply_filters('rencMailBirthday', $u);
				else {
					// ****** TEMPLATE ********
					ob_start();
					if($tpl=rencTpl('rencontre_mail_birthday.php')) include $tpl;
					$s = ob_get_clean();
					// ************************
					$s = trim(preg_replace('/\t+/', '', $s)); // remove tab
					$s = preg_replace('/^\s+|\n|\r|\s+$/m', '', $s); // remove line break
					$mailSubj = (!empty($rencOpt['titmailbirt'])?$rencOpt['titmailbirt']:'');
					$mailSubj = str_replace("[BLOGNAME]", $blogName, $mailSubj);
					$he = array();
					if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
						$he[] = 'From: '.$blogName.' <'.$rencDiv['admin_email'].'>';
						$he[] = 'Content-type: text/html; charset=UTF-8';
						$s = '<html><head></head><body>'.$s.'</body></html>';
					}
					if(empty($mailSubj)) $mailSubj = $blogName." - ".__('Happy Birthday','rencontre');
					@wp_mail($u->user_email, $mailSubj, $s, $he);
				}
				++$cm;
			}
		}
		// 12. Efface une fois par semaine les statistiques du nombre de mail par heure
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-12: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		if(current_time("N")=="1") { // Monday
			$mph = array($cm,0);
			update_option('rencontre_mailPerHour',$mph);
		}
		break;
		// ********************************************************************************************
		case 4:
		if(has_filter('rencCronMailPart4')) { // Surpass the following part
			apply_filters('rencCronMailPart4', 0);
			return;
		}
		// 13. Mail vers les membres et nettoyage des comptes actions (suppression comptes inexistants)
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-13: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		$hcron = (isset($rencOpt['hcron'])?$rencOpt['hcron']+0:3);
		$j = floor((floor((time()-3600*$hcron)/86400)/60 - floor(floor((time()-3600*$hcron)/86400)/60)) * 60 +.00001); // horloge de jour de 0 à 59 (temps unix) - ex : aujourd'hui -> 4 ; ajout de hcron pour rester le meme jour dans la plage +12h
		if(isset($rencOpt['mailmois']) && $rencOpt['mailmois']==2) {
			$j0 = floor(($j/15-floor($j/15)) * 15 + .00001); // horloge de jour de 0 a 14
			if(!$cronBis) { // CRON (H)
				$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.85)); // 85% du max - heure creuse - 15% restant pour inscription nouveaux membres et anniv
				$j1=$j0+15;
			}
			else { // CRON BIS (H+1)
				$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.95)); // 95% du max - heure creuse - 5% restant pour inscription nouveaux membres
				$j2=$j0+30; $j3=$j0+45;
			}
		}
		else if(isset($rencOpt['mailmois']) && $rencOpt['mailmois']==3) {
			$j0 = floor(($j/7-floor($j/7)) * 7 + .00001); // horloge de jour de 0 a 6
			if(!$cronBis) { // CRON (H)
				$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.85)); // 85% du max - heure creuse - 15% restant pour inscription nouveaux membres et anniv
				$j1=$j0+7; $j2=$j0+14; $j3=$j0+21;
			}
			else { // CRON BIS (H+1)
				$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.95)); // 95% du max - heure creuse - 5% restant pour inscription nouveaux membres
				$j4=$j0+28; $j5=$j0+35; $j6=$j0+42; $j7=$j0+49; $j8=$j0+56;
			}
		}
		else { // ($max needed)
			$jj = ($j>29)?$j-30:$j+30; // aujourd'hui : 34
			if(!$cronBis) $max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.85)); // 85% du max - heure creuse - 15% restant pour inscription nouveaux membres et anniv
			else $max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.95)); // 95% du max - heure creuse - 5% restant pour inscription nouveaux membres
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
					c_liste_categ='d' or (c_liste_categ='p' and c_liste_lang='".substr($rencWPLANG,0,2)."')
				");
			foreach($q as $r) {
				if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur;
			}
		}
		$q = 0;
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
				and R.i_status NOT IN (4,12)
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
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRON-13.2: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); $rbm2 = $rbm; }
		if($q) foreach($q as $u) {
			++$ct;
			$melCountry = (!empty($rencOpt['melctry'])&&!empty($u->c_pays)&&empty($rencCustom['country'])&&empty($rencCustom['place'])?"and R.c_pays='".$u->c_pays."'":"");
			$action = json_decode((empty($u->t_action)?'{}':$u->t_action),true);
			if(!empty($rencOpt['mailmois']) && $ct<=$max) {
				$b = 0;
				// Connect_link
				if(function_exists('openssl_encrypt')) $oo = base64_encode(openssl_encrypt($u->ID.'|'.$u->user_login.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
				// PROPOSITIONS
				$zmin = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$u->i_zage_min));
				$zmax = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")-$u->i_zage_max));
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' Mail'.$ct.' - S0:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				// Selection par le sex
				$sexQuery = '';
				$relQuery = '';
				// 1. SEX
				if($u->i_zsex==99) $sexQuery .= " and R.i_sex IN (".substr($u->c_zsex,1,-1).") ";
				else $sexQuery .= " and R.i_sex=".$u->i_zsex." ";
				// 2. ZSEX
				$sexQuery .= " and (R.i_zsex=".$u->i_sex." or R.c_zsex LIKE '%,".$u->i_sex.",%') "; // multiSR or not (in case of change multiSR by ADMIN but not by users Account)
				// 3. ZRELATION
				if($u->i_zrelation!=99) $relQuery = " and (R.i_zrelation=".$u->i_zrelation." or  R.c_zrelation LIKE '%,".$u->i_zrelation.",%') ";
				else {
					$a = explode(',', $u->c_zrelation);
					foreach($a as $a1) if(strlen($a1)) {
						if(!empty($relQuery)) $relQuery .= " or ";
						$relQuery .= "R.c_zrelation LIKE '%,".$a1.",%'";
					}
					if(!empty($relQuery)) $relQuery = " and (".$relQuery.") ";
				}
				//
			//	$qpause = (empty($rencOpt['paus'])?"and (P.t_action NOT REGEXP ',pause1,|,pause2,' or P.t_action IS NULL) ":""); // REGEXP
				$qpause = (empty($rencOpt['paus'])?"and (P.t_action NOT LIKE '%,pause_,%' or P.t_action IS NULL) ":""); // LIKE
				$selectionQuery = $wpdb->get_results("SELECT 
						U.ID, 
						U.user_login, 
						R.d_naissance, 
						R.c_pays, 
						R.c_ville, 
						P.t_titre
					FROM ".$wpdb->base_prefix."users U
					RIGHT JOIN ".$wpdb->prefix."rencontre_users R
						ON R.user_id=U.ID
					LEFT JOIN ".$wpdb->prefix."rencontre_users_profil P
						ON P.user_id=R.user_id
					WHERE 
						U.ID!='".$u->ID."'
						".$melCountry."
						".((!empty($rencOpt['onlyphoto']) || !empty($rencOpt['mailph']))?" and R.i_photo>0 ":" ")."
						".$sexQuery."
						".$relQuery."
						and R.d_naissance BETWEEN '".$zmax."' AND '".$zmin."' 
						".$qpause."
					ORDER BY U.user_registered DESC
					LIMIT ".(!empty($rencOpt['nbr']['mailSelection'])?$rencOpt['nbr']['mailSelection']:4));
				if($selectionQuery) $b = 1;
				$smilread = false; $contread = false;
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S1:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				if(has_filter('rencLimitedActionP')) {
					$smilread = apply_filters('rencLimitedActionP', array('smilread',0,$u->ID));
					$contread = apply_filters('rencLimitedActionP', array('contread',0,$u->ID));
				}
				// SOURIRES
				if(isset($action['sourireIn']) && count($action['sourireIn'])) {
					$c = 0; $smileQuery = array();
					for($v=0; $v<count($action['sourireIn']);++$v) {
						if(isset($action['sourireIn'][$v]['d']) && strtotime($action['sourireIn'][$v]['d'])>current_time('timestamp',0)-$ti) { // only new before last mail
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
									".$qpause."
								ORDER BY R.d_session DESC
								LIMIT 1
								");
							if($r1) {
								$smileQuery[] = $r1;
								$b = 1;
							}
						}
					}
				}
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S2:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				// DEMANDES DE CONTACT
				if(isset($action['contactIn']) && count($action['contactIn'])) {
					$c = 0; $contactQuery = array();
					for($v=0; $v<count($action['contactIn']);++$v) {
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
									".$qpause."
								ORDER BY R.d_session DESC
								LIMIT 1
								");
							if($r1) {
								$contactQuery[] = $r1;
								$b = 1;
							}
						}
					}
				}
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S3:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				// MESSAGES
				$nbMessage = $wpdb->get_var("SELECT 
						COUNT(*)
					FROM ".$wpdb->prefix."rencontre_msg M
					WHERE
						M.recipient='".$u->user_login."'
						and M.read=0
						and M.deleted=0
					");
				if($nbMessage) $b = 1;
				// MOT DE LA FIN
				$wid = 260 - 32;
				$buttonCSS = "color:".$w3renc[$bt0.'T'].";background-color:".$w3renc[$bt0].";border:none;display:inline-block;width:".$wid."px;padding:8px 16px;vertical-align:middle;overflow:hidden;text-decoration:none;text-align:center;cursor:pointer;white-space:nowrap;font-size:13px;";
				$buttonHover = "this.style.backgroundColor='".$w3renc[$bt1]."';this.style.color='".$w3renc[$bt1.'T']."'";
				$buttonOut = "this.style.backgroundColor='".$w3renc[$bt0]."';this.style.color='".$w3renc[$bt0.'T']."'";
				$buttonLink = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii));
				$mailSubj = '';
				//
				if($b) {
					// ****** TEMPLATE ********
					ob_start();
					if($tpl=rencTpl('rencontre_mail_regular_global.php')) include $tpl;
					$s = ob_get_clean();
					// ************************
					$s = trim(preg_replace('/\t+/', '', $s)); // remove tab
					$s = preg_replace('/^\s+|\n|\r|\s+$/m', '', $s); // remove line break
					$mailSubj = (!empty($rencOpt['titmailmois'])?$rencOpt['titmailmois']:'');
					$mailSubj = str_replace("[BLOGNAME]", $blogName, $mailSubj);
					$he = array();
					if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
						$he[] = 'From: '.$blogName.' <'.$rencDiv['admin_email'].'>';
						$he[] = 'Content-type: text/html; charset=UTF-8';
						$s = '<html><head></head><body>'.$s.'</body></html>';
					}
					if(empty($mailSubj)) $mailSubj = $blogName;
					@wp_mail($u->user_email, $mailSubj, $s, $he);
					++$cm;
				}
				if(file_exists($rencDiv['basedir'].'/portrait/cache/cron_list/'.$u->ID.'.txt')) unlink($rencDiv['basedir'].'/portrait/cache/cron_list/'.$u->ID.'.txt');
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S4:'.(microtime(true)-$rbm2).' ('.$u->user_email.')'; $rbm2 = microtime(true); }
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
				$wpdb->update($wpdb->prefix.'rencontre_users_profil', array('t_action'=>$out), array('user_id'=>$u->ID));
			}
			// ***************************************************
			if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S5:'.(microtime(true)-$rbm2).PHP_EOL; $rbm2 = microtime(true); }

			// MOYENNE BENCH initiale	: S1:0,14583s  S2:0,00024s  S3:0,000087s  S4:0,01864s  S5:0,01033s
			// MOYENNE BENCH LIKE S1	: S1:0,04321s  S2:0,00010s  S3:0,000014s  S4:0,01826s  S5:0,01023s

		}
		if(!empty($rencBenchmark)) { $rencBenchmark .= '...Send email to '.$ct.' members'.PHP_EOL.'F_CRON-14: '.(microtime(true)-$rbm).' - '.PHP_EOL; $rbm = microtime(true); }
		// 14. Suppression des msg anciens
		if(!empty($rencOpt['msgdel'])) {
			$d = array(1=>7884000, 2=>15768000, 3=>31536000, 4=>2592000);
			if(isset($d[$rencOpt['msgdel']])) {
				$d1 = date('Y-m-d H:i:s', time()-$d[$rencOpt['msgdel']]);
				$wpdb->query("DELETE FROM ".$wpdb->prefix."rencontre_msg WHERE date<'".$d1."' ");
			}
		}
		// 15. Premium Cron
		$ho = false; if(has_filter('rencCronP')) $ho = apply_filters('rencCronP', $ho);
		//
		if(current_time("N")!="1") { // N : day of the week - 1 for Monday
			$mph = get_option('rencontre_mailPerHour');
			if(!isset($mph[1])) $mph = array(0,0);
			$mph[0] = max(intval($mph[0]), $cm);
			update_option('rencontre_mailPerHour',$mph);
		}
		if($CURLANG!=$rencWPLANG) echo '<script>document.location.reload(true);</script>'; // restore previous locale after switch_to_locale($rencWPLANG);
		clearstatcache();
		break;
		// ********************************************************************************************
	} // SWITCH
	if(!empty($rencBenchmark)) {
		$rencBenchmark .= 'F_CRON-end: '.(microtime(true)-$rbm).' - '.PHP_EOL.($cronBis?'cronbis':'cron').' - cronPart:'.$part;
		$rencBenchmark .= '- size:'.filesize($rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt').' - Debug:'.strip_tags($_POST['debug']);
		wp_mail( get_option('admin_email'), get_bloginfo('name').' - RENCONTRE BenchMark', $rencBenchmark );
	}
}
//
function f_cron_list() {
	// Hourly mailings within quotas
	if(has_filter('rencCronList')) { // Surpass this function f_cron_list()
		apply_filters('rencCronList', 0);
		return;
	}
	global $wpdb, $rencOpt, $rencDiv, $rencCustom, $rencBenchmark, $rencWPLANG;
	if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRONLIST_ON: '.microtime(true).' - '.PHP_EOL; $rbm = microtime(true); }
	$CURLANG = get_locale(); // already exists - can be user lang (cookie)
	if($CURLANG!=$rencWPLANG) switch_to_locale($rencWPLANG); // Multilang : emails in ADMIN default Lang
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
				c_liste_categ='d' or (c_liste_categ='p' and c_liste_lang='".substr($rencWPLANG,0,2)."') ");
		foreach($q as $r) {
			if($r->c_liste_categ=='d') $rencDrap[$r->c_liste_iso] = $r->c_liste_valeur;
		}
	}
	$max = floor(max(0, (isset($rencOpt['qmail'])?$rencOpt['qmail']:6)*.8)); // 80% du max - 20% restant pour inscription nouveaux membres
	$cm = 0; // compteur de mail
	if($dh = @opendir($rencDiv['basedir'].'/portrait/cache/cron_list/')) {
		// 1. listing des USERS en attente
		$blogName = get_bloginfo('name');
		$lis = '(';
		$fi = Array();
		$c = 0;
		while(($file = readdir($dh))!==false) {
			$lid = explode('.',$file);
			if($file!='.' && $file!='..') {
				if(!preg_match('/^[0-9]+$/',$lid[0])) @unlink($dh.$file);
				else {
					$fi[$c][0] = filemtime($rencDiv['basedir'].'/portrait/cache/cron_list/'.$file); // date - en premier pour le sort
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
		$mailSubj = '';
		$ct = 0;
		if(!empty($rencBenchmark)) { $rencBenchmark .= 'F_CRONLIST-1: '.(microtime(true)-$rbm).' - '.PHP_EOL.'LIST ID:'.$lis.PHP_EOL; $rbm = microtime(true); $rbm2 = $rbm; }
		if($q) foreach($q as $u) { // {"sourireIn":[{"i":992,"d":"2015-09-01"},{"i":75,"d":"2015-09-01"}],"contactIn":[{"i":992,"d":"2015-09-01"}]}
			if(has_filter('rencMailInstant')) apply_filters('rencMailInstant', $u);
			else {
				$b = 0; $smile = 0; $contact = 0; $inbox = 0;
				if(function_exists('openssl_encrypt')) $oo = base64_encode(openssl_encrypt($u->ID.'|'.$u->user_login.'|'.time(), 'AES-256-CBC', substr(AUTH_KEY,0,32), OPENSSL_RAW_DATA, $iv));
				$action= json_decode((empty($u->t_action)?'{}':$u->t_action),true);
				//
				$smilread = false; $contread = false;
				if(has_filter('rencLimitedActionP')) {
					$smilread = apply_filters('rencLimitedActionP', array('smilread',0,$u->ID));
					$contread = apply_filters('rencLimitedActionP', array('contread',0,$u->ID));
				}
				if(!empty($rencBenchmark)) $rencBenchmark .= ' Mail'.$ct;
				if(isset($action['contactIn']) && count($action['contactIn'])) {
					$v = count($action['contactIn'])-1;
					if(isset($action['contactIn'][$v]['d']) && strtotime($action['contactIn'][$v]['d'])>current_time('timestamp',0)-108000) { // 30h
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
							$contact = rencMailBox($r,$rencDrap,$oo,$ii);
							$b = 1;
							if(!empty($rencBenchmark)) $rencBenchmark .= ' *Contact* ';
						}
					}
				}
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - id:'.$u->ID.' - S1:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				//
				if(isset($action['sourireIn']) && count($action['sourireIn'])) {
					$v = count($action['sourireIn'])-1;
					if(isset($action['sourireIn'][$v]['d']) && strtotime($action['sourireIn'][$v]['d'])>current_time('timestamp',0)-108000) { // 30h
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
							$smile = rencMailBox($r,$rencDrap,$oo,$ii);
							$b = 1;
							if(!empty($rencBenchmark)) $rencBenchmark .= ' *Smile* ';
						}
					}
				}
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S2:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				//
				$inbox = $wpdb->get_var("SELECT
						COUNT(*)
					FROM ".$wpdb->prefix."rencontre_msg M
					WHERE
						M.recipient='".$u->user_login."'
						and M.read=0
						and M.deleted=0
					");
				if($inbox) {
					$hrefinbox = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Lidf."=".rencGetId('r0',0)."&".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii));
					$b = 1;
					if(!empty($rencBenchmark)) $rencBenchmark .= ' *InBox* ';
				}
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S3:'.(microtime(true)-$rbm2); $rbm2 = microtime(true); }
				//
				if($b) {
					$wid = 260 - 32;
					$buttonCSS = "color:".$w3renc[$bt0.'T'].";background-color:".$w3renc[$bt0].";border:none;display:inline-block;width:".$wid."px;padding:8px 16px;vertical-align:middle;overflow:hidden;text-decoration:none;text-align:center;cursor:pointer;white-space:nowrap;font-size:13px;";
					$buttonHover = "this.style.backgroundColor='".$w3renc[$bt1]."';this.style.color='".$w3renc[$bt1.'T']."'";
					$buttonOut = "this.style.backgroundColor='".$w3renc[$bt0]."';this.style.color='".$w3renc[$bt0.'T']."'";
					$buttonLink = htmlspecialchars((empty($rencOpt['home'])?site_url():$rencOpt['home'])."?".$Loo."=".urlencode($oo)."&".$Lii."=".urlencode($ii));
					// ****** TEMPLATE ********
					ob_start();
					if($tpl=rencTpl('rencontre_mail_instant.php')) include $tpl;
					$o = ob_get_clean();
					// ************************
					$o = trim(preg_replace('/\t+/', '', $o)); // remove tab
					$o = preg_replace('/^\s+|\n|\r|\s+$/m', '', $o); // remove line break
					$mailSubj = (!empty($rencOpt['titmailinst'])?$rencOpt['titmailinst']:'');
					$mailSubj = str_replace("[BLOGNAME]", $blogName, $mailSubj);
					$he = array();
					if(!empty($rencOpt['mailfo']) || !has_filter('wp_mail_content_type')) {
						$he[] = 'From: '.$blogName.' <'.$rencDiv['admin_email'].'>';
						$he[] = 'Content-type: text/html; charset=UTF-8';
						$o = '<html><head></head><body>'.$o.'</body></html>';
					}
					if(empty($mailSubj)) $mailSubj = $blogName." - ".__('A member contact you','rencontre');
					@wp_mail($u->user_email, $mailSubj, $o, $he);
					++$cm;
				}
				if(!empty($rencBenchmark)) { $rencBenchmark .= ' - S4:'.(microtime(true)-$rbm2).' ('.$u->user_email.')'.PHP_EOL; $rbm2 = microtime(true); }
			}
			$d = filemtime($rencDiv['basedir'].'/portrait/cache/cron_list/'.$u->ID.'.txt');
			if($d>$las) $las = $d;
			@unlink($rencDiv['basedir'].'/portrait/cache/cron_list/'.$u->ID.'.txt');
			++$ct;
		}
		if($ct<($max/2)) {
			$fi = glob($rencDiv['basedir'].'/portrait/cache/cron_list/{,.}*', GLOB_BRACE);
			if($fi) foreach($fi as $f) if(is_file($f)) unlink($f); // empty cron_list Dir
		}
	}
	$mph = get_option('rencontre_mailPerHour');
	if(!isset($mph[1])) $mph = array(0,0);
	$mph[1] = max(intval($mph[1]),$cm);
	update_option('rencontre_mailPerHour',$mph);
	//
	@unlink($rencDiv['basedir'].'/portrait/cache/rencontre_cronOn.txt');
	if($CURLANG!=$rencWPLANG) echo '<script>document.location.reload(true);</script>'; // restore previous locale after switch_to_locale($rencWPLANG);
	clearstatcache();
	if(!empty($rencBenchmark)) {
		$rencBenchmark .= '...Send email to '.$cm.' members'.PHP_EOL.'F_CRONLIST-end: '.(microtime(true)-$rbm).' - size:'.filesize($rencDiv['basedir'].'/portrait/cache/rencontre_cron.txt');
		wp_mail( get_option('admin_email'), get_bloginfo('name').' - RENCONTRE BenchMark', $rencBenchmark );
	}
}
//
function rencMailBox($u,$rencDrap,$oo,$ii) {
	global $rencDiv, $rencOpt, $rencCustom;
	$Loo = (!empty($rencOpt['lbl']['rencoo'])?$rencOpt['lbl']['rencoo']:'rencoo');
	$Lii = (!empty($rencOpt['lbl']['rencii'])?$rencOpt['lbl']['rencii']:'rencii');
	$Lidf = (!empty($rencOpt['lbl']['rencidfm'])?$rencOpt['lbl']['rencidfm']:'rencidfm');
	if(file_exists($rencDiv['basedir'].'/portrait/'.floor($u->ID/1000).'/'.Rencontre::f_img(($u->ID*10).'-libre',2).'.jpg')) $u->photoUrl = $rencDiv['baseurl'].'/portrait/'.floor($u->ID/1000).'/'.Rencontre::f_img(($u->ID*10).'-libre',2).'.jpg'; // 2 : no filter (current_user != dest user)
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
	if($tpl=rencTpl('rencontre_mail_regular.php')) include $tpl;
	$o = ob_get_clean();
	// ************************
	$o = trim(preg_replace('/\t+/', '', $o)); // remove tab
	$o = preg_replace('/^\s+|\n|\r|\s+$/m', '', $o); // remove line break
	return $o;
}
//
?>

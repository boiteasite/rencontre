<?php
// ******************************************************************************************************************
// Short loop AJAX call file for the chat. Much faster than using admin-ajax.php
// Pilot : rencontre.js
// ******************************************************************************************************************
//
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {die;} // ajax request
if(isset($_POST['tchat'])) {
	if(empty($_SESSION)) session_start();
	if(empty($_POST['c']) || empty($_SESSION['rencTokc']) || $_SESSION['rencTokc']!==$_POST['c']) die;
	$tc = preg_replace("/[^a-z]+/i", "", $_POST['tchat']);
	if(isset($_POST['fm'])) $fm = intval($_POST['fm']);
	if(isset($_POST['to'])) $to = intval($_POST['to']);
	if(isset($_POST['msg'])) $ms = strip_tags(trim($_POST['msg']));
	if(isset($_POST['d'])) $base = filter_var(strip_tags($_POST['d']),FILTER_SANITIZE_URL).'/';
	else $base = '../../../uploads/';
	$d = $base.'tchat/';
	switch($tc) {
		// ********************************************************************************************
		case 'tchatveille':
		if(!file_exists($d.$fm.'.txt')) {  // init de mon txt
			$t = fopen($d.$fm.'.txt', 'w'); fclose($t);
			echo null;
		}
		else if(filesize($d.$fm.'.txt')==0) {
			if(isset($_SESSION['tchat'])) unset($_SESSION['tchat']);
			$t = fopen($base.'session/'.$fm.'.txt', 'w'); fclose($t); // Refresh session
			echo null;
		}
		else {
			$t = fopen($d.$fm.'.txt', 'r'); $r = fread($t, 15); fclose($t);
			$to = substr($r,1,(strpos($r,']')-1));
			if(!file_exists($d.$to.'.txt') || filesize($d.$to.'.txt')==0) { // old tchat
				if(isset($_SESSION['tchat'])) unset($_SESSION['tchat']);
				$t = fopen($d.$fm.'.txt', 'w'); fclose($t);
				echo null;
			}
			else {
				$t = fopen($d.$to.'.txt', 'r'); $r = fread($t, 15); fclose($t);
				if($r=='['.$fm.']') echo null;
				echo $to;
			}
		}
		break;
		// ********************************************************************************************
		case 'tchatbutton': // Is chat available or already active
		if((!file_exists($d.$fm.'.txt') || filesize($d.$fm.'.txt')==0) && (!file_exists($d.$to.'.txt') || filesize($d.$to.'.txt')==0)) echo 1;
		else echo null;
		break;
		// ********************************************************************************************
		case 'tchatdebut': // mon ID dans les deux txt
		if(@filesize($d.$fm.'.txt')===0 && @filesize($d.$to.'.txt')===0) {
			$_SESSION['tchat'] = $to;
			$t = fopen($d.$fm.'.txt', 'wb'); fwrite($t,'['.$fm.']',15); fclose($t);
			$t = fopen($d.$to.'.txt', 'wb'); fwrite($t,'['.$fm.']',15); fclose($t);
		}
		clearstatcache();
		break;
		// ********************************************************************************************
		case 'tchatfin':  // vide les deux txt
		if(filesize($d.$fm.'.txt')>0) {
			if(isset($_SESSION['tchat'])) unset($_SESSION['tchat']);
			$t = fopen($d.$to.'.txt', 'w'); fclose($t);
			$t = fopen($d.$fm.'.txt', 'w'); fclose($t);
			clearstatcache();
		}
		break;
		// ********************************************************************************************
		case 'tchatok': // accepte le tchat - mon ID dans txt du demandeur
		$_SESSION['tchat'] = $to;
		$t = fopen($base.'session/'.$fm.'.txt', 'w'); fclose($t);
		$t = fopen($d.$to.'.txt', 'wb'); fwrite($t,'['.$fm.']-',15); fclose($t); // '-' pour > size (scrute)
		@copy('../images/no-photoCam.jpg', $base.'tchat/cam'.$fm.'-'.$to.'.jpg');
		@copy('../images/no-photoCam.jpg', $base.'tchat/cam'.$to.'-'.$fm.'.jpg');
		clearstatcache();
		break;
		// ********************************************************************************************
		case 'tchatdemveille': // La demande est elle toujours valable ?
		if(filesize($d.$fm.'.txt')==0) echo 1;
		else echo null;
		break;
		// ********************************************************************************************
		case 'tchatscrute':
		if(time()-filemtime($base.'session/'.$fm.'.txt')>5) {
			$t = fopen($base.'session/'.$fm.'.txt', 'w'); fclose($t); // raffraichissement de ma session 5 sec
		}
		if(!file_exists($d.$fm.'.txt') || filesize($d.$fm.'.txt')===0) {
			if(isset($_SESSION['tchat'])) unset($_SESSION['tchat']);
			echo "::".$fm."::"; // fin du chat =>JS f_tchat_off()
			$t = fopen($d.$to.'.txt', 'r'); $r=fread($t, 15); fclose($t);
			if($r=='['.$fm.']') {
				fopen($d.$to.'.txt', 'w'); fclose($t);
			}
		}
		else if(!file_exists($base.'session/'.$to.'.txt') || time()-filemtime($base.'session/'.$to.'.txt')>17) { // sa session >17 sec : fin sauf demande
			$t = fopen($d.$fm.'.txt', 'r'); $r=fread($t, 15); fclose($t);
			$r = substr($r,1,(strpos($r,']')-1));
			if($r!=$fm || time()-filemtime($base.'session/'.$to.'.txt')>60) { // fin si session to > 60 (voir f_en_ligne() rencontre_widget.php)
				if(isset($_SESSION['tchat'])) unset($_SESSION['tchat']);
				echo "::".$fm."::"; // fin du chat sauf si demande en cours (fm dans mon fichier fm)
				$t = fopen($d.$fm.'.txt', 'w'); fclose($t);
				$t = fopen($d.$to.'.txt', 'r'); $r=fread($t, 15); fclose($t);
				if($r=='['.$fm.']') {
					$t = fopen($d.$to.'.txt', 'w'); fclose($t);
				}
			}
			else echo null;
			clearstatcache();
		}
		else if(filesize($d.$fm.'.txt')>strlen('[]'.$to)) {
			$t = fopen($d.$fm.'.txt', 'r'); $r = fread($t, filesize($d.$fm.'.txt')); fclose($t); // lecture de ma boite
			if(substr($r,1,(strpos($r,']')-1))!=$fm) { // vide ma boite
				$t = fopen($d.$fm.'.txt', 'w'); fwrite($t,'['.$to.']',15); fclose($t);
				if(file_exists($base.'tchat/cam'.$to.'-'.$fm.'.jpg') && $r!='['.$to.']-') echo stripslashes($r.chr(127)); // cam chez autre
				else echo stripslashes($r);
			}
			else echo null;
			clearstatcache();
		}
		else echo null;
		break;
		// ********************************************************************************************
		case 'tchatenvoi':
		if(!is_dir($d)) mkdir($d); $r = "";
		if(filemtime($d.$to.'.txt')>filemtime($d.$fm.'.txt')) { // envoi mon message en conservant le precedant - pas encore de reponse : deux messages de suite
			$t = fopen($d.$to.'.txt', 'a+'); fwrite($t,'['.$fm.']'.$ms); fclose($t);
		}
		else { // au moins une reponse
			if(filesize($d.$fm.'.txt')>0) {
				$t = fopen($d.$fm.'.txt', 'r'); $r = fread($t, filesize($d.$fm.'.txt')); fclose($t); // lecture de ma boite
				$t = fopen($d.$fm.'.txt', 'wb'); fwrite($t,'['.$to.']'); fclose($t); // vide ma boite
			}
			$t = fopen($d.$to.'.txt', 'wb'); fwrite($t,'['.$fm.']'.$ms); fclose($t); // envoi mon message
		}
		echo $r;
		clearstatcache();
		break;
		// ********************************************************************************************
		case 'cam': // sauvegarde de l image de la webcam
		$im = str_replace(" ","+",strip_tags($_POST['image']));
		$im = substr($im, 1+strrpos($im, ','));
		if(strip_tags($_POST['id'])) {
			file_put_contents($d."cam".strip_tags($_POST['id']).".jpg", base64_decode($im));
			clearstatcache();
		}
		break;
		// ********************************************************************************************
		case 'session': // update session if chat disabled
		if(is_file($base.'session/'.$fm.'.txt') && time()-filemtime($base.'session/'.$fm.'.txt')>20) {
			$t = fopen($base.'session/'.$fm.'.txt', 'w'); fclose($t);
		}
		break;
		// ********************************************************************************************
	}
	session_write_close();
}
else die;
//
?>

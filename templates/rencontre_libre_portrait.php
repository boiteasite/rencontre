<?php
/*
 * Plugin : Rencontre
 * Template : Libre Portrait
 * Last Change : Rencontre 3.0
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_libre_portrait.php
 * $u : ID, display_name, user_registered, i_sex, i_zsex, c_pays, c_ville, d_naissance, i_photo, t_titre, t_annonce, titre, annonce, libreID, librePhoto, genre
*/
?>

	<div class="rencLibrePortrait <?php if(!isset($rencCustom['libreAd'])) echo 'ad '; ?><?php echo $u->genre; ?> w3-padding-small w3-col" style="width:<?php echo $photoWidth; ?>px">
		<div class="w3-card w3-white">
			<?php if(!empty($rencCustom['reglink'])) { ?>
		
			<a href="<?php echo $rencCustom['reglink']; ?>">
			<?php } else { ?>

			<a href="<?php echo $rencDiv['siteurl'].'/wp-login.php?action=register'; ?>">
			<?php } ?>
			
				<img class="w3-hover-grayscale" src="<?php echo $u->librePhoto; ?>" style="width:100%" alt="<?php echo $u->display_name; ?>" />
			<?php if(empty($rencCustom['librePhoto'])) { ?>

				<div class="w3-container w3-center" style="white-space:nowrap;">
					<div class="w3-large"><?php echo $u->display_name; ?></div>
					<?php if(empty($rencCustom['place'])) { ?>
					
					<div><?php echo $u->c_ville; ?></div>
					<?php } ?>
					<div class="w3-small" style="line-height:1.4em;height:4.2em;overflow:hidden;"><?php echo $u->title; ?></div>
				</div>
			<?php } ?>
			
			</a>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

<?php
/*
 * Plugin : Rencontre
 * Template : Libre SEARCH Portrait
 * Last Change : Rencontre 3.6.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_libre_search_portrait.php
 * $u : U.ID, display_name, i_sex, c_pays, c_ville, d_naissance, i_photo, t_titre, title, miniPhoto
*/
?>

	<div class="libreSearchPortrait <?php if(!isset($rencCustom['libreAd'])) echo 'ad '; ?><?php echo $u->genre; ?> w3-padding-small w3-col" style="max-width:<?php echo $photoWidth; ?>px">
		<div class="w3-card w3-white">
			<?php if(!empty($rencCustom['reglink'])) { ?>
		
			<a href="<?php echo $rencCustom['reglink']; ?>">
			<?php } else { ?>

			<a href="<?php echo esc_url(wp_registration_url()); ?>">
			<?php } ?>
			
				<img class="w3-hover-grayscale" src="<?php echo $u->librePhoto; ?>" style="width:<?php echo $photoWidth; ?>px" alt="<?php echo $u->display_name; ?>" />
				<div class="w3-container w3-center" style="white-space:nowrap;">
					<div class="w3-large"><?php echo $u->display_name; ?></div>
					<?php if(empty($rencCustom['place'])) { ?>
					
					<div><?php echo $u->c_ville; ?></div>
					<?php } ?>
					<div class="w3-small" style="line-height:1.4em;height:4.2em;overflow:hidden;"><?php echo $u->title; ?></div>
				</div>
			</a>
		</div><!-- .w3-card -->
	</div>

<?php
/*
 * Plugin : Rencontre
 * Template : Libre Portrait
 * Last Change : Rencontre 3.8.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_libre_portrait.php
 * Call : rencontre.php => f_ficheLibre()
 * $u : ID, display_name, user_registered, i_sex, i_zsex, c_pays, c_ville, d_naissance, i_photo, t_titre, t_annonce, titre, annonce, libreID, librePhoto, genre
*/
?>

	<div class="rencLibrePortrait <?php echo $u->genre; ?> w3-padding-small w3-col">
		<div class="w3-card w3-white"  style="overflow:hidden">
			<?php if(!empty($rencCustom['reglink'])) { ?>
		
			<a href="<?php echo $rencCustom['reglink']; ?>">
			<?php } else { ?>

			<a href="<?php echo $redirect; ?>">
			<?php } ?>
			<?php if(!empty($u->librePhotoWebp)) { ?>
			
				<picture class="w3-hover-grayscale">
					<source srcset="<?php echo $u->librePhotoWebp; ?>" type="image/webp" style="width:100%;max-width:<?php echo $photoWidth; ?>px;" alt="<?php echo $u->display_name; ?>">
					<source srcset="<?php echo $u->librePhoto; ?>" type="image/jpeg" style="width:100%;max-width:<?php echo $photoWidth; ?>px;" alt="<?php echo $u->display_name; ?>"> 
					<img src="<?php echo $u->librePhoto; ?>" srcset="<?php echo $u->librePhotoRetina; ?>" style="width:100%" loading="lazy" alt="<?php echo $u->display_name; ?>" />
				</picture>
			<?php } else { ?>
			
				<img class="w3-hover-grayscale" src="<?php echo $u->librePhoto; ?>" srcset="<?php echo $u->librePhotoRetina; ?>" style="width:100%;max-width:<?php echo $photoWidth; ?>px;" loading="lazy" alt="<?php echo $u->display_name; ?>" />
			<?php } ?>
			<?php if(empty($rencCustom['librePhoto'])) { ?>

				<div class="w3-container w3-center" style="white-space:nowrap;">
					<div class="w3-large"><?php echo ($u->display_name?$u->display_name:'&nbsp;'); ?></div>
					<?php if(empty($rencCustom['place'])) { ?>
					
					<div><?php echo ($u->c_ville?$u->c_ville:'&nbsp;'); ?></div>
					<?php } ?>
					<?php if(!isset($rencCustom['libreAd'])) { ?>
					
					<div class="w3-small" style="line-height:1.4em;height:4.2em;overflow:hidden;"><?php echo ($u->title?$u->title:'&nbsp;'); ?></div>
					<?php } ?>
					
				</div>
			<?php } ?>
			
			</a>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

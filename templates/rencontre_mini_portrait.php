<?php
/*
 * Plugin : Rencontre
 * Template : Mini Portrait
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mini_portrait.php
 * Call : rencontre_widget.php => f_miniPortrait()
 * $u : ID, display_name, c_pays, c_ville, d_naissance, i_photo, t_titre, online, miniPhoto, miniPhotoWebp
*/
?>
	<div class="rencMiniPortrait w3-padding-small w3-col <?php if(!empty($highlight)) echo 'highlight'; ?>">
		<div class="w3-card w3-renc-blbg <?php if(!empty($highlight)) echo 'w3-renc-hili'; ?>">
			<div class="w3-renc-container-lm">
				<div class="w3-row">
				<?php if($u->i_photo!=0) { ?>

					<div class="w3-col w3-margin-top w3-renc-margin-top-8-s w3-display-container" style="width:<?php echo $photoWidth; ?>px;">
					<?php if(!empty($onClick['profile'])) { ?>
					
						<a href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
					<?php } ?>
					
						<?php if(!empty($u->miniPhotoWebp)) { ?>
						
							<picture <?php echo $title['thumb']; ?>>
								<source class="w3-circle" srcset="<?php echo $u->miniPhotoWebp; ?>" type="image/webp" style="width:<?php echo $photoWidth; ?>px;" alt="<?php echo $u->display_name; ?>">
								<source class="w3-circle" srcset="<?php echo $u->miniPhoto; ?>" type="image/jpeg" style="width:<?php echo $photoWidth; ?>px;" alt="<?php echo $u->display_name; ?>"> 
								<img class="w3-circle" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" style="width:<?php echo $photoWidth; ?>px;" alt="<?php echo $u->display_name; ?>" />
							</picture>
						<?php } else { ?>
			
							<img class="w3-circle" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" style="width:<?php echo $photoWidth; ?>px;" alt="<?php echo $u->display_name; ?>" <?php echo $title['thumb']; ?> />
						<?php } ?>
						
					<?php if(!empty($onClick['profile'])) { ?>
					
						</a>
					<?php } ?>
					<?php if(!empty($certified)) echo $certified; ?>
					
					</div>
				<?php } else { ?>

					<div class="w3-col w3-margin-top w3-renc-margin-top-8-s w3-display-container" style="width:<?php echo $photoWidth; ?>px;">
						<a href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
							<img class="w3-circle" style="width:<?php echo $photoWidth; ?>px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" />
						</a>
					</div>
				<?php } ?>
					<div class="w3-rest w3-renc-container-lm w3-padding w3-renc-padding-8-s" style="white-space:nowrap;text-transform:capitalize;">
						<div class="w3-large w3-renc-medium-s"><?php echo $u->display_name; ?></div>
						<?php if(!isset($rencCustom['born']) && strpos($u->d_naissance.'-','0000')===false) { ?>
						
						<div class="w3-renc-small-s"><?php echo Rencontre::f_age($u->d_naissance).' '.__('years','rencontre'); ?></div>
						<?php } ?>
						<?php if(!isset($rencCustom['place'])) { ?>
						
						<div class="w3-renc-small-s"><?php echo $u->c_ville; ?> </div>
						<?php } ?>
					</div>
				</div>
				<div class="w3-medium w3-renc-txtc w3-border-top w3-renc-line w3-rest w3-hide-small" style="line-height:1.4em;height:4.2em;"><?php echo stripslashes($u->t_titre); ?></div>
			</div>
		<?php if(!empty($onClick['profile'])) { ?>
		
			<div class="w3-button w3-block w3-renc-sebg" onClick="<?php echo $onClick['profile']; ?>"><?php _e('Profile','rencontre'); ?>...
			<?php if(!empty($u->online)){ ?>
				<span class="w3-right w3-renc-inlb w3-badge" title="<?php _e('online','rencontre'); ?>">&nbsp;</span>

			<?php } ?>
			</div>
		<?php } else { ?>
			
			<div class="w3-button w3-block w3-renc-sebg w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['profile']); ?>')" title="<?php echo $title['profile']; ?>"><?php _e('Profile','rencontre'); ?>...</div>
		<?php } ?>

		</div>
	</div>

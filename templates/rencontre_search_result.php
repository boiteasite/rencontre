<?php
/*
 * Plugin : Rencontre
 * Template : Search Result
 * Last Change : Rencontre 3.13
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_search_result.php
 * Call : rencontre_widget.php => f_quickFind(), f_trouver()
 * $u : user_login, user_id, d_naissance, i_sex, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, e_lat, e_lon, d_session, t_annonce, t_profil, t_action, looking, forwhat, hidephoto, online, online_ago, miniPhoto, miniPhotoWebp
*/
?>

	<div class="rencSearchResult w3-margin-bottom">
		<div class="w3-card w3-renc-blbg <?php if(!empty($highlight)) echo 'w3-renc-hili'; ?>">
			<div class="w3-container">
				<div class="w3-row w3-border-bottom w3-renc-line w3-section">
					<div class="w3-half">
						<div class="w3-cell-row">
						<?php if($u->i_photo!=0) { ?>

							<div class="w3-cell w3-cell-middle w3-display-container" style="width:60px;" onClick="<?php echo $onClick['profile']; ?>">
							<?php if(!empty($u->miniPhotoWebp)) { ?>
							
								<picture <?php echo $u->thumb; ?>>
									<source class="w3-circle" srcset="<?php echo $u->miniPhotoWebp; ?>" type="image/webp" alt="<?php echo $u->display_name; ?>">
									<source class="w3-circle" srcset="<?php echo $u->miniPhoto; ?>" type="image/jpeg" alt="<?php echo $u->display_name; ?>"> 
									<img class="w3-circle" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" alt="<?php echo $u->display_name; ?>" />
								</picture>
							<?php } else { ?>
			
								<img class="w3-circle" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" alt="<?php echo $u->display_name; ?>" <?php echo $u->thumb; ?> />
							<?php } ?>
							<?php if(!empty($certified)) echo $certified; ?>
								
							</div>
						<?php } else { ?>

							<div class="w3-cell w3-cell-middle">
								<img class="w3-circle" style="width:60px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" />
							</div>
						<?php } ?>
							<div class="w3-cell w3-cell-middle w3-padding-small" style="text-transform:capitalize;">
								<div class="w3-large" onClick="<?php echo $onClick['profile']; ?>"><?php echo $u->display_name; ?></div>
								<?php if(!isset($rencCustom['born']) && strpos($u->d_naissance.'-','0000')===false) { ?>

								<div>
									<?php if(isset($rencCustom['sex']) && isset($rencOpt['iam'][$u->i_sex])) echo $rencOpt['iam'][$u->i_sex].' - '; ?>
									<?php echo Rencontre::f_age($u->d_naissance).' '.__('years','rencontre'); ?>
								</div>
								<?php } ?>
								<?php if(!isset($rencCustom['place'])) { ?>

								<div><?php echo $u->c_ville; ?></div>
								<?php } ?>
							</div>
						</div>
					</div><!-- .w3-half -->
					<div class="w3-half">
					<?php if(empty($rencCustom['searchAd'])) { ?>

						<div class="looking">
							<?php if($u->looking) { ?>
							<?php _e('I\'m looking for','rencontre'); ?> <span><?php echo $u->looking; ?></span>
							
							<br />
							<?php } ?>
							<?php if(!isset($rencCustom['born']) && $u->i_zage_min) { ?>
							<span style="text-transform:capitalize;"><?php _e('between','rencontre'); ?> </span><span><?php echo $u->i_zage_min; ?></span> <?php _e('and','rencontre'); ?><span> <?php echo $u->i_zage_max; ?> </span><?php _e('years','rencontre')?>
							
							<br />
							<?php } ?>
							<?php if($u->forwhat) { ?>
							<span style="text-transform:capitalize;"><?php _e('for','rencontre'); ?> </span><span><?php echo $u->forwhat; ?></span>
							<?php } ?>

						</div><!-- .looking -->
					<?php } ?>
					<?php if(empty($rencCustom['searchAd'])) { ?>
						<?php if($u->date) { ?>
						
						<div class="rencDate w3-right-align w3-opacity" style="text-transform:capitalize;"><?php echo __('The','rencontre').' '.$u->date; ?></div>
						<?php } ?>
						<?php if($u->online===true) { ?>
						
						<div class="rencInline w3-right-align"><span class="dotInline"></span><span class="w3-renc-inlc" style="text-transform:capitalize;"><?php _e('online','rencontre'); ?></span></div>
						<?php } else if($u->online_ago) { ?>
						
						<div class="rencOutline w3-right-align w3-opacity"><span class="dotOutline"></span><span style="text-transform:capitalize;"><?php _e('online','rencontre'); ?></span><?php echo __(': ','rencontre').$u->online_ago; ?></div>
						<?php } ?>
					<?php } ?>

					</div><!-- .w3-half -->
				</div><!-- .w3-row -->
				<div class="annonce w3-section w3-renc-txtc" style="word-break:break-word">
					<?php echo stripslashes($u->t_annonce); ?>

					<div class="w3-right-align"><?php echo $searchAdd1; ?></div>
				</div><!-- .annonce -->
				<div>
				<?php if(!$disable['send']) { ?>
				
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['send']; ?>"><i class="far fa-envelope"></i> <?php _e('Send a message','rencontre');?></button> 
				<?php } else { ?>
					
					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['send']); ?>')" title="<?php echo $title['send']; ?>"><i class="far fa-envelope"></i> <?php _e('Send a message','rencontre');?></button> 
				<?php } ?>
				<?php if(!isset($rencCustom['smile'])) { ?>
					<?php if(!$disable['smile']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['smile']; ?>"><i class="far fa-grin-wink"></i> <?php if($t=rencTranslate('smiw1')) echo $t; else _e('Smile','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['smile']); ?>')" title="<?php echo $title['smile']; ?>"><i class="far fa-grin-wink"></i> <?php if($t=rencTranslate('smiw1')) echo $t; else _e('Smile','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(!$disable['profile']) { ?>
				
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['profile']; ?>"><i class="far fa-address-card"></i> <?php _e('Profile','rencontre'); ?></button> 
				<?php } else { ?>
					
					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['profile']); ?>')" title="<?php echo $title['profile']; ?>"><i class="far fa-address-card"></i> <?php _e('Profile','rencontre');?></button> 
				<?php } ?>

				</div>
			</div><!-- .w3-container --> 
		</div><!-- .w3-card -->
	</div><!-- .rencSearchResult -->
	<div class="w3-clear"></div>

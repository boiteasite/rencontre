<?php
/*
 * Plugin : Rencontre
 * Template : Search Result
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_search_result.php
 * $u : user_login, user_id, d_naissance, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, e_lat, e_lon, d_session, t_annonce, t_profil, t_action, looking, forwhat, hidephoto, online, online_ago
*/
?>

	<div class="rencSearchResult w3-container w3-padding-small">
		<div class="w3-card w3-renc-blbg">
			<div class="w3-container">
				<div class="w3-row w3-border-bottom w3-renc-line w3-section">
					<div class="w3-half">
						<div class="w3-cell-row">
						<?php if($u->i_photo!=0) { ?>

							<div class="w3-cell w3-cell-middle w3-display-container" style="width:60px;">
								<img class="w3-circle" src="<?php echo $u->miniPhoto; ?>" alt="<?php echo $u->display_name; ?>" <?php echo $u->thumb; ?> />
								<?php if(!empty($certified)) echo $certified; ?>
								
							</div>
						<?php } else { ?>

							<div class="w3-cell w3-cell-middle">
								<img class="w3-circle" style="width:60px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" />
							</div>
						<?php } ?>
							<div class="w3-cell w3-cell-middle w3-padding-small" style="text-transform:capitalize;">
								<div class="w3-large"><?php echo $u->display_name; ?></div>
								<?php if(!isset($rencCustom['born']) && strpos($u->d_naissance,'0000')===false) { ?>

								<div><?php echo Rencontre::f_age($u->d_naissance); ?>&nbsp;<?php _e('years','rencontre'); ?></div>
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
						<?php if($u->i_zsex!=99) { ?>
							<?php if($u->looking) { ?>
							<?php _e('I\'m looking for','rencontre'); ?>&nbsp;<span><?php echo $u->looking; ?></span>
							
							<br />
							<?php } ?>
							<?php if(!isset($rencCustom['born']) && $u->i_zage_min) { ?>
							<span style="text-transform:capitalize;"><?php _e('between','rencontre'); ?></span>&nbsp;<span><?php echo $u->i_zage_min; ?></span>&nbsp;<?php _e('and','rencontre'); ?>&nbsp;<span><?php echo $u->i_zage_max; ?></span>&nbsp;<?php _e('years','rencontre')?>
							
							<br />
							<?php } ?>
							<?php if($u->forwhat) { ?>
							<span style="text-transform:capitalize;"><?php _e('for','rencontre'); ?></span>&nbsp;<span><?php echo $u->forwhat; ?></span>
							<?php } ?>
						<?php } ?>

						</div><!-- .looking -->
					<?php } ?>
					<?php if(empty($rencCustom['searchAd'])) { ?>
						<?php if($u->date) { ?>
						
						<div class="rencDate w3-right-align w3-opacity" style="text-transform:capitalize;"><?php _e('The','rencontre'); ?>&nbsp;<?php echo $u->date; ?></div>
						<?php } ?>
						<?php if($u->online) { ?>
						
						<div class="rencDate w3-right-align w3-opacity"><span style="text-transform:capitalize;"><?php _e('online','rencontre'); ?></span>&nbsp;:&nbsp;<?php echo $u->online_ago; ?></div>
						<?php } ?>
					<?php } ?>

					</div><!-- .w3-half -->
				</div><!-- .w3-row -->
				<div class="annonce w3-section w3-renc-txtc">
					<?php echo stripslashes($u->t_annonce); ?>

					<div class="w3-right-align"><?php echo $searchAdd1; ?></div>
				</div><!-- .annonce -->
				<div>
				<?php if(!$disable['send']) { ?>
				
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['send']; ?>"><i class="far fa-envelope"></i>&nbsp;<?php _e('Send a message','rencontre');?></button> 
				<?php } else { ?>
					
					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['send']; ?>"><i class="far fa-envelope"></i>&nbsp;<?php _e('Send a message','rencontre');?></button> 
				<?php } ?>
				<?php if(!isset($rencCustom['smile'])) { ?>
					<?php if(!$disable['smile']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['smile']; ?>"><i class="far fa-grin-wink"></i>&nbsp;<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) echo stripslashes($rencCustom['smiw1']); else _e('Smile','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['smile']; ?>"><i class="far fa-grin-wink"></i>&nbsp;<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) echo stripslashes($rencCustom['smiw1']); else _e('Smile','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(!$disable['profile']) { ?>
				
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['profile']; ?>"><i class="far fa-address-card"></i>&nbsp;<?php _e('Profile','rencontre'); ?></button> 
				<?php } else { ?>
					
					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['profile']; ?>"><i class="far fa-address-card"></i>&nbsp;<?php _e('Profile','rencontre');?></button> 
				<?php } ?>

				</div>
			</div><!-- .w3-container --> 
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->
	<div class="w3-clear"></div>

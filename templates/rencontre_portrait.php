<?php
/*
 * Plugin : Rencontre
 * Template : Portrait
 * Last Change : Rencontre 3.1.1
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_portrait.php
 * $u : ID, user_login, display_name, c_pays, c_region, c_ville, i_sex, d_naissance, i_taille, i_poids, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, e_lat, e_lon, d_session, t_titre, t_annonce, t_profil, t_action, maxPhoto, photo (object), looking, forwhat, session, session_ago, profil, online
 * $u0 (myself) : ID
*/
?>

	<div class="rencPortrait w3-section">
		<?php if($infochange) { ?>
		
		<div id="infoChange" class="w3-panel w3-renc-wabg">
			<div>
				<?php echo $infochange; ?>
			</div>
		</div><!-- .infoChange -->
		<?php } ?>
		
		<div class="w3-row w3-margin-bottom">
			<div class="w3-col w3-mobile" style="width:<?php echo $photoWidth;?>px">
				<div class="w3-card w3-renc-blbg">
				<?php if(!empty($u->photo->grande[0])) { ?>
				
					<img id="portraitGrande" alt="" src="<?php echo $u->photoUrl.$u->photo->grande[0]; ?>" <?php echo $onClick['thumb']; ?> title="<?php echo $title['thumb']; ?>" />
				<?php } else { ?>

					<img id="portraitGrande" alt="" src="<?php echo plugins_url('rencontre/images/no-photo600.jpg'); ?>" <?php echo $onClick['thumb']; ?> title="<?php echo $title['thumb']; ?>" />
				<?php } ?>
					
				</div>
				<div class="w3-card w3-renc-blbg w3-margin-top" style="width:<?php echo $photoWidth;?>px">
					<div class="rencBlocimg w3-center" style="font-size:0;">
					<?php for($v=0;$v<$u->maxPhoto;++$v) { ?>
						<?php if(($u->ID)*10+$v <= $u->i_photo) { ?>
							<?php if(!$disable['thumb']) { ?>
								
							<a href="javascript:void(0)" class="rencZoombox" title="<?php echo $title['zoombox']; ?>" onClick="document.getElementById('rencPhoto<?php echo $v; ?>').style.display='block'">
							<?php } else { ?>
								
							<a href="javascript:void(0)" <?php echo $onClick['thumb']; ?>>
							<?php } ?>

								<img class="w3-show-inline-block" onMouseOver="<?php echo $u->photo->over[$v]; ?>" src="<?php echo $u->photoUrl.$u->photo->mini[$v]; ?>" alt="" title="<?php echo $title['thumb']; ?>" />
							</a>
							<?php if(!empty($u->photo->grande[$v])) { ?>
							
							<img style="display:none;" src="<?php echo $u->photoUrl.$u->photo->grande[$v]; ?>" alt="" />
							<?php } ?>
							<?php if(!empty($u->photo->full[$v])) { ?>
							
							<div id="rencPhoto<?php echo $v; ?>" class="w3-modal" onclick="this.style.display='none'">
								<span class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</span>
								<div class="w3-modal-content w3-animate-zoom w3-transparent" style="width:90%">
									<img style="max-width:100%;max-height:85vh" src="<?php echo $u->photoUrl.$u->photo->full[$v]; ?>" alt="" />
								</div>
							</div>
							<?php } ?>
						<?php } else { ?>
							
							<img class="w3-show-inline-block" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="" />
						<?php } ?>
					<?php } ?>
							
					</div><!-- .rencBlocimg -->
				</div>
			</div><!-- .w3-col -->
			<div class="w3-col" style="width:16px;">&nbsp;</div><!-- .w3-col -->
			<div class="w3-rest w3-mobile w3-card w3-renc-blbg">
				<div class="w3-container">
					<div class="w3-section w3-cell-row w3-border-bottom w3-renc-line">
						<div class="w3-cell">
							<div class="w3-large" style="text-transform:capitalize"><?php echo $u->display_name; ?>
							<?php if($u->display_name!=$u->user_login) { ?>
							&nbsp;<span class="w3-small w3-opacity">(<?php echo $u->user_login; ?>)</span>
							<?php } ?>
							</div>
							<div>
							<?php if(empty($rencCustom['born']) && strpos($u->d_naissance,'0000')===false) echo Rencontre::f_age($u->d_naissance); ?>&nbsp;<?php _e('years','rencontre'); ?>
							<?php if(!isset($rencCustom['size'])) { ?>
							
							&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo (empty($rencCustom['sizeu'])?$u->i_taille.' '.__('cm','rencontre'):floor($u->i_taille/24-1.708).' '.__('ft','rencontre').' '.round(((($u->i_taille/24-1.708)-floor($u->i_taille/24-1.708))*12),1).' '.__('in','rencontre')); ?>
							<?php } ?>
							<?php if(!isset($rencCustom['weight'])) { ?>
							
							&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo (empty($rencCustom['weightu'])?$u->i_poids.' '.__('kg','rencontre'):($u->i_poids*2+10).' '.__('lbs','rencontre')) ?>
							<?php } ?>
							
							</div>
							<?php if(empty($rencCustom['place'])) { ?>

							<div>
								<span style="text-transform:capitalize"><?php echo $u->c_ville; ?></span>
								<?php if($u->c_region && !isset($rencCustom['region'])) echo '<em>('.$u->c_region.')</em>'; ?>
								&nbsp;<?php RencontreWidget::f_distance($u->e_lat,$u->e_lon); ?>
							</div>
							<?php } ?>

						</div>
						<div class="w3-cell w3-display-container">
							<div class="w3-right-align">
							<?php if($u->online) { ?>
								
								<div class="rencInline w3-text-teal"><?php _e('online','rencontre'); ?></div>
							<?php } else { ?>
								
								<div class="rencOutline w3-opacity"><?php _e('offline','rencontre'); ?></div>
							<?php } ?>
							<?php if($u->blocked->me) { ?>
						
								<div class="w3-text-deep-orange"><?php _e('You are blocked !','rencontre'); ?></div>
							<?php } ?>

							</div>
							<?php if($u->c_pays!="" && empty($rencCustom['country']) && empty($rencCustom['place'])) { ?>
				
							<div class="w3-display-bottomright">
								<img src="<?php echo plugins_url('rencontre/images/drapeaux/').$rencDrap[$u->c_pays]; ?>" style="margin-bottom:4px" alt="<?php echo $rencDrapNom[$u->c_pays]; ?>" title="<?php echo $rencDrapNom[$u->c_pays]; ?>" />
							</div>
							<?php } ?>
							
						</div>
					</div><!-- .w3-cell-row -->
					<?php if(!empty($certified)) echo $certified; ?>
					<div class="w3-renc-txtc">
						<div class="titre"><?php echo stripslashes($u->t_titre); ?></div>
						<div class="w3-section"><?php echo stripslashes($u->t_annonce); ?></div>
					</div>
					<div>
						<div class="looking"><?php _e('I\'m looking for','rencontre'); ?>&nbsp;<?php echo $u->looking . $u->forwhat; ?></div>
						<?php echo $portraitAdd1; ?>
					<?php if(!empty($u->session) && $u0->ID!=$u->ID && !$u->online) { ?>
					
						<div class="rencDate w3-right w3-opacity"><?php _e('online','rencontre'); ?>&nbsp;:&nbsp;<?php echo $u->session_ago; ?></div>
					<?php } ?>

					</div>
				</div><!-- .w3-container -->
				<div id="portraitBox" class="w3-container">
					<div class="w3-section">
						<div class="w3-bar w3-renc-mebg">
						<?php $i = 0; foreach($u->profil as $key=>$value) { ?>
							
							<div id="portraitTab<?php echo $i; ?>" class="w3-bar-item w3-button<?php if($i==0) echo ' w3-renc-mebt'; ?>" style="text-transform:capitalize;" onclick="javascript:f_onglet(<?php echo $i; ?>);"><?php echo $key; ?></div>
							<?php ++$i; ?>
						<?php } ?>
						</div><!-- .w3-bar -->
						
						<?php $i = 0; foreach($u->profil as $key=>$value) { ?>

						<div id="portraitCont<?php echo $i; ?>" class="w3-container w3-border w3-renc-line w3-hide<?php if($i==0) echo ' w3-show'; ?>">
							<?php foreach($value as $k=>$v) { ?>
							
							<div class="w3-row">
								<div class="w3-third"><?php echo $k; ?></div>
								<div class="w3-twothird"><?php echo $v; ?></div>
							</div>
							<?php } ?>
							
						</div><!-- .w3-container -->
							<?php ++$i; ?>
						<?php } ?>

					</div>
				</div>
			</div><!-- .w3-rest -->
		</div><!-- .w3-row -->
		<?php if($u0->ID!=$u->ID) { ?>
		<div class="w3-card w3-renc-blbg w3-margin-bottom">
			<div class="w3-container">
				<div class="w3-section">

				<?php if(!$disable['send']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['send']; ?>"><i class="far fa-envelope"></i>&nbsp;<?php _e('Send a message','rencontre');?></button> 
				<?php } else { ?>
					
					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['send']; ?>"><i class="far fa-envelope"></i>&nbsp;<?php _e('Send a message','rencontre');?></button> 
				<?php } ?>
				<?php if(empty($rencCustom['smile'])) { ?>
					<?php if(!$disable['smile']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['smile']; ?>"><i class="far fa-grin-wink"></i>&nbsp;<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) echo stripslashes($rencCustom['smiw1']); else _e('Smile','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['smile']; ?>"><i class="far fa-grin-wink"></i>&nbsp;<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) echo stripslashes($rencCustom['smiw1']); else _e('Smile','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(empty($rencCustom['creq'])) { ?>
					<?php if(!$disable['contact']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['contact']; ?>"><i class="far fa-flag"></i>&nbsp;<?php _e('Ask for a contact','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['contact']; ?>"><i class="far fa-flag"></i>&nbsp;<?php _e('Ask for a contact','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(!empty($rencOpt['tchat'])) { ?>
					<?php if(!$disable['chat']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['chat']; ?>"><i class="far fa-comments"></i>&nbsp;<?php _e('Chat','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['chat']; ?>"><i class="far fa-comments"></i>&nbsp;<?php _e('Chat','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(!$disable['block']) { ?>

					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['block']; ?>"><i class="far fa-eye-slash"></i>&nbsp;<?php if(!$u->blocked->he) _e('Block','rencontre'); else _e('Unblock','rencontre'); ?></button> 
				<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['block']; ?>"><i class="far fa-eye-slash"></i>&nbsp;<?php _e('Block','rencontre'); ?></button> 
				<?php } ?>
				<?php if(!$disable['report']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['report']; ?>" title="<?php _e('Report a fake profile or inappropriate content','rencontre'); ?>"><i class="far fa-thumbs-down "></i>&nbsp;<?php _e('Report','rencontre'); ?></button> 
				<?php } else { ?>

					<button class="w3-btn w3-margin-bottom w3-renc-mebt w3-disabled" title="<?php echo $title['report']; ?>"><i class="far fa-thumbs-down "></i>&nbsp;<?php _e('Report','rencontre'); ?></button> 
				<?php } ?>
				<?php if(!$disable['favori']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['favoriAdd']; ?>" title="<?php _e('Add to my favorites','rencontre'); ?>"><i class="far fa-star"></i>&nbsp;<?php _e('Favorite','rencontre'); ?>&nbsp;(+)</button> 
				<?php } else { ?>

					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['favoriDel']; ?>" title="<?php _e('Delete from my favorites','rencontre'); ?>"><i class="far fa-star"></i>&nbsp;<?php _e('Favorite','rencontre'); ?>&nbsp;(-)</button> 
				<?php } ?>
				<?php if(!empty($buttonPlus)) echo $buttonPlus; ?>

				</div>
			</div>
		</div><!-- .w3-card -->
		<?php } ?>
		<?php if(!empty($portraitPlus)) echo $portraitPlus; ?>

		
	</div><!-- .rencPortrait -->

<?php
/*
 * Plugin : Rencontre
 * Template : Portrait
 * Last Change : Rencontre 3.13
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_portrait.php
 * Call : rencontre_widget.php => widget()
 * $u : ID, user_login, display_name, c_pays, c_region, c_ville, i_sex, d_naissance, i_taille, i_poids, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, e_lat, e_lon, d_session, t_titre, t_annonce, t_profil, t_action, maxPhoto, photo (object), looking, forwhat, session, session_ago, profil, online
 * $u0 (myself)
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
				<div class="w3-card w3-renc-blbg w3-center">
				<?php if(!empty($u->photo->grande[0])) { ?>
				
					<img id="portraitGrande" alt="" src="<?php echo $u->photoUrl.$u->photo->grande[0]; ?>" srcset="<?php echo $u->photo->grandeRetina[0]; ?>" <?php echo $onClick['thumb']; ?> loading="lazy" title="<?php echo $title['thumb']; ?>" />
				<?php } else { ?>

					<img id="portraitGrande" alt="" src="<?php echo plugins_url('rencontre/images/no-photo600.jpg'); ?>" <?php echo $onClick['thumb']; ?> title="<?php echo $title['thumb']; ?>" />
				<?php } ?>
					
				</div>
				<div class="w3-card w3-renc-blbg w3-margin-top w3-mobile" style="width:<?php echo $photoWidth;?>px">
					<div class="rencBlocimg w3-center" style="font-size:0;">
					<?php for($v=0;$v<$u->maxPhoto;++$v) { ?>
						<?php if(($u->ID)*10+$v <= $u->i_photo) { ?>
							<?php if($disable['thumb']) { ?>
								
							<a href="javascript:void(0)" <?php echo $onClick['thumb']; ?>>
							<?php } else if(empty($rencOpt['lightbox'])) { ?>
								
							<a href="javascript:void(0)" class="rencZoombox" title="<?php echo $title['zoombox']; ?>" onClick="document.getElementById('rencPhoto<?php echo $v; ?>').style.display='block'">
							<?php } else { ?>
								
							<a href="javascript:void(0)" class="rencZoombox" title="<?php echo $title['zoombox']; ?>" onClick="f_lightbox(<?php echo $v; ?>)">
							<?php } ?>
							<?php if(!empty($u->photo->miniWebp[$v])) { ?>
							
								<picture onMouseOver="<?php echo $u->photo->over[$v]; ?>" title="<?php echo $title['thumb']; ?>">
									<source class="w3-show-inline-block" srcset="<?php echo $u->photoUrl.$u->photo->miniWebp[$v]; ?>" type="image/webp">
									<source class="w3-show-inline-block" srcset="<?php echo $u->photoUrl.$u->photo->mini[$v]; ?>" type="image/jpeg"> 
									<img class="w3-show-inline-block" src="<?php echo $u->photoUrl.$u->photo->mini[$v]; ?>" srcset="<?php echo $u->photo->miniRetina[$v]; ?>" />
								</picture>
							<?php } else { ?>

								<img class="w3-show-inline-block" onMouseOver="<?php echo $u->photo->over[$v]; ?>" src="<?php echo $u->photoUrl.$u->photo->mini[$v]; ?>" srcset="<?php echo $u->photo->miniRetina[$v]; ?>" alt="" title="<?php echo $title['thumb']; ?>" />
							<?php } ?>
							
							</a>
							<?php if(!empty($u->photo->grande[$v])) { ?>
							
							<img id="portraitGrande<?php echo $v; ?>" style="display:none;" src="<?php echo $u->photoUrl.$u->photo->grande[$v]; ?>" srcset="<?php echo $u->photo->grandeRetina[$v]; ?>" loading="lazy" alt="" />
							<?php } ?>
							<?php if(!empty($u->photo->full[$v])) { ?>
							
							<div id="rencPhoto<?php echo $v; ?>" class="w3-modal" onclick="this.style.display='none'">
								<span class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</span>
								<div class="w3-modal-content w3-animate-zoom w3-transparent" style="width:90%">
									<img style="max-width:100%;max-height:85vh" src="<?php echo $u->photoUrl.$u->photo->full[$v]; ?>" srcset="<?php echo $u->photo->fullRetina[$v]; ?>" loading="lazy" alt="" />
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
							<?php if($u->display_name!=strtok($u->user_login, '@')) { ?>
							<span class="w3-small w3-opacity"> (<?php echo strtok($u->user_login, '@'); ?>)</span>
							<?php } ?>
							</div>
							<div>
							<?php if(empty($rencCustom['born']) && strpos($u->d_naissance.'-','0000')===false) echo '<span id="userAge">'.Rencontre::f_age($u->d_naissance).' '.__('years','rencontre').'</span>'; ?>
							<?php if(!isset($rencCustom['size'])) { ?>
							
							<span id="userSize"> &nbsp;-&nbsp; <?php echo ((empty($u0->imperials))?$u->i_taille.' '.__('cm','rencontre'):rencIn2Ft(rencConvertUnit($u->i_taille,'cm'))); ?></span>
							<?php } ?>
							<?php if(!isset($rencCustom['weight'])) { ?>
							
							<span id="userWeight"> &nbsp;-&nbsp; <?php echo ((empty($u0->imperialw))?$u->i_poids.' '.__('kg','rencontre'):rencConvertUnit($u->i_poids,'kg',1).' '.__('lbs','rencontre')) ?></span>
							<?php } ?>
							<?php if(!empty($rencCustom['sex'])) { ?>
							
							<span id="userGender" style="text-transform:capitalize"> &nbsp;-&nbsp; <?php echo $rencOpt['iam'][$u->i_sex]; ?></span>
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
								
								<div class="rencInline w3-renc-inlc"><span class="dotInline"></span><?php _e('online','rencontre'); ?></div>
							<?php } else { ?>
								
								<div class="rencOutline w3-opacity"><span class="dotOutline"></span><?php _e('offline','rencontre'); ?></div>
							<?php } ?>
							<?php if($u->blocked->me) { ?>
						
								<div class="w3-text-deep-orange"><?php _e('You are blocked!','rencontre'); ?></div>
							<?php } ?>

							</div>
							<?php if($u->c_pays!="" && empty($rencCustom['country']) && empty($rencCustom['place'])) { ?>
				
							<div class="w3-display-bottomright">
								<img src="<?php echo plugins_url('rencontre/images/drapeaux/').$rencDrap[$u->c_pays]; ?>" height="22px" width="30px" style="margin-bottom:4px" alt="<?php echo $rencDrapNom[$u->c_pays]; ?>" title="<?php echo $rencDrapNom[$u->c_pays]; ?>" />
							</div>
							<?php } ?>
							
						</div>
					</div><!-- .w3-cell-row -->
					<?php if(!empty($certified)) echo $certified; ?>
					<div class="w3-renc-txtc">
						<div class="titre"><?php echo stripslashes($u->t_titre); ?></div>
						<div class="annonce w3-section" style="word-break:break-word"><?php echo stripslashes($u->t_annonce); ?></div>
					</div>
					<div>
						<div class="looking"><?php echo __('I\'m looking for','rencontre') . ' ' . $u->looking . $u->forwhat; ?></div>
						<?php echo $portraitAdd1; ?>
					<?php if(!empty($u->session) && $u0->ID!=$u->ID && !$u->online) { ?>
					
						<div class="rencDate w3-right w3-opacity"><?php echo __('online','rencontre').__(': ','rencontre').$u->session_ago; ?></div>
					<?php } ?>

					</div>
				</div><!-- .w3-container -->
				<div id="portraitBox" class="w3-container">
					<div class="w3-section">
						<div class="w3-bar w3-renc-mebg">
						<?php $i = 0; foreach($u->profil as $key=>$value) { ?>
							
							<div id="portraitTab<?php echo $i; ?>" class="w3-bar-item w3-button<?php if($i==0 && !$hideProf) echo ' w3-renc-mebt'; ?>" style="text-transform:capitalize;" onclick="javascript:f_onglet(<?php echo $i; ?>);"><?php echo $key; ?></div>
							<?php ++$i; ?>
						<?php } ?>
						
						</div><!-- .w3-bar -->
						<?php $i = 0; foreach($u->profil as $key=>$value) { ?>

						<div id="portraitCont<?php echo $i; ?>" class="w3-container w3-border w3-renc-line w3-hide<?php if($i==0 && !$hideProf) echo ' w3-show'; ?>">
							<?php foreach($value as $k=>$v) { ?>
							
							<div class="w3-row">
								<div class="w3-third w3-renc-underline-s"><?php echo $k; ?></div>
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
				<div id="rencAlert" class="w3-panel w3-renc-wabg" style="display:none"></div>
				<div class="w3-section">
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="if(jQuery(this).children('i').hasClass('fa-angle-up')){jQuery('.btnrar.w3-hide').removeClass('w3-hide');jQuery(this).children('i').removeClass('fa-angle-up').addClass('fa-angle-down')}else{jQuery('.btnrar').addClass('w3-hide');jQuery(this).children('i').removeClass('fa-angle-down').addClass('fa-angle-up')}"><i class="fa fa-angle-up"></i></button>
				<?php if(!$disable['send']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['send']; ?>"><i class="far fa-envelope"></i> <?php _e('Send a message','rencontre');?></button> 
				<?php } else { ?>
					
					<button class="w3-btn btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['send']); ?>')" title="<?php echo $title['send']; ?>"><i class="far fa-envelope"></i> <?php _e('Send a message','rencontre');?></button> 
				<?php } ?>
				<?php if(empty($rencCustom['smile'])) { ?>
					<?php if(!$disable['smile']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['smile']; ?>"><i class="far fa-grin-wink"></i> <?php if($t=rencTranslate('smiw1')) echo $t; else _e('Smile','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['smile']); ?>')" title="<?php echo $title['smile']; ?>"><i class="far fa-grin-wink"></i> <?php if($t=rencTranslate('smiw1')) echo $t; else _e('Smile','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(empty($rencCustom['creq'])) { ?>
					<?php if(!$disable['contact']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['contact']; ?>"><i class="far fa-flag"></i> <?php _e('Ask for a contact','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['contact']); ?>')" title="<?php echo $title['contact']; ?>"><i class="far fa-flag"></i> <?php _e('Ask for a contact','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(!empty($rencOpt['tchat'])) { ?>
					<?php if(!$disable['chat']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['chat']; ?>"><i class="far fa-comments"></i> <?php _e('Chat','rencontre'); ?></button> 
					<?php } else { ?>

					<button class="w3-btn btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['chat']); ?>')" title="<?php echo $title['chat']; ?>"><i class="far fa-comments"></i> <?php _e('Chat','rencontre'); ?></button> 
					<?php } ?>
				<?php } ?>
				<?php if(!$disable['block']) { ?>

					<button class="w3-button btnrar w3-hide w3-margin-bottom w3-renc-mebt <?php echo (!$u->blocked->he?'w3-renc-mebo':'w3-renc-mebw'); ?>" onClick="<?php echo $onClick['block']; ?>"><i class="far fa-eye-slash"></i> <?php if(!$u->blocked->he) _e('Block','rencontre'); else _e('Unblock','rencontre'); ?></button> 
				<?php } else { ?>

					<button class="w3-btn btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['block']); ?>')" title="<?php echo $title['block']; ?>"><i class="far fa-eye-slash"></i> <?php _e('Block','rencontre'); ?></button> 
				<?php } ?>
				<?php if(!$disable['report']) { ?>
					
					<button class="w3-button btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['report']; ?>" title="<?php _e('Report a fake profile or inappropriate content','rencontre'); ?>"><i class="far fa-thumbs-down "></i> <?php _e('Report','rencontre'); ?></button> 
				<?php } else { ?>

					<button class="w3-btn btnrar w3-hide w3-margin-bottom w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['report']); ?>')" title="<?php echo $title['report']; ?>"><i class="far fa-thumbs-down "></i> <?php _e('Report','rencontre'); ?></button> 
				<?php } ?>
				<?php if(!$disable['favori']) { ?>
					
					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['favoriAdd']; ?>" title="<?php _e('Add to my favorites','rencontre'); ?>"><i class="far fa-star"></i> <?php _e('Favorite','rencontre'); ?> &nbsp;<i class="fas fa-user-plus"></i></button> 
				<?php } else { ?>

					<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebc" onClick="<?php echo $onClick['favoriDel']; ?>" title="<?php _e('Delete from my favorites','rencontre'); ?>"><i class="far fa-star"></i> <?php _e('Favorite','rencontre'); ?> &nbsp;<i class="fas fa-user-slash"></i></button> 
				<?php } ?>
				<?php if(!empty($buttonPlus)) echo $buttonPlus; ?>
				
				</div>
			</div>
		</div><!-- .w3-card -->
		<?php } ?>
		<?php if(!empty($portraitPlus)) echo $portraitPlus; ?>

		<?php if(!empty($rencOpt['lightbox']) && $tpl=rencTpl('rencontre_lightbox.php',0)) include($tpl); ?>
		
	</div><!-- .rencPortrait -->

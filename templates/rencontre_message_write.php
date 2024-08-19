<?php
/*
 * Plugin : Rencontre
 * Template : Message Write
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_message_write.php
 * Call : rencontre_widget.php => widget()
 * $u : user_id, user_login, display_name, i_photo, miniPhoto, miniPhotoWebp
 * $u0 : user_login
*/
?>

	<div class="rencMsgWrite">
		<div class="w3-margin-bottom">
			<div class="w3-card w3-renc-blbg">
				<div class="w3-container">
					<div class="w3-section">
						<div class="w3-cell-row">
							<div class="w3-container w3-cell">
								<?php if(!empty($onClick['profile'])) { ?>
								
								<a class="msgProfil" href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
								<?php } else { ?>
								
								<span class="msgProfil">
								<?php } ?>
								<?php if(!empty($u->i_photo)) { ?>
									<?php if(!empty($u->miniPhotoWebp)) { ?>

									<picture>
										<source class="w3-circle w3-image" srcset="<?php echo $u->miniPhotoWebp; ?>" type="image/webp" alt="<?php echo $u->display_name; ?>">
										<source class="w3-circle w3-image" srcset="<?php echo $u->miniPhoto; ?>" type="image/jpeg" alt="<?php echo $u->display_name; ?>"> 
										<img class="w3-circle w3-image" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" alt="<?php echo $u->display_name; ?>" />
									</picture>
									<?php } else { ?>
			
									<img class="w3-circle w3-image" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" alt="<?php echo $u->display_name; ?>" />
									<?php } ?>
								<?php } else { ?>

									<img class="w3-circle w3-image" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" title="<?php echo $u->display_name; ?>" />
								<?php } ?>

								<?php if(!empty($onClick['profile'])) { ?></a><?php } else { ?></span><?php } ?>
								
								<div>
								<?php if(!empty($onClick['profile'])) { ?>
								
									<a class="msgProfil" href="javascript:void(0)" style="text-transform:capitalize;" onClick="<?php echo $onClick['profile']; ?>" title="<?php _e('Profile','rencontre'); ?>"><?php echo $u->display_name; ?></a>
								<?php } else { ?>

									<span class="msgProfil" style="text-transform:capitalize;"><?php echo $u->display_name; ?></span>
								<?php } ?>

								</div>
							</div>
							<div class="w3-container w3-cell">
								<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['inbox']; ?>"><i class="far fa-envelope"></i><span class="w3-hide-small"> <?php _e('Inbox','rencontre');?></span></button>
							</div>
						</div><!-- .w3-cell-row -->
					</div>
					<div class="w3-section w3-renc-lblc">
						<form name="formEcrire" method='post' action=''>
						<?php if(isset($rencOpt['page_id'])) { ?>
						
							<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
						<?php } ?>
						
							<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
							<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id'); ?>" value="" />
							<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg'); ?>" value="" />
							<label><?php _e('Send a message to','rencontre'); ?><span style="text-transform:capitalize;"> <?php echo $u->display_name; ?></span></label>
							<textarea name="contenu" class="w3-input w3-renc-inbg" rows="8"></textarea>
						<?php if(!isset($rencCustom['emot']) || !$rencCustom['emot']) { ?>
							
							<div id="msgEmot" class="msgEmot"></div>
							<script type="text/javascript">jQuery(document).ready(function(){f_msgEmot(document.getElementById("msgEmot"))})</script>
						<?php } ?>
						</form>
					</div>
					<div class="w3-section w3-right-align">
						<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['send']; ?>"><?php _e('Send','rencontre'); ?></button>
					</div>
				</div>
				<div class="w3-container">
					<div class="w3-section w3-border-top w3-renc-line">
					<?php RencontreWidget::f_conversation($u0->user_login,$u->user_id,array($u->user_login,$u->display_name),$u->i_photo,1); ?>

					</div>
				</div>
			</div><!-- .w3-card -->
		</div><!-- .w3-margin-bottom -->
	</div><!-- .rencMsgWrite -->

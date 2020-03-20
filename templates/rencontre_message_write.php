<?php
/*
 * Plugin : Rencontre
 * Template : Message Write
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_message_write.php
 * $u : user_id, user_login, display_name, i_photo, photo
 * $u0 : user_login
*/
?>

	<div class="rencMsgWrite w3-container w3-padding-small">
		<div class="w3-card w3-renc-blbg">
			<div class="w3-container">
				<div class="w3-section">
					<div class="w3-cell-row">
						<div class="w3-container w3-cell">
							<a href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
							<?php if(!empty($u->i_photo)) { ?>

								<img class="w3-circle w3-image" src="<?php echo $u->photo; ?>" alt="<?php echo $u->display_name; ?>" />
							<?php } else { ?>

								<img class="w3-circle w3-image" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" title="<?php echo $u->display_name; ?>" />
							<?php } ?>

							</a>
							<div>
								<a href="javascript:void(0)" style="text-transform:capitalize;" onClick="<?php echo $onClick['profile']; ?>" title="<?php _e('Profile','rencontre'); ?>"><?php echo $u->display_name; ?></a>
							</div>
						</div>
						<div class="w3-container w3-cell">
							<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['inbox']; ?>"><i class="far fa-envelope"></i><span class="w3-hide-small">&nbsp;<?php _e('Inbox','rencontre');?></span></button>
						</div>
					</div><!-- .w3-cell-row -->
				</div>
				<div class="w3-section w3-renc-lblc">
					<form name="formEcrire" method='post' action=''>
					<?php if(isset($rencOpt['page_id'])) { ?>
					
						<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
					<?php } ?>
					
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id'); ?>" value="" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg'); ?>" value="" />
						<label><?php _e('Send a message to','rencontre'); ?>&nbsp;<span style="text-transform:capitalize;"><?php echo $u->display_name; ?></span></label>
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
				<?php RencontreWidget::f_conversation($u0->user_login,$u->user_id,array($u->user_login,$u->display_name),$u->i_photo,99); ?>

				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

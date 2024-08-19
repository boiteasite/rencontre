<?php
/*
 * Plugin : Rencontre
 * Template : Modal Warning
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_modal_warning.php
 * Call : rencontre_widget.php => widget()
*/
?>

	<?php if(isset($_GET[$Lidf]) && $_GET[$Lidf]=='rencfastreg') { ?>
	
		<div id="rencTitann" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4 w3-renc-wmbg" style="max-width:600px;">
				<div class="w3-container" onClick="f_titann();">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</div>
					<div class="w3-padding-48">
				<?php _e('Your email is validated','rencontre'); ?>
				
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
	<?php }	else if(empty($rencDiv['titann']) && !isset($_COOKIE["titann"]) && !current_user_can("administrator")) { ?>
		<?php if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) { ?>
		
		<div id="rencTitann" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4 w3-renc-wmbg" style="max-width:600px;">
				<div class="w3-container" onClick="f_titann();">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</div>
					<div class="w3-padding-48">
				<?php printf( __('Welcome to %s. You will stay hidden without interaction to other members as long as your account is not completed.','rencontre'), get_bloginfo('name')); ?>
				<?php if($rencOpt['fastreg']>2) echo ' '.__('You have 24 hours to confirm your email.','rencontre'); ?>
			
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
		<?php } else { ?>
		
		<div id="rencTitann" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4 w3-renc-wmbg" style="max-width:600px;">
				<div class="w3-container" onClick="f_titann();">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</div>
					<div class="w3-padding-48">
					<?php if($t=rencTranslate('emptyText')) echo $t;
					else _e('Your profile is empty. To take advantage of the site and being more visible, thank you to complete it.','rencontre'); ?>
					
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
		<?php } ?>
	<?php }	else if(!current_user_can("administrator") && !empty($rencOpt['mandatory']) && rencistatus($rencDiv['istatus'],3)) { ?>

		<div id="rencTitann" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4 w3-renc-wmbg" style="max-width:600px;">
				<div class="w3-container" onClick="f_titann();">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</div>
					<div class="w3-padding-48">
					<?php _e('Some required fields in your profile are incomplete. Thank you to fill them in.','rencontre'); ?>
					
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
	<?php } ?>


		<div id="modalWarn" class="w3-modal w3-animate-opacity">
			<div class="w3-modal-content w3-card-4 w3-renc-wmbg" style="max-width:600px;">
				<div class="w3-container"  onClick="document.getElementById('modalWarn').style.display='none'">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo">&times;</div>
					<div id="modalWarnContent" class="w3-padding-48 w3-center">
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
	<?php if(!empty($warning)) { ?>

		<script type="text/javascript">jQuery(document).ready(function(){f_modalWarn('<?php echo addslashes($warning); ?>');})</script>
	<?php } ?>

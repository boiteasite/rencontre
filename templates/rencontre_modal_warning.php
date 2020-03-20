<?php
/*
 * Plugin : Rencontre
 * Template : Modal Warning
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_modal_warning.php
*/
?>

	<?php if(isset($_GET[$Lidf]) && $_GET[$Lidf]=='rencfastreg') { ?>
	
		<div id="rencFantome" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4" style="max-width:600px;">
				<div class="w3-container">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo" onClick="f_fantome();">&times;</div>
					<div class="w3-padding-48">
				<?php _e('Your email is validated','rencontre'); ?>
				
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
	<?php }	else if(!$fantome && !isset($_COOKIE["rencfantome"]) && !current_user_can("administrator"))	{ ?>
		<?php if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) { ?>
		
		<div id="rencFantome" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4" style="max-width:600px;">
				<div class="w3-container">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo" onClick="f_fantome();">&times;</div>
					<div class="w3-padding-48">
				<?php printf( __('Welcome to %s. You will stay hidden without interaction to other members as long as your account is not completed.','rencontre'), get_bloginfo('name')); ?>
				<?php if($rencOpt['fastreg']>2) echo '&nbsp;'.__('You have 24 hours to confirm your email.','rencontre'); ?>
			
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
		<?php } else { ?>
		
		<div id="rencFantome" class="w3-modal w3-show w3-animate-opacity">
			<div class="w3-modal-content w3-card-4" style="max-width:600px;">
				<div class="w3-container">
					<div class="w3-button w3-display-topright w3-large w3-renc-mebo" onClick="f_fantome();">&times;</div>
					<div class="w3-padding-48">
					<?php if(!isset($rencCustom['empty']) || empty($rencCustom['emptyText'])) _e('Your profile is empty. To take advantage of the site and being more visible, thank you to complete it.','rencontre');
					else echo stripslashes($rencCustom['emptyText']); ?>
					
					</div>
				</div>
			</div>
		</div><!-- .w3-modal -->
		<?php } ?>
	<?php } ?>

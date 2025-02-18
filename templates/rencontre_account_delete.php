<?php
/*
 * Plugin : Rencontre
 * Template : Account Delete
 * Last Change : Rencontre 3.9.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_account_delete.php
 * Call : rencontre_widget.php => f_compte()
*/
?>

	<div class="rencAccountDel">
		<div class="w3-margin-bottom">
			<div class="w3-card w3-renc-blbg">
				<div class="w3-container w3-renc-txtc">
					<div class="w3-section">
						<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Account deletion','rencontre');?></div>
						<form name="formFin" method="post" action="">
							<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
							<div class="w3-border w3-renc-line w3-padding">
								<div><?php if($t=rencTranslate('dele1')) echo $t; else _e('This action will result in the complete deletion of your account and everything about you from our server. We do not keep historical accounts.','rencontre');?></div>
								<div style="font-weight:bold"><?php if($t=rencTranslate('dele2')) echo $t; else _e('Please note that this action is irreversible!','rencontre');?></div>
							</div>
							<div class="w3-section w3-right-align">
								<button type="button" class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['delete']; ?>"><?php _e('Delete Account','rencontre');?></button>
							</div>
						</form>
					</div>
				</div>
			</div><!-- .w3-card -->
		</div><!-- .w3-margin-bottom -->
	</div><!-- .rencAccountDel -->

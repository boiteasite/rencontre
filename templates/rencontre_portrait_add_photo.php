<?php
/*
 * Plugin : Rencontre
 * Template : Portrait add photo
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_portrait_add_photo.php
 * $u0 : i_photo
*/
?>

	<div id="rencPhotoPop" class="w3-modal" style="display:none">
		<div class="w3-modal-content w3-card-4" style="max-width:600px;">
			<div class="w3-container">
				<div class="w3-button w3-display-topright w3-large w3-renc-mebo" onClick="document.getElementById('rencPhotoPop').style.display='none'">&times;</div>
				<form class="portraitPhotoPop" name="portraitPhotoPop" method="post" enctype="multipart/form-data" action="">
					<div class="w3-section">
						<div class="w3-xlarge w3-renc-titc w3-section w3-center"><?php echo $head; ?></div>
					<?php if(!empty($info)) { ?>
					
						<div class="w3-section w3-large w3-renc-txtc"><?php echo $info; ?></div>
					<?php } ?>
						<input type="hidden" name="a1" value="" />
						<input type="hidden" name="a2" value="" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
						<input type="hidden" name="rotate" value="" />
						<input type="hidden" name="rnd" value="<?php echo $_SESSION['rnd']; ?>" />
						<div style="margin:0 auto;text-align:center"></div>
						<input type="file" name="plusPhoto" size="18" accept="image/*" onchange="f_photoPop_display(this)">
					</div>
				<?php if(!empty($rencOpt['fblog']) && strlen($rencOpt['fblog'])>2) { ?>
					<div class="w3-section">
						<div class="w3-button w3-renc-mebt w3-renc-mebo w3-margin" onClick="f_FBLogin(<?php echo $u0->i_photo; ?>);"><?php echo addslashes(__('Facebook Profile Photo','rencontre')); ?></div>
					</div>
				<?php } ?>
					<div class="w3-section w3-border-top w3-renc-line w3-cell-row">
						<div id="popPhoto" class="w3-cell w3-padding"></div>
						<div class="w3-cell">
							<div id="popPhotoSubm" class="w3-button w3-renc-mebt w3-renc-mebo w3-margin w3-right" onClick="f_plus_photoPop_submit(<?php echo $u0->i_photo; ?>)" title="<?php echo addslashes(__('Add this photo','rencontre')); ?>"><?php echo addslashes(__('Add this photo','rencontre')); ?></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php if(!empty($rencOpt['fblog']) && strlen($rencOpt['fblog'])>2) { ?>
	
		<script type="text/javascript">
		(function(d){var js,id='facebook-jssdk',ref=d.getElementsByTagName('script')[0];if(d.getElementById(id))return;js=d.createElement('script');js.id=id;js.async=true;js.src="https://connect.facebook.net/en_US/sdk.js";ref.parentNode.insertBefore(js,ref);}(document,'script','facebook-jssdk'));
		window.fbAsyncInit=function(){FB.init({appId:'<?php echo $rencOpt['fblog']; ?>',status:true,cookie:true,xfbml:true,version:'v3.2'});};
		</script>
	<?php } ?>
	
	</div><!-- .w3-modal -->

<?php
/*
 * Plugin : Rencontre
 * Template : Registration Jail
 * Last Change : Rencontre 3.0
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_registration_jail.php
 * $u0 : 
*/
?>

	<div class="rencJail w3-container w3-padding-small">
		<div class="w3-card w3-renc-blbg">
			<div class="w3-container">
				<div class="w3-section">
					<div class="w3-xlarge w3-renc-titc w3-section"><?php if(isset($rencCustom['jail']) && !empty($rencCustom['jailText'])) echo stripslashes($rencCustom['jailText']);
					else _e('Your email address is currently in quarantine. Sorry','rencontre'); ?>
					</div>
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

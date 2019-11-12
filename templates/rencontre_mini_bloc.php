<?php
/*
 * Plugin : Rencontre
 * Template : Warning
 * Last Change : Rencontre 3.0
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mini_bloc.php
 * input : $title, $content
*/
?>

	<div class="rencMiniBloc w3-container w3-padding-small">
		<div class="w3-card w3-white">
			<div class="w3-container">
				<div class="w3-section">
				<?php if(!empty($title)) { ?>
				
					<div class="w3-xlarge w3-renc-titc w3-section"><?php echo $title; ?></div>
				<?php } ?>
				<?php echo $content; ?>
				
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

<?php
/*
 * Plugin : Rencontre
 * Template : My Home
 * Last Change : Rencontre 3.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_my_home.php
 * $u : user_id
 * $u0 : ID
*/
?>

<?php if(!empty($rencCustom['fitw'])) echo '<script type="text/javascript">jQuery(document).ready(function(){if(typeof f_dynamicWidth==="function")f_dynamicWidth()})</script>'; ?>

	<div class="rencMyHome">
		<?php if(count($uFeatProf)) { ?>

		<div class="featuredBox w3-section w3-clear">
			<div class="w3-xlarge w3-renc-titc"><?php _e('Featured profiles','rencontre');?></div>
			<?php foreach($uFeatProf as $u) {
				RencontreWidget::f_miniPortrait($u->user_id,((!empty($rencOpt['onlyphoto']) && !$mephoto)?1:0),$photoWidth);
			} ?>

		</div>
		<?php } ?>
		<?php if(!empty($rencOpt['anniv']) && !isset($rencCustom['born']) && count($uBirthday)) { ?>
		
		<div class="birthdayBox w3-section w3-clear">
			<div class="w3-xlarge w3-renc-titc"><?php _e('Today\'s birthday','rencontre');?></div>
			<?php foreach($uBirthday as $u) {
				RencontreWidget::f_miniPortrait($u->user_id,((!empty($rencOpt['onlyphoto']) && !$mephoto)?1:0),$photoWidth);
			} ?>
					
		</div>
		<?php } ?>

		<?php $ho = false; if(has_filter('rencAddBox', 'f_rencAddBox')) $ho = apply_filters('rencAddBox', $u0->ID); if($ho) echo $ho; ?>

		<?php if(!empty($rencOpt['ligne']) && count($uLine)) { ?>

		<div class="onlineBox w3-section w3-clear">
			<div class="w3-xlarge w3-renc-titc">
			
			<?php if(!empty($rencOpt['home'])) { ?>
				<a href="javascript:void(0)" onClick="document.forms['rencLine'].submit();"><?php _e('Online now','rencontre'); ?></a>
			<?php } else { ?>
				<?php _e('Online now','rencontre'); ?>
			<?php } ?>
			</div>
				<?php foreach($uLine as $u) {
					RencontreWidget::f_miniPortrait($u->user_id,((!empty($rencOpt['onlyphoto']) && !$mephoto)?1:0),$photoWidth);
				} ?>
					
		</div>
		<?php } ?>

		<div class="newBox w3-section w3-clear">
			<div class="w3-xlarge w3-renc-titc"><?php _e('New entrants','rencontre'); ?></div>
			<?php foreach($uNew as $u) {
				RencontreWidget::f_miniPortrait($u->user_id,((!empty($rencOpt['onlyphoto']) && !$mephoto)?1:0),$photoWidth);
			} ?>
					
		</div>
		
		<?php if(count($uFavori)) { ?>

		<div class="favouriteBox w3-section w3-clear">
			<div class="w3-xlarge w3-renc-titc"><?php _e('My favourites','rencontre');?></div>
			<?php foreach($uFavori as $u) {
				RencontreWidget::f_miniPortrait($u->user_id,((!empty($rencOpt['onlyphoto']) && !$mephoto)?1:0),$photoWidth);
			} ?>

		</div>
		<?php } ?>
		
	</div><!-- .rencMyHome -->

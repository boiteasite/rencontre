<?php
/*
 * Plugin : Rencontre
 * Template : Mini Portrait Chat - Chat only
 * Last Change : Rencontre 3.0
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mini_portrait_chat.php
 * $u : display_name, c_pays, c_ville, d_naissance, i_photo, t_titre 
*/
?>

	<div class="rcMiniPortrait w3-padding-small <?php if($highlight) echo 'highlight'; ?>" style="min-width:220px">
		<div class="w3-card w3-white">
			<div class="w3-container">
				<div class="w3-cell-row">
				<?php if($u->i_photo!=0) { ?>

					<div class="w3-cell w3-cell-middle" style="width:60px;">
						<img class="w3-circle" src="<?php echo $rencDiv['baseurl'].'/portrait/'.floor(($user_id)/1000).'/'.Rencontre::f_img(($user_id*10).'-mini').'.jpg?r='.rand(); ?>" alt="<?php echo $u->display_name; ?>" />
					</div>
				<?php } else { ?>

					<div class="w3-cell w3-cell-middle">
						<img class="w3-circle" style="width:60px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" />
					</div>
				<?php } ?>
					<div class="w3-cell w3-cell-middle w3-padding-small">
						<div class="w3-large"><?php echo $u->display_name; ?></div>
						<?php if(!isset($rencCustom['born']) && strpos($u->d_naissance,'0000')===false) { ?>
						
						<div><?php echo Rencontre::f_age($u->d_naissance); ?>&nbsp;<?php _e('years','rencontre'); ?></div>
						<?php } ?>
						<?php if(!isset($rencCustom['place'])) { ?>
						
						<div><?php echo $u->c_ville; ?></div>
						<?php } ?>
					</div>
				</div>
				<div id="rcStart" class="w3-border-top w3-border-grey w3-clear">
					<div class="w3-cell-row">
						<div class="w3-cell w3-cell-middle w3-padding-small">
							<button id="rcYes" class="w3-button w3-renc-mebt w3-renc-mebo"><i class="far fa-comments"></i>&nbsp;<?php _e('Accept','rencontre'); ?></button>
						</div>
						<div class="w3-cell w3-cell-middle w3-padding-small">
							<button id="rcNo" class="w3-button w3-renc-mebt w3-renc-mebo"><i class="far fa-window-close"></i>&nbsp;<?php _e('Ignore','rencontre'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

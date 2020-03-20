<?php
/*
 * Plugin : Rencontre
 * Template : Mini Portrait
 * Last Change : Rencontre 3.0.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mini_portrait.php
 * $u : ID, display_name, c_pays, c_ville, d_naissance, i_photo, t_titre, online, miniPhoto
*/
?>

	<div class="rencMiniPortrait w3-padding-small w3-col m2 <?php if($highlight) echo 'highlight'; ?>" style="min-width:200px">
		<div class="w3-card w3-renc-blbg">
			<div class="w3-container w3-rest">
				<div class="w3-cell-row">
				<?php if($u->i_photo!=0) { ?>

					<div class="w3-cell w3-cell-middle w3-display-container" style="min-width:<?php echo $photoWidth; ?>px;">
						<a href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
							<img class="w3-circle" style="width:<?php echo $photoWidth; ?>px;" src="<?php echo $u->miniPhoto; ?>" alt="<?php echo $u->display_name; ?>" <?php echo $title['thumb']; ?> />
						</a>
						<?php if(!empty($certified)) echo $certified; ?>
						
					</div>
				<?php } else { ?>

					<div class="w3-cell w3-cell-middle">
						<a href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
							<img class="w3-circle" style="width:<?php echo $photoWidth; ?>px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" />
						</a>
					</div>
				<?php } ?>
					<div class="w3-cell w3-cell-middle w3-padding-small" style="white-space:nowrap;text-transform:capitalize;">
						<div class="w3-large"><?php echo $u->display_name; ?></div>
						<?php if(!isset($rencCustom['born']) && strpos($u->d_naissance,'0000')===false) { ?>
						
						<div><?php echo Rencontre::f_age($u->d_naissance); ?>&nbsp;<?php _e('years','rencontre'); ?></div>
						<?php } ?>
						<?php if(!isset($rencCustom['place'])) { ?>
						
						<div><?php echo $u->c_ville; ?>&nbsp;</div>
						<?php } ?>
					</div>
				</div>
				<div class="w3-medium w3-renc-txtc w3-border-top w3-renc-line w3-rest" style="line-height:1.4em;height:4.2em;"><?php echo $u->t_titre; ?></div>
			</div>
			<div class="w3-button w3-block w3-renc-sebg" onClick="<?php echo $onClick['profile']; ?>"><?php _e('Profile','rencontre'); ?>...</div>
		</div>
	</div>

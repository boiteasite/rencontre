<?php
/*
 * Plugin : Rencontre
 * Template : Registration Part 2/3
 * Last Change : Rencontre 3.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_registration_part2.php
*/
?>

	<div class="rencReg2 w3-container w3-padding-small">
		<div class="w3-card w3-renc-blbg w3-padding">
			<div class="rencRegProgress w3-white w3-border">
				<div class="w3-container w3-renc-mebg w3-center" style="width:66.7%">2 / 3</div>
			</div>
		</div><!-- .w3-card -->
		<div class="w3-card w3-renc-blbg w3-section">
			<div class="w3-container w3-renc-lblc">
				<div class="w3-section">
					<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Hello','rencontre'); ?>&nbsp;<?php echo $current_user->user_login; ?>,&nbsp;<?php _e('welcome to the site','rencontre'); ?>&nbsp;<?php echo bloginfo('name'); ?></div>
					<form name="formNouveau" method="post" action="">
						<input type="hidden" name="nouveau" value="2" />
						<input type="hidden" name="a1" value="" />
					<?php if(!empty($rencGeonames)) echo $rencGeonames;
						else { ?>
							
						<?php if(empty($rencCustom['country'])) { ?>
						
						<div>
							<label><?php _e('My country','rencontre'); ?></label>
							<select id="rencPays" name="pays" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['country']; ?>">
								<?php RencontreWidget::f_pays($rencOpt['pays'],1); ?>
								
							</select>
						</div>
						<?php } ?>
						
						<?php if(empty($rencCustom['region'])) { ?>
						
						<div>
							<label><?php _e('My region','rencontre'); ?></label>
							<select id="regionSelect" name="region" class="w3-select w3-border w3-renc-sebg">
								<?php RencontreWidget::f_regionBDD(1,$rencOpt['pays']); ?>
								
							</select>
						</div>
						<?php } ?>
						
						<div>
							<label><?php _e('My city','rencontre'); ?></label>
							<input id="rencVille" name="ville" type="text" class="w3-input w3-renc-inbg" autocomplete="off" />
							<input id="gps" name="gps" type="hidden" />
						</div>
					<?php } ?>

					</form>
					<div id="rencAlert" class="w3-panel w3-renc-wabg" style="display:none;"></div>
					<div class="w3-section w3-right-align">
						<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['save']; ?>"><?php _e('Save','rencontre'); ?></button>
					</div>
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

<?php
/*
 * Plugin : Rencontre
 * Template : Registration Part 3/3 and 2/2
 * Last Change : Rencontre 3.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_registration_part3.php
 * $u0 (myself) : i_sex, d_naissance, age, agemin, agemax
*/
?>

	<div class="rencReg3 w3-container w3-padding-small">
		<div class="w3-card w3-renc-blbg w3-padding">
			<div class="rencRegProgress w3-white w3-border">
			<?php if(!isset($rencCustom['place'])) { ?>

				<div class="w3-container w3-renc-mebg w3-center" style="width:100%">3 / 3</div>
			<?php } else { ?>

				<div class="w3-container w3-renc-mebg w3-center" style="width:100%">2 / 2</div>
			<?php } ?>

			</div>
		</div><!-- .w3-card -->
		<div class="w3-card w3-renc-blbg w3-section">
			<div class="w3-container w3-renc-lblc">
				<div class="w3-section">
					<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Hello','rencontre'); ?>&nbsp;<?php echo $current_user->user_login; ?>,&nbsp;<?php _e('welcome to the site','rencontre'); ?>&nbsp;<?php echo bloginfo('name'); ?></div>
					<form name="formNouveau" method="post" action="">
						<input type="hidden" name="nouveau" value="OK" />
						<input type="hidden" name="a1" value="" />
						<div>
							<label><?php _e('I\'m looking for','rencontre'); ?></label>
						<?php if(empty($rencCustom['multiSR'])) { ?>
							
							<select name="zsex" class="w3-select w3-border w3-renc-sebg">
							<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>
								<?php if(empty($rencCustom['hetero']) || $hetero!=$v) { ?>
							
								<option value="<?php echo $v; ?>"><?php echo $rencOpt['iam'][$v]; ?></option>
								<?php } ?>
							<?php } ?>
							
							</select>						
						<?php }	else { ?>
						
							<div>
							<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>

								<input type="checkbox" class="rencLabelauty" name="zsex[]" value="<?php echo $v; ?>" data-labelauty="<?php echo $rencOpt['iam'][$v]; ?>" />
							<?php } ?>
							
							</div>
						<?php } ?>
						
						</div>
						<?php if(!isset($rencCustom['born'])) { ?>
						
						<div>
							<label><?php _e('Age min/max','rencontre'); ?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="zageMin" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemin']; ?>">
									<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v) { ?>
										
										<option value="<?php echo $v; ?>" <?php if($v==$u0->agemin) echo 'selected'; ?> ><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option>
									<?php } ?>
										
									</select>
								</div>
								<div class="w3-half">
									<select name="zageMax" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
									<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18)+1;$v<(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99)+1;++$v) { ?>
										
										<option value="<?php echo $v; ?>" <?php if($v==$u0->agemax) echo 'selected'; ?> ><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option>
									<?php } ?>
										
									</select>
								</div>
							</div><!-- .w3-row -->
						</div>
						<?php } ?>

						<div>
							<label><?php _e('For','rencontre'); ?></label>
						<?php if(empty($rencCustom['multiSR'])) { ?>
							
							<select name="zrelation" class="w3-select w3-border w3-renc-sebg">
							<?php for($v=(isset($rencCustom['relation'])?3:0);$v<(isset($rencCustom['relation'])?count($rencOpt['for']):3);++$v) { ?>
							
								<option value="<?php echo $v; ?>"><?php echo $rencOpt['for'][$v]; ?></option>
							<?php } ?>
							
							</select>
						<?php } else { ?>
						
							<div>
							<?php for($v=(isset($rencCustom['relation'])?3:0);$v<(isset($rencCustom['relation'])?count($rencOpt['for']):3);++$v) { ?>
						
								<input type="checkbox" class="rencLabelauty" name="zrelation[]" value="<?php echo $v; ?>" data-labelauty="<?php echo $rencOpt['for'][$v]; ?>" />
							<?php } ?>
							
							</div>
						<?php } ?>
						
						</div>
					</form>
					<div id="rencAlert" class="w3-panel w3-renc-wabg" style="display:none;"></div>
					<div class="w3-section w3-right-align">
						<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['save']; ?>"><?php _e('Save','rencontre'); ?></button>
					</div>
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->
	<?php echo $script; ?>

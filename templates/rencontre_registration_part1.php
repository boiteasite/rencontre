<?php
/*
 * Plugin : Rencontre
 * Template : Registration Part 1/3 and 1/2
 * Last Change : Rencontre 3.5
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_registration_part1.php
 * Filter : do_action('rencontre_registration', $f, $g) - see below
*/
?>

	<div class="rencReg1 w3-margin-bottom">
		<div class="w3-card w3-renc-blbg w3-padding">
			<div class="rencRegProgress w3-white w3-border">
			<?php if(!isset($rencCustom['place'])) { ?>
			
				<div class="w3-container w3-renc-mebg w3-center" style="width:33.3%">1 / 3</div>
			<?php } else { ?>
			
				<div class="w3-container w3-renc-mebg w3-center" style="width:50%">1 / 2</div>
			<?php } ?>
			
			</div>
		</div><!-- .w3-card -->
		<div class="w3-card w3-renc-blbg w3-section">
			<div class="w3-container w3-renc-txtc">
				<div class="w3-section">
					<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Hello','rencontre'); ?>&nbsp;<?php echo $current_user->user_login; ?>,&nbsp;<?php _e('welcome to the site','rencontre'); ?>&nbsp;<?php echo bloginfo('name'); ?></div>
				<?php if(isset($rencCustom['new']) && !empty($rencCustom['newText'])) { ?>
					
					<div><?php echo stripslashes($rencCustom['newText']); ?></div>
				<?php } else { ?>
					
					<div>
						<?php _e('You will access all the possibilities offered by the site in few minutes.','rencontre'); ?>
						<?php _e('Before that, you need to provide some information requested below.','rencontre'); ?>
					
					</div>
					<div>
						<?php _e('We would like to inform you that we do not use your personal data outside of this site.','rencontre'); ?>
						<?php _e('Deleting your account on your part or ours, causes the deletion of all your data.','rencontre'); ?>
						<?php _e('This also applies to messages that you have sent to other members as well as those they have sent to you.','rencontre'); ?>

					</div>
					<div>
						<?php _e('We wish you nice encounters.','rencontre'); ?>

					</div>
				<?php } ?>
					
				</div>
			</div>
		</div><!-- .w3-card -->
		<div class="w3-card w3-renc-blbg w3-section">
			<div class="w3-container w3-renc-lblc">
				<div class="w3-section">
					<form name="formNouveau" method="post" action="">
						<input type="hidden" name="nouveau" value="1" />
						<input type="hidden" name="a1" value="" />
						<div>
							<label><?php _e('I am','rencontre');?></label>
							<select name="sex" class="w3-select w3-border w3-renc-sebg">
							<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>
							
								<option value="<?php echo $v; ?>"><?php echo $rencOpt['iam'][$v]; ?></option>
							<?php } ?>
							
							</select>
						</div>
						<?php if(!isset($rencCustom['born'])) { ?>
						
						<div>
							<label><?php _e('Born','rencontre'); ?></label>
							<div class="w3-row">
								<div class="w3-third">
									<select name="jour" class="w3-select w3-border w3-renc-sebg">
										<?php for($v=1;$v<32;++$v) { ?>
										
										<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
										<?php } ?>
										
									</select>
								</div>
								<div class="w3-third">
									<select name="mois" class="w3-select w3-border w3-renc-sebg">
										<?php for($v=1;$v<13;++$v) { ?>
										
										<option value="<?php echo $v; ?>"><?php echo $month[$v]; ?></option>
										<?php } ?>
										
									</select>
								</div>
								<div class="w3-third">
									<select name="annee" class="w3-select w3-border w3-renc-sebg">
										<?php for($v=$oldmax;$v<$oldmin;++$v) { ?>
										
										<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
										<?php } ?>
										
									</select>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if(empty($rencCustom['size'])){ ?>
						
						<div>
							<label><?php _e('My height','rencontre'); ?></label>
							<select name="taille" class="w3-select w3-border w3-renc-sebg">
							<?php for($v=140;$v<221;++$v) { ?>
								<?php if(empty($rencCustom['sizeu'])) { ?>
								
								<option value="<?php echo $v; ?>"><?php echo $v.'&nbsp;'.__('cm','rencontre'); ?></option>
								<?php } else { ?>
								
								<option value="<?php echo $v; ?>"><?php echo (floor($v/24-1.708)).'&nbsp;'.__('ft','rencontre').'&nbsp;'.(round(((($v/24-1.708)-floor($v/24-1.708))*12),1)).'&nbsp;'.__('in','rencontre'); ?></option>
								<?php } ?>
							<?php } ?>
							
							</select>
						</div>
						<?php } ?>
						<?php if(empty($rencCustom['weight'])) { ?>

						<div>
							<label><?php _e('My weight','rencontre'); ?></label>
							<select name="poids" class="w3-select w3-border w3-renc-sebg">
							<?php for($v=40;$v<140;++$v) { ?>
								<?php if(empty($rencCustom['weightu'])) { ?>
								
								<option value="<?php echo $v; ?>"><?php echo $v.'&nbsp;'.__('kg','rencontre'); ?></option>
								<?php } else { ?>
								
								<option value="<?php echo $v; ?>"><?php echo ($v*2+10).'&nbsp;'.__('lbs','rencontre'); ?></option>
								<?php } ?>
							<?php } ?>
							
							</select>
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
	</div><!-- .rencReg1 -->

<?php
/*
 * Data from this Template (POST) are treated in rencontre_widget.php, static function f_registerMember($f,$g).
 * You can replace this function by adding this example in the functions.php file of your theme :
 * 
 *		add_action('rencontre_registration','my-registration',10,2);
 *		function my-registration($f,$g)
 *			{
 *			// $f : my user_id
 *			// $g : $_POST['nouveau'] (1, 2, 3)
 *			global $wpdb; global $rencOpt;
 *			... see static function f_registerMember($f,$g) for your code creation.
 *			...
 *			}
*/
?>

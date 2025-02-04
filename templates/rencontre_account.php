<?php
/*
 * Plugin : Rencontre
 * Template : Account
 * Last Change : Rencontre 3.13
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_account.php
 * Call : rencontre_widget.php => f_compte()
 * $u0 : all + born_day, born_month, born_year, accountAlert, pause
 * Filter : do_action('rencontre_account', $f, $g) - see below
*/
?>

	<div class="rencAccount">
		<?php if(!empty($u0->accountAlert)) { ?>
		<div class="w3-margin-bottom">
			<div class="w3-card w3-renc-blbg">
				<div class="w3-panel w3-renc-wabg"><?php echo $u0->accountAlert; ?></div>
			</div>
		</div>
		
		<?php } ?>
		<?php if(!empty($accountPlus)) echo $accountPlus; ?>
		<?php if(empty($rencOpt['chpsw'])) { ?>
			
		<div class="w3-margin-bottom">
			<div class="w3-card w3-renc-blbg">
				<div class="w3-container w3-renc-lblc">
					<div class="w3-section">
						<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Change password','rencontre');?></div>
						<form name="formPass" method="post" action="">
							<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
							<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id'); ?>" value="" />
							<div>
								<label><?php _e('Former','rencontre');?></label>
								<input name="pass0" type="password" class="w3-input w3-renc-inbg" />
							</div>
							<div>
								<label><?php _e('New','rencontre');?></label>
								<input name="pass1" type="password" class="w3-input w3-renc-inbg" />
							</div>
							<div>
								<label><?php _e('Retype the new','rencontre');?></label>
								<input name="pass2" type="password" class="w3-input w3-renc-inbg" />
							</div>
						</form>
						<div id="rencAlertPass" class="w3-panel w3-renc-wabg" style="display:none"></div>
						<div class="w3-section w3-right-align">
							<button id="buttonPass" class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['change']; ?>"><?php _e('Change','rencontre'); ?></button>
						</div>
					</div>
				</div>
			</div><!-- .w3-card -->
		</div><!-- .w3-margin-bottom -->
		<?php } ?>
		
		<div class="w3-margin-bottom">
			<div class="w3-card w3-renc-blbg">
				<div class="w3-container w3-renc-lblc">
					<div class="w3-section">
						<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('My Account','rencontre'); ?><span style="font-size:16px;font-weight:400;margin-left:10px;">(<?php echo $u0->user_login; ?> - <?php echo $u0->user_email; ?>)</span></div>
						<form name="formNouveau" method="post" action="">
							<input type="hidden" name="nouveau" value="update" />
							<input type="hidden" name="a1" value="" />
							<input type="hidden" name="pseudo" value="<?php echo $u0->user_login; ?>" />
						<?php if(isset($rencOpt['disnam']) && $rencOpt['disnam']==2) { ?>
						
							<div>
								<label><?php _e('My name','rencontre'); ?></label>
								<input name="dname" type="text" class="w3-input w3-renc-inbg" value="<?php echo $u0->display_name; ?>" />
							</div>
						<?php } ?>
						
							<div>
								<label><?php _e('I am','rencontre');?></label>
								<select name="sex" class="w3-select w3-border w3-renc-sebg">
								<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>
									<?php if(empty($rencCustom['blksex']) || $blksex==$v || $blksex===false) { ?>
								
									<option value="<?php echo $v; ?>"<?php if($u0->i_sex==$v) echo ' selected'; ?>><?php echo $rencOpt['iam'][$v]; ?></option>
									<?php } ?>
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
											
											<option value="<?php echo $v; ?>"<?php if($v==$u0->born_day) echo ' selected'; ?>><?php echo $v; ?></option>
											<?php } ?>
											
										</select>
									</div>
									<div class="w3-third">
										<select name="mois" class="w3-select w3-border w3-renc-sebg">
											<?php for($v=1;$v<13;++$v) { ?>
											
											<option value="<?php echo $v; ?>"<?php if($v==$u0->born_month) echo ' selected'; ?>><?php echo $month[$v]; ?></option>
											<?php } ?>
											
										</select>
									</div>
									<div class="w3-third">
										<select name="annee" class="w3-select w3-border w3-renc-sebg">
											<?php for($v=$oldmax;$v<$oldmin;++$v) { ?>
											
											<option value="<?php echo $v; ?>"<?php if($v==$u0->born_year) echo ' selected'; ?>><?php echo $v; ?></option>
											<?php } ?>
											
										</select>
									</div>
								</div><!-- .w3-row -->
							</div>
							<?php } ?>
									
					<?php if(!empty($rencGeonames)) echo $rencGeonames;
					else { ?>
						<?php if(empty($rencCustom['place'])) { ?>
						
							<?php if(empty($rencCustom['country'])) { ?>
							
							<div>
								<label><?php _e('My country','rencontre'); ?></label>
								<select id="rencPays" name="pays" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['country']; ?>">
									<?php RencontreWidget::f_pays($u0->c_pays,1); ?>
									
								</select>
							</div>
							<?php } ?>
							
							<?php if(empty($rencCustom['region'])) { ?>
							
							<div>
								<label><?php _e('My region','rencontre'); ?></label>
								<select id="regionSelect" name="region" class="w3-select w3-border w3-renc-sebg">
									<?php if($u0->c_region) RencontreWidget::f_regionBDD($u0->c_region,$u0->c_pays);
									else RencontreWidget::f_regionBDD(1,$u0->c_pays); ?>
									
								</select>
							</div>
							<?php } ?>
							
							<div>
								<label><?php _e('My city','rencontre'); ?></label>
								<input id="rencVille" name="ville" type="text" class="w3-input w3-renc-inbg" autocomplete="off" value="<?php echo $u0->c_ville; ?>" />
								<input id="gps" name="gps" type="hidden" value="<?php echo $u0->e_lat.'|'.$u0->e_lon; ?>" />
							</div>
						<?php } ?>
					<?php } ?>
						<?php if(empty($rencCustom['weight']) || empty($rencCustom['size'])) { ?>

							<?php if(empty($rencCustom['size'])) {
								$b = 0; ?>

							<div>
								<label><?php _e('My height','rencontre'); ?></label>
								<select name="taille" class="w3-select w3-border w3-renc-sebg">
								<?php 
								if(empty($u0->imperials)) {
									for($v=(!empty($rencCustom['sizemin'])?$rencCustom['sizemin']:140); $v<=(!empty($rencCustom['sizemax'])?$rencCustom['sizemax']:220); ++$v) {
										?><option value="<?php echo $v; ?>"<?php if(!$b && $v>=intval($u0->i_taille+.5)) { echo ' selected'; $b = 1; } ?>><?php echo $v.' '.__('cm','rencontre'); ?></option>
									<?php }
								}
								else {
									for($v=(!empty($rencCustom['sizemin'])?rencConvertUnit($rencCustom['sizemin'],'cm',1):55); $v<=(!empty($rencCustom['sizemax'])?rencConvertUnit($rencCustom['sizemax'],'cm',1):86.5); $v+=.5) {
										?><option value="<?php echo $v; ?>"<?php if(!$b && $v>=rencConvertUnit($u0->taille,'cm',3)) { echo ' selected'; $b = 1; } ?>><?php echo rencIn2Ft($v); ?></option>
									<?php }
								} ?>
								
								</select>
							</div>
							<?php } ?>
							
							<?php if(empty($rencCustom['weight'])) {
								$b = 0; ?>

							<div>
								<label><?php _e('My weight','rencontre'); ?></label>
								<select name="poids" class="w3-select w3-border w3-renc-sebg">
								<?php
								if(empty($u0->imperialw)) {
									for($v=(!empty($rencCustom['weightmin'])?$rencCustom['weightmin']:40); $v<=(!empty($rencCustom['weightmax'])?$rencCustom['weightmax']:140); ++$v) {
										?><option value="<?php echo $v; ?>"<?php if(!$b && $v>=intval($u0->i_poids+.5)) { echo ' selected'; $b = 1; } ?>><?php echo $v.' '.__('kg','rencontre'); ?></option>
									<?php }
								}
								else {
									for($v=(!empty($rencCustom['weightmin'])?rencConvertUnit($rencCustom['weightmin'],'kg',1):88); $v<=(!empty($rencCustom['weightmax'])?rencConvertUnit($rencCustom['weightmax'],'kg',1):309); $v++) {
										?><option value="<?php echo $v; ?>"<?php if(!$b && $v>=rencConvertUnit($u0->poids,'kg',1)) { echo ' selected'; $b = 1; } ?>><?php echo $v.' '.__('lbs','rencontre'); ?></option>
									<?php }
								} ?>
								
								</select>
							</div>
							<?php } ?>
							
						<?php } ?>
							
							<div>
								<label><?php _e('I\'m looking for','rencontre'); ?></label>
							<?php if(empty($rencCustom['multiSR'])) { ?>
								
								<select name="zsex" class="w3-select w3-border w3-renc-sebg">
								<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>
									<?php if(empty($rencCustom['hetero']) || $hetero!=$v) { ?>
								
									<option value="<?php echo $v; ?>"<?php if($u0->i_zsex==$v) echo ' selected'; ?>><?php echo $rencOpt['iam'][$v]; ?></option>
									<?php } ?>
								<?php } ?>
								
								</select>
							<?php }	else { ?>
							
								<div>
								<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>
									<?php if(empty($rencCustom['hetero']) || $hetero!=$v) { ?>

									<input type="checkbox" class="rencLabelauty" name="zsex[]" value="<?php echo $v; ?>" <?php if(strpos($u0->c_zsex.'-',','.$v.',')!==false) echo 'checked'; ?> data-labelauty="<?php echo $rencOpt['iam'][$v]; ?>" />
									<?php } ?>
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
											
											<option value="<?php echo $v; ?>"<?php if($v==$u0->i_zage_min) echo ' selected'; ?>><?php echo $v.'&nbsp;'.__('years','rencontre'); ?></option>
										<?php } ?>
											
										</select>
									</div>
									<div class="w3-half">
										<select name="zageMax" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
										<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18)+1;$v<(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99)+1;++$v) { ?>
											
											<option value="<?php echo $v; ?>"<?php if($v==$u0->i_zage_max) echo ' selected'; ?>><?php echo $v.'&nbsp;'.__('years','rencontre'); ?></option>
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
								
									<option value="<?php echo $v; ?>"<?php if($u0->i_zrelation==$v) echo ' selected'; ?>><?php echo $rencOpt['for'][$v]; ?></option>
								<?php } ?>
								
								</select>
							<?php } else { ?>
							
								<div>
								<?php for($v=(isset($rencCustom['relation'])?3:0);$v<(isset($rencCustom['relation'])?count($rencOpt['for']):3);++$v) { ?>
							
									<input type="checkbox" class="rencLabelauty" name="zrelation[]" value="<?php echo $v; ?>"<?php if(strpos($u0->c_zrelation.'-',','.$v.',')!==false) echo ' checked'; ?> data-labelauty="<?php echo $rencOpt['for'][$v]; ?>" />
								<?php } ?>
								
								</div>
							<?php } ?>
							
							</div>
							<div class="w3-margin"></div>
							<?php if(isset($rencCustom['weightu']) && $rencCustom['weightu']==3) { ?>
							
							<div class="w3-right-align w3-padding-small">
								<label><?php _e('Weight unit', 'rencontre'); ?>
									<select name="weightunit" style="margin:0 8px;max-width:50%;width:auto;">
										<option value="0" <?php if(strpos($u0->t_action.'-',',weight-kg,')!==false) echo 'selected'; ?>><?php _e('Kilograms', 'rencontre'); ?></option>
										<option value="1" <?php if(strpos($u0->t_action.'-',',weight-lbs,')!==false) echo 'selected'; ?>><?php _e('Pounds', 'rencontre'); ?></option>
									</select>
								</label>
							</div>
							<?php } ?>
							<?php if(isset($rencCustom['sizeu']) && $rencCustom['sizeu']==3) { ?>
							
							<div class="w3-right-align w3-padding-small">
								<label><?php _e('Height unit', 'rencontre'); ?>
									<select name="sizeunit" style="margin:0 8px;max-width:50%;width:auto;">
										<option value="0" <?php if(strpos($u0->t_action.'-',',size-cm,')!==false) echo 'selected'; ?>><?php _e('Meter', 'rencontre'); ?></option>
										<option value="1" <?php if(strpos($u0->t_action.'-',',size-ft,')!==false) echo 'selected'; ?>><?php _e('Feet and Inches', 'rencontre'); ?></option>
									</select>
								</label>
							</div>
							<?php } ?>
							
							<div class="w3-right-align w3-padding-small">
								<label><?php _e('No interaction with members who don\'t match my search criteria','rencontre'); ?>
									<input type="checkbox" style="margin:0 8px" name="zstrict"<?php if(strpos($u0->t_action.'-',',zstrict,')!==false) echo ' checked'; ?> />
								</label>
							</div>
							<div class="w3-right-align w3-padding-small">
								<label><?php _e('No beep on the chat','rencontre'); ?>
									<input type="checkbox" style="margin:0 8px" name="nobip"<?php if(strpos($u0->t_action.'-',',nobip,')!==false) echo ' checked'; ?> />
								</label>
							</div>
							<?php if(empty($rencCustom['unmail'])) { ?>
							
							<div class="w3-right-align w3-padding-small">
								<label><?php _e('No email from this site','rencontre'); ?>
									<input type="checkbox" style="margin:0 8px" name="nomail"<?php if(strpos($u0->t_action.'-',',nomail,')!==false) echo ' checked'; ?> />
								</label>
							</div>
							<?php } ?>
							<?php if(isset($checkbox2)) echo $checkbox2; ?>
									
						</form>
						<div id="rencAlert" class="w3-panel w3-renc-wabg" style="display:none"></div>
						<div class="w3-section w3-right-align">
							<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['save']; ?>"><?php _e('Save','rencontre'); ?></button>
						</div>
					</div>
				</div>
			</div><!-- .w3-card -->
			<?php echo (!empty($scriptMap)?$scriptMap:''); ?>

		</div><!-- .w3-margin-bottom -->
		<?php if(empty($rencOpt['paus'])) { ?>

		<div class="w3-margin-bottom">
			<div class="w3-card w3-renc-blbg">
				<div class="w3-container w3-renc-lblc">
					<div class="w3-section">
						<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Profile visibility','rencontre'); ?></div>
						<form name="formPause" method="post" action="">
							<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="pause" />
							<div>
								<select name="pause" class="w3-select w3-border w3-renc-sebg">
									<option value="0" <?php if(!$u0->pause) echo ' selected'; ?>><?php _e('Profile visible','rencontre'); ?></option>
									<option value="1" <?php if($u0->pause==1) echo ' selected'; ?>><?php echo __('Profile hidden','rencontre').' ('.__('home page and search','rencontre').')'; ?></option>
									<option value="2" <?php if($u0->pause==2) echo ' selected'; ?>><?php echo __('Profile switched off','rencontre').' ('.__('home page, search and message','rencontre').')'; ?></option>
								</select>
							</div>
						</form>
						<div class="w3-section w3-right-align">
							<button id="buttonPass" class="w3-button w3-renc-mebt w3-renc-mebo" onClick="document.forms['formPause'].submit();"><?php _e('Save','rencontre'); ?></button>
						</div>
					</div>
				</div>
			</div><!-- .w3-card -->
		</div><!-- .w3-margin-bottom -->
		<?php } ?>

	</div><!-- .rencAccount -->


<?php
/*
 * Data from this Template (POST) are treated in rencontre_widget.php, static function f_updateMember($f).
 * You can replace this function by adding this example in the functions.php file of your theme :
 * 
 *		add_action('rencontre_account','my-account-save',10,1);
 *		function my-account-save($f)
 *			{
 *			// $f : my user_id
 *			global $wpdb; global $rencOpt;
 *			... see static function f_updateMember($f) for your code creation.
 *			...
 *			}
*/
?>

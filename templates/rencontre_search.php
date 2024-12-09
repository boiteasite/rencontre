<?php
/*
 * Plugin : Rencontre
 * Template : Search
 * Last Change : Rencontre 3.13
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_search.php
 * Call : rencontre_widget.php => f_cherchePlus()
 * $u0 (myself) : all + cryptID
*/
?>

	<div class="rencSearch">
		<div class="w3-card w3-renc-blbg">
			<div class="w3-container w3-renc-lblc">
				<div class="w3-section">
					<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Search','rencontre'); ?></div>
					<form id="formTrouve" name="formTrouve" method="get" action="">
					<?php if(isset($rencOpt['page_id'])) { ?>
						
						<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
					<?php } ?>
						
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id'); ?>" value="<?php echo $u0->cryptID; ?>" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['sex'])?$rencOpt['lbl']['sex']:'sex'); ?>" value="<?php echo $u0->i_sex; ?>" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex'); ?>" value="<?php echo $u0->zsex; ?>" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine'); ?>" value="" />
					<?php if(!isset($rencCustom['born'])) { ?>

						<div>
							<label><?php echo __('Age','rencontre').__(': ','rencontre').__('min','rencontre').' - '.__('max','rencontre');?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemin']; ?>">
										<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
										{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_min) echo 'selected'; ?> ><?php echo $v?> <?php _e('years','rencontre'); ?></option><?php } ?>
										
									</select>
								</div>
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
										<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
										{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_max) echo 'selected'; ?> ><?php echo $v; ?> <?php _e('years','rencontre'); ?></option><?php } ?>
										
									</select>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if(!isset($rencCustom['size'])) { ?>

						<div>
							<label><?php echo __('Height','rencontre').__(': ','rencontre').__('min','rencontre').' - '.__('max','rencontre'); ?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['tailleMin'])?$rencOpt['lbl']['tailleMin']:'tailleMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['sizemin']; ?>">
										<?php
										$b = 0;
										if(empty($u0->imperials)) {
											for($v=(!empty($rencCustom['sizemin'])?$rencCustom['sizemin']:140);$v<=(!empty($rencCustom['sizemax'])?$rencCustom['sizemax']:220);++$v) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['size_min']) && $v>=$mem['size_min']) { echo 'selected'; $b = 1; } ?>><?php echo $v; ?> <?php _e('cm','rencontre'); ?></option>
											<?php }
										}
										else {
											for($v=(!empty($rencCustom['sizemin'])?rencConvertUnit($rencCustom['sizemin'],'cm',1):55); $v<=(!empty($rencCustom['sizemax'])?rencConvertUnit($rencCustom['sizemax'],'cm',1):86.5); $v+=.5) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['size_min']) && $v>=$mem['size_min']) { echo 'selected'; $b = 1; } ?>><?php echo rencIn2Ft($v); ?></option>
											<?php }
										} ?>
										
									</select>
								</div>
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['tailleMax'])?$rencOpt['lbl']['tailleMax']:'tailleMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['sizemax']; ?>">
										<?php
										$b = 0;
										if(empty($u0->imperials)) {
											for($v=(!empty($rencCustom['sizemin'])?$rencCustom['sizemin']:140); $v<=(!empty($rencCustom['sizemax'])?$rencCustom['sizemax']:220); ++$v) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['size_max']) && $v>=$mem['size_max']) { echo 'selected'; $b = 1; } ?>><?php echo $v; ?> <?php _e('cm','rencontre'); ?></option>
											<?php }
										}
										else {
											for($v=(!empty($rencCustom['sizemin'])?rencConvertUnit($rencCustom['sizemin'],'cm',1):55); $v<=(!empty($rencCustom['sizemax'])?rencConvertUnit($rencCustom['sizemax'],'cm',1):86.5); $v+=.5) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['size_max']) && $v>=$mem['size_max']) { echo 'selected'; $b = 1; } ?>><?php echo rencIn2Ft($v); ?></option>
											<?php }
										} ?>
										
									</select>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if(!isset($rencCustom['weight'])) { ?>

						<div>
							<label><?php echo __('Weight','rencontre').__(': ','rencontre').__('min','rencontre').' - '.__('max','rencontre'); ?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['poidsMin'])?$rencOpt['lbl']['poidsMin']:'poidsMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['weightmin']; ?>">
										<?php
										$b = 0;
										if(empty($u0->imperialw)) {
											for($v=(!empty($rencCustom['weightmin'])?$rencCustom['weightmin']:40); $v<=(!empty($rencCustom['weightmax'])?$rencCustom['weightmax']:140); ++$v) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['weight_min']) && $v>=$mem['weight_min']) { echo 'selected'; $b = 1; } ?>><?php echo $v; ?> <?php _e('kg','rencontre'); ?></option>
											<?php }
										}
										else { 
											for($v=(!empty($rencCustom['weightmin'])?rencConvertUnit($rencCustom['weightmin'],'kg',1):88); $v<=(!empty($rencCustom['weightmax'])?rencConvertUnit($rencCustom['weightmax'],'kg',1):309); $v++) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['weight_min']) && $v>=$mem['weight_min']) { echo 'selected'; $b = 1; }  ?>><?php echo $v; ?> <?php _e('lbs','rencontre'); ?></option>
											<?php }
										} ?>
														
									</select>
								</div>
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['poidsMax'])?$rencOpt['lbl']['poidsMax']:'poidsMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['weightmax']; ?>">
										<?php
										$b = 0;
										if(empty($u0->imperialw)) {
											for($v=(!empty($rencCustom['weightmin'])?$rencCustom['weightmin']:40); $v<=(!empty($rencCustom['weightmax'])?$rencCustom['weightmax']:140); ++$v) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['weight_max']) && $v>=$mem['weight_max']) { echo 'selected'; $b = 1; } ?>><?php echo $v; ?> <?php _e('kg','rencontre'); ?></option>
											<?php }
										}
										else { 
											for($v=(!empty($rencCustom['weightmin'])?rencConvertUnit($rencCustom['weightmin'],'kg',1):88); $v<=(!empty($rencCustom['weightmax'])?rencConvertUnit($rencCustom['weightmax'],'kg',1):309); $v++) {
												?><option value="<?php echo $v; ?>" <?php if(!$b && !empty($mem['weight_max']) && $v>=$mem['weight_max']) { echo 'selected'; $b = 1; } ?>><?php echo $v; ?> <?php _e('lbs','rencontre'); ?></option>
											<?php }
										} ?>
										
									</select>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if(isset($rencCustom['sex'])) { ?>

						<div>
							<label><?php _e('Gender','rencontre');?></label>
							<select name="<?php echo (!empty($rencOpt['lbl']['z2sex'])?$rencOpt['lbl']['z2sex']:'z2sex'); ?>" class="w3-select w3-border w3-renc-sebg">
								<option value="">-</option>
								<?php for($v=2;$v<count($rencOpt['iam']);++$v) {
									if($v==$u0->i_sex && !empty($rencCustom['hetero'])) continue;
								?><option value="<?php echo $v; ?>" <?php if(isset($mem['z2sex']) && $mem['z2sex']==$v) echo 'selected'; ?>><?php echo $rencOpt['iam'][$v]; ?></option><?php } ?>
							
							</select>
						</div>
					<?php } else if(isset($rencCustom['sexAll'])) { ?>
						<div>
							<label><?php _e('Gender','rencontre');?></label>
							<select name="<?php echo (!empty($rencOpt['lbl']['z2sex'])?$rencOpt['lbl']['z2sex']:'z2sex'); ?>" class="w3-select w3-border w3-renc-sebg">
								<option value="">-</option>
								<option value="0" <?php if(isset($mem['z2sex']) && $mem['z2sex']!=='' && $mem['z2sex']==0) echo 'selected'; ?>><?php echo $rencOpt['iam'][0]; ?></option>
								<option value="1" <?php if(isset($mem['z2sex']) && $mem['z2sex']==1) echo 'selected'; ?>><?php echo $rencOpt['iam'][1]; ?></option>
							</select>
						</div>
					<?php } ?>
					<?php if(!isset($rencCustom['country'])) { ?>
					
						<div id="rencCountryTr">
							<label><?php _e('Country','rencontre');?></label>
							<select id="rencPays" name="<?php echo (!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['country']; ?>">
							<?php RencontreWidget::f_pays((!empty($mem['country'])?$mem['country']:$u0->country)); ?>
							
							</select>
						</div>
					<?php } ?>
					<?php if(!isset($rencCustom['place'])) { ?>
						<?php if(!isset($rencCustom['region'])) { ?>
						
						<div id="rencRegionTr">
							<label><?php _e('Region','rencontre');?></label>
							<select id="regionSelect" name="<?php echo (!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region'); ?>" class="w3-select w3-border w3-renc-sebg">
							<?php RencontreWidget::f_regionBDD((!empty($mem['region'])?'_'.$mem['region']:0),(!empty($mem['country'])?$mem['country']:$u0->country)); ?>
							
							</select>
						</div>
						<?php } ?>
						
						<div id="rencCityTr">
							<label><?php _e('City','rencontre');?></label>
							<input id="rencVille" name="<?php echo (!empty($rencOpt['lbl']['ville'])?$rencOpt['lbl']['ville']:'ville'); ?>" type="text" class="w3-input w3-renc-inbg" value="<?php if(!empty($mem['city'])) echo $mem['city']; ?>" <?php if(!empty($map)) echo $onClick['city']; ?>/>
						</div>
					<?php } ?>
					<?php if(!empty($map)) echo $map; ?>
			
						<div>
							<label><?php _e('Relation','rencontre');?></label>
							<select name="<?php echo (!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation'); ?>" class="w3-select w3-border w3-renc-sebg">
								<option value="" selected>-</option>
								<?php for($v=(isset($rencCustom['relation'])?3:0);$v<(isset($rencCustom['relation'])?count($rencOpt['for']):3);++$v) {
								?><option value="<?php echo $v; ?>" <?php if(!empty($mem['relation']) && $mem['relation']==$v) echo 'selected'; ?>><?php echo $rencOpt['for'][$v]; ?></option><?php } ?>
								
							</select>
						</div>
					<?php echo $moreSearch1; ?>
			
						<div>
							<label><?php _e('Word in the ad','rencontre');?></label>
							<input type="text" name="<?php echo (!empty($rencOpt['lbl']['mot'])?$rencOpt['lbl']['mot']:'mot'); ?>" class="w3-input w3-renc-inbg" />
						</div>
						<div>
							<label><?php _e('Alias','rencontre');?></label>
							<input type="text" name="<?php echo (!empty($rencOpt['lbl']['pseudo'])?$rencOpt['lbl']['pseudo']:'pseudo'); ?>" class="w3-input w3-renc-inbg" />
						</div>
						<div class="w3-padding-small">
							<input type="checkbox" name="<?php echo (!empty($rencOpt['lbl']['photo'])?$rencOpt['lbl']['photo']:'photo'); ?>" style="margin:0 8px" value="1" <?php if(!empty($mem['photo'])) echo 'checked'; ?> />
							<label><?php _e('Only with picture','rencontre');?></label>
						</div>
						<div class="w3-section w3-right-align">
						<?php if(empty($blockSearch)) { ?>
						
							<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['find']; ?>"><?php _e('Find','rencontre'); ?></button>
						<?php } else { ?>

							<button type="button" class="w3-button w3-renc-mebt w3-opacity w3-disable w3-renc-mebo" onClick="f_modalWarn('<?php echo addslashes($blockSearch); ?>')"><?php _e('Find','rencontre'); ?></button>
						<?php } ?>

						</div>
					</form>
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

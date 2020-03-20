<?php
/*
 * Plugin : Rencontre
 * Template : Search
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_search.php
 * $u0 : ID, cryptID, i_sex, i_zsex, c_zsex, i_zage_min, i_zage_max, e_lat, e_lon, zsex, homo, c_pays, country, age, agemin, agemax
*/
?>

	<div class="rencSearch w3-container w3-padding-small">
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
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['homo'])?$rencOpt['lbl']['homo']:'homo'); ?>" value="<?php echo $u0->homo; ?>" />
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine'); ?>" value="0" />
					<?php if(!isset($rencCustom['born'])) { ?>

						<div>
							<label><?php _e('Age','rencontre');?>&nbsp;:&nbsp;<?php _e('min','rencontre');?>&nbsp;-&nbsp;<?php _e('max','rencontre');?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemin']; ?>">
										<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
										{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_min) echo 'selected'; ?> ><?php echo $v?>&nbsp;<?php _e('years','rencontre'); ?></option><?php } ?>
										
									</select>
								</div>
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
										<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
										{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_max) echo 'selected'; ?> ><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option><?php } ?>
										
									</select>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if(!isset($rencCustom['size'])) { ?>

						<div>
							<label><?php _e('Height','rencontre');?>&nbsp;:&nbsp;<?php _e('min','rencontre');?>&nbsp;-&nbsp;<?php _e('max','rencontre');?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['tailleMin'])?$rencOpt['lbl']['tailleMin']:'tailleMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['sizemin']; ?>">
										<?php for($v=140;$v<221;++$v) { 
											if(empty($rencCustom['sizeu']))
											{ ?><option value="<?php echo $v; ?>" <?php if(!empty($mem['size_min']) && $mem['size_min']==$v) echo 'selected'; ?>><?php echo $v; ?>&nbsp;<?php _e('cm','rencontre'); ?></option><?php }
											else { ?><option value="<?php echo $v; ?>" <?php if(!empty($mem['size_min']) && $mem['size_min']==$v) echo 'selected'; ?>><?php echo (floor($v/24-1.708)); ?>&nbsp;<?php _e('ft','rencontre'); ?>&nbsp;<?php echo (round(((($v/24-1.708)-floor($v/24-1.708))*12),1)); ?>&nbsp;<?php _e('in','rencontre'); ?></option><?php } } ?>
										
									</select>
								</div>
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['tailleMax'])?$rencOpt['lbl']['tailleMax']:'tailleMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['sizemax']; ?>">
										<?php for($v=140;$v<221;++$v) {
											if(empty($rencCustom['sizeu']))
											{ ?><option value="<?php echo $v; ?>" <?php if(!empty($mem['size_max']) && $mem['size_max']==$v) echo 'selected'; ?>><?php echo $v; ?>&nbsp;<?php _e('cm','rencontre')?></option><?php }
											else { ?><option value="<?php echo $v; ?>" <?php if(!empty($mem['size_max']) && $mem['size_max']==$v) echo 'selected'; ?>><?php echo (floor($v/24-1.708)); ?>&nbsp;<?php _e('ft','rencontre'); ?>&nbsp;<?php echo (round(((($v/24-1.708)-floor($v/24-1.708))*12),1)); ?>&nbsp;<?php _e('in','rencontre'); ?></option><?php } } ?>
										
									</select>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if(!isset($rencCustom['weight'])) { ?>

						<div>
							<label><?php _e('Weight','rencontre');?>&nbsp;:&nbsp;<?php _e('min','rencontre');?>&nbsp;-&nbsp;<?php _e('max','rencontre');?></label>
							<div class="w3-row">
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['poidsMin'])?$rencOpt['lbl']['poidsMin']:'poidsMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['weightmin']; ?>">
										<?php for($v=40;$v<141;++$v) {
											if(empty($rencCustom['weightu']))
											{ ?><option value="<?php echo ($v+100); ?>" <?php if(!empty($mem['weight_min']) && $mem['weight_min']==$v+100) echo 'selected'; ?>><?php echo $v; ?>&nbsp;<?php _e('kg','rencontre'); ?></option><?php }
											else { ?><option value="<?php echo ($v+100); ?>" <?php if(!empty($mem['weight_min']) && $mem['weight_min']==$v+100) echo 'selected'; ?>><?php echo ($v*2+10); ?>&nbsp;<?php _e('lbs','rencontre'); ?></option><?php } } ?>
														
									</select>
								</div>
								<div class="w3-half">
									<select name="<?php echo (!empty($rencOpt['lbl']['poidsMax'])?$rencOpt['lbl']['poidsMax']:'poidsMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['weightmax']; ?>">
										<?php for($v=40;$v<141;++$v) {
											if(empty($rencCustom['weightu']))
											{ ?><option value="<?php echo ($v+100); ?>" <?php if(!empty($mem['weight_max']) && $mem['weight_max']==$v+100) echo 'selected'; ?>><?php echo $v; ?>&nbsp;<?php _e('kg','rencontre'); ?></option><?php }
											else { ?><option value="<?php echo ($v+100); ?>" <?php if(!empty($mem['weight_max']) && $mem['weight_max']==$v+100) echo 'selected'; ?>><?php echo ($v*2+10); ?>&nbsp;<?php _e('lbs','rencontre'); ?></option><?php } } ?>
										
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
								?><option value="<?php echo $v; ?>" <?php if(!empty($mem['zsex']) && $mem['zsex']==$v) echo 'selected'; ?>><?php echo $rencOpt['iam'][$v]; ?></option><?php } ?>
							
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
							<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['find']; ?>"><?php _e('Find','rencontre'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

<?php
/*
 * Plugin : Rencontre
 * Template : Sidebar Quick Search
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_sidebar_quick_search.php
 * $u0 : ID, display_name, user_login, c_ip, c_pays, c_region, c_ville, i_sex, d_naissance, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, t_action, sourireIn, contactIn, visite, looking, forwhat, homo, zsex, country, agemin, agemax
*/
?>


			<div class="w3-medium w3-border-grey w3-renc-lblc">
				<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Quick Search','rencontre');?></div>
				<form id="formMonAccueil" name="formMonAccueil" method="get" action="">
				<?php if(isset($rencOpt['page_id'])) { ?>
				
					<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
				<?php } ?>
					
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex'); ?>" value="<?php echo $u0->zsex; ?>" />
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['homo'])?$rencOpt['lbl']['homo']:'homoc'); ?>" value="<?php echo $u0->homo; ?>" />
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine'); ?>" value="0" />
				<?php if(!isset($rencCustom['born'])) { ?>
					
					<div>
						<label><?php _e('From','rencontre');?>&nbsp;</label>
						<select name="<?php echo (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemin']; ?>">
							<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
							{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_min) echo 'selected'; ?> ><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option><?php } ?>
							
						</select>
					</div>
					<div>
						<label>&nbsp;<?php _e('To','rencontre');?>&nbsp;</label>
						<select name="<?php echo (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
							<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
							{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_max) echo 'selected'; ?> ><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option><?php } ?>
							
						</select>
					</div>
				<?php } ?>
				<?php if(!isset($rencCustom['country'])) { ?>
				
					<div>
						<label><?php _e('Country','rencontre');?>&nbsp;</label>
						<select name="<?php echo (!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['country']; ?>">
							<?php RencontreWidget::f_pays((!empty($mem['country'])?$mem['country']:$u0->country)); ?>
							
						</select>
					</div>
				<?php } ?>
				<?php if(!isset($rencCustom['place']) && !isset($rencCustom['region'])) { ?>

					<div>
						<label><?php _e('Region','rencontre');?>&nbsp;</label>
						<select id="regionSelectSide" name="<?php echo (!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region'); ?>" class="w3-select w3-border w3-renc-sebg">
							<?php RencontreWidget::f_regionBDD((!empty($mem['region'])?'_'.$mem['region']:0),(!empty($mem['country'])?$mem['country']:$u0->country)); ?>
							
						</select>
					</div>
				<?php } ?>
				<?php if(isset($rencCustom['relationQ']) && isset($rencOpt['for'])) { ?>
					
					<div>
						<label><?php _e('Relation','rencontre'); ?>&nbsp;</label>
						<select name="<?php echo (!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation'); ?>" class="w3-select w3-border w3-renc-sebg">
							<option value="">-</option>
						<?php for($v=(isset($rencCustom['relation'])?3:0);$v<(isset($rencCustom['relation'])?count($rencOpt['for']):3);++$v)
						{ ?><option value="<?php echo $v; ?>"><?php echo $rencOpt['for'][$v]; ?></option><?php } ?>
						
						</select>
					</div>
				<?php } ?>
				<?php if(!empty($profilQuickSearch1)) { ?>
				
					<div>
						<label><?php echo $qs1->c_label; ?>&nbsp;</label>
						<select id="profilQS1" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" class="w3-select w3-border w3-renc-sebg">
						<?php echo $profilQuickSearch1; ?>
						</select>
					</div>
				<?php } else if(!empty($profilQuickSearch1D)) { ?>
				
					<div>
						<label><?php echo $qs1->c_label; ?>&nbsp;(<?php echo ($profilQuickSearch1D=='after'?__('After','rencontre'):__('Before','rencontre')); ?>)</label>
						<input id="profilQS1" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" type="date" class="w3-input w3-renc-inbg" />
					</div>
				<?php } ?>
				<?php if(!empty($profilQuickSearch2)) { ?>
				
					<div>
						<label><?php echo $qs2->c_label; ?>&nbsp;</label>
						<select id="profilQS2" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" class="w3-select w3-border w3-renc-sebg">
						<?php echo $profilQuickSearch2; ?>
						</select>
					</div>
				<?php } else if(!empty($profilQuickSearch2D)) { ?>
				
					<div>
						<label><?php echo $qs2->c_label; ?>&nbsp;(<?php echo ($profilQuickSearch2D=='after'?__('After','rencontre'):__('Before','rencontre')); ?>)</label>
						<input id="profilQS2" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" type="date" class="w3-input w3-renc-inbg" />
					</div>
				<?php } ?>
					
					<div class="w3-section w3-right-align">
						<button class="<?php echo $find['class']; ?>" onClick="<?php echo $onClick['find']; ?>"><?php _e('Find','rencontre'); ?></button>
					</div>
				</form>
			</div>
		</div><!-- .w3-container --> <?php // open in rencontre_sidebar_top.php ?>
	</div><!-- .w3-card .w3-white --> <?php // open in rencontre_sidebar_top.php ?>

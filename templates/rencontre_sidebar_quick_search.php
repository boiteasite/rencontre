<?php
/*
 * Plugin : Rencontre
 * Template : Sidebar Quick Search
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_sidebar_quick_search.php
 * Call : rencontre_widget.php => RencontreSidebarWidget::widget()
 * $u0 : ID, display_name, user_login, c_ip, c_pays, c_region, c_ville, i_sex, d_naissance, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, t_action, sourireIn, contactIn, visite, looking, forwhat, homo, zsex, country, agemin, agemax, profilQS1, profilQS2
*/
?>

<?php if(empty($blockQSearch) || $blockQSearch!==true) { ?>

			<div class="w3-medium w3-border-grey w3-renc-lblc">
				<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Quick Search','rencontre');?></div>
				<form id="formMonAccueil" name="formMonAccueil" method="get" action="">
				<?php if(isset($rencOpt['page_id'])) { ?>
				
					<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
				<?php } ?>
					
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex'); ?>" value="<?php echo $u0->zsex; ?>" />
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['homo'])?$rencOpt['lbl']['homo']:'homo'); ?>" value="<?php echo $u0->homo; ?>" />
					<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['pagine'])?$rencOpt['lbl']['pagine']:'pagine'); ?>" value="" />
				<?php if(!isset($rencCustom['born'])) { ?>
					
					<div>
						<label><?php _e('From','rencontre');?> </label>
						<select name="<?php echo (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemin']; ?>">
							<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
							{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_min) echo 'selected'; ?> ><?php echo $v; ?> <?php _e('years','rencontre'); ?></option><?php } ?>
							
						</select>
					</div>
					<div>
						<label> <?php _e('To','rencontre');?> </label>
						<select name="<?php echo (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
							<?php for($v=(isset($rencCustom['agemin'])?intval($rencCustom['agemin']):18);$v<=(isset($rencCustom['agemax'])?intval($rencCustom['agemax']):99);++$v)
							{ ?><option value="<?php echo $v; ?>" <?php if($v==$u0->i_zage_max) echo 'selected'; ?> ><?php echo $v; ?> <?php _e('years','rencontre'); ?></option><?php } ?>
							
						</select>
					</div>
				<?php } ?>
				<?php if(!isset($rencCustom['country'])) { ?>
				
					<div>
						<label><?php _e('Country','rencontre');?> </label>
						<select name="<?php echo (!empty($rencOpt['lbl']['pays'])?$rencOpt['lbl']['pays']:'pays'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['country']; ?>">
							<?php RencontreWidget::f_pays((!empty($mem['country'])?$mem['country']:$u0->country)); ?>
							
						</select>
					</div>
				<?php } ?>
				<?php if(!isset($rencCustom['place']) && !isset($rencCustom['region'])) { ?>

					<div>
						<label><?php _e('Region','rencontre');?> </label>
						<select id="regionSelectSide" name="<?php echo (!empty($rencOpt['lbl']['region'])?$rencOpt['lbl']['region']:'region'); ?>" class="w3-select w3-border w3-renc-sebg">
							<?php RencontreWidget::f_regionBDD((!empty($mem['region'])?'_'.$mem['region']:0),(!empty($mem['country'])?$mem['country']:$u0->country)); ?>
							
						</select>
					</div>
				<?php } ?>
				<?php if(isset($rencCustom['relationQ']) && isset($rencOpt['for'])) { ?>
					
					<div>
						<label><?php _e('Relation','rencontre'); ?> </label>
						<select name="<?php echo (!empty($rencOpt['lbl']['relation'])?$rencOpt['lbl']['relation']:'relation'); ?>" class="w3-select w3-border w3-renc-sebg">
							<option value="">-</option>
						<?php for($v=(isset($rencCustom['relation'])?3:0);$v<(isset($rencCustom['relation'])?count($rencOpt['for']):3);++$v)
						{ ?><option value="<?php echo $v; ?>"><?php echo $rencOpt['for'][$v]; ?></option><?php } ?>
						
						</select>
					</div>
				<?php } ?>
				<?php if(!empty($profilQuickSearch1)) { ?>
				
					<div>
						<label><?php echo $qs1->c_label; ?> </label>
						<select id="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" class="w3-select w3-border w3-renc-sebg">
						<?php echo $profilQuickSearch1; ?>
						</select>
					</div>
				<?php } else if(!empty($profilQuickSearch1D)) { ?>
				
					<div>
						<label><?php echo $qs1->c_label; ?> (<?php echo ($profilQuickSearch1D=='after'?__('After','rencontre'):__('Before','rencontre')); ?>)</label>
						<input id="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" type="date" class="w3-input w3-renc-inbg" />
					</div>
				<?php } else if(!empty($profilQuickSearch1N) && isset($profilQuickSearch1N[4])) { ?>
				
					<div>
						<label><?php echo $qs1->c_label; ?> (<?php echo ($profilQuickSearch1N[0]=='min'?__('Greater than','rencontre'):__('Less than','rencontre')); ?>)</label>
						<select id="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>1" class="w3-select w3-border w3-renc-sebg">
							<option value="">-</option>
						<?php $c=1; for($v=(!empty($profilQuickSearch1N[1])?$profilQuickSearch1N[1]:0);$v<=(!empty($profilQuickSearch1N[2])?$profilQuickSearch1N[2]:0);$v+=(!empty($profilQuickSearch1N[3])?$profilQuickSearch1N[3]:1))
						{ ?><option value="<?php echo $c; ?>" <?php if(isset($u0->profilQS1) && $c==$u0->profilQS1) echo 'selected'; ?>><?php echo $v.' '.$profilQuickSearch1N[4]; ?></option><?php ++$c; } ?>
						</select>
					</div>
				<?php } ?>
				<?php if(!empty($profilQuickSearch2)) { ?>
				
					<div>
						<label><?php echo $qs2->c_label; ?> </label>
						<select id="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" class="w3-select w3-border w3-renc-sebg">
						<?php echo $profilQuickSearch2; ?>
						</select>
					</div>
				<?php } else if(!empty($profilQuickSearch2D)) { ?>
				
					<div>
						<label><?php echo $qs2->c_label; ?> (<?php echo ($profilQuickSearch2D=='after'?__('After','rencontre'):__('Before','rencontre')); ?>)</label>
						<input id="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" type="date" class="w3-input w3-renc-inbg" />
					</div>
				<?php } else if(!empty($profilQuickSearch2N) && isset($profilQuickSearch2N[4])) { ?>
				
					<div>
						<label><?php echo $qs2->c_label; ?> (<?php echo ($profilQuickSearch2N[0]=='min'?__('Greater than','rencontre'):__('Less than','rencontre')); ?>)</label>
						<select id="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" name="<?php echo (!empty($rencOpt['lbl']['profilQS'])?$rencOpt['lbl']['profilQS']:'profilQS'); ?>2" class="w3-select w3-border w3-renc-sebg">
							<option value="">-</option>
						<?php $c=1; for($v=(!empty($profilQuickSearch2N[1])?$profilQuickSearch2N[1]:0);$v<=(!empty($profilQuickSearch2N[2])?$profilQuickSearch2N[2]:0);$v+=(!empty($profilQuickSearch2N[3])?$profilQuickSearch2N[3]:1))
						{ ?><option value="<?php echo $c; ?>" <?php if(isset($u0->profilQS2) && $c==$u0->profilQS2) echo 'selected'; ?>><?php echo $v.' '.$profilQuickSearch2N[4]; ?></option><?php ++$c; } ?>
						</select>
					</div>
				<?php } ?>
					
				</form>
				<div class="w3-section w3-right-align">
					<?php if(empty($blockQSearch)) { ?>
					
					<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['find']; ?>"><?php _e('Find','rencontre'); ?></button>
					<?php } else { ?>

					<button class="w3-button w3-renc-mebt w3-opacity w3-disable w3-renc-mebo" onClick="f_modalWarn('<?php echo addslashes($blockQSearch); ?>')"><?php _e('Find','rencontre'); ?></button>
					<?php } ?>
					
				</div>
			</div>
<?php } ?>

		</div><!-- .w3-container --> <?php // open in rencontre_sidebar_top.php ?>
	</div><!-- .w3-card .w3-white --> <?php // open in rencontre_sidebar_top.php ?>

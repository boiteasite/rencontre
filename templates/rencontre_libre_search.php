<?php
/*
 * Plugin : Rencontre
 * Template : Libre Search
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_libre_search.php
*/
?>

	<script type="text/javascript">
	function f_min(f,x,y,z){var c=0,d=document.forms[x][y],e=document.forms[x][z];f=parseInt(f);for(v=0;v<e.length;v++){if(parseInt(d.options[v].value)==f)c=v;if(parseInt(e.options[v].value)<=f)e.options[v].disabled=true;else e.options[v].disabled=false;}if(f>parseInt(e.options[e.selectedIndex].value))e.selectedIndex=c;};
	function f_max(f,x,y,z){var c=0,d=document.forms[x][z],e=document.forms[x][y];f=parseInt(f);for(v=0;v<e.length;v++){if(parseInt(d.options[v].value)==f)c=v;if(parseInt(e.options[v].value)>=f)e.options[v].disabled=true;else e.options[v].disabled=false;}if(f<parseInt(e.options[e.selectedIndex].value))e.selectedIndex=c;};
	</script>
	<div id="rencSearchLibre" class="rencLibreSearch w3-container w3-padding-small">
		<div class="w3-card w3-renc-blbg">
			<div class="w3-container">
				<div class="w3-section w3-renc-lblc">
					<form name="rencSearch" method="get" action="">
						<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="searchLibre" />
						<?php if(!empty($rencOpt['page_id'])) { ?>
						
						<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
						<?php } ?>
						<div class="w3-row-padding">
							<div class="rencSearchBloc<?php echo (empty($rencCustom['born'])?' w3-third':''); ?>">
								<label><?php _e('I\'m looking for','rencontre'); ?>&nbsp;</label>
								<select name="<?php echo (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex'); ?>" class="w3-select w3-border w3-renc-sebg">
								<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>

									<option value="<?php echo $v; ?>"><?php echo $rencOpt['iam'][$v]; ?></option>
								<?php } ?>

								</select>
							</div>
							<?php if(empty($rencCustom['born'])) { ?>

							<div class="rencSearchBloc w3-third">
								<label><?php _e('between','rencontre'); ?>&nbsp;</label>
								<select name="<?php echo (!empty($rencOpt['lbl']['ageMin'])?$rencOpt['lbl']['ageMin']:'ageMin'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemin']; ?>">
								<?php for($v=20;$v<91;$v+=5) { ?>
								
									<option value="<?php echo $v; ?>"<?php if($v==20) echo ' selected'; ?>><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option>
								<?php } ?>
								
								</select>
							</div>
							<div class="rencSearchBloc w3-third">
								<label>&nbsp;<?php _e('and','rencontre'); ?>&nbsp;</label>
								<select name="<?php echo (!empty($rencOpt['lbl']['ageMax'])?$rencOpt['lbl']['ageMax']:'ageMax'); ?>" class="w3-select w3-border w3-renc-sebg" onChange="<?php echo $onClick['agemax']; ?>">
								<?php for($v=25;$v<96;$v+=5) { ?>
								
									<option value="<?php echo $v; ?>"<?php if($v==95) echo ' selected'; ?>><?php echo $v; ?>&nbsp;<?php _e('years','rencontre'); ?></option>
								<?php } ?>
								
								</select>
							</div>
							<?php } ?>
							
						</div>
						<div class="rencSearchSubmit w3-section w3-right-align">
							<input type="submit" class="w3-button w3-renc-mebt w3-renc-mebo" value="<?php _e('Search','rencontre'); ?>" />
						</div>
					</form>
				</div>
			</div>
			<div class="w3-container<?php if(empty($searchResult)) echo ' w3-hide'; ?>">
				<div class="w3-section w3-border-top">
					<div class="w3-row">
					<?php echo $searchResult; ?>
					</div>
				</div>
			</div>
		</div><!-- .w3-card -->
	</div><!-- .w3-container -->

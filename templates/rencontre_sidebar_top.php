<?php
/*
 * Plugin : Rencontre
 * Template : Sidebar Top
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_sidebar_top.php
 * $u0 : ID, display_name, user_login, c_ip, c_pays, c_ville, i_sex, d_naissance, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, t_action, sourireIn, contactIn, visite, looking, forwhat, homo, zsex, country, age, agemin, agemax
*/
?>


	<div class="rencSidebar w3-card w3-renc-blbg">
		<div class="w3-panel w3-renc-mebg w3-hide-large w3-hide-medium">
			<div class="w3-right-align w3-padding-small">
				<i class="<?php echo (empty($hideSideMobile)?'fas fa-angle-down':'fas fa-angle-up'); ?>" style="cursor:pointer;" onclick="<?php echo $onClick['hideSide']; ?>"></i>
			</div>
		</div>
		<div class="w3-container<?php if(!empty($hideSideMobile)) echo' w3-hide-small'; ?>">
			<div class="w3-cell-row w3-border-bottom w3-padding-small w3-renc-line">
			<?php if($u0->i_photo!=0) { ?>

				<div class="w3-cell w3-cell-middle" style="width:60px;">
					<img class="w3-circle" src="<?php echo $rencDiv['baseurl'].'/portrait/'.floor(($u0->ID)/1000).'/'.Rencontre::f_img(($u0->ID*10).'-mini').'.jpg?r='.rand(); ?>" alt="<?php echo $u0->display_name; ?>" alt="<?php echo $u0->display_name; ?>" />
				</div>
			<?php } else { ?>

				<div class="w3-cell w3-cell-middle">
					<img class="w3-circle" style="width:60px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u0->display_name; ?>" />
				</div>
			<?php } ?>
				<div class="w3-cell w3-cell-middle w3-padding-small" style="text-transform:capitalize;word-break:break-all;">
					<div class="w3-large"><?php echo $u0->display_name; ?></div>
					<?php if(!isset($rencCustom['born']) && strpos($u0->d_naissance,'0000')===false) { ?>
					
					<div><?php echo Rencontre::f_age($u0->d_naissance); ?>&nbsp;<?php _e('years','rencontre'); ?></div>
					<?php } ?>
					<?php if(!isset($rencCustom['place'])) { ?>
					
					<div><?php echo $u0->c_ville; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="myAction w3-medium w3-border-bottom w3-renc-line">
				<div class="firstMaj">
				<?php if($u0->looking) { ?>
					<?php _e('I\'m looking for','rencontre'); ?>&nbsp;<em><?php echo $u0->looking; ?></em>
				<?php } ?>
				<?php if($u0->forwhat) { ?>
					&nbsp;<?php _e('for','rencontre'); ?>&nbsp;<em><?php echo $u0->forwhat; ?></em>
				<?php } ?>
				
				</div>
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['sourireIn']; ?>">
						<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) echo stripslashes($rencCustom['smiw1']);
						else _e('Smile','rencontre'); ?>
						<?php if(count($u0->sourireIn)>49) { ?>
						
						:&nbsp;>50
						<?php } else { ?>
						
						:&nbsp;<?php echo count($u0->sourireIn); ?>
						<?php } ?>
						
					</a>
				</div>
				
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['visite']; ?>">
						<?php if(!empty($rencCustom['loow']) && !empty($rencCustom['loow1'])) echo stripslashes($rencCustom['loow1']);
						else _e('Look','rencontre'); ?>
						<?php if(count($u0->visite)>49) { ?>
						
						:&nbsp;>50
						<?php } else { ?>
						
						:&nbsp;<?php echo count($u0->visite); ?>
						<?php } ?>
						
					</a>
				</div>
				<?php if(empty($rencCustom['creq'])) { ?>
				
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['contactIn']; ?>">
						<?php _e('Contact requests','rencontre'); ?>
						<?php if(count($u0->contactIn)>49) { ?>
						
						:&nbsp;>50
						<?php } else { ?>
						
						:&nbsp;<?php echo count($u0->contactIn); ?>
						<?php } ?>
						
					</a>
				</div>
				<?php } ?>
				
			</div>
			<div class="myAction w3-medium w3-border-bottom w3-renc-line">
				<?php if(empty($rencCustom['smile'])) { ?>
				
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['sourireOut']; ?>">
						<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw2'])) echo stripslashes($rencCustom['smiw2']);
						else _e('Who I smiled ?','rencontre'); ?>
						
					</a>
				</div>
				<?php } ?>
				<?php if(empty($rencCustom['creq'])) { ?>

				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['contactOut']; ?>"><?php _e('Who I asked for a contact ?','rencontre');?></a>
				</div>
				<?php } ?>

				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['bloque']; ?>"><?php _e('Who I\'ve blocked ?','rencontre');?></a>
				</div>
			</div>
			
		
<?php
// </div><!-- .w3-container --> closed in rencontre_sidebar_quick_search
// </div><!-- .w3-card .w3-white --> closed in rencontre_sidebar_quick_search
?>

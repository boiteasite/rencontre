<?php
/*
 * Plugin : Rencontre
 * Template : Sidebar Top
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_sidebar_top.php
 * Call : rencontre_widget.php => RencontreSidebarWidget::widget()
 * $u0 : ID, display_name, user_login, c_ip, c_pays, c_ville, i_sex, d_naissance, i_zsex, c_zsex, i_zage_min, i_zage_max, i_zrelation, c_zrelation, i_photo, miniPhoto, miniPhotoWebp, t_action, sourireIn, contactIn, visite, looking, forwhat, homo, zsex, country, age, agemin, agemax, pause
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
				<?php if(!empty($u0->miniPhotoWebp)) { ?>
				
					<picture>
						<source class="w3-circle" srcset="<?php echo $u0->miniPhotoWebp; ?>" type="image/webp" alt="<?php echo $u0->display_name; ?>">
						<source class="w3-circle" srcset="<?php echo $u0->miniPhoto; ?>" type="image/jpeg" alt="<?php echo $u0->display_name; ?>"> 
						<img class="w3-circle" src="<?php echo $u0->miniPhoto; ?>" srcset="<?php echo $u0->miniPhotoRetina; ?>" alt="<?php echo $u0->display_name; ?>" />
					</picture>
				<?php } else { ?>
				
					<img class="w3-circle" src="<?php echo $u0->miniPhoto; ?>" srcset="<?php echo $u0->miniPhotoRetina; ?>" alt="<?php echo $u0->display_name; ?>" />
				<?php } ?>
				
				</div>
			<?php } else { ?>

				<div class="w3-cell w3-cell-middle">
					<img class="w3-circle" style="width:60px;" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u0->display_name; ?>" />
				</div>
			<?php } ?>
				<div class="w3-cell w3-cell-middle w3-padding-small" style="text-transform:capitalize;word-break:break-all;">
					<div class="w3-large"><?php echo $u0->display_name; ?></div>
					<?php if(!isset($rencCustom['born']) && strpos($u0->d_naissance.'-','0000')===false) { ?>
					
					<div><?php echo Rencontre::f_age($u0->d_naissance); ?> <?php _e('years','rencontre'); ?></div>
					<?php } ?>
					<?php if(!isset($rencCustom['place'])) { ?>
					
					<div><?php echo $u0->c_ville; ?></div>
					<?php } ?>
				</div>
			</div>
			<?php if(!empty($u0->pause)) { ?>
			
			<div class="w3-panel w3-renc-wabg w3-center"><?php echo ($u0->pause==1?__('Profile hidden','rencontre'):__('Profile switched off','rencontre')); ?></div>
			<?php } ?>
			<?php if(!empty($rencCustom['menu']) && $nbmsg=RencontreWidget::f_count_inbox($current_user->user_login)!=0) { ?>
			<div class="w3-section"><span class="w3-badge w3-renc-mebc" style="margin-right:4px"><?php echo $nbmsg; ?></span><a href="<?php echo $onClick['msg']; ?>" /><?php _e(($nbmsg>1?'messages':'message'),'rencontre'); ?></a></div>

			<?php } ?>
			
			<div class="myAction w3-medium w3-border-bottom w3-renc-line">
				<div class="firstMaj">
				<?php if($u0->looking) { ?>
					<?php _e('I\'m looking for','rencontre'); ?> <em><?php echo $u0->looking; ?></em>
				<?php } ?>
				<?php if($u0->forwhat) { ?>
				<?php echo ' '.__('for','rencontre'); ?> <em><?php echo $u0->forwhat; ?></em>
				<?php } ?>
				
				</div>
				<?php if(empty($rencCustom['smile'])) { ?>

				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['sourireIn']; ?>">
						<?php if($t=rencTranslate('smiw1')) echo $t; else _e('Smile','rencontre'); _e(': ','rencontre'); ?>
						<?php if(count($u0->sourireIn)>49) echo '>50'; else echo count($u0->sourireIn); ?>
						
					</a>
				</div>
				<?php } ?>
				
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['visite']; ?>">
						<?php if($t=rencTranslate('loow1')) echo $t; else _e('Look','rencontre'); _e(': ','rencontre'); ?>
						<?php if(count($u0->visite)>49) echo '>50'; else echo count($u0->visite); ?>
						
					</a>
				</div>
				<?php if(empty($rencCustom['creq'])) { ?>
				
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['contactIn']; ?>">
						<?php _e('Contact requests','rencontre'); _e(': ','rencontre'); ?>
						<?php if(count($u0->contactIn)>49) echo '>50'; else echo count($u0->contactIn); ?>
						
					</a>
				</div>
				<?php } ?>
				
			</div>
			<div class="myAction w3-medium w3-border-bottom w3-renc-line">
				<?php if(empty($rencCustom['smile'])) { ?>
				
				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['sourireOut']; ?>">
						<?php if($t=rencTranslate('smiw2')) echo $t; else _e('Who I smiled?','rencontre'); ?>
						
					</a>
				</div>
				<?php } ?>
				<?php if(empty($rencCustom['creq'])) { ?>

				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['contactOut']; ?>"><?php _e('Who I asked for a contact?','rencontre');?></a>
				</div>
				<?php } ?>

				<div>
					<a href="javascript:void(0)" onClick="<?php echo $onClick['bloque']; ?>"><?php _e('Who I\'ve blocked?','rencontre');?></a>
				</div>
			</div>
			
		
<?php
// </div><!-- .w3-container --> closed in rencontre_sidebar_quick_search
// </div><!-- .w3-card .w3-white --> closed in rencontre_sidebar_quick_search
?>

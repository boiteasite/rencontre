<?php
/*
 * Plugin : Rencontre
 * Template : Menu
 * Last Change : Rencontre 3.13
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_menu.php
 * Call : rencontre_widget.php => widget()
 * $u0 (myself) : all
*/
?>

	<div id="rencMenu" class="rencMenu w3-bar w3-renc-mebg w3-left-align w3-large w3-margin-bottom">

		<a id="rencMenuHome" class="w3-bar-item w3-button w3-padding-large w3-renc-mebt w3-renc-mebo" href="<?php echo $link['home']; ?>" title="<?php _e('My homepage','rencontre'); ?>"><i class="fas fa-home"></i></a>
		<a id="rencMenuCard" class="rencMenuCard w3-bar-item w3-button w3-padding-large w3-renc-mebo" href="javascript:void(0)" title="<?php _e('My card','rencontre'); ?>"><i class="far fa-address-card"></i></a>
		<?php if(empty($rencOpt['accprof'])) { ?>

				<?php if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) { ?>
			
		<a id="rencMenuEdit" class="rencMenuEdit w3-bar-item w3-button w3-padding-large w3-renc-mebo" href="javascript:void(0)" title="<?php _e('Edit My Profile','rencontre'); ?>"><i class="far fa-edit"></i></a>
				<?php } else { ?>
			
		<a id="rencMenuEdit" class="rencMenuEdit w3-bar-item w3-button w3-padding-large <?php if(empty($rencDiv['titann']) || !empty($u0->photoreq) || (!empty($rencOpt['mandatory']) && rencistatus($rencDiv['istatus'],3))) echo 'w3-renc-mebw '; ?>w3-renc-mebo" href="javascript:void(0)" title="<?php _e('Edit My Profile','rencontre'); ?>"><i class="far fa-edit"></i></a>
				<?php } ?>
			<?php } ?>
			
			<?php if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) { ?>
			
		<a id="rencMenuMsg" class="rencMenuMsg w3-bar-item w3-button w3-padding-large w3-renc-mebo" href="javascript:void(0)" title="<?php _e('Messaging','rencontre'); ?>"><i class="far fa-envelope"></i></a>
			<?php } else { ?>
			
		<a id="rencMenuMsg" class="rencMenuMsg w3-bar-item w3-button w3-padding-large w3-renc-mebo w3-display-container" href="javascript:void(0)" title="<?php _e('Messaging','rencontre'); ?>">
			<i class="far fa-envelope"></i>
			<span class="w3-badge w3-small w3-renc-mebc w3-display-topright" style="margin:4px"><?php echo RencontreWidget::f_count_inbox($current_user->user_login); ?></span>
		</a>
			<?php } ?>
			<?php if(empty($blockSearch)) { ?>
			
		<a id="rencMenuSearch" class="rencMenuSearch w3-bar-item w3-button w3-padding-large w3-renc-mebo" href="javascript:void(0)" title="<?php _e('Search','rencontre'); ?>"><i class="fas fa-search"></i></a>
			<?php } else { ?>

		<a id="rencMenuSearch" class="rencMenuSearch w3-bar-item w3-button w3-opacity w3-padding-large w3-renc-mebo" onClick="f_modalWarn('<?php echo addslashes($blockSearch); ?>')" href="javascript:void(0)" title="<?php _e('Search','rencontre'); ?>"><i class="fas fa-search"></i></a>
			<?php } ?>
		
		<a id="rencMenuAccount" class="rencMenuAccount w3-bar-item w3-button w3-padding-large <?php if(isset($rencOpt['fastreg']) && $rencOpt['fastreg']>1) echo 'w3-renc-mebw '; ?>w3-renc-mebo" href="javascript:void(0)" title="<?php _e('My Account','rencontre'); ?>"><i class="fas fa-sliders-h"></i></a>

			<?php if(file_exists(get_stylesheet_directory().'/templates/rencontre_custom_page1.php')) { ?>
			
		<a id="rencMenuC1" class="rencMenuC1 w3-bar-item w3-button w3-padding-large w3-renc-mebo" href="javascript:void(0)"><i class="fab fa-angellist"></i></a>
			<?php } ?>
			<?php if(file_exists(get_stylesheet_directory().'/templates/rencontre_custom_page2.php')) { ?>
			
		<a id="rencMenuC2" class="rencMenuC2 w3-bar-item w3-button w3-padding-large w3-renc-mebo" href="javascript:void(0)"><i class="far fa-hand-peace"></i></a>
			<?php } ?>
			<?php if(false) { ?>
				
		<span class="rencMenuFB w3-bar-item w3-button w3-padding-large w3-right">
			<!-- Add you FACEBOOK LIKE BUTTON CODE here (and replace false with true)-->
		</span>
			<?php } ?>
			
	</div><!-- .rencMenu -->
	<?php if(!empty($rencOpt['discrol'])) { ?>
		
	<script><?php if($rencOpt['discrol']==2) { ?>var risMob=/iPhone|iPad|iPod|Android/i.test(navigator.userAgent); if(risMob) <?php } ?>
["DOMContentLoaded","pageshow"].forEach(function(e){document.addEventListener(e,function(){window.scrollTo(0,document.getElementById("rencMenu").getBoundingClientRect().top-8);},false);});</script>
	<?php } ?>

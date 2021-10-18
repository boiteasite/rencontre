<?php
/*
 * Plugin : Rencontre
 * Template : ImgReg shortcode
 * Last Change : Rencontre 3.6.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_imgreg.php
 * $arg : 'title', 'selector', 'left', 'top', 'login'
*/
?>

	<div id="imgreg" class="imgreg w3-hide-small" style="position:absolute;max-width:280px;"><h2><?php echo $arg['title']; ?></h2>
	<?php if(empty($arg['login'])) { ?>

		<form name="registerform" id="registerform" action="<?php echo esc_url(wp_registration_url()); ?>" method="post" novalidate="novalidate">
			<p>
				<label for="user_login"><?php _e('Username'); ?><br /><input type="text" name="user_login" id="user_login" class="input" value="" size="20" />
				</label>
			</p>
			<p>
				<label for="user_email"><?php _e('Email'); ?><br /><input type="email" name="user_email" id="user_email" class="input" value="" size="25" />
				</label>
			</p>
		<?php if(!empty($rencOpt['fastreg'])) { ?>

			<p>
				<label for="pssw"><?php _e('Password'); ?><br /><input type="password" name="pssw" id="pssw" class="input" value="" size="25" />
				</label>
			</p>
			<?php if(!empty($rencOpt['disnam'])) { ?>
			
			<p>
				<label for="dname"><?php _e('My name','rencontre'); ?><br /><input type="text" name="dname" id="dname" class="input" value="" size="30" />
				</label>
			</p>
			<?php } ?>
			
			<p>
				<label for="<?php echo (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex'); ?>"><?php _e('I\'m looking for','rencontre'); ?>
				<br />
				<select name="<?php echo (!empty($rencOpt['lbl']['zsex'])?$rencOpt['lbl']['zsex']:'zsex'); ?>">
				<?php for($v=(isset($rencCustom['sex'])?2:0);$v<(isset($rencCustom['sex'])?count($rencOpt['iam']):2);++$v) { ?>
					<option value="<?php echo $v; ?>"><?php echo $rencOpt['iam'][$v]; ?></option>
				<?php } ?>
				
				</select></label>
			</p>
		<?php } else { ?>
		
			<p id="reg_passmail"><?php _e('Registration confirmation will be emailed to you.'); ?></p>
		<?php } ?>
		
			<input type="hidden" name="redirect_to" value="" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php echo esc_attr__('Register'); ?>" />
			</p>
		</form>
	<?php } else { ?>
	
		<form name="loginform" id="loginform" action="<?php echo esc_url(wp_login_url(get_permalink())); ?>" method="post">
			<p>
				<label for="user_login"><?php _e( 'Username or Email Address' ); ?><br /><input type="text" name="log" id="user_login" class="input" value="" size="20" />
				</label>
			</p>
			<p>
				<label for="user_pass"><?php _e('Password'); ?><br /><input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
				</label>
			</p>
			<input type="hidden" name="redirect_to" value="" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php echo esc_attr__('Log In'); ?>" />
			</p>
		</form>
	<?php } ?>
	
	</div>
	<script>jQuery(document).ready(function(){var p=jQuery("<?php echo $arg['selector']; ?>").parent(),h=parseInt((jQuery("<?php echo $arg['selector']; ?>").height())*0.<?php echo $arg['top']; ?>),w=parseInt((jQuery("<?php echo $arg['selector']; ?>").width())*0.<?php echo $arg['left']; ?>);jQuery("#imgreg").appendTo(p);document.getElementById("imgreg").style.top=h+"px";document.getElementById("imgreg").style.left=w+"px";jQuery(p).css("position","relative");});</script>

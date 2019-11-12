<?php
/*
 * Plugin : Rencontre
 * Template : Mail Regular
 * Last Change : Rencontre 3.0
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_regular.php
 * $u : ID, user_login, d_naissance, c_pays, c_ville, t_titre, name, age, title, link
*/
?>

	<div class='rencMailRegular' style='font-size:15px;width:260px;'>
		<div style='background-color:#fcfcfc'>
			<img src='<?php echo $u->photoUrl; ?>' style='width:100%' alt='<?php echo $u->name; ?>' />
			<div style='padding:16px;text-align:center;overflow:hidden'>
				<div style='font-size:18px;white-space:nowrap;'><?php echo $u->name; ?></div>
				<?php if(empty($rencCustom['place'])) { ?>
				
				<div style='white-space:nowrap'><?php echo $u->c_ville; ?></div>
				<?php } ?>
				<div style='font-size:12px;line-height:1.4em;height:4.2em;overflow:hidden;'><?php echo $u->title; ?></div>
			</div>
			<div style='padding-top:4px'>
				<a style='<?php echo $buttonCSS; ?>' onMouseOver='<?php echo $buttonHover; ?>' onMouseOut='<?php echo $buttonOut; ?>' href='<?php echo $u->link->contact; ?>' target='_blank'>
					&nbsp;<?php _e('Ask for a contact','rencontre'); ?>
				</a>
			</div>
			<div style='padding-top:4px'>
				<a style='<?php echo $buttonCSS; ?>' onMouseOver='<?php echo $buttonHover; ?>' onMouseOut='<?php echo $buttonOut; ?>' href='<?php echo $u->link->smile; ?>' target='_blank'>
					&nbsp;<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw1'])) echo stripslashes($rencCustom['smiw1']);
						else _e('Smile','rencontre'); ?>
				</a>
			</div>
			<div style='padding-top:4px'>
				<a style='<?php echo $buttonCSS; ?>' href='<?php echo $u->link->message; ?>' target='_blank'>
					&nbsp;<?php _e('Send a message','rencontre'); ?>
				</a>
			</div>
			<div style='padding-top:4px'>
				<a style='<?php echo $buttonCSS; ?>' onMouseOver='<?php echo $buttonHover; ?>' onMouseOut='<?php echo $buttonOut; ?>' href='<?php echo $u->link->profile; ?>' target='_blank'>
					&nbsp;<?php _e('Profile','rencontre'); ?>
				</a>
			</div>
		</div>
	</div>

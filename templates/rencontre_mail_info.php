<?php
/*
 * Plugin : Rencontre
 * Template : Mail Info
 * Last Change : Rencontre 3.0.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_info.php
 * $u : ID, user_login, user_email
*/
?>

<?php $infotit = __('A member contact you','rencontre'); ?>

	<div style='font-family:"Helvetica Neue",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;color:#000;'>
		<p><?php _e('Hello','rencontre'); ?>&nbsp;<?php echo $u->user_login; ?>,</p>
		
	<?php if(!empty($contact)) { ?>
		<p><?php _e('You have received a contact request from','rencontre'); ?></p>
		<table>
			<tr>
			<?php echo $contact; ?>
			</tr>
		</table>
	<?php } ?>
	
	<?php if(!empty($smile)) { ?>
		<?php if(!empty($rencCustom['smiw']) && !empty($rencCustom['smiw4'])) { ?>
		<p><?php echo stripslashes($rencCustom['smiw4']); ?></p>
		<?php } else { ?>
		<p><?php _e('You have received a smile from','rencontre'); ?></p>
		<?php } ?>
		<table>
			<tr>
			<?php echo $smile; ?>
			</tr>
		</table>
	<?php } ?>
	
	<?php if(!empty($inbox)) { ?>
		<p><?php _e('You have','rencontre'); ?>&nbsp;
			<a href='<?php echo $hrefinbox; ?>' target='_blank'><?php echo $inbox; ?>&nbsp;<?php echo (($inbox>1)?__('messages','rencontre'):__('message','rencontre')); ?></a>
			&nbsp;<?php _e('in your inbox.','rencontre'); ?>
		</p>
	<?php } ?>
	
		<br />
		<p><?php _e('Regards,','rencontre'); ?></p>
		<br />
		
	<?php if(!empty($buttons)) echo $buttons; ?>
	</div>

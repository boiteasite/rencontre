<?php
/*
 * Plugin : Rencontre
 * Template : Mail Instant
 * Last Change : Rencontre 3.8.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_instant.php
 * Call : rencontre_filter.php => f_cron_liste()
 * $u : ID, user_login, user_email
*/
?>
<?php $mailSubj = $blogName.' - '.__('A member contact you','rencontre'); ?>
<div style='font-family:"Helvetica Neue",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;color:#000;'>
	<p><?php _e('Hello','rencontre'); ?>&nbsp;<?php echo $u->user_login; ?>,</p>
<?php if(!empty($contact)) { ?>
	<p><?php _e('You have received a contact request from','rencontre'); ?></p>
	<?php if(!empty($contread)) { ?>
	
	<i><?php echo $contread; ?></i>
	<?php } else { ?>
	
	<table>
		<tr>
			<td><?php echo $contact; ?></td>
		</tr>
	</table>
	<?php } ?>
<?php } ?>
<?php if(!empty($smile)) { ?>
	<?php if($t=rencTranslate('smiw4')) echo '<p>'.$t.'</p>';
	else { ?>
	<p><?php _e('You have received a smile from','rencontre'); ?></p>
	<?php } ?>
	<?php if(!empty($smilread)) { ?>
	
	<i><?php echo $smilread; ?></i>
	<?php } else { ?>

	<table>
		<tr>
			<td><?php echo $smile; ?></td>
		</tr>
	</table>
	<?php } ?>
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
	<div style='margin:8px 0 0;'>
		<a style='<?php echo $buttonCSS; ?>' onMouseOver='<?php echo $buttonHover; ?>' onMouseOut='<?php echo $buttonOut; ?>' href='<?php echo $buttonLink; ?>' target='_blank'> <?php _e('Login','rencontre'); ?></a>
	</div>
</div>

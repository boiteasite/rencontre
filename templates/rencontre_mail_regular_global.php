<?php
/*
* Plugin : Rencontre
* Template : Mail Regular global
* Last Change : Rencontre 3.12.3
* Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_regular_global.php
* Call : rencontre_cron.php => f_cron_on()
* $u : ID, user_login, d_naissance, c_pays, c_ville, t_titre, name, age, title, link
*/
?>
<?php $mailSubj = $blogName; ?>
<div style='font-family:"Helvetica Neue",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;'>
	<p><?php echo __('Hello','rencontre').' '.$u->user_login; ?>,</p>
<?php if($t=rencTranslate('textmail')) echo '<p>'.nl2br($t).'</p>'; ?>
<?php if(isset($action['visite'])) { ?>
	<p style='font-weight:700;font-size:.9em;'><?php _e('Your profile has been visited','rencontre'); ?>
		<span style='color:red;'> <?php echo count($action['visite']); ?> <?php _e('time','rencontre'); ?></span>
	</p>
<?php } ?>	
<?php if(!empty($selectionQuery)) { $c = 0; ?>
	<p style='font-weight:700;font-size:.9em;'><?php _e('Here\'s a selection of members that may interest you','rencontre'); _e(': ','rencontre'); ?></p>
	<table><tr>
	<?php foreach($selectionQuery as $u1) { ++$c; ?>
		<td><?php echo rencMailBox($u1,$rencDrap,$oo,$ii); ?></td>
		<?php if($c>$mailPerline) { $c = 0; ?>
		</tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr>
		<?php } ?>
	<?php } ?>
	</tr></table>
<?php } ?>
<?php if(isset($action['sourireIn']) && count($action['sourireIn']) && !empty($smileQuery)) { $c = 0; ?>
	<p style='font-weight:700;font-size:.9em;'>
	<?php if($t=rencTranslate('smiw4')) echo $t.__(': ','rencontre');
	else { ?>
		<?php _e('You have received a smile from','rencontre'); _e(': ','rencontre'); ?>
	<?php } ?>
	<?php if(!empty($smileread)) { ?>
		<i><?php echo $smileread; ?></i>
	<?php } else { ?>
	</p>
	<table><tr>
		<?php foreach($smileQuery as $u1) { ++$c; ?>
		<td><?php echo rencMailBox($u1,$rencDrap,$oo,$ii); ?></td>
			<?php if($c>$mailPerline) { $c = 0; ?>
		</tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	</tr></table>
<?php } ?>
<?php if(isset($action['contactIn']) && count($action['contactIn']) && !empty($contactQuery)) { $c = 0; ?>
	<p style='font-weight:700;font-size:.9em;'><?php _e('You have received a contact request from','rencontre'); _e(': ','rencontre'); ?>
	<?php if(!empty($contread)) { ?>
		<i><?php echo $contread; ?></i></p>
	<?php } else { ?>
	</p>
	<table><tr>
		<?php foreach($contactQuery as $u1) { ++$c; ?>
		<td><?php echo rencMailBox($u1,$rencDrap,$oo,$ii); ?></td>
			<?php if($c>$mailPerline) { $c = 0; ?>
		</tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	</tr></table>
<?php } ?>
<?php if(!empty($nbMessage)) { ?>
	<p style='font-weight:700;font-size:.9em;'><?php _e('You have','rencontre'); ?>
		<span style='color:red;'> <?php echo $nbMessage; ?> <?php echo (($nbMessage>1)?__('messages','rencontre'):__('message','rencontre')); ?>"</span><?php echo ' '.__('in your inbox.','rencontre'); ?>
	</p>
<?php } ?>
	<p><?php _e('Do not hesitate to send us your comments.','rencontre'); ?></p>
	<br />
	<?php _e('Regards,','rencontre'); ?>
	<br />
	<?php echo $blogName; ?>
	<div style='margin:8px 0 0;'>
		<a style='<?php echo $buttonCSS; ?>' onMouseOver='<?php echo $buttonHover; ?>' onMouseOut='<?php echo $buttonOut; ?>' href='<?php echo $buttonLink; ?>' target='_blank'> <?php _e('Login','rencontre'); ?></a>
	</div>
</div>

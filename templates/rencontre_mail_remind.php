<?php
/*
 * Plugin : Rencontre
 * Template : Mail remind
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_remind.php
 * Call : rencontre_filter.php => f_cron_on()
 * $u : ID, user_login, user_email
*/
?>
<?php $mailSubj = $blogName.' - '.__('Registration','rencontre'); ?>
<div style='text-align:left;margin:5px 5px 5px 10px;font-size:13px;font-family:"Helvetica Neue",Helvetica;'>
	<p><?php echo __('Hello','rencontre').' '.$u->user_login; ?>,</p>
<?php if($t=rencTranslate('relancText')) echo '<p>'.nl2br($t).'</p>';
	else { ?>
	<p><?php _e('You registered on our website but you did not complete the procedure. You\'ll miss a date. That\'s too bad.','rencontre'); ?></p>
	<p><?php _e('Thus take two minutes to finish the registration. You should not be disappointed.','rencontre'); ?></p>
	<p><?php _e('Regards,','rencontre'); ?></p>
<?php } ?>
	<a href="<?php echo (!empty($remindUrl)?$remindUrl:site_url()); ?>"><?php _e('Login','rencontre'); ?></a>
</div>

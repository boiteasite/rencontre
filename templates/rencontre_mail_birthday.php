<?php
/*
 * Plugin : Rencontre
 * Template : Mail birthday
 * Last Change : Rencontre 3.8.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_birthday.php
 * Call : rencontre_filter.php => f_cron_on()
 * $u : ID, user_login, user_email
*/
?>
<?php $mailSubj = $blogName.' - '.__('Happy Birthday','rencontre'); ?>
<div style='font-family:"Helvetica Neue",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;'>
	<p><?php _e('Hello','rencontre'); ?>&nbsp;<?php echo $u->user_login; ?>,</p>
<?php if($t=rencTranslate('textanniv')) echo '<p>'.nl2br($t).'</p>';
else { ?>
	<p><?php _e('Happy Birthday','rencontre'); ?>&nbsp;<?php echo $u->user_login. __('!','rencontre'); ?></p>
<?php } ?>
</div>

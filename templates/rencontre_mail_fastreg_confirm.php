<?php
/*
 * Plugin : Rencontre
 * Template : Mail Fastreg confirm
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_mail_fastreg_confirm.php
 * Call : rencontre_filter.php => rencFastreg_email()
 * $u : ID, user_login, user_email
*/
?>
<?php $mailSubj = $blogName.' - '.__('Confirmation email','rencontre'); ?>
<div style='font-family:"Helvetica Neue",Helvetica;font-size:13px;text-align:left;margin:5px 5px 5px 10px;color:#000;'>
	<p><?php echo __('Hello','rencontre').' '.$u->user_login; ?>',</p>
	<p><?php _e('You should confirm your email with this link','rencontre'); ?></p>
	<p><a href="<?php echo $confirmUrl; ?>" target="_blank"><?php echo $confirmUrl; ?></a></p>
	<p><?php _e('Regards,','rencontre'); ?></p>
	<br />
	<p><?php echo $blogName; ?></p>
</div>

<?php
/*
 * Plugin : Rencontre
 * Template : Warning
 * Last Change : Rencontre 3.0
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_warning.php
 * Call : rencontre_widget.php => widget(), plusImg(), f_quickFind(), f_trouver()
 * input : $warning
*/
?>

<?php if(!empty($warning)) { ?>

	<div id="rencWarning" class="w3-panel w3-renc-wabg"><?php echo $warning; ?></div>
	<?php if(!empty($timeout)) { ?>
	
	<script type="text/javascript">window.setTimeout(function(){jQuery("#rencWarning").remove()},<?php echo $timeout; ?>);</script>
	<?php } ?>
<?php } ?>

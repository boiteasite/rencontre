<div id="rencDonation" style="float:right;width:250px;z-index:-1;margin:20px;">
<table cellpadding="0" class="widefat" style="margin-bottom:10px; border:solid 2px #0074a2;">
	<thead>
		<th style="text-align:center;color:#0074a2;"><strong><?php _e('About This Plugin', 'rencontre') ?></strong></th>
	</thead>
	<tbody>
		<tr>
			<td><?php _e('Enjoyed this free dating plugin?  A donation or a good review will help us to spend more time to make it evolve. Thanks for your contribution.','rencontre') ?></td>
		</tr>
		<tr>
			<td style="text-align:center;position:relative;">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_donations" />
					<input type="hidden" name="business" value="J5R558LZ7FQ7L" />
					<input type="hidden" name="notify_url" value="http://www.boiteasite.fr/fiches/uno/plugins/paypal/ipn.php" />
					<input type="hidden" name="lc" value="<?php _e('US', 'rencontre') ?>" />
					<input type="hidden" name="item_name" value="<?php _e('Rencontre Plugin Donation', 'rencontre') ?>" />
					<input type="hidden" name="no_note" value="1" />
					<input type="hidden" name="src" value="0" />
					<input type="hidden" name="currency_code" value="EUR" />
					<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest" />
					<input type="image" style="max-width:180px;" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="<?php _e('PayPal - The most secure online payment solution !','rencontre') ?>" />
				</form>
			</td>
		</tr>
	<?php if(!function_exists('wpGeonames')) { ?>
	
		<tr>
			<td><a href="https://wordpress.org/plugins/wp-geonames/" target="_blank">WP-Geonames</a>&nbsp;<?php _e('is a recommended plugin to use with Rencontre.', 'rencontre') ?></td>
		</tr>
	<?php } ?>
	<?php if(!function_exists('rencontreP_init')) { ?>
	
		<tr>
			<td><?php _e('You want an integrated payment system, an easy moderation panel and a lot of features?', 'rencontre') ?><br />=>&nbsp;<a href="http://www.boiteasite.fr/fiches/rencontre-premium.html" target="_blank"><?php _e('Add this kit PREMIUM','rencontre') ?></a></td>
		</tr>
	<?php } ?>
	
		<tfoot>
			<tr>
				<td style="font-size:.8em;text-align:center;">
					<a href="https://wordpress.org/plugins/rencontre/faq" target="_blank">FAQ</a>&nbsp;|
					<a href="https://wordpress.org/support/plugin/rencontre" target="_blank">Support Forums</a>&nbsp;|
					<a href="https://wordpress.org/support/view/plugin-reviews/rencontre" target="_blank">Reviews</a>&nbsp;|
					<a href="https://translate.wordpress.org/projects/wp-plugins/rencontre" target="_blank">Translations</a>
				</td>
			</tr>
		</tfoot>
	</tbody>
</table>
</div>

<?php
function rencontre_install_help() {
	global $rencDiv;
	$d = $rencDiv['basedir'].'/tmp/';
	if(!is_dir($d)) mkdir($d);
	?>
	
	<div style="margin:10px 0;padding:5px 10px;border:solid 2px #ffba00;background-color:#fff;max-width:620px;">
	<div style="color:#0074a2;border-bottom:1px solid #e1e1e1;line-height:2em;font-size:1.2em;font-weight:700;margin:0;padding:0;text-align:center;"><?php _e('How to install this plugin ?', 'rencontre') ?></div>
	<ol>
		<li><?php _e('In <a href="edit.php?post_type=page">Pages</a>, edit or create the page of your choice. Add the shortcodes <code>[rencontre][rencontre_libre]</code> in this page content.', 'rencontre') ?></li>
		<li><?php _e('In <a href="admin.php?page=rencontre.php">Rencontre / General</a>, set the plugin page and save.', 'rencontre') ?></li>
		<li><?php _e('You need the WordPress Login / Logout / Register links. Take a look in <a href="nav-menus.php">Appearance / Menus</a> if your theme has not these links.', 'rencontre') ?></li>
		<li><?php _e('Add a new subscriber member in WP. Login this member and go to the plugin page (1).', 'rencontre') ?>
			<br /><?php _e('To facilitate the settings after installing, you can download and install via CSV (<a href="admin.php?page=rencontre.php">Rencontre / General</a>) 20 test profiles with photo', 'rencontre') ?> : <a href="https://www.boiteasite.fr/telechargement/rencontre_import_20_profiles.zip">zip</a>
		</li>
	</ol>
	<p><?php _e('More details in the <a href="https://wordpress.org/plugins/rencontre/#faq" target="_blank">F.A.Q</a>.', 'rencontre') ?></p>
	</div>
	<?php
}
?>

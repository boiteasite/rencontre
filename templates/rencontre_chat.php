<?php
/*
 * Plugin : Rencontre
 * Template : Chat
 * Last Change : Rencontre 3.14
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_chat.php
 * Call : rencontre_widget.php => widget()
*/
?>

	<div id="rencChat" class="w3-card w3-white w3-hide" style="position:fixed;bottom:0;right:10px;z-index:999;width:300px;margin-bottom:10px;">
		<header class="w3-container w3-black w3-padding"> 
			<div class="w3-display-topright w3-padding-small">
				<span id="rcBcam" class="w3-button w3-padding-small w3-hover-black w3-hover-opacity w3-hide" title="webcam">
					<img src="<?php echo plugins_url('rencontre/images/cam20.png'); ?>" />
				</span>
				<span id="rcClose" class="w3-button w3-padding-small w3-hover-black w3-hover-opacity">&times;</span>
			</div>
			<div>
				<span id="rcOnOff" class="rcOnOff w3-hover-opacity">&nbsp;</span>
				Chat&nbsp;<span id="rcName" class="w3-opacity"></span>
			</div>
		</header>
		<div id="rcContent" style="height:280px;overflow-y:auto;"></div>
	<?php if(empty($rencCustom['emot'])) { ?>
		
		<div id="rcEmot" class="w3-center w3-clear w3-hide">
		<?php for($i=1;$i<16;++$i) { ?><img src="<?php echo plugins_url('rencontre/images/'.$i.'.gif'); ?>" alt="<?php echo $i; ?>" style="display:inline" /><?php } ?>
		</div>
	<?php } ?>
	
		<input id="rcInput" class="w3-input w3-hide" disabled="" />
	</div><!-- .w3-card -->
	
<?php // Conversation blocks inserted and updated using JavaScript ?>
	<div id="rcMe" style="display:none">
		<div class="w3-row w3-padding-small">
			<div class="w3-col s2 m3">&nbsp;</div>
			<div class="w3-renc-msbr rcMe w3-col s10 m9 w3-card w3-round w3-padding"><span></span></div>
		</div>
	</div>
	<div id="rcYou" style="display:none">
		<div class="w3-row w3-padding-small">
			<div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-round w3-padding"><b></b><br /><span></span></div>
			<div class="w3-col s2 m3">&nbsp;</div>
		</div>
	</div>

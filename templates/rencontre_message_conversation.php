<?php
/*
 * Plugin : Rencontre
 * Template : Message Conversation
 * Last Change : Rencontre 3.1.1
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_message_conversation.php
 * $u : ID, user_login, display_name, login, i_photo, photo
 * $u0 : user_login, login
*/
?>

	<div class="rencMsgConversation w3-container">
		<div class="w3-section">
		<?php if($hoAns!==99) { // display buttons and photo ?>

			<div class="w3-cell-row">
				<div class="w3-container w3-cell">
					<a class="msgProfil" href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
					<?php if(!empty($u->i_photo)) { ?>

						<img class="w3-circle w3-image" src="<?php echo $u->photo; ?>" alt="<?php echo $u->display_name; ?>" />
					<?php } else { ?>

						<img class="w3-circle w3-image" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" title="<?php echo $u->display_name; ?>" />
					<?php } ?>

					</a>
					<div style="text-transform:capitalize;">
						<a class="msgProfil" href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>"><?php echo $u->display_name; ?></a>
					</div>
				</div>
				<div class="w3-container w3-cell">
					<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['inbox']; ?>"><i class="far fa-envelope"></i><span class="w3-hide-small">&nbsp;<?php _e('Inbox','rencontre');?></span></button>
				<?php if(!$hoAns){ ?>
					
					<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['write']; ?>"><i class="fas fa-reply"></i><span class="w3-hide-small">&nbsp;<?php _e('Answer','rencontre');?></span></button>
				<?php } else { ?>
				
					<a href="javascript:void(0)"><i class="fas fa-reply"></i><span class="w3-hide-small">&nbsp;<?php _e('Answer','rencontre');?></span></a>
				<?php } ?>
				
				</div>
				<div class="w3-container w3-cell w3-text-right">
					<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="if(confirm('<?php _e('Delete the conversation','rencontre'); ?>')){<?php echo $onClick['del']; ?>}"><i class="far fa-trash-alt"></i></button>
				</div>
			</div><!-- .w3-cell-row -->
		<?php } ?>
		<?php foreach($conversation as $m) { ?>

			<div class="w3-row w3-section">
			<?php if($m->sender==$u0->user_login) { ?>
			
				<div class="w3-col s2 m3 w3-padding">
					<div class="w3-display-container">
						<div class="w3-display-middle <?php echo (!empty($m->read)?'w3-text-blue':'w3-opacity'); ?>">
						<i class="fa fa-check fa-stack-1x" style="margin-left:4px"></i><i class="fa fa-check fa-stack-1x" style="margin-left:-4px"></i>
						</div>
					</div>
				</div>
				<div class="w3-col s10 m9 w3-card w3-padding w3-renc-msbs">
					<div class="msgDate w3-right-align w3-small w3-opacity"><?php echo $m->date; ?></div>
					<div class="msgContent"><?php echo stripslashes(nl2br($m->content)); ?></div>
				</div>
			<?php } else { ?>
				<div class="w3-col s10 m9 w3-card w3-padding w3-renc-msbr">
					<div class="msgDate w3-right-align w3-small w3-opacity"><?php echo $m->date; ?></div>
					<div class="msgContent"><?php echo stripslashes(nl2br($m->content)); ?></div>
				</div>
				<div class="w3-col s2 m3">&nbsp;</div>
			
			<?php } ?>
		
			</div>
		<?php } ?>
		</div>
	</div><!-- .w3-container -->
	<?php if(!isset($rencCustom['emot']) || !$rencCustom['emot']) { ?>
	
	<script type="text/javascript">jQuery(document).ready(function(){f_msgEmotContent(document.getElementsByClassName("msgContent"))})</script>
	<?php } ?>

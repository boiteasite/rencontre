<?php
/*
 * Plugin : Rencontre
 * Template : Message Conversation
 * Last Change : Rencontre 3.12.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_message_conversation.php
 * Call : rencontre_widget.php => f_conversation()
 * $u : ID, user_login, display_name, login, i_photo, miniPhoto, miniPhotoWebp
 * $u0 : user_login, login
*/
?>

	<div class="rencMsgConversation w3-container">
		<div class="w3-section">
		<?php if(empty($msgWrite)) { // display buttons and photo ?>

			<div class="w3-cell-row">
				<div class="w3-container w3-cell">
					<?php if(!empty($onClick['profile'])) { ?>
					
					<a class="msgProfil" href="javascript:void(0)" onClick="<?php echo $onClick['profile']; ?>">
					<?php } else { ?>
					
					<span class="msgProfil" title="<?php echo $title['profile']; ?>">
					<?php } ?>
					<?php if(!empty($u->i_photo)) { ?>
						<?php if(!empty($u->miniPhotoWebp)) { ?>

						<picture>
							<source class="w3-circle w3-image" srcset="<?php echo $u->miniPhotoWebp; ?>" type="image/webp" alt="<?php echo $u->display_name; ?>">
							<source class="w3-circle w3-image" srcset="<?php echo $u->miniPhoto; ?>" type="image/jpeg" alt="<?php echo $u->display_name; ?>"> 
							<img class="w3-circle w3-image" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" alt="<?php echo $u->display_name; ?>" />
						</picture>
						<?php } else { ?>
			
						<img class="w3-circle w3-image" src="<?php echo $u->miniPhoto; ?>" srcset="<?php echo $u->miniPhotoRetina; ?>" alt="<?php echo $u->display_name; ?>" />
						<?php } ?>
					<?php } else { ?>

						<img class="w3-circle w3-image" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $u->display_name; ?>" title="<?php echo $u->display_name; ?>" />
					<?php } ?>
					
					<?php if(!empty($onClick['profile'])) { ?></a><?php } else { ?></span><?php } ?>
					
					<div style="text-transform:capitalize;">
					<?php if(!empty($onClick['profile'])) { ?>
					
						<a class="msgProfil" href="javascript:void(0)" style="text-transform:capitalize;" onClick="<?php echo $onClick['profile']; ?>"><?php echo $u->display_name; ?></a>
					<?php } else { ?>

						<span class="msgProfil" style="text-transform:capitalize;"><?php echo $u->display_name; ?></span>
					<?php } ?>
					
					</div>
				</div>
				<div class="w3-container w3-cell">
					<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['inbox']; ?>"><i class="far fa-envelope"></i><span class="w3-hide-small"> <?php _e('Inbox','rencontre');?></span></button>
				<?php if(!empty($onClick['write'])){ ?>
					
					<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['write']; ?>"><i class="fas fa-reply"></i><span class="w3-hide-small"> <?php _e('Answer','rencontre');?></span></button>
				<?php } else { ?>
				
					<button class="w3-btn w3-renc-mebt w3-disabled" onClick="f_modalWarn('<?php echo addslashes($title['write']); ?>')" title="<?php echo $title['write']; ?>"><i class="fas fa-reply"></i><span class="w3-hide-small"> <?php _e('Answer','rencontre');?></span></button>
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

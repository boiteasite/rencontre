<?php
/*
 * Plugin : Rencontre
 * Template : Message Inbox
 * Last Change : Rencontre 3.8.2
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_message_inbox.php
 * Call : rencontre_widget.php => widget()
 * $u0 : user_login
*/
?>

	<div class="rencMsgInbox">
		<div class="w3-margin-bottom">
			<form name="formEcrire" method='post' action=''>
			<?php if(isset($rencOpt['page_id'])) { ?>
			
				<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
			<?php } ?>
			
				<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
				<input type="hidden" name="P<?php echo (!empty($rencOpt['lbl']['id'])?$rencOpt['lbl']['id']:'id'); ?>" value="" />
				<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['msg'])?$rencOpt['lbl']['msg']:'msg'); ?>" value="" />
			</form>
			<div id="rencMsg" class="w3-card w3-renc-blbg">
				<div class="w3-container">
					<div class="w3-section">
						<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Inbox','rencontre');?></div>
					</div>
				</div>
				<div class="w3-margin-bottom">
					<?php foreach($inbox as $m) { ?>
					
					<div class="w3-bar" <?php if(empty($m->read) && $m->sender!=$u0->user_login) echo 'style="font-weight:700"'; ?>>
						<div class="w3-bar-item" style="width:60px">
						<?php if(!empty($m->i_photo)) { ?>
							<?php if(!empty($m->miniPhotoWebp)) { ?>

							<picture>
								<source class="w3-circle w3-image" srcset="<?php echo $m->miniPhotoWebp; ?>" type="image/webp" alt="<?php echo $m->member; ?>">
								<source class="w3-circle w3-image" srcset="<?php echo $m->miniPhoto; ?>" type="image/jpeg" alt="<?php echo $m->member; ?>"> 
								<img class="w3-circle w3-image" src="<?php echo $m->miniPhoto; ?>" srcset="<?php echo $m->miniPhotoRetina; ?>" alt="<?php echo $m->member; ?>" />
							</picture>
							<?php } else { ?>
			
							<img class="w3-circle w3-image" src="<?php echo $m->miniPhoto; ?>" srcset="<?php echo $m->miniPhotoRetina; ?>" alt="<?php echo $m->member; ?>" />
							<?php } ?>
						<?php } else { ?>

							<img class="w3-circle w3-image" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php echo $m->member; ?>" />
						<?php } ?>
						
						</div>
						<div class="w3-bar-item">
							<a href="javascript:void(0)" style="text-transform:capitalize;" onClick="id=<?php echo $m->id.';'.$onClick['look']; ?>"><?php echo $m->member; ?></a>
							<?php if($m->sender==$u0->user_login) { ?>
							
							<span class="<?php echo (!empty($m->read)?'w3-text-blue':'w3-opacity'); ?>" style="margin-left:5px">
								<i class="fa fa-check"></i><i class="fa fa-check" style="margin-left:-.5em"></i>
							</span>
							<?php } ?>
							
						</div>
						<div class="w3-bar-item w3-right">
							<a href="javascript:void(0)" onClick="id=<?php echo $m->id.';'.$onClick['look']; ?>"><?php echo $m->date; ?></a>
							<a class="rencSupp w3-margin-left" href="javascript:void(0)" onClick="id=<?php echo $m->user_id.';'.$onClick['del']; ?>"><i class="far fa-trash-alt"></i></a>
						</div>
					</div><!-- .w3-bar -->
					<?php } ?>

				</div>
			</div><!-- .w3-card -->
		</div><!-- .w3-margin-bottom -->
	</div><!-- .rencMsgInbox -->

<?php
/*
 * Plugin : Rencontre
 * Template : Portrait Edit
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_portrait_edit.php
 * $u0 : U.ID, display_name, c_pays, c_ville, i_sex, i_photo, t_titre, t_annonce, t_profil
*/
?>

	<div class="rencPortraitEdit w3-section">
		<?php if($infochange) { ?>
		
		<div id="infoChange" class="w3-panel w3-renc-wabg">
			<div>
				<?php echo $infochange; ?>
			</div>
		</div><!-- .infoChange -->
		<?php } ?>
		
		<form name="portraitPhoto" method="post" enctype="multipart/form-data" action="">
			<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
			<input type="hidden" name="a1" value="" />
			<input type="hidden" name="a2" value="" />
			<input type="hidden" name="rnd" value="<?php echo $_SESSION['rnd']; ?>" />
		</form>
		<form name='portraitChange' method='post' action=''>
			<div class="w3-row w3-margin-bottom">
				<div class="w3-col w3-mobile" style="width:<?php echo $photoWidth;?>px">
					<div class="w3-xlarge w3-renc-titc w3-section"><?php _e('Edit My Profile','rencontre');?></div>
					<div class="w3-card w3-renc-blbg">
					<?php if($u0->i_photo) { ?>
						
						<img id="portraitGrande" src="<?php echo $u0->photoUrl.$u0->photo->grande[0]; ?>" alt="" />
					<?php } else { ?>

						<img id="portraitGrande" src="<?php echo plugins_url('rencontre/images/no-photo600.jpg'); ?>" alt="" />
					<?php } ?>
						
					</div>
					<div class="w3-card w3-renc-blbg w3-margin-top" style="width:<?php echo $photoWidth;?>px">
						<div class="rencBlocimg w3-center" style="font-size:0;">
						<?php for($v=0;$v<$rencOpt['imnb'];++$v) { ?>
							<?php if($u0->i_photo >= $u0->ID*10+$v) { ?>
							
							<a href="javascript:void(0)" onClick="<?php echo $onClick['delete'.$v]; ?>">
								<img class="portraitMini w3-show-inline-block" onMouseOver="<?php echo $u0->photo->over[$v]; ?>" src="<?php echo $u0->photoUrl.$u0->photo->mini[$v]; ?>" alt="<?php _e('Click to delete','rencontre'); ?>" title="<?php _e('Click to delete','rencontre'); ?>" />
							</a>
							<img style="display:none;" src="<?php echo $u0->photoUrl.$u0->photo->grande[$v]; ?>" />
							<?php } else {  ?>
								<?php if($v < $u0->maxPhoto) { ?>
								
							<a href="javascript:void(0)" onClick="<?php echo $onClick['add']; ?>">
								<img class="portraitMini w3-show-inline-block" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php _e('Click to add a photo','rencontre'); ?>" title="<?php _e('Click to add a photo','rencontre'); ?>" />
							</a>
								<?php } else { ?>
								
							<img class="portraitMini w3-show-inline-block" src="<?php echo plugins_url('rencontre/images/no-photo60.jpg'); ?>" alt="<?php _e('You are limited','rencontre'); ?>" title="<?php _e('You are limited','rencontre'); ?>" />
								<?php } ?>
							<?php } ?>
						<?php } ?>
							
						</div><!-- .rencBlocimg -->
						<div id="changePhoto" class="w3-center"></div>
						<div class="rencInfo w3-center w3-opacity"><?php _e('Click the photo','rencontre');?></div>
						<div class="w3-section w3-center">
							<button class="w3-button w3-margin-bottom w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['deleteAll']; ?>"><?php _e('Delete all photos','rencontre');?></button>
						</div>
					</div><!-- .w3-card -->
				</div><!-- .w3-col -->
				<div class="w3-col" style="width:16px;">&nbsp;</div><!-- .w3-col -->
				<?php if(isset($rencOpt['page_id'])) { ?>
				
				<input type="hidden" name="page_id" value="<?php echo $rencOpt['page_id']; ?>" />
				<?php } ?>
				
				<input type="hidden" name="<?php echo (!empty($rencOpt['lbl']['renc'])?$rencOpt['lbl']['renc']:'renc'); ?>" value="" />
				<input type="hidden" name="a1" value="" />
				<input type="hidden" name="a2" value="" />
				<div class="w3-rest w3-mobile w3-card w3-renc-blbg">
					<div class="w3-container w3-renc-lblc">
						<div class="w3-section w3-cell-row">
							<div class="w3-xlarge" style="text-transform:capitalize;"><?php echo $u0->display_name; ?></div>
						<?php if(!isset($rencCustom['place'])) { ?>
						
							<div class="ville"><?php echo $u0->c_ville; ?></div>
						<?php } ?>
						
							<label><?php _e('My attention-catcher','rencontre');?></label>
							<br />
							<input type="text" class="w3-input w3-renc-inbg" name="titre" value="<?php echo stripslashes($u0->t_titre); ?>" />
							<br /><br />
							<label><?php _e('My ad','rencontre');?></label>
							<br />
							<textarea name="annonce" class="w3-input w3-renc-inbg" rows="10"><?php echo stripslashes($u0->t_annonce); ?></textarea>
						</div>
						<div class="w3-section w3-right-align">
							<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['sauv']; ?>"><?php _e('Save profile','rencontre');?></button>
						</div>
					</div><!-- .w3-container -->
				</div><!-- .w3-rest -->
			</div><!-- .w3-row -->
		<?php if(!empty($u0->profil)) { ?>

			<div id="portraitBox" class="w3-card w3-renc-blbg w3-margin-bottom">
				<div class="w3-container">
					<div class="w3-section">
						<div class="w3-bar w3-renc-mebg">
						<?php $i = 0; foreach($u0->profil as $key=>$value) { ?>
							
							<div id="portraitTab<?php echo $i; ?>" class="w3-bar-item w3-button<?php if($i==0) echo ' w3-renc-mebt'; ?>" style="text-transform:capitalize;" onclick="javascript:f_onglet(<?php echo $i; ?>);"><?php echo $key; ?></div>
							<?php ++$i; ?>
						<?php } ?>
						</div><!-- .w3-bar -->

					<?php $i = 0; foreach($u0->profil as $key=>$value) { ?>

						<div id="portraitCont<?php echo $i; ?>" class="w3-container w3-border w3-renc-line w3-hide<?php if($i==0) echo ' w3-show'; ?>">
							<div class="w3-section">
							<?php foreach($value as $v) { ?>
							
								<div class="w3-row w3-padding-small">
									<div class="w3-quarter"><?php echo $v->label; ?></div>
									<div class="w3-threequarter">
									<?php if($v->type==1) { ?>
										
										<input type="text" class="w3-input w3-renc-inbg" name="text<?php echo $v->id; ?>" onkeydown="return event.key!='Enter'" value="<?php echo $v->active; ?>" />
									<?php } else if($v->type==2) { ?>
									
										<textarea name="area<?php echo $v->id; ?>" class="w3-input w3-renc-inbg" rows="4" cols="50"><?php echo $v->active; ?></textarea>
									<?php } else if($v->type==3) { ?>
									
										<select class="w3-select w3-border w3-renc-line w3-renc-sebg" name="select<?php echo $v->id; ?>">
											<option value="0">&nbsp;</option>
										<?php $j = 0; foreach($v->valeur as $valeur) { ?>
											
											<option value="<?php echo ($j+1); ?>"<?php if($v->active===$j) echo ' selected'; ?>><?php echo $valeur; ?></option>
											<?php ++$j; ?>
										<?php } ?>
										
										</select>
									<?php } else if($v->type==4) { ?>
										<?php $j = 0; foreach($v->valeur as $valeur) { ?>
					
										<input type="checkbox" class="rencLabelauty" name="check<?php echo $v->id; ?>[]" value="<?php echo $j; ?>"<?php if(strpos($v->active,','.$j.',')!==false) echo ' checked'; ?> data-labelauty="<?php echo $valeur; ?>" />
											<?php ++$j; ?>
										<?php } ?>
									<?php } else if($v->type==5) { ?>
										
										<select name="ns<?php echo $v->id; ?>" class="w3-select w3-border w3-renc-line w3-renc-sebg">
											<option value="0">&nbsp;</option>';
										<?php $j = 0; foreach($v->valeur as $valeur) { ?>
											
											<option value="<?php echo ($j+1); ?>"<?php if($v->active===$j) echo ' selected'; ?>><?php echo $valeur; ?></option>
											<?php ++$j; ?>
										<?php } ?>
										
										</select>
									<?php } else if($v->type==6) { ?>
										
										<input type="date" class="w3-input w3-renc-inbg" name="date<?php echo $v->id; ?>" onkeydown="return event.key!='Enter';" value="<?php echo $v->active; ?>" />
									<?php } ?>
								
									</div>
								</div>
							
							<?php } ?>
							</div>	
						</div><!-- .w3-container -->
						<?php ++$i; ?>
					<?php } ?>
					
						<div class="w3-section w3-right-align">
							<button class="w3-button w3-renc-mebt w3-renc-mebo" onClick="<?php echo $onClick['sauv']; ?>"><?php _e('Save profile','rencontre');?></button>
						</div>
					</div>
				</div><!-- .w3-container -->
			</div><!-- .w3-card -->
		<?php } ?>
		
		</form>
		<?php if(!empty($portraitPlus)) echo $portraitPlus; ?>

		<script type="text/javascript">jQuery(document).ready(function(){jQuery(":checkbox.rencLabelauty").labelauty({icon:false});});</script>
	</div><!-- .rencPortraitEdit -->

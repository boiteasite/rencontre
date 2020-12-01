<?php
/*
 * Plugin : Rencontre
 * Template : Lightbox
 * Last Change : Rencontre 3.4
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_lightbox.php
*/
?>

<div id="lightboxModal" class="w3-padding-16 w3-modal w3-black">
	<div class="w3-modal-content w3-black" style="height:92vh;">
	<?php for($v=0;$v<$u->maxPhoto;++$v) { if(($u->ID)*10+$v > $u->i_photo) break; ?>
	
		<img class="lightboxSlide w3-animate-opacity w3-display-middle" src="<?php echo $u->photoUrl.$u->photo->full[$v]; ?>" style="max-width:100%;max-height:90vh" />
	<?php } ?>
		<button class="w3-button w3-black w3-xlarge w3-display-topright" onclick="f_lightboxClose()">&#10005;</button>
		<button class="w3-button w3-black w3-display-left" onclick="f_lightboxPrevNext(-1)">&#10094;</button>
		<button class="w3-button w3-black w3-display-right" onclick="f_lightboxPrevNext(1)">&#10095;</button>
	</div> <!-- w3-modal-content -->
</div> <!-- lightboxModal -->

<script type="text/javascript">
var lightboxSlideIndex=1;
function f_lightbox(f){
	var a=document.getElementById('lightboxModal');
	a.className+=' w3-show';
	f_lightboxShowImg(f+1);
}
function f_lightboxPrevNext(f){f_lightboxShowImg(lightboxSlideIndex+=f);}
function f_lightboxClose(){
	var a=document.getElementById('lightboxModal');
	a.className=a.className.replace(' w3-show','');
}
function f_lightboxShowImg(f){
	lightboxSlideIndex=f;
	var v,x=document.getElementsByClassName("lightboxSlide");
	if(f>x.length)lightboxSlideIndex=1;
	if(f<1)lightboxSlideIndex=x.length;
	for(v=0;v<x.length;v++)x[v].style.display="none";
	x[lightboxSlideIndex-1].style.display="block";
}
</script>

<?php
/* This simple lightbox is called by the function "f_lightbox(img_number)". Img_number start at 0.
 * 
 * To change this lightbox by another one, you just have to replace this template content
 * and to keep a function named f_lightbox(n) to call your new lightbox name.
 * 
*/
?>

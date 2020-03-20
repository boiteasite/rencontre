<?php
/*
 * Plugin : Rencontre
 * Template : WpGeonames Location taxonomy
 * Last Change : Rencontre 3.3
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencontre_wp-geonames_location_taxonomy.php
 * $data : 0=>country, 1=>region, 2=>city, 3=>lat, 4=>lon
*/
?>
<?php $data = explode('|',$shortcode['data']); ?>

		<?php if(empty($rencCustom['country'])) { ?>
		
		<div>
			<label><?php _e('My country','rencontre'); ?></label>
			<select id="rencPays" name="pays" class="w3-select w3-border w3-renc-sebg" <?php echo $geoData['onChangeCountry']; ?>>
				<option value=""> - </option>
				<?php RencontreWidget::f_pays($data[0],1); ?>

			</select>
		</div>
		<?php } ?>
		<?php if(empty($rencCustom['region'])) { ?>
		
		<div>
			<label><?php _e('My region','rencontre'); ?></label>
			<select id="regionSelect" name="region" class="w3-select w3-border w3-renc-sebg" style="display:none;" <?php echo $geoData['onChangeRegion']; ?> >
				<option value=""> - </option>
			</select>
		</div>
		<?php } ?>
		
		<div>
			<label><?php _e('My city','rencontre'); ?></label>
			<input id="rencVille" name="ville" type="text" autocomplete="off" class="w3-input w3-renc-inbg" value="<?php echo $data[2]; ?>" style="display:none;"  />
			<input id="gps" name="gps" type="hidden" value="<?php echo $data[1].'|'.$data[2]; ?>" />
			<div id="rencCity" class="rencCity"></div>
			<div class="w3-clear" style="height:5px;"></div>					
			<div id="geomap" style="display:none;height:300px;max-width:400px"></div>
		</div>
	<script type="text/javascript">
	var wpgeomap,wpgeoajx;
	if('<?php echo addslashes($data[0]); ?>'!=''){
		jQuery(document).ready(function(){
			jQuery('#rencPays option[value="<?php echo addslashes($data[0]); ?>"]').attr('selected','selected');
			geoDataRegion(1);
		});
	}
	function geoDataRegion(f){
		if(typeof(f)=='undefined')f=0;
		jQuery('#geomap').hide();
		jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{'action':'geoDataRegion','country':jQuery('#rencPays option:selected').val()},function(data){
			var r=jQuery.parseJSON(data.substring(0,data.length-1));
			jQuery('#rencCity').empty();
			jQuery('#regionSelect').show().empty().append('<option value="0"> - </option>');
			jQuery.each(r,function(k,v){
				jQuery('#regionSelect').append('<option value="'+v.name+'" data-regionid="'+v.regionid+'">'+v.name+'</option>');
			});
			if(f==1&&'<?php echo addslashes($data[1]); ?>'!=''){
				jQuery('#regionSelect option[value="<?php echo addslashes($data[1]); ?>"]').attr('selected','selected');
				geoDataCity();
			}
		});
	}
	function geoDataCity(){
		jQuery('#rencVille').show().keyup(function(){
			if(jQuery('#rencVille').val().length>2){
				wpgeoajx=null;
				var regid=jQuery('#regionSelect option:selected').attr('data-regionid');
				if(regid.substr(0,2)!='zz'){
					wpgeoajx=jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{'action':'geoDataCity','country':jQuery('#rencPays option:selected').val(),'region':regid,'city':jQuery('#rencVille').val(),'nbcity':8},function(data){
						jQuery('#rencCity').show().empty();
						var r=jQuery.parseJSON(data.substring(0,data.length-1));
						jQuery.each(r,function(k,v){
							jQuery('#rencCity').append('<div class="cityrencVille"><span style="cursor:pointer" onClick="wpGeonameCityMap(\''+v.name+'\','+v.latitude+','+v.longitude+');">'+v.name+'</span></div>');
						});
					});
				}
			}
		});
	}
	function wpGeonameCityMap(ci,lat,lon){
		jQuery('#geomap').show();
		jQuery('#gps').val(lat+'|'+lon);
		jQuery('#rencVille').val(ci);
		if(typeof(wpgeomap)!='undefined'){
			wpgeomap.off();
			wpgeomap.remove();
		}
		wpgeomap=new L.map('geomap').setView([lat,lon],9);
		var wpgeodata=new L.TileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{minZoom:5,maxZoom:14,attribution:'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		wpgeomap.addLayer(wpgeodata);
		var wpgeomark=L.marker([lat,lon]).addTo(wpgeomap);
		wpgeomark.bindPopup("<b>"+ci+"</b>").openPopup();
	}		
	</script>

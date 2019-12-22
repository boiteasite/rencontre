/* Rencontre */
var rencEmot=["",":-)",":-(",":-d",":-D",";;)","8-)",":-/",":-3",":-r",":-p",":-*",":-K",":-O",":-S","B-)"],rencVeil,rencDVeil,rencSetiCam1=false,rencSetiCam2=false,rencStream=false,rencScrut,rencMoi='',rencToi='',rencSave=1,rencName='',rencWidg=1,rencLast=0;
if(typeof noEmot==='undefined')var noEmot=0;
if(typeof rencUrl==='undefined'){
	rencUrl=window.location.protocol+'//'+window.location.hostname;
	rencWidg=0;
}
/* fonctions classiques */
function f_min(f,x,y,z){
	var c=0,d=document.forms[x][y],e=document.forms[x][z];
	f=parseInt(f);
	for(v=0;v<e.length;v++){
		if(parseInt(d.options[v].value)==f)c=v;
		if(parseInt(e.options[v].value)<=f)e.options[v].disabled=true;
		else e.options[v].disabled=false;
	}
	if(e.options[e.selectedIndex]&&f>parseInt(e.options[e.selectedIndex].value))e.selectedIndex=c;
}
function f_max(f,x,y,z){
	var c=0,d=document.forms[x][z],e=document.forms[x][y];
	f=parseInt(f);
	for(v=0;v<e.length;v++){
		if(parseInt(d.options[v].value)==f)c=v;
		if(parseInt(e.options[v].value)>=f)e.options[v].disabled=true;
		else e.options[v].disabled=false;
	}
	if(e.options[e.selectedIndex]&&f<parseInt(e.options[e.selectedIndex].value))e.selectedIndex=c;
}
function f_onglet(f){
	if(document.getElementById("portraitBox")!==null){ // V3
		jQuery("#portraitTab"+rencLast).removeClass("w3-renc-mebt");
		jQuery("#portraitTab"+f).addClass("w3-renc-mebt");
		jQuery("#portraitCont"+rencLast).removeClass("w3-show");
		jQuery("#portraitCont"+f).addClass("w3-show");
	}
	else{ // V2
		document.getElementById('portraitTable'+rencLast).style.display='none';
		document.getElementById('portraitTable'+f).style.display='table';
		document.getElementById('portraitOnglet'+rencLast).className='portraitOnglet';
		document.getElementById('portraitOnglet'+f).className='portraitOnglet rencTab';
	}
	rencLast=f;
}
function f_vignette(f,img){
	ff=rencBaseurl+"/portrait/"+Math.floor((f)/10000)+"/"+img+".jpg?r="+Math.random();
	document.getElementById('portraitGrande').src=ff;
}
function f_vignette_change(f,img){
	f_vignette(f,img);
	document.getElementById('changePhoto').innerHTML='';
}
function f_supp_photo(f){
	document.getElementById('changePhoto').innerHTML='<a href="javascript:void(0)" class="rencSupp" onClick="document.forms[\'portraitPhoto\'].elements[\'a1\'].value=\'suppImg\';document.forms[\'portraitPhoto\'].elements[\'a2\'].value=\''+f+'\';document.forms[\'portraitPhoto\'].elements[\'renc\'].value=\'edit\';document.forms[\'portraitPhoto\'].submit();" title="'+rencobjet.supp_la_photo+'">'+rencobjet.supp_photo+'</a>';
}
function f_photoPop_display(f){
	if(f.files&&f.files[0]){
		f_exifOrientation(f.files[0],function(o){
			var r=new FileReader(),b=document.getElementById('popPhoto');
			r.onload=function(e){
				while(b.firstChild)b.removeChild(b.firstChild);
				var a=document.createElement('img');
				a.src=e.target.result;
				a.className='pop-photo';
				a.style.width='auto';
				a.style.height='60px';
				b.appendChild(a);
				if(o==3)jQuery('#popPhoto img').rotate({angle:180});
				else if(o==6)jQuery('#popPhoto img').rotate({angle:90});
				else if(o==8)jQuery('#popPhoto img').rotate({angle:270});
				a=document.createElement('span');
				a.className='w3-button w3-renc-mebt w3-renc-mebo w3-margin rotateLeft';
				a.onclick=function(){
					var r=jQuery('#popPhoto img').getRotateAngle(),s=Number(r)-90;
					jQuery('#popPhoto img').rotate({angle:r,animateTo:s});
				};
				a.innerHTML='<i class="fas fa-undo"></i>';
				b.appendChild(a);
				a=document.createElement('span');
				a.className='w3-button w3-renc-mebt w3-renc-mebo w3-margin rotateRight';
				a.onclick=function(){
					var r=jQuery('#popPhoto img').getRotateAngle(),s=Number(r)+90;
					jQuery('#popPhoto img').rotate({angle:r,animateTo:s});
				};
				a.innerHTML='<i class="fas fa-redo"></i>';
				b.appendChild(a);
			}
			r.readAsDataURL(f.files[0]);
		});
	}
}
function f_exifOrientation(f,g){
	if(typeof DataView==="undefined")return g(0); // old browser
	var r=new FileReader();
	r.onload=function(e){
		var d=new DataView(e.target.result),i,l,m,n,o=2,t;
		if(d.getUint16(0,false)!=0xFFD8)return g(-2);
		l=d.byteLength;
		while(o<l){
			m=d.getUint16(o,false);
			o+=2;
			if(m==0xFFE1){
				if(d.getUint32(o+=2,false)!=0x45786966)return g(-1);
				n=d.getUint16(o+=6,false)==0x4949;
				o+=d.getUint32(o+4,n);
				t=d.getUint16(o,n);
				o+=2;
				for(i=0;i<t;++i)if(d.getUint16(o+(i*12),n)==0x0112)return g(d.getUint16(o+(i*12)+8,n));
			}
			else if((m&0xFF00)!=0xFF00)break;
			else o+=d.getUint16(o,false);
		}
		return g(-1);
	}
	r.readAsArrayBuffer(f);
}
function f_plus_photoPop_submit(f){
	var a=document.forms['portraitPhotoPop'];
	if(a.elements['plusPhoto'].value=='')return;
	a.elements['a1'].value='plusImg';
	a.elements['a2'].value=f;
	a.elements['rotate'].value=jQuery('#popPhoto img').getRotateAngle();
	a.elements['renc'].value='edit';
	a.submit();
}
function f_suppAll_photo(){
	var a=document.forms['portraitPhoto'];
	a.elements['a1'].value='suppImgAll';
	a.elements['renc'].value='edit';
	a.submit();
}
function f_sauv_profil(f){
	var a=document.forms['portraitChange'];
	a.elements['a1'].value='sauvProfil';
	a.elements['a2'].value=f;
	a.elements['renc'].value='edit';
	a.submit();
}
function f_fantome(){
	jQuery('#rencFantome').remove();
	document.cookie="rencfantome=oui";
}
function f_mod_nouveau(f){
	var a=0,c=document.forms['formNouveau'],b=c.elements,d,e,g;
	if(b['sex']&&b['sex'].value=='')a++;
	if(b['jour']&&b['jour'].value=='')a++;
	if(b['mois']&&b['mois'].value=='')a++;
	if(b['annee']&&b['annee'].value=='')a++;
	if(b['taille']&&b['taille'].value=='')a++;
	if(b['poids']&&b['poids'].value=='')a++;
	if(b['zsex']&&b['zsex'].value=='')a++;
	if(b['zsex[]']){
		d=0;
		if(!b['zsex[]'].length){
			if(b['zsex[]'].checked==true)d++;
		}
		else for(v=0;v<b['zsex[]'].length;++v){
			if(b['zsex[]'][v].checked)d++;
		};
		if(d==0)a++;
	}
	if(b['zageMin']&&b['zageMin'].value=='')a++;
	if(b['zageMax']&&b['zageMax'].value=='')a++;
	if(b['zrelation']&&b['zrelation'].value=='')a++;
	if(b['zrelation[]']){
			d=0;
			if(!b['zrelation[]'].length){
				if(b['zrelation[]'].checked==true)d++;
			}
			else for(v=0;v<b['zrelation[]'].length;++v){
				if(b['zrelation[]'][v].checked)d++;
			};
			if(d==0)a++;
		}
	if(b['pays']&&b['pays'].options[b['pays'].selectedIndex].value==0)a++;
	if(b['region']&&b['region'].options[b['region'].selectedIndex].value==0)a++;
	if(b['ville']&&b['ville'].value=='')a++;
	if(a==0){
		b['a1'].value=f;
		e=document.getElementById('fastregInfo');
		if(e!==null)e.style.display='none';
		c.submit();
	}
	else jQuery('#rencAlert').html(a+'&nbsp;'+rencobjet.champs_incomplets).show(0).delay(5000).hide(0);
}
function f_fin(f){
	if(confirm(rencobjet.conf_supp_compte)){
	var a=document.forms['formFin'];
		a.elements['renc'].value='fin';
		a.elements['id'].value=f;
		a.submit();
	}
}
function f_trouve(){
	var a=document.forms['formTrouve'];
	a.elements['renc'].value='liste';
	a.submit();
}
function f_quickTrouve(){
	var a=document.forms['formMonAccueil'];
	a.elements['renc'].value='qsearch';
	a.submit();
}
/* Popup avec photo FB */
function f_plus_photoFB_submit(f,g){
	var a=document.forms['portraitPhotoPop'];
	a.elements['a1'].value='plusImg';
	a.elements['a2'].value=f+'|'+g;
	a.elements['renc'].value='edit';
	a.submit();
}
function f_FBLogin(f){
	FB.getLoginStatus(function(response){
		if(response.status==='connected')f_FBProfileImage(f);
		else if(response.status==='not_authorized'){
			FB.login(function(response){
				if(response&&response.status==='connected')f_FBProfileImage(f);
			});
		}
		else{
			FB.login(function(response){
				if(response&&response.status==='connected')f_FBProfileImage(f);
			});
		}
	});
}
function f_FBProfileImage(f){
	FB.api("/me/picture?type=large&redirect=false",function(response){
	var u=response.data.url,a=document.createElement("img");
		a.src=u;
		a.className='pop-photo';
		a.style.width='auto';
		a.style.height='60px';
		document.getElementById('popPhoto').appendChild(a);
		document.getElementById('popPhotoSubm').onclick=function(){f_plus_photoFB_submit(f,u);};
	});  
}
function f_msgEmot(d){
	for(v=1;v<16;v++){
		d1=document.createElement("img");
		d1.src=rencUrl+"/wp-content/plugins/rencontre/images/"+v+".gif";
		d1.alt=v;
		d1.onclick=function(){
			a=formEcrire.elements['contenu'];
			a.value+=rencEmot[this.alt];
		};
		d.appendChild(d1);
	};
}
function f_msgEmotContent(f){
	for(v in f){
		f[v].innerHTML=f_emot(f[v].innerHTML);
	}
}
/* Menu via WP */
function f_renc_menu(f,i,c){
	if(c){
		jQuery("."+c).parent().children().removeClass("w3-renc-mebt");
		jQuery("."+c).addClass("w3-renc-mebt");
	}
	jQuery(".rencMenuCard").click(function(){
		document.forms['rencMenu'].elements['renc'].value='card';
		document.forms['rencMenu'].submit();
	});
	if(f.edit)jQuery(".rencMenuEdit").click(function(){
		document.forms['rencMenu'].elements['renc'].value='edit';
		document.forms['rencMenu'].submit();
	});
	else jQuery(".rencMenuEdit").addClass("menu-item-off");
	if(f.msg)jQuery(".rencMenuMsg").click(function(){
		document.forms['rencMenu'].elements['renc'].value='msg';
		document.forms['rencMenu'].submit();
	});
	else jQuery(".rencMenuMsg").addClass("menu-item-off");
	if(f.search)jQuery(".rencMenuSearch").click(function(){
		document.forms['rencMenu'].elements['renc'].value='gsearch';
		document.forms['rencMenu'].submit();
	});
	else jQuery(".rencMenuSearch").addClass("menu-item-off");
	jQuery(".rencMenuAccount").click(function(){
		document.forms['rencMenu'].elements['renc'].value='account';
		document.forms['rencMenu'].submit();
	});
	jQuery(".rencMenuC1").click(function(){
		document.forms['rencMenu'].elements['renc'].value='c1';
		document.forms['rencMenu'].submit();
	});
	jQuery(".rencMenuC2").click(function(){
		document.forms['rencMenu'].elements['renc'].value='c2';
		document.forms['rencMenu'].submit();
	});
}
/* fonctions avec appel Ajax */
function f_gpsNavigator(g,h){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(function(f){
			jQuery.post(g,{'action':'gpsnavigator','lat':f.coords.latitude,'lon':f.coords.longitude,'acc':f.coords.accuracy,'opt':h,'rencTok':rencTok});
			if(document.getElementById('gps')!==null)document.getElementById('gps').value=f.coords.latitude+'|'+f.coords.longitude;
		},function error(err){});
	}
}
function f_region_select(f,g,x){
	jQuery(document).ready(function(){
		jQuery('#'+x).empty();
		jQuery.post(g,{'action':'regionBDD','pays':f,'rencTok':rencTok},function(r){
			jQuery('#'+x).append(r);
		});
	});
}
function f_voir_msg(f,g,h,ho){
	jQuery(document).ready(function(){
		jQuery.post(g,{'action':'voirMsg','idmsg':f,'alias':h,'ho':ho,'rencTok':rencTok},function(r){
			jQuery('#rencMsg').empty();
			jQuery('#rencMsg').append(r.substring(0,r.length-1));
		});
	});
}
function f_password(f0,f1,f2,f,g){
	if(f1.length<6)return;
	if(f1!=f2)jQuery('#rencAlertPass').html(rencobjet.nouv_pass_diff).show(0).delay(5000).hide(0);
	else{
		if(document.getElementById('buttonPass')!==null)document.getElementById('buttonPass').style.visibility="hidden";
		jQuery.post(g,{'action':'testPass','id':f,'pass':f0,'nouv':f1,'rencTok':rencTok},function(r){
			if(r!=0){
				d=document.forms['formPass'];
				d.elements['renc'].value='paswd';
				d.elements['id'].value=f;
				d.submit();
			}
			else{
				jQuery('#rencAlertPass').html(rencobjet.pass_init_faux+r.substring(0,r.length-1)).show(0).delay(5000).hide(0);
				if(document.getElementById('buttonPass')!==null)document.getElementById('buttonPass').style.visibility="visible";
			}
		});
	}
}
function f_fastregMail(g){
	jQuery.post(g,{'action':'fastregMail','rencTok':rencTok},function(r){
		if(r){
			document.cookie="rencfastregMail=oui";
			jQuery('#rencAlertEmail').html(r.substring(0,r.length-1)).show(0).delay(5000).hide(0);
		}
	});
}
//
function f_nouveau(f,g,e){
	var a=0,d=0,c=document.forms['formNouveau'],b=c.elements,v;
	if(e==0){
		if(b['sex']&&b['sex'].value=='')a++;
		if(b['jour']&&b['jour'].value=='')a++;
		if(b['mois']&&b['mois'].value=='')a++;
		if(b['annee']&&b['annee'].value=='')a++;
		if(b['taille']&&b['taille'].value=='')a++;
		if(b['poids']&&b['poids'].value=='')a++;
	}
	else if(e==1){
		if(b['pays']&&b['pays'].value=='')a++;
		if(b['ville']&&b['ville'].value=='')a++;
	}
	else if(e==2){
		if(b['zsex']&&b['zsex'].value=='')a++;
		if(b['zsex[]']){
			d=0;
			if(!b['zsex[]'].length){
				if(b['zsex[]'].checked==true)d++;
			}
			else for(v=0;v<b['zsex[]'].length;++v){
				if(b['zsex[]'][v].checked)d++;
			};
			if(d==0)a++;
		}
		if(b['zageMin']&&b['zageMin'].value=='')a++;
		if(b['zageMax']&&b['zageMax'].value=='')a++;
		if(b['zrelation']&&b['zrelation'].value=='')a++;
		if(b['zrelation[]']){
			d=0;
			if(!b['zrelation[]'].length){
				if(b['zrelation[]'].checked==true)d++;
			}
			else for(v=0;v<b['zrelation[]'].length;++v){
				if(b['zrelation[]'][v].checked)d++;
			};
			if(d==0)a++;
		}
	}
	if(a==0){
		b['a1'].value=f;
		c.submit();
	}
	else jQuery('#rencAlert').html(a+' '+rencobjet.champs_incomplets).show(0).delay(5000).hide(0);
}
/* Tchat */
function f_bip(){
	at={
		"mp3":"audio/mpeg",
		"mp4":"audio/mp4",
		"ogg":"audio/ogg",
		"wav":"audio/wav"
	};
	am=["bip.ogg","bip.mp3"];
	bip=document.createElement('audio');
	if(bip.canPlayType){
		for(i=0;i<am.length;i++){
			sl=document.createElement('source');
			sl.setAttribute('src',rencobjet.bipurl+am[i]);
			if(am[i].match(/\.(\w+)$/i))sl.setAttribute('type',at[RegExp.$1]);
			bip.appendChild(sl);
		}
		bip.load();
		bip.playclip=function(){
			bip.pause();
			bip.currentTime=0;
			bip.play();
		};
		return bip;
	}
	else return;
};
function f_emot(f){
	if(noEmot==1)return f;
	if(typeof f!=='undefined')for(var v=1;v<16;v++){
		var r=rencEmot[v].replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
		var re=new RegExp(r,'g');
		f=f.replace(re,"<img src='"+rencUrl+"/wp-content/plugins/rencontre/images/"+v+".gif' alt='' />");
	};
	return f;
}
function f_tchat_off_session(){
	jQuery.post(rencobjet.ajaxchat,{'tchat':'session','fm':rencobjet.mid,'d':rencBasedir,'c':rencTokc});
}
function f_tchat_veille(s,p){
	if(!s)s='';
	if(p)rencName=p;
	jQuery(document).ready(function(){
		jQuery.post(rencobjet.ajaxchat,{'tchat':'tchatVeille','fm':rencobjet.mid,'d':rencBasedir,'c':rencTokc},function(r){
			if(r){
				clearInterval(rencVeil);
				if(r==s)f_tchat_ok(rencobjet.mid,r,rencobjet.ajaxchat);
				else if(r!=rencobjet.mid)f_tchat_dem(rencobjet.mid,r);
				else f_tchat(r,s,rencobjet.ajaxchat,1,rencobjet.tchatname);
			}
		});
	});
}
function f_tchat(f,t,g,p,s){
	clearInterval(rencDVeil);
	rencName=s;
	jQuery("#rcName").html('('+rencName+')');
	if(document.getElementById('rencCam')!==null){
		jQuery("#rcBcam").removeClass('w3-hide').click(function(){
			if(rencMoi!=''){
				rencMoi='';f_camOff();
			}
			else webcam(f,t);
		});
	}
	jQuery("#rcClose").click(function(){f_tchat_fin(f,t,g);});
	jQuery("#rcInput").removeClass('w3-hide').val(rencobjet.ecrire_appuyer).prop("disabled",true).focus(function(){
		if(!jQuery(this).hasClass('actif')){
			jQuery(this).addClass('actif').val('').css('color','#222');
		}
	});
	jQuery("#rcInput").keypress(function(e){
		if(e.which==13&&jQuery(this).val()){
			var h=jQuery(this).val();
			h=jQuery('<p>'+h+'</p>').text();
			f_tchat_envoi(f,t,h,g);
			jQuery(this).val('');
		}
	});
	if(document.getElementById("rcEmot")!==null)for(v=1;v<16;v++){
		jQuery("#rcEmot").children("img").eq(v-1).click(function(){
			var a=jQuery("#rcInput");
			if(!a.hasClass('actif')){
				a.addClass('actif').val('').css('color','#222');
			}
			if(!a.prop('disabled')){
				a.val(a.val()+rencEmot[this.alt]).focus();
			}
		});
	}
	jQuery("#rencChat").removeClass('w3-hide');
	clearInterval(rencVeil);
	if(p==1){
		jQuery("#rcContent").empty().append('<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.tchat_attendre+'</div><div class="w3-col s2 m3">&nbsp;</div></div>');
		f_tchat_debut(f,t,g);
	}
	else if(p==0){
		rencScrut=setInterval(function(){
			f_tchat_scrute(f,t,g);
		},2023);
	};
}
function f_tchat_debut(f,t,g){
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatDebut','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			rencScrut=setInterval(function(){
				f_tchat_scrute(f,t,g);
			},2023);
		});
	});
}
function f_tchat_scrute(f,t,g){
	jQuery(document).ready(function(){
		if(!jQuery("#rcInput").hasClass('w3-hide')){
			jQuery.post(g,{'tchat':'tchatScrute','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
				if(r=='::'+f+'::')f_tchat_off();
				else if(r){
					if(jQuery("#rcInput").prop('disabled'))f_tchat_on();
					f_tchat_actualise("",r,f,t);
				}
			});
		}
	});
}
function f_tchat_dem(f,t){
	jQuery("#rcClose").click(function(){f_tchat_fin(f,t,rencobjet.ajaxchat);});
	jQuery("#rcContent").empty().append('<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.demande_tchat+'&nbsp;:&nbsp;</div><div class="w3-col s2 m3">&nbsp;</div></div>');
	jQuery(document).ready(function(){
		jQuery.post(rencobjet.wpajax,{'action':'miniPortrait2','id':t,'rencTok':rencTok},function(r){
			r=r.split('|');
			rencName=r[0];
			jQuery("#rcContent").append(r[1].substring(0,r[1].length-1));
			jQuery("#rcYes").click(function(){
				f_tchat_ok(f,t,rencobjet.ajaxchat);
			});
			jQuery("#rcNo").click(function(){
				f_tchat_fin(f,t,rencobjet.ajaxchat);
			});
			jQuery("#rencChat").removeClass('w3-hide');
			rencDVeil=setInterval(f_tchat_dem_veille,5111);
		});
	});
}
function f_tchat_dem_veille(s,p){
	if(!s)s='';
	if(p)rencName=p;
	jQuery(document).ready(function(){
		jQuery.post(rencobjet.ajaxchat,{'tchat':'tchatDemVeille','fm':rencobjet.mid,'d':rencBasedir,'c':rencTokc},function(r){
			if(r==1){
				clearInterval(rencDVeil);
				jQuery("#rcContent").empty();
				rencVeil=setInterval(f_tchat_veille,5111);
			}
		});
	});
}
function f_tchat_ok(f,t,g){
	clearInterval(rencDVeil);
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatOk','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			f_tchat(f,t,g,0,rencName);
			jQuery("#rcContent").empty();
			jQuery("#rcInput").prop("disabled",false);
		});
	});
}
function f_tchat_on(){
	jQuery("#rcInput").prop("disabled",false);
	jQuery("#rcContent").append('<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.tchat_dem_ok+'</div><div class="w3-col s2 m3">&nbsp;</div></div>');
}
function f_tchat_off(){
	var u=navigator.userAgent.toLowerCase(),sm=u.indexOf("android")>-1;
	clearInterval(rencScrut);
	jQuery("#rcInput").prop("disabled",true);
	var a='<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.ferme_fenetre+'</div><div class="w3-col s2 m3">&nbsp;</div></div>';
	if(sm)jQuery("#rcContent").prepend(a);
	else jQuery("#rcContent").append(a).scrollTop(jQuery("#rcContent")[0].scrollHeight);
	jQuery("#rcBcam").click(function(){
		return true;
	});
}
function f_tchat_envoi(f,t,h,g){
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatEnvoi','fm':f,'to':t,'msg':h,'d':rencBasedir,'c':rencTokc},function(r){
			f_tchat_actualise(h,r,f,t);
		});
	});
}
function f_tchat_actualise(h,r,f,t){
	var a,u=navigator.userAgent.toLowerCase(),sm=u.indexOf("android")>-1,c=document.getElementById('rcContent');
	h=f_emot(h);
	r=f_emot(r);
	if(r){
		r1=r.split('['+t+']');
		if(r1!=null){
			for(v=0;v<r1.length;v++){
				if(r1[v].length>0&&r1[v]!="-"){
					if(r1[v].charCodeAt(r1[v].length-1)==127){
						r1[v]=r1[v].substr(0,r1[v].length-1);
						if(jQuery("#rcBcam").hasClass('w3-hide')){
							jQuery("#rcBcam").removeClass('w3-hide').click(function(){
								if(rencMoi!=''){
									rencMoi='';f_camOff();
								}
								else webcam(f,t);
							});
						}
					}
					var fbip=f_bip();
					a='<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+'<b>'+rencName+'</b><br />'+r1[v]+'</div><div class="w3-col s2 m3">&nbsp;</div></div>';
					if(sm)jQuery("#rcContent").prepend(a);
					else jQuery("#rcContent").append(a);
					fbip.playclip();
				}
			}
		}
	}
	if(h){
		a='<div class="w3-row w3-padding-small"><div class="w3-col s2 m3">&nbsp;</div><div class="w3-renc-msbr rcMe w3-col s10 m9 w3-card w3-padding">'+h+'</div></div>';
		if(sm)jQuery("#rcContent").prepend(a);
		else jQuery("#rcContent").append(a);
	}
	if(!sm)jQuery("#rcContent").scrollTop(jQuery("#rcContent")[0].scrollHeight);
}
function f_tchat_fin(f,t,g){
	jQuery(document).ready(function(){
		clearInterval(rencScrut);clearInterval(rencDVeil);
		jQuery.post(g,{'tchat':'tchatFin','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			rencVeil=setInterval(f_tchat_veille,5111);
		});
		jQuery("#rencChat").addClass('w3-hide');
		jQuery("#rcContent").empty();
		jQuery("#rcInput").addClass('w3-hide').val('');
	});
	if(rencMoi!=''){
		rencMoi='';
		f_camOff();
	}
}
/* Webcam */
function webcam(f,t){
	rencMoi=f+"-"+t;
	rencToi=t+"-"+f;
	var m=document.getElementById('rencCam2'),c=document.getElementById('rencCamCanvas');
	navigator.getMedia=(navigator.getUserMedia||navigator.webkitGetUserMedia||navigator.mozGetUserMedia||navigator.msGetUserMedia);
	navigator.getMedia(
		{video:{width:{ideal:160},height:{ideal:120}},audio:false},
		function(stream){
			rencStream=stream;
			try{
				video.srcObject=stream;
			}catch(error){
				var u=window.URL||window.webkitURL;
			//	m.src=u.createObjectURL(stream); // deprecated
				m.srcObject=stream;
			}
			m.onloadedmetadata=function(e){m.play();};
		},
		function(err){console.log(err.name + " : " + err.message);}
	);
	m.addEventListener('canplay',function(e){
		m.style.visibility="visible";
		m.style.width="160px";
		m.style.height="120px";
		c.setAttribute('width',300);
		c.setAttribute('height',225);
		rencSetiCam1=setInterval(saveCam,790);
	});
	stream_on();
}
function f_camOff(){
	var a=document.getElementById('rencCam'),m=document.getElementById('rencCam2'),t;
	if(rencSetiCam1)clearInterval(rencSetiCam1);
	if(rencSetiCam2)clearInterval(rencSetiCam2);
	if(rencStream){
		t=rencStream.getTracks();
		t.forEach(function(r){r.stop();});
	}
	rencStream=false;rencSetiCam1=false;rencSetiCam2=false;
	a.innerHTML="";
	a.style.visibility="hidden";
	m.style.bottom="10px";
	m.style.visibility="hidden";
}
function saveCam(){
	if(rencSave==1&&rencSetiCam1){
		rencSave=0;
		var c=document.getElementById('rencCamCanvas'),i,m=document.getElementById('rencCam2'),t=c.getContext("2d"),x=new XMLHttpRequest();
		t.drawImage(m,0,0,300,225);
		i=c.toDataURL('image/jpeg',.5);
		x.open("POST",rencobjet.ajaxchat,true);
		x.setRequestHeader('Content-Type',"application/x-www-form-urlencoded; charset=UTF-8");
		x.setRequestHeader("X-Requested-With","XMLHttpRequest");
		x.onreadystatechange=function(){if(x.readyState==4)rencSave=1;};
		x.send("tchat=cam&id="+rencMoi+"&image="+i+"&d="+rencBasedir+"&c="+rencTokc);
	}
}
function stream_cam(){
	if(!rencSetiCam2)return;
	document.getElementById('rencCamImg').src=rencBaseurl+'/tchat/cam'+rencToi+'.jpg?'+new Date().getTime();
}
function stream_on(){
	var a=document.getElementById('rencCam'),b=document.createElement("img"),m=document.getElementById('rencCam2');
	b.id="rencCamImg";
	b.src="";
	a.appendChild(b);
	a.style.visibility="visible";
	m.style.bottom="245px";
	rencSetiCam2=setInterval(stream_cam,890); // +100ms
}
function f_dynamicWidth(){
	var a=jQuery(".rencMyHome").width(),b=200,c=Math.max(Math.floor(a/(b+16)),1);
	jQuery(".rencMiniPortrait").width(Math.floor(a/c)-16.51).css("min-width",b);
}
function f_dynamicWidthLibre(){
	var a=jQuery(".ficheLibre").width(),b=200,c=Math.max(Math.floor(a/(b+16)),1);
	jQuery(".rencLibrePortrait").width(Math.floor(a/c)-16.51);
}
//
if(document.getElementById("infoChange")!==null)window.setTimeout(function(){jQuery("#infoChange").remove()},((typeof rencInfochange!=='undefined')?rencInfochange:5000));
jQuery(document).ready(function(){
	if(typeof rencobjet!=='undefined'){
		if(rencobjet.tchaton==1)rencVeil=setInterval(f_tchat_veille,5111);
		else rencVeil=setInterval(f_tchat_off_session,21111);
	}
});

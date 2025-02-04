/* Rencontre */
var rencEmot=["",":-)",":-(",":-d",":-D",";;)","8-)",":-/",":-3",":-r",":-p",":-*",":-K",":-O",":-S","B-)"],rencVeil=false,rencDVeil=false,rencSetiCam1=false,rencSetiCam2=false,rencStream=false,rencScrut=false,rencMoi='',rencToi='',rencSave=1,rencName='',rencWidg=1,rencLast=0;
if(typeof noEmot==='undefined')var noEmot=0;
if(typeof rencUrl==='undefined'){
	rencUrl=window.location.protocol+'//'+window.location.hostname;
	rencWidg=0;
}
/* fonctions classiques */
function f_min(f,x,y,z){
	var c=0,d=document.forms[x][y],e=document.forms[x][z];
	f=parseFloat(f);
	for(v=0;v<e.length;v++){
		if(parseFloat(d.options[v].value)==f)c=v;
		if(parseFloat(e.options[v].value)<=f)e.options[v].disabled=true;
		else e.options[v].disabled=false;
	}
	if(e.options[e.selectedIndex]&&f>parseFloat(e.options[e.selectedIndex].value))e.selectedIndex=c;
}
function f_max(f,x,y,z){
	var c=0,d=document.forms[x][z],e=document.forms[x][y];
	f=parseFloat(f);
	for(v=0;v<e.length;v++){
		if(parseFloat(d.options[v].value)==f)c=v;
		if(parseFloat(e.options[v].value)>=f)e.options[v].disabled=true;
		else e.options[v].disabled=false;
	}
	if(e.options[e.selectedIndex]&&f<parseFloat(e.options[e.selectedIndex].value))e.selectedIndex=c;
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
function f_vignette(f,img){ // before V 3.8.1
	var ff=rencBaseurl+"/portrait/"+Math.floor((f)/10000)+"/"+img+".jpg?r="+Math.random();
	document.getElementById('portraitGrande').src=ff;
}
function f_vignetteRetina(f,img,ret,b){
	var ff=rencBaseurl+"/portrait/"+Math.floor((f)/10000)+"/"+img;
	document.getElementById('portraitGrande').src=ff+".jpg?"+b;
	if(ret)document.getElementById('portraitGrande').srcset=ff+"@2x.jpg?"+b+" 2x";
}
function f_vignette_change(f,img){ // before V 3.8.1
	f_vignette(f,img);
	jQuery('#changePhoto .rencMain').hide();
	document.getElementById('changePhoto').style.display='none';
	jQuery('#clickPhoto').show();
}
function f_vignetteRetina_change(f,img,ret,b){
	f_vignetteRetina(f,img,ret,b);
	jQuery('#changePhoto .rencMain').hide();
	document.getElementById('changePhoto').style.display='none';
	jQuery('#clickPhoto').show();
}
function f_change_photo(f,g){
	document.getElementById('changePhoto').style.display='block';
	if(g)jQuery('#changePhoto .rencMain').show();
	jQuery('#clickPhoto').hide();
	document.getElementById("changePhoto").setAttribute("data-photo",f);
}
function f_supp_photo(f){
	var a=document.forms['portraitPhoto'];
	a.elements['a1'].value='suppImg';
	a.elements['a2'].value=f;
	a.elements['P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc')].value=(typeof lbl.edit!="undefined"?lbl.edit:'edit');
	a.submit();
}
function f_rotate_photo(f,g){
	var a=document.forms['portraitPhoto'];
	a.elements['a1'].value=(g==0?'rotateLImg':'rotateRImg');
	a.elements['a2'].value=f;
	a.elements['P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc')].value=(typeof lbl.edit!="undefined"?lbl.edit:'edit');
	a.submit();
}
function f_main_photo(f){
	var a=document.forms['portraitPhoto'];
	a.elements['a1'].value='mainImg';
	a.elements['a2'].value=f;
	a.elements['P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc')].value=(typeof lbl.edit!="undefined"?lbl.edit:'edit');
	a.submit();
}
function f_photoPop_display(f){
	if(f.files&&f.files[0]){
		f_exifOrientation(f.files[0],function(o){
			var r=new FileReader(),b=document.getElementById('popPhoto'),x={3:180,6:90,8:270};
			r.onload=function(e){
				while(b.firstChild)b.removeChild(b.firstChild);
				var a=document.createElement('img');
				a.src=e.target.result;
				a.className='pop-photo';
				a.style.width='auto';
				a.style.height='60px';
				b.appendChild(a);
				if(o)jQuery('#popPhoto img').rotate({angle:x[o]});
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
	var a=document.forms['portraitPhotoPop'],
		jrenc='P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
		jedit=(typeof lbl.edit!="undefined"?lbl.edit:'edit');
	if(a.elements['plusPhoto'].value=='')return;
	a.elements['a1'].value='plusImg';
	a.elements['a2'].value=f;
	a.elements['rotate'].value=jQuery('#popPhoto img').getRotateAngle();
	a.elements[jrenc].value=jedit;
	a.submit();
}
function f_suppAll_photo(){
	var a=document.forms['portraitPhoto'],
		jrenc='P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
		jedit=(typeof lbl.edit!="undefined"?lbl.edit:'edit');
	a.elements['a1'].value='suppImgAll';
	a.elements[jrenc].value=jedit;
	a.submit();
}
function f_sauv_profil(f){
	var a=document.forms['portraitChange'],
		jrenc='P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
		jedit=(typeof lbl.edit!="undefined"?lbl.edit:'edit');
	a.elements['a1'].value='sauvProfil';
	a.elements['a2'].value=f;
	a.elements[jrenc].value=jedit;
	a.submit();
}
function f_titann(){
	jQuery('#rencTitann').remove();
	document.cookie="titann=yes";
}
function f_nouveau(f,e){
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
		if(b['gps']&&b['gps'].value=='|')a++;
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
function f_fin(){
	if(confirm(rencobjet.conf_supp_compte)){
		var a=document.forms['formFin'],
			jrenc='P'+(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
			jfin=(typeof lbl.fin!="undefined"?lbl.fin:'fin');
		a.elements[jrenc].value=jfin;
		a.submit();
	}
}
function f_trouve(){
	var a=document.forms['formTrouve'],
		jrenc=(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
		jliste=(typeof lbl.liste!="undefined"?lbl.liste:'liste');
	a.elements[jrenc].value=jliste;
	a.submit();
}
function f_quickTrouve(){
	var a=document.forms['formMonAccueil'],
		jrenc=(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
		jqsearch=(typeof lbl.qsearch!="undefined"?lbl.qsearch:'qsearch');
	a.elements[jrenc].value=jqsearch;
	a.submit();
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
	var jrenc=(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
		jcard=(typeof lbl.card!="undefined"?lbl.card:'card'),
		jedit=(typeof lbl.edit!="undefined"?lbl.edit:'edit'),
		jmsg=(typeof lbl.msg!="undefined"?lbl.msg:'msg'),
		jgsearch=(typeof lbl.gsearch!="undefined"?lbl.gsearch:'gsearch'),
		jaccount=(typeof lbl.account!="undefined"?lbl.account:'account');
	if(c){
		jQuery("."+c).parent().children().removeClass("w3-renc-mebt");
		jQuery("."+c).addClass("w3-renc-mebt");
	}
	jQuery(".rencMenuCard").click(function(){
		document.forms['rencMenu'].elements[jrenc].value=jcard;
		document.forms['rencMenu'].submit();
	});
	if(f.edit)jQuery(".rencMenuEdit").click(function(){
		document.forms['rencMenu'].elements[jrenc].value=jedit;
		document.forms['rencMenu'].submit();
	});
	else jQuery(".rencMenuEdit").addClass("menu-item-off");
	if(f.msg)jQuery(".rencMenuMsg").click(function(){
		document.forms['rencMenu'].elements[jrenc].value=jmsg;
		document.forms['rencMenu'].submit();
	});
	else jQuery(".rencMenuMsg").addClass("menu-item-off");
	if(f.search)jQuery(".rencMenuSearch").click(function(){
		document.forms['rencMenu'].elements[jrenc].value=jgsearch;
		document.forms['rencMenu'].submit();
	});
	else jQuery(".rencMenuSearch").addClass("menu-item-off");
	jQuery(".rencMenuAccount").click(function(){
		document.forms['rencMenu'].elements[jrenc].value=jaccount;
		document.forms['rencMenu'].submit();
	});
	jQuery(".rencMenuC1").click(function(){
		document.forms['rencMenu'].elements[jrenc].value='c1';
		document.forms['rencMenu'].submit();
	});
	jQuery(".rencMenuC2").click(function(){
		document.forms['rencMenu'].elements[jrenc].value='c2';
		document.forms['rencMenu'].submit();
	});
}
function f_hideSideMobile(){
	if(document.cookie.indexOf('rencNoSideMobile=')==-1)document.cookie="rencNoSideMobile=yes";
	else document.cookie="rencNoSideMobile=; expires=Thu, 01 Jan 1970 00:00:01 GMT;";
	window.location.reload();
}
/* Ajax */
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
function f_voir_msg(f,g,h){
	jQuery(document).ready(function(){
		jQuery.post(g,{'action':'voirMsg','idmsg':f,'alias':h,'rencTok':rencTok},function(r){
			jQuery('#rencMsg').empty();
			jQuery('#rencMsg').append(r);
		});
	});
}
function f_password(f0,f1,f2,f,g){
	if(f1.length<6)return;
	if(f1!=f2)jQuery('#rencAlertPass').html(rencobjet.nouv_pass_diff).show(0).delay(5000).hide(0);
	else{
		if(document.getElementById('buttonPass')!==null)document.getElementById('buttonPass').style.visibility="hidden";
		var jrenc=(typeof lbl.renc!="undefined"?lbl.renc:'renc'),
			jid=(typeof lbl.id!="undefined"?lbl.id:'id'),
			jpaswd=(typeof lbl.paswd!="undefined"?lbl.paswd:'paswd'),
			p={'action':'testPass','pass':f0,'nouv':f1,'rencTok':rencTok};
			p[jid]=f;
		jQuery.post(g,p,function(r){
			if(r!=0){
				d=document.forms['formPass'];
				d.elements[jrenc].value=jpaswd;
				d.elements[jid].value=f;
				d.submit();
			}
			else{
				jQuery('#rencAlertPass').html(rencobjet.pass_init_faux+r).show(0).delay(5000).hide(0);
				if(document.getElementById('buttonPass')!==null)document.getElementById('buttonPass').style.visibility="visible";
			}
		});
	}
}
function f_fastregMail(g){
	jQuery.post(g,{'action':'fastregMail','rencTok':rencTok},function(r){
		if(r){
			document.cookie="rencfastregMail=yes";
			jQuery('#rencAlertEmail').html(r).show(0).delay(5000).hide(0);
		}
	});
}
function f_dyn_search(f,g,q){
	var m=document.getElementById("dynMemory");
	jQuery.post(g,{'action':'dynSearch','rencTok':rencTok,'var':f,'dyn':dynPag,'quick':q},function(r){
		if(r.length>10){
			jQuery("#dynSearch").before(r);
			var s=sessionStorage.getItem("dynsearch");
			sessionStorage.setItem("dynsearch",(s?s:'')+r);
			dynSearch=1;
			dynPag++;
			if(m)m.value=dynPag;
		}
	});
}
function f_dyn_reload(){ // (NOT AJAX)
	var m=document.getElementById("dynMemory"),s="";
	if(m&&m.value!="0"){
		s=sessionStorage.getItem("dynsearch");
		if(s)jQuery("#dynSearch").before(s);
		dynPag=m.value;
	}
	else sessionStorage.removeItem("dynsearch");
}
//
/* Tchat */
function f_bip(){
	if(typeof rencobjet.bipurl==='undefined'||typeof noBip!=='undefined'&&noBip==1)return false;
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
	else return false;
};
function f_emot(f){
	if(noEmot==1)return f;
	if(typeof f!=='undefined')for(var v=1;v<16;v++){
		var r=rencEmot[v].replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
		var re=new RegExp(r,'g');
		f=f.replace(re,"<img src='"+rencUrl+"/wp-content/plugins/rencontre/images/"+v+".gif' alt='' style='display:inline' />");
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
		jQuery.post(rencobjet.ajaxchat,{'tchat':'tchatveille','fm':rencobjet.mid,'d':rencBasedir,'c':rencTokc},function(r){
			if(r){
				f_tchat_interval('veil',0,[]);
				if(r==s)f_tchat_ok(rencobjet.mid,r,rencobjet.ajaxchat);
				else if(r!=rencobjet.mid&&rencDVeil===false)f_tchat_dem(rencobjet.mid,r);
				else f_tchat(r,s,rencobjet.ajaxchat,2,rencobjet.tchatname);
			}
		});
	});
}
function f_tchat_btn(f,t,g,s){
	jQuery(document).ready(function(){
	if(jQuery("#rencChat").is(":visible"))return;
		jQuery.post(rencobjet.ajaxchat,{'tchat':'tchatbutton','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			if(r)f_tchat(f,t,g,1,s);
			else if(jQuery("#rencAlert").length!=0)jQuery("#rencAlert").html(rencobjet.tchat_indispo).show(0).delay(5000).hide(0);
		});
	});
}
function f_tchat(f,t,g,p,s){ // p=0:chat accepted-1:chatBtn-2:veil
	if(p&&jQuery("#rencChat").is(":visible"))return;
	f_tchat_interval('dveil',0,[]);
	rencName=s;
	jQuery("#rcName").html('('+rencName+')');
	jQuery("#rcClose").click(function(){f_tchat_fin(f,t,g);});
	jQuery("#rcOnOff").click(f_chatOnOff);
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
	f_tchat_interval('veil',0,[]);
	if(p){
		jQuery("#rcContent").empty().append('<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.tchat_attendre+'</div><div class="w3-col s2 m3">&nbsp;</div></div>');
		f_tchat_debut(f,t,g);
	}
	else f_tchat_interval('scrut',1,[f,t,g]);
}
function f_chatOnOff(){
	if(jQuery("#rcContent").is(":visible")){
		jQuery("#rcOnOff").css("background-position","0px 0px");
		jQuery("#rcContent").hide();
		jQuery("#rencCam").addClass("contOff");
	}
	else{
		jQuery("#rcOnOff").css("background-position","0px -24px");
		jQuery("#rcContent").show();
		jQuery("#rencCam").removeClass("contOff");
	}
}
function f_tchat_debut(f,t,g){
	if(rencScrut!==false)return;
	sessionStorage.setItem("chatF"+f+"T"+t,"");
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatdebut','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			f_tchat_interval('scrut',1,[f,t,g]);
		});
	});
}
function f_tchat_scrute(f,t,g){
	jQuery(document).ready(function(){
		if(!jQuery("#rcInput").hasClass('w3-hide')){
			jQuery.post(g,{'tchat':'tchatscrute','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
				if(r=='::'+f+'::')f_tchat_off();
				else if(r){
					if(jQuery("#rcInput").prop('disabled'))f_tchat_on(f,t);
					f_tchat_actualise("",r,f,t);
				}
			});
		}
	});
}
function f_tchat_dem(f,t){
	jQuery("#rcClose").click(function(){f_tchat_fin(f,t,rencobjet.ajaxchat);});
	jQuery("#rcContent").empty().append('<div id="rcDem" class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.demande_tchat+'&nbsp;:&nbsp;</div><div class="w3-col s2 m3">&nbsp;</div></div>');
	sessionStorage.setItem("chatF"+f+"T"+t,"");
	jQuery(document).ready(function(){
		var jid=(typeof lbl.id!="undefined"?lbl.id:'id'),
		p={'action':'miniPortrait2','rencTok':rencTok};
		p[jid]=t;
		jQuery.post(rencobjet.wpajax,p,function(r){
			r=r.split('|');
			rencName=r[0];
			jQuery("#rcContent").append(r[1]);
			jQuery("#rcYes").click(function(){
				f_tchat_ok(f,t,rencobjet.ajaxchat);
			});
			jQuery("#rcNo").click(function(){
				f_tchat_fin(f,t,rencobjet.ajaxchat);
			});
			jQuery("#rencChat").removeClass('w3-hide');
			f_tchat_interval('dveil',1,[]);
		});
	});
}
function f_tchat_dem_veille(s,p){
	if(!s)s='';
	if(p)rencName=p;
	jQuery(document).ready(function(){
		jQuery.post(rencobjet.ajaxchat,{'tchat':'tchatdemveille','fm':rencobjet.mid,'d':rencBasedir,'c':rencTokc},function(r){
			if(r==1){
				f_tchat_interval('dveil',0,[]);
				jQuery("#rencChat").addClass('w3-hide');
				jQuery("#rcContent").empty();
				jQuery("#rcInput").addClass('w3-hide').val('');
				if(rencMoi!=''){
					rencMoi='';
					f_camOff();
				}
				f_tchat_interval('veil',1,[]);
			}
		});
	});
}
function f_tchat_ok(f,t,g){
	f_tchat_interval('dveil',0,[]);
	if(document.getElementById('rencCam')!==null){
		jQuery("#rcBcam").removeClass('w3-hide').click(function(){
			if(rencMoi!=''){
				rencMoi='';f_camOff();
			}
			else webcam(f,t);
		});
	}
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatok','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			f_tchat(f,t,g,0,rencName);
			jQuery("#rcContent").empty();
			jQuery("#rcInput").prop("disabled",false);
			jQuery("#rcEmot").removeClass('w3-hide');
			f_tchat_actualise("","",f,t); // sessionStorage
		});
	});
}
function f_tchat_on(f,t){
	if(document.getElementById('rencCam')!==null){
		jQuery("#rcBcam").removeClass('w3-hide').click(function(){
			if(rencMoi!=''){
				rencMoi='';f_camOff();
			}
			else webcam(f,t);
		});
	}
	jQuery("#rcInput").prop("disabled",false);
	if(jQuery("#rcEmot").length!=0&&jQuery("#rcEmot").hasClass("w3-hide"))jQuery("#rcEmot").removeClass('w3-hide');
	jQuery("#rcContent").append('<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.tchat_dem_ok+'</div><div class="w3-col s2 m3">&nbsp;</div></div>');
}
function f_tchat_off(){
	var u=navigator.userAgent.toLowerCase(),sm=u.indexOf("android")>-1;
	f_tchat_interval('scrut',0,[]);
	f_tchat_interval('dveil',0,[]);
	f_tchat_interval('veil',0,[]);
	jQuery("#rcInput").prop("disabled",true);
	var a='<div class="w3-row w3-padding-small"><div class="w3-renc-msbs rcYou w3-col s10 m9 w3-card w3-padding">'+rencobjet.ferme_fenetre+'</div><div class="w3-col s2 m3">&nbsp;</div></div>';
	if(sm)jQuery("#rcContent").prepend(a);
	else jQuery("#rcContent").append(a).scrollTop(jQuery("#rcContent")[0].scrollHeight);
	if(!jQuery("#rcBcam").hasClass("w3-hide"))jQuery("#rcBcam").addClass('w3-hide');
	jQuery("#rcBcam").click(function(){
		return true;
	});
	f_camOff();
}
function f_tchat_envoi(f,t,h,g){
	if(h.length>2000)h=h.substring(0,1999)+'...';
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatenvoi','fm':f,'to':t,'msg':h,'d':rencBasedir,'c':rencTokc},function(r){
			f_tchat_actualise(h,r,f,t);
		});
	});
}
function f_tchat_actualise(h,r,f,t){
	var a,s,u=navigator.userAgent.toLowerCase(),sm=u.indexOf("android")>-1,c=document.getElementById('rcContent'),r1;
	h=f_emot(h);
	r=f_emot(r);
	if(!r&&!h){
		s=sessionStorage.getItem("chatF"+f+"T"+t);
		if(s)jQuery("#rcContent").append(s);
	}
	else if(r&&(r.length-r.indexOf("]")-1)!=0){
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
					s=sessionStorage.getItem("chatF"+f+"T"+t);
					if(sm&typeof rencChatSm==='undefined'){
						jQuery("#rcContent").prepend(a);
						s=a+(s?s:'');
					}
					else{
						jQuery("#rcContent").append(a);
						s=(s?s:'')+a;
					}
					sessionStorage.setItem("chatF"+f+"T"+t,s);
					if(fbip)fbip.playclip();
				}
			}
		}
	}
	else if(h){
		a='<div class="w3-row w3-padding-small"><div class="w3-col s2 m3">&nbsp;</div><div class="w3-renc-msbr rcMe w3-col s10 m9 w3-card w3-padding">'+h+'</div></div>';
		s=sessionStorage.getItem("chatF"+f+"T"+t);
		if(sm&typeof rencChatSm==='undefined'){
			jQuery("#rcContent").prepend(a);
			s=a+(s?s:'');
		}
		else{
			jQuery("#rcContent").append(a);
			s=(s?s:'')+a;
		}
		sessionStorage.setItem("chatF"+f+"T"+t,s);
	}
	if(!sm||typeof rencChatSm!=='undefined')jQuery("#rcContent").scrollTop(jQuery("#rcContent")[0].scrollHeight);
}
function f_tchat_fin(f,t,g){
	f_tchat_interval('scrut',0,[]);
	f_tchat_interval('dveil',0,[]);
	f_tchat_interval('veil',0,[]);
	jQuery(document).ready(function(){
		jQuery.post(g,{'tchat':'tchatfin','fm':f,'to':t,'d':rencBasedir,'c':rencTokc},function(r){
			f_tchat_interval('veil',1,[]);
		});
		jQuery("#rencChat").addClass('w3-hide');
		jQuery("#rcContent").empty();
		jQuery("#rcInput").addClass('w3-hide').val('');
	});
	if(rencMoi!=''){
		rencMoi='';
		f_camOff();
	}
	sessionStorage.removeItem("chatF"+f+"T"+t);
}
function f_tchat_interval(f,g,h){
	if(g==1){
		if(f=='veil'&&rencVeil===false){
			if(rencobjet.tchaton==1)rencVeil=setInterval(f_tchat_veille,5111);
			else rencVeil=setInterval(f_tchat_off_session,21111);
		}
		else if(f=='dveil'&&rencDVeil===false)rencDVeil=setInterval(f_tchat_dem_veille,5111);
		else if(f=='scrut'&&rencScrut===false)rencScrut=setInterval(function(){f_tchat_scrute(h[0],h[1],h[2])},2023);
		else if(f=='cam1'&&rencSetiCam1===false)rencSetiCam1=setInterval(saveCam,790);
		else if(f=='cam2'&&rencSetiCam2===false)rencSetiCam2=setInterval(stream_cam,890); // +100ms
	}
	else{
		if(f=='veil'&&rencVeil!==false){
			clearInterval(rencVeil);
			rencVeil=false;
		}
		else if(f=='dveil'&&rencDVeil!==false){
			clearInterval(rencDVeil);
			rencDVeil=false;
		}
		else if(f=='scrut'&&rencScrut!==false){
			clearInterval(rencScrut);
			rencScrut=false;
		}
		else if(f=='cam1'&&rencSetiCam1!==false){
			clearInterval(rencSetiCam1);
			rencSetiCam1=false;
		}
		else if(f=='cam2'&&rencSetiCam2!==false){
			clearInterval(rencSetiCam2);
			rencSetiCam2=false;
		}
	}
}
/* Webcam */
function webcam(f,t){
	rencMoi=f+"-"+t;
	rencToi=t+"-"+f;
	var m=document.getElementById('rencCam2'),c=document.getElementById('rencCamCanvas');
	navigator.mediaDevices.getUserMedia(
		{video:{width:{ideal:160},height:{ideal:120},facingMode:'user'},audio:false}
	).then(function(mediaStream){
			rencStream=mediaStream;
			m.srcObject=mediaStream;
			m.onloadedmetadata=function(e){m.play()}
		}
	).catch(function(err){console.log(err.name+" : "+err.message)}
	);
	m.addEventListener('canplay',function(e){
		m.style.visibility="visible";
		m.style.display="";
		m.style.width="160px";
		m.style.height="120px";
		c.setAttribute('width',300);
		c.setAttribute('height',225);
		f_tchat_interval('cam1',1,[]);
	});
	stream_on();
}
function f_camOff(){
	var a=document.getElementById('rencCam'),m=document.getElementById('rencCam2'),t;
	f_tchat_interval('cam1',0,[]);
	f_tchat_interval('cam2',0,[]);
	if(rencStream){
		t=rencStream.getTracks();
		t.forEach(function(r){r.stop();});
	}
	rencStream=false;
	a.innerHTML="";
	a.style.visibility="hidden";
	m.style.bottom="10px";
	m.style.visibility="hidden";
}
function saveCam(){
	if(rencSave==1&&rencSetiCam1!==false){
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
	if(rencSetiCam2===false)return;
	document.getElementById('rencCamImg').src=rencBaseurl+'/tchat/cam'+rencToi+'.jpg?'+new Date().getTime();
}
function stream_on(){
	var a=document.getElementById('rencCam'),b=document.createElement("img"),m=document.getElementById('rencCam2');
	b.id="rencCamImg";
	b.src="";
	a.appendChild(b);
	a.style.visibility="visible";
	m.style.bottom="245px";
	f_tchat_interval('cam2',1,[]);
}
function f_dynamicWidth(f,s){
	if(typeof f==='undefined')f=200;
	if(typeof s==='undefined')s=168;
	if(window.matchMedia("(max-width:600px)").matches)f=s;
	var a=jQuery(".rencMyHome").width(),c=Math.max(Math.floor(a/(f+8)),1);
	jQuery(".rencMiniPortrait").width(Math.floor(a/c)-16);
}
function f_modalWarn(f){
	var a=document.getElementById('modalWarn'),b=document.getElementById('modalWarnContent'),c=4000;
	if(f.length<4||a==null||b==null)return;
	if(typeof rencmodaltimeout!=='undefined')c=rencmodaltimeout;
	b.innerHTML=f;a.style.display='block';
	window.setTimeout(function(){b.innerHTML='';a.style.display='none';},c);
}
//
if(document.getElementById("infoChange")!==null)window.setTimeout(function(){jQuery("#infoChange").remove()},((typeof rencInfochange!=='undefined')?rencInfochange:5000));
jQuery(document).ready(function(){
	if(typeof rencobjet.ajaxchat!=='undefined')f_tchat_interval('veil',1,[]);
});

/*
* Rencontre
*/
var b6=0,b0='',csv=0,csv1=0,rencToka;
jQuery(document).ready(function(){
	rencToka=(document.getElementById("rencToka")?document.getElementById("rencToka").dataset.toka:'');
});
// ****************************************
// ONGLETS GENERAL
// ****************************************
function f_exportCsv(){
	document.getElementById("waitCsv").style.display="inline";
	jQuery(document).ready(function(){
		jQuery.post('admin-ajax.php',{
				'action':'exportCsv',
				'activ':document.getElementById("selectCsv").options[document.getElementById("selectCsv").selectedIndex].value,
				'photo':(document.getElementById("csvPhoto").checked?1:0),
				'ad':(document.getElementById("csvAd").checked?1:0),
				'rencToka':rencToka
			},function(r){
			a=document.getElementById("rencCsv");
			document.getElementById("waitCsv").style.display="none";
			a.style.display="inline";
			document.getElementById("photoCsv").style.display="block";
			a.href='../wp-content/uploads/tmp/'+r.substring(0,r.length-1)+'export_rencontre.csv';
		});
	});
}
function startUpload(){
	document.getElementById('impCsv1').style.display='none';
	document.getElementById('impCsv2').style.display='none';
	document.getElementById('impCsv3').style.display='none';
	document.getElementById('impCsv4').style.display='none';
	document.getElementById('impCsv5').style.display='none';
	document.getElementById('impCsv7').style.display='none';
	if(document.getElementById("fileCsv").files.length!=0){
		document.getElementById('loadingCsv').style.display='inline';
		return true;
	}
}
function finUpload(s){
	var result='';
	document.getElementById('loadingCsv').style.display='none';
	if(s==0)document.getElementById('impCsv2').style.display='inline';
	else{
		document.getElementById('impCsv1').style.display='inline';
		f_importCsv(3);
	}
	return true;
}
function f_importCsv(f){
	if(f){
		jQuery(document).ready(function(){
			document.getElementById('loadingCsv').style.display='inline';
			jQuery.post('admin-ajax.php',{'action':'importCsv','cas':f,'rencToka':rencToka},function(r){
				if(f==3){
					if(r!=0){
						document.getElementById('impCsv1').innerHTML+=r.substring(0,r.length-1);
						document.getElementById('impCsv3').style.display='inline';
						f_importCsv(2);
					}
					else{
						document.getElementById('impCsv2').style.display='inline';
						f_importCsv(0);
					}
				}
				else{
					if(f==2){
						csv=r.substring(0,r.length-1);
						document.getElementById('impCsv4').style.display='inline';
						document.getElementById('impCsv5').style.display='inline';
					}
					if(r!=0){
						csv1=parseInt(csv1)+parseInt(r.substring(0,r.length-1));
						if(f==2)csv1=0;
						if(csv=='999999')document.getElementById('impCsv6').innerHTML=csv1;
						else document.getElementById('impCsv6').innerHTML=csv1+' / '+csv;
						f_importCsv(1);
					}
					else f_importCsv(0);
				}
			});
		});
	}
	else{
		document.getElementById('loadingCsv').style.display='none';
		document.getElementById('impCsv5').style.display='none';
		document.getElementById('impCsv6').innerHTML='';
		document.getElementById('impCsv7').style.display='inline';
	}
}
function f_regeneratePhotos(f){
	if(f==1||confirm(rencobjet.confirmer)){
		jQuery.post('admin-ajax.php',{'action':'regeneratePhotos','cas':f,'rencToka':rencToka},function(r){
			if(r.substring(0,1)=='!')jQuery("#infoePhotos").html(r.substring(1,r.length-1));
			else if(r.substring(0,1)=='>'){
				jQuery("#infoPhotos").html(r.substring(1,r.length-1))
				f_regeneratePhotos(1);
			}
			else jQuery("#infoePhotos").html('End');
		});
	}
}
function f_cleanupPhotos(f){
	if(f!=0||confirm(rencobjet.confirmer)){
		jQuery.post('admin-ajax.php',{'action':'cleanupPhotos','nb':f},function(r){
			if(r.substring(0,1)=='!')jQuery("#infoePhotos").html(r.substring(1,r.length-1));
			else if(r.substring(0,1)=='>'){
				jQuery("#infoPhotos").html(r.substring(1,r.length-1))
				f_regeneratePhotos(f);
			}
			else jQuery("#infoePhotos").html('End');
		});
	}
}
// ****************************************
// ONGLET PROFIL
// ****************************************
function f_edit(f,a2,a3,a4,a5,g){
	// a2 : ID
	// a3 : 'c_categ', 'c_label', 't_valeur'
	// a4 on submit : c_categ, c_label, (valeur concernée du JSON t_valeur pour select)('' pour checkbox) Format : fr=valeur&en=...& ***urlencode***
	// a5 : langues separees par & : fr&en - '', type (1 a 6)
	// a6 on submit (type)
	// g : genre (only edit) : men=1&girl=1&,
	b6=0; var a,a6,b=0,e,m,n,n0,n1;
	if(a3=='t_valeur'&&a5==0){
		a=f.parentNode.parentNode.getElementsByTagName('DIV');
		for(v=0;v<a.length;++v){if(a[v].className=='valeur')++b;if(a[v]==f.parentNode)v=a.length+1;}
		a5=b; // indice de la valeur concernée (select & checkbox)
	}
	if(document.contains(document.getElementById("editProfil")))document.getElementById("editProfil").remove();
	jQuery(f).parent().parent().prepend('<div id="editProfil"><div style="text-align:center;font-weight:bold;font-size:1.3em;padding:5px;">'+rencobjet.mod_valeur+'</div></div>');
	e=document.getElementById("editProfil");
	e.style.position="relative";
	e.style.padding="5px";
	e.style.border="1px solid #aaa";
	e.style.backgroundColor="#ddd";
	e.style.maxWidth="600px";
	e.style.color="#111";
	m=document.createElement("span");
	m.style.position="absolute";
	m.style.top="2px";
	m.style.right="8px";
	m.style.fontWeight="bold";
	m.style.cursor="pointer";
	m.innerHTML="X";
	m.onclick=function(){document.getElementById("editProfil").remove();}
	e.appendChild(m);
	// left side : name
	m=document.createElement("div");
	m.style.width="55%";
	m.style.float="left";
	b0=decodeURIComponent((a4+'').replace(/\+/g, '%20'));
	while(b0.indexOf('|#39;')!=-1){
		a=b0.indexOf('|#39;');
		b0=b0.substr(0,a)+"'"+b0.substr(a+5);
	}
	a6=b0.split("&");
	for(v=0;v<a6.length-1;++v){
		n=document.createElement("div");
		n0=document.createElement("label");
		n0.style.display="inline-block"
		if(a5=='ns'){
			n0.style.width="80px";
			n0.style.textAlign="right";
			n0.innerHTML=rencobjet.numselect[v];
		}
		else{
			n0.style.width="20px";
			n0.innerHTML=a6[v].substring(0,a6[v].search("="));
		}
		n1=document.createElement("input");
		n1.id="profilLabel"+v;
		n1.setAttribute('type','text');
		if(a5=='ns'){
			n1.setAttribute('size','8');
			n1.setAttribute('value',a6[v]);
			n1.style.marginLeft='10px';
		}
		else{
			n1.setAttribute('size','25');
			n1.setAttribute('name',a6[v].substring(0,a6[v].search("=")));
			n1.setAttribute('value',a6[v].substring(a6[v].search("=")+1));
		}
		n.appendChild(n0);n.appendChild(n1);
		m.appendChild(n);
	}
	e.appendChild(m);
	// right side : type & save
	m=document.createElement("div");
	m.style.width="35%";
	m.style.float="left";
	if(a3=='c_label'){
		b6=1;
		n=document.createElement("select");
		n.id="profilSelect";
		n.setAttribute('name','b6');
		o='<option value="1" '+((a5==1)?'selected':'')+'>TEXT</option>';
		o+='<option value="2" '+((a5==2)?'selected':'')+'>TEXTAREA</option>';
		o+='<option value="3" '+((a5==3)?'selected':'')+'>SELECT</option>';
		o+='<option value="4" '+((a5==4)?'selected':'')+'>CHECKBOX</option>';
		o+='<option value="5" '+((a5==5)?'selected':'')+'>SELECT min-max</option>';
		o+='<option value="6" '+((a5==6)?'selected':'')+'>DATE</option>';
		n.innerHTML=o;
		m.appendChild(n);
	}
	if(a3!='t_valeur'){
		n=document.createElement("div");
		n.id="profilGenre";
		a6=g.split("&");
		for(v=0;v<a6.length-1;++v){
			n0=document.createElement("label");
			n0.innerHTML=a6[v].substring(0,a6[v].search("="));
			n1=document.createElement("input");
			n1.id="profilLabel"+v;
			n1.setAttribute('type','checkbox');
			n1.setAttribute('name','genre'+v);
			if(a6[v].substring(a6[v].search("=")+1)==1)n1.checked=true;
			n.appendChild(n0);n.appendChild(n1);
			m.appendChild(n);
		}
	}
	//
	n=document.createElement("div");
	n0=document.createElement("input");
	n0.setAttribute('type','button');
	n0.setAttribute('value',rencobjet.sauvegarde);
	n0.setAttribute('onClick','f_Submit("edit","'+a2+'","'+a3+'","'+a5+'");return false;');
	n.appendChild(n0);
	m.appendChild(n);
	e.appendChild(m);
	m=document.createElement("div");
	m.style.clear="both";
	e.appendChild(m);
}
function f_plus(f,a2,a3,a4,a5){
	// a2 : ID
	// a3 : 'c_categ', 'c_label', 't_valeur'
	// a4 on submit
	// a5 : langues separees par & : fr&en
	b6=0;b0=a5;var a,a6,e,m,n;
	if(a3=='c_categ')a=rencobjet.categ;
	else if(a3=='c_label')a=rencobjet.valeur;
	else if(a3=='t_valeur')a=rencobjet.nouvelit;
	if(document.contains(document.getElementById("editProfil")))document.getElementById("editProfil").remove();
	if(a3=='t_valeur')jQuery(f).parent().parent().prepend('<div id="editProfil"><div style="text-align:center;font-weight:bold;font-size:1.3em;padding:5px;">'+a+'</div></div>');
	else jQuery(f).parent().prepend('<div id="editProfil"><div style="text-align:center;font-weight:bold;font-size:1.3em;padding:5px;">'+a+'</div></div>');
	e=document.getElementById("editProfil");
	e.style.position="relative";
	e.style.padding="5px";
	e.style.border="1px solid #aaa";
	e.style.backgroundColor="#ddd";
	e.style.maxWidth="600px";
	e.style.color="#111";
	m=document.createElement("span");
	m.style.position="absolute";
	m.style.top="2px";
	m.style.right="8px";
	m.style.fontWeight="bold";
	m.style.cursor="pointer";
	m.innerHTML="X";
	m.onclick=function(){document.getElementById("editProfil").remove();}
	e.appendChild(m);
	// left side : name
	m=document.createElement("div");
	m.style.width="55%";
	m.style.float="left";
	a6=a5.split("&");
	for(v=0;v<a6.length-1;++v){
		n=document.createElement("div");
		n0=document.createElement("label");
		n0.style.display="inline-block"
		n0.style.width="20px";
		n0.innerHTML=a6[v].substring(0,2);
		n1=document.createElement("input");
		n1.id="profilLabel"+v;
		n1.setAttribute('type','text');
		n1.setAttribute('name',a6[v].substring(0,2));
		n1.setAttribute('size','25');
		n.appendChild(n0);n.appendChild(n1);
		m.appendChild(n);
	}
	e.appendChild(m);
	// right side : type & save
	m=document.createElement("div");
	m.style.width="35%";
	m.style.float="left";
	n=document.createElement("div");
	n0=document.createElement("input");
	n0.setAttribute('type','button');
	n0.setAttribute('value',rencobjet.ajoute);
	n0.setAttribute('onClick','f_Submit("plus","'+a2+'","'+a3+'","'+a5+'");return false;');
	n.appendChild(n0);
	m.appendChild(n);
	e.appendChild(m);
	m=document.createElement("div");
	m.style.clear="both";
	e.appendChild(m);
}
function f_Submit(a1,a2,a3,a5){
	var a='',b,c=0,a4,a6=0,g=',',h='',t=new Array();
	if(a5=='ns'){
		b=new Array();
		for(v=0;v<4;++v){b[v]=document.getElementById("profilLabel"+v).value;}
		b[0]=parseFloat(b[0])?parseFloat(b[0]):0;
		b[2]=(parseFloat(b[2])>0)?parseFloat(b[2]):1;
		b[1]=(parseFloat(b[1])>=(b[0]+b[2]))?parseFloat(b[1]):b[0]+b[2];
		b[3]=b[3].replace(/[^a-z0-9 /_-]/gi,'');
		a=b[0]+'&'+b[1]+'&'+b[2]+'&'+b[3]+'&';
	}
	else if(a1=='edit'){
		a=b0.substring(0,b0.search('=')+1)+document.getElementById('profilLabel0').value+'&';
		t[b0.substring(0,2)]=document.getElementsByName(b0.substring(0,b0.search('=')))[0].value;
		while(b0.substring(b0.search('&')+1).length>1){
			b0=b0.substring(b0.search('&')+1);
			a+=b0.substring(0,b0.search('=')+1)+document.getElementsByName(b0.substring(0,b0.search('=')))[0].value+'&';
			t[b0.substring(0,2)]=document.getElementsByName(b0.substring(0,b0.search('=')))[0].value;
		}
		jQuery('#profilGenre').children('input').each(function(){
			h+=jQuery(this).prev('label').text();
			if(this.checked){
				g+=this.name.substr(5)+',';
				h+='=1&';
			}
			else{
				h+='=0&';
			}
		});
	}
	else if(a1=='plus'){
		a=b0.substring(0,2)+'='+document.getElementById('profilLabel0').value+'&';
		while(b0.substring(b0.search('&')+1).length>1){
			b0=b0.substring(b0.search('&')+1);
			a+=b0.substr(0,2)+'='+document.getElementsByName(b0.substr(0,2))[0].value+'&';
		}
	}
	a=a.replace(/"/g,'');
	a4=encodeURIComponent(a);
	if(b6==1)a6=document.getElementById('profilSelect').value; // (edit c_label)
	jQuery.post('admin-ajax.php',{'action':'profilA','a1':a1,'a2':a2,'a3':a3,'a4':a4,'a5':a5,'a6':a6,'g':g,'rencToka':rencToka},function(r){
		if(a3=='c_categ'||r.substr(0,6)=='reload')location.reload(true);
		else{
			document.getElementById('editProfil').remove();
			if(a1=='edit'&&a3=='c_label'){
				jQuery('#rencLabel'+a2).find('span').each(function(){;
					this.innerHTML=(c!=0?this.className.substring(9)+' : ':'')+t[this.className.substring(9)];
					++c;
				});
				jQuery('#rencLabel'+a2+' .rencEdit').attr('onclick','f_edit(this,'+a2+',"c_label","'+a4+'",'+a6+',"'+h+'")');
			}
			else if(a1=='edit'&&a5=='ns'){ // t_valeur in case 5 (numeric select)
				jQuery('#rencValeur'+a2+' .valeur .rencEdit').attr('onclick','f_edit(this,'+a2+',"t_valeur","'+a4+'","ns",0)');
				a=a.split('&');
				jQuery('#rencValeur'+a2).find('.valeur').children('span').each(function(){
					if(c==0)this.innerHTML='['+a[0]+' ; '+a[1]+']';
					else if(c==1)this.innerHTML=' : '+a[2];
					else if(c==2)this.innerHTML=' : '+a[3];
					++c;
				});
			}
			else if(a1=='edit'&&a3=='t_valeur'){
				jQuery('#rencValeur'+a2+' .valeur:eq('+(a5-1)+')').children('span').each(function(){
					this.innerHTML=(c!=0?this.className.substring(10)+' : ':'')+t[this.className.substring(10)];
					++c;
				});
				jQuery('#rencValeur'+a2+' .valeur:eq('+(a5-1)+') .rencEdit').attr('onclick','f_edit(this,'+a2+',"t_valeur","'+a4+'",0,0)');
			}
			else if(a1=='plus'&&a3=='c_label'){ // r : "newID|a_man=1&a_woman=1&"
				c=r.split('|');
				t=a4.split('%26');
				a='<div class="label"><div class="rencLabel" id="rencLabel'+c[0]+'">';
				a+='<a href="javascript:void(0)" class="rencUp" onClick="f_rencUp('+c[0]+',\'c_label\',this);"></a>';
				a+='<a href="javascript:void(0)" class="rencDown" onClick="f_rencDown('+c[0]+',\'c_label\',this);"></a>';
				a+='<a href="javascript:void(0)" class="rencEdit" onClick="f_edit(this,'+c[0]+',\'c_label\',\''+a4+'\',1,\''+c[1].substr(0,c[1].length-1)+'\');"></a>';
				a+='<a href="javascript:void(0)" class="rencSupp" onClick="f_supp('+c[0]+',\'c_label\',this);"></a>';
				jQuery.each(t,function(k,v){
					v=v.split('%3D');
					if(typeof v[1]!=='undefined'){
						if(k==0)a+='<span class="rencLabel'+v[0]+'">'+decodeURI(v[1])+'</span><br />';
						else a+='<span style="margin:0 0 0 37px;color:#777;" class="rencLabel'+v[0]+'">'+v[0]+' : '+decodeURI(v[1])+'</span><br />';
					}
				});
				a+='</div><div style="height:5px;"></div><div class="rencValeur rencType">(TEXT)</div><br style="clear:left;"/></div>';
				jQuery('#rencLabel'+a2).parent().parent().append(a);
			}
			else if(a1=='plus'&&a3=='t_valeur'){
				jQuery('#rencValeur'+a2).children('div').each(function(){
					a=this.id.split('-');
					if(typeof a[1]!=='undefined'&&a[1]>c)c=a[1]; // search max
				});
				t=a4.split('%26');
				a='<div class="valeur"><br />';
				a+='<a href="javascript:void(0)" class="rencUp" onClick="f_rencUp('+a2+',\'t_valeur\',this);"></a>';
				a+='<a href="javascript:void(0)" class="rencDown" onClick="f_rencDown('+a2+',\'t_valeur\',this);"></a>';
				a+='<a href="javascript:void(0)" class="rencEdit" onClick="f_edit(this,'+a2+',\'t_valeur\',\''+a4+'\',this,0);"></a>';
				a+='<a href="javascript:void(0)" class="rencSupp" onClick="f_supp('+a2+',\'t_valeur\',this);"></a>';
				jQuery.each(t,function(k,v){
					v=v.split('%3D');
					if(typeof v[1]!=='undefined'){
						if(k==0)a+='<span class="rencValeur'+v[0]+'">'+decodeURI(v[1])+'</span><br />';
						else a+='<span style="margin:0 0 0 37px;color:#777;" class="rencValeur'+v[0]+'">'+v[0]+' : '+decodeURI(v[1])+'</span><br />';
					}
				});
				a+='</div>';
				jQuery('#rencValeur'+a2).append(a);
			}
		}
	});
}
function f_supp(a2,a3,t){
	if(confirm(rencobjet.confirmer)){
		var a,b=0;
		if(a3=='t_valeur'){
			a=t.parentNode.parentNode.getElementsByTagName('DIV');
			for(v=0;v<a.length;++v){
				if(a[v].className=='valeur')++b;
				if(a[v]==t.parentNode)v=a.length+1;
			}
		}
		jQuery(document).ready(function(){
			jQuery.post('admin-ajax.php',{'action':'updown','move':'supp','id':a2,'typ':a3,'id2':b,'rencToka':rencToka},function(r){
				if(r.substring(0,2)=='OK'){
					if(a3=='t_valeur')jQuery(t.parentNode).remove();
					else jQuery(t.parentNode.parentNode).remove();
				}
			});
		});
	}
}
function f_rencUp(a2,a3,t){var a,b=0;
	t.style.display='none';
	if(a3=='t_valeur'){
		a=t.parentNode.parentNode.getElementsByTagName('DIV');
		for(v=0;v<a.length;++v){
			if(a[v].className=='valeur')++b;
			if(a[v]==t.parentNode)v=a.length+1;
		}
	}
	jQuery(document).ready(function(){
		jQuery.post('admin-ajax.php',{'action':'updown','move':'up','id':a2,'typ':a3,'id2':b,'rencToka':rencToka},function(r){
			if(r.substring(0,2)=='OK'){
				if(a3=='c_categ'){a=t.parentNode.parentNode;jQuery(a).insertBefore(jQuery(a).prev(".categ"));}
				else if(a3=='c_label'){a=t.parentNode.parentNode;jQuery(a).insertBefore(jQuery(a).prev(".label"));}
				else if(a3=='t_valeur'){a=t.parentNode;jQuery(a).insertBefore(jQuery(a).prev(".valeur"));}
			}
			t.style.display='inline-block';
		});
	});
}
function f_rencDown(a2,a3,t){var a,b=0;
	t.style.display='none';
	if(a3=='t_valeur'){
		a=t.parentNode.parentNode.getElementsByTagName('DIV');
		for(v=0;v<a.length;++v){if(a[v].className=='valeur')++b;if(a[v]==t.parentNode)v=a.length+1;}
	}
	jQuery(document).ready(function(){
		jQuery.post('admin-ajax.php',{'action':'updown','move':'down','id':a2,'typ':a3,'id2':b,'rencToka':rencToka},function(r){
			if(r.substring(0,2)=='OK'){
				if(a3=='c_categ'){a=t.parentNode.parentNode;jQuery(a).insertAfter(jQuery(a).next(".categ"));}
				else if(a3=='c_label'){a=t.parentNode.parentNode;jQuery(a).insertAfter(jQuery(a).next(".label"));}
				else if(a3=='t_valeur'){a=t.parentNode;jQuery(a).insertAfter(jQuery(a).next(".valeur"));}
			}
			t.style.display='inline-block';
		});
	});
}
function f_profil_hideshow(f,id){
	var b=(f==0?'rencLabelHide':'rencValeurHide')+id,a=document.getElementById(b);
	if(a.style.display=='block')a.style.display='none';
	else a.style.display='block';
}
function f_synchronise(){
	document.forms["menu_profil"].elements["a1"].value="synchro";
	document.forms["menu_profil"].elements["a6"].value="1";
	document.forms["menu_profil"].submit();}
function f_langplus(){
	document.forms["menu_profil"].elements["a1"].value="langplus";
	f=document.getElementsByName("langplus")[0].value;
	document.forms["menu_profil"].elements["a4"].value=f;
	if(/^[a-zA-Z]+$/.test(f))document.forms["menu_profil"].submit();
}
function f_langsupp(){
	document.forms["menu_profil"].elements["a1"].value="langsupp";
	f=document.getElementById("langsupp");
	document.forms["menu_profil"].elements["a4"].value=f.options[f.selectedIndex].value;
	if (confirm(rencobjet.supprimer+' '+f.options[f.selectedIndex].value+' ?'))document.forms["menu_profil"].submit();
}
// ****************************************
// ONGLET REGION
// ****************************************
function f_liste_edit(a2,a3,a4){
	b6 = 0;
	document.forms["menu_liste"].elements["a1"].value="edit";
	document.forms["menu_liste"].elements["a2"].value=a2; // iso / id
	document.forms["menu_liste"].elements["a3"].value=a3; // 'p', 'r'
		// a4 on submit -  c_categ, c_label, (valeur concernée du JSON t_valeur pour select)('' pour checkbox) Format : fr=valeur&en=...& ***urlencode***
		// a6 on submit (type)
	b0=decodeURIComponent((a4+'').replace(/\+/g, '%20'));
	a6=b0.substring(b0.search("=")+1,b0.search("&")); // valeur pour la langue principale
	e=document.getElementById("edit_liste");e.style.padding="25px";e.style.backgroundColor="green";e.innerHTML="";
	t=document.createElement("label");t.style.color="#000";t.style.fontSize="18px";t.style.marginRight="10px";t.innerHTML=rencobjet.mod_valeur+" : ";
	i=document.createElement("input");i.setAttribute('type','text');i.setAttribute('size','40');i.setAttribute('value',a6);i.style.marginRight="15px";
	s=document.createElement("input");s.setAttribute('type','button');s.setAttribute('value',rencobjet.sauvegarde);s.setAttribute('onClick','f_liste_Submit()'); // Appel fonction on submit
	e.appendChild(t);e.appendChild(i);e.appendChild(s);
	d=document.createElement("div");d.style.marginLeft="170px";
	a6=b0;
	if(a3=='p'){
		a6=a6.substring(a6.search("&")+1); // premiere langue
		i.value=a6.substring(a6.search("=")+1,a6.search("&"));
		while(a6.substring(a6.search("&")+1).length>1){
			a6=a6.substring(a6.search("&")+1);
			d0=document.createElement("label");d0.style.color="#222";d0.style.fontSize="14px";d0.style.marginRight="5px";d0.style.marginLeft="10px";d0.innerHTML=a6.substring(0,a6.search("="))+ ' :';
			d1=document.createElement("input");d1.setAttribute('type','text');d1.setAttribute('name',a6.substring(0,a6.search("=")));d1.setAttribute('size','25');d1.setAttribute('value',a6.substring(a6.search("=")+1,a6.search("&")));d1.style.marginRight="15px";
			d.appendChild(d0);d.appendChild(d1);d.appendChild(document.createElement("br"));
		}
	}
	else if(a3=='r')i.value=a4;
	e.appendChild(d);
	window.scrollTo(0,0);
}
function f_liste_supp(a2,a3,a4){
	document.forms["menu_liste"].elements["a1"].value="supp";
	document.forms["menu_liste"].elements["a2"].value=a2;
	document.forms["menu_liste"].elements["a3"].value=a3;
	document.forms["menu_liste"].elements["a4"].value=a4;
	document.forms["menu_liste"].submit();
}
function f_liste_plus(a2,a3,a4,a5){
	b6=0;b0=a5;
	document.forms["menu_liste"].elements["a1"].value="plus";
	document.forms["menu_liste"].elements["a2"].value=a2; // ID (p) ou iso (r)
	document.forms["menu_liste"].elements["a3"].value=a3; // 'p', 'r'
		// a4 on submit
	document.forms["menu_liste"].elements["a5"].value=a5; // langues separees par & : fr&en
	e=document.getElementById("edit_liste");e.innerHTML="";e.style.padding="25px 10px";e.style.backgroundColor="green";
	t=document.createElement("label");t.style.color="#000";t.style.fontSize="18px";t.style.marginRight="5px";
	if (a3=='p') t.innerHTML=rencobjet.pays+" : ";else if (a3=='r') t.innerHTML=rencobjet.region+" : ";
	i=document.createElement("input");i.setAttribute('type','text');i.setAttribute('size','20');i.setAttribute('value','');i.style.marginRight="15px";
	e.appendChild(t);e.appendChild(i);
	if (a3=='p'){
		u=document.createElement("label");u.style.color="#000";u.style.fontSize="18px";u.style.marginRight="5px";u.innerHTML=rencobjet.code_iso+" : ";u.style.marginLeft="15px";
		j=document.createElement("input");j.setAttribute('type','text');j.setAttribute('size','2');j.setAttribute('value','');j.maxLength=2;j.id='iiso';j.setAttribute('onkeyup','f_iso()');
		e.appendChild(u);e.appendChild(j);
		m=document.createElement("label");m.style.color="#000";m.style.fontSize="18px";m.style.marginRight="5px";m.innerHTML=rencobjet.fichier_im+" : ";m.style.marginLeft="15px";
		n=document.createElement("select");n.id='sdrap';
		jQuery(document).ready(function(){
			jQuery.post('admin-ajax.php',{'action':'drap','rencToka':rencToka},function(r){
				r=r.substring(0,r.length-1);
				n.innerHTML=r;
			});
		});
		e.appendChild(m);e.appendChild(n);
	}
	s=document.createElement("input");s.setAttribute('type','button');s.setAttribute('value',rencobjet.ajoute);s.setAttribute('onClick','f_liste_Submit()');s.id='siso';s.style.marginLeft="15px";
	e.appendChild(s);
	d=document.createElement("div");d.style.marginLeft="20px";
	a6=a5;
	if (a3=='p'){
		while(a6.substring(a6.search("&")+1).length>1){
			a6=a6.substring(a6.search("&")+1);
			d0=document.createElement("label");d0.style.color="#222";d0.style.fontSize="14px";d0.style.marginRight="5px";d0.style.marginLeft="10px";d0.innerHTML=a6.substr(0,2)+ ' :';
			d1=document.createElement("input");d1.setAttribute('type','text');d1.setAttribute('name',a6.substr(0,2));d1.setAttribute('size','20');d1.setAttribute('value','');d1.style.marginRight="15px";
			d.appendChild(d0);d.appendChild(d1);d.appendChild(document.createElement("br"));
		}
	}
	e.appendChild(d);
	window.scrollTo(0,0);
}
function f_liste_Submit(){
	if(document.forms["menu_liste"].elements["a3"].value=="p"){
		if(document.forms["menu_liste"].elements["a1"].value=="edit"){
			b0=b0.substring(b0.search("&")+1);
			a6=b0.substring(0,b0.search("=")+1)+i.value+"&";
			while(b0.substring(b0.search("&")+1).length>1){
				b0=b0.substring(b0.search("&")+1);
				a6+=b0.substring(0,b0.search("=")+1)+document.getElementsByName(b0.substring(0,b0.search("=")))[0].value+"&";
			}
		}
		else if(document.forms["menu_liste"].elements["a1"].value=="plus"){
			document.forms["menu_liste"].elements["a5"].value=j.value.toUpperCase();
			document.forms["menu_liste"].elements["a6"].value=document.getElementById("sdrap").options[document.getElementById("sdrap").selectedIndex].value; // fichier image
			a6=b0.substring(0,2)+"="+i.value+"&";
			while(b0.substring(b0.search("&")+1).length>1){
				b0=b0.substring(b0.search("&")+1);
				a6+=b0.substr(0,2)+"="+document.getElementsByName(b0.substr(0,2))[0].value+"&";
			}
		}
	}
	else if(document.forms["menu_liste"].elements["a3"].value=="r")a6=i.value;
	a6=a6.replace(/"/g,"");
	document.forms["menu_liste"].elements["a4"].value=encodeURIComponent(a6.toLowerCase());
	document.forms["menu_liste"].submit();
}
function f_iso(){
	g=document.getElementById("iiso");
	h=document.getElementById("siso");
	f=g.value.toUpperCase();
	if(f.length>1){
		jQuery(document).ready(function(){
			jQuery.post('admin-ajax.php',{'action':'iso','iso':f,'rencToka':rencToka},function(r){
				r=r.substring(0,r.length-1);
				if(r){g.style.backgroundColor="#ffffff";h.style.visibility="visible";}
				else{g.style.backgroundColor="red";h.style.visibility="hidden";}
			});
		});
	}
	else{g.style.backgroundColor="#ffffff";h.style.visibility="visible";}
}
function f_liste_langplus(){
	document.forms["menu_liste"].elements["a1"].value="langplus";
	f=document.getElementsByName("langplus")[0].value;
	document.forms["menu_liste"].elements["a4"].value=f;
	if(/^[a-zA-Z]+$/.test(f))document.forms["menu_liste"].submit();
}
function f_liste_langsupp(){
	document.forms["menu_liste"].elements["a1"].value="langsupp";
	f=document.getElementById("langsupp");
	document.forms["menu_liste"].elements["a4"].value=f.options[f.selectedIndex].value;
	if (confirm(rencobjet.supprimer+' '+f.options[f.selectedIndex].value+' ?'))document.forms["menu_liste"].submit();
}
function f_country_hideshow(f,id){
	var b=(f==0?'rencLabelHide':'rencValeurHide')+id,a=document.getElementById(b);
	if(a.style.display=='block')a.style.display='none';
	else a.style.display='block';
}
// ****************************************
// ONGLETS MEMBRES & PRISON
// ****************************************
function f_vignette(f,img){
	ff="../wp-content/uploads/portrait/"+Math.floor((f)/10000)+"/"+img+".jpg?r="+Math.random();
	document.getElementById('portraitGrande').src=ff;
}
function f_vignette_change(f,img){
	f_vignette(f,img);
	document.getElementById('changePhoto').innerHTML='';
}
function f_supp_photo(f){
	document.getElementById('changePhoto').innerHTML = rencobjet.supp_photo+'<a href="javascript:void(0)" class="rencSupp" onClick="document.forms[\'portraitChange\'].elements[\'a1\'].value=\'suppImg\';document.forms[\'portraitChange\'].elements[\'a2\'].value=\''+f+'\';document.forms[\'portraitChange\'].submit();" title="'+rencobjet.supp_la_photo+'">';
}
function f_plus_photo(f){
	document.getElementById('changePhoto').innerHTML = '<input type="file" name="plusPhoto" size="18"><br />'+rencobjet.ajouter_photo+'<a href="javascript:void(0)" class="rencPlus" onClick="document.forms[\'portraitChange\'].elements[\'a1\'].value=\'plusImg\';document.forms[\'portraitChange\'].elements[\'a2\'].value=\''+f+'\';document.forms[\'portraitChange\'].submit();" title="'+rencobjet.ajouter_photo+'">';
}
function f_suppAll_photo(){
	document.forms['portraitChange'].elements['a1'].value='suppImgAll';
	document.forms['portraitChange'].submit();
}
function f_sauv_profil(f){
	document.forms['portraitChange'].elements['a1'].value='sauvProfil';
	document.forms['portraitChange'].elements['a2'].value=f;
	document.forms['portraitChange'].submit();
}
function f_onglet(f){
	document.getElementById('portraitTable'+renclast).style.display='none';
	document.getElementById('portraitTable'+f).style.display='table';
	document.getElementById('portraitOnglet'+renclast).style.backgroundColor='transparent';
	document.getElementById('portraitOnglet'+f).style.backgroundColor='#e5d4ac';renclast=f;
}
var renclast=0;
function f_fin(f,g){
	document.forms['listUser'].elements['a1'].value=f;
	document.forms['listUser'].elements['a2'].value=g;
	if(confirm(rencobjet.supp_compte)){
		if(rencVarModer!=0)document.getElementById('rencModerTyp').style.display='block';
		else document.forms['listUser'].submit();
	}
}
function f_block(f,g){
	document.forms['listUser'].elements['a1'].value=f;
	document.forms['listUser'].elements['a2'].value=g;
	document.forms['listUser'].submit();
}
function f_blockMail(f,g){
	document.forms['listUser'].elements['a1'].value=f;
	document.forms['listUser'].elements['a2'].value=g;
	document.forms['listUser'].submit();
}
function f_liberte(f){
	if(confirm(rencobjet.libre_mail)){
		document.forms['listPrison'].elements['a1'].value=f;
		document.forms['listPrison'].submit();
	}
}
function rencStat(){
	if(document.getElementById("rencStat").style.display=='none'){
		jQuery('#rencStat').slideDown(800,function(){
			jQuery.post('admin-ajax.php',{'action':'stat','rencToka':rencToka},function(r){
				now=(new Date()).getTime();
				var d=[],d1=[],e=[],e1=[],a=[],a1=[],h=[],h1=[],k=[],k1=[],data=jQuery.parseJSON(r.substr(0,r.length-1)),j=365,n=0,c=0;
				for(w=0;w<366;++w){e1[w]=0;e[w]=[];d[w]=[];a1[w]=0;a[w]=[];}
				for(w=0;w<=48;++w){h1[w]=0;h[w]=[];}
				for(w=0;w<=8;++w){k1[w]=0;k[w]=[];}
				jQuery.each(data,function(k,v){
					if(v.day>365)n=v.id;
					else{
						if(v.day>3&&v.day<335){ // lissage
							for(w=-2;w<=2;++w){
								e1[v.day+w]+=.2;
								if(v.act==1)a1[v.day+w]+=.2;
							}
						}
						else{
							e1[v.day]++;
							if(v.act==1)a1[v.day]++;
						}
						if(v.h.substr(0,5)!='00:00'){
							h1[(v.h.substr(0,2))*2+Math.floor(parseInt((v.h.substr(3,5)))/30)]++;
							k1[v.s]++;
						}
						if(v.day<j){
							if(j-v.day>1){
								for(w=j-v.day;w>1;--w){
									d[c]=[];
									d[c][0]=v.tme*1000-(w-1)*86400000;
									d[c][1]=0;
									++c;
								}
							}
							d[c]=[];
							d[c][0]=v.tme*1000;
							d[c][1]=(n!=0?Math.floor((v.id-n)/(j-v.day)+.5):0);
							++c;
							j=v.day;
							n=v.id;
						}
					}
				});
				// lissage
				c1=parseInt(c*0.01+.5);
				for(w=c1+1;w<c-c1-1;++w){
					d1[w]=0;
					for(x=-c1;x<=c1;++x){
						d1[w]+=d[w+x][1];
					}
					d1[w]=parseInt(d1[w]/(2*c1+1)+.5);
					if(w>parseInt(2*c1+1))d[w-c1][1]=d1[w-c1];
				}
				for(w=0;w<366;++w){
					e[w][0]=now-w*86400000;
					e[w][1]=parseInt(e1[w]);
					a[w][0]=now-w*86400000;
					a[w][1]=parseInt(a1[w]);
				}
				for(w=0;w<=48;++w){
					h[w][0]=w/2;
					h[w][1]=(w==48?h1[0]:h1[w]);
				}
				for(w=0;w<7;++w){
					k[w][0]=w;
					k[w][1]=k1[w];
				}
				jQuery.plot("#rencStat1",[
						{data:d,label:rencobjet.nb_registered},
						{data:e,label:rencobjet.nb_exist},
						{data:a,label:rencobjet.nb_actif}],{
					xaxis:{
						mode:'time',
						timeformat:"%b<br>%Y",
						tickSize:[1,"month"],
						position:"top",
						color:'#cccccc'
					},
					legend:{position:"nw"}
				});
				jQuery.plot("#rencStat2",[
						{data:h,label:rencobjet.trafic},
						{data:k,bars:{show:true,barWidth:0.6,align:"center"},xaxis:2,yaxis:2,label:rencobjet.trafic_sem}],{
					xaxes:[{
						mode:null,
						tickSize:1,
						position:"top"
					},{
						ticks:[
							[0,rencobjet.dim],
							[1,rencobjet.lun],
							[2,rencobjet.mar],
							[3,rencobjet.mer],
							[4,rencobjet.jeu],
							[5,rencobjet.ven],
							[6,rencobjet.sam]],
						position:"bottom"
					}],
					yaxes:[{ticks:[0],min:0,position:"right",autoscaleMargin:.15},{ticks:[0],min:0,position:"left",autoscaleMargin:.4}],
					legend:{position:"nw"}
				});
				if(typeof rencstatHook==="function")rencstatHook();
			});
		});
	}else jQuery('#rencStat').slideUp(800,function(){
		jQuery('#rencStat1').empty();
		jQuery('#rencStat2').empty();
		if(typeof rencstatHookEmpty==="function")rencstatHookEmpty();
	});
}
function f_bulleOn(f,g){
	if(g.length<5)g='-';
	var a=document.getElementById('bulle'),b=f.getBoundingClientRect();
	a.style.top=(b.top+window.pageYOffset-f.ownerDocument.documentElement.clientTop-60)+"px";
	a.style.left=50+"px";
	g=decodeURIComponent(g)
	g=g.replace(/\+/g,' ');
	a.innerHTML=g;
	a.style.display='block';
}
function f_bulleOff(){
	var a=document.getElementById('bulle');
	a.innerHTML='';
	a.style.display='none';
}
function f_region_select_adm(f,g,x){
	jQuery(document).ready(function(){
		jQuery('#'+x).empty();
		jQuery.post(g,{'action':'regionBDD','pays':f},function(r){
			jQuery('#'+x).append(r);
		});
	});
}
function f_newMember(f){
	var a=f.options[f.selectedIndex].value;
	jQuery.post('admin-ajax.php',{'action':'newMember','id':a,'rencToka':rencToka},function(r){
		location.reload(); 
	});
}
function f_copyIP(f){
	var a=document.createElement("input");
    a.style="position:absolute;left:-1000px;top:-1000px";
    a.value=f;
    document.body.appendChild(a);
    a.select();
    document.execCommand("copy");
    document.body.removeChild(a);
}
// ****************************************
// ONGLETS CUSTOM
// ****************************************
function f_minA(f,x,y,z){
	var c=0,d=document.forms[x][y],e=document.forms[x][z];
	f=parseInt(f);
	for(v=0;v<e.length;v++){
		if(parseInt(d.options[v].value)==f)c=v;
		if(parseInt(e.options[v].value)<f)e.options[v].disabled=true;
		else e.options[v].disabled=false;
	}
	if(e.options[e.selectedIndex]&&f>parseInt(e.options[e.selectedIndex].value))e.selectedIndex=c;
}
function f_maxA(f,x,y,z){
	var c=0,d=document.forms[x][z],e=document.forms[x][y];
	f=parseInt(f);
	for(v=0;v<e.length;v++){
		if(parseInt(d.options[v].value)==f)c=v;
		if(parseInt(e.options[v].value)>f)e.options[v].disabled=true;
		else e.options[v].disabled=false;
	}
	if(e.options[e.selectedIndex]&&f<parseInt(e.options[e.selectedIndex].value))e.selectedIndex=c;
}

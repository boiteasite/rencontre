/* Rencontre libre */
function f_tete_zoom(f,g){f.style.width="141px";f.style.height="108px";f.style.position="absolute";f.style.right="0";f.style.top="0";f.style.position="absolute";f.src=g;}
function f_tete_normal(f,g){f.style.width="60px";f.style.height="60px";f.style.position="relative";f.src=g;}
function f_tete_turn(t,u){var t,u;window.setTimeout(function(){i="tete"+t;a=document.getElementById(i);s=a.src;a.src=s.substring(0,s.length-8)+"libre.jpg";a.style.width="141px";a.style.height="108px";a.style.position="absolute";a.style.right="0";a.style.top="0";a.style.position="absolute";f_tete_return(t,u);},2000);}
function f_tete_return(t,u){if(t!=u){i="tete"+(u);a=document.getElementById(i);s=a.src;a.src=s.substring(0,s.length-9)+"mini.jpg";a.style.width="60px";a.style.height="60px";a.style.position="relative";}t++;u=t-1;if(!jQuery("#tete"+t).length>0){f_tete_turn(0,u);}else f_tete_turn(t,u);}
//
jQuery(window).load(function(){window.setTimeout(f_tete_turn(0,0),2000);});

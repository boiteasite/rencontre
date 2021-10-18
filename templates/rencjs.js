/*
 * Plugin : Rencontre
 * Template : rencjs
 * Last Change : Rencontre 3.6
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/rencjs.js
*/



/* Use this file to override rencontre.js functions.
 * 
 * Respect the format : fct_name_to_override = function(vars) { }
 * Example with "f_dynamicWidth" function, add the code above to this file :
 
	f_dynamicWidth = function(f,s){
		f=250; s=200;
		if(window.matchMedia("(max-width:600px)").matches)f=s;
		var a=jQuery(".rencMyHome").width(),c=Math.max(Math.floor(a/(f+8)),1);
		jQuery(".rencMiniPortrait").width(Math.floor(a/c)-16);
	}

 * 
*/

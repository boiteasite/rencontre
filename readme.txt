=== Rencontre - Dating Site ===
Contributors: sojahu
Donate link: https://www.paypal.me/JacquesMalgrange
Tags: date, dating, meet, meeting, love, chat, webcam, rencontre, match, social, members, friends, messaging
Requires at least: 4.6
Tested up to: 6.4
Requires PHP: 5.5
Stable tag: 3.11.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A free powerful and exhaustive dating plugin with private messaging, webcam chat, search by profile and automatic sending of email. No third party.

== Description ==

This WordPress plugin allows you to create a professional **dating website** with Wordpress. It is simple to install and administer with numerous possibilities.

The features are as follows :

* Login Required to access functionality ;
* Home unconnected with overview of the latest registered members and tiny quick search ;
* **Private messaging** between members ;
* **Extended and customizable profile** (free composition, checkbox, select, numeric select, date event...) ;
* **Advanced Search** including in profile elements by value or value range (date, numeric select) ;
* Image format adapted to retina-ready screens ;
* Private Members **chat with webcam** ;
* Sending smiles and contact requests ;
* Reporting of non-compliant member profiles ;
* Rapid registration ;
* **Sending regular emails to members** in accordance with the quota server ;
* Daily cleaning to maintain the level of performance ;
* **Low resource**, optimized for shared web server ;
* Unlimited number of members ;
* Many adjustable parameters ;
* **Modularity** to fit many projects ;
* Adaptable **templates** based on [W3.css](https://www.w3schools.com/w3css/) framework and [Font Awesome](https://fontawesome.com/) icons;
* Multilingual ;
* Easy **administration with filtering members** ;
* Blacklist by email ;
* IP Localization ;
* Import/Export members in CSV with photos ;
* **Standalone**, not depend on other services or other plugins ;
* GDPR complience.

[**Kit Premium**](https://www.boiteasite.fr/rencontre-premium.html) :

* Sophisticated **payment system** for members with numerous settings, restrictions and promotions - Compatible with WooCommerce gateways ;
* Subscription system by duration or by purchase of points ;
* Settings & access defined by gender ;
* Photos and/or Videos **Private Gallery** ;
* Proximity search on **map** by geolocation ;
* Search by profile elements ;
* Search by **Astrological** affinity - Very powerful ;
* Pictures **moderation** on image wall - Very useful ;
* Blacklist - Registration restrictions by IP country and by email domain ;
* Messages supervision for reported members - Very useful ;
* Insert Google AdSense in different places to **make money** ;
* Google AdWords conversion code for registration.

= Internationalization =

Rencontre is currently translated in :

* English (main language) and WP Translation Team
* French - thanks to me :-)
* Chinese - thanks to Lucien Huang
* Czech - thanks to Libor and WP Translation Team
* Danish - thanks to [C-FR](http://www.C-FR.net/)
* Dutch - thanks to Martin Zaagman and WP Translation Team
* German (front office) - thanks to Stefan Wolfarth
* Hungarian - thanks to FunnelXpert
* Italian - thanks to Gaelle Dozzi and WP Translation Team
* Japanese - thanks to Rorylouis
* Norvegian - thanks to Steffen Madsen and WP Translation Team
* Portuguese - thanks to Patricio Fernandes
* Portuguese Brazil - thanks to Cesar Ramos
* Russian - thanks to Vetal Soft and WP Translation Team
* Spanish - thanks to Sanjay Gandhi
* Swahili - thanks to Kenneth Longo Mlelwa
* Swedish - thanks to WP Translation Team
* Turkish - thanks to Cise Candarli

If you have translated the plugin in your language or want to, please let me know on [Support](https://wordpress.org/support/plugin/rencontre) page.

== Installation ==

= Install and Activate =

1. Unzip the downloaded rencontre zip file
2. Upload the `rencontre` folder and its contents into the `wp-content/plugins/` directory of your WordPress installation
3. Activate Rencontre from Plugins page

= Instructions for use =

If you use the Twenty Seventeen theme (2017), you should watch this video.

[youtube http://www.youtube.com/watch?v=UwrIQWu4Vd8]

Connect to the Dashboard in ADMIN.

**Primo**

In 'Pages', edit or create the page of your choice (Home ?). Add the shortcode `[rencontre]` in your page content.

**Secundo (not required)**

For visitors not connected, you can view thumbnails and small profile of the last registered members :

Add the shortcode `[rencontre_libre]` in your page content. You can add it next to the other one : `[rencontre][rencontre_libre]` or in another page.

See FAQ for differents options.

**Tertio**

You need a WordPress Login/logout/Register link. Select one or more of these possibilities :

1. In Appearance / Menus, in the Rencontre block, select 'Log in' and 'Register' and add to the menu. Save. If you don't see the Rencontre block, check "screen options" at the top right.
2. Add the Rencontre registration form Shortcode `[rencontre_imgreg]` in your page content. (see FAQ).
3. Add the shortcode `[rencontre_login]` in your page content.
4. Use the WordPress default widget 'Meta'.
5. Install a specific plugin like [baw-login-logout-menu](https://wordpress.org/plugins/baw-login-logout-menu/).
6. Use the widget from another plugin (BBPress has one).
7. Add this small code in your header.php file (or other one...), next to the menu :
`<?php Rencontre::f_login(); ?>`
 
**Quarto**

In Dashboard :

* In Rencontre / General, select the page where you installed the shortcode. Save.
* In Settings / General, check the box 'Anyone can register' with role 'Subscriber'. Save.
* (not required) In Settings / Reading, check "a static page" and select "front-page" : the page with the shortcode. Save.

**Quinto**

Register as a member (Admin is not a Rencontre member) : Click Register, add login and email.

If you are localhost, you can't validate the email, but, in Admin panel / users, you can change the password of this new member.
Then, log in with this new login/password. Welcome to the dating part.

To facilitate the settings after installing, you can download and install via CSV (Rencontre/General) [**20 test profiles with photo**](https://www.boiteasite.fr/telechargement/rencontre_import_20_profiles.zip).
You are **not allowed** to use these pictures outside testing on your site.

A question ? [Rencontre WordPress Support](https://wordpress.org/support/plugin/rencontre)

More details in french [here](https://www.boiteasite.fr/site_rencontre_wordpress.html).

== Frequently Asked Questions ==

= The plugin doen't works =
Rencontre is now reliable. Most errors that are reported in the support come from improper installation.

* Do you have members ? (see Rencontre Members in Dashboard)
* Are you connected as a member ? (Admin is not a member)
* Did you follow the instructions PRIMO to QUINTO, para Installation ?
* Are you on the right page ? (with the shortcode)

Before start a new topic in the [support](https://wordpress.org/support/plugin/rencontre), try to find the origin of the error :

* Clear the caches.
* Change wp-config.php to have `define('WP_DEBUG', true);`.
* Use Firebug.
* Googlize your error.

There is no obligation of answer on the support.

= I'm a newbie and I'm a real beginner with WordPress =
Expect some difficulties. It's a little more than plug and play. Do not wait for the support to do the job for you.

= Useful plugins to work with Rencontre =
* WP GeoNames : Insert all or part of the global GeoNames database in your WordPress base - Suggest city to members.
* Email Templates : Send beautiful emails with the WordPress Email Templates plugin.
* Theme My Login : Creates a page to use in place of wp-login.php, using a page template from your theme.
* Polylang : Use Rencontre in a multilingual environment.

= Conditions to appear in un-logged homepage =
* Wait few days (or reset in admin) ;
* Have a photo on my profile ;
* Have an attention-catcher and an ad with more than 30 characters ;
* Shortcode [rencontre_libre] on the right page or Rencontre::f_ficheLibre() is on the right template.

= How to personalize style =
Rencontre is now using W3.css framework.
You can add your custom css in your theme css file or directly in the dashboard.
To overwrite default css file, add `#widgRenc` (and space) at the beginning of every new line.
Sometimes you also have to add "!important" to overwrite W3CSS rules (see example below).

Example :

`#widgRenc img {padding:1px;}`

Example with Google font :

`@import url('https://fonts.googleapis.com/css?family=Coiny'); #widgRenc, #widgRenc .w3-container {font-family:Coiny!important;}`

= Chat and Webcam =

* Chat has only local memory (session). You cannot display the content of a conversation in the Admin side.
* Webcam is not a real streaming but an emulation. The display is refreshed a bit more than every second.
* There's no sound with the webcam. Streaming is not possible on a simple shared hosting without third party.
* HTTPS is mandatory in most case to use the webcam.
* You can change the chat beep : Create two audio files named bip.mp3 and bip.ogg. Move them to /my_theme_folder/templates/.

= Geolocation  =
Geolocation is used to set the GPS location of the user. It works with all devices but :
 
* HTTPS is mandatory,
* Firefox has sometime a bad setting in geo.wifi.uri (about:config),
* The user must accept the request.

Geolocation is activated once per session, only on the account and registration pages.
Geolocation can be enable/disable in Rencontre General options.

By default, geolocation only give the distance between you and another user (xx km from my position).
With the Premium kit, Goelocation is needed to enable the proximity search with map result.

= Facebook =
* Framework for the [Facebook](https://developers.facebook.com/docs/plugins/like-button?locale=en_US#configurator) Like button : 

`
<div id="fb-root"></div>
<script>(function(d, s, id) {var js,fjs=d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js, fjs);}(document,'script','facebook-jssdk'));</script>
<div class="fb-like" data-href="http://mysite.com" data-layout="button" data-action="like" data-show-faces="false" data-share="true"></div>
`

= How to use unconnected search =
For visitors not connected, you can add a tiny quick search form :

*Method 1* : Shortcode : Add the shortcode `[rencontre_search nb=6]` in your page content. nb is the max result number.

*Method 2* : PHP in your theme (best solution for integrator) :

`<?php if(!is_user_logged_in()) Rencontre::f_rencontreSearch(0, array('nb'=>6) ); ?>`

= How to use Rencontre Templates =

**SIMPLEST METHOD**

Copy the files you have changed in a templates folder of your theme : /my_theme_folder/templates/. Don't copy unchanged files.
Prefer a child theme if you don't want to lose these files after a theme update.

**BEST METHOD**
Create a minimalist plugin just for that (and other filter/functions if you want...).  :

*Plugin structure* : A folder "myPlugin" that contains "myPlugin.php" and a folder "templates".
*myPlugin.php* :

`
<?php
/*
Plugin Name: My Plugin
Description: My plugin to customize the templates.
Version: 1.0
Author: Its Me
License: GPL
Copyright: Its Me
*/
function myTplDir($arg) {
	$arg['path'] = realpath(__DIR__).'/templates/';
	$arg['url'] = plugins_url('myPlugin/templates/'); // name of your plugin !
	return $arg;
}
add_filter('rencTemplateDir', 'myTplDir', 10, 1);
// Others functions here
`

[More details](https://www.boiteasite.fr/site_rencontre_wordpress.html#Developpeurs)

= How to set the plugin multilingual =

**SIMPLEST METHOD**

* Install [Polylang](https://wordpress.org/plugins/polylang/).
* Add the Rencontre shortcode(s) on all local homepages (home, home-FR, home-ES, home-DK...).
* If you add Rencontre items to your WP menu, it will be necessary to create a specific WP menu for each language. For languages other than 'default', you will have to fill in the URLs manually for the 'home' page.

**LIGHTEST METHOD**

Add text or little flags in the header of your theme. On click, you create cookie with the right language. Then, the site changes language (back and front office) :

`
<div id="lang">
	<a href="" title="Francais" onClick="javascript:document.cookie='lang=fr_FR;path=/'">Francais</a>
	&nbsp;
	<a href="" title="English" onClick="javascript:document.cookie='lang=en_US;path=/'">English</a>
</div>
`

If you prefer an image flag, replace the content of tag A (English) with something like this :

`<img src="<?php echo plugins_url('rencontre/images/drapeaux/svg/gb.png'); ?>" style="width:36px;" alt="English" />`

= How to customize translation =
The best method is to use 'Loco Translate' plugin to edit a rencontre-xx_YY.po file and create a rencontre-xx_YY.mo file.
Then, copy the po/mo created files from wp-content/plugins/rencontre/lang to wp-content/languages/plugins/.
You can use POEDIT if you prefer.
You can also email us your best version so that we insert it in the plugin.
You can turn off the automatic download of translations in wp-content/languages/plugins/ by adding this filter :

`
add_filter('auto_update_translation', '__return_false');
`

= User role & user removed =
All WordPress roles for the new Rencontre members are removed by this plugin to improve security and speed. That can be a conflict with other plugin.
The members without Rencontre account are automaticaly removed after two days if they can't "edit_posts".

If you want to keep users WP roles, you have just to check the option in the general tab.
Note that if you do this, user deletion (user himself or Admin) will only concern data in Rencontre. Account in WordPress will still exists.

= User registration =
Registration is divided in two part :

* WP registration : email and login Form => clic the email => you are on WP.
* Rencontre registration : phase one to four => you are in rencontre.

With the **fast registration** option :

* WP registration : email and login Form => you are in rencontre with a **limited status**. You have 3 days to complete your account and validate your email to be unlimited.

ADMIN side :

* Members : New user => he is in WP.
* Rencontre / Members : Add new from WordPress => he is in Rencontre.

= Dashboard Access - Moderator =
The Administrator can access to all the Dashboard Rencontre menu.
A BBPress moderator (capability "bbp_moderator") can access to the pages members and jail.

= How to add profil search in search tab (like quick search) =
This is a Premium option. The number of items that can be added is unlimited.

= The automatic sending of emails =
There are two various types of email :

* Regular emails. They give the informations since the precedent regular email. They are sending every month (or 15 or 7 days). One serie during the maintenance hour and another serie the hour after. 
* Instant emails. They just give a instant information (contact request, message in box, smile). There is a sending per hour except during regular emails period. Only one email per person per hour.

= What to include with WP-GeoNames =
The default values are OK.
It's better to limit the data size.

= Available Shortcodes =

* [rencontre] : Display the plugin
* [rencontre_libre] or [rencontre_libre gen=mix city=london] : Display the unconnected part (home page for example)
   * gen=mix : men & girl in same number (&plusmn;5), gen=gay : only gay, gen=girl : only girl, gen=men : only men, gen=mycustomgender (a custom gender you have created)
   * country=fr : only members from France (fr)
   * region=aquitaine : only members from Aquitaine
   * city=paris : only members from Paris
   * redirect=https://mysite.com/wp-login.php?action=register : Redirection URL when click on profile
* [rencontre_nbmembre] or [rencontre_nbmembre gen=girl ph=1] : Display the number of user
   * gen=girl or gen=men
   * ph=1 : only with photo
* [rencontre_search nb=8 day=365] : Display a search form for unconnected member (home page for example)
   * nb: number of results
   * day: age of last connection
* [rencontre_login] : link to login/logout/register
* [rencontre_imgreg title= selector= left= top=] - Display registration form (See screenshots for example)
   * title='Register to ...'
   * selector='.site-header .wp-custom-header img' (jQuery selector of the image where you want to display the form - See Screenshots)
   * left=20 (Left position in purcentage of the parent container size). From 0 to 99. To set less than 10%, write 0 first (ex : 5% => 05)
   * top=10 (Top position in purcentage of the parent container size)
   * login=1 (login form). Empty => (registration form)

= Available Filters =

* rencWidget - return bool : Rencontre can be used as widget
* rencInitHook : Executed after init and before Rencontre
* rencImgSize - args array() - return args array() : Change default profile images size. var_dump args in your filter to get the right format.
* rencImgFullSize - args array(w,h) - return Width and Height for the big img (img src on server, displayed in popup) - default : array(1280,960)
* rencUserDel - arg : $id : Executed when user is deleted (himself or admin)
* rencUserDelMailContent - args array() - return args array() : title, content (user and admin deletion) and moderation item (admin deletion only) for the user deletion email
* rencNumbers - args array() - return args array() : change default numbers (number of portrait in featured box, in online box, in new entrant, in summary email, number of letter in search result Ad ...) and options. var_dump args in your filter to get the right format.
* rencLabels - args array() - return args array() : change the name of URL variables such as "renc", "account". Available name : 'renc','rencfastreg','rencoo','rencii','rencidfm','id','card','edit','msg','account','gsearch','liste','qsearch','write','sourire','demcont','signale','bloque','favoriAdd','favoriDel','sex','zsex','z2sex','homo','ageMin','ageMax','tailleMin','tailleMax','poidsMin','poidsMax','mot','pseudo','pagine','pays','region','ville','relation','profilQS','line','photo','profil','astro','gps','km','fin','paswd'.
* rencTemplateDir - args array() - return args array() : change templates directory.
* rencFicheLibre - shortcode args array(), HTML output - return HTML output : Add content to the Rencontre unconnected home page (fiche libre). Ex : css file...
* rencColor - args array() - return args array() : Add colors to $w3renc list - See "inc/rencontre_color.php"
* rencNoFontawesome - remove Font Awesome css file if filter exists (no need function, only filter).
* rencJsLang - args $lang array() - return $lang - Add or change values in rencontre/lang/rencontre-js-lang.php.
* rencUserPost - args $post, $source - return $post : Allows to filter the data (_POST or _GET) entered by the user before saving or sending. $source : 'sauvProfil','updateMember','sendMsg','quickFind','find'
* rencMailBirthday - args $u - send an email to $u->user_email. See rencontre_filter function f_cron_on().
* rencMailRemind - args $u - send a registration remind email to $u->user_email. See rencontre_filter function f_cron_on().
* rencMailInstant - args $u - send an instant email (One per hour) to $u->user_email. See rencontre_filter function f_cron_liste().
* rencCron : Control the launch of the 2 functions f_cron_ (on & liste) of maintenance and sending of daily and immediate emails. See rencontre_filter.php function f_cron().

[Howto](https://www.boiteasite.fr/site_rencontre_wordpress.html#Developpeurs)

== Screenshots ==

1. Visitor's home page when not connected - Theme Twenty seventeen (2017).
2. Visitor's home page when not connected - Theme Avada.
3. A connected member's home page.
4. Private messages page.
5. Private webcam chat.
6. Administration members.
7. Administration of available profiles.
8. Registration and connection statistics.

== Changelog ==

15/01/2024 : 3.11.3 -  Fix dynamic search issue.

08/01/2024 : 3.11.2

* Fix Subscriber+ PHP Object Injection in search fonction - Thanks to Darius Sveikauskas.

09/12/2023 : 3.11.1

* Add max upload filesize value in CSV Tab.
* Fix Chat launched when not required.
* Fix some issues.
* Add Premium hook.

30/11/2023 : 3.11

* Removes Facebook login. To keep this WordPress connection via Facebook (and add others), use a specific plugin.
* Removes ability to import facebook profile photo.
* Fix Unauthenticated Arbitrary File Upload in CSV Admin part - Thanks to Darius Sveikauskas.
* Fix Subscriber+ PHP Object Injection in dynamic search - Thanks to Darius Sveikauskas.
* Fix wrong orientation, in some cases, when uploading an image.

24/10/2023 : 3.10.1

* Add online notification in search result and home page.
* The color of the online text and the color of the online badge can be set in the custom tab of the dashboard.
* Add an option to disable password change in my account.

13/10/2023 : 3.10

* dbip-country update 2023-10.
* Fix PHP 8.1 deprecated.
* Fix "answer a message" issue.
* Add Rencontre sidebar links (who i smile...) to WordPress menu creation in Rencontre meta-box.
* Add filters 'rencCronMailPart4' and 'rencCronList' to fully take control of automatic emailing.
* Add search by value range to "numeric select" profile fields.
* Updated list of country regions. 

01/05/2023 : 3.9.2

* Fix search issue when city has space in name.
* Remember also gender in search.
* Fix PHP 8.2 deprecated.

01/04/2023 : 3.9.1 -  Fix some issues.

12/02/2023 : 3.9

* The maintenance period and the email sending period are done in the background with Ajax. No more slowdown for the visitor.
* Fix 'home' var warning.

16/12/2022 : 3.8.3

* Fix typo mismatch with ':', '?' and '!' in some language.
* Add badge in the sidebar with number of message in box when menu disabled.
* Add new custom color for actived button (favorite for example).
* Change 'Favorite' button style and background color.
* ADMIN ref lang is WPLANG.

26/10/2022 : 3.8.2

* Add option to set background color for modal warning. Rencontre > Custom > Templates.
* Fix variable mismatch detection.
* Fix a width issue in unconnected home page images.
* Fix menu issue when changing plugin page URL.
* Fix a slowness issue.
* Fix Polylang plugin issue and improves multilingual use.
* Remove nl_NL, it_IT and ru_RU language. They have been taken into account by the WordPress community and will be loaded automatically.

19/08/2022 : 3.8.1

* Flag in SVG. Add Filter "rencNumber['flagPng']" to force old PNG mode.
* Members' photos are now available in retina-ready X2. Add Filter "rencNumber['retina']" to disable retina creation.
* Browser images cache optimisation.
* Images loading optimisation to reduce page load time and data consumption.
* Fix issues.

12/04/2022 : 3.8

* Ability to create mandatory profile fields that are only visible in the back-office (invisible to other members).
* Enhanced profile manager.

21/02/2022 : 3.7.1

* Show-Hide unused buttons on portrait page. The template rencontre_portrait.php should be updated if you have customized it.
* User can rotate images after upload. The **template rencontre_portrait_edit.php** must be updated if you have customized it !
* Add portuguese (Brazil) translation - thanks to César Ramos
* Fix PHP 8.1 warning.

04/01/2022 : 3.7

* Add templates and filter to customize all Rencontre emails : templates/rencontre_mail_birthday.php & rencMailBirthday, templates/rencontre_mail_remind.php & rencMailRemind, templates/rencontre_mail_instant.php & rencMailInstant, templates/rencontre_mail_regular_global.php
* Add rencUserPost filter (filter the data _POST or _GET entered by the user). See F.A.Q.
* Code cleaning.
* Fix somme bugs.

27/11/2021 : 3.6.8

* Fix modal warning issue.
* Add 'rencmodaltimeout' filter to set modal warning windows timeout delay.

23/11/2021 : 3.6.7

* Remove "Region field is empty or outdated !" Warning when option "no region" or "No localisation" is checked.
* Avoid that very long words exceeds the box.
* Profiles not translated into the default language are indicated by a warning on the Rencontre > Profile dashboard
* Add 'searchResultAd' filter also to quick search.
* Add 'lengthNameHome' and 'lengthName' filter to limit display_name length.

08/11/2021 : 3.6.6 - Fix issue in Sidebar with language switcher plugins

18/10/2021 : 3.6.5

* Option to make the choice of one of the proposed cities mandatory, on registration, if wp_geonames is actived.
* Fix somes issues in back-office.

06/10/2021 : 3.6.4

* Add empty Modal Warning ready to be used in rencontre_modal_warning.php.
* Fix PHP8 issue in photo upload.
* dbip-country update 2021-10.

04/08/2021 : 3.6.3

* Display warning if custom genders mixed with default genders.
* Option to show chat conversation down on android.
* Add age of last connection (day) to rencontre_search shortcode filter result.
* Scroll down to result in rencontre_search.

11/04/2021 : 3.6.2

* Add WEBP image format (currently only Mini and Libre) to improve speed and quality.
* Redirect URL in Shortcode Rencontre_libre.
* Login and Redirect Link with WordPress method.
* Hide email if used as login.

08/03/2021 : 3.6.1

* PHP 8 compatibility.
* Redirect Profile Edition after registration.
* Fix registration issue.
* Fix age max search issue.
* Avoid duplicate shortcode installation for [rencontre].
* Ability to allow all gender search.
* Fix GPS issue.
* Rencontre Metabox Menu remove select all.
* Add German translation (front office only) - thanks to Stefan Wolfarth

26/01/2021 : 3.6

* Import CSV and photo available with a ZIP file.
* Remove rencontre_libre reloading delay.
* Ability for user to temporarily deactivate his profile.
* Change "Immaterial" to "No matter".
* Remove "gay" label.
* Add rencjs.js file in template to override rencontre.js functions.
* Fix search issue.
* Fix modal window issue.
* Fix some issues.

01/12/2020 : 3.5.1

* Fix chat issue (my message not displayed in my window).
* Fix unconnected search issue.
* Fix template mini-portrait no photo width issue.

= 3.5 =
19/11/2020

* Display optimization on Smartphones / Small screen.
* Chat improvement. Chat has now memory when user change or reload a page.
* Webcam API Update.
* Dynamic loading of search result when scrolling page (no more pagination). Option in Rencontre > General > Display.
* Option for user to disable the beep of the Chat.
* Add Japanese translation, thanks to Rorylouis.

09/09/2020 : 3.4.3 - Fix chat issue.

08/09/2020 : 3.4.2 - Fix photo issue.

28/08/2020 : 3.4.1

* Fix session not write-closed warning. WP 5.5.
* Fix imagettfbbox issue : PHP without freetype.

= 3.4 =
04/08/2020

* Fix template name issue. Thanks to Tolumba.
* Display gender in profile if custom gender set.
* Main picture permutation.
* Option to display pictures in lightbox : General > Display : Lightbox. New template rencontre_lightbox.php

= 3.3 =
20/03/2020

* Add rencLabels filter to customize URL param name (example : www.mysite.com/?renc=liste&id=981&sex=0 can become www.mysite.com/?abcd=search&zz=981&gender=0). See Available Filters FAQ.
* Add rencJsLang filter.
* ID in URL is now crypted. Set 1 to 'urlNoCryptId' value in rencNumbers() filter to keep ID readable. Template 'rencontre_search.php' is concerned.
* Add 'mailUserPerLine' value in rencNumbers() filter to change number of portrait per line in regular email (default : 2). Add also 'urlNoCryptId'.
* Unread msg is now removed from DB if deleted by sender (previous : marked as deleted in DB and really removed if deleted by recipient).
* User can hide sidebar on mobile.
* Fix message count issue with deleted account.
* Fix search issue and registration issue when "No birth date" checked in Custom tab.
* Fix redirect issue after registration in fast registration with some custom login plugins.
* Fix sidebar my photo size issue.
* DataBase dbip updated

19/04/2020 : 3.3.1 -  Fix locale issue in profile and contry selection.

03/06/2020 : 3.3.2

* Fix country deletion issue.
* Add Russian translation, thanks to Vetal Soft.

= 3.2 =
11/09/2019

* Fix conflict between Rencontre and the WordPress Dashboard theme editor.
* Hide profile box and save button when empty profile.
* Remove registration part 4/4 (and 3/3). User can no longer change his login name.
* Fix disconnection issue on registration.
* Improve featured profiles selection.
* Hungarian translation - thanks to FunnelXpert.

13/10/2019 : 3.2.1

* Fix country deletion issue.
* Fix Fast registration confirmation email issue.
* Fix bad link in email buttons.
* Remove disable search menu notification (OFF).
* Add delNotConfirmed value in rencNumber hook : time before new account deletion.

02/12/2019 : 3.2.2

* Fix SQL injection & XSS vulnerability - Thanks to Sathish Kumar.
* Add imgQuality value in rencNumber hook : user images JPG quality. Default 75.
* Fix message deletion issue.

11/12/2019 : 3.2.3

* Improve CSRF security.
* Fix instant email link issue.
* Fix member search issue in ADMIN part.

30/12/2019 : 3.2.4 -  Fix some issues.

01/01/2020 : 3.2.5 -  Fix profiles issues in dashboard.

10/01/2020 : 3.2.6 -  Fix issue when email used as login.

24/01/2020 : 3.2.7 -  Fix issue with single & double quotes in users profile.

= 3.1 =
15/03/2019

* Adds "infochange" in rencNumber hook.
* Option to open search result profile in a new tab.
* Instant emails in a template.
* Adds Date format in profile fields.
* Join syntax in sql select.
* Load template file in Rencontre folder if does not exist elsewhere (theme or hook).

01/04/2019 : 3.1.1

* Adds custom profile search field before & after a date.
* Fix Immaterial country unsaved issue in search fields.
* Quick search by profil date format, before & after.
* User can display another name than his login name. See Dashboard Rencontre > General > Display.

30/05/2019 : 3.1.2

* Braces style standardisation in the code.
* Hide Region and Profil details in admin dashboard to improve speed.

08/07/2019 : 3.1.3

* Fix SQL injection & XSS vulnerability - Thanks to N Admavidhya

= 3.0 =
18/12/2018 :
* New design based on W3.css framework and Fontawesome. New templates files. You'll need to delete your custom templates if you have them.
* Options to change and view colors of the theme from dashboard.
* New images size. Option to regenerate all images with the new size imposed by the new design.
* Adds rencColor hook to add custom colors.
* Adds rencNoFontawesome hook to block fontawesome css file load.
* JQuery featherlight removed and replaced by CSS.
* Rencontre.css has been very lightened.

14/01/2019 : 3.0.1

* Adds option to fit portrait width in home page. See Rencontre > Custom > Templates.
* Adds title on menu icons.
* Adds img link on mini portrait.
* Adds link to profile in message.
* Adds fichelibre hook and functions for dev.
* Fix issue with PHP>=7.1 on Windows host.
* Fix some errors.

20/02/2019 : 3.0.2

* Fix bad number in search pagination.
* Adds size and weight in profile page (removed in V3).
* Adds imgreg shortcode CSS (removed in V3).
* [rencontre_imgreg] shortcode can display login form (see FAQ).

= 2.3 =
17/02/2018 :
* Search Field Memory.
* Inactive users deletion.
* Fix some issues with the chat.
* Webcam in webRTC in place of SWF.
* Option to hide contact request.
* Option to display only members from my country in my home page. Idem for summary email.
* Adds Dashboard Button to Refresh unconnected home page (rencontre_libre).
* Adds rencNumbers hook.
* Ability to add a custom chat beep (see FAQ).
* Fix onLine time issue.
* TEMPLATE - rencontre_sidebar_top & rencontre_portrait - option to hide contact request.
* TEMPLATE - rencontre_search & rencontre_sidebar_quick_search - add field memory

15/04/2018 : 2.3.1

* Fix option deletion when decode/encode profile images.
* Fix issue with IPV6 in the dashboard Member tab.
* Fix report display in the dashboard Member tab.
* Adds user deletion reason. Add "rencUserDelMailContent" filter to change the default reason list.
* Adds rencTemplateDir filter.
* Better english sentences.
* TEMPLATE - account & search & registration_part3 - English sentences.

02/05/2018 : 2.3.2

* Fix registration issue (user in registration infinite loop).
* Remove zoombox lines in rencontre.css.

28/05/2018 : 2.3.3

* GDPR complience :  Add Rencontre datas in the new "Export Personal Data" Tool in Dashboard.
* TEMPLATE - modal_warning - Move "close" link to the bottom.

25/07/2018 : 2.3.4

* Add country, region and city attributes in rencontre_libre shortcode.
* Fix webcam issue.

07/09/2018 : 2.3.5

* Fix login issue on PHP>7.0.
* Change Facebook API V2.7 => V2.9.

13/11/2018 : 2.3.6

* No Homosexual option checked => Impossible gender change
* Fix maintenance time issue

09/12/2018 : 2.3.7

* Adds cleanup button to remove abandoned images.
* Update IPDB database.
* Adds Update notice hook.
* Change max saved default original image size : 640x480 => 1280x960

= 2.2 =
04/08/2017 :

* WordPress Multisite compatibility.
* Remove Rencontre Widget (Creates confusion on new install).
* Improve user profile synchro after change by Admin : file in "Uploads" folder and button in General.
* Fix issue with photo upload link (if the theme use *{position:relative} what is not a good method).
* Fix activation and installation issues.
* GoogleMap removed => Back improved in Premium current 2017.
* Update country and region default list.
* City Region & Country Taxonomy with wpGeonames plugin.
* Fix error with region in search.
* Add the two template-custom-pages in WordPress meta menu 'Rencontre'.
* TEMPLATE - account & registration_part2 - Change wpGeonames include, remove Map.
* TEMPLATE - sidebar_quick_search & search - Fix error with region.
* TEMPLATE - portrait - Fix error with $rencOpt['chat'].

09/08/2017 : 2.2.1

* Fix MySql "Illegal mix of collations" issue.
* Fix error when reset password.

22/09/2017 : 2.2.2

* Fix profile edition issue with single quote and Ampersand.
* Fix EXIF error when not implemented.
* Enqueue JQuery.
* Replace jQuery ZoomBox by jQuery featherlight (compatible with jQuery3).
* Fix photo profile issue with some themes.
* Update photo libre when changed.
* Adds "bbp_moderator" capability to the Rencontre Dashboard.
* New update DBIP (sept 2017).

01/11/2017 : 2.2.3

* Adds cleaner for missing regions in users data.
* Read EXIF Orientation in JS in place of PHP when add new photo.
* Custom genders in CSV Export/Import.
* Checkbox in a button style (jquery-labelauty).
* Fix empty region warning.
* Adds size image hook (see FAQ).
* Adds Swahili language - thanks to Kenneth Longo Mlelwa.
* Adds Turkish language - thanks to Cise Candarli.
* TEMPLATE - rencontre_portrait_edit & rencontre_account : checkbox Labelauty

29/11/2017 : 2.2.4

* Geolocation only in HTTPS.
* Change Openstreetmap url (both http & https).
* Fix JS error onmouseover in unconnected home page.
* Adds 'immaterial' in region search field.
* Adds ALT tag in unconnected home page IMG.
* Improve page loading speed by removing a slow query.
* Month in string (in place of number).
* Adds UserDel hook.
* Adds Dutch language - thanks to Martin Zaagman.
* TEMPLATE - wp-geonames_location_taxonomy : openstreetmap url
* TEMPLATE - libre_portrait : fix JS error and add ALT tag
* TEMPLATE - portrait_edit : add option for bottom box ($portraitPlus)

12/01/2018 : 2.2.5

* Adds individual class in my-home page rencBox.
* Fix some bugs.

= 2.1 =
22/01/2017 :

* Rencontre menu items in WordPress menu are available from all the site. Connection link added. Registration link added.
* Shortcodes [rencontre_libre_mix], [rencontre_libre_girl], [rencontre_libre_men], [rencontre_nbmembre_girl], [rencontre_nbmembre_men], [rencontre_nbmembre_girlphoto], [rencontre_nbmembre_menphoto] removed. See FAQ.
* New shortcode for Registration Form on the main page (see screenshots theme 2017).
* Fix issue with my locked member list.
* Fix back-line issue in my ad.
* Fix error with bip.ogg & bip.mp3 URL.
* CSS and JS files only loaded when needed.
* TEMPLATE - rencontre_search - fix error in Gender select.
* TEMPLATE - account & registration_part2 - add autocomplete="off" in the City input.
* TEMPLATE - portrait_edit - Fix error : replace $u with $u0 line 44.

20/02/2017 : 2.1.1

* Adds favorites.
* Adds geolocation (see FAQ).
* Adds option to remove inactive accounts from one year.
* Adds option to Prohibit homosexual types.
* Adds Czech - thanks to Libor
* Last online date in "time ago".
* Remove option that prevents user to delete his account.
* Fix issue in message list.
* TEMPLATE - rencontre_account - Prohibit homosexual option
* TEMPLATE - rencontre_my_home - Add favorites
* TEMPLATE - rencontre_portrait - Date in "time ago"
* TEMPLATE - rencontre_registration_part3 - Prohibit homosexual option
* TEMPLATE - rencontre_search_result - Date in "time ago"

01/04/2017 : 2.1.2

* Replace Mcrypt by OpenSSL.
* Improve the efficiency of cities search.
* Fix online issue on portrait.
* Fix no homosexual in search.
* Display a no result text if no result on search.
* Set default agemin agemax on search.
* Set my country in search.
* Improve search display on small screen.
* Adds option to force HTML in email when not readable.
* Fix some errors with PHP 7.1.
* Display an install how-to if no member in base.
* Clean-up on uninstall.
TEMPLATE - rencontre_account - agemin agemax born - Remove deletion part (see bellow)
TEMPLATE - rencontre_search - agemin agemax country, no homo
TEMPLATE - rencontre_sidebar_quick_search - agemin agemax country
TEMPLATE - rencontre_registration_part3 - agemin agemax
TEMPLATE - rencontre_sidebar_top - age
TEMPLATE NEW : rencontre_account_delete

12/05/2017 : 2.1.3

* Fix astro affinity visibility issue on small screen.
* Fix error in registration part4 submission.
* Fix some other bugs.
* Adds default content in Happy birthday email.
* Rencontre menu positioned higher.
* The style of the submit buttons becomes that of the activated theme : input type="button"
TEMPLATE - rencontre_search_result - display astro affinity on small screen (issue 2.1.2)
TEMPLATE - rencontre_account - submit button
TEMPLATE - rencontre_account_delete - submit button
TEMPLATE - rencontre_sidebar_quick_search - submit button
TEMPLATE - rencontre_portrait_edit - submit button
TEMPLATE - rencontre_message_write - submit button
TEMPLATE - rencontre_search - submit button
TEMPLATE - rencontre_registration_part1 - submit button
TEMPLATE - rencontre_registration_part2 - submit button
TEMPLATE - rencontre_registration_part3 - submit button
TEMPLATE - rencontre_registration_part4 - submit button
TEMPLATE NEW : rencontre_modal_warning

= 2.0 =
15/10/2016 :

* Overhaul of the code - Creation 26 Templates files.
* Smartphone display improved.
* Admin Dashboard with tabs to be more readable.
* GoogleMap API key is now needed.
* Facebook Graph API upgrade v2.7.

03/11/2016 : 2.0.1

* Adds image rotate option on upload.
* Fix sidebar hidden.
* Shortcode "Rencontre" launched after init.
* Fix an issue in the message page that can cause CPU overload.
* TEMPLATE - registration_part2 - Add GoogleMap Key also in registration (forget it).
* TEMPLATE - sidebar_top - Fix error whis user name (bracket).
* TEMPLATE - account & registration_part1, 2 ,3 & rencontre.css - remove size in select tag, remove CSS .9em in corresponding options.
* TEMPLATE - message_conversation - confirm before deletion.

12/12/2016 : 2.0.2

* Adds monthly message deletion option.
* Dashboard is now enable to all users who can 'edit posts'.
* Fix image rotation issue on IOS.
* The messages sent are displayed in italic in Inbox.
* Blocked member cannot send message (issue).
* New update DBIP (dec 2016).
* TEMPLATE - message_inbox - add class msgin or msgout. msgout in italic in rencontre.css
* TEMPLATE - message_conversation - delete "Date : ".

= 1.0 =
09/06/2014 - First stable version.

== Upgrade Notice ==

= 3.0 =
This new version is not compatible with the old templates. If you use custom templates in your theme folder, you will need to delete them or not migrate to this new version 3.0. After update, you should regenerate photos (Rencontre > General > Display)

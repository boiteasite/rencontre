=== Rencontre - Dating Site ===
Contributors: sojahu
Donate link: https://www.paypal.me/JacquesMalgrange
Tags: dating, meet, love, match, social
Requires at least: 4.6
Tested up to: 6.5
Requires PHP: 5.5
Stable tag: 3.12.3
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

= Emails not received =
Rencontre is not responsible for this recurring WordPress problem.
If members don't receive emails when registering (for example), we strongly recommend that you install an SMTP email plugin.
You can use a free solution via your gmail account, or a paid solution for larger volumes.
WP Mail SMTP is the most recommended plugin and is free.

= I'm a newbie and I'm a real beginner with WordPress =
Expect some difficulties. It's a little more than plug and play. Do not wait for the support to do the job for you.

= Useful plugins to work with Rencontre =
* WP GeoNames : Insert all or part of the global GeoNames database in your WordPress base - Suggest city to members.
* WP Mail SMTP : fixes your email deliverability issues by reconfiguring WordPress to use a proper SMTP provider when sending emails.
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
* [rencontre_login] or [rencontre_login register=0] : link to login/logout/register
   * loginout=0 : link hidden - default is visible
   * register=0 : link hidden - default is visible
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

= 3.12.3 =

* Remove '&nbsp;' that cause text alignment issues.
* Fix profil search issue on some servers.

= 3.12.2 =

* Fix modal warning closing issue on Android.

= 3.12.1 =

* Fix user photo issue when add or delete.

= 3.12 =

* Fix warning file not exists on plugin install - patch.php.
* Fix issues with rencontre_login shortcode. Adds option to disable register or loginout links. See shortcodes F.A.Q.
* Reduced menu size on smartphones - Template rencontre_menu.php and rencontre.css.
* Auto scroll on loading (option) to display the plugin menu at the top of the page.
* Added sorting of members by sex in the Rencontre > Members tab of the dashboard.
* Email subjects can be customized.
* Users have the option of prohibiting interaction with members who do not match their criteria.
* Option to respect precise criteria for featured profiles.

= 3.11.3 =

* Fix dynamic search issue.

= 3.11.2 =

* Fix Subscriber+ PHP Object Injection in search fonction - Thanks to Darius Sveikauskas.

= 3.11.1 =

* Add max upload filesize value in CSV Tab.
* Fix Chat launched when not required.
* Fix some issues.
* Add Premium hook.

= 3.11 =

* Removes Facebook login. To keep this WordPress connection via Facebook (and add others), use a specific plugin.
* Removes ability to import facebook profile photo.
* Fix Unauthenticated Arbitrary File Upload in CSV Admin part - Thanks to Darius Sveikauskas.
* Fix Subscriber+ PHP Object Injection in dynamic search - Thanks to Darius Sveikauskas.
* Fix wrong orientation, in some cases, when uploading an image.

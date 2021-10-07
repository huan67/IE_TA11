=== Checklist in Post ===
Contributors: tomiskym
Tags: checklist, in, post, shortcode,for,user,reader, checklist,Frontend,list,to-do,todo
Requires at least: 3.0
Tested up to: 5.0
Stable tag: 1.2.3
License: #GNUGPLv3
License URI: https://www.gnu.org/licenses/gpl.html

Allow creating checklists in posts based on bulleted list.

== Description ==
Allow creating checklists in posts based on bulleted list.
**Supports Cookies**

*Wordpress 5.0 and Gutenberg Support:*
Use Classic Block and then Visual editor instructions below.
There are plans for Separate Gutenberg block in the future.

*Usage in visual editor :*
* Create / Edit Post and add classic Visual Editor bulleted list to it. Try to prepare space for it by leaving empty lines before and after.
* Select list in Visual Editor (and empty line above) and click button `"Add Checklist"`.
* Shortcodes `[checklist_in_post] [/checklist_in_post]` will appear.
* Save.
* If you want to use cookies - check cookie option under plugin settings.

== Installation ==
EN:

* Upload the directory wordpresschecklistinpost in /wp-content/plugins/ or install the plugin over the plugin manager of WordPress.
* Activate the plugin over the plugin manager of WordPress.

*Wordpress 5.0 and Gutenberg Support:*
Use Classic Block and then Visual editor instructions below.
There are plans for Separate Gutenberg block in the future.

*Usage in visual editor :*
* Create / Edit Post and add classic Visual Editor bulleted list to it. Try to prepare space for it by leaving empty lines before and after.
* Select list in Visual Editor (and empty line above) and click button `"Add Checklist"`.
* Shortcodes `[checklist_in_post] [/checklist_in_post]` will appear.
* Save.
* If you want to use cookies - check cookie option under plugin settings.


== Changelog ==
1.1.3 
Wordpress 5.0 Support (You need to use Block Classic). 
1.1.2
Fixed: Styling for some themes
Fixed: Javascript on change event
1.1.1
Fixed: ToggleCookie
Fixed: Cookie path=/
1.1.0 
New : Changed way how checked elements are build, changed classes
New : Cookie support - settings page into WordPress Admin. 365 days period.
1.0.4
Fixed: Contact Form conflict resolved (Thanks to @tdper)
1.0.3
Fixed: Allow Nested Lists (Thanks @iisisrael)
Fixed: Allow using HTML into Lists
Fixed: Force padding-left: 0px;
1.0.2
Force transparent element list background (for themes using background in li)
1.0
Initial Release

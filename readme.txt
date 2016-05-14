=== DNUI ===
Contributors: nicearma
Tags: image, clean up, images, delete, image not used, image unused, delete unused image, delete not used image, clean up image, clean image, clean images, clean, clean wp, clean wordpress
Requires at least: 4.4
Tested up to: 4.4
Stable tag: 2.5.4

Search image from the database and delete all unused images making space in your server and clean up the database from all them
 
== Description ==

DNUI (<b>D</b>elete <b>N</b>ot <b>U</b>sed <b>I</b>mage) will search images from the database and try to find it on every Post and Page, if one image has one reference in this either post or page, the plugin will tell you that the image is used.

The version 2.0 is one big remake of this plugin, all the code have changed, now the code work by Rest Service and use AngularJS (not more the BackboneJS)

The update from the 1.x to the 2.0 is automatic, the only problem is that you will lost the backup folder and all backups made from the version 1.x

The plugin was fully test it with WordPress 4.3/4.4.1 and PHP 5.3/5.4/5.6

This plugin use:

* AngularJS 1.4.8 core, resource and animate
* Angular UI 
* Bootstrap

This version can search if the image is used at:

* Excerpt (reference image or shortcode)
* Publish Post/Page (reference image or shortcode)
* Draft/Revesion Post/Page (reference image or shortcode)
* Shortcodes (include gallery)

Translation:

* Spanish

You can found the <a href="https://apps.nicearma.com/product/dnui-delete-not-used-image-pro/">DNUI PRO VERSION</a> if you need more useful features, like compatibility with WooCommerce

If you need search all type of file or search from you upload folder try <a href="https://es.wordpress.org/plugins/cuf-cleanup-upload-folder/">CUF</a>

Github at <a href="https://github.com/nicearma/dnui">DNUI</a>

== Changelog ==

= Version 2.5.4 =
* Change log
* Added ob_clean to clean the output
* Change wp_die for die(json) (see at other plugin)
* Added more useful information
* Add new context for translation
* Added alert if fetching server go wrong

= Version 2.5.3 =
* Better user experience
* Fix JS error log
* Disabled errorHandler (catchching error from other plugin)
* Add Spanish

= Version 2.5.2 =
* Fix bug PHP 5.3
* Fix bug rollback page
* Added lost lang

= Version 2.5.1 =
* Fix bad name (incompatibility with the pro version, only necessary if you use the PRO version)

= Version 2.5 =
* Fix JS bug delete all (without backup folder)
* Added log system
* Fix JS bug delete all with original image already deleted
* HTML changes
* Normally last 2.x version, until Angular2 come out 

= Version 2.4 =

* Search shorcode in excerpt
* Search image in excerpt (short description)
* Search in meta value
* Change the option view
* Fix serveral bug (the restore button)
* Change codes

= Version 2.3 =

* Added better english
* Now can be translated.

= Version 2.2.2 =

*Fix backup active but folder not created

= Version 2.2.1 =

* Fix empty array gallery and backup
* Fix sync of delete sizes (deleteAll button)
* Fix several others bugs (dead last page)

= Version 2.2 =

* Add original to ignore list

= Version 2.1 =

* Add draft/revision check
* Add gallery check (made with javascript)
* Add wait to the deleteAll button
* Changes in the Javascript (better performance the first call)

= Version 2.0.1 =

* PHP 5.3.x compatibility

= Version 2.0 =

* Change all the PHP code
* Change all the Javascript code (now with AngularJS)
* Added warning

= Version 1.5.4 =

* Not limit in the quantity of image to search

= Version 1.5.3 =

* Fix JS ignore size if there nothing in sizes and hidden orignal problem


= Version 1.5.2 =

* SQL performance
* FIX JS select all 
* FIX crazy problem with sizes

= Version 1.5.1 =

* Fix: Uncaught SyntaxError: Unexpected token < 

= Version 1.5 =

* Add compatibility with gallery (wordpress native gallery)
* Fix of Bug has_cap
* Fix of other bugs

= Version <1.5 =

* Bugs fixed
* Add 3 menu (Scan database, backup, option)
* Add backups
* Add Ignore sizes
* Add update if image not exist
* Add some security option
* Restore image from backup

A lots of js thanks to Backbone! 

= Version 1.0 =

Change a lots of logic, a lots of js and not much of PHP, use of concept of service rest

= Version 0.8 =

Get projet DUI from the web, and change some logic, a lots of PHP, not much of js

== Installation ==

The easy way :

1. Download this plugin direct from the page of plugin in your wordpress site.

The hard way :

1. Download the zip.
2. Connect to your server and upload the `DNUI` folder to your `/wp-content/plugins/` directory
2. Activate the plugin using the `Plugins` menu in WordPress


== Frequently Asked Questions ==

= Why i have to do Backup? =

This plugin will delete images and information's in your server and the database, so you have to do one BACKUP every time you want
to use this plugin.

= Is the backup system from the DNUI plugin enough? =

Yes and no, if you have the backup option active, the plugin will try to do one backup of the image you are try to delete, but this is not the main purpose of the DNUI plugin, so is not bull proof <br/>
In the WordPress.org plugin page you can find a lots of Backup Plugin, so the will have better code for make Backup's

= Is really not used / unused? =

Yes and not, the not used label, tell you that the imageName.imageType (toto.jpg) is not found in any Post/Page/Shortcode
So if you have another plugin, for example 'E-commerce X' that use the toto.jpg in one HTML code, the
DNUI plugin can't work finding any reference, so you will have one false 'not used' label

= How to fix the false 'not used' label? =

This question can be hard to answer <br>
I build this plugin for help you to fix this problem, you have somes options:

1.  Use the Ignore Size Option, you can select one or more options (use Ctrl+Click) to ignore the size's
2.  You can dev your own ChekkerImage[Plugin].php code, and add this to CheckersDNUI (you can send me the code and i will put this in the Free version)
3.  Ask me to do it this plugin compatible with the X Plugin (Only for Pro version)

= Where i can found the version pro? =
You can found it at https://apps.nicearma.com

= Fetching server... all time =

I really don't know why some of you have this problem, try to reset the options at the option tab, and if the problem continue make a Support Threads at wordpress.org

= How to test the plugin in my page =

1. Add one post.
2. Upload n images to this post.
3. Add different sizes and see if the plugin DNUI is taking the good original/sizes used or not.
4. See if other plugin is using other sizes that the plugin DNUI is thinking that aren't used and adapt the ignore size list.
5. Delete some not used image and see if the post/theme/page/other plugin is working|showing like he have to do.
6. Try to adapt the DNUI option for make the plugin work fine with all plugin(TIP: USE THE IGNORE SIZE)
7. Make your own crazy test (changing the DNUI option) for see what happen.
8. Begin the delete part with the backup option checked (But is better if you use other backup plugin just in case)

*This will take you 15 min but you will see if all is OK or not*


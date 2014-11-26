=== DNUI Delete not used image===
Contributors: nicearma
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=DX8UCXHB6RMD4&lc=US&item_name=DNUI%20donation&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: image, cleanup, images, delete, image not used, image unused, delete unused image, delete not used image, cleanup image
Requires at least: 4.0
Tested up to: 4.0
Stable tag: 1.5.4

Search/Delete all not used images from the database and make space in your server and cleanup the database from all unused images
 
== Description ==

Again : Make backups before intence use of this plugin.

This plugin will help you to find all image in the database that are not being used/unused and give you the oportunity to cleanup the database and the server space.

You will only delete not used/unused image (based in the logic of a blog site, continue to read please), so dont worry about delete image used (if you use the DNUI backup option!!!!).

*Never use this plugin for the first time for delete all not used image, be careful because some plugin and theme can use images that this plugin will thing is not used (You have in the option menu, the ignore sizes for this)*

If you have problem with other plugin, you can ask me for helpt to adapt the code.

If you have any feedback, recommendation, comments leave me a comment in the blog or here!

How do i know if this code will work or not in my site?
The plugin work this way!!
* The plugin will search images (original and sizes) from the database
* He will search one by one (orinal and sizes) the if one of each is used or not (the mean of used or not, it's if the image nicearma.png have one page or post with the same value Ex: <img src='nicearma.png' > )
* He will search all post/page with gallery (if the option is checked) and try to find the match with the image id (matching to the original and size images)

This plugin will not work fine if:

* You use some lazy image plugin
* You dont have the html code of <img src='' or markdown equivalent (the importat is have in pages/posts the reference of the image)
* Some other plugin that use other logic (i put all the time that the plugin will search in post and page, but the sql search all rows if the value arent 'attachment' and 'nav_menu_item', you can see more in the file dnuiL.php)

More information you can find it here http://www.nicearma.com/delete-not-used-image-wordpress-dnui/

*Recommendations*

* Make BACKUPs of your database and the uploads folder, because the plugin will delete the information in the database and your server, they are irreversible, so if something goes wrong is better have a backup.
* Performs a lots of tests before use in post in production, try to use blogs with the same characteristics, since I have not tested for compatibility with other plugin, so you can see if will work fine (see more in the https://wordpress.org/plugins/dnui-delete-not-used-image-wordpress/faq/ ).
* Download this plugin only from wordpress.org.

== Changelog ==

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

= Why after upgrade the plugin, doesn't work =

This is a bug that i don't know have to resolver, the problem is that i change some value from the database, and in the update this value is not added to the database, but the solution is easy: 
*Deactivate and active it the plugin again*

= Why you have to make a backup  =

You have to make sure that the logic is the good for you, i desing this plugin for the wordpress of base(a blog site), not some super blog-ecommerce-forum-something.
So is better that you have some return point if something go wrong

= How to test the plugin in my page =

1. Add one post.
2. Upload n images to this post.
3. Add diferent size and see if the plugin DNUI is taking the good original/sizes used or not.
4. See if other plugin is using other sizes that the plugin DNUI is thiking that aren't used and adapt the ignore size to this part.
5. Delete some not used image and see if the post/theme/page/other plugin is working|showing like he have to do.
6. Try to adapt the DNUI option for make the plugin work fine with all plugin(TIP: USE THE IGNORE SIZE)
7. Make your own crazy test (changing the DNUI option) for see what happen.
8. Begin the delete part with the backup option checked (But is better if you use other backup plugin just in case)

*This will take you 15 min but you will see if all is OK or not*

= The backup folder is empty, but i checked the backup option =

Please set right permission of the backup folder to write (you can find a lots information about this in the internet)


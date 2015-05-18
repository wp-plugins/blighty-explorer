=== Blighty Explorer ===
Contributors: Blighty
Tags: dropbox explorer, dropbox, file manager, explorer, file management, document management, digital store, integrate dropbox, embed dropbox
Donate link: http://blighty.net/go/blighty-explorer-plugin-paypal-donation/
Requires at least: 4.1.1
Tested up to: 4.2.2
Stable tag: 1.3.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allows the complete file structure of a folder in Dropbox to be shared and navigated within a Wordpress page or post.

== Description ==
Allows the complete file structure of a folder in Dropbox to be shared within a Wordpress page or post. The folder can then be navigated via the Wordpress site and files selected and downloaded. Changes in the Dropbox folder, such as the addition or deletion of files, are reflected through to the Wordpress site. This version supports a subfolder within the Apps folder of Dropbox.

== Installation ==
1. Connect this plugin to your Dropbox account.
2. This will create a subfolder called Blighty Explorer in your Apps folder within Dropbox.
3. Place your folders and files you wish to share with this Wordpress installation inside the Apps/Blighty Explorer subfolder.
4. Use the shortcode [bex_folder] in your post or page to display a folder structure / file navigator.

== Frequently Asked Questions ==

= Can I use share two different folders from the same Dropbox account on two different installations? =
Yes. Structure your Apps/Blighty Explorer folder in Dropbox to have two subfolders. Change the root folder in the settings page on each installation to the subfolder you want to share.

= I like the icons in the file explorer. Can I use them in my software? =
With version 1.3.0, I implemented several icons from the wonderful Silk Icon set by [famfamfam](http://www.famfamfam.com/lab/icons/silk/). They are used under Creative Commons Attribution 3.0 License.

= I've upgraded from Version 1.2.1 or earlier. Do I still need the WP SVG Icons plugin? =
WP SVG Icons provides an easy to implement selection of icons that this plugin used to use in previous versions. It is no longer required and, if you have no other need for it, can be deactivated and deleted. The current version of the Blighty Explorer plugin will detect the presence of WP SVG Icons and prompt you to uninstall it. You can dismiss this prompt if you like.

== Changelog ==
= Version 1.3.1 - May 18th, 2015 =

* Fixed a bug that caused problems with the folder/file navigation when WordPress permalinks were left to their default setting.

= Version 1.3.0 - May 9th, 2015 =

* Replaced the need for the WP SVG Icons plugin.
* Added new Silk icon set from [famfamfam](http://www.famfamfam.com/lab/icons/silk/).
* Show unique icons by file type.
* Only show notices on all plugins and Blighty Explorer pages in the admin.
* Tidied up the formatting of the Dropbox explorer / folder hierarchy.

= Version 1.2.0 - April 30th, 2015 =

* Added stylesheet support for improved formatting.
* Added support to optionally display file modification date and filesize in the folder/file list.
* Sorted folders to always display above files in the folder/file list.
* Added a link in the Admin to the WordPress Support Forums for this plugin.
* Tidied up some of the code.

= Version 1.1.1 - April 19th, 2015 =

* Improved root foldername validation.

= Version 1.1.0 - April 18th, 2015 =

* Added admin settings to specify an optional starting (or root) subfolder.
* Added Frequently Asked Questions.

= Version 1.0.0 - April 14th, 2015 =

* Initial release.

== Upgrade Notice ==
* 1.3.1 - Minor bug fix for WordPress non-permalinks setting.
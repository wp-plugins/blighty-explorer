=== Blighty Explorer ===
Contributors: Blighty
Tags: dropbox explorer, dropbox, file manager, explorer, file management, document management, digital store, integrate dropbox, embed dropbox
Donate link: http://blighty.net/go/blighty-explorer-plugin-paypal-donation/
Requires at least: 4.1.1
Tested up to: 4.1.2
Stable tag: 1.1.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allows the complete file structure of a folder in Dropbox to be shared and navigated within a Wordpress page or post.

== Description ==
Allows the complete file structure of a folder in Dropbox to be shared within a Wordpress page or post. The folder can then be navigated via the Wordpress site and files selected and downloaded. Changes in the Dropbox folder, such as the addition or deletion of files, are reflected through to the Wordpress site. This version currently supports a subfolder within the Apps folder of Dropbox.

== Installation ==
1. Connect this plugin to your Dropbox account.
2. This will create a subfolder called Blighty Explorer in your Apps folder within Dropbox.
3. Place your folders and files you wish to share with this Wordpress installation inside the Apps/Blighty Explorer subfolder.
4. Use the shortcode [bex_folder] in your post or page to display a folder structure / file navigator.

== Frequently Asked Questions ==
= What is the WP SVG Icons plugin and why does this plugin need it? =
WP SVG Icons provides an easy to implement selection of icons that this plugin uses in the folder/file explorer. The Blighty Explorer plugin automatically detects whether the WP SVG Icons plugin is installed and, if not, notifies you and offers links to install and activate it. It's optional, but things won't look right on the front-end if you don't install it.

= Can I use share two different folders from the same Dropbox account on two different installations? =
Yes. Structure your Apps/Blighty Explorer folder in Dropbox to have two subfolders. Change the root folder in the settings page on each installation to the subfolder you want to share.

== Changelog ==
= Version 1.1.1 - April 19th, 2015 =

Improved root foldername validation.

= Version 1.1.0 - April 18th, 2015 =

Added admin settings to specify an optional starting (or root) subfolder.
Added Frequently Asked Questions.

= Version 1.0.0 - April 14th, 2015 =

Initial release.

== Upgrade Notice ==
Added root folder option and support. Added FAQs.
=== Short URL Plugin ===
Contributors: harleyquinedotcom
Donate link: http://www.harleyquine.com/
Tags: plugin, linking
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: trunk

Take long winding URL's and make them short and snappy. Also keeps track of clicks.

== Description ==

Short URL allows you to create shorter URL's and keeps track of how many times a link has been clicked. It's useful for managing downloads, keeping track of outbound links and for masking URL's. Clicking the Clear All Clicks button will reset the count for each entry. Visit the plugin page for more information about this plugin.

Version 2.7.1 includes pagination of links, easier setup of htaccess (permalinks) and allows admins to pass variables to their redirects. It also allows customisation of the permalink so you're no longer limited to /u/23.

Modify u.php if you'd like a custom message when a redirect is not found.

== Installation ==

1. Upload `short-url.php` and 'u.php' to the `/wp-content/plugins/short-url-plugin` directory or automatically install from WordPress 2.7
1. You can either keep the u.php in the plugins folder or move/copy it to your root folder. Two u.php's won't make any difference.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit Plugins > ShortURL to setup the permalink options, add, remove and edit links.
1. Your links will now have a Short URL such as http://www.yourdomain.com/u/5

== Frequently Asked Questions ==

= How do I use it? =

Visit the ShortURL page in your dashboard (Plugins > ShortURL) to add, remove and edit links. Then use the outputted url in your posts/emails wherever.

= Where can I get more information? =

From the plugins page on http://www.harleyquine.com/php-scripts/short-url-plugin/.

== Screenshots ==

1. The admin screen.

== Upgrading ==

Simply upload the files and go. Files should be in a short-url-plugin folder in wp-content/plugins.
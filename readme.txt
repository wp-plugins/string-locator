=== String locator ===
Contributors: Clorith
Author URI: http://www.clorith.net
Plugin URI: http://wordpress.org/plugins/string-locator/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARR8GWYHBVPN
Tags: theme, plugin, text, search, find, editor, syntax, highlight
Requires at least: 3.6
Tested up to: 4.3
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Find and edit code in your themes and plugins

== Description ==

When working on themes and plugins you often notice a piece of text that appears hardcoded into the files, you need to modify it, but you don't know where it's located in the theme files.

Easily search through your themes, plugins or the WordPress core and be presented with a list of files, the matched text and what line of the file matched your search.
You can then quickly make edits directly in your browser by clicking the link from the search results.

By default a Smart-Scan is enabled when making edits, this will look for inconsistencies with braces, brackets and parenthesis that are often accidentally left.
This drastically reduces the risk of breaking your site when making edits, but is not an absolute guarantee.

As of version 1.6 the plugin will check for errors on your site after making edits, and if any are detected, will revert to the previous version of your edited file.

** Translations**

српски (Serbian) - Ognjen Djuraskovic

Español (Spanish) - Ognjen Djuraskovic

Deutsch (German) - [pixolin](http://profiles.wordpress.org/pixolin/)

== Installation ==

1. Upload the `string-locator` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You will find the String Locator option under then `Tools` menu

== Frequently asked questions ==

= Will Smart-Scan guarantee my site is safe when making edits? =
Although it will do it's best at detecting incorrect usage of the commonly used symbols (parenthesis, brackets and braces), there is no guarantee every possible error is detected. The best safe guard is to keep consistent backups of your site (even when not making edits).

As of version 1.6, the plugin will check your site health after performing an edit. If the site is returning a site breaking error code, we'll revert to the previous version of the file.


== Screenshots ==

1. Searching through the Twenty Fourteen theme for the string 'not found'
2. Having clicked the link for one of the results and being taken to the editor in the browser
3. Smart-Scan has detected an inconsistency in the use of braces

== Changelog ==

= 1.7.0 =
* Tested with WordPress 4.3
* Made it uses WordPress list tables (because they look nice and I felt adventurous)
* If the preview text is really long, an excerpt is pulled instead of making a massive text blob
* Fixed a typo in a query argument
* Reordered the search result list based on priority

= 1.6.0 =
* Revert edits if site health degrades as a direct cause of said edit

== Upgrade notice ==

4.3 compability update with other improvements, see the changelog for details
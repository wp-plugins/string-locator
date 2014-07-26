=== String locator ===
Contributors: Clorith
Author URI: http://www.clorith.net
Plugin URI: http://wordpress.org/plugins/string-locator/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARR8GWYHBVPN
Tags: theme, plugin, text, search, find, editor, syntax, highlight
Requires at least: 3.6
Tested up to: 4.0
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Find and edit code in your themes and plugins

== Description ==

When working on themes and plugins you often notice a piece of text that appears hardcoded into the files, you need to modify it, but you don't know where it's located in the theme files.

Easily search through your themes or plugins and be presented with a list of files, the matched text and what line of the file matched your search.
You can then quickly make edits directly in your browser by clicking the link from the search results.

By default a Smart-Scan is enabled when making edits, this will look for inconsistencies with braces, brackets and parenthesis that are often accidentally left.
This drastically reduces the risk of breaking your site when making edits

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



== Screenshots ==

1. Searching through the Twenty Fourteen theme for the string 'not found'
2. Having clicked the link for one of the results and being taken to the editor in the browser
3. Smart-Scan has detected an inconsistency in the use of braces

== Changelog ==

= 1.3.0 =
* Added search all for themes and plugins
* Refactored code
* Added german translations

= 1.2.1 =
* Added missing i18n text strings
* Added capability checks for edit screens

= 1.2.0 =
* Added custom code editor
* Syntax highlighting
* Code validation using Smart-Scan
* Quick jump links to areas with errors detected
* Replaced unused admin notice
* Removed previously used WP Editor checks

= 1.1.1 =
* Added Spanish translation files added
* Added Serbian translation files added

= 1.1.0 =
* Added link to the online editor for themes/plugins from search results
* Added extra notification text on editor page when referenced by the plugin, makes it easier to find your search string
* Screenshots and more plugin details added
* Fixed column width for line number being way too large
* Add missing inline comments/function references

= 1.0.0 =
* Initial release

== Upgrade notice ==

Extended the search functionality
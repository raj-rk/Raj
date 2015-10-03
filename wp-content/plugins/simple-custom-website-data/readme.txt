=== Custom Website Data ===
Contributors: DannyWeeks
Donate link: http://dannyweeks.com/contact
Tags: information, data, storage, business details, developer tools, contact, details, phone, email, address, global, info
Requires at least: 3.5.2
Tested up to: 4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Store any data you wish and access it using shortcodes or PHP functions. Easy and simple data storage and retrieval for beginners and advanced users.

== Description ==

CWD allows you to simply store and retrieve  data quickly and easily for your own use. Example applications of this could be to save your websites contact email address and phone number. Storing them using CWD you can then output them using the simple shortcodes throughout your website; if something changes, no problem just update it in one place and you are good to go.

Your custom data can either be displayed on the website using shortcodes or you can use the data to manipulate the website in ways only limited by your imagination through use of a PHP function!

Key Features:

*   Easy and quick installation.
*   Data stored in your Wordpress database for security and quick access.
*   Shortcode works instantly with your data with not need for additional settings.
*   Developers can use the PHP function provided to access the data quickly and simply.
*   Import and export data using using csv.

Future Features:

*   Implement AJAX submissions for quicker manipulation and better UX
*   Opt in anonymous usage tracking

== Installation ==

Installation of Custom Website Data is simple.

e.g.

1. Upload `simple-custom-website-data` to the `/wp-content/plugins/` directory or upload the .zip to in your Wordpress admin area.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the "Custom Data" tab in your admin menu

== Frequently Asked Questions ==

= How do I use my data =

There are currently three to retrieve your data:

* You can place the shortcode generated on any page or post.
* Use the Wordpress function `do_shortcode('[cwd ref="yourdataref"]')`
* Use the CWD PHP function `cwd_getThe('yourdataref')`
* If you have stored an array and wish to access a specific key use `[cwd ref="my_array" key="the_key"]`

== Screenshots ==

1. Dashboard showing all records, their values and shortcodes.
2. Adding a record couldn't be easier.
3. Importing and backing up is simple with the Utility feature.

== Changelog ==

= 2.1 =

* Tested up to Wordpress 4.2
* Removed use of Namespaces throughout CWD. Should now be compatible with PHP 5.2> - Thanks [miwalter](https://wordpress.org/support/topic/namespace-problem)
* Fixed issue with being unable to import some json files - Thanks Oscar for contacting me regarding this issue.
* Changed markdown parser to [Slimdown](https://gist.github.com/jbroadway/2836900#file-slimdown-php) to avoid issues with namespacing in Markdown

= 2.0.3 =

* 'What's New' section automatically populates using readme.txt.
* Fixed bug where in some cases a fatal error occurs due to the reflection class [reported via support forum](https://wordpress.org/support/topic/error-activating-plugin-16).

= 2.0.2 =

* Tested up to Wordpress 4.1.
* Added screenshots to Wordpress.org.
* Fixed issue with debug function conflicting with theme debug function.

= 2.0.1 =

* Fixed notices shown with WP_Debug turned on.

= 2.0 =

* Out with the 90's style; in with the modern Wordpress look.

* Added sub menu.

* Arrays are displayed in a much cleaner fashion on the dashboard.

* Entire code base refactored to be cleaner, more readable and more efficient.

* Automatically select the shortcode from the dashboard by clicking it.

= 1.4.1 =

* Added ability to retrieve array elements using shortcode

= 1.4 =

* Added ability to store and retrieve multidimensional array

= 1.3.2 =

* Fixed bug with isJson function return true if var is numeric

= 1.3.1.1 =

* Updated information

= 1.3.1 =

* Minor changes

= 1.3 =

* Added ability to export all records to csv file
* Added ability to import csv file of records - if a reference already exists it will be skipped during the import process
* Additional security measures added
* Updated processing arrays to be consistant
* Changed menu logo

= 1.2 =

* Added advanced function `cwd_updateThe()` for writing to a record via PHP
* Updated user guide to reflect changes
* CSS change to hide wp footer

= 1.1 =

* Fixed security issues.
* Updated folder name to 'simple-custom-website-data' and documentation to match.

= 1.0 =

* N/A for version 1.0.

== Upgrade Notice ==

= 2.0.2 =
Tested upto Wordpress 4.1. If you have had issues updating this plugin in the past briefly change your theme, update the plugin and re-activate your theme.

= 1.1 =
N/A for version 1.1.

= 1.0 =
N/A for version 1.0.

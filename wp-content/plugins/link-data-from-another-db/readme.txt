=== Link Data From Another DB ===
Contributors: montanabanana, abda53
Donate link: http://www.amazon.com/gp/wishlist/NLKLKBFRBRZU/
Tags: link data another separate db database
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 0.1.0.6
License: GPLv2 or later

== Description ==
Link data from another database is a plugin that allows your WP users to display user selectable content from a MySQL database. The Admin will set up the SQL query and the User can select results from the query. This plugin was originally created for a real estate website to allow Users to display their properties within their WP author page.

== Installation ==
1. Upload the `link-data-from-another-db` folder to the `/wp-content/plugins/` directory, or install from the repository
2. Activate the plugin from the Plugin menu in Wordpress
3. In your Admin account Navigate to Users -> Link Data From DB
4. Click on the `DB Config` tab and enter your Database Settings
5. Click on the `Generator` or `Manual` tab to create your query
6. Click on the `Options` tab and enter your permissions and other options
7. Click on `Update Options` at the bottom
8. In your template code, reference the plugin with MBlinkData() and parse your data (ex: http://pastie.org/8406961 )
8. In a User account click on Your Profile
9. Scroll down to Select Your Listings
10. Select the results you want to show in your profile
11. View your page

== Screenshots ==
1. **Location** - Manage your linked data
2. **Tips Screen** - Find useful tips and info
3. **DB Config** - Set up the second database config information
4. **Query Generator** - Use this Generator for single queries
5. **Manual Generator** - Use this Generator for complex queries including JOINs and SUBSELECTs
6. **Options** - Plugin options, permissions and text
7. **Edit Profile** - User can select results based on the Admin setup
8. **Parse Code in Template** - You then parse your data.
9. **Parsed Code** - Your parsed data displayed

== Frequently Asked Questions ==
1. If you have any questions please ask at http://montanab.com/blog/wordpress-link-data-from-another-database/
2. Want to say thanks? [donate](http://www.amazon.com/gp/wishlist/NLKLKBFRBRZU/) 

== Future ToDos ==
1. Add a shortcode method to display looped data, configured by the Admin

== Changelog ==
1. 0.1 Initial Development
2. 0.1.0.2 minor text update and images
3. 0.1.0.3 fixed jQuery and jQuery UI loader and initial tab views for clean installs
4. 0.1.0.4 added uninstall code to clean the database of plugin entries
5. 0.1.0.5 added Wordpress compatibility and plugin branding
6. 0.1.0.6 updated pluging description

== Upgrade Notice ==
This update fixes the link to jQuery and jQuery UI and initial tab views for clean installs.

Want a new feature? Let me know!
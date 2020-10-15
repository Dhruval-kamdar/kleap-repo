=== Template Hero for Elementor ===
Contributors: j hanlon, muhammadfaizanhaidar
Tags: elementor, custom-library, custom-course, remote-library, custom-template, remote-template-library, custom-template-library
Requires at least: 4.6
Donate link: https://waashero.com
Tested up to: 5.5.1
Stable tag: 1.2.4
License: GPLv2 or later
Requires PHP: 5.2
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Remote Elementor Template Library.

== Description ==

Create your own unique template library for Elementor Page Builder on any remote site.

== Prerequisite: ==

- Elementor

== Features: ==

- This addon provides Remote Source Template Library functionality for Elementor.
- Users can make there own site a remote template source for other sites.
- Users can get elementor templates from other remote sites.
- Admin can manage current site urls & remote site urls.
- Admin can turn on/off remote sourcing process.

== Installation ==

#### Minimum System Requirements

Template Hero for Elementor Requires

+ PHP 5.2 or later
+ MySQL 5.6 or later
+ WordPress 4.0 or later
Before installation please make sure you have latest Elementor installed.

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

== Screenshots ==

1. Plugin settings 
2. Enable/disable plugin 
3. Delete plugin data on uninstall 
4. User can start remote sourcing by clicking this button
5. Disallow remote sourcing

== FAQ ==

**Can I use “Template Hero for Elementor” addon and any other rest api based addon simultaneously?**

Yes, you can use “Template Hero for Elementor”  addon and any other rest api based addon at the same time.

== Upgrade Notice ==
**1.0.0**
- Intial releas

== Changelog ==
**1.0.0**
- Intial release
**1.1.0**
- Removed ',' from admin/settings/class-template-hero-settings.PHP ( causing errors on specific hostings ).
- Restrcited scripts to load on all pages.
- Removed useless create-token.js code .
**1.1.2**
- Updated rest api url to access server templates.
- added delete library and get library secret js code.
- Updated namespace name to acess ajax function for library deletion. 
**1.1.3**
- Added singleton instance for main class.
- Resolved issues ( non network admin users can not access templates ).
- Added Token tab.
- Added comptiability for multiple admins sync settings.
- Removed issue ( other then network admin site libraries can not be created ).
**1.1.4**
- Added Network Activated option for multisites.
- Added singleton instance for main class.
- Resolved issues ( non network admin users can not access templates ).
- Added Token tab to menu.
- Added comptiability for multiple admins sync settings.
- Removed issue ( other then network admin site libraries can not be created ).
- Added Custom Template Library tab to default library.
- Added filters to update menu names, menu tabs names, library tab names.
- Added Select Post Types option to allow library to show on specific post types.
- Removed License Tab for multisite from subsites.
**1.1.5**
- Added multiple active libraries support.
- Added filters and actions to update admin, network & custom tab titles.
- Remved feature (Select Post Types option to allow library to show on specific post types.).
- Added support for custom categories for templates.
- Updated activate library tab's UI.
- Added settings to update plugin admin menus & custom templates tab titles. 
**1.1.6**
- Removed network wide license issue.
**1.1.7**
- Removed template preview issues.
- Removed tabs conflicts in multisite.
- Updated uninstall.php to delete data while deleting plugin.
**1.1.8**
- Added license tab for single site.
- Updated warning message in advanced tab.
**1.1.9**
- Made compatible with beaver builder addons.
- Removed deleted libraries meta properly.
- Removed api keys functions from the code.
**1.2.0**
- Updated license check while activating templates.
- Added new version notices.
- Updated rest api path for localhost
**1.2.1**
- Added functions to add default library from server plugin.
- Updated Create Library/ Create Network Library Tab.
- Now users will add token and private key provided from server plugin to add library.
- Removed allow libray creation from network menu advance tab.
- Added category filter on library page template.
- Removed categories select issue.
**1.2.2**
- Added comptability with elementor 3.0.5.( updated class-template-hero-elementor-admin.php )
- Added default category select on library page tab( updated editor.js & templates.php ).
**1.2.3**
- Removed un necessary js scripts.( pop up patch fixed )
- Updated rest call to get all templates from get to post( removed 414 error ). 
**1.2.4**
- Added check to show categories select if categories exist.
- Fixed network menu title . ( made it editable ).
- Added backward compatibility with elementor < 3 versions.
- Resolved default templates loading issue while using library in editing elementor templates.( updated editor.js )
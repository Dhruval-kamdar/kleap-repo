=== WP Ultimo: WooCommerce Integration ===
Contributors: aanduque
Requires at least: 4.5
Tested up to: 5.2.2
Requires PHP: 5.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 2.6
WC tested up to: 3.7.0

Extend your WP Ultimo payment options to allow your subscribers to use all the many available methods on your WooCommerce install!

== Description ==

WP Ultimo: WooCommerce Integration

Extend your WP Ultimo payment options to allow your subscribers to use all the many available methods on your WooCommerce install!

== Installation ==

1. Upload 'wp-ultimo-woocommerce' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in your WordPress Network Admin

== Changelog ==

Version 1.2.6 - 26/03/2020

* Fixed: Small incompatibility with newer versions of WooCommerce Subscriptions;

Version 1.2.5 - 26/08/2019

* Fixed: Error on previous release;

Version 1.2.4 - 22/08/2019

* Improved: Added option to redirect to WooCommerce checkout screen after integration immediately;

Version 1.2.3 - 26/05/2019

* Fixed: Payment email for WooCommerce disapeared in some edge cases;

Version 1.2.2 - 27/02/2019

* Added: Support to setup fees on the WooCommerce Subscription integration;

Version 1.2.1 - 17/11/2018

* Fixed: Compatibility issues with WP Ultimo version 1.9.0;

Version 1.2.0 - 10/09/2018

* Improved: New updates URL for add-ons;
* Added: Beta support to WooCommerce Subscription;

Version 1.1.2 - 11/02/2018

* Fixed: Link to Pay being generated dynamically to respond to changes to WooCommerce endpoints;
* Improved: We now force completed status for our orders when payment_completed is called to make sure our renewal hooks run when they should;

Version 1.1.1 - 24/01/2018

* Fixed: Now it also checks to see if the WooCommerce is just activated on the main site;
* Fixed: Included over-loadings to allow order creation to include taxes;

Version 1.1.0 - 04/11/2017

* Fixed: Now the label of the integration button actually changes to reflect the settings. Requires WP Ultimo 1.5.0;
* Fixed: WooCommerce Integration now works even if WooCommerce is not network active and activated only in t^he main site;

1.0.0 - Initial Release
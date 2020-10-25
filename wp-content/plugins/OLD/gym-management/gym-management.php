<?php
/*
Plugin Name: WPGYM - Gym management system
Plugin URI: http://www.mobilewebs.net/mojoomla/extend/wordpress/gym/
Description: WPGYM - Gym management system Plugin for wordpress is ideal way to manage complete gym. 
The system has different access rights for Admin, Staff Members, Accountant and Members.
Version: 35.0
Author: Mojoomla	
Author URI: http://codecanyon.net/user/dasinfomedia
Text Domain: gym_mgt
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2015  Mojoomla  (email : sales@mojoomla.com)
*/
define( 'GMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'GMS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'GMS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'GMS_CONTENT_URL',  content_url( ));
define( 'GMS_HOME_URL',  home_url( ));
require_once GMS_PLUGIN_DIR . '/settings.php';
 ?>
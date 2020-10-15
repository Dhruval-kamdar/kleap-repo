<?php
/*
Plugin Name: Admin 2020
Plugin URI: https://admintwentytwenty.com
Description: Powerful WordPress admin theme with a streamlined dashboard, Google Analytics & WooCommerce Integration, intuitive media library, dark mode and much more.
Version: 1.3.2
Author: ADMIN 2020
Text Domain: admin2020
Domain Path: /languages
Author URI: https://admintwentytwenty.com
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

////PRODUCT ID
$productid = "5ebe7e0d701f5d079480905e";

///Main Plugin Class
require plugin_dir_path( __FILE__ ) . 'admin/class-admin-2020.php';

function run_admin_2020($productid) {

	$plugin = new Admin_2020($productid);
	$plugin->run();

	return $plugin;

}

function admin2020_update($productid){


	$options = get_option( 'admin2020_settings' );

	if(!isset($options['admin2020_pluginPage_licence_key'])) return;

	$productkey = $options['admin2020_pluginPage_licence_key'];
	$domain = get_home_url();

	if($productkey == "" || !$productid || $productid == "") return;

	require plugin_dir_path( dirname( __FILE__ ) ) . 'admin-2020/admin/updates/plugin-update-checker.php';

	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://admintwentytwenty.com/validate/update.php?id='.$productid.'&k='.$productkey.'&d='.$domain,
		__FILE__, //Full path to the main plugin file or functions.php.
		'admin-2020'
	);

}


////BUILD ADMIN 2020
run_admin_2020($productid);
///CHECK FOR UPDATES
admin2020_update($productid);







/// SHOW ERRORS
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

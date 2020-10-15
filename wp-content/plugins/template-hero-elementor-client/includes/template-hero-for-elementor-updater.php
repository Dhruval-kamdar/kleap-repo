<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for checking for updates.
 */
if( !class_exists( 'THE_CL_EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	require_once TEMPLATE_HERO_ELEMENTOR_INCLUDES_DIR . 'updater/EDD_SL_Plugin_Updater.php';
}
$license_key = trim( get_option( 'template_hero_for_elemetor_license_key' ) );	
if( is_multisite() ) {
	switch_to_blog( 1 );
	$license_key = trim( get_option( 'template_hero_for_elemetor_license_key' ) );
	restore_current_blog();
}


$edd_updater = THE_CL_EDD_SL_Plugin_Updater::getInstance( TEMPLATE_HERO_ELEMENTOR_STORE_URL, TEMPLATE_HERO_ELEMENTOR_FILE, array( 
		'version' 	=> TEMPLATE_HERO_ELEMENTOR_VERSION,
		'license' 	=> $license_key, 
		'item_id'   => TEMPLATE_HERO_ELEMENTOR_ITEM_ID, 
		'author' 	=> 'J Hanlon - template_hero Media',
		'url'       => home_url(),
		'beta'      => true
	)
);

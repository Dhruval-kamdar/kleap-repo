<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for checking for updates.
 */
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/updater/EDD_SL_Plugin_Updater.php';
}	

$license_key = trim( get_option( '_the_wu_license_key_status' ) );

$edd_updater = new EDD_SL_Plugin_Updater( THE_WU_INTEGRATION_STORE_URL, THE_WU_INTEGRATION_FILE, array( 
		'version' 	=> THE_WU_INTEGRATION_VERSION,
		'license' 	=> $license_key, 
		'item_id'   => THE_WU_INTEGRATION_ITEM_ID, 
		'author' 	=> 'J Hanlon - template_hero Media',
		'url'       => home_url()
	)
);

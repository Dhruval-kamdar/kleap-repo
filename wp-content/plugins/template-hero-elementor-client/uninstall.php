<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor_Client
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
// check if it is a multisite network
$template_hero_elementor_options = get_option( 'template_hero_elementor_advance_options', array() );
$delete                          = !empty( $template_hero_elementor_options['template_hero_elementor_delete_data'] ) ? $template_hero_elementor_options['template_hero_elementor_delete_data'] : 'no';
if( $delete == 'on' ) {
	if ( is_multisite() ) {

		// get ids of all sites
		global $wpdb;
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

		foreach ( $blogids as $blog_id ) {

			switch_to_blog( $blog_id );

			///////////////////////
			# Delete Table for each site
			////////////////////////
			global $wpdb;
			$templatehero_clients = $wpdb->prefix . "templatehero_libraries";
			$wpdb->query( "DROP TABLE IF EXISTS $templatehero_clients" );
				
			delete_option( '_waashero_elementor_installed_time' );
			delete_option( 'template_hero_current_url' );
			delete_option( 'template_hero_elementor_advance_options' );
			delete_option( 'template_hero_elementor_log_options' );
			delete_option( 'template_hero_elementor_options' );
			$ids = get_option( 'active_libraries_ids' );
			foreach( $ids as $id ) {
				delete_option( 'template_hero_elementor_remote_url'.$id );
				delete_option( 'template_hero_elementor_public_key'.$id );
			}
			$ids = get_site_option( 'active_libraries_ids' );
			foreach( $ids as $id ) {
				delete_site_option( 'template_hero_elementor_remote_url'.$id );
				delete_site_option( 'template_hero_elementor_public_key'.$id );
			}
			delete_option( 'active_libraries_ids' );
			delete_option( 'active_libraries_names' );
			delete_site_option( 'active_libraries_ids' );
			delete_site_option( 'active_libraries_names' );
			delete_site_option( 'th_cl_admin_menu_title' );
            delete_site_option( 'th_cl_tab_title' );
			delete_site_option( 'th_cl_network_menu_title' );
			delete_site_option('template_hero_elementor_networkwide');
			delete_option( '_template_hero_license_key_status' );
			delete_option( '_template_hero_license_key' );
			restore_current_blog();

		}
	} else {

		////////////////////////
		# Delete Table
		////////////////////////
		global $wpdb;
		$templatehero_clients = $wpdb->prefix . "templatehero_libraries";
		$wpdb->query( "DROP TABLE IF EXISTS $templatehero_clients" );
		$ids = get_option( 'active_libraries_ids' );
		foreach( $ids as $id ) {
			delete_option( 'template_hero_elementor_remote_url'.$id );
			delete_option( 'template_hero_elementor_public_key'.$id );
		}
		delete_option( '_waashero_elementor_installed_time' );
		delete_option( 'template_hero_current_url' );
		delete_option( 'template_hero_elementor_advance_options' );
		delete_option( 'template_hero_elementor_log_options' );
		delete_option( 'template_hero_elementor_options' );
		delete_option( 'active_libraries_ids' );
		delete_option( 'active_libraries_names' );
		delete_site_option( 'th_cl_admin_menu_title' );
		delete_site_option( 'th_cl_tab_title' );
		delete_site_option( 'th_cl_network_menu_title' );
		delete_option( '_template_hero_license_key_status' );
		delete_option( '_template_hero_license_key' );
	}
}
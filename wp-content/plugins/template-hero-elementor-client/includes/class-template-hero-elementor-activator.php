<?php
/**
 * Fired during plugin activation
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 * @author     J Hanlon | Waas Hero <info@waashero.com>
 */

class Template_Hero_Elementor_Client_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.0
	 */
	public static function activate() {
		
		// check if it is a multisite network

		if ( is_multisite() ) {

			// get ids of all sites
			global $wpdb;
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
	
			foreach ( $blogids as $blog_id ) {
	
				switch_to_blog( $blog_id );
	
				global $wpdb;
				$charset_collate     = $wpdb->get_charset_collate();
				/**
				 * Create APi Table
				 */
				$templatehero_libs  = $wpdb->prefix . "templatehero_libraries";
				$sql2 = "CREATE TABLE $templatehero_libs (
					id bigint(9) NOT NULL AUTO_INCREMENT,
					user_id bigint(6) NOT NULL,
					blog_id bigint(6) NOT NULL,
					library_name varchar(800) NOT NULL,
					library_url varchar(800) NOT NULL,
					client_secret varchar(350) NOT NULL,
					client_id varchar(800) NOT NULL,
					created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY  (id)
					) $charset_collate;";
			
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $sql2 );
					
					update_option( '_waashero_elementor_installed_time', time() );
					$current_temp_url = get_site_url();
					update_option( "template_hero_current_url", $current_temp_url );
					
					restore_current_blog();
	
			}
			
		} else {
	
			global $wpdb;
			$charset_collate     = $wpdb->get_charset_collate();
				
		
			/**
			 * Create APi Table
			 */
			$templatehero_libs = $wpdb->prefix . "templatehero_libraries";
			$sql2 = "CREATE TABLE $templatehero_libs (
				id bigint(9) NOT NULL AUTO_INCREMENT,
				user_id bigint(6) NOT NULL,
				blog_id bigint(6) NOT NULL,
				library_name varchar(800) NOT NULL,
				library_url varchar(800) NOT NULL,
				client_secret varchar(350) NOT NULL,
				client_id varchar(800) NOT NULL,
				created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY  (id)
				) $charset_collate;";
		
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql2 );

			update_option( '_waashero_elementor_installed_time', time() );
			$current_temp_url = get_site_url();
			update_option( "template_hero_current_url", $current_temp_url );
		}
	}	
} //end activate()

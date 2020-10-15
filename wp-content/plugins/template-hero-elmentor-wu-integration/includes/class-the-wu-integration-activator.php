<?php

/**
 * Fired during plugin activation
 *
 * @link       www.waashero.com
 * @since      1.0.0
 *
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/includes
 * @author     J Hanlon <info@waashero.com>
 */
class The_Wu_Integration_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_site_option( 'template_hero_elementor_networkwide', 'on' );
		delete_site_option( 'active_libraries_ids' );
		delete_site_option( 'active_libraries_names' );
	}

}

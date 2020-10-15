<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.waashero.com
 * @since      1.0.0
 *
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    The_Wu_Integration
 * @subpackage The_Wu_Integration/includes
 * @author     J Hanlon <info@waashero.com>
 */
class The_Wu_Integration_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		update_site_option( 'template_hero_elementor_networkwide', 'no' );
	}

}

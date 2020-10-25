<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Template_Hero_Elementor
 * @subpackage Template_Hero_Elementor/includes
 * @author     J Hanlon | Waas Hero <info@waashero.com>
 */
class Template_Hero_Elementor_Client_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_site_transient( 'the_cl_version_info_shown' );
	}
}

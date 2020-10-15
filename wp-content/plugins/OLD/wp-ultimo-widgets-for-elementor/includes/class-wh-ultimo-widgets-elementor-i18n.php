<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       info@waashero.com
 * @since      1.0.0
 *
 * @package    Wh_Ultimo_Widgets_Elementor
 * @subpackage Wh_Ultimo_Widgets_Elementor/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wh_Ultimo_Widgets_Elementor
 * @subpackage Wh_Ultimo_Widgets_Elementor/includes
 * @author     J. Hanlon | WaaS Hero <info@waashero.com>
 */
namespace Wh_Elementor_Modules;

class Wh_Ultimo_Widgets_Elementor_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wh-ultimo-widgets-elementor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Wh_Ultimo_Widgets_Elementor
 * @subpackage Wh_Ultimo_Widgets_Elementor/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wh_Ultimo_Widgets_Elementor
 * @subpackage Wh_Ultimo_Widgets_Elementor/includes
 * @author     J. Hanlon | WaaS Hero <info@waashero.com>
 */
namespace Wh_Elementor_Modules;

class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, WH_ULTIMO_WIDGETS_URL . 'assets/css/wh-ultimo-widgets-elementor-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, WH_ULTIMO_WIDGETS_URL . 'assets/js/wh-ultimo-widgets-elementor-admin.js', array( 'jquery' ), $this->version, false );

	}
}
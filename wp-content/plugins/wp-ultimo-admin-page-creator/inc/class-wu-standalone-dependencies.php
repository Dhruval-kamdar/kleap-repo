<?php

/**
 * WU_Admin_Pages_Standalone_Dependencies
 *
 * Contains nothing on WP Ultimo addon.
 *
 * @author      WP_Ultimo
 * @category    Admin
 * @package     WP_Ultimo/Addon/Admin_Pages
 * @version     0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
} // end if;

/**
 * Loads the dependencies of the standalone version of the plugin
 *
 * @since 1.3.0
 */
class WU_Admin_Pages_Standalone_Dependencies {

	/**
	 * Makes sure we are only using one instance of the plugin
	 *
	 * @var object WU_Admin_Pages_Standalone_Dependencies
	 */
	public static $instance;

	/**
	 * Keeps the main menu page slug for later use.
	 *
	 * @var string
	 */
	public $main_menu_slug = 'wp-ultimo-admin-pages';

	/**
	 * Keeps the edit page slug for later use
	 *
	 * @var string
	 */
	public $edit_menu_slug = 'wu-edit-admin-page';

	/**
	 * Returns a single instance of this class
	 *
	 * @since 0.0.1
	 * @return WU_Admin_Pages
	 */
	public static function get_instance() {

		if (!isset(self::$instance)) {
			self::$instance = new self();
		} // end if;

		return self::$instance;

	} // end get_instance;

}  // end class WU_Admin_Pages_Standalone_Dependencies;

/**
 * Returns an instance of this class
 *
 * @since 1.1.0
 * @return WU_Admin_Pages
 */
function WU_Admin_Pages_Standalone_Dependencies() { // phpcs:ignore

	return WU_Admin_Pages_Standalone_Dependencies::get_instance();

} // end WU_Admin_Pages_Standalone_Dependencies;

// Run it
WU_Admin_Pages_Standalone_Dependencies();

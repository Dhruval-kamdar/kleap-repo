<?php
/**
 * The internationalization class of the plugin.
 *
 * @link    http://premium.wpmudev.org
 * @since   3.2.4
 *
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Controllers
 */

namespace Beehive\Core\Controllers;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

use Beehive\Core\Views\Locale;
use Beehive\Core\Utils\Abstracts\Base;

/**
 * Class I18n
 *
 * @package Beehive\Core\Controllers
 */
class I18n extends Base {

	/**
	 * Initialize the class by registering hooks.
	 *
	 * @since 3.2.4
	 *
	 * @return void
	 */
	public function init() {
		// Set text domain.
		add_action( 'init', array( $this, 'setup_locale' ) );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  3.2.0
	 * @since  3.2.4 Moved to own class.
	 * @access private
	 *
	 * @return void
	 */
	public function setup_locale() {
		load_plugin_textdomain(
			'ga_trans',
			false,
			BEEHIVE_DIR . '/languages/'
		);
	}

	/**
	 * Get the locale string to use with JS files.
	 *
	 * @param string $type String type.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function get_strings( $type ) {
		$strings = Locale::instance()->common();

		switch ( $type ) {
			case 'beehive-settings':
				// Add strings.
				$strings['settings']   = Locale::instance()->settings();
				$strings['onboarding'] = Locale::instance()->onboarding();
				break;
			case 'beehive-dashboard':
				// Add strings.
				$strings['dashboard']  = Locale::instance()->dashboard();
				$strings['settings']   = Locale::instance()->settings();
				$strings['onboarding'] = Locale::instance()->onboarding();
				break;
		}

		/**
		 * Filter to add more strings to the script specific locale vars.
		 *
		 * @param array  $strings Locale vars.
		 * @param string $type    Locale script type.
		 *
		 * @since 3.2.4
		 */
		return apply_filters( 'beehive_i18n_get_locale_scripts', $strings, $type );
	}
}

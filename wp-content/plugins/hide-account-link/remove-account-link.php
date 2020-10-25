<?php
/**
 * Plugin Name: Remove WP Ultimo Account Link & Forward to Checkout
 * Description: Uses jQuery to remove the account link on the WP Ultimo signup page and auto forward to checkout.
 * Author:      J Hanlon | Waas hero
 * License:     GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


/**
 *
 * Hook into login_enqueue_scripts
 * 
 */
add_action( 'login_enqueue_scripts', 'remove_signup_account_link_68248765' );

/**
 * Remove the account link.
 * 
 * 
 */
function remove_signup_account_link_68248765() {
	wp_enqueue_script( 'ultimo-signup-account-link', plugin_dir_url( __FILE__ ).'js/remove-account-link.js', null, null, true ); 
}
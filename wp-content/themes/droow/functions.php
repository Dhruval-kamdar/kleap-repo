<?php


define( 'DROOW_THEME_SLUG', 'droow' );

define( 'DROOW_DIRECTORY', trailingslashit( get_template_directory() ) );
define( 'DROOW_THEME_DIRECTORY', trailingslashit( get_template_directory_uri() ) );

/**
 * This walker is fully compliant with all Theme Review
 */

require_once DROOW_DIRECTORY . '/inc/classes/droow-class-nav-walker.php';
require_once DROOW_DIRECTORY . '/inc/classes/droow-class-nav-list-walker.php';
/**
 *
 * =======================================
 *          TGM Plugin Activation
 * =======================================
 * @description
 * TGM Plugin Activation is a PHP library that allows you to easily require or recommend plugins for your WordPress themes
 * (and plugins).
 * It allows your users to install,
 * update and even automatically activate plugins in singular or bulk fashion using native
 * WordPress classes, functions and interfaces.
 * You can reference bundled plugins, plugins from the WordPress Plugin Repository
 * or even plugins hosted elsewhere on the internet.
 *
 */
require_once DROOW_DIRECTORY . '/inc/classes/class-tgm-plugin-activation.php';


require_once DROOW_DIRECTORY . '/inc/classes/class-droow-walker-comment.php';


/**
 *  Registers theme support for a given feature.
 */
require_once DROOW_DIRECTORY . '/inc/function-theme-support.php';


/**
 *
 * - functions and definitions
 */


require_once DROOW_DIRECTORY . '/inc/function-helpers.php';

/**
 * Registers the script if $src provided (does NOT overwrite), and enqueues it.
 */
require_once DROOW_DIRECTORY . '/inc/function-enqueue.php';


/**
 * The Primary Page navBar
 */
require_once DROOW_DIRECTORY . '/inc/function-nav.php';

/**
 * Accepts either a string or an array and then parses that against a set of default arguments for the new sidebar. WordPress will automatically generate a sidebar ID and name based on the current number of registered sidebars if those arguments are not included.
 */
require_once DROOW_DIRECTORY . '/inc/function-widget.php';

/**
 * This hook allows you to handle your custom AJAX endpoints. The wp_ajax_ hooks follows the format "wp_ajax_$youraction", where $youraction is the 'action' field submitted to admin-ajax.php.
 */
require_once DROOW_DIRECTORY . '/inc/function-ajax.php';
//
require_once DROOW_DIRECTORY . '/inc/function-filter.php';

/**
 * Register the required plugins for this theme.
 **/
require_once DROOW_DIRECTORY . '/inc/function-required-plugins.php';


add_filter( 'comment_form_fields', function ( $fields ) {

    $comment_field = $fields[ 'comment' ];
    $cookie_field = false;
    if ( isset( $fields[ 'cookies' ] ) ) {
        $cookie_field = $fields[ 'cookies' ];
    }

    unset( $fields[ 'comment' ] );
    unset( $fields[ 'cookies' ] );
    $fields[ 'comment' ] = $comment_field;

    if ( $cookie_field ) {
        $fields[ 'cookies' ] = $cookie_field;
    }

    return $fields;
} );

/**
 * Merlin WP
 * Load only if One Click Demo Import plugin
 * is not activated
 */
if ( !class_exists( 'OCDI_Plugin' ) ) {
    require_once DROOW_DIRECTORY . '/inc/merlin/vendor/autoload.php';
    require_once DROOW_DIRECTORY . '/inc/merlin/class-merlin.php';
    require_once DROOW_DIRECTORY . '/inc/merlin/merlin-config.php';
}


require_once DROOW_DIRECTORY . '/inc/merlin/merlin-filters.php';



<?php
/**
 * buildico functions and definitions
 *
 * @package buildico
 */

/**
 * Mobile_Detect
 */
require get_template_directory() . '/inc/Mobile_Detect.php';

/**
 * Theme Options
 */
if ( function_exists( 'cs_framework_init' ) ) {
    require get_template_directory() . '/inc/theme-options.php';
    require get_template_directory() . '/inc/meta-boxes.php';
}

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom Typography
 */
require get_template_directory() . '/inc/typography.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Related Posts
 */
require get_template_directory() . '/inc/related-posts.php';

/**
 * Custom Comments file.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Plugin Activation Init
 */
require get_template_directory() . '/inc/tgm/init.php';
require get_template_directory() . '/inc/update/verify.php';

/**
 * WooCommerce
 */
if ( class_exists( 'WooCommerce' ) ) {
    require get_template_directory() . '/inc/woocommerce-config.php';
}
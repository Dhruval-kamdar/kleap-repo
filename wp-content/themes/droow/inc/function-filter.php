<?php


add_filter( 'body_class', function ( $classes ) {


    $dsn_style = droow_acf_option( 'background_color', 'dark' );
    if ( $dsn_style === 'light' ):
        $classes[] = 'light-v';
    endif;


    if ( get_theme_mod( 'effect_cursor' ) ):
        $classes[] = 'dsn-mousemove';
    endif;

    if ( get_theme_mod( 'ajax_pages' ) ):
        $classes[] = 'dsn-ajax';
    endif;

    if ( get_theme_mod( 'stop_parallax_image' , 1 ) ):
        $classes[] = 'dsn-stop-parallax-img';
    endif;

//    if ( get_theme_mod( 'ajax_menu' ) ):
//        $classes[] = 'ajax-menu';
//    endif;

    if ( get_theme_mod( 'ajax_slider' ) ):
        $classes[] = 'ajax-slider';
    endif;

    if ( get_theme_mod( 'event_smooth_scrolling' ) ):
        $classes[] = 'dsn-effect-scroll';
    endif;


    if ( droow_is_work() ):
        $classes[] = 'dsn-droow-is-work';
    endif;


    $classes[] = droow_acf_option( 'menu_type', 'hamburger-menu' );

    return $classes;


} );

/**
 *
 *  Filters the list of CSS class names for the current post.
 *
 */


add_filter( 'post_class', function ( $classes ) {

    $default = 'post';
    if ( droow_is_work() ) {
        $default = 'droow-project';
    }

    $layout = droow_type_header();

    $classes[] = 'dsn-layout-' . esc_attr( $layout[ 'type' ] );


    return $classes;
} );


if ( class_exists( 'ACF' ) ) :
//    add_filter('acf/settings/show_admin', '__return_false');
endif;


/**
 * Contact Form 7: Don't Wrap Form Fields Into </p>
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );


if ( get_theme_mod( 'ajax_pages' ) ):
// JS assets
    add_action(
        'elementor/frontend/before_enqueue_scripts', function () {
        wp_enqueue_script( 'elementor-gallery' ); // Elementor Gallery
    }
    );

// CSS assets
    add_action(
        'elementor/frontend/before_enqueue_styles', function () {
        wp_enqueue_style( 'elementor-gallery' ); // Elementor Gallery
    }
    );
endif;


/**
 * Remove Elementor welcome splash screen
 * on the initial plugin activation
 * This prevents some issues when Merlin wizard
 * installs and activates the required plugins
 */
add_action( 'init', function () {
    delete_transient( 'elementor_activation_redirect' );
} );


/*
 * Admin Logo Link
 * */

add_filter( 'login_headerurl', function () {
    return esc_url( home_url( '/' ) );
} );


add_action( 'login_head', function () {
    if ( get_theme_mod( 'admin_custom_logo' ) ) {
        $src = get_theme_mod( 'admin_custom_logo' );
        $width_logo = get_theme_mod( 'admin_width_number', 80 );
        $width_logo = ( (int)$width_logo === 0 ) ? 'auto' : $width_logo . 'px';
        $height_logo = get_theme_mod( 'admin_height_number', 80 );
        $height_logo = ( (int)$height_logo === 0 ) ? 'auto' : $height_logo . 'px';


        echo '<style type="text/css">
        h1 a { 
            background: transparent url(' . esc_url( $src ) . ') 50% 50% no-repeat !important;
            width:' . esc_attr( $width_logo ) . '!important; 
            height:' . esc_attr( $height_logo ) . '!important; 
            background-size: cover !important;
        }
    </style>';
    }
} );


do_action( 'droow_developer', array(
    'views/pages/options/Block-pages',
    'views/pages/options/option-pages',
    'views/pages/options/Droow_Widget_Loader',
    'views/pages/customizer/customizer-pages',
) );

add_filter( 'acf/load_field/name=header_style_img',
    function ( $field ) {
        if ( droow_is_work() )
            $field[ 'default_value' ] = 'project';
        return $field;
    }
);


add_filter( 'acf/load_field/name=page_layout',
    function ( $field ) {
        if ( droow_is_work() )
            $field[ 'default_value' ] = 'full';

        return $field;
    }
);

do_action( 'widget_loader' );

<?php

/**
 *  --===== wp_enqueue_scripts --=====
 * is the proper hook to use when enqueuing items
 * that are meant to appear on the front end.
 * Despite the name, it is used for enqueuing both scripts and styles.
 *
 *  Custom CSS , JS For Web
 *
 */


function droow_fonts_url()
{
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin-ext';
    $display   = 'swap';
    $fonts[]   = 'Montserrat:400,500,600,700,800|Raleway:400,500,600,700';
    if ( $fonts ) {
        $fonts_url = add_query_arg( array(
            'family'  => urlencode( implode( '|' , $fonts ) ) ,
            'subset'  => urlencode( $subsets ) ,
            'display' => urlencode( $display ) ,
        ) , '//fonts.googleapis.com/css' );
    }

    return $fonts_url;
}


add_action( 'wp_enqueue_scripts' , function ( $hook ) {

    $ver = '1.2.2';
    global $wp_version;


    wp_enqueue_style( 'droow-google-fonts' , droow_fonts_url() , array() , $ver );

    wp_enqueue_style( 'bootstrap-grid' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/bootstrap-grid.min.css' );
    wp_enqueue_style( 'fontawesome-all' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/fontawesome-all.min.css' );
    wp_enqueue_style( 'slick' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/slick.css' );
    wp_enqueue_style( 'swiper' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/swiper.min.css' );

    wp_enqueue_style( 'justifiedGallery' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/justifiedGallery.min.css' );
    wp_enqueue_style( 'magnific-popup' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/magnific-popup.css' );
    wp_enqueue_style( 'YouTubePopUp' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/youtubepopup.css' );

    if ( (float) $wp_version < 5 ) {
        wp_enqueue_style( 'droow-content' , DROOW_THEME_DIRECTORY . '/assets/css/content.min.css' );
    }

    wp_enqueue_style( 'droow-custom-style' , DROOW_THEME_DIRECTORY . '/assets/css/style.css' , array() , $ver );


    /**
     *
     *  --===== Load File Js =====--
     *
     */


    // enable reply to comments
    if ( ( !is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_enqueue_script( 'slick' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/slick.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'swiper' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/swiper.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'tweenmax' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/TweenMax.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'isotope' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/Isotope.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'justifiedGallery' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/justifiedGallery.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'popper' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/popper.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'magnific-popup' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/magnific-popup.min.js' , array( 'jquery' ) , false , true );
    wp_enqueue_script( 'YouTubePopUp' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/YouTubePopUp.js' , array( 'jquery' ) , false , true );

    wp_enqueue_script( 'smooth-scrollbar' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/smooth-scrollbar.js' , array( 'jquery' ) , false , true );

    wp_enqueue_script( 'dsn-grid' , DROOW_THEME_DIRECTORY . '/assets/js/plugins/dsn-grid.js' , array( 'jquery' ) , $ver , true );
    wp_enqueue_script( 'droow-scripts' , DROOW_THEME_DIRECTORY . '/assets/js/custom.js' , array(
        'jquery' ,
        'imagesloaded'
    ) , $ver , true );


    $Param = array(
        'queries'           => esc_url( admin_url( 'admin-ajax.php?action=droow_post_query' ) ) ,
        'hold_time'         => get_theme_mod( 'hold_time' , 0 ) ,
        'mouse_scroll_step' => get_theme_mod( 'mouse_scroll_step' , 15 ) ,
        'scroll_speed'      => get_theme_mod( 'scroll_speed' , 300 ) ,
        'cursor_drag_speed' => get_theme_mod( 'cursor_drag_speed' , 0.3 ) ,
        'map_marker_icon'   => esc_url( get_theme_mod( 'map_marker_icon' , DROOW_THEME_DIRECTORY . 'assets/img/map-marker.png' ) ) ,
        'map_zoom_level'    => get_theme_mod( 'map_zoom_level' , 12 ) ,
        'effect_aos'        => get_theme_mod( 'effect_aos' ) ,
        'map_api'           => get_theme_mod( 'map_api' ) ,
    );

    wp_localize_script( 'droow-scripts' , 'dsnParam' , $Param );

}  , 99);


/**
 *
 * Enqueue scripts for all admin pages.
 *  Custom CSS , JS For Admin
 */

add_action( 'admin_enqueue_scripts' , function ( $hook ) {

    if ( !in_array( $hook , array( 'term.php' , 'edit-tags.php' , 'post.php' , 'post-new.php' ) ) ) {
        return;
    }
    wp_enqueue_style( 'droow-google-fonts' , droow_fonts_url() , array() );
    wp_enqueue_style( 'bootstrap-grid' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/bootstrap-grid.min.css' );
    wp_enqueue_style( 'fontawesome-all' , DROOW_THEME_DIRECTORY . '/assets/css/plugins/fontawesome-all.min.css' );
    wp_enqueue_style( 'droow-custom-main' , DROOW_THEME_DIRECTORY . '/assets/css/admin/main.css' );
} , 20 );

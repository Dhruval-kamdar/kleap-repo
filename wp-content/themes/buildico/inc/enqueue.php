<?php
/**
 * buildico enqueue scripts
 *
 * @package buildico
 */

$detect = new Mobile_Detect;

 // Google Font Function
function buildico_google_fonts_url() {

	$fonts_url = '';

	$montserrat = _x( 'on', 'Montserrat font: on or off', 'buildico' );

	$open_sans = _x( 'on', 'Open Sans font: on or off', 'buildico' );

	if ( 'off' !== $montserrat || 'off' !== $open_sans ) {
		$font_families = array();

		if ( 'off' !== $montserrat ) {
			$font_families[] = 'Montserrat:400,600,700,800,900';
		}

		if ( 'off' !== $open_sans ) {
			$font_families[] = 'Open Sans:400,700,900';
		}

		$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}


if ( ! function_exists( 'buildico_scripts' ) ) {

	function buildico_scripts() {
		// Get the theme data.
		$the_theme = wp_get_theme();
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'font-awesome-icons', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'elegant-line-icons', get_template_directory_uri() . '/assets/css/elegant-line-icons.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/assets/css/themify-icons.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'slicknav', get_template_directory_uri() . '/assets/css/slicknav.min.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'swipebox', get_template_directory_uri() . '/assets/css/swipebox.min.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'wt-cons-icon', get_template_directory_uri() . '/assets/css/wt-construction-icon.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'buildico-default', get_template_directory_uri() . '/assets/css/default-styles.css', array(), $the_theme->get( 'Version' ), 'all' );
		wp_enqueue_style( 'buildico-wp-blocks', get_template_directory_uri() . '/assets/css/wp-blocks.css', array(), $the_theme->get( 'Version' ), 'all' );
		if( class_exists('WooCommerce') ){
			wp_enqueue_style( 'buildico-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), $the_theme->get( 'Version' ), 'all' );
		}
		// Style CSS
        wp_enqueue_style( 'buildico-stylesheet', get_stylesheet_uri() );
        wp_enqueue_style( 'buildico-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), $the_theme->get( 'Version' ), 'all' );
        // Google Fonts
		wp_enqueue_style( 'google-fonts', buildico_google_fonts_url(), array(), null );
		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'popper', get_template_directory_uri() . '/assets/js/popper.min.js', array(), true);
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), $the_theme->get( 'Version' ), true );
		wp_enqueue_script( 'imageloaded', get_template_directory_uri() . '/assets/js/imageloaded.min.js', array('jquery'), $the_theme->get( 'Version' ), true );
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/js/isotope.min.js', array('jquery'), $the_theme->get( 'Version' ), true );
		if( wt_get_option( 'scrollbar_enable' ) === true ){
			wp_enqueue_script( 'nicescroll', get_template_directory_uri() . '/assets/js/jquery.nicescroll.min.js', array('jquery'), $the_theme->get( 'Version' ), true );
		}
		wp_enqueue_script( 'slicknav', get_template_directory_uri() . '/assets/js/jquery.slicknav.min.js', array(), $the_theme->get( 'Version' ), false );
		wp_enqueue_script( 'swipebox', get_template_directory_uri() . '/assets/js/jquery.swipebox.min.js', array(), $the_theme->get( 'Version' ), true );
		wp_enqueue_script( 'buildico-custom-scripts', get_template_directory_uri() . '/assets/js/custom.js', array(), $the_theme->get( 'Version' ), true );
        wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr-2.8.3.min.js', array(), '2.8.3', false );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // endif function_exists( 'buildico_scripts' ).

add_action('wp_enqueue_scripts', 'buildico_scripts');

if( wt_get_option( 'mobile_disable_scrollbar' ) === true ){
	$mobile = !$detect->isMobile();
}else{
	$mobile = '';
}

if( wt_get_option( 'scrollbar_enable' ) === true ) :

	if( !function_exists('buildico_nicescroll_active') ){

		function buildico_nicescroll_active(){
			$nicescroll_active = '
				(function($){
					"use strict";
					$("html").niceScroll({
						background: "'. esc_html( wt_get_option('scrollbar_bg') ) .'",
						cursorcolor:"'. esc_html( wt_get_option('cursor_color') ) .'",
						cursorwidth:"'. esc_html( wt_get_option('cursor_width') ) .'px",
				        scrollspeed: '. esc_html( wt_get_option('scroll_speed') ) .',
				        mousescrollstep: '. esc_html( wt_get_option('mouse_scroll_step') ) .',
						cursorborder:"'. esc_html( wt_get_option('cursor_border') ) .'",
						cursorborderradius: "'. esc_html( wt_get_option('cursor_border_radius') ) .'px",
						autohidemode: "'. esc_html( wt_get_option('autohide_mode') ) .'",
						zindex: "'. esc_html( wt_get_option('scrollbar_zindex') ) .'"
					});
				})(jQuery);
			';

			wp_add_inline_script( 'buildico-custom-scripts', $nicescroll_active );
		}
	}

	if( wt_get_option( 'mobile_disable_scrollbar' ) === true ){
		add_action('wp_enqueue_scripts', 'buildico_nicescroll_active');
	}else{
		if( !$detect->isMobile() ){
			add_action('wp_enqueue_scripts', 'buildico_nicescroll_active');
		}
	}

endif;

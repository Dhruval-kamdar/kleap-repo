<?php
/**
 * Theme basic setup.
 *
 * @package buildico
 */


// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'buildico_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function buildico_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on buildico, use a find and replace
		 * to change 'buildico' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'buildico', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// WooCommerce support
		add_theme_support( 'woocommerce' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'buildico' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Adding Thumbnail basic support
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Adding Thumbnail Size
		 */
        add_image_size( '850x420-large', 850, 420, true );
        add_image_size( '380x220-blog', 380, 220, true );
		add_image_size( '1900x1200-slider', 1900, 1200, true );


		/*
		 * Adding support for Widget edit icons in customizer
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'buildico_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Set up the WordPress Theme logo feature.
		if( ! empty( wt_get_option('crop_width') && wt_get_option('crop_height') ) ){
			$logo_defaults = array(
		        'height'      => esc_html(wt_get_option('crop_height')),
		        'width'       => esc_html(wt_get_option('crop_width')),
		    );
			add_image_size( '180x40-logo', esc_html( wt_get_option('crop_width') ), esc_html(wt_get_option('crop_height')), true );
		}else{
			$logo_defaults = array(
		        'height'      => 40,
		        'width'       => 180,
		    );
			add_image_size( '180x40-logo', 180, 40, true );
		}

		add_theme_support( 'custom-logo', $logo_defaults );

		add_theme_support( 'custom-header', apply_filters( 'buildico_custom_header_args', array(
			'width'              => 2000,
			'height'             => 1200,
			'flex-height'        => true,
		) ) );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( buildico_google_fonts_url() );
		add_editor_style( 'style-editor.css' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'buildico' ),
					'shortName' => __( 'S', 'buildico' ),
					'size'      => 12,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'buildico' ),
					'shortName' => __( 'M', 'buildico' ),
					'size'      => 14,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'buildico' ),
					'shortName' => __( 'L', 'buildico' ),
					'size'      => 25,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'buildico' ),
					'shortName' => __( 'XL', 'buildico' ),
					'size'      => 55,
					'slug'      => 'huge',
				),
			)
		);

		// Editor color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary', 'buildico' ),
					'slug'  => 'primary',
					'color' => ! empty( wt_get_option('primary_color') ) ? wt_get_option('primary_color') : '#fab702',
				),
				array(
					'name'  => __( 'Dark Gray', 'buildico' ),
					'slug'  => 'dark-gray',
					'color' => '#222',
				),
				array(
					'name'  => __( 'Light Gray', 'buildico' ),
					'slug'  => 'light-gray',
					'color' => '#555',
				),
				array(
					'name'  => __( 'White', 'buildico' ),
					'slug'  => 'white',
					'color' => '#FFF',
				),
			)
		);

	}
endif; // buildico_setup.
add_action( 'after_setup_theme', 'buildico_setup' );

if ( ! function_exists( 'buildico_custom_excerpt_more' ) ) {
	/**
	 * Removes the ... from the excerpt read more link
	 *
	 * @param string $more The excerpt.
	 *
	 * @return string
	 */
	function buildico_custom_excerpt_more( $more ) {
		return '';
	}
}
add_filter( 'excerpt_more', 'buildico_custom_excerpt_more' );

// Excerpt Lenth
if ( ! function_exists( 'excerpt' ) ) {

	function excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if ( count( $excerpt ) >= $limit ) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		return $excerpt;
	}
}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Buildico
 */
function buildico_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'buildico_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function buildico_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'buildico_pingback_header' );

/**
 * CSF Get Options
 */
if ( ! function_exists( 'wt_get_option' ) ) {
	function wt_get_option( $option_name = '', $default = '' ) {
	    defined( 'CS_OPTION') || define( 'CS_OPTION', '_cs_options' );
	    $options = apply_filters( 'wt_get_option', get_option( CS_OPTION ), $option_name, $default );
	    if( ! empty( $option_name ) && ! empty( $options[$option_name] ) ) {
	        return $options[$option_name];
	    } else {
	        return ( ! empty( $default ) ) ? $default : null;
	    }
	}
}

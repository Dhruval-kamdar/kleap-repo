<?php
/** De-register Contact Form 7 styles. */

add_filter(
	'wpcf7_load_js',
	function() {
		return apply_filters( 'thb_wpcf7_assets', false );
	}
);
add_filter(
	'wpcf7_load_css',
	function() {
		return apply_filters( 'thb_wpcf7_assets', false );
	}
);

/** Main Styles. */
function thb_main_styles() {
	global $post;

	$i                 = 0;
	$self_hosted_fonts = ot_get_option( 'self_hosted_fonts' );
	$thb_theme_version = Thb_Theme_Admin::$thb_theme_version;

	// Enqueue.
	wp_enqueue_style( 'thb-fa', esc_url( get_theme_file_uri( 'assets/css/font-awesome.min.css' ) ), null, esc_attr( $thb_theme_version ) );
	wp_enqueue_style( 'thb-app', esc_url( get_theme_file_uri( 'assets/css/app.css' ) ), null, esc_attr( $thb_theme_version ) );

	wp_dequeue_style( 'vc_lte_ie9' );
	wp_deregister_style( 'vc_lte_ie9' );

	if ( ! defined( 'THB_DEMO_SITE' ) ) {
		wp_enqueue_style( 'thb-style', get_stylesheet_uri(), null, esc_attr( $thb_theme_version ) );
	}
	wp_enqueue_style( 'thb-google-fonts', thb_google_webfont(), null, esc_attr( $thb_theme_version ) );
	wp_add_inline_style( 'thb-app', thb_selection() );

	// Self Hosted Fonts Css.
	if ( $self_hosted_fonts ) {
		foreach ( $self_hosted_fonts as $font ) {
			$i++;
			wp_enqueue_style( 'thb-self-hosted-' . $i, $font['font_url'], null, esc_attr( $thb_theme_version ) );
		}
	}

	if ( $post ) {
		if ( has_shortcode( $post->post_content, 'contact-form-7' ) && function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
	}
	// Remove Dynamic front-end CSS.
	remove_action( 'wp_enqueue_scripts', 'ot_load_dynamic_css', 999 );
}
add_action( 'wp_enqueue_scripts', 'thb_main_styles' );

/** Main Scripts. */
function thb_register_js() {
	if ( ! is_admin() ) {
		global $post;
		$thb_combined_libraries  = ot_get_option( 'thb_combined_libraries', 'on' );
		$thb_api_key             = ot_get_option( 'map_api_key' );
		$thb_dependency          = array( 'jquery', 'underscore' );
		$thb_theme_directory_uri = Thb_Theme_Admin::$thb_theme_directory_uri;
		$thb_theme_version       = Thb_Theme_Admin::$thb_theme_version;

		// Register.
		wp_register_script( 'gmapdep', 'https://maps.google.com/maps/api/js?key=' . esc_attr( $thb_api_key ), false, esc_attr( $thb_theme_version ), false );
		if ( 'on' === $thb_combined_libraries ) {
			wp_enqueue_script( 'thb-vendor', esc_url( get_theme_file_uri( 'assets/js/vendor.min.js' ) ), array( 'jquery' ), esc_attr( $thb_theme_version ), true );
			$thb_dependency[] = 'thb-vendor';
		} else {
			$thb_js_libraries = array(
				'GSAP'                 => '_0gsap.min.js',
				'GSAP-DrawSVGPlugin'   => '_1DrawSVGPlugin.min.js',
				'GSAP-SplitText'       => '_1SplitText.min.js',
				'GSAP-ScrollToPlugin'  => '_2ScrollToPlugin.min.js',
				'animsition'           => 'animsition.js',
				'bezier-easing'        => 'bezier-easing.js',
				'clipboard'            => 'clipboard.min.js',
				'flickity'             => 'flickity.pkgd.min.js',
				'headroom'             => 'headroom.min.js',
				'howler'               => 'howler.core.min.js',
				'imagesloaded'         => 'imagesloaded.pkgd.min.js',
				'isInViewport'         => 'isInViewport.min.js',
				'isotope'              => 'isotope.pkgd.min.js',
				'isotope-packery-mode' => 'packery-mode.pkgd.js',
				'jquery-downcount'     => 'jquery.downCount.js',
				'jquery-headroom'      => 'jquery.headroom.js',
				'jquery-hoverIntent'   => 'jquery.hoverIntent.js',
				'magnific-popup'       => 'jquery.magnific-popup.min.js',
				'panr'                 => 'jquery.panr.js',
				'video'                => 'jquery.vide.js',
				'js-cookie'            => 'js.cookie.js',
				'lazysizes'            => 'lazysizes.min.js',
				'mobile-detect'        => 'mobile-detect.min.js',
				'odometer'             => 'odometer.min.js',
				'perfect-scrollbar'    => 'perfect-scrollbar.jquery.min.js',
				'PreventGhostClick'    => 'PreventGhostClick.js',
				'raf'                  => 'raf.js',
				'scrollspy'            => 'scrollspy.js',
				'select2'              => 'select2.min.js',
				'slick'                => 'slick.min.js',
				'sticky-kit'           => 'sticky-kit.min.js',
				'thb-3dImg'            => 'thb_3dImg.js',
				'typed'                => 'typed.min.js',
			);
			foreach ( $thb_js_libraries as $handle => $value ) {
				wp_enqueue_script( $handle, esc_url( get_theme_file_uri( 'assets/js/vendor/' . esc_attr( $value ) ) ), array( 'jquery' ), esc_attr( $thb_theme_version ), true );
			}
		}
		wp_enqueue_script( 'underscore' );
		wp_enqueue_script( 'thb-app', esc_url( get_theme_file_uri( 'assets/js/app.min.js' ) ), $thb_dependency, esc_attr( $thb_theme_version ), true );

		if ( is_singular() && comments_open() && ( get_option( 'thread_comments' ) === 1 ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		$footer_top_content = apply_filters( 'thb_footer_page_content', ot_get_option( 'footer_top_content' ) );
		$footer_post        = get_post( $footer_top_content );

		if ( $post || $footer_post ) {
			if (
				has_shortcode( $post->post_content, 'thb_map_parent' ) ||
				has_shortcode( $post->post_content, 'thb_location_parent' ) ||
				has_shortcode( $footer_post->post_content, 'thb_map_parent' ) ||
				has_shortcode( $footer_post->post_content, 'thb_location_parent' )
			) {
				wp_enqueue_script( 'gmapdep' );
			}

			if ( has_shortcode( $post->post_content, 'contact-form-7' ) && function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();
			}
		}

		// Typekit.
		$typekit_id = ot_get_option( 'typekit_id' );
		if ( $typekit_id ) {
			wp_enqueue_script( 'thb-typekit', 'https://use.typekit.net/' . $typekit_id . '.js', array(), esc_attr( $thb_theme_version ), false );
			wp_add_inline_script( 'thb-typekit', 'try{Typekit.load({ async: true });}catch(e){}' );
		}

		wp_localize_script(
			'thb-app',
			'themeajax',
			array(
				'url'      => admin_url( 'admin-ajax.php' ),
				'l10n'     => array(
					'of'               => esc_html__( '%curr% of %total%', 'revolution' ),
					'loading'          => esc_html__( 'Loading', 'revolution' ),
					'lightbox_loading' => esc_html__( 'Loading...', 'revolution' ),
					'nomore'           => esc_html__( 'No More Posts', 'revolution' ),
					'nomore_products'  => esc_html__( 'All Products Loaded', 'revolution' ),
					'loadmore'         => esc_html__( 'Load More', 'revolution' ),
					'added'            => esc_html__( 'Added To Cart', 'revolution' ),
					'copied'           => esc_html__( 'Copied', 'revolution' ),
					'prev'             => esc_html__( 'Prev', 'revolution' ),
					'next'             => esc_html__( 'Next', 'revolution' ),
					'prev_arrow_key'   => esc_html__( 'Previous (Left arrow key)', 'revolution' ),
					'next_arrow_key'   => esc_html__( 'Next (Right arrow key)', 'revolution' ),
					'lightbox_close'   => esc_html__( 'Close (Esc)', 'revolution' ),
					'adding_to_cart'   => esc_html__( 'Adding to Cart', 'revolution' ),
				),
				'svg'      => array(
					'prev_arrow'  => thb_load_template_part( 'assets/img/svg/prev_arrow.svg' ),
					'next_arrow'  => thb_load_template_part( 'assets/img/svg/next_arrow.svg' ),
					'added_arrow' => thb_load_template_part( 'assets/svg/arrows_check.svg' ),
				),
				'settings' => array(
					'current_url'                     => get_permalink(),
					'fixed_header_scroll'             => ot_get_option( 'fixed_header_scroll', 'on' ),
					'fixed_header_padding'            => ot_get_option( 'header_padding_fixed' ),
					'page_transition'                 => ot_get_option( 'page_transition', 'on' ),
					'newsletter'                      => ot_get_option( 'newsletter', 'on' ),
					'newsletter_length'               => ot_get_option( 'newsletter-interval', '1' ),
					'newsletter_delay'                => ot_get_option( 'newsletter_delay', '0' ),
					'page_transition_style'           => ot_get_option( 'page_transition_style', 'thb-fade' ),
					'page_transition_in_speed'        => ot_get_option( 'page_transition_in_speed', 1000 ),
					'page_transition_out_speed'       => ot_get_option( 'page_transition_out_speed', 500 ),
					'shop_product_listing_pagination' => ot_get_option( 'shop_product_listing_pagination', 'style1' ),
					'right_click'                     => ot_get_option( 'right_click', 'on' ),
					'cart_url'                        => thb_wc_supported() ? wc_get_cart_url() : false,
					'is_cart'                         => thb_wc_supported() ? is_cart() : false,
					'is_checkout'                     => thb_wc_supported() ? is_checkout() : false,
					'accessibility'                   => apply_filters( 'revolution_accessibility', false ),
					'touch_threshold'                 => apply_filters( 'revolution_touchthreshold', 5 ),
					'lightbox_fixedcontent'           => apply_filters( 'revolution_lightbox_fixedcontent', false ),
					'mobile_menu_breakpoint'          => apply_filters( 'revolution_mobilemenu_breakpoint', 1200 ),
				),
				'sounds'   => array(
					'music_sound'             => ot_get_option( 'music_sound', 'off' ),
					'music_disable_mobile'    => ot_get_option( 'music_disable_mobile', 'off' ),
					'music_sound_toggle_home' => ot_get_option( 'music_sound_toggle_home', 'off' ),
					'music_sound_file'        => ot_get_option( 'music_sound_file', esc_url( $thb_theme_directory_uri ) . 'assets/sounds/music_sound.mp3' ),
					'link_hover_sound'        => ot_get_option( 'link_hover_sound', 'off' ),
					'link_hover_sound_file'   => ot_get_option( 'link_hover_sound_file', esc_url( $thb_theme_directory_uri ) . 'assets/sounds/hover.mp3' ),
					'click_sound'             => ot_get_option( 'click_sound', 'off' ),
					'click_sound_file'        => ot_get_option( 'click_sound_file', esc_url( $thb_theme_directory_uri ) . 'assets/sounds/click.mp3' ),
				),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'thb_register_js' );

/** WooCommerce. */
function thb_woocommerce_scripts_styles() {

	if ( ! is_admin() ) {
		if ( thb_wc_supported() ) {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_deregister_style( 'woocommerce_prettyPhoto_css' );

			wp_dequeue_style( 'yith-wcwl-font-awesome' );
			wp_deregister_style( 'yith-wcwl-font-awesome' );

			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );

			wp_dequeue_style( 'jquery-selectBox' );
			wp_dequeue_script( 'jquery-selectBox' );

			wp_dequeue_script( 'vc_woocommerce-add-to-cart-js' );
			if ( ! is_checkout() ) {
				wp_dequeue_script( 'jquery-selectBox' );
				wp_dequeue_style( 'selectWoo' );
				wp_dequeue_script( 'selectWoo' );
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'thb_woocommerce_scripts_styles', 98 );
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

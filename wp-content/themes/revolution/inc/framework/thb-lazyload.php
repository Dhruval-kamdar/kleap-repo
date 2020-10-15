<?php

/** Adding Lazyload Class */
function thb_add_lazy_class( $html = '', $new_class ) {
	if ( in_array( ot_get_option( 'thb_preload_type', 'preloader' ), array( 'lazyload', 'preloadlazyload' ), true ) ) {
		$pattern = '/class="([^"]*)"/';
		// Class attribute set.
		if ( preg_match( $pattern, $html, $matches ) ) {
			$predefined_classes = explode( ' ', $matches[1] );
			if ( ! in_array( $new_class, $predefined_classes, true ) && ! in_array( 'rev-slidebg', $predefined_classes, true ) && ! in_array( 'tp-bgimg', $predefined_classes, true ) ) {
				$predefined_classes[] = $new_class;
				$html                 = str_replace(
					$matches[0],
					sprintf( 'class="%s"', implode( ' ', $predefined_classes ) ),
					$html
				);
			}
		} else {
			$html = preg_replace( '/(\<.+\s)/', sprintf( '$1class="%s" ', $new_class ), $html );
		}
	}
	return $html;
}

/** Filter Images */
function thb_lazy_images_filter( $content ) {
	if ( in_array( ot_get_option( 'thb_preload_type', 'preloader' ), array( 'lazyload', 'preloadlazyload' ), true ) ) {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return $content;
		}
		$user_agent = null;
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = wp_unslash( $_SERVER['HTTP_USER_AGENT'] );
		}
		if ( is_feed()
			|| intval( get_query_var( 'print' ) ) === 1
			|| intval( get_query_var( 'printpage' ) ) === 1
			|| strpos( $user_agent, 'Opera Mini' ) !== false
		) {
			return $content;
		}

		$matches            = array();
		$resp_replace       = 'data-sizes="auto" data-srcset=';
		$skip_images_regex  = '/class=".*lazyload.*"/';
		$skip_images_regex2 = '/class=".*rev-slidebg.*"/';
		$skip_images_regex3 = '/class=".*tp-bgimg.*"/';
		$placeholder        = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
		preg_match_all( '/<img\s+.*?>/', $content, $matches );

		$search  = array();
		$replace = array();

		foreach ( $matches[0] as $img_html ) {
			// Don't to the replacement if a skip class is provided and the image has the class.
			if ( ! ( preg_match( $skip_images_regex, $img_html ) ) && ! ( preg_match( $skip_images_regex2, $img_html ) ) && ! ( preg_match( $skip_images_regex3, $img_html ) ) ) {

				$replace_html = preg_replace( '/<img(.*?)src=/i', '<img$1src="' . $placeholder . '" data-src=', $img_html );
				$replace_html = preg_replace( '/srcset=/i', $resp_replace, $replace_html );

				$replace_html = thb_add_lazy_class( $replace_html, 'lazyload' );

				array_push( $search, $img_html );
				array_push( $replace, $replace_html );
			}
		}
		// End foreach.

		$content = str_replace( $search, $replace, $content );
	}
	return $content;
}

/** Change source to low quality */
function thb_lazy_low_quality( $attr, $attachment, $size ) {
	if ( in_array( ot_get_option( 'thb_preload_type', 'preloader' ), array( 'lazyload', 'preloadlazyload' ), true ) ) {
		$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';

		// Low Quality Image Placeholders.
		if ( ! is_string( $size ) ) {
			return $attr;
		}
		$name = explode( '-', $size );
		if ( 'revolution' === $name[0] ) {
			$name[2]     = 'mini';
			$size        = implode( '-', $name );
			$placeholder = wp_get_attachment_image_src( $attachment->ID, $size );
			$placeholder = $placeholder[0];
		} else {
			if ( strpos( $attr['class'], 'thb-ignore-lazyload' ) !== false ) {
				$placeholder = $attr['src'];
			}
		}
		// Lazy Sizes.
		$attr['data-src']   = $attr['src'];
		$attr['data-sizes'] = 'auto';
		$attr['src']        = $placeholder;
		$attr['class']     .= ' thb-lazyload lazyload';

		// Set Src Set.
		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
			unset( $attr['srcset'] );
		}
	}
	return $attr;
}

/*
 * Filters
 */
if ( ! is_admin() ) {
	add_filter( 'the_content', 'thb_lazy_images_filter', 200 );
	add_filter( 'wp_get_attachment_image_attributes', 'thb_lazy_low_quality', 10, 3 );
}

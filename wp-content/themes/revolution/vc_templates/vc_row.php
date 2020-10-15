<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$el_class        = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = '';
$disable_element = $thb_scroll_to_bottom = $thb_color = $thb_video_bg = $bg_video_overlay = $thb_scroll_bottom_style = '';
$output          = $after_output = '';
$atts            = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class                = $this->getExtraClass( $el_class );
$thb_column_padding      = $thb_column_padding ? 'no-column-padding' : '';
$thb_row_padding         = $thb_row_padding ? 'no-row-padding' : '';
$thb_full_width          = $thb_full_width ? 'full-width-row' : '';
$thb_scroll_bottom_class = 'true' === $thb_scroll_bottom ? 'thb-arrow-enabled' : '';
$thb_shape_divider_class = 'true' === $thb_shape_divider ? 'thb-divider-enabled' : '';
$thb_overlay_class       = $bg_gradient1 || $bg_gradient2 ? 'thb-overlay-enabled' : '';
$css_classes             = array(
	'vc_row',
	'wpb_row', // deprecated
	'vc_row-fluid',
	$box_shadow,
	$el_class,
	$thb_row_padding,
	$thb_full_width,
	$thb_video_play_button,
	$thb_column_padding,
	$thb_scroll_bottom_class,
	$thb_shape_divider_class,
	$thb_overlay_class,
	vc_shortcode_custom_css_class( $css ),
);


if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array( 'border', 'background' ) ) || $video_bg || $parallax ) {
	$css_classes[] = 'vc_row-has-fill';
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[]        = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row      = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row      = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row      = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax       = $video_bg_parallax;
	$parallax_speed = $parallax_speed_video;
	$parallax_image = $video_bg_url;
	$css_classes[]  = 'vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[]        = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[]        = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id  = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

/* Video BG */
if ( '' !== $thb_video_bg ) {
	$bg_type    = '';
	$video_type = wp_check_filetype( $thb_video_bg, wp_get_mime_types() );
	$pattern    = '/background-image:\s*url\(\s*([\'"]*)(?P<file>[^\1]+)\1\s*\)/i';
	preg_match( $pattern, $css, $bg_image );

	$wrapper_attributes[] = 'data-vide-bg="' . $video_type['ext'] . ': ' . esc_attr( $thb_video_bg ) . ( isset( $bg_image[2] ) ? ', poster: ' . esc_attr( $bg_image[2] ) : '' ) . '"';

	if ( isset( $bg_image[2] ) ) {
		$bg_url  = strtok( $bg_image[2], '?' );
		$bg_type = wp_check_filetype( $bg_url, wp_get_mime_types() );
	}

	$wrapper_attributes[] = 'data-vide-options="posterType: ' . ( isset( $bg_image[2] ) ? esc_attr( $bg_type['ext'] ) : 'none' ) . ', loop: true, muted: true, position: 50% 50%, resizing: true' . ( $thb_video_play_button ? ', autoplay:false' : '' ) . '"';
	if ( '' !== $thb_video_overlay_color ) {
		$bg_video_overlay = '<div class="thb_video_overlay" style="background-color: ' . esc_attr( $thb_video_overlay_color ) . ';"></div>';
	}

	$css_classes[] = 'thb_video_bg';
}

$css_class            = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

if ( $thb_border_radius ) {
	$wrapper_attributes[] = 'style="border-radius:' . esc_attr( $thb_border_radius ) . '"';
}

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= wpb_js_remove_wpautop( $content );
$output .= $bg_video_overlay;
if ( 'true' === $thb_scroll_bottom ) {
	$output .= '<div class="scroll-bottom ' . esc_attr( $thb_scroll_bottom_color . ' ' . $thb_scroll_bottom_style ) . '"><div></div></div>';
}
if ( 'true' === $thb_shape_divider ) {
	$divider_id         = uniqid();
	$thb_divider_height = empty( $thb_divider_height ) ? '50px' : $thb_divider_height;
	$pattern            = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
	// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
	$regexr               = preg_match( $pattern, $thb_divider_height, $matches );
	$value                = isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'row', 'thb_divider_height' );
	$unit                 = isset( $matches[2] ) ? $matches[2] : 'px';
	$thb_divider_height   = $value . $unit;
	$thb_divider_position = empty( $thb_divider_position ) ? 'bottom' : $thb_divider_position;
	$thb_divider_shape    = thb_load_template_part( 'assets/img/svg/dividers/' . $divider_shape . '.svg' );

	$output .= '<div id="thb-divider-id-' . esc_attr( $divider_id ) . '-first" class="thb-divider-container ' . esc_attr( $divider_shape ) . '" style="height: ' . esc_attr( $thb_divider_height ) . ';" data-position="' . esc_attr( $thb_divider_position ) . '">' . $thb_divider_shape . '</div>';
	if ( 'both' === $thb_divider_position ) {
		$output .= '<div id="thb-divider-id-' . esc_attr( $divider_id ) . '-second" class="thb-divider-container ' . esc_attr( $divider_shape ) . ' second" style="height: ' . esc_attr( $thb_divider_height ) . ';" data-position="' . esc_attr( $thb_divider_position ) . '">' . $thb_divider_shape . '</div>';
	}
	if ( $thb_divider_color ) {
		$output .= '<style>
			#thb-divider-id-' . esc_attr( $divider_id ) . '-first .thb-svg-divider {
				fill: ' . esc_attr( $thb_divider_color ) . ';
			}';
		if ( 'both' === $thb_divider_position ) {
			$output .= '
				#thb-divider-id-' . esc_attr( $divider_id ) . '-second .thb-svg-divider {
					fill: ' . esc_attr( $thb_divider_color_2 ) . ';
				}';
		}
		$output .= '</style>';
	}
}
if ( $bg_gradient1 && $bg_gradient2 ) {
	$overlay_id = uniqid( 'thb-row-overlay-' );
	$output    .= '<div id="' . esc_attr( $overlay_id ) . '" class="thb-row-overlay"></div>';
	$output    .= '<style>
  	#' . $overlay_id . ' {' . thb_css_gradient( $bg_gradient1, $bg_gradient2, $bg_gradient_direction, true ) . ' }</style>';
}
$output .= '</div>';
$output .= $after_output;
echo '' . $output;

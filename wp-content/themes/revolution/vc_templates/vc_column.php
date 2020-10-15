<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $el_class
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column
 */
$el_class              = $width = $css = $offset = $animation = $thb_color = '';
$el_class              = $el_id = $width = $parallax_speed_bg = $parallax_speed_video = $parallax = $parallax_image = $video_bg = $video_bg_url = $video_bg_parallax = $css = $offset = $css_animation = $thb_video_bg = $bg_video_overlay = '';
$output                = '';
$thb_video_play_button = false;
$atts                  = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ),
	'wpb_column',
	'vc_column_container',
	$animation,
	$width,
	$thb_color,
);

if ( vc_shortcode_custom_css_has_property(
	$css,
	array(
		'border',
		'background',
	)
) || $video_bg || $parallax
) {
	$css_classes[] = 'vc_col-has-fill';
}

$wrapper_attributes = array();

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
if ( $thb_video_bg !== '' ) {
	$bg_type    = '';
	$video_type = wp_check_filetype( $thb_video_bg, wp_get_mime_types() );
	$pattern    = '/background-image:\s*url\(\s*([\'"]*)(?P<file>[^\1]+)\1\s*\)/i';
	preg_match( $pattern, $css, $bg_image );

	$inner_attributes[] = 'data-vide-bg="' . $video_type['ext'] . ': ' . esc_attr( $thb_video_bg ) . ( isset( $bg_image[2] ) ? ', poster: ' . esc_attr( $bg_image[2] ) : '' ) . '"';

	if ( isset( $bg_image[2] ) ) {
		$bg_url  = strtok( $bg_image[2], '?' );
		$bg_type = wp_check_filetype( $bg_url, wp_get_mime_types() );
	}

	$inner_attributes[] = 'data-vide-options="posterType: ' . ( isset( $bg_image[2] ) ? esc_attr( $bg_type['ext'] ) : 'none' ) . ', loop: true, muted: true, position: 50% 50%, resizing: true' . ( $thb_video_play_button ? ', autoplay:false' : '' ) . '"';
	if ( $thb_video_overlay_color != '' ) {
		$bg_video_overlay = '<div class="thb_video_overlay" style="background-color: ' . esc_attr( $thb_video_overlay_color ) . ';"></div>';
	}

	$css_classes[] = 'thb_video_bg';
}

$css_class            = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$inner_attributes[] = 'class="vc_column-inner ' . esc_attr( $box_shadow ) . ' ' . esc_attr( $fixed ) . ' ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '"';

if ( $thb_border_radius ) {
	$inner_attributes[] = 'style="border-radius:' . esc_attr( $thb_border_radius ) . '"';
}

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= '<div ' . implode( ' ', $inner_attributes ) . '>';
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= $bg_video_overlay;
$output .= '</div>';
$output .= '</div>';

echo '' . $output;

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
 * @var $this WPBakeryShortCode_VC_Column_Inner
 */
$el_class = $width = $css = $offset = $animation = $thb_color = '';
$output   = '';
$atts     = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

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
) ) {
	$css_classes[] = 'vc_col-has-fill';
}

$wrapper_attributes = array();

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
$output .= '</div>';
$output .= '</div>';

echo '' . $output;

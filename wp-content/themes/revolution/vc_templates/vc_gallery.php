<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $title
 * @var $source
 * @var $type
 * @var $onclick
 * @var $custom_links
 * @var $custom_links_target
 * @var $img_size
 * @var $external_img_size
 * @var $images
 * @var $custom_srcs
 * @var $el_class
 * @var $el_id
 * @var $interval
 * @var $css
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_gallery
 */
$thumbnail     = '';
$title         = $source = $type = $onclick = $custom_links = $custom_links_target = $img_size = $external_img_size = $images = $custom_srcs = $el_class = $el_id = $interval = $css = $css_animation = '';
$large_img_src = $animation = '';

$attributes = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $attributes );

$default_src = vc_asset_url( 'vc/no_image.png' );

$gal_images        = '';
$link_start        = '';
$link_end          = '';
$el_start          = '';
$el_end            = '';
$slides_wrap_start = '';
$slides_wrap_end   = '';

$el_class = $this->getExtraClass( $el_class );
if ( 'grid' === $gallery_type ) {

	$slides_wrap_start = '<div class="row ' . $lightbox . ' ' . $thb_margins . '">';
	$slides_wrap_end   = '</div>';
} elseif ( 'thb-portfolio' === $gallery_type ) {

	$slides_wrap_start = '<div class="thb-portfolio masonry variable-height row ' . $lightbox . ' ' . $thb_margins . '" data-layoutmode="packery" data-thb-animation="thb-vertical-flip">';
	$slides_wrap_end   = '</div>';

}


if ( '' === $images ) {
	$images = '-1,-2,-3';
}

$pretty_rel_random = ' data-rel="prettyPhoto[rel-' . get_the_ID() . '-' . wp_rand() . ']"';

if ( 'custom_link' === $onclick ) {
	$custom_links = vc_value_from_safe( $custom_links );
	$custom_links = explode( ',', $custom_links );
}

$images = explode( ',', $images );



$class_to_filter    = 'wpb_gallery wpb_content_element vc_clearfix';
$class_to_filter   .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class          = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output  = '';
$output .= '<div class="' . $css_class . '" ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= '<div class="wpb_wrapper">';
$output .= '<div class="wpb_gallery_slides">' . $slides_wrap_start;

// Start Image Output
foreach ( $images as $i => $image ) {
	// Columns
	$el_start        = '<div class="' . $thb_columns . ' columns"><div class="' . $animation . '">';
	$el_end          = '</div></div>';
	$attachment_meta = get_post_custom( $image );

	if ( 'thb-portfolio' === $gallery_type ) {
		$masonry_image_size = ! empty( $attachment_meta['thb-masonry-image-size'] ) ? $attachment_meta['thb-masonry-image-size'][0] : 'masonry-small';

		$thb_masonry_size = thb_get_masonry_size( $masonry_image_size );
		$masonry_class    = $thb_masonry_size['class'];
		$img_size         = $thb_masonry_size['image_size'];

		$el_start = '<div class="' . $masonry_class . ' columns"><div class="' . $animation . '">';
	}

	// Get Image
	if ( $image > 0 ) {
		$img           = wpb_getImageBySize(
			array(
				'attach_id'  => $image,
				'thumb_size' => $img_size,
				'class'      => 'wp-post-image',
			)
		);
		$thumbnail     = $img['thumbnail'];
		$large_img_src = wp_get_attachment_image_src( $image, 'full' );
		$large_img_src = $large_img_src[0];
	} else {
		$large_img_src = $default_src;
		$thumbnail     = '<img src="' . $default_src . '" />';
	}

	// Create Thumbnail
	$thumbnail_html  = '<figure class="thb-overlay-caption">';
	$thumbnail_html .= $thumbnail;
	if ( ! empty( $attachment_meta['_wp_attachment_image_alt'] ) ) {
			$thumbnail_html .= '<figcaption>' . $attachment_meta['_wp_attachment_image_alt'][0] . '</figcaption>';
	}
	$thumbnail_html .= '</figure>';

	// Image Link
	$link_start = $link_end = '';

	if ( $lightbox ) {
		$link_start = '<a href="' . esc_url( $large_img_src ) . '" class="thb-portfolio-link mfp-image">';
		$link_end   = '</a>';
	}
	$output .= $el_start . $link_start . $thumbnail_html . $link_end . $el_end;
}
// End Image Output
$output .= $slides_wrap_end . '</div>';
$output .= '</div>';
$output .= '</div>';

echo '' . $output;

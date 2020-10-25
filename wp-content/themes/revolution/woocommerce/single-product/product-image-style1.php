<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$placeholder       = $product->get_image_id() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

$wrapper_classes[] = 'small-12';
$wrapper_classes[] = 'large-10';
$wrapper_classes[] = 'columns';
$wrapper_classes[] = 'small-order-1';
$wrapper_classes[] = 'large-order-2';

$classes[] = 'woocommerce-product-gallery__wrapper';
$classes[] = 'product-images';
$classes[] = 'carousel';
$classes[] = 'thb-carousel';
$classes[] = 'center-arrows';
$classes[] = 'thb-carousel-dark';

?>
<div class="row">
	<div class="large-2 columns small-order-2 large-order-1 product-thumbnail-container">
		<?php wc_get_template( 'woocommerce/single-product/product-gallery-thumbnails.php' ); ?>
	</div>
	<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
		<figure id="product-images" class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $classes ) ) ); ?>" data-navigation="true" data-autoplay="false" data-columns="1" data-asnavfor="#product-thumbnails">
			<?php

			if ( has_post_thumbnail() ) {
        $html = '<div class="first">';
				$html .= wc_get_gallery_image_html( $post_thumbnail_id, true );
				$html .= '</div>';
			} else {
				$html  = '<div class="first woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src('woocommerce_single') ), esc_html__( 'Awaiting product image', 'revolution' ) );
				$html .= '</div>';
			}

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

			do_action( 'woocommerce_product_thumbnails' );
			?>
		</figure>
	</div>
</div>

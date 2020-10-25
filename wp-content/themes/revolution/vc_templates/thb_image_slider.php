<?php function thb_image_slider( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_image_slider', $atts );
	extract( $atts );

	$element_id = 'thb-image-slider-' . wp_rand( 10, 999 );
	$el_class[] = 'thb-image-slider';
	$el_class[] = 'thb-carousel';
	$el_class[] = 'row';
	$el_class[] = $lightbox;
	$el_class[] = $thb_next_slides;
	$el_class[] = $thb_center === 'true' ? 'thb_center' : '';
	$el_class[] = $thb_equal_height === 'true' ? 'thb_equal_height' : '';
	$el_class[] = $thb_navigation === 'true' ? 'center-arrows' : '';
	$out        = '';
	ob_start();
	$images = explode( ',', $images );

	$infinite = $lightbox ? 'false' : 'true';
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" data-pagination="<?php echo esc_attr( $thb_pagination ); ?>" data-center="<?php echo esc_attr( $thb_center ); ?>" data-columns="<?php echo esc_attr( $thb_columns ); ?>" data-infinite="<?php echo esc_attr( $infinite ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-navigation="<?php echo esc_attr( $thb_navigation ); ?>">
		<?php
		foreach ( $images as $image ) {
			$image_link = wp_get_attachment_image_src( $image, 'full' );
			?>
				<figure class="columns">
				<?php if ( $lightbox ) { ?>
						<a href="<?php echo esc_attr( $image_link[0] ); ?>">
					<?php } ?>
				<?php
				if ( $img_size !== '' ) {
					$post_thumbnail = wpb_getImageBySize(
						array(
							'attach_id'  => $image,
							'thumb_size' => $img_size,
						)
					);

					$thumbnail = $post_thumbnail['thumbnail'];
					echo $thumbnail;
				} else {
					echo wp_get_attachment_image( $image, 'full' );
				}
				?>
				<?php if ( $lightbox ) { ?>
						</a>
					<?php } ?>
				</figure>
			<?php
		}
		?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_image_slider', 'thb_image_slider' );

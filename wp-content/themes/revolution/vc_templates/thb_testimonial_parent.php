<?php function thb_testimonial_parent( $atts, $content = null ) {
	global $thb_testimonial_columns, $thb_style;

	$autoplay = $autoplay_speed = false;
	$atts     = vc_map_get_attributes( 'thb_testimonial_parent', $atts );
	extract( $atts );

	$element_id              = uniqid( 'thb-testimonials-' );
	$el_class[]              = 'thb-testimonials';
	$el_class[]              = 'thb-testimonial-' . $thb_style;
	$el_class[]              = 'style6' === $thb_style ? 'row' : 'thb-carousel';
	$el_class[]              = $thb_style;
	$el_class[]              = 'style5' === $thb_style ? 'no-padding' : '';
	$el_class[]              = $extra_class;
	$el_class[]              = 'style9' === $thb_style ? 'center-arrows thb-carousel-dark' : false;
	$fade                    = 'style3' !== $thb_style ? 'true' : 'false';
	$thb_testimonial_columns = ( in_array( $thb_style, array( 'style3', 'style6' ), true ) ? $columns : 1 );

	$pagination = 'style5' !== $thb_style ? $thb_pagination : '';

	$navigation = ! in_array( $thb_style, array( 'style5', 'style9' ), true ) ? 'false' : 'true';

	$adaptive = in_array( $thb_style, array( 'style5', 'style9' ), true ) ? 'true' : 'false';
	$out      = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" data-columns="<?php echo esc_attr( $thb_testimonial_columns ); ?>" data-pagination="<?php echo esc_attr( $pagination ); ?>" data-navigation="<?php echo esc_attr( $navigation ); ?>"
						<?php
						if ( 'style3' === $thb_style ) {
							?>
								data-center="true"<?php } ?> data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-fade="<?php echo esc_attr( $fade ); ?>" data-adaptive="<?php echo esc_attr( $adaptive ); ?>">
		<?php echo wpb_js_remove_wpautop( $content, false ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_testimonial_parent', 'thb_testimonial_parent' );

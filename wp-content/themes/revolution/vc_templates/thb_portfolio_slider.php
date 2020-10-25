<?php function thb_portfolio_slider( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_portfolio_slider', $atts );
	extract( $atts );

	$source_data   = VcLoopSettings::parseData( $source );
	$query_builder = new ThbLoopQueryBuilder( $source_data );
	$slider_posts  = $query_builder->build();
	$slider_posts  = $slider_posts[1];
	$rand          = wp_rand( 0, 1000 );

	ob_start();

	$classes[]  = 'thb-portfolio-slider';
	$classes[]  = 'thb-carousel';
	$classes[]  = $full_height;
	$classes[]  = 'thb-portfolio-slider-' . $style;
	$classes[]  = in_array( $style, array( 'style5', 'style6' ) ) ? 'thb-arrows-style2' : '';
	$classes[]  = $style === 'style4' ? 'overflow-visible center-arrows' : '';
	$classes[]  = ! in_array( $style, array( 'style4', 'style5', 'style7' ) ) ? 'position-arrows' : '';
	$classes[]  = $style === 'style3' ? 'thb-carousel-dark' : '';
	$classes[]  = $extra_class;
	$pagination = in_array( $style, array( 'style5', 'style6', 'style7' ) ) ? 'true' : false;
	$navigation = $style === 'style7' ? false : 'true';
	$vertical   = in_array( $style, array( 'style3', 'style4' ) ) ? false : $thb_slider_direction;
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" id="portfolio-slider-<?php echo esc_attr( $rand ); ?>" data-columns="1" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-navigation="<?php echo esc_attr( $navigation ); ?>" data-pagination="<?php echo esc_attr( $pagination ); ?>" data-vertical="<?php echo esc_attr( $vertical ); ?>">
		<?php
		if ( $slider_posts->have_posts() ) :
			while ( $slider_posts->have_posts() ) :
				$slider_posts->the_post();
						get_template_part( 'inc/templates/portfolio/slider/' . $style );
				endwhile; else :
					get_template_part( 'inc/templates/not-found' );
			endif;
				?>
	</div>

	<?php
	$out = ob_get_clean();
	wp_reset_postdata();
	return $out;
}
thb_add_short( 'thb_portfolio_slider', 'thb_portfolio_slider' );

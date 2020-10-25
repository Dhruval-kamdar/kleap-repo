<?php function thb_post( $atts, $content = null ) {
	$style = '';
	$atts  = vc_map_get_attributes( 'thb_post', $atts );
	extract( $atts );

	$element_id = uniqid( 'thb-posts-shortcode-' );
	$rand       = wp_rand( 0, 1000 );

	$thb_excerpt = $thb_excerpt === 'true' ? true : false;
	$thb_date    = $thb_date === 'true' ? true : false;

	if ( $offset ) {
		$source .= '|offset:' . $offset;
	}
	$source_data   = VcLoopSettings::parseData( $source );
	$query_builder = new ThbLoopQueryBuilder( $source_data );
	$thb_posts     = $query_builder->build();
	$posts         = $thb_posts[1];
	$style         = $style;

	$classes[]   = 'posts-shortcode';
	$classes[]   = 'posts-' . $style;
	$classes[]   = $style === 'style2' ? 'align-center' : false;
	$classes[]   = $style !== 'style3' ? 'row' : false;
	$classes[]   = $style === 'style9' ? 'thb-toggle-blog' : false;
	$classes[]   = $style === 'style4' && $thb_carousel !== 'true' ? 'masonry' : '';
	$classes[]   = $thb_carousel === 'true' ? 'thb-carousel overflow-visible' : '';
	$classes[]   = ( $thb_carousel !== 'true' && $loadmore == 'true' ) ? 'posts-pagination-style2' : '';
	$thb_columns = thb_translate_columns( $columns );
	set_query_var( 'columns', $thb_columns );
	ob_start();
	?>

	 <div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-pagination="true" data-loadmore="#loadmore-<?php echo esc_attr( $rand ); ?>" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_posts_ajax' ) ); ?>">
		<?php
		if ( $posts->have_posts() ) :
			while ( $posts->have_posts() ) :
				$posts->the_post();
				?>
							<?php
							set_query_var( 'thb_i', false );
							set_query_var( 'thb_date', $thb_date );
							set_query_var( 'thb_cat', $thb_cat );
							set_query_var( 'thb_excerpt', $thb_excerpt );
							set_query_var( 'thb_animation', $animation );
							get_template_part( 'inc/templates/postbit/' . $style );
							?>
					<?php
		endwhile;
else :
endif;
?>
	</div>
	<?php if ( $thb_carousel !== 'true' && $loadmore === 'true' ) { ?>
		<?php
		wp_localize_script(
			'thb-app',
			esc_attr( 'thb_postsajax_' . $rand ),
			array(
				'count'         => $source_data['size'],
				'loop'          => $source,
				'style'         => $style,
				'columns'       => $columns,
				'thb_i'         => false,
				'thb_date'      => $thb_date,
				'thb_cat'       => $thb_cat,
				'thb_excerpt'   => $thb_excerpt,
				'thb_animation' => $animation,
			)
		);
		?>
		<div class="row">
			<div class="small-12 columns text-center">
				<a href="#" class="thb_load_more masonry_btn" title="<?php esc_html_e( 'Load More', 'revolution' ); ?>" data-posts-id="<?php echo esc_attr( $rand ); ?>" id="loadmore-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e( 'Load More', 'revolution' ); ?></a>
			</div>
		</div>
	<?php } ?>
	<?php
	$out = ob_get_clean();
	set_query_var( 'count', false );
	set_query_var( 'loop', false );
	set_query_var( 'columns', false );
	set_query_var( 'thb_i', false );
	wp_reset_query();
	wp_reset_postdata();

	return $out;
}
thb_add_short( 'thb_post', 'thb_post' );

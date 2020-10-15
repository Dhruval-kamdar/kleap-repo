<?php function thb_freescroll( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_freescroll', $atts );
	extract( $atts );

	$element_id = 'thb-freescroll-' . wp_rand( 10, 999 );
	$el_class[] = 'thb-freescroll';
	$el_class[] = $extra_class;
	$el_class[] = $thb_margins;
	$el_class[] = 'thb-freescroll-' . $type;
	$el_class[] = 'instagram' === $type ? 'thb-instagram-row' : '';
	$el_class[] = 'portfolios' === $type ? 'thb-portfolio' : '';
	$el_class[] = 'images' === $type ? $lightbox : '';
	$out        = '';
	ob_start();
	$images = explode( ',', $images );

	$free_posts = vc_build_loop_query( $source );
	$free_posts = $free_posts[1];
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" data-direction="<?php echo esc_attr( $direction ); ?>" data-pause="<?php echo esc_attr( $pause_on_hover ); ?>">
		<?php
		if ( 'text' === $type ) {
			$content      = force_balance_tags( $atts['text_content'] );
			$content_safe = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
			?>
				<div class="small-12 columns text-content">
				<?php echo wp_kses_post( $content_safe ); ?>
				</div>
		<div class="small-12 columns text-content">
				<?php echo wp_kses_post( $content_safe ); ?>
				</div>
				<?php
		} elseif ( 'images' === $type ) {
			foreach ( $images as $image ) {
				$image_link = wp_get_attachment_image_src( $image, 'full' );

				?>

					<figure class="<?php echo esc_attr( $thb_columns ); ?> columns">
					<?php
					if ( $lightbox ) {
						?>
						<a href="<?php echo esc_attr( $image_link[0] ); ?>" class="thb-lightbox-link"><?php } ?>
					<?php
						echo wp_get_attachment_image(
							$image,
							'full',
							false,
							array(
								'class' => $box_shadow,
							)
						);
					?>
					<?php
					if ( $lightbox ) {
						?>
						</a><?php } ?>
					</figure>
				<?php
			}
		} elseif ( 'instagram' === $type ) {
			$instagram = thb_get_instagram_photos( $number, $username, $access_token );

			if ( $instagram ) {
				?>
				<?php
				if ( array_key_exists( 'data', $instagram ) ) {
					foreach ( $instagram['data'] as $item ) {
						?>
		<div class="<?php echo esc_attr( $thb_columns ); ?> columns">
<figure style="background-image:url(<?php echo esc_url( $item['image_url'] ); ?>)">
		<a href="<?php echo esc_attr( $item['link'] ); ?>" target="_blank" class="instagram-link"></a>
		<span><?php get_template_part( 'assets/img/svg/like.svg' ); ?><em><?php echo thb_numberAbbreviation( $item['likes'] ); ?></em> <?php get_template_part( 'assets/img/svg/comment.svg' ); ?><em><?php echo thb_numberAbbreviation( $item['comments'] ); ?></em></span>
</figure>
						<?php if ( array_key_exists( 'caption', $item ) ) { ?>
		<figcaption><?php echo esc_html( $item['caption'] ); ?></figcaption>
<?php } ?>
	</div>
								<?php
					}
				}
				?>
					<?php
			}
		} elseif ( 'blog-posts' === $type ) {
			if ( $free_posts->have_posts() ) :
				while ( $free_posts->have_posts() ) :
					$free_posts->the_post();
					set_query_var( 'columns', $thb_columns );
					set_query_var( 'thb_cat', false );
					set_query_var( 'thb_date', true );
					set_query_var( 'thb_excerpt', false );
					get_template_part( 'inc/templates/postbit/style1' );
				endwhile;
endif;
				wp_reset_query();
				wp_reset_postdata();
		} elseif ( 'portfolios' === $type ) {
			if ( $free_posts->have_posts() ) :
				while ( $free_posts->have_posts() ) :
					$free_posts->the_post();
					set_query_var( 'thb_size', $thb_columns );
					get_template_part( 'inc/templates/portfolio/' . $portfolio_style );
				endwhile;
endif;
				wp_reset_query();
				wp_reset_postdata();
		}
		?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_freescroll', 'thb_freescroll' );

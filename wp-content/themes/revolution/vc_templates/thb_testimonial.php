<?php function thb_testimonial( $atts, $content = null ) {
	global $thb_testimonial_columns, $thb_style;
	$atts = vc_map_get_attributes( 'thb_testimonial', $atts );
	extract( $atts );

	$author_image = wpb_getImageBySize(
		array(
			'attach_id'  => $author_image,
			'class'      => 'author_image',
			'thumb_size' => array(
				'140',
				'140',
			),
		)
	);

	$image = wpb_getImageBySize(
		array(
			'attach_id'  => $review_image,
			'class'      => 'review_image thb-ignore-lazyload ' . ( 'style9' === $thb_style ? 'retina_size' : false ),
			'thumb_size' => 'full',
		)
	);

	/* Full Link */
	$url     = ( $link == '||' ) ? '' : $link;
	$url_btn = vc_build_link( $url );

	$url_to     = $url_btn['url'];
	$url_title  = $url_btn['title'];
	$url_target = $url_btn['target'] ? $url_btn['target'] : '_self';

	$el_class[]              = 'thb-testimonial';
	$el_class[]              = $author_image ? 'has-avatar' : '';
	$thb_testimonial_columns = in_array( $thb_style, array( 'style3', 'style6' ), true ) ? $thb_testimonial_columns : '';
	$out                     = '';
	ob_start();

	?>
	<div class="small-12 <?php echo esc_attr( $thb_testimonial_columns ); ?> columns">
		<?php if ( 'style5' === $thb_style ) { ?>
			<div class="row no-padding">
				<div class="small-12 medium-4 small-order-2 medium-order-1 columns">
		<?php } ?>
					<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
						<?php if ( 'style9' === $thb_style ) { ?>
							<?php echo $image['thumbnail']; ?>
						<?php } ?>
						<?php
						if ( $thb_review ) {
							$percentage = ( $thb_review_stars / 5 ) * 100;
							?>
							<div class="star-rating"><span style="width: <?php echo esc_attr( $percentage ); ?>%"></span></div>
						<?php } ?>
						<?php if ( $quote_title ) { ?>
						<h4><?php echo esc_html( $quote_title ); ?></h4>
						<?php } ?>
						<blockquote><?php echo wpautop( $quote ); ?></blockquote>
						<?php if ( $author_name ) { ?>
							<?php
							if ( $author_image ) {
								echo $author_image['thumbnail'];
							}
							?>
							<div class="testimonial-author">
								<?php if ( 'style8' === $thb_style ) { ?>
									<h6><?php echo esc_html( $author_name ); ?></h6>
								<?php } else { ?>
									<cite><?php echo esc_html( $author_name ); ?></cite>
								<?php } ?>
								<span class="title"><?php echo esc_html( $author_title ); ?></span>
							</div>
						<?php } ?>
					</div>
		<?php if ( 'style5' === $thb_style ) { ?>
				</div>
				<div class="small-12 medium-8 small-order-1 medium-order-2 columns">
					<?php echo $image['thumbnail']; ?>
				</div>
				<?php if ( $url_to ) { ?>
					<a href="<?php echo esc_url( $url_to ); ?>" target="<?php echo esc_attr( $url_target ); ?>" title="<?php echo esc_attr( $url_title ); ?>" class="review-full-link"></a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_testimonial', 'thb_testimonial' );

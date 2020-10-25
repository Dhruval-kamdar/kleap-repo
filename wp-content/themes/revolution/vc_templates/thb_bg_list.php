<?php function thb_bg_list( $atts, $content = null ) {
	global $thb_list_height, $thb_list_columns, $thb_list_animation, $thb_i;
	$atts = vc_map_get_attributes( 'thb_bg_list', $atts );
	extract( $atts );

	$image = wpb_getImageBySize(
		array(
			'attach_id'  => $image,
			'class'      => 'thb-bg-list-image',
			'thumb_size' => 'full',
		)
	);

	/* Full Link */
	$url     = ( '||' === $link ) ? '' : $link;
	$url_btn = vc_build_link( $url );

	$url_to     = $url_btn['url'];
	$url_title  = $url_btn['title'];
	$url_target = $url_btn['target'] ? $url_btn['target'] : '_self';

	$el_class[] = 'thb-bg-list';
	$el_class[] = $thb_list_animation;
	$el_class[] = '' !== $url_to ? 'has-btn' : '';
	$out        = '';
	ob_start();

	?>
	<div class="small-12 <?php echo esc_attr( $thb_list_columns ); ?> columns" style="min-height: <?php echo esc_attr( $thb_list_height ); ?>">
		<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
			<div class="list-content-container">
				<?php if ( $title ) { ?>
					<div class="thb-bg-list-title">
						<h4><?php echo esc_html( $title ); ?></h4>
					</div>
				<?php } ?>
				<?php if ( $content ) { ?>
					<div class="thb-bg-list-content">
						<?php echo force_balance_tags( $content ); ?>
					</div>
				<?php } ?>
			</div>
			<?php if ( $url_to ) { ?>
				<a href="<?php echo esc_url( $url_to ); ?>" target="<?php echo esc_attr( $url_target ); ?>" title="<?php echo esc_attr( $url_title ); ?>" class="btn thb-bglist-btn no-radius style2 white small"><span><?php echo esc_attr( $url_title ); ?></span></a>
			<?php } ?>
		</div>
	</div>
	<figure class="thb-bg-list-bg">
		<?php echo $image['thumbnail']; ?>
	</figure>
	<?php
	$thb_i++;
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_bg_list', 'thb_bg_list' );

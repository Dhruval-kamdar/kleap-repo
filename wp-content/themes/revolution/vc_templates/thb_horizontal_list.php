<?php function thb_horizontal_list( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_horizontal_list', $atts );
	extract( $atts );

	$element_id = 'thb-horizontal-list-' . wp_rand( 10, 999 );

	$el_class[] = 'thb-horizontal-list';
	$el_class[] = $extra_class;
	$el_class[] = $animation;

	switch ( $thb_columns ) {
		case 2:
			$el_class[] = $columns_2_size;
			break;
		case 3:
			$el_class[] = $columns_3_size;
			break;
		case 4:
			$el_class[] = $columns_4_size;
			break;
	}

	/* Full Link */
	$url     = ( '||' === $url ) ? '' : $url;
	$url_btn = vc_build_link( $url );

	$url_to     = $url_btn['url'];
	$url_title  = $url_btn['title'];
	$url_target = $url_btn['target'] ? $url_btn['target'] : '_self';

	/* CTA - 1 */
	$cta_1     = ( '||' === $cta_1 ) ? '' : $cta_1;
	$cta_1_btn = vc_build_link( $cta_1 );

	$cta_1_to     = $cta_1_btn['url'];
	$cta_1_title  = $cta_1_btn['title'];
	$cta_1_target = $cta_1_btn['target'] ? $cta_1_btn['target'] : '_self';

	/* CTA - 2 */
	$cta_2     = ( '||' === $cta_2 ) ? '' : $cta_2;
	$cta_2_btn = vc_build_link( $cta_2 );

	$cta_2_to     = $cta_2_btn['url'];
	$cta_2_title  = $cta_2_btn['title'];
	$cta_2_target = $cta_2_btn['target'] ? $cta_2_btn['target'] : '_self';

	if ( $cta_1 || $cta_2 ) {
		$el_class[] = 'has-button';
	}
	$btn_class[] = 'btn';
	$btn_class[] = $style;
	$btn_class[] = $size;
	$btn_class[] = $full_width;
	$btn_class[] = $color;
	$btn_class[] = $border_radius;
	$btn_class[] = $add_arrow ? 'arrow-enabled' : '';

	$out = '';
	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<?php
		for ( $i = 0; $i < intval( $thb_columns ); $i++ ) {
			$cell_class[ $i ] = array( 'horizontal-list-cell' );
			array_push( $cell_class[ $i ], $atts[ 'column_' . ( $i + 1 ) . '_align' ] );

			$content      = force_balance_tags( $atts[ 'column_' . ( $i + 1 ) . '_content' ] );
			$content_safe = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
			?>
			<div class="<?php echo implode( ' ', $cell_class[ $i ] ); ?>">
			<?php echo wp_kses_post( $content_safe ); ?>
			<?php if ( intval( $thb_columns ) - 1 === $i ) { ?>
					<?php if ( $cta_1_to ) { ?>
						<a class="<?php echo implode( ' ', $btn_class ); ?>" href="<?php echo esc_attr( $cta_1_to ); ?>" target="<?php echo sanitize_text_field( $cta_1_target ); ?>" role="button" title="<?php echo esc_attr( $cta_1_title ); ?>"><span><?php echo esc_attr( $cta_1_title ); ?></span>
							<?php
							if ( 'style4' !== $style && $add_arrow ) {
								get_template_part( 'assets/img/svg/next_arrow.svg' );
							}
							?>
						</a>
					<?php } ?>
					<?php if ( $cta_2_to ) { ?>
						<a class="<?php echo implode( ' ', $btn_class ); ?>" href="<?php echo esc_attr( $cta_2_to ); ?>" target="<?php echo sanitize_text_field( $cta_2_target ); ?>" role="button" title="<?php echo esc_attr( $cta_2_title ); ?>"><span><?php echo esc_attr( $cta_2_title ); ?></span>
							<?php
							if ( 'style4' !== $style && $add_arrow ) {
								get_template_part( 'assets/img/svg/next_arrow.svg' );
							}
							?>
						</a>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } // End for ?>
		<?php if ( $url_to ) { ?>
			<a href="<?php echo esc_url( $url_to ); ?>" target="<?php echo esc_attr( $url_target ); ?>" title="<?php echo esc_attr( $url_title ); ?>" class="horizontal-full-link"></a>
		<?php } ?>
		<style>
			<?php if ( $hover_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?>:before {
					background: <?php echo esc_attr( $hover_color ); ?>;
				}
			<?php } ?>
			<?php if ( 'gradient' === $color ) { ?>
				<?php if ( $bg_gradient1 && $bg_gradient2 ) { ?>
					<?php if ( 'style1' === $style ) { ?>
				/* Style1 */
				#<?php echo esc_attr( $element_id ); ?> .btn.style1 {
						<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style1:after {
					background: <?php echo esc_attr( $bg_gradient2 ); ?>;
				}
				<?php } ?>
					<?php if ( 'style2' === $style ) { ?>
				/* Style2 */
				#<?php echo esc_attr( $element_id ); ?> .btn.style2:after {
						<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style2:not(:hover) {
					color: <?php echo esc_attr( $bg_gradient2 ); ?>;
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style2:before {
					background: <?php echo esc_attr( $st_color ); ?>;
				}
				<?php } ?>
					<?php if ( 'style3' === $style ) { ?>
				/* Style3 */
				#<?php echo esc_attr( $element_id ); ?> .btn.style3:after {
						<?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', true ); ?>
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style3:not(:hover) {
					color: <?php echo esc_attr( $bg_gradient2 ); ?>;
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style3:before {
					background: <?php echo esc_attr( $st_color ); ?>;
				}
				<?php } ?>
					<?php if ( 'style4' === $style ) { ?>
				/* Style4 */
				#<?php echo esc_attr( $element_id ); ?> .btn.style4 {
					border-image: <?php echo thb_css_gradient( $bg_gradient1, $bg_gradient2, '-135', false ); ?> 1;
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style4 {
					color: <?php echo esc_attr( $bg_gradient2 ); ?>;
				}
				#<?php echo esc_attr( $element_id ); ?> .btn.style4:after {
					background: <?php echo esc_attr( $bg_gradient2 ); ?>;
				}
				<?php } ?>
			<?php } ?>
			<?php } ?>
		</style>
	</div>
	<?php

	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_horizontal_list', 'thb_horizontal_list' );

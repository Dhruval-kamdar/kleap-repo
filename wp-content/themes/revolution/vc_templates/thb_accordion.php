<?php function thb_accordion( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_accordion', $atts );
	extract( $atts );

	$element_id = uniqid( 'thb-accordion-' );
	$out        = '';
	$el_class[] = 'thb-accordion';
	$el_class[] = $style;
	$el_class[] = 'true' === $accordion ? 'has-accordion' : '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" data-scroll="<?php echo esc_attr( $tabs_scroll ); ?>" data-index="<?php echo esc_attr( $thb_index ); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_accordion', 'thb_accordion' );

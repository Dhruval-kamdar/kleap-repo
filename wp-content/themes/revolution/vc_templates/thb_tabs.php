<?php function thb_tabs( $atts, $content = null ) {

	$atts = vc_map_get_attributes( 'thb_tabs', $atts );
	extract( $atts );

	$element_id = uniqid( 'thb-tabs-' );
	$out        = '';
	$el_class[] = 'thb-tabs';
	$el_class[] = $style;
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" data-active-section="<?php echo esc_attr( $active_section ); ?>" data-animation="<?php echo esc_attr( $tabs_animation ); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_tabs', 'thb_tabs' );

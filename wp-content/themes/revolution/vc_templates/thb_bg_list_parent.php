<?php function thb_bg_list_parent( $atts, $content = null ) {
	global $thb_list_height, $thb_list_columns, $thb_list_animation, $thb_i;
	$thb_i = 1;
	$atts  = vc_map_get_attributes( 'thb_bg_list_parent', $atts );
	extract( $atts );
	$thb_list_animation = $animation;
	$element_id         = uniqid( 'thb-bg-list-' );
	$el_class[]         = 'thb-bg-list-parent';
	$el_class[]         = 'style1';
	$el_class[]         = 'row no-padding';
	$el_class[]         = 'thb-light-title';
	$out                = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>" data-zoom-effect="<?php echo esc_attr( $zoom_effect ); ?>">
		<?php echo wpb_js_remove_wpautop( $content, false ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_bg_list_parent', 'thb_bg_list_parent' );

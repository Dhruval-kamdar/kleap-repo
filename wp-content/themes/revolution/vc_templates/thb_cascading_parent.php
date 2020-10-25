<?php function thb_cascading_parent( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_cascading_parent', $atts );
	extract( $atts );
	$class[] = 'thb_cascading_images';
	$class[] = $extra_class;

	ob_start(); ?>
	<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
		<?php echo wpb_js_remove_wpautop( $content, false ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_cascading_parent', 'thb_cascading_parent' );

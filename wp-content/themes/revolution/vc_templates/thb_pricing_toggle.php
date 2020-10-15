<?php function thb_pricing_toggle( $atts, $content = null ) {
	global $thb_pricing_style;
	$atts = vc_map_get_attributes( 'thb_pricing_toggle', $atts );
	extract( $atts );
	$out = '';
	ob_start();
	?>
	<div class="thb-pricing-toggle">
		<div class="thb-label-initial">
			<?php echo esc_html( $label_initial ); ?>
		</div>
		<div class="thb-pricing-toggle-element">
			<div class="thb-pricing-toggle-circle"></div>
		</div>
		<div class="thb-label-second">
			<?php echo esc_html( $label_second ); ?>
		</div>
		<div class="thb-label-discount">
			<?php echo esc_html( $label_discount ); ?>
		</div>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_pricing_toggle', 'thb_pricing_toggle' );

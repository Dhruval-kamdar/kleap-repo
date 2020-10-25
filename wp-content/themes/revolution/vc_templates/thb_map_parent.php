<?php function thb_map_parent( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_map_parent', $atts );
	extract( $atts );
	$thb_api_key  = ot_get_option( 'map_api_key' );
	$map_style    = rawurldecode( thb_decode( strip_tags( $map_style ) ) );
	$map_controls = explode( ',', $map_controls );

	$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
	$regexr  = preg_match( $pattern, $height, $matches );
	$value   = isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'thb_map_parent', 'height' );
	$unit    = isset( $matches[2] ) ? $matches[2] : 'vh';
	$height  = $value . $unit;
	ob_start(); ?>
	<div class="contact_map_parent <?php echo esc_attr( $position ); ?>" style="height:<?php echo esc_attr( $height ); ?>">
		<div class="contact_map
		<?php
		if ( '' === $thb_api_key ) {
			?>
			disabled<?php } ?>" data-map-style="<?php echo esc_attr( $map_style ); ?>" data-map-zoom="<?php echo esc_attr( $zoom ); ?>" data-map-type="<?php echo esc_attr( $map_type ); ?>" data-pan-control="<?php echo esc_attr( in_array( 'panControl', $map_controls ) ); ?>" data-zoom-control="<?php echo esc_attr( in_array( 'zoomControl', $map_controls, true ) ); ?>" data-maptype-control="<?php echo esc_attr( in_array( 'mapTypeControl', $map_controls, true ) ); ?>" data-scale-control="<?php echo esc_attr( in_array( 'scaleControl', $map_controls, true ) ); ?>" data-streetview-control="<?php echo esc_attr( in_array( 'streetViewControl', $map_controls, true ) ); ?>">
			<?php if ( '' !== $thb_api_key ) { ?>
				<?php echo wpb_js_remove_wpautop( $content, false ); ?>
			<?php } else { ?>
				<?php esc_html_e( 'Please fill out Google Maps Api key inside Theme Options', 'revolution' ); ?>
			<?php } ?>

		</div>
		<?php
		if ( $expand ) {
			?>
 <a href="#" class="thb-expand"><?php get_template_part( 'assets/svg/arrows_expand.svg' ); ?></a><?php } ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_map_parent', 'thb_map_parent' );

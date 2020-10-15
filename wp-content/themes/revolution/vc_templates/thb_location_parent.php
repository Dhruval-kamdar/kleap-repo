<?php function thb_location_parent( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_location_parent', $atts );
	extract( $atts );
	wp_enqueue_script( 'gmapdep' );
	$thb_api_key  = ot_get_option( 'map_api_key' );
	$map_style    = rawurldecode( thb_decode( strip_tags( $map_style ) ) );
	$map_controls = explode( ',', $map_controls );

	$location_title = $location_description = '';
	preg_match_all( '/marker_title=\"(.*?)\"\smarker_description=\"(.*?)\"]/is', $content, $matches, PREG_OFFSET_CAPTURE );

	$locations = array();
	if ( isset( $matches[1] ) ) {
		for ( $i = 0; $i < sizeof( $matches[1] ); ++$i ) {
			$locations[] = array(
				'title'       => $matches[1][ $i ][0],
				'description' => $matches[2][ $i ][0],
			);
		}
	}
	$element_id = 'thb-office-locations-' . wp_rand( 10, 999 );

	$classes[] = 'thb-office-locations-' . $style;
	$classes[] = 'thb_location_container';
	$classes[] = 'style1' === $style ? 'row max_width thb-carousel center-arrows' : false;
	ob_start(); ?>
	<div class="thb_office_location_container <?php echo esc_attr( $style ); ?>">
		<?php if ( sizeof( $locations ) ) { ?>
			<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-columns="4" data-pagination="true" data-infinite="false" data-navigation="<?php echo esc_attr( $thb_navigation ); ?>">
				<?php if ( 'style2' === $style ) { ?>
					<?php
					if ( $title ) {
						?>
						<h2><?php echo esc_html( $title ); ?></h2><?php } ?>
				<?php } ?>
				<?php $i = 1; foreach ( $locations as $location ) { ?>
					<?php if ( 'style1' === $style ) { ?>
					<div class="columns">
					<?php } ?>
						<div class="thb_location
						<?php
						if ( $i === 1 ) {
							?>
							active<?php } ?>">
							<h5>
							<?php
							if ( 'style1' === $style && 'true' === $title_numbers ) {
								echo esc_attr( $i . '. ' ); }
							?>
							<?php echo esc_html( $location['title'] ); ?></h5>
							<?php
							if ( 'style1' === $style ) {
								echo wp_kses_post( thb_remove_p( $location['description'] ) ); }
							?>
						</div>
					<?php if ( 'style1' === $style ) { ?>
					</div>
					<?php } ?>
				<?php $i++; } ?>
			</div>

		<?php } ?>
		<?php
			$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
			$regexr  = preg_match( $pattern, $height, $matches );
			$value   = isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'thb_map_parent', 'height' );
			$unit    = isset( $matches[2] ) ? $matches[2] : 'vh';
			$height  = $value . $unit;
		?>
		<div class="thb_office_location
		<?php
		if ( $thb_api_key === '' ) {
			?>
			disabled<?php } ?>" style="height:<?php echo esc_attr( $height ); ?>" data-map-style="<?php echo esc_attr( $map_style ); ?>" data-map-zoom="<?php echo esc_attr( $zoom ); ?>" data-map-type="<?php echo esc_attr( $map_type ); ?>" data-pan-control="<?php echo esc_attr( in_array( 'panControl', $map_controls, true ) ); ?>" data-zoom-control="<?php echo esc_attr( in_array( 'zoomControl', $map_controls, true ) ); ?>" data-maptype-control="<?php echo esc_attr( in_array( 'mapTypeControl', $map_controls, true ) ); ?>" data-scale-control="<?php echo esc_attr( in_array( 'scaleControl', $map_controls, true ) ); ?>" data-streetview-control="<?php echo esc_attr( in_array( 'streetViewControl', $map_controls, true ) ); ?>" data-style="<?php echo esc_attr( $style ); ?>">
			<?php if ( $thb_api_key !== '' ) { ?>
				<?php echo wpb_js_remove_wpautop( $content, false ); ?>
			<?php } else { ?>
				<?php esc_html_e( 'Please fill out Google Maps Api key inside Theme Options', 'revolution' ); ?>
			<?php } ?>
		</div>
		<style>
			<?php if ( $location_bg_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-office-locations-style1 .thb_location.active {
					background: <?php echo esc_attr( $location_bg_color ); ?>;
				}
			<?php } ?>
			<?php if ( $heading_color ) { ?>
				#<?php echo esc_attr( $element_id ); ?>.thb-office-locations-style1 h5 {
					color: <?php echo esc_attr( $heading_color ); ?>;
				}
			<?php } ?>
		</style>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_location_parent', 'thb_location_parent' );

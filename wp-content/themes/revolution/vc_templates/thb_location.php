<?php function thb_location( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_location', $atts );
	extract( $atts );

	if ( $marker_image ) {
		$marker        = wp_get_attachment_image_src( $marker_image, 'full' );
		$marker_image  = $marker[0];
		$marker_size   = array( $marker[1], $marker[2] );
		$retina_marker = $retina_marker;
	} else {
		$marker_image  = Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/pin.png';
		$marker_size   = array( 80, 108 );
		$retina_marker = true;
	}

	$options = array(
		'marker_image'       => $marker_image,
		'marker_title'       => esc_attr( $marker_title ),
		'marker_description' => wp_kses_post( $marker_description ),
		'marker_size'        => $marker_size,
		'retina_marker'      => esc_attr( $retina_marker ),
		'latitude'           => esc_attr( $latitude ),
		'longitude'          => esc_attr( $longitude ),
	);

	ob_start(); ?>
	<input class="thb-location" type="hidden" data-option="<?php echo esc_attr( wp_json_encode( $options ) ); ?>" />
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_location', 'thb_location' );


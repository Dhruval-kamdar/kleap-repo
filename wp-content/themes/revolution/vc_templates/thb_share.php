<?php function thb_share( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_share', $atts );
	extract( $atts );
	ob_start();
	$sharing_type_override = array();
	if ( $facebook ) {
		array_push( $sharing_type_override, 'facebook' );
	}
	if ( $twitter ) {
		array_push( $sharing_type_override, 'twitter' );
	}
	if ( $pinterest ) {
		array_push( $sharing_type_override, 'pinterest' );
	}
	if ( $linkedin ) {
		array_push( $sharing_type_override, 'linkedin' );
	}
	if ( $vkontakte ) {
		array_push( $sharing_type_override, 'vkontakte' );
	}
	if ( $whatsapp ) {
		array_push( $sharing_type_override, 'whatsapp' );
	}
	if ( $facebook_messenger ) {
		array_push( $sharing_type_override, 'facebook-messenger' );
	}
	do_action( 'thb_portfolio_share', $text, $sharing_type_override, $thb_alignment, $style );

	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_share', 'thb_share' );

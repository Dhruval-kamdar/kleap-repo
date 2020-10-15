<?php function thb_portfolio_attribute( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_portfolio_attribute', $atts );
	extract( $atts );

	$out = '';
	ob_start();
	$element_id = uniqid( 'thb-attributes-' );
	do_action( 'thb_portfolio_attributes', $style, $thb_columns, $animation, $element_id );

	if ( $thb_label_color || $thb_label_font_size || $thb_text_font_size ) {
		?>
	<style>
		<?php if ( $thb_label_color ) { ?>
		#<?php echo esc_attr( $element_id ); ?>.portfolio-attributes h6 {
			color: <?php echo esc_attr( $thb_label_color ); ?>;
		}
	<?php } ?>
		<?php if ( $thb_label_font_size ) { ?>
		#<?php echo esc_attr( $element_id ); ?>.portfolio-attributes h6 {
			font-size: <?php echo esc_attr( $thb_label_font_size ); ?>;
		}
	<?php } ?>
		<?php if ( $thb_text_font_size ) { ?>
			#<?php echo esc_attr( $element_id ); ?>.portfolio-attributes p {
				font-size: <?php echo esc_attr( $thb_text_font_size ); ?>;
			}
	<?php } ?>
	</style>
		<?php
	}
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_portfolio_attribute', 'thb_portfolio_attribute' );

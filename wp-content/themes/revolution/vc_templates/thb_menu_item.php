<?php function thb_menu_item( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_menu_item', $atts );
	extract( $atts );

	$element_id = 'thb-menu-item-' . wp_rand( 10, 999 );

	$el_class[] = 'thb-menu-item';

	$out = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<div class="thb-menu-item-parent">
			<div class="thb-menu-title"><h6><?php echo esc_html( $title ); ?></h6></div>
			<div class="thb-menu-line"></div>
			<div class="thb-menu-price"><?php echo esc_html( $price ); ?></div>
		</div>
		<?php if ( $description ) { ?>
			<div class="thb-menu-description">
				<?php echo wp_kses_post( $description ); ?>
			</div>
		<?php } ?>
	</div>
	<?php

	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_menu_item', 'thb_menu_item' );

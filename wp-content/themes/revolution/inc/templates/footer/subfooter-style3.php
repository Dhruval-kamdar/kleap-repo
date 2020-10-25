<?php
	$subfooter_classes[]   = 'subfooter';
	$subfooter_classes[]   = 'style1';
	$subfooter_classes[]   = ot_get_option( 'footer_color', 'light' );
	$subfooter_classes[]   = 'subfooter-full-width-' . ot_get_option( 'subfooter_full_width', 'off' );
	$full_menu_hover_style = ot_get_option( 'full_menu_hover_style', 'thb-standard' );
	$subfooter_social_link = ot_get_option( 'subfooter_social_link' );
	$subfooter_menu        = ot_get_option( 'subfooter_menu' );
?>
<!-- Start subfooter -->
<div class="<?php echo esc_attr( implode( ' ', $subfooter_classes ) ); ?>">
	<div class="row subfooter-row">
		<div class="small-12 medium-6 columns text-center medium-text-left">
			<?php echo do_shortcode( wp_kses_post( ot_get_option( 'subfooter_text' ) ) ); ?>
		</div>
		<div class="small-12 medium-6 columns text-center medium-text-right">
			<?php
			if ( $subfooter_menu ) {
				wp_nav_menu(
					array(
						'menu'       => ot_get_option( 'subfooter_menu' ),
						'depth'      => 1,
						'menu_class' => 'thb-full-menu ' . $full_menu_hover_style,
					)
				); }
			?>
			<?php do_action( 'thb_footer_payment' ); ?>
		</div>
	</div>
</div>
<!-- End Subfooter -->

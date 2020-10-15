<?php
	$subheader_color       = ot_get_option( 'subheader_color' );
	$subheader_class[]     = 'subheader style2';
	$subheader_class[]     = $subheader_color;
	$header_style          = ot_get_option( 'header_style' );
	$full_menu_hover_style = ot_get_option( 'full_menu_hover_style', 'thb-standard' );
	$subheader_menu        = ot_get_option( 'subheader_menu' );
if ( in_array( $header_style, array( 'style7', 'style8' ), true ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( implode( ' ', $subheader_class ) ); ?>">
	<div class="row align-middle">
		<div class="small-12 medium-6 columns subheader-left">
			<?php
			if ( $subheader_menu ) {
				wp_nav_menu(
					array(
						'menu'       => $subheader_menu,
						'container'  => false,
						'depth'      => 1,
						'menu_class' => 'thb-full-menu ' . $full_menu_hover_style,
					)
				); }
			?>
		</div>
		<div class="small-12 medium-6 columns subheader-right">
			<?php
			do_action( 'thb_social_links', ot_get_option( 'subheader_social_link' ), false, true );
			?>
		</div>
	</div>
</div>

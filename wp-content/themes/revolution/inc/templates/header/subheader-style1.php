<?php
	$subheader_color = ot_get_option( 'subheader_color' );

	$subheader_class[] = 'subheader style1';
	$subheader_class[] = $subheader_color;

	$header_style   = ot_get_option( 'header_style' );
	$subheader_text = ot_get_option( 'subheader_text' );

if ( in_array( $header_style, array( 'style7', 'style8' ), true ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( implode( ' ', $subheader_class ) ); ?>">
	<div class="row align-middle">
		<div class="small-12 medium-6 columns subheader-left">
		<?php if ( $subheader_text ) { ?>
			<?php echo do_shortcode( $subheader_text ); ?>
		<?php } ?>
		</div>
		<div class="small-12 medium-6 columns subheader-right">
			<?php do_action( 'thb_social_links', ot_get_option( 'subheader_social_link' ), false, true ); ?>
		</div>
	</div>
</div>

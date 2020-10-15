<?php
	$footer_classes[] = 'footer';
	$footer_classes[] = ot_get_option( 'footer_color', 'light' );
	$footer_classes[] = 'footer-full-width-' . ot_get_option( 'footer_full_width', 'off' );
?>
<!-- Start Footer -->
<footer id="footer" class="<?php echo esc_attr( implode( ' ', $footer_classes ) ); ?>">
	<?php do_action( 'thb_footer_logo' ); ?>
	<?php do_action( 'thb_page_content', apply_filters( 'thb_footer_page_content', ot_get_option( 'footer_top_content' ) ) ); ?>
	<div class="row footer-row">
		<?php do_action( 'thb_footer_columns' ); ?>
	</div>
</footer>
<!-- End Footer -->

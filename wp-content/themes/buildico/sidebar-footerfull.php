<?php
/**
 * Sidebar setup for footer full.
 *
 * @package buildico
 */

if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
if( get_post_meta( get_the_ID(), "custom_page_options", true ) ){
     $buildico_page_settings = get_post_meta( get_the_ID(), "custom_page_options", true );
}else{
     $buildico_page_settings = array();
}
if( ! empty( $buildico_page_settings['footer_widget_text_color'] ) && ! empty('default' != $buildico_page_settings['footer_widget_text_color']) ){
    $buildico_footer_text_color = $buildico_page_settings['footer_widget_text_color'];
}else{
    $buildico_footer_text_color = '';
}
?>

<?php if ( is_active_sidebar( 'footerfull' ) ) : ?>

	<!-- ******************* The Footer Full-width Widget Area ******************* -->

	<div class="wrapper footer-widget-section <?php echo esc_attr($buildico_footer_text_color); ?>">

		<div class="<?php echo esc_attr( $container ); ?>" id="footer-full-content" tabindex="-1">

			<div class="row footer-widgets">

				<?php dynamic_sidebar( 'footerfull' ); ?>

			</div>

		</div>

	</div><!-- #wrapper-footer-full -->

<?php endif; ?>

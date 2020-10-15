<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package buildico
 */

global $buildico_page_settings;
if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
$footer_text = wt_get_option( 'footer_copy_text' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="footer-wrap" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">
                        <?php
                        if( ! empty( $footer_text ) ){
                            echo esc_html( $footer_text );
                        }else{
                            echo esc_html__( '&copy; Copyright 2017 WowThemez - All Rights Reserved', 'buildico' );
                        } ?>
					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php if( wt_get_option('gototop_btn') == true ) : ?>
	<a href="#" id="scroll-top" class="scroll-to-top"><i class="ti-angle-up"></i></a>
<?php endif; ?>

<?php wp_footer(); ?>

</body>

</html>

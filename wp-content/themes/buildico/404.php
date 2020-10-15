<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package buildico
 */

get_header();

if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
$icon_color = wt_get_option( 'error_page_icon_color' );
$error_page_title = wt_get_option( 'error_page_title' );
$error_page_content = wt_get_option( 'error_page_content' );
$error_home_btn = wt_get_option( 'error_home_btn' );
$error_home_text = wt_get_option( 'error_home_text' );
?>


<div class="wrapper" id="error-404-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main">

					<section class="error-404 text-center not-found">

						<div class="page-content">
							<i style="color: <?php echo esc_attr( $icon_color ); ?>;" class="fa fa-exclamation-circle"></i>
							<h1><?php if( ! empty( $error_page_title ) ){
								 echo esc_html( $error_page_title );
							}else{
								echo esc_html__( '404 Not Found!', 'buildico' );
							} ?></h1>
							<p><?php if( ! empty( $error_page_content ) ){
								echo esc_html( $error_page_content );
							}else{
								echo esc_html__( 'The page you are looking for might have been removed had its name changed or is temporarily unavailable!', 'buildico' );
							} ?></p>
							<?php if( $error_home_btn === true ) : ?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-to-home"><?php echo esc_html( $error_home_text ); ?></a>
							<?php endif; ?>

						</div><!-- .page-content -->

					</section><!-- .error-404 -->

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>

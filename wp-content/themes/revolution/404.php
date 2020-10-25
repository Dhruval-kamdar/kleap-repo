<?php get_header(); ?>
<?php
	$fourofour_page_content = ot_get_option( '404_page_content' );
if ( $fourofour_page_content ) {
	do_action( 'thb_page_content', $fourofour_page_content );
} else {
	?>
	<div class="page-404">
		<div>
			<h1><?php esc_html_e( '404', 'revolution' ); ?></h1>
			<p><?php esc_html_e( 'We are sorry, but the page you are looking for cannot be found.', 'revolution' ); ?><br /><?php esc_html_e( 'You might try searching our site.', 'revolution' ); ?></p>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn style2 medium  black pill-radius arrow-enabled"><span><?php esc_html_e( 'BACK TO HOME', 'revolution' ); ?></span><?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?></a>
		</div>
	</div>
	<?php
}
get_footer();

<?php
/**
 * The template for displaying search results pages.
 *
 * @package buildico
 */

get_header();

if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
// Page Header Alignment
$ph_align = wt_get_option('page_header_align');
if( ! empty( $ph_align ) ){
	if( 'left' === $ph_align ){
		$page_align = 'col-md-7 text-left';
	}elseif ( 'right' === $ph_align ) {
		$page_align = 'col-md-7 offset-md-5 text-right';
	}else{
		$page_align = 'col-md-12 text-center';
	}
}else{
	$page_align = 'col-md-7';
}
?>

<div class="page-header">
	<div class="display-table">
		<div class="table-cell">
			<div class="<?php echo esc_attr( $container ); ?>">
				<div class="row">
					<div class="<?php echo esc_attr( $page_align ); ?>">
						<h1 class="page-title"><?php printf(
							/* translators:*/
							 esc_html__( 'Search Results for: %s', 'buildico' ),
							'<span>' . get_search_query() . '</span>' ); ?></h1>
							<?php
							if( wt_get_option('breadcrumbs_enable') == true ){
								if(function_exists( 'bcn_display' ) ) {
									echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
								    bcn_display();
								    echo '</div>';
								}
							}
							?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.page-header -->

<div class="wrapper" id="wrapper-index">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check and opens the primary div -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
				<div class="row blog-grid">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'loop-templates/content', 'search' );
						?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'loop-templates/content', 'none' ); ?>

				<?php endif; ?>
				</div>

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php buildico_pagination(); ?>

		</div><!-- #primary -->

		<!-- Do the right sidebar check -->
		<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>
		

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>

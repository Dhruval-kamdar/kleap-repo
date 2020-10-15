<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
$pageheader_hide = wt_get_option( 'archive_pageheader' );
$pageheader_bg_img = wt_get_option( 'archive_bg_img' );
$ph_bg_img_src = wp_get_attachment_image_src( $pageheader_bg_img, 'large');
?>

<?php if( $pageheader_hide === true ) : ?>
<div class="page-header" style="<?php if( ! empty( $pageheader_bg_img ) ) : ?>background-image: url( <?php echo esc_url( $ph_bg_img_src[0] ); ?> ); <?php endif; ?>">
	<div class="<?php echo esc_attr( $container ); ?>">
		<div class="<?php echo esc_attr( $page_align ); ?>">
			<?php 
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
				
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
</div><!-- /.page-header -->
<?php endif; ?>

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
						get_template_part( 'loop-templates/content', get_post_format() );
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
